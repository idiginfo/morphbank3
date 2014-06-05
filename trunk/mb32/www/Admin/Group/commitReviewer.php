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

include_once('updater.class.php');
include_once('Classes/UUID.php');
 
$userId    = $objInfo->getUserId();
$groupId   = $objInfo->getUserGroupId();
$gid       = $_POST['id'];
$firstName = $_POST['first_Name'];
$lastName  = $_POST['last_Name'];
$uin       = $_POST['uin'];
$pin       = $_POST['pin'];
$name      = trim($firstName) . " " . trim($lastName);
$returnUrl = "/Admin/Group/addReviewer.php";
$uuid = UUID::v4();

if (!checkGroupEditAuthorization()) {
	header("location: $returnUrl?id=$gid");
}

$db = connect();
// Insert BaseObject for User
$params = array(
    $db->quote("User"),
    $userId,
    $groupId,
    $userId,
    "NOW()",
    $db->quote("Added new reviewer to database"),
    $db->quote(NULL),
    $db->quote($uuid)
);
$result = $db->executeStoredProc('CreateObject', $params);
isMdb2Error($result, 'Create Object procedure');
$id = $result->fetchOne();
clear_multi_query($result);

/* Prepare user updater */
$userUpdater = new Updater($db, $id, $userId , $groupId, 'User');
$userUpdater->addField('last_Name', $lastName, null);
$userUpdater->addField('first_Name', $firstName,null);
$userUpdater->addField('uin', $uin, null);
$userUpdater->addPasswordField('pin', $pin, null);
$userUpdater->addField('status', 1, null);
$userUpdater->addField('privilegeTSN', 0, null); // TODO Default 0. Eventually remove
$userUpdater->addField('primaryTSN', 0, null);   // TODO Default 0. Eventually remove
$userUpdater->addField('secondaryTSN', 0, null); // TODO Default 0. Eventually remove
$userUpdater->addField('name', $name, null);
$userUpdater->addField("preferredGroup", $gid, null);
$numRows = $userUpdater->executeUpdate();

// Insert in UserGroup table
$data = array($id, $gid, $userId, $db->mdbNow(), $db->mdbToday(), 'reviewer');
$sql = "insert into UserGroup (user, groups, userId, dateCreated, dateToPublish, userGroupRole) values (?,?,?,?,?,?)";
$stmt = $db->prepare($sql);
$affRows = $stmt->execute($data);
isMdb2Error($affRows, "Insert reviewer into user group");

header("location: $returnUrl?id=$gid&code=1");
exit;
