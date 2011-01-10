<?php
/**
* Copyright (c) 2011 Greg Riccardi, Fredrik Ronquist.
* All rights reserved. This program and the accompanying materials
* are made available under the terms of the GNU Public License v2.0
* which accompanies this distribution, and is available at
* http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
* 
* Contributors:
*   Fredrik Ronquist - conceptual modeling and interaction design
*   Austin Mast - conceptual modeling and interaction design
*   Greg Riccardi - initial API and implementation
*   Wilfredo Blanco - initial API and implementation
*   Robert Bruhn - initial API and implementation
*   Christopher Cprek - initial API and implementation
*   David Gaitros - initial API and implementation
*   Neelima Jammigumpula - initial API and implementation
*   Karolina Maneva-Jakimoska - initial API and implementation
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

require_once('../configuration/app.server.php');
include_once('newKeywordsData.php');
include_once('tsnFunctions.php');

//TODO move this out of the admin directory
// Define functions that perform keyword update on a single object

function updateBaseKeywords ($id, $massUpdate = false){
	global $xml_pattern, $xml_replace, $text_pattern, $text_replace;
	global $updateStmt, $SQL_update, $keywordXml, $keywordText, $dom;
	global $keywordsTempStmt, $numCleared, $taxaUpdateStmt;
	global $keywordsTempClearSql, $keywordsBaseObjectUpdateSql, $keywordsUpdateSql;
	global $keywordsMissingSql, $keywordsInsertSql, $keywordsTempParams;

	$db = connect();

	echo "UBK $id\n";

	// update the keywords and related fields for one object
	$dom = new DomDocument('1.0', 'utf-8');
	$morphbank = $dom->appendChild($dom->createElement("morphbank"));
	//create imagealttext! GR
	$SQL_objectType = "SELECT objectTypeId from BaseObject where id = $id";
	$objectTypeId = $db->getOne($SQL_objectType);
	isMdb2Error($objectTypeId, "No object for id $id");
	echo "type: $objectTypeId\n";
	if (!$objectTypeId){
		// no object for id
		echo("no object for $id");
		return;
	}
	$keywordXml = $morphbank->appendChild($dom->createElement(strtolower($objectTypeId)));
	$keywordText = "";

	if ($objectTypeId == "MbCharacter") {
		//TODO will not work because of dependence on $row and $link GR
		CharacterKeywords($link, $id, "script");
		return;
	}
	if ($objectTypeId ==  "Otu"){
		//TODO will not work because of dependence on $row GR
		$checkQuery = 'SELECT boId FROM Taxa WHERE boId='.$id;
		//			$result = mysqli_query($link, $checkQuery);
		//			if (mysqli_num_rows($result) != 1) {)
		$result = $db->query($checkQuery);
		if(PEAR::isError($result)){
			die($result->getUserInfo()." $objectTypeId\n");
		}

		if ($result->numrows() != 1) {
			//call the Taxa Procedure to create Taxa record
			//var_dump($row);
			//exit(0);
			$query = "CALL `TaxaInsert`($id,NULL,'".$row['name']
			."',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,"
			."NULL,NULL,NULL,".$row['groupid'].",".$row['userid'].",NOW(),NULL,'Otu')";
			//$result = mysqli_multi_query($link,$query);
			echo "OTU query: $query\n\n";
			$result = $db->_query($query);
			if(PEAR::isError($result)){
				die($result->getUserInfo()." $objectTypeId\n");
			}
			//update the Taxa keywords and BaseObject insert
			TaxaKeywords($link, NULL, $id);
		}
		return;
	}

	// Rest of the method is solid
	echo "starting for object $id\n";
	$SQL_keywords= getObjectKeywordQuery($objectTypeId);
	if ($SQL_keywords){
		$params = array($id);
		$keywords_results = $SQL_keywords->execute($params);
		if(PEAR::isError($keywords_results)){
			die($keywords_results->getUserInfo()." $objectTypeId\n");
		}
		echo "getting keywords\n";
		$keywords = $keywords_results->fetchRow(MDB2_FETCHMODE_ASSOC);
		// why more than one row? vernacular?
		foreach($keywords as $field => $value){
			//echo "field $field value $value\n";
			addKeywords($field, $value);
		}
		echo "before taxon names\n";
		
		$taxonNames = getTaxonomicNamesByType($id, $objectTypeId);
		addKeywords('taxonomicNames', $taxonNames);
		echo "after taxon names\n";
		$vernacularNames = getVernacularNamesByType($id, $objectTypeId);
		echo "after vern names\n";
		addKeywords('vernacularNames', $vernacularNames);
		$externalLinkWords = getExternalLinkWords($id);
		addKeywords('externalLinkWords', $externalLinkWords);
echo "after external\n";
		// create imagealttext! GR
		$imageAltText = 'Morphbank biodiversity NSF FSU Florida State University ';
		$imageAltText .= $keywords['groupname'].' ';
		$imageAltText .= $keywords['name'].' ';
		$imageAltText .= $keywords['imagingtechnique'].' ';
		$imageAltText .= $keywords['imagingpreparationtechnique'].' ';
		$imageAltText .= $keywords['specimenpart'].' ';
		$imageAltText .= $keywords['viewangle'].' ';
		$imageAltText .= $keywords['sex'].' ';
		$imageAltText .= $keywords['form'].' ';
		//$imageAltText .= $keywords['taxonomicnames'].' ';
		$imageAltText .= $keywords['collectorname'].' ';
		$imageAltText .= $keywords['developmentalstage'].' ';
		$imageAltText .= $keywords['continentocean'].' ';
		$imageAltText .= $keywords['locality'].' ';
		$imageAltText .= $keywords['country'].' ';
		$imageAltText .= $keywords['affiliation'].' ';
		$imageAltText .= $taxonNames . $vernacularNames;
		//add vernacular names from ITIS table 'Vernacular'
		//$tsn = $keywords['tsnid'];
		$dom->formatOutput = true;
		$xml_output = $dom->saveXML();
		if(PEAR::isError($xml_output)){
			die("\n" . $xml_output->getMessage() . " $SQL_update\n");
		}
		echo "mass update $keywordText\n$xml_output\n";//

		if ($massUpdate){// insert into KeywordsTemp
			$params = array($id, $keywordText, $xml_output, $imageAltText);
			$result = $keywordsTempStmt->execute($params);
			isMdb2Error($result,"keywords temp stmt");
		} else {
			$params = array($keywordText, $imageAltText, $xml_output, $id);
			$update_results = $updateStmt->execute($params);
			if(PEAR::isError($update_results)){
				die("\n" . $update_results->getMessage() . " $SQL_update\n");
			}
		}
	}
}

function addKeywords($field, $value){
	global $keywordXml, $keywordText, $text_pattern, $text_replace, $dom;
	if (!empty($value)) {
		$keywordNode = $keywordXml->appendChild($dom->createElement($field));
		$keywordNode->appendChild($dom->createTextNode(preg_replace($text_pattern, $text_replace, $value)));
		$keywordText .= " ".$value;
	}
}

function updateKeywords($query){
	global $updateStmt, $SQL_update, $keywordXml, $keywordText, $dom;
	global $keywordsTempStmt, $numCleared, $taxaUpdateStmt;
	global $keywordsTempClearSql, $keywordsBaseObjectUpdateSql, $keywordsUpdateSql;
	global $keywordsMissingSql, $keywordsInsertSql;
	$massUpdate = true;
	$db = connect();

	//clear KeywordsTemp
	$result = $db->exec($keywordsTempClearSql);
	isMdb2Error($result,$keywordsTempClearSql);

	//find missing rows in Keywords
	$result = $db->exec($keywordsMissingSql);
	isMdb2Error($result,$keywordsMissingSql);
	echo "Time after find missing rows: ".date("H:i:s")."\n";

	//add missing rows to Keywords
	$result = $db->exec($keywordsInsertSql);
	isMdb2Error($result,$keywordsInsertSql);
	echo "Time after add missing rows: ".date("H:i:s")."\n";

	//clear KeywordsTemp
	$result = $db->exec($keywordsTempClearSql);
	isMdb2Error($result,$keywordsTempClearSql);

	$ids = $db->query($query);
	isMdb2Error($ids,$query);
	echo "query: $query num rows: ".$ids->numRows()."\n";
	// harvest keywords into KeywordsTemp
	while($id = $ids->fetchOne()) {
		echo "id: $id\n";
		updateBaseKeywords($id, $massUpdate);
		$updateCount++;
		if ($updateCount % 1000 == 0){
			echo "Number so far: $updateCount last id $id \n";
		}
	}
	$ids -> free();
	echo "Time after recreating keywords: ".date("H:i:s")."\n";
	// update BaseObject from KeywordsTemp
	$result = $db->exec($keywordsBaseObjectUpdateSql);
	isMdb2Error($result,$keywordsBaseObjectUpdateSql);

	echo "BaseObjects update finished: $updateCount objects updated!\n";
	echo "Time after  updating BaseObject: ".date("H:i:s")."\n";

	// update Keywords from KeywordsTemp
	$result = $db->exec($keywordsUpdateSql);
	isMdb2Error($result,$keywordsUpdateSql);

	// reindex Keywords
	$result = $db->exec('repair table Keywords quick');
	isMdb2Error($result,$keywordsTempClearSql);
	echo "Time after Keywords update: ".date("H:i:s")."\n";

}

function updateGeolocated(){

	echo "Beginning Geolocated update\n";
	$db = reconnect();
	$updateGeoResult = $db->executeStoredProc('SetGeolocated');
	if(PEAR::isError($updateGeoResult)){
		echo("\nset Geolocated failed: " . $updateGeoResult->getUserInfo()." \n");
	}
}


//TODO remove keywords code from this module

/**
 * Update keywords in BaseObject and insert/update keyword table
 * @param $link
 * @param $id
 * @param $keysSql
 * @param $operation
 * @return unknown_type
 */
