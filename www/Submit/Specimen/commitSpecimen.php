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

/* Config scripts */

include_once('tsnFunctions.php');
include_once('objectFunctions.php');
include_once('updateObjectKeywords.php');
include_once('extLinksRefs.php');
include_once('urlFunctions.inc.php');
include_once('updater.class.php');

$userId = $objInfo->getUserId();
$groupId = $objInfo->getUserGroupId();

$indexUrl = 'index.php?'.getParamString($_REQUEST);
if(!checkAuthorization(null, $userId, $groupId, 'add')){
	header ("location: index.php");
	exit;
}

$db = connect();

// Check TSN Id
$count = getObjectCount('Tree', $_POST['tsnId'], 'tsn');
if(is_string($count)) { // Error returned
	header ("location: $indexUrl&code=2");
	exit;
} elseif ($count == 0){
	header ("location: $indexUrl&code=3");
	exit;
}

if(!$_POST['DateToPublish']) {
	$dateToPublish = date('Y-m-d', (mktime(0, 0, 0, date("m") +6, date("d") - 1, date("Y"))));
} else {
	$dateToPublish = date("Y-m-d", strtotime($_POST['DateToPublish']));
}

// Insert Object and Specimen returning id
$params = array($db->quote("Specimen"), $_POST['Contributor'], $groupId, $userId, $db->quote($dateToPublish, 'date'), $db->quote("Specimen added"), $db->quote(NULL));
$result = $db->executeStoredProc('CreateObject', $params);
if(isMdb2Error($result, 'Create Object procedure', false)) {
	header("location: $indexUrl&code=4&id=' . $id");
	exit;
}
$id = $result->fetchOne();
clear_multi_query($result);

// Get taxonomic names
$taxonomicNames = getTaxonomicNames($_POST['tsnId']);

$specimenUpdater = new Updater($db, $id, $userId, $groupId, 'Specimen');
$specimenUpdater->addField("basisOfRecordId", $_POST['BasisOfRecord'], null);
$specimenUpdater->addField("sex", $_POST['Sex'], null);
$specimenUpdater->addField("form", $_POST['Form'], null);
$specimenUpdater->addField("developmentalStage", $_POST['DevelopmentalStage'], null);
$specimenUpdater->addField("typeStatus", $_POST['TypeStatus'], null);
$specimenUpdater->addField("preparationType", $_POST['PreparationType'], null);
$specimenUpdater->addField("tsnId", $_POST['tsnId'], null);
$specimenUpdater->addField("individualCount", $_POST['IndividualCount'], null);
$specimenUpdater->addField("name", $_POST['tsnname'], null);
$specimenUpdater->addField("dateIdentified", $_POST['DateDetermined'], null);
$specimenUpdater->addField("comment", $_POST['Comment'], null);
$specimenUpdater->addField("institutionCode", $_POST['InstitutionCode'], null);
$specimenUpdater->addField("collectionCode", $_POST['CollectionCode'], null);
$specimenUpdater->addField("catalogNumber", $_POST['CatalogNumber'], null);
$specimenUpdater->addField("previousCatalogNumber", $_POST['PreviousCatalogNumber'], null);
$specimenUpdater->addField("relatedCatalogItem", $_POST['RelatedCatalogItem'], null);
$specimenUpdater->addField("relationshipType", $_POST['RelationshipType'], null);
$specimenUpdater->addField("collectionNumber", $_POST['CollectionNumber'], null);
$specimenUpdater->addField("collectorName", $_POST['CollectorName'], null);
$specimenUpdater->addField("dateCollected", $_POST['DateCollected'], null);
$specimenUpdater->addField("earliestDateCollected", $_POST['earliestDateCollected'], null);
$specimenUpdater->addField("latestDateCollected", $_POST['latestDateCollected'], null);
$specimenUpdater->addField("localityId", $_POST['LocalityId'], null);
$specimenUpdater->addField("notes", $_POST['Notes'], null);
$specimenUpdater->addField("taxonomicNames", $taxonomicNames, null);
$numRows = $specimenUpdater->executeUpdate();
if (is_string($numRows)) { // Error returned
	header("location: /Edit/Specimen/?code=11&id=' . $id");
	exit;
}

// Update keywords
if ($numRows > 0) {
	updateKeywordsTable($id);
}

/* Update and Add external links and unique references */
$insertLinkRes = insertLinks($id, $_POST);
$insertRefRes  = insertReferences($id, $_POST);
if(!$insertLinkRes || !$insertRefRes) {
	header("location: /Edit/Specimen/?code=9&id=' . $id");
	exit;
}

// Added objTitle and pop to url to determine if being added via popup
$objTitle = getScientificName($_POST['tsnId']);
header ("location: $indexUrl&code=1&id=$id&objTitle=".$objTitle);
exit;
?>
