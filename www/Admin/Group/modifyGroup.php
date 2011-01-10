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