function Keywords($link, $id, $keysSql, $operation = "insert"){
	KeywordsXML($link, $id, NULL, $operation);
}

/**
 * Updates keywords in BaseObject and insert/update keywords table
 * @param $link database connection
 * @param $id id of MB object
 * @param $objectType Morphbank Object name (Table in the database)
 * @param $operation Default - Insert. The keywords are either inserted or updated
 * 	based on this value.
 * @return unknown_type
 */
function KeywordsXML($link, $id, $objectType, $operation = "insert") {
	updateKeywordsTable($id, $operation);
}


function updateKeywordsTable($id, $operation = "insert") {
	if (empty($id)) return;
	$db = connect();
	updateBaseKeywords($id);

	$operation = strtolower($operation);
	if ($operation == "update" || $operation=="delete"){
		// delete then insert
		$keywordSql = 'DELETE FROM Keywords WHERE id ='.$id;
		$deleteResult = $db->query($keywordSql);
		if(PEAR::isError($deleteResult)){
			echo($deleteResult->getUserInfo()." $sql\n");
		}
	}
	//TODO add fields to keywords (dateCreated and dateModified). Get data from BaseObject
	// dateCreated and dateLastModified
	if ($operation == "insert" || $operation=="update"){
		$keywordInsert =  "INSERT INTO Keywords(id, userId, groupId, dateToPublish, objectTypeId,"
		." keywords, xmlKeywords, submittedBy)"
		." SELECT id, userId, groupId, dateToPublish, objectTypeId,"
		." keywords, xmlKeywords, submittedBy"
		." FROM BaseObject WHERE id = $id";
		$keywordResult = $db->query($keywordInsert);
		if(PEAR::isError($keywordResult)){
			echo($keywordResult->getUserInfo()." $sql\n");
		}
	}
}


