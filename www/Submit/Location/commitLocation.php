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
include_once('urlFunctions.inc.php');
include_once('updater.class.php');
include_once('Classes/UUID.php');

$userId = $objInfo->getUserId();
$groupId = $objInfo->getUserGroupId();
$indexUrl = 'index.php?'.getParamString($_REQUEST);
if(!checkAuthorization(null, $userId, $groupId, 'add')){
	header ("location: index.php");
	exit;
}

$db = connect();

$country = strtoupper(trim($_POST['Country']));
// Check country
if (!empty($country)) {
  $sql = "select description from Country where description = ?";
  $country_check = $db->getOne($sql, null, array($country));
  if (isMdb2Error($country_check, "Selecting country", 5)) {
    header("location: $indexUrl&code=2");
    exit;
  }
  if (empty($country_check)) {
    $db->beginTransaction();
    $sql = "insert into Country set description = ?";
    $stmt = $db->prepare($sql);
    $num_rows = $stmt->execute(array($country));
    if (isMdb2Error($num_rows, "Insert value into Country", 5)) {
      $db->rollback();
      header("location: $indexUrl&code=6");
      exit;
    }
    $db->commit();
  }
}

$latitude = trim($_POST['Latitude']);
$longitude = trim($_POST['Longitude']);
if($_POST['NS'] == '2') {$latitude = !empty($latitude) ? '-' .$latitude : $latitude; }
if($_POST['EW'] == '2') {$longitude = !empty($longitude) ? '-' . $longitude : $longitude; }

$uuid = UUID::v4();
// Insert Object and Locality returning id
$params = array(
    $db->quote("Locality"),
    $_POST['Contributor'],
    $groupId,
    $userId,
    "NOW()",
    $db->quote("Locality added"),
    $db->quote(NULL),
    $db->quote($uuid)
);
$result = $db->executeStoredProc('CreateObject', $params);
if(isMdb2Error($result, 'Create Object procedure', false)) {
	header("location: $indexUrl&code=3");
	exit;
}
$id = $result->fetchOne();
clear_multi_query($result);

$localityUpdater = new Updater($db, $id, $userId, $groupId, 'Locality');
$localityUpdater->addField("continent", $_POST['continent'], null);
$localityUpdater->addField("ocean", $_POST['ocean'], null);
$localityUpdater->addField("country", $country, null);
$localityUpdater->addField("state", $_POST['state'], null);
$localityUpdater->addField("county", $_POST['county'], null);
$localityUpdater->addField("locality", $_POST['Locality'], null);
$localityUpdater->addField("latitude", $latitude, null);
$localityUpdater->addField("longitude", $longitude, null);
$localityUpdater->addField("coordinatePrecision", $_POST['CoordinatePrecision'], null);
$localityUpdater->addField("minimumElevation", $_POST['MinimumElevation'], null);
$localityUpdater->addField("maximumElevation", $_POST['MaximumElevation'], null);
$localityUpdater->addField("minimumDepth", $_POST['MinimumDepth'], null);
$localityUpdater->addField("maximumDepth", $_POST['MaximumDepth'], null);
$numRows = $localityUpdater->executeUpdate();
if (is_string($numRows)) {
	header("location: $indexUrl&code=4&id=' . $id");
	exit;
}

// Insert keywords
if ($numRows > 0) {
	updateKeywordsTable($id);
}

// Update and Add external links and unique references
$updateLinkRes = updateLinks($id, $_POST);
$updateRefRes  = updateReferences($id, $_POST);
if(!$updateLinkRes || !$updateRefRes) {
    header("location: $indexUrl&code=5&id=$id");
    exit;
}

$objTitle = $_POST['Locality'];
header ("location: $indexUrl&code=1&id=$id&objTitle=$objTitle");
exit;
?>
