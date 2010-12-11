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
/**
 * File name: modifyTSN.php
 * @package Morphbank2
 * @subpackage Edit
 * @subpackage Taxon
 */


include_once('updater.class.php');
include_once('objectFunctions.php');
include_once('updateObjectKeywords.php');
include_once('tsnFunctions.php');
include_once('extLinksRefs.php');

$id  = $_POST['objId'];
$tsn = $_POST['tsn'];
$indexUrl = "editTSN.php?id=$tsn";
$userId = $objInfo->getUserId();
$groupId =$objInfo->getUserGroupId();

if (!checkAuthorization($id, $userId, $groupId, 'edit')) {
	header ("location: $indexUrl");
	exit;
}

$kingdom_id      = $_POST['kingdomid'];
$parent_tsn      = $_POST['parent_tsn'];
$rank_id         = $_POST['rank_id'];
$rankName        = getRankName($rank_id);
$referenceId     = $_POST['reference_id'];
$taxon_author    = $_POST['taxon_author'];
$taxon_author_id = FindAuthor($taxon_author, $kingdom_id);
$nameType        = $_POST['nametype'];
$usage           = $_POST['status'];
$status          = $_POST['status'];
$nameSource      = trim($_POST['namesource']);
$comments        = trim($_POST['comments']);
$parentName      = getScientificName($parent_tsn);
$newName         = trim($_POST['sname']);
$pages           = trim($_POST['pages']);
$contributor     = $_POST['Contributor'];

$taxonAuthorName = FindAuthorName($taxon_author_id);

$db = connect();

// Get values needed for updates
$scientificName = createNewScientificName($newName, $parentName, $rank_id, $parent_tsn);
if (!$scientificName) {
	header("location: $indexUrl&code=2");
	exit;
}

// Get Tree info
$rowTree = getObjectData('Tree', $tsn, '*', 'tsn');
if (is_string($rowTree)) { // Error returned
	header("location: $indexUrl&code=3");
	exit;
}

// Get Taxa info
$rowTaxa = getObjectData('Taxa', $tsn, '*', 'tsn');
if (is_string($rowTaxa)) { // Error returned
	header("location: $indexUrl&code=4");
	exit;
}

// Get BaseObject info
$rowBObj = getObjectData('BaseObject', $rowTaxa['boid']);
if (is_string($rowBObj)) { // Error returned
	header("location: $indexUrl&code=5");
	exit;
}

// Update Tree
$treeUpdater = new Updater($db, $tsn, $userId , $groupId, 'Tree', 'tsn', false);
$treeUpdater->addField('nameType', $nameType, $rowTree['nametype']);
$treeUpdater->addField('publicationId', $referenceId, $rowTree['publicationid']);
$treeUpdater->addField('taxon_author_id', $taxon_author_id, $rowTree['taxon_author_id']);
$treeUpdater->addField('pages', $pages, $rowTree['pages']);
$treeUpdater->addField('nameSource', $nameSource, $rowTree['nameource']);
$treeUpdater->addField('comments', $comments, $rowTree['comments']);
$treeUpdater->addField('usage', $usage, $rowTree['usage']);

// Update Taxa
$taxaUpdater = new Updater($db, $tsn, $userId , $groupId, 'Taxa', 'tsn', false);
$taxaUpdater->addField('nameType', $nameType, $rowTaxa['nametype']);
$taxaUpdater->addField('publicationId', $referenceId, $rowTaxa['publicationid']);
$taxaUpdater->addField('taxon_author_id', $taxon_author_id, $rowTaxa['taxon_author_id']);
$taxaUpdater->addField('taxon_author_name', $taxonAuthorName, $rowTaxa['taxon_author_name']);
$taxaUpdater->addField('nameSource', $nameSource, $rowTaxa['namesource']);
$taxaUpdater->addField('status', $status, $rowTaxa['status']);
$taxaUpdater->addField('userId', $contributor, $rowTaxa['userid']);
$taxaUpdater->addField('rank_name', $rankName, $rowTaxa['rank_name']);

// Update TaxonConcept
$taxonConceptUpdater = new Updater($db, $id, $userId , $groupId, 'TaxonConcept');
$taxonConceptUpdater->addField('status', $status, $rowTaxa['status']);

