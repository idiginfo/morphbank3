<?php
/**
 * File name: modifyGroup.php
 * @package Morphbank2
 * @subpackage Admin Group
 */


include_once('showFunctions.inc.php');
include_once('urlFunctions.inc.php');
include_once('updater.class.php');

$userId         = $objInfo->getUserId();
$groupId        = $objInfo->getUserGroupId();
$id             = $_POST['id'];
$groupName      = $_POST['groupname'];
$status         = $_POST['groupstatus'];
$returnUrl      = "/Admin/Group/editGroup.php?id=$id";

if (!checkGroupEditAuthorization()) {
	header("location: $returnUrl");
}

$db = connect();
$sql = "select * from Groups where id = ?";
$row = $db->getRow($sql, null, array($id), null, MDB2_FETCHMODE_ASSOC);
if (isMdb2Error($row, "Error selecting group $id information.", 5)) {
	header("location: $returnUrl&code=3");
	exit;
}

$updater = new Updater($db, $id, $userId , $id, 'Groups');
$updater->addField("groupName", $groupName, $row['groupname']);
$updater->addField("tsn", 0, $row['tsn']);
$updater->addField("status", $status, $row['status']);
$numRows = $updater->executeUpdate();
if ($numRows == 1){
	//updateKeywordsTable($id, 'update');
	header("location: $returnUrl&code=1");
	exit;
} elseif (empty($numRows)) {
	header("location: $returnUrl&code=6");
	exit;
} else {
	header("location: $returnUrl&code=7");
	exit;
}
