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
 * File: modifySpecimen.php 
 * @subpackage Submit
 * @subpackage Edit
 * @subpackage Specimen
 */


include_once('tsnFunctions.php');
include_once('objectFunctions.php');
include_once('imageFunctions.php');
include_once('extLinksRefs.php');
include_once('updateObjectKeywords.php');
include_once('urlFunctions.inc.php');
include_once('updater.class.php');

/* Check authorization */
$id = trim($_POST['objId']);
$userId = $objInfo->getUserId();
$groupId = $objInfo->getUserGroupId();
$indexUrl = "index.php?id=$id";
if (!checkAuthorization($id, $userId, $groupId, 'edit')) {
	header ("location: $indexUrl");
	exit;
}

$db = connect();

// Check TSN Id exists
$count = getObjectCount('Tree', $_POST['tsnId'], 'tsn');
if (is_string($count)) { // Error returned
	header ("location: $indexUrl&code=2");
	exit;
} elseif ($count == 0){
	header ("location: $indexUrl&code=3");
	exit;
}

// Get taxonomic names
$taxonomicNames = getTaxonomicNames($_POST['tsnId']);

$sql = "SELECT s.*, b.thumbURL, b.userId as contributor, date_format(b.dateToPublish, '%Y-%m-%d') as dateToPublish, br.description as bor, s.name as detName, l.locality from Specimen s
		left join BaseObject b on b.id = s.id 		
		left join BasisOfRecord br on br.name = s.basisOfRecordId 
		left join Locality l on l.id = s.localityId where s.id = ?";
$curr = $db->getRow($sql, null, array($id), null, MDB2_FETCHMODE_ASSOC);
if (isMdb2Error($curr, 'Select Specimen', false)) {
	header ("location: $indexUrl&code=4");
	exit;
}

// Update Specimen
$specUpdater = new Updater($db, $id, $userId, $groupId, 'Specimen');
$specUpdater->addField("basisOfRecordId", $_POST['BasisOfRecord'], $curr['basisofrecordid']);
$specUpdater->addField("sex", $_POST['Sex'], $curr['sex']);
$specUpdater->addField("form", $_POST['Form'], $curr['form']);
$specUpdater->addField("developmentalStage", $_POST['DevelopmentalStage'], $curr['developmentalstage']);
$specUpdater->addField("typeStatus", $_POST['TypeStatus'], $curr['typestatus']);
$specUpdater->addField("preparationType", $_POST['PreparationType'], $curr['preparationtype']);
$specUpdater->addField("tsnId", $_POST['tsnId'], $curr['tsnid']);
$specUpdater->addField("individualCount", $_POST['IndividualCount'], $curr['individualcount']);
$specUpdater->addField("name", $_POST['DeterminedBy'], $curr['detname']);
$specUpdater->addField("dateIdentified", $_POST['DateDetermined'], $curr['dateidentified']);
$specUpdater->addField("comment", $_POST['Comment'], $curr['comment']);
$specUpdater->addField("institutionCode", $_POST['InstitutionCode'], $curr['institutioncode']);
$specUpdater->addField("collectionCode", $_POST['CollectionCode'], $curr['collectioncode']);
$specUpdater->addField("catalogNumber", $_POST['CatalogNumber'], $curr['catalognumber']);
$specUpdater->addField("previousCatalogNumber", $_POST['PreviousCatalogNumber'], $curr['previouscatalonumber']);
$specUpdater->addField("relatedCatalogItem", $_POST['RelatedCatalogItem'], $curr['relatedcatalogitem']);
$specUpdater->addField("relationshipType", $_POST['RelationshipType'], $curr['relationshiptype']);
$specUpdater->addField("collectionNumber", $_POST['CollectionNumber'], $curr['collectionnumber']);
$specUpdater->addField("collectorName", $_POST['CollectorName'], $curr['collectorname']);
$specUpdater->addField("dateCollected", $_POST['DateCollected'], $curr['datecollected']);
$specUpdater->addField("earliestDateCollected", $_POST['earliestDateCollected'], $curr['earliestdatecollected']);
$specUpdater->addField("latestDateCollected", $_POST['latestDateCollected'], $curr['latestdatecollected']);
$specUpdater->addField("localityId", $_POST['LocalityId'], $curr['localityid']);
$specUpdater->addField("standardImageId", $_POST['StandardImage'], $curr['standardimageid']);
$specUpdater->addField("notes", $_POST['Notes'], $curr['notes']);
$specUpdater->addField("taxonomicNames", $taxonomicNames, null);
$numRowsSpec = $specUpdater->executeUpdate();
if (is_string($numRowsSpec)) {
	header("location: $indexUrl&code=6");
	exit;
}

// Update  BaseObject
$baseObjUpdater = new Updater($db, $id, $userId, $groupId, 'BaseObject');
$baseObjUpdater->addField("dateToPublish", $_POST['DateToPublish'], $curr['datetopublish']);
$baseObjUpdater->addField("thumbURL", $_POST['StandardImage'], $curr['thumburl']);
$baseObjUpdater->addField("userId", $_POST['Contributor'], $curr['contributor']);
$numRowsBO = $baseObjUpdater->executeUpdate();
if (is_string($numRowsBO)) { // Error returned
	header("location: $indexUrl&code=7");
	exit;
}

if ($numRowsSpec == 1 || $numRowsBO == 1) {
	updateImageCountForTaxon($_POST['tsnId'], $curr['tsnid'], $curr['imagescount']);
	replaceImage('Locality', $_POST['StandardImage'], $_POST['LocalityId'], $curr['localityid'], $curr['imagescount']);
	
	// Update keywords
	updateKeywordsTable($id, 'update');

	// Update image keywords
	$sql = "select id as imgId from Image where specimenId = ?";
	$imgRows = $db->getAll($sql, null, array($id), null, MDB2_FETCHMODE_ASSOC);
	if (isMdb2Error($imgRows, 'Select image ids', false)) {
		header("location: $indexUrl&code=8");
		exit;
	}
	if (!empty($imgRows)) {
		foreach ($imgRows as $imgRow) {
			updateKeywordsTable($imgRow['imgid'], 'update');
		}
	}
}

/* Update and Add external links and unique references */
$insertLinkRes = insertLinks($id, $_POST);
$updateLinkRes = updateLinks($id, $_POST);
$insertRefRes  = insertReferences($id, $_POST);
$updateRefRes  = updateReferences($id, $_POST);
if(!$insertLinkRes || !$insertRefRes) {
	header("location: $indexUrl&code=9");
	exit;
}
if(!$updateLinkRes || !$updateRefRes) {
	header("location: $indexUrl&code=10");
	exit;
}
	
/* Success */
header("location: $indexUrl&code=1");
exit;
?>
