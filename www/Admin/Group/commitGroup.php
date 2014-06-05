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
$grpName   = $_REQUEST['groupname'];
$returnUrl = '/Admin/Group/addGroup.php';
$uuid = UUID::v4();

// Check authorization
if ($groupId != $config->adminGroup) {
	header("location: $returnUrl?code=2");
	exit;
}

$db = connect();
$sql = "select count(*) as count from Groups where groupName = ?";
$count = $db->getOne($sql, null, array($grpName));
isMdb2Error($count, "Check group name exists");
if ($count > 0) {
	header("location: $returnUrl?code=3");
	exit;
}

// Insert BaseObject for Groups
$params = array($db->quote("Groups"), $userId, $groupId, $userId, "NOW()", $db->quote("Group added"), $db->quote(NULL), $db->quote($uuid));
$result = $db->executeStoredProc('CreateObject', $params);
isMdb2Error($result, 'Create Object procedure');
$gid = $result->fetchOne();
clear_multi_query($result);

$coordinator = $_POST['coordinator'];

// prepare Groups update
$userUpdater = new Updater($db, $gid, $userId , $groupId, 'Groups');
$userUpdater->addField('groupName', $grpName, null);
$userUpdater->addField('tsn', 0,null);
$userUpdater->addField('groupManagerId', $coordinator, null);
$userUpdater->addField('status', 1, null);
$userUpdater->addField('dateCreated', $db->mdbNow(), null);
$numRows = $userUpdater->executeUpdate();

// Insert in UserGroup table
$data = array($coordinator, $gid, $userId, $db->mdbNow(), $db->mdbToday(), 'coordinator');
$sql = "insert into UserGroup (user, groups, userId, dateCreated, dateToPublish, userGroupRole) values (?,?,?,?,?,?)";
$stmt = $db->prepare($sql);
$affRows = $stmt->execute($data);
isMdb2Error($affRows, "Insert new user group");

// success
header("location: /Admin/Group/editGroup.php?id=$gid&code=8");
exit;