// Update BaseObject
$baseObjUpdater = new Updater($db, $id, $userId , $groupId, 'BaseObject');
$baseObjUpdater->addField('userId', $contributor, $rowBObj['userid']);

// Run basic checks based on data submittted
// TODO update specimen table if the tsn was used for some specimens (for now run the script separatly)
$checkTsn     = CheckTsn($tsn);
$haveChildren = HaveChildren($tsn);
$nameChange   = $scientificName != $rowTree['scientificname'] ? true : false;
$rankChange   = $rank_id != $rowTree['rank_id'] ? true : false;

if ($nameChange || $rankChange) {
	if (!$checkTsn) {
		header("location: $indexUrl&code=6"); // TODO TSN is used in other parts of the sytem. Disallow if false.
		exit;
	}
	$updateChildren = false;
	if ($haveChildren && $rankChange) {
		header("location: $indexUrl&code=7"); // TODO has children, cannot change rank
		exit;
	}
	if ($haveChildren && !CheckChildren($tsn, $userId, $groupId)) {
		header("location: $indexUrl&code=8"); // TODO Do not have permission to change children
		exit;
	}
	if ($nameChange) {
		$updateChildren = true;
	}
}

// If script makes it here, it's ok to update following fields
$treeUpdater->addField('rank_id', $rank_id, $rowTree['rank_id']);
$treeUpdater->addField('scientificName', $scientificName, $rowTree['scientificname']);
$treeUpdater->addField('parent_tsn', $parent_tsn, $rowTree['parent_tsn']);

$sciNames = getTaxonomicNamesFromBranch($parent_tsn, " ", false);
$taxonomicNames = $sciNames . ' ' . $scientificName;
$taxaUpdater->addField('rank_id', $rank_id, $rowTaxa['rank_id']);
$taxaUpdater->addField('scientificName', $scientificName, $rowTaxa['scientificname']);
$taxaUpdater->addField('taxonomicNames', $taxonomicNames, $rowTaxa['taxonomicnames']);
$taxaUpdater->addField('parent_tsn', $parent_tsn, $rowTaxa['parent_tsn']);
$taxaUpdater->addField('parent_name', $parentName, $rowTaxa['parent_name']);

// Update tree
$numRowsTree = $treeUpdater->executeUpdate();
if (is_string($numRowsTree)) { // Error returned
	header("location: $indexUrl&code=9");
	exit;
}

// Update Taxa
$numRowsTaxa = $taxaUpdater->executeUpdate();
if (is_string($numRowsTaxa)) { // Error returned
	header("location: $indexUrl&code=10");
	exit;
}

//Update TaxonConcept
$numRowsTC = $taxonConceptUpdater->executeUpdate();
if (is_string($numRowsTC)) { // Error returned
	header("location: $indexUrl&code=11");
	exit;
}

// Update BaseObject and keywords
$numRowsBO = $baseObjUpdater->executeUpdate();
if (is_string($numRowsBO)) { // Error returned
	header("location: $indexUrl&code=12");
	exit;
}

// Update children names if true: scientific name changed
if ($updateChildren) {
	if (!updateChildrenName($tsn, $scientificName, $rowTree['scientificname'])) {
		header("location: $indexUrl&code=13");
		exit;
	}
}

// Update keywords
updateKeywordsTable($rowBObj['id'], 'update');
TaxaKeywords($tsn, $rowBObj['id']);

/* Insert vernacular */
$insertVernacular = insertVernacular($tsn, $_POST);
$updateVernacular = updateVernacular($tsn, $_POST);
if(!$insertVernacular || !$updateVernacular) {
	header("location: $indexUrl&code=20");
	exit;
}

// Update and Add external links and unique references
$insertLinkRes = insertLinks($rowBObj['id'], $_POST);
$updateLinkRes = updateLinks($rowBObj['id'], $_POST);
$insertRefRes  = insertReferences($rowBObj['id'], $_POST);
$updateRefRes  = updateReferences($rowBObj['id'], $_POST);
if(!$insertLinkRes || !$insertRefRes) {
	header("location: $indexUrl&code=21");
	exit;
}
if(!$updateLinkRes || !$updateRefRes) {
	header("location: $indexUrl&code=22");
	exit;
}

header("location: $indexUrl&code=1");
exit;
