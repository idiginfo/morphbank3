<?php
/**
 * File name: commitGroup.php
 * @package Morphbank2
 * @subpackage Admin Group
 * @author Robert Bruhn
 */


include_once('updater.class.php');

$userId    = $objInfo->getUserId();
$groupId   = $objInfo->getUserGroupId();
$grpName   = $_REQUEST['groupname'];
$returnUrl = '/Admin/Group/';

// Check authorization
if ($groupId != $config->adminGroup) {
	header("location: $returnUrl?code=2");
	exit;
}

$db = connect();
$sql = "select count(*) as count from Groups where groupName = ?";
$count = $db->getOne($sql, null, array($grpName));
isMdb2Error($name, "Check group name exists");
if ($count > 0) {
	header("location: $returnUrl?code=3");
	exit;
}

// Insert BaseObject for Groups
$params = array($db->quote("Groups"), $userId, $groupId, $userId, "NOW()", $db->quote("Group added"), $db->quote(NULL));
$result = $db->executeStoredProc('CreateObject', $params);
isMdb2Error($result, 'Create Object procedure');
$gid = $result->fetchOne();
clear_multi_query($result);

// prepare Groups update
$userUpdater = new Updater($db, $gid, $userId , $groupId, 'Groups');
$userUpdater->addField('groupName', $grpName, null);
$userUpdater->addField('tsn', 0,null);
$userUpdater->addField('groupManagerId', $userId, null);
$userUpdater->addField('status', 1, null);
$userUpdater->addField('dateCreated', $db->mdbNow(), null);
$numRows = $userUpdater->executeUpdate();

// Insert in UserGroup table
$data = array($userId, $gid, $userId, $db->mdbNow(), $db->mdbToday(), 'coordinator');
$sql = "insert into UserGroup (user, groups, userId, dateCreated, dateToPublish, userGroupRole) values (?,?,?,?,?,?)";
$stmt = $db->prepare($sql);
$affRows = $stmt->execute($data);
isMdb2Error($affRows, "Insert new user group");

// success
header("location: /Admin/Group/editGroup.php?id=$gid&code=8");
exit;
