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
/**
 * File name: modifyUser.php
 * @package Morphbank2
 * @subpackage Admin User
 */


include_once('updater.class.php');
include_once('objectFunctions.php');
include_once('updateObjectKeywords.php');
include_once('urlFunctions.inc.php');

	
$userId = $objInfo->getUserId();
$groupId = $objInfo->getUserGroupId();
$id = $_POST['id'];
$editUrl = "/Admin/User/edit/?id=$id";

if ($userId != $id && $groupId != $config->adminGroup) {
	header("location: $editUrl&code=2");
	exit;
}

$first_name   = $_POST['first_name'];
$last_name    = $_POST['last_name'];
$middle_init  = $_POST['middle_init'];
$suffix       = $_POST['suffix'];
$pin          = $_POST['pin'];
$email        = $_POST['email'];
$status       = $_POST['accountstatus'];
$affiliation  = $_POST['affiliation'];
$street1      = $_POST['street1'];
$street2      = $_POST['street2'];
$city         = $_POST['city'];
$country      = $_POST['country'];
$state        = $_POST['state'];
$zipcode      = $_POST['zipcode'];
$pref_group   = $_POST['preferredgroup'];
$logoURL      = $_POST['logourl'];
$name         = trim($first_name) . " " . trim($last_name);
$address      = trim($street1) . " " . trim($street2) . " " . trim($city) . " " . trim($state) . " " . trim($country) . " " . trim($zipcode);


$db = connect();
$sql = "select * from User where id = ?";
$row = $db->getRow($sql, null, array($id), null, MDB2_FETCHMODE_ASSOC);
if (isMdb2Error($row, "Update User Select Query", 5)) {
	header("location: $editUrl&code=3");
	exit;
}

$updater = new Updater($db, $id, $userId , $groupId, 'User');
$updater->addField("first_Name", $first_name, $row['first_name']);
$updater->addField("last_Name", $last_name, $row['last_name']);
$updater->addField("middle_init", $middle_init, $row['middle_init']);
$updater->addField("suffix", $suffix, $row['suffix']);

if (!empty($pin)){
	$updater->addPasswordField('pin', $pin, $row['pin']);
}
if ($groupId == $config->adminGroup) {
	$updater->addField("status", $status, $row['status']);
}
$updater->addField("email", $email, $row['email']);
$updater->addField("affiliation", $affiliation, $row['affiliation']);
$updater->addField("street1", $street1, $row['street1']);
$updater->addField("street2", $street2, $row['street2']);
$updater->addField("city", $city, $row['city']);
$updater->addField("state", $state, $row['state']);
$updater->addField("country", $country, $row['country']);
$updater->addField("zipcode", $zipcode, $row['zipcode']);
$updater->addField("privilegeTSN", 0, $row['privilegetsn']); // TODO Default 0. Eventually remove
$updater->addField("primaryTSN", 0, $row['primarytsn']); // TODO Default 0. Eventually remove
$updater->addField("secondaryTSN", 0, $row['secondarytsn']); // TODO Default 0. Eventually remove
$updater->addField("preferredGroup", $pref_group, $row['preferredgroup']);
$updater->addField("logoURL", $logoURL, $row['logourl']);
$updater->addField("address", $address, $row['address']);
$updater->addField("name", $name, $row['name']);

if (isset($_FILES['userlogo']) && ($_FILES['userlogo']['name'] > "")) {
	$new_image = $_FILES['userlogo']['name'];

	$simple_name = substr($new_image, 0, strpos($new_image, "."));
	$simple_name .= $id;
	$image_new = $simple_name . substr($new_image, strpos($new_image, "."), strlen($new_image) - 1);
	$userLogo = $config->appServerBaseUrl . '/images/userLogos/' . trim($image_new);

	$tmpFile = $_FILES['userlogo']['tmp_name'];
	move_uploaded_file($tmpFile, $config->userLogoPath . $image_new);
	exec("chmod 755 " . $config->userLogoPath . $image_new);

	$updater->addField("userLogo", $userLogo, $row['userlogo']);
	@unlink('Admin/User/cv/'.$row['userlogo']);
}

$numRows = $updater->executeUpdate();

if ($numRows == 1){
	header("location: $editUrl&code=1");
	exit;
} elseif (empty($numRows)) {
	header("location: $editUrl&code=4");
	exit;
} else {
	header("location: $editUrl&code=5");
	exit;
}
