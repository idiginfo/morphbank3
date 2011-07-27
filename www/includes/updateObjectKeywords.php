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
*   Deborah Paul - initial API and implementation implementation
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

include_once('keywordsData.php');
include_once('tsnFunctions.php');

//TODO move this out of the admin directory
// Define functions that perform keyword update on a single object

function updateBaseKeywords ($id, $massUpdate = false){
	global $xml_pattern, $xml_replace, $text_pattern, $text_replace;
	global $updateStmt, $SQL_update, $keywordXml, $keywordText, $dom;
	global $keywordsTempStmt, $numCleared, $taxaUpdateStmt;
	global $keywordsTempClearSql, $keywordsBaseObjectUpdateSql, $keywordsUpdateSql;
	global $keywordsMissingSql, $keywordsInsertSql, $keywordsTempParams;
	global $collectionObjectKeywordsStmt, $externalKeywordsStmt, $annotationKeywordsStmt;

	global $db;

	$db = connect();

	// update the keywords and related fields for one object
	$dom = new DomDocument('1.0', 'utf-8');
	$morphbank = $dom->appendChild($dom->createElement("morphbank"));
	//create imagealttext! GR
	$SQL_objectType = "SELECT objectTypeId from BaseObject where id = $id";
	$objectTypeId = $db->getOne($SQL_objectType);
	isMdb2Error($objectTypeId, "No object for id $id");
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
//		$checkQuery = 'SELECT boId FROM Taxa WHERE boId='.$id;
//		//			$result = mysqli_query($link, $checkQuery);
//		//			if (mysqli_num_rows($result) != 1) {)
//		$result = $db->query($checkQuery);
//		if(PEAR::isError($result)){
//			die($result->getUserInfo()." $objectTypeId\n");
//		}
//
//		if ($result->numrows() == 0) {
//			//call the Taxa Procedure to create Taxa record
//			//var_dump($row);
//			//exit(0);
//			$query = "CALL `TaxaInsert`($id,NULL,'".$row['name']
//			."',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,"
//			."NULL,NULL,NULL,".$row['groupid'].",".$row['userid'].",NOW(),NULL,'Otu')";
//			//$result = mysqli_multi_query($link,$query);
//			echo "OTU query: $query\n\n";
//			$result = $db->_query($query);
//			if(PEAR::isError($result)){
//				die($result->getUserInfo()." $objectTypeId\n");
//			}
//			//update the Taxa keywords and BaseObject insert
//			TaxaKeywords(NULL, $id);
//		}
//		return;
	}

	// Rest of the method is solid
	$SQL_keywords= getObjectKeywordQuery($objectTypeId);
	if ($SQL_keywords){
		$params = array($id);
		$keywords_results = $SQL_keywords->execute($params);
		if(PEAR::isError($keywords_results)){
			die($keywords_results->getUserInfo()." $objectTypeId\n");
		}
		//TODO Collection has many rows, one for each member
		$keywords = $keywords_results->fetchRow(MDB2_FETCHMODE_ASSOC);
		// why more than one row? vernacular? Collection!
		foreach($keywords as $field => $value){
			//echo "field $field value $value\n";
			addKeywords($field, $value);
		}

		$tsn = getTsnByType($id, $objectTypeId);
		$taxonNames = getTaxonomicNames($tsn);
		addKeywords('taxonomicNames', $taxonNames);
		$vernacularNames = getVernacularNames($tsn);
		addKeywords('vernacularNames', $vernacularNames);
		$externalLinkWords = getExternalLinkWords($id);
		addKeywords('externalLinkWords', $externalLinkWords);

		// create image alt text! GR
		$imageAltText = 'Morphbank biodiversity NSF FSU Florida State University ';
		$imageAltText .= $keywords['groupname'].' ';
		$imageAltText .= $keywords['name'].' ';
		$imageAltText .= $keywords['imagingtechnique'].' ';
		$imageAltText .= $keywords['imagingpreparationtechnique'].' ';
		$imageAltText .= $keywords['specimenpart'].' ';
		$imageAltText .= $keywords['viewangle'].' ';
		$imageAltText .= $keywords['sex'].' ';
		$imageAltText .= $keywords['form'].' ';
		$imageAltText .= $keywords['collectorname'].' ';
		$imageAltText .= $keywords['developmentalstage'].' ';
		$imageAltText .= $keywords['continent'].' ';
    $imageAltText .= $keywords['ocean'].' ';
    $imageAltText .= $keywords['country'].' ';
    $imageAltText .= $keywords['state'].' ';
    $imageAltText .= $keywords['county'].' ';
		$imageAltText .= $keywords['locality'].' ';
		$imageAltText .= $keywords['affiliation'].' ';
		$imageAltText .= $taxonNames . $vernacularNames;
		//add vernacular names from ITIS table 'Vernacular'
		//$tsn = $keywords['tsnid'];
		$dom->formatOutput = true;
		$xml_output = $dom->saveXML();
		if(PEAR::isError($xml_output)){
			die("\n" . $xml_output->getMessage() . " $SQL_update\n");
		}
		//echo "mass update $keywordText\n$xml_output\n";//

		if (! $massUpdate){
			$keywordText .= getRelatedKeywords($id, $collectionObjectKeywordsStmt);
			$keywordText .= getRelatedKeywords($id, $externalKeywordsStmt);
			$keywordText .= getRelatedKeywords($id, $annotationKeywordsStmt);
		} else {
			//TODO add related keywords in second pass, after mass update
		}

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


/**
 * Harvest keywords from related objects using a prepared statement
 * @param $id
 */
function getRelatedKeywords($id, $stmt){
	global $db;
	$keywordsText = '';
	$result = $stmt->execute(array($id));
	while ($row = $result->fetchRow()) {
		$keywordsText .= implode(' ',$row);
	}
	return $keywordsText;
}


/**
 * Add the property to the global keyword variables
 * @param $field - name of the property
 * @param $value - value of the property
 */
function addKeywords($field, $value){
	global $keywordXml, $keywordText, $text_pattern, $text_replace, $dom;
	if (!empty($value)) {
		$keywordNode = $keywordXml->appendChild($dom->createElement($field));
		$keywordNode->appendChild($dom->createTextNode(preg_replace($text_pattern, $text_replace, $value)));
		$keywordText .= " ".$value;
	}
}

/**
 * Update the Keywords or Taxa table from the KeywordsTemp table
 * Used in batch keyword processing
 * @param unknown_type $query
 */
function updateKeywords($query){
	global $updateStmt, $SQL_update, $keywordXml, $keywordText, $dom;
	global $keywordsTempStmt, $numCleared, $taxaUpdateStmt;
	global $keywordsTempClearSql, $keywordsBaseObjectUpdateSql, $keywordsTaxaUpdateSql, $keywordsUpdateSql;
	global $keywordsMissingSql, $keywordsInsertSql;
	$massUpdate = true;
	$db = connect();

	//clear KeywordsTemp
	$result = $db->exec($keywordsTempClearSql);
	isMdb2Error($result,$keywordsTempClearSql);

	//find missing rows in Keywords
	$result = $db->exec($keywordsMissingSql);
	isMdb2Error($result,$keywordsMissingSql);
	echo "Time after find $result missing rows: ".date("H:i:s")."\n";

	//add missing rows to Keywords
	$result = $db->exec($keywordsInsertSql);
	isMdb2Error($result,$keywordsInsertSql);
	echo "Time after add $result missing rows: ".date("H:i:s")."\n";

	//clear KeywordsTemp
	$result = $db->exec($keywordsTempClearSql);
	isMdb2Error($result,$keywordsTempClearSql);
	echo "Cleared $result rows from KeywordsTemp\n";
	$ids = $db->query($query);
	isMdb2Error($ids,$query);
	echo "query: $query num rows: ".$ids->numRows()."\n";
	// harvest keywords into KeywordsTemp
	while($id = $ids->fetchOne()) {
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
  
  // update Taxa from KeywordsTemp
	$result = $db->exec($keywordsTaxaUpdateSql);
	isMdb2Error($result,$keywordsTaxaUpdateSql);

	echo "Taxa update finished: $result taxa updated!\n";
	echo "Time after updating Taxa: ".date("H:i:s")."\n";

	// update Keywords from KeywordsTemp
	$result = $db->exec($keywordsUpdateSql);
	isMdb2Error($result,$keywordsUpdateSql);
	echo "Time after Keywords update: ".date("H:i:s")."\n";

	// reindex Keywords
	$result = $db->exec('repair table Keywords quick');
	isMdb2Error($result,$keywordsTempClearSql);
	echo "Time after Keywords repair: ".date("H:i:s")."\n";

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
 * Place holder for Taxa Keywords
 */
include_once("updateTaxaKeywords.php");

/**
 * This function creates/updates the keywords for MbCharacter
 * @param $link
 * @param $id
 * @param $operation
 */
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
								$query = "SELECT Image.id, Image.originalFileName, Image.resolution, Image.magnification, Image.imageType, Image.accessNum, Image.copyrightText, Image.viewId, View.viewName, View.imagingTechnique, View.imagingPreparationTechnique, View.specimenPart, View.viewAngle, View.developmentalStage, View.sex, View.form, View.viewTSN, View.isStandardView, Specimen.sex, Specimen.form, Specimen.developmentalStage, Specimen.preparationType, Specimen.typeStatus, Specimen.name, Specimen.comment, Specimen.institutionCode, Specimen.collectionCode, Specimen.catalogNumber, Specimen.previousCatalogNumber, Specimen.relatedCatalogItem, Specimen.collectionNumber, Specimen.collectorName, Specimen.dateCollected, Specimen.notes, Specimen.taxonomicNames, Specimen.localityId, Locality.continent, Locality.ocean, Locality.country, Locality.state, Locality.county, Locality.locality, Locality.latitude, Locality.longitude, Locality.minimumElevation, Locality.maximumElevation, Specimen.tsnId, Groups.groupName, Image.specimenId, User.name FROM Image LEFT JOIN View ON Image.viewId = View.id LEFT JOIN Specimen ON Image.specimenId = Specimen.id LEFT JOIN Locality ON Specimen.localityId = Locality.id LEFT JOIN BaseObject ON Image.id = BaseObject.id LEFT JOIN Groups ON BaseObject.groupId = Groups.id LEFT JOIN User ON BaseObject.userId = User.id WHERE Image.id = ".$single_keywords['objectid'];
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

