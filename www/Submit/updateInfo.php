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
