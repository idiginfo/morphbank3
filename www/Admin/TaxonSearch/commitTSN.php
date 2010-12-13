<?php 
/**
 * File name: commitTSN.php
 * @package Morphbank2
 * @subpackage Submit Taxon
 */

/* Config scripts */

include_once('updater.class.php');
include_once('tsnFunctions.php');
include_once('updateObjectKeywords.php');
include_once('extLinksRefs.php');
include_once('urlFunctions.inc.php');

$userId = $objInfo->getUserId();
$groupId = $objInfo->getUserGroupId();
$main_tsn = $_POST['maintsn'];

$indexUrl = 'addTSN.php?'.getParamString($_POST);
if(!checkAuthorization(null, $userId, $groupId, 'add')){
	header ("location: $indexUrl&tsn=$main_tsn");
	exit;
}

$db = connect();

// $indicators = array("x","var.","ssp.","f.");

$kingdom_id      = $_POST['kingdomid'];
$rank_id         = $_POST['rank_id'];
$parent_tsn      = $_POST['maintsn'];
$referenceId     = $_POST['reference_id'];
$taxon_author    = $_POST['taxon_author'];
$taxon_author_id = FindAuthor($taxon_author, $kingdom_id);
$nameType        = $_POST['nametype'];
$usage           = $_POST['status'];
$status          = $_POST['status'];
$unaccept_reason = 'Temporary MorphBank number';
$nameSource      = trim($_POST['namesource']);
$comments        = trim($_POST['comments']);
$parentName      = getScientificName($parent_tsn);
$newName         = trim($_POST['sname']);
$pages           = $_POST['pages'];
$contributor     = $_POST['Contributor'];
$authorName      = FindAuthorName($taxon_author_id);

$scientificName = createNewScientificName($newName, $parentName, $rank_id, $parent_tsn);
if (!$scientificName) {
	header("location: $indexUrl&tsn=$main_tsn&code=2");
	exit;
}

$letter = strtoupper(substr($scientificName, 0, 1));

//TODO get the whole name, in practice the genus name is missing
// end of scientific name

//check if the record does not exist already
$query = "SELECT count(*) as count FROM Tree WHERE scientificName=" . $db->quote($scientificName);
if (!empty($taxon_author_id)){
	$query.= " AND taxon_author_id= $taxon_author_id ";
}
$query .=  " AND rank_id=$rank_id AND parent_tsn=$parent_tsn";
if ($nameType == 'Regular scientific name') {
	if ($referenceId != null) {
		$query .= " AND publicationId=" . $referenceId;
	}
	if ($pages != null) {
		$query .= " AND pages=" . $db->quote($pages);
	}
}
$count = $db->queryOne($query);
if(isMdb2Error($count, 'Select TSN from Tree: ' . $query, false)) {
	header("location: $indexUrl&tsn=$main_tsn&code=3");
	exit;
}
if ($count) {
	header("location: $indexUrl&tsn=$main_tsn&code=3");
	exit;
}

//Call Tree Insert procedure to insert new taxa
$params = array();
$params[] = $db->quote($nameSpace);
$params[] = $db->quote($usage);
$params[] = $objInfo->getUserId();
$params[] = $objInfo->getUserGroupId();
$params[] = $contributor;
$params[] = "NOW()";
$params[] = $db->quote($unnacept_reason);
$params[] = $db->quote($parent_tsn, 'integer');// for parent
$params[] = $db->quote($kingdom_id, 'integer');
$params[] = $db->quote($rank_id, 'integer');
$params[] = $db->quote($letter);
$params[] = $db->quote($scientificName);
$params[] = $db->quote($taxon_author_id, 'integer');
$params[] = $db->quote($referenceId, 'integer');
$params[] = $db->quote($pages);
$params[] = $db->quote($nameType);
$params[] = $db->quote($nameSource);
$params[] = $db->quote($comments);

$result = $db->executeStoredProc('TreeInsert', $params);
if(isMdb2Error($result, 'TreeInsert Stored Procedure', 5)) {
	header("location: $indexUrl&tsn=$main_tsn&code=4");
	exit;
}
$tsn = $result->fetchOne();
clear_multi_query($result);

// Update Taxa
$sciNames = getTaxonomicNamesFromBranch($parent_tsn, " ", false);
$taxonomicNames = $sciNames . ' ' . $scientificName;
$taxaUpdater = new Updater($db, $tsn, $userId , $groupId, 'Taxa', 'tsn', false);
$taxaUpdater->addField('parent_name', $parentName, null);
$taxaUpdater->addField('taxon_author_name', $authorName, null);
$taxaUpdater->addField('taxonomicNames', $taxonomicNames, null);
// Update Taxa
$numRowsTaxa = $taxaUpdater->executeUpdate();
if (is_string($numRowsTaxa)) { // Error returned
	header("location: $indexUrl&tsn=$main_tsn&code=5");
	exit;
}

// Get BaseObject id for update keywords and adding external links/refs
$id = $db->queryOne("select id from TaxonConcept where tsn = $tsn");
if(isMdb2Error($id, 'Select BaseObject id', 5)) {
	header("location: editTSN.php?id=$tsn&code=21");
	exit;
}

// Run Keywords
updateKeywordsTable($id);
TaxaKeywords($tsn, $id);

/* Insert vernacular */
$insertVernacular = insertVernacular($tsn, $_POST);
if(!$insertVernacular) {
	header("location: editTSN.php?id=$tsn&code=20");
	exit;
}

/* Update and Add external links and unique references */
$insertLinkRes = insertLinks($id, $_POST);
$insertRefRes  = insertReferences($id, $_POST);
if(!$insertLinkRes || !$insertRefRes) {
	header("location: editTSN.php?id=$tsn&code=22");
	exit;
}
header("location: $indexUrl&tsn=$main_tsn&id=$tsn&scientificName=$scientificName&code=1");
exit;
