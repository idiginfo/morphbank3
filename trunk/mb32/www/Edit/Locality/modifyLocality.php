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

include_once('updateObjectKeywords.php');
include_once('extLinksRefs.php');
include_once('objectFunctions.php');
include_once('updater.class.php');

$id = $_POST['objId'];
$userId = $objInfo->getUserGroupId();
$groupId = $objInfo->getUserId();
$indexUrl = "index.php?id=$id";
if (!checkAuthorization($id, null, null, 'edit')) {
	header ("location: $indexUrl");
	exit;
}

$latitude = trim($_POST['Latitude']);
$longitude = trim($_POST['Longitude']);
if($_POST['NS'] == '2') {$latitude = !empty($latitude) ? '-' .$latitude : $latitude; }
if($_POST['EW'] == '2') {$longitude = !empty($longitude) ? '-' . $longitude : $longitude; }

$db = connect();

if (empty($_POST['Country'])) {
  $country  = 'UNSPECIFIED';
} else {
  $sql = "select description from Country where description = ?";
  $country = $db->getOne($sql, null, array($_POST['Country']));
  if (isMdb2Error($country, "Selecting country", 5)) {
    header("location: $indexUrl&code=2");
    exit;
  }
  if (empty($country)) {
    $country = strtoupper($_POST['Country']);
    $db->beginTransaction();
    $sql = "insert into Country set description = ?";
    $stmt = $db->prepare($sql);
    $num_rows = $stmt->execute(array($country));
    if (isMdb2Error($num_rows, "Insert value into Country", 5)) {
      $db->rollback();
      header("location: $indexUrl&code=33");
      exit;
    }
    $db->commit();
  }
}

// Get base object contributor
$result = getObjectData('BaseObject', $id, 'userId');
if(is_string($result)) { // Error returned
	header("location: $indexUrl&code=3");
	exit;
}
$baseContributor = $result['userid'];

// Get original locality information
$row = getObjectData('Locality', $id);
if(is_string($row)) {
	header("location: $indexUrl&code=4");
	exit;
}

// Baseobject updater
$baseObjUpdater = new Updater($db, $id, $userId, $groupId, 'BaseObject');
$baseObjUpdater->addField("userId", $_POST['Contributor'], $baseContributor);
$numRowsBO = $baseObjUpdater->executeUpdate();
if (is_string($numRowsBO)) { // Error returned
	header("location: $indexUrl&code=5");
	exit;
}

// Locality updater
$locUpdater = new Updater($db, $id, $userId, $groupId, 'Locality');
$locUpdater->addField("continent", $_POST['continent'], $row['continent']);
$locUpdater->addField("ocean", $_POST['ocean'], $row['ocean']);
$locUpdater->addField("country", $country, $row['country']);
$locUpdater->addField("state", $_POST['state'], $row['state']);
$locUpdater->addField("county", $_POST['county'], $row['county']);
$locUpdater->addField("locality", $_POST['Locality'], $row['locality']);
$locUpdater->addField("latitude", $latitude, $row['latitude']);
$locUpdater->addField("longitude", $longitude, $row['longitude']);
$locUpdater->addField("coordinatePrecision", $_POST['CoordinatePrecision'], $row['coordinateprecision']);
$locUpdater->addField("minimumElevation", $_POST['MinimumElevation'], $row['minimumelevation']);
$locUpdater->addField("maximumElevation", $_POST['MaximumElevation'], $row['maximumelevation']);
$locUpdater->addField("minimumDepth", $_POST['MinimumDepth'], $row['minimumdepth']);
$locUpdater->addField("maximumDepth", $_POST['MaximumDepth'], $row['maximumdepth']);

$numRowsLoc = $locUpdater->executeUpdate();
if (is_string($numRowsLoc)) { // Error returned
	header("location: $indexUrl&code=6");
	exit;
}

// Update keywords
if ($numRowsLoc == 1 || $numRowsBO == 1) {
	updateKeywordsTable($id, 'update');
	$sql = "select id as specid from Specimen where localityId = ?";
	$specRows = $db->getAll($sql, null, array($id), null, MDB2_FETCHMODE_ASSOC);
	if(isMdb2Error($specRows, 'Select specimen ids for locality data', false)) {
		header("location: $indexUrl&code=7");
		exit;
	}
	
	if (!empty($specRows)) {
		foreach ($specRows as $specRow) {
			updateKeywordsTable($specRow['specid'], 'update');
			
			$sql = "select id as imgid from Image where specimenId = " . $specRow['specid'];
			$imgRows = $db->queryAll($sql, null, MDB2_FETCHMODE_ASSOC);
			if(isMdb2Error($imgRows, 'Select image ids for specimen', false)) {
				header("location: $indexUrl&code=8");
				exit;
			}
			if (!empty($imgRows)) {
				foreach ($imgRows as $imgRow) {
					updateKeywordsTable($imgRow['imgid'], 'update');
				}
			}
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

header("location: $indexUrl&code=1");
exit;
?>