/**
 * This function creates the keywords for the Taxa table that
 *  contains taxonomy and OTU records
 * @param $link
 * @param $tsn - the taxon number assigned to the new taxon
 * @param $taxaId - baseObjectId passed for newly created OTU or taxon
 * @return unknown_type
 */
function TaxaKeywords($link,$tsn,$taxaId){
	//TODO make taxa keywords consistent with other keywords
	global  $xml_pattern, $xml_replace, $text_pattern, $text_replace;
	$db = connect();

	if (empty($taxaId)){
		$sql = "select id from TaxonConcept where tsn = $tsn";
		$result = $db -> query($sql);
		if(PEAR::isError($result)){
			echo($result->getUserInfo() . $sql . "\n");
			die("TSN '$tsn' does not match TaxonConcept");
		} else {
			$row = $result-> fetchRow();
			$taxaId = $row[0];
		}
	}
	$baseObjectFields = "BaseObject.id, BaseObject.userId, BaseObject.groupId, BaseObject.objectTypeId,";
	$baseObjectFields .=" BaseObject.submittedBy, User.name, Groups.groupName ";

	$taxaFields = "tsn, boId, scientificName, taxon_author_id, taxon_author_name, status,";
	$taxaFields .="nameSource, parent_tsn, parent_name, kingdom_id, kingdom_name, rank_id,";
	$taxaFields .="rank_name, imagesCount, nameType, publicationId";

	$publicationFields = "Publication.doi, Publication.publicationType, Publication.author, Publication.publicationTitle,";
	$publicationFields .="Publication.month, Publication.publisher, Publication.school, Publication.series, Publication.note,";
	$publicationFields .="Publication.organization, Publication.institution, Publication.title, Publication.volume, Publication.year,";
	$publicationFields .="Publication.isbn, Publication.issn, Groups.groupName, User.name AS submittedBy ";

	$publicationJoin = 'Publication LEFT JOIN BaseObject ON Publication.id = BaseObject.id '
	. 'LEFT JOIN Groups ON BaseObject.groupId = Groups.id '
	. 'LEFT JOIN User ON BaseObject.userId = User.id ';

	$baseObjectJoin = ' BaseObject LEFT JOIN User ON BaseObject.submittedby=User.id '
	. 'LEFT JOIN Groups ON BaseObject.groupId=Groups.id';

	$extLinkJoin = 'ExternalLinkObject inner join ExternalLinkType '
	. 'on ExternalLinkObject.extLinkTypeId =ExternalLinkType.linkTypeId';

	$SQL_search = "SELECT ".$taxaFields." From Taxa WHERE ";
	$keywordText = "";
	if($taxaId!=NULL) {
		$SQL_search .="boId=".$taxaId;
	} else {
		$SQL_search .="tsn=".$tsn;
	}
	$result = $db->query($SQL_search);
	if(PEAR::isError($result)){
		echo($result->getUserInfo()." $sql\n");
	}
	else{
		$dom = new DomDocument('1.0', 'utf-8');
		$morphbank = $dom->appendChild($dom->createElement("morphbank"));

		$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
		foreach($row as $field => $value){
			if (strcmp(trim($value), "")){
				$keyword_entry = $morphbank->appendChild($dom->createElement($field));
				$keyword_entry->appendChild($dom->createTextNode(preg_replace($text_pattern, $text_replace, $value)));
				$keywordText .= " ".$value;
			}
		}

		if(!empty($row['publicationId'])){
			$SQL_extended = "SELECT ".$publicationFields. " FROM ".$publicationJoin
			." WHERE Publication.id=".$row['publicationId'];

			$supplementary_results = $db->query($SQL_extended);
			if(PEAR::isError($supplementary_results)){
				echo($supplementary_results->getUserInfo()." $sql\n");
			} else {
				$additional_keywords = $supplementary_results->fetchRow(MDB2_FETCHMODE_ASSOC);
				foreach($additional_keywords as $field => $value){
					if (strcmp(trim($value), "")){
						if (strcmp(trim($value), "0000-00-00 00:00:00")){
							$keyword_entry = $morphbank->appendChild($dom->createElement($field));
							$keyword_entry->appendChild($dom->createTextNode(preg_replace($text_pattern, $text_replace, $value)));
							$keywordText .= " ".$value;
						}
					}
				}
			}
		}

		$SQL_extended = "SELECT ".$baseObjectFields." FROM ".$baseObjectJoin." WHERE BaseObject.id=$taxaId";
		$supplementary_results = $db->query($SQL_extended);
		if(PEAR::isError($supplementary_results)){
			echo($supplementary_results->getUserInfo()." $sql\n");
		} else {
			$additional_keywords = $supplementary_results->fetchRow(MDB2_FETCHMODE_ASSOC);
			foreach($additional_keywords as $field => $value){
				if (strcmp(trim($value), "")){
					$keyword_entry = $morphbank->appendChild($dom->createElement($field));
					$keyword_entry->appendChild($dom->createTextNode(preg_replace($text_pattern, $text_replace, $value)));
					$keywordText .= " ".$value;
				}
			}
		}

		$SQL_extended = "SELECT vernacular_name, language FROM Vernacular where tsn=$tsn";
		$supplementary_results = $db->query($SQL_extended);
		if(PEAR::isError($supplementary_results)){
			echo($supplementary_results->getUserInfo()." $sql\n");
		} else {
			if($supplementary_results->numrows()>0){
				while($additional_keywords = $supplementary_results->fetchRow(MDB2_FETCHMODE_ASSOC)) {
					foreach($additional_keywords as $field => $value){
						if (strcmp(trim($value), "")){
							$keyword_entry = $morphbank->appendChild($dom->createElement($field));
							$keyword_entry->appendChild($dom->createTextNode(preg_replace($text_pattern, $text_replace, $value)));
							$keywordText .= " ".$value;
						}
					}
				}
			}
		}

		$SQL_extended = "SELECT name,label from ".$extLinkJoin." where mbId=$taxaId";
		$supplementary_results = $db->query($SQL_extended);
		if(PEAR::isError($supplementary_results)){
			echo($supplementary_results->getUserInfo()." $sql\n");
		} else {
			if($supplementary_results->numrows()>0){
				while($additional_keywords = $supplementary_results->fetchRow(MDB2_FETCHMODE_ASSOC)) {
					foreach($additional_keywords as $field => $value){
						if (strcmp(trim($value), "")){
							$keyword_entry = $morphbank->appendChild($dom->createElement($field));
							$keyword_entry->appendChild($dom->createTextNode(preg_replace($text_pattern, $text_replace, $value)));
							$keywordText .= " ".$value;
						}
					}
				}
			}
		}

		//if the keywords are created for Otu information about
		//taxa or specimen is added to the keywords filed
		if($tsn==NULL && $taxaId!=NULL){
			$SQL_extended = "SELECT objectTypeId, objectTitle, objectid FROM CollectionObjects "
			. "where collectionId=$taxaId";
			$supplementary_results = $db->query($SQL_extended);
			if(PEAR::isError($supplementary_results)){
				echo($supplementary_results->getUserInfo()." $sql\n");
			} else {
				if($supplementary_results->numrows()>0){
					while($additional_keywords = $supplementary_results->fetchRow(MDB2_FETCHMODE_ASSOC)) {
						foreach($additional_keywords as $field => $value){
							if (strcmp(trim($value), "")){
								$keyword_entry = $morphbank->appendChild($dom->createElement($field));
								$keyword_entry->appendChild($dom->createTextNode(preg_replace($text_pattern, $text_replace, $value)));
								$keywordText .= " ".$value;
							}
						}
						if($additional_keywords['objectTypeId']=="TaxonConcept"){
							$SQL_simple = "SELECT tsn from Taxa where boId=".$additional_keywords['objectid'];
							$simple_result = $db->query($SQL_simple);

							if(PEAR::isError($simple_result)){
								echo($simple_result->getUserInfo()." $sql\n");
							} else {
								$simple_row = $simple_result->fetchRow(MDB2_FETCHMODE_ASSOC);
								foreach($simple_row as $field => $value){
									$keyword_entry = $morphbank->appendChild($dom->createElement($field));
									$keyword_entry->appendChild($dom->createTextNode(preg_replace($text_pattern, $text_replace, $value)));
								}
							}
						}
					}
				}
			}
		}

		$dom->formatOutput = true;
		$xml_output = $dom->saveXML();

		global $taxaUpdateStmt;
		$params = array($keywordText, $id);
		$update_results = $taxaUpdateStmt->execute($params);
		if(PEAR::isError($SQL_update)){
			echo($SQL_update->getUserInfo()." $sql\n");
		}
		// use global prepared statement for update to BaseObject
		global $updateStmt;
		$params = array($keywordText, NULL, $xml_output, $id);
		$update_results = $updateStmt->execute($params);
		if(PEAR::isError($update_results)){
			die("\n" . $update_results->getMessage() . " $SQL_update\n");
		}
	}
}

