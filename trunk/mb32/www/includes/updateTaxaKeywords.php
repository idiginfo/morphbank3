#-------------------------------------------------------------------------------
# Copyright (c) 2010 Greg Riccardi, Fredrik Ronquist.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Public License v2.0
# which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# 
# Contributors:
#   Fredrik Ronquist - conceptual modeling and interaction design
#   Austin Mast - conceptual modeling and interaction design
#   Greg Riccardi - initial API and implementation
#   Wilfredo Blanco - initial API and implementation
#   Robert Bruhn - initial API and implementation
#   Christopher Cprek - initial API and implementation
#   David Gaitros - initial API and implementation
#   Neelima Jammigumpula - initial API and implementation
#   Karolina Maneva-Jakimoska - initial API and implementation
#   Katja Seltmann - initial API and implementation
#   Stephen Winner - initial API and implementation
#-------------------------------------------------------------------------------
<?php
include_once('updateObjectKeywords.php');

$db = connect();
$insertTmpSql = "insert into KeywordsTemp (keywords, id) values (?,?)";
$param_types = array('text','integer');

$insertTempStmt = $db->prepare($insertTmpSql,$param_types);
if(PEAR::isError($insertTempStmt)){
	die("prepareKeywordQueries for Taxa\n".$insertTmpSql->getUserInfo()." $updateTaxaSql\n");
}

/**
 * This function creates the keywords for the Taxa table that
 *  contains taxonomy and OTU records
 * @param $link
 * @param $tsn - the taxon number assigned to the new taxon
 * @param $taxaId - baseObjectId passed for newly created OTU or taxon
 * @return unknown_type
 */
function TaxaKeywords($tsn, $taxaId = null, $defer = false){
	//TODO make taxa keywords consistent with other keywords
	global  $xml_pattern, $xml_replace, $text_pattern, $text_replace;
	global $updateStmt, $SQL_update, $keywordXml, $keywordText, $dom;
	$keywordText = '';
	$db = connect();
	$taxaFields = "tsn, boId, taxon_author_name, ";
	$taxaFields .="nameSource, rank_name, taxonomicNames";
	
	$taxaKeywordsSql = "SELECT $taxaFields From Taxa WHERE tsn=$tsn ";

	// Taxa keyword fields
	$taxaKeywords = $db->getRow($taxaKeywordsSql, null, null, null, MDB2_FETCHMODE_ASSOC);
	isMdb2Error($taxaKeywords, $taxaKeywordsSql);
	if(empty($taxaKeywords)) return "tsn $tsn not found";

	//	$dom = new DomDocument('1.0', 'utf-8');
	//	$morphbank = $dom->appendChild($dom->createElement("morphbank"));
	//	$keywordXml = $morphbank->appendChild($dom->createElement('taxon'));

	foreach($taxaKeywords as $field => $value){
		//echo "field $field value $value\n";
		addTaxaKeywords($field, $value);
	}

	if (empty($taxaId)){
		$sql = "select id from TaxonConcept where tsn = $tsn";
		$taxaId = $db -> queryOne($sql);
		isMdb2Error($taxaId,$sql);
	}

	$publicationId = $taxaKeywords['publicationId'];
	if(!empty($publicationId)){
		$publicationFields = "p.doi, p.publicationType, p.author, p.publicationTitle,";
		$publicationFields .="p.month, p.publisher, p.school, p.series, p.note,";
		$publicationFields .="p.organization, p.institution, p.title, p.year,";
		$publicationFields .="p.isbn, p.issn, g.groupName, u.name AS submittedBy ";
		$publicationJoin = 'Publication p join BaseObject b on p.id = b.id join Groups g on b.groupId = g.id '
		. 'join User u on b.userId = u.id ';
		$pubKeywordsSql = "SELECT $publicationFields FROM $publicationJoin WHERE p.id=$publicationId";

		$pubKeywords = $db->getRow($pubKeywordsSql, null, null, null, MDB2_FETCHMODE_ASSOC);
		isMdb2Error($pubKeywords, $pubKeywordsSql);
		foreach($pubKeywords as $field => $value){
			//echo "field $field value $value\n";
			addTaxaKeywords($field, $value);
		}
	}

	if (!empty($taxaId)){
		$baseObjectFields = "u.name, g.groupName ";
		$baseObjectJoin = ' BaseObject b join User u ON b.submittedby=u.id '
		. 'join Groups g on b.groupId=g.id';
		$baseKeywordsSql = "SELECT ".$baseObjectFields." FROM ".$baseObjectJoin." WHERE b.id=$taxaId";

		$baseKeywords = $db->getRow($baseKeywordsSql, null, null, null, MDB2_FETCHMODE_ASSOC);
		isMdb2Error($baseKeywords, $baseKeywordsSql);
		foreach($baseKeywords as $field => $value){
			//echo "field $field value $value\n";
			addTaxaKeywords($field, $value);
		}
	}
	// Vernacular
	$vernacularNames = getVernacularNames($tsn);
	addTaxaKeywords('vernacularNames', $vernacularNames);
	//TODO add external link keywords

	//TODO handle OTU as in the following
	//if the keywords are created for Otu information about
	//taxa or specimen is added to the keywords filed
	//	$SQL_extended = "SELECT objectTypeId, objectTitle, objectid FROM CollectionObjects "
	//	. "where collectionId=$taxaId";
	//	$supplementary_results = $db->query($SQL_extended);
	//	if($supplementary_results->numrows()>0){
	//		while($additional_keywords = $supplementary_results->fetchRow(MDB2_FETCHMODE_ASSOC)) {
	//			for each($additional_keywords as $field => $value){
	//			}
	//			if($additional_keywords['objectTypeId']=="TaxonConcept"){
	//				$SQL_simple = "SELECT tsn from Taxa where boId=".$additional_keywords['objectid'];
	//				$simple_result = $db->query($SQL_simple);
	//				$simple_row = $simple_result->fetchRow(MDB2_FETCHMODE_ASSOC);
	//				for each($simple_row as $field => $value){
	//				}
	//			}
	//		}
	//	}

	//	$dom->formatOutput = true;
	//	$xml_output = $dom->saveXML();
	//	if(PEAR::isError($xml_output)){
	//		die("\n" . $xml_output->getMessage() . " $SQL_update\n");
	//	}

	// update Taxa
	global $insertTempStmt, $taxaUpdateStmt;
	if ($defer) $stmt = $insertTempStmt;
	else $stmt = $taxaUpdateStmt;
	$params = array($keywordText, $tsn);
	$updateResults = $stmt->execute($params);
	isMdb2Error($updateResults,"update Taxa");

	return "Updated tsn $tsn";
}

function addTaxaKeywords($field, $value){
	global  $keywordText;
	if (!empty($value)) {
		$keywordText .= " ".$value;
	}
}


