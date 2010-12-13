<?php
/**
 * File name: commitLocation.php
 * @package Morphbank2
 * @subpackage Submit Location
 */

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

// Get continent
$sql = "select co.description as continent from ContinentOcean co 
		left join Country c on c.continentOcean = co.name 
		where c.description = ?";
$continent = $db->getOne($sql, null, array($_POST['Country']));
if(isMdb2Error($continent, "Select Continent data", 5)){
	header("location: $indexUrl&code=2");
	exit;
}
$continent = empty($continent) ? 'UNSPECIFIED' : $continent;
$country = empty($_POST['Country']) ? 'UNSPECIFIED' : $_POST['Country'];

$latitude = trim($_POST['Latitude']);
$longitude = trim($_POST['Longitude']);
if($_POST['NS'] == '2') {$latitude = !empty($latitude) ? '-' .$latitude : $latitude; }
if($_POST['EW'] == '2') {$longitude = !empty($longitude) ? '-' . $longitude : $longitude; }

// Insert Object and Locality returning id
$params = array($db->quote("Locality"), $_POST['Contributor'], $groupId, $userId, "NOW()", $db->quote("Locality added"), $db->quote(NULL));
$result = $db->executeStoredProc('CreateObject', $params);
if(isMdb2Error($result, 'Create Object procedure', false)) {
	header("location: $indexUrl&code=3");
	exit;
}
$id = $result->fetchOne();
clear_multi_query($result);

$localityUpdater = new Updater($db, $id, $userId, $groupId, 'Locality');
$localityUpdater->addField("continentOcean", $continent, null);
$localityUpdater->addField("continent", $continent, null);
$localityUpdater->addField("country", $country, null);
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
	
/* Update and Add external links and unique references */
$insertLinkRes = insertLinks($id, $_POST);
$insertRefRes  = insertReferences($id, $_POST);
if(!$insertLinkRes || !$insertRefRes) {
	header("location: $indexUrl&code=5&id=$id");
	exit;
}
$objTitle = $_POST['Locality'];
header ("location: $indexUrl&code=1&id=$id&objTitle=$objTitle");
exit;
?>
