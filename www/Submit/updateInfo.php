<?php

/*
 File name: updateInfo.inc.php 
 @author Neelima Jammigumpula <jammigum@scs.fsu.edu>
 @package Morphbank2
 @subpackage Submit 

 This script gets the group the user is interested in and sets session variables to appropriate values based on user group. setUserGroupInfo function will be used for this purpose in the near future.

*/
include_once('head.inc.php');


checkIfLogged();
groups();

if(ereg("Mirror", $_SERVER['HTTP_REFERER'])){
	header('Location: ' .$config->domain. 'Mirror/?code=no');
	exit;
}
if ($_GET['fromLogin']) {
	header('Location: ' .$config->domain. 'MyManager');
	exit;
}

$userId = $objInfo->getUserId();
$groupId = $objInfo->getUserGroupId();

$sql = "select g.id, g.groupName, g.tsn, ug.userGroupRole from Groups g  
		left join UserGroup ug on ug.groups = g.id 
		left join User u on u.id = ug.user 
		where u.id = ? and g.id = ?";
$row = $db->getRow($sql, null, array($userId, $_GET['group']), null, MDB2_FETCHMODE_ASSOC);
isMdb2Error($row, "Select user group query", 4);

if($row){
	$sql = "select scientificName from Tree where tsn = '" . $row['tsn'] . "'";
	$name = $db->getOne($sql);
	isMdb2Error($row, "Select scientific name query", 4);
}

if($row && $name){
   	$objInfo->setUserGroupInfo($row['id'], $row['groupName'], $row['userGroupRole'], $row['tsn'], $name);
    $_SESSION['userInfo'] = serialize($objInfo);
}
$url = $_SERVER['HTTP_REFERER'];

if($groupId == $config->adminGroup || $_GET['group'] == $config->adminGroup){
	header('Location: ' .$config->domain. 'Admin');
	exit;
}else if(ereg("Reviewer", $url)){
	header('Location: ' .$config->domain. 'user.php'); 
	exit;
}
header('Location: ' .$url);
exit;
?>
