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

include_once('showFunctions.inc.php');
include_once('updater.class.php');

$userId    = $objInfo->getUserId();
$groupId   = $objInfo->getUserGroupId();
$id        = $_POST['id'];
$gmId      = $_POST['gmId']; // group manager id
$origGmId  = $_POST['gmId']; // group manager id
$rowIds    = $_POST['rowid']; // id array of users on page
$rowIdList = implode(',', $rowIds);

// Check authorization
$returnUrl = "editGroupMembers.php?id=$id";
if (!checkGroupEditAuthorization()) {
	header ("location: $returnUrl");
	exit;
}

$defaultRole = ($id == $config->adminGroup) ? 'administrator' : 'guest';

/**
 * Loop through post user array to remove empty values
 * Create new array for checked users
 */
$userArray = $_POST['user'];
foreach ($userArray as $key => $value) {
	if (is_null($userArray[$key]['user']) || $userArray[$key]['user'] == "") {
		unset($userArray[$key]);
	} else {
		$role = empty($userArray[$key]['usergrouprole']) ? 
			$defaultRole : $userArray[$key]['usergrouprole'];
		$checkedUsers[$userArray[$key]['user']] = $role;
	}
}

/**
 * Select all current users of group in existing rowids except group manager
 * Result is returned with user id as array key
 */
$db = connect();
$sql = "select * from UserGroup where user in($rowIdList) and groups = ?";
$rows = $db->getAll($sql, null, array($id), null, MDB2_FETCHMODE_ASSOC, true);
isMdb2Error($rows, "Select information for UserGroup $id");

/**
 * Loop through row ids present on form page and check for update/delete
 * 
 * If $rowId in checkedUsers and database rows, update user information if different
 * If $rowId not in checkUsers and in database rows, delete user
 */
foreach ($rowIds as $rowId) {	
	/* update existing members */
	if (isset($checkedUsers[$rowId]) && isset($rows[$rowId])) {
		if ($id == $config->adminGroup) {
			$role = $checkedUsers[$rowId] == 'coordinator' ? 'administrator' : $checkedUsers[$rowId];
		} else {
			$role = $checkedUsers[$rowId];
		}
		$sql = "update UserGroup set userGroupRole = ? where user = ? and groups = ?";
		$stmt = $db->prepare($sql);
		isMdb2Error($stmt, "Error preparing query to update group member");
		$affRows = $stmt->execute(array($role, $rowId, $id));
		isMdb2Error($affRows, "Error executing query to update group member");
		$stmt->free();
		
		/* set group manaager id to whoever is coordinator */
		$gmId = ($checkedUsers[$rowId] == 'coordinator') ? $rowId : $gmId;
		
		/* update history */
		$modification = "'userRole: " . $rows[$rowId]['usergrouprole'] . "',' userRole: " . $checkedUsers[$rowId] . "'";
	}
	/* delete existing members */
	else if (!isset($checkedUsers[$rowId]) && isset($rows[$rowId])) {
		$sql = "delete from UserGroup where user = ? and groups = ? limit 1";
		$stmt = $db->prepare($sql);
		isMdb2Error($stmt, "Error preparing query to delete group member");
		$affRows = $stmt->execute(array($rowId, $id));
		isMdb2Error($affRows, "Error executing query to delete group member");
		$stmt->free();
		
		if ($rows[$rowId]['usergrouprole'] == 'reviewer') {
			$sql = "delete from User where id = ? limit 1";
			$stmt = $db->prepare($sql);
			isMdb2Error($stmt, "Error preparing query to delete User");
			$affRows = $stmt->execute(array($rowId));
			isMdb2Error($affRows, "Error executing query to delete User");
			$stmt->free();
			
			$sql = "delete from BaseObject where id = ? limit 1";
			$stmt = $db->prepare($sql);
			isMdb2Error($stmt, "Error preparing query to delete user from base object");
			$affRows = $stmt->execute(array($rowId));
			isMdb2Error($affRows, "Error executing query to delete user from base object");
			$stmt->free();
		}
		
		/* update history */
		$modification = "'user: ".$rows[$rowId]."',' removed from group: ".$id."'";
	}
	$sql = "insert into History (id, userId, groupId, dateModified, modifiedFrom, modifiedTo, tableName) values (";
	$sql .= $rowId . "," . $userId . "," . $groupId . ",NOW()," . $modification . "," . $db->quote('UserGroup') .")";
	$result = $db->exec($sql);
	isMdb2Error($result, "Error inserting History for UserGroup");
}

/**
 * If original group manager id is not equal to gmId, update accordingly
 */
$updater = new Updater($db, $id, $userId , $id, 'Groups');
$updater->addField("groupManagerId", $gmId, $origGmId);
$numRows = $updater->executeUpdate();

header("location: $returnUrl&code=1");
exit;