/***************************************************************************
 Description: This function creates/updates the keywords for the Character
 inserts
 Input parameters: $link - connection link
 $id - the BaseObject id assigned to this character
 $operation - type of operation preformed it can have 2 values
 'Insert' or 'Update'

 dateCreated: Oct 23rd 2007
 Author: Karolina Maneva-Jakimoska, Software Developer
 ***************************************************************************/
function CharacterKeywords($link, $id, $operation){
	global $db;
	$xml_pattern = array('/\"/');
	$xml_replace = array('\\\"');
	$text_pattern = array('/\s\s+/');
	$text_replace = array(' ');

	$baseObjectFields = "BaseObject.id, BaseObject.userId, BaseObject.groupId, BaseObject.objectTypeId,";
	$baseObjectFields .=" BaseObject.submittedBy, User.name, Groups.groupName ";

	$characterFields = "MbCharacter.id, MbCharacter.label, MbCharacter.characterNumber, MbCharacter.discrete, MbCharacter.ordered,";
	$characterFields .="MbCharacter.publicationId, MbCharacter.pubComment ";

	$stateFields = "CharacterState.charStateValue ";

	$publicationFields = "Publication.doi, Publication.publicationType, Publication.author, Publication.publicationTitle,";
	$publicationFields .="Publication.month, Publication.publisher, Publication.school, Publication.series, Publication.note,";
	$publicationFields .="Publication.organization, Publication.institution, Publication.title, Publication.volume, Publication.year,";
	$publicationFields .="Publication.isbn, Publication.issn, Groups.groupName, User.name AS submittedBy ";

	$collectionObjectFields = "CollectionObjects.objectid, CollectionObjects.objectOrder, CollectionObjects.objectTypeId, ";
	$collectionObjectFields .="CollectionObjects.objectRole, CollectionObjects.objectRole";

	$publicationJoin = 'Publication LEFT JOIN BaseObject ON Publication.id = BaseObject.id LEFT JOIN Groups ON BaseObject.groupId = Groups.id';
	$publicationJoin .=' LEFT JOIN User ON BaseObject.userId = User.id ';

	$baseObjectJoin = ' BaseObject LEFT JOIN User ON BaseObject.submittedby=User.id LEFT JOIN Groups ON BaseObject.groupId=Groups.id';

	$dom = new DomDocument('1.0', 'utf-8');
	$morphbank = $dom->appendChild($dom->createElement("morphbank"));

	$SQL_extended = "SELECT ".$baseObjectFields." FROM ".$baseObjectJoin." WHERE BaseObject.id=".$id;
	$supplementary_results = $db->query($SQL_extended);
	if(PEAR::isError($supplementary_results)){
		echo($supplementary_results->getUserInfo()." $sql\n");
	} else {
		$additional_keywords = $supplementary_results->fetchRow(MDB2_FETCHMODE_ASSOC);
		foreach($additional_keywords as $field => $value){
			if (strcmp(trim($value), "")){
				$keyword_entry = $morphbank->appendChild($dom->createElement($field));
				$keyword_entry->appendChild($dom->createTextNode(preg_replace($text_pattern, $text_replace, $value)));
			}
		}
	}

	$mbcharacter = $morphbank->appendChild($dom->createElement("mbcharacter"));
	$SQL_search = "SELECT ".$characterFields." From MbCharacter WHERE id=".$id;
	$results = $db->query($SQL_search);
	if(PEAR::isError($results)){
		echo($results->getUserInfo()." $sql\n");
	} else {
		$row = $results->fetchRow(MDB2_FETCHMODE_ASSOC);
		foreach($row as $field => $value){
			if (strcmp(trim($value), "")){
				$keyword_entry = $mbcharacter->appendChild($dom->createElement($field));
				$keyword_entry->appendChild($dom->createTextNode(preg_replace($text_pattern, $text_replace, $value)));
			}
		}

		if($row['publicationId']!=NULL && $row['publicationId']!="" && $row['publicationId']!=0){
			$SQL_extended = "SELECT ".$publicationFields. " FROM ".$publicationJoin." WHERE Publication.id=".$row['publicationId'];
			$publication = $mbcharacter->appendChild($dom->createElement("publication"));
			$supplementary_results = $db->query($SQL_extended);
			if(PEAR::isError($supplementary_results)){
				echo($supplementary_results->getUserInfo()." $sql\n");
			} else {
				$additional_keywords = $supplementary_results->fetchRow(MDB2_FETCHMODE_ASSOC);
				foreach($additional_keywords as $field => $value){
					if (strcmp(trim($value), "")){
						if (strcmp(trim($value), "0000-00-00 00:00:00")){
							$keyword_entry = $publication->appendChild($dom->createElement($field));
							$keyword_entry->appendChild($dom->createTextNode(preg_replace($text_pattern, $text_replace, $value)));
						}
					}
				}
			}
		}
		$object_type = $mbcharacter ->appendChild($dom->createElement("objects"));
		//creating keywords for the states
		$SQL_extended = "SELECT ".$collectionObjectFields." FROM CollectionObjects WHERE collectionId=".$row['id'];
		$supplementary_results = $db->query($SQL_extended);
		if(PEAR::isError($supplementary_results)){
			echo($supplementary_results->getUserInfo()." $sql\n");
		} else {
			while($additional_keywords = $supplementary_results->fetchRow(MDB2_FETCHMODE_ASSOC)){
				foreach($additional_keywords as $field => $value){
					if (strcmp(trim($value), "")){
						$keyword_entry = $object_type->appendChild($dom->createElement($field));
						$keyword_entry->appendChild($dom->createTextNode(preg_replace($text_pattern, $text_replace, $value)));
					}
				}

				$states = $object_type->appendChild($dom->createElement("states"));
				$stateId = $additional_keywords['objectid'];
				$SQL_state = "SELECT ".$stateFields." FROM CharacterState WHERE id=".$additional_keywords['objectid'];
				$state_results = $db->query($SQL_state);
				if(PEAR::isError($state_results)){
					echo($state_results->getUserInfo()." $sql\n");
				} else {
					$state_keywords = $state_results->fetchRow(MDB2_FETCHMODE_ASSOC);
					foreach($state_keywords as $field => $value){
						if (strcmp(trim($value), "")){
							$keyword_entry = $states->appendChild($dom->createElement($field));
							$keyword_entry->appendChild($dom->createTextNode(preg_replace($text_pattern, $text_replace, $value)));
						}
					}

					$state = $states->appendChild($dom->createElement("state"));

					$SQL_single_state = "SELECT ".$collectionObjectFields." FROM CollectionObjects WHERE collectionId=".$additional_keywords['objectid'];
					$single_state_results = $db->query($SQL_single_state);
					if(PEAR::isError($single_state_results)){
						echo($single_state_results->getUserInfo()." $sql\n");
					} else {
						while($single_keywords = $single_state_results->fetchRow(MDB2_FETCHMODE_ASSOC)){
							foreach($single_keywords as $field => $value){
								if (strcmp(trim($value), "")){
									$keyword_entry = $state->appendChild($dom->createElement($field));
									$keyword_entry->appendChild($dom->createTextNode(preg_replace($text_pattern, $text_replace, $value)));
								}
							}

							if($single_keywords['objectTypeId']=="Image"){
								$image = $state -> appendChild($dom->createElement("image"));
								$query = "SELECT Image.id, Image.originalFileName, Image.resolution, Image.magnification, Image.imageType, Image.accessNum, Image.copyrightText, Image.viewId, View.viewName, View.imagingTechnique, View.imagingPreparationTechnique, View.specimenPart, View.viewAngle, View.developmentalStage, View.sex, View.form, View.viewTSN, View.isStandardView, Specimen.sex, Specimen.form, Specimen.developmentalStage, Specimen.preparationType, Specimen.typeStatus, Specimen.name, Specimen.comment, Specimen.institutionCode, Specimen.collectionCode, Specimen.catalogNumber, Specimen.previousCatalogNumber, Specimen.relatedCatalogItem, Specimen.collectionNumber, Specimen.collectorName, Specimen.dateCollected, Specimen.notes, Specimen.taxonomicNames, Specimen.localityId, Locality.country, Locality.locality, Locality.latitude, Locality.longitude, Locality.minimumElevation, Locality.maximumElevation, Country.description AS Country, ContinentOcean.description, ContinentOcean.name, Specimen.tsnId, Groups.groupName, Image.specimenId, User.name FROM Image LEFT JOIN View ON Image.viewId = View.id LEFT JOIN Specimen ON Image.specimenId = Specimen.id LEFT JOIN Locality ON Specimen.localityId = Locality.id LEFT JOIN Country ON Locality.country = Country.name LEFT JOIN ContinentOcean ON Locality.continentOcean = ContinentOcean.name LEFT JOIN BaseObject ON Image.id = BaseObject.id LEFT JOIN Groups ON BaseObject.groupId = Groups.id LEFT JOIN User ON BaseObject.userId = User.id WHERE Image.id = ".$single_keywords['objectid'];
								$result = $db->query($query);
								if(PEAR::isError($result)){
									echo($result->getUserInfo()." $sql\n");
								} else {
									$keyword = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
									foreach($keyword as $field => $value){
										if (strcmp(trim($value), "")){
											$keyword_entry = $image->appendChild($dom->createElement($field));
											$keyword_entry -> appendChild($dom->createTextNode(preg_replace($text_pattern, $text_replace, $value)));
										}
									}
								}
							}
						}
					}
				}
			}
		}

		$dom->formatOutput = true;
		$xml_output = $dom->saveXML();


		$SQL_update = "UPDATE BaseObject SET keywords = \"" . preg_replace($xml_pattern, $xml_replace, $xml_output) . "\" WHERE id = " . $id;
		$update_results = $db->query($SQL_update);
		if(PEAR::isError($update_results)){
			echo($update_results->getUserInfo()." $sql\n");
		}

		if ($operation == "script") {
			return TRUE;
		}

		if($operation=="Update"){
			$SQL_update = "UPDATE Keywords SET keywords = \"" . preg_replace($xml_pattern, $xml_replace, $xml_output) . "\" WHERE id = " . $id;
			$update_results = $db->query($SQL_update);
			if(PEAR::isError($update_results)){
				echo($update_results->getUserInfo()." $sql\n");
			}


			if($operation=="Insert"){
				$keywordInsert = 'INSERT INTO Keywords SELECT id, userId, groupId, dateToPublish, objectTypeId, keywords, submittedBy FROM ';
				$keywordInsert .= 'BaseObject WHERE id ='.$id;
				//echo $keywordInsert;
				$keywordResult = $db->query($keywordInsert);
				if(PEAR::isError($update_results)){
					echo($update_results->getUserInfo()." $sql\n");
				}

			}
		}
	}
}//end of function CharacterKeywords

function getExternalLinkWords($id) {
	$db = connect();
	$extLinkSql = "SELECT label,externalId from ExternalLinkObject where mbId=$id";
	$results = $db->query($extLinkSql);
	isMdb2Error($results,"getExtLinkWds: $query");
	while ($row = $results->fetchRow()) {
		$words .= $row[0].' '.$row[1].' ';
	}
	return $words;
}

