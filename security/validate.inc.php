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

/*
 File name: validate.inc.php
 @author Neelima Jammigumpula <jammigum@scs.fsu.edu>
 @package Morphbank2
 @subpackage security

 This script checks user login and sets default session variables using MbSessionHandler class.

 Functions:
 - checkAuthorization - Returs true if the user within the group can perform the function required else returns false.
 - updateGroupInfo($groupname): It gets the group TSN, UserGroupRole of the user given the group the user wants to work with. Once the values are obtained from the database, it uses the setUserGroupInfo function to set the corresponding class variables.
 */

function publicSql($id){
	return "SELECT dateToPublish<now() as public FROM BaseObject WHERE id = $id";
}

function getAccessInfo($objectId){
	if (empty($objectId) || $objectId == "0") {
		return null;
	}
	$db = connect();
	$getInfoQuery = "SELECT (dateToPublish < now()) as public, userId, submittedBy, "
	."groupId FROM BaseObject WHERE id=$objectId";

	$result = $db->query($getInfoQuery);
	if (PEAR::isError($result)){
		echo($result->getUserInfo());
		return null;
	}
	$row = $result->fetchRow();
	if ($row) return $row;
	return null;
}

function isPublic($id){
	list($public, $objUserId, $objSubId, $objGroupId) = getAccessInfo($id);
	return $public;
}

/**
 * @param $objectId
 * @param $userId
 * @param $groupId
 * @param $function one of view, add, edit, delete, annotate
 * @return unknown_type
 */
function checkAuthorization($objectId, $userId = null, $groupId = null, $function = 'view'){
	global $objInfo, $config, $nonAuthCode;
	// get user and group from global $objInfo, if necessary
	if (empty($objectId)){
		$objectId = 0;
	} else if (intval($objectId)==0){
		$objectType = $objectId;
		$objectId = 0;
	}

	if (empty($userId)) $userId = $objInfo->userId;
	if (empty($groupId)) $groupId = $objInfo->groupId;
	$function = strtolower($function);

	// allow administrator access
	if ($groupId == $config->adminGroup) return true;
	
	// Disable everything but viewing if config->disableSite value set
	if ($function != 'view' && $config->disableSite == 1) {
	  $nonAuthCode = 7;
	  return false;
	}

	// Handle add
	if($function == 'add' || $function == 'annotate'){
		if (empty($userId)){
			$nonAuthCode = 2; // not logged in
			return false;
		}
		$insertGroups = getUserInsertGroups($userId);
		if (isset($insertGroups[$groupId])) return true; // allowed
		$nonAuthCode = 3; // not allowed
		return false;
	}

	// Get object's user and group
	$row = getAccessInfo($objectId);

	// Cannot continue without an object
	if(is_null($row)) {
		// no object
		$nonAuthCode = 6; // no object with this id
		return false;
	}

	// There is an object, get its public status, users and group
	list($public, $objUserId, $objSubId, $objGroupId) = $row;

	// Allow viewing of public objects
	if ($function == 'view') {
		if($public) return true;
	}

	// No further access without login

	if ($userId == null) {
		if ($function == 'view') $nonAuthCode = 1;
		else $nonAuthCode = 2; // not logged in
		return false;
	}

	// allow any user to annotate or determine
	if($function == 'annotate' || $function == 'determine')	return true;

	// Allow any function if user contributed or submitted the object
	if($objUserId == $userId || $objSubId == $userId) return true;

	// Rest of security rules are valid for the user role in the group of the object
	// and are not dependent on selected group

	// get role of user in object's group
	$userObjRole = getUserGroupRole($userId, $objGroupId);

	// Deny access if user is not in group of object
	if ($userObjRole == null) {
		$nonAuthCode = 4; // not in the group
		return false;
	}

	// Allow view by all roles in the group
	if($function == 'view') return true;

	// Allow edit unless role is guest, scientist, or reviewer
	if($function == 'edit' || $function == 'delete'){
		$editGroups = getUserEditGroups($userId);
		if (isset($editGroups[$objGroupId])) return true;
		$nonAuthCode = 5;
		return false;
	}
	$nonAuthCode = 8;
	return false;
}

/**
 * Get authorization codes
 * 1 = Not logged in and trying to view non-public object
 * 2 = Not logged in and trying to update or insert
 * 3 = Group role is guest or reviewer and is add
 * 4 = Not user's object and not a member of the group
 * 5 = Not user's object and user's role in group does not allow edit/delete
 * 6 = No object with this id
 * 7 = Site updates and additions disabled. Viewing only.
 * 8 = Unexpected result
 */
function getNonAuthCode() {
	global $nonAuthCode;
	return $nonAuthCode;
}

function getUserGroupRole($userId, $groupId){
	global $db;
	// get role of user in selected group
	if (empty($userId)||empty($groupId)){
		return null;
	}
	$db = connect();
	$sql = "SELECT userGroupRole FROM UserGroup WHERE user =$userId AND groups=$groupId";
	$result = $db->query($sql);
	if (PEAR::isError($result)){
		die("SQL Error: " . $result->getMessage() . "<br />" . $result->getUserInfo());
	}
	$role = $result-> fetchOne();
	if (PEAR::isError($role)){
		return null;
	}
	return $role;
}
// this can be removed.
function updateGroupInfo($groupname){
	$sql = 'SELECT Groups.id AS groupId, Groups.tsn, userGroupRole FROM User, UserGroup, Groups
          WHERE User.uin = \'' . $mysessionHandler->userName . '\' AND User.id = UserGroup.user 
            AND UserGroup.groups = Groups.id AND Groups.groupName = \'' . $groupname . '\';';
	$row = mysqli_fetch_array(runQuery($sql));
	if ($row) {
		$mySessionHandler->setUserGroupVariables($groupname, $row['groupId'], $row['userGroupRole'], $row['tsn']);
	}
}

/**
 * Get new objInfo MbSessionHandler from $_SESSION
 *
 * @param $sessionId name of session variable
 * @return MbSessionHandler
 */
function resetObjInfo($sessionVar = 'userInfo'){
	global $objInfo;
	if($_SESSION[$sessionVar]){
		$objInfo = unserialize($_SESSION[$sessionVar]);
	} else {
		$objInfo = new MbSessionHandler();
	}
	return $objInfo;
}

/**
 * Get groups where user is allowed to edit
 * @param $userId
 * @return $data array is returned with group id as key, group name as value
 */
function getUserEditGroups($userId) {
	$db = connect();
	$sql = "select ug.groups, g.groupName from UserGroup ug "
		 . "left join Groups g on g.id = ug.groups "
		 . "where ug.user = $userId and ug.userGroupRole not in ('guest', 'scientist', 'reviewer') "
		 . "order by ug.groups";
	$result = $db->query($sql);
	isMdb2Error($result, "Error selecting user groups for allowed edits");
	$data = $result->fetchAll(MDB2_FETCHMODE_ASSOC, true);
	return $data;
}

/**
 * Get groups where user is allowed to insert
 * @param $userId
 * @return $data array is returned with group id as key, group name as value
 */
function getUserInsertGroups($userId) {
	$db = connect();
	$sql = "select ug.groups, g.groupName from UserGroup ug "
		 . "left join Groups g on g.id = ug.groups "
		 . "where ug.user = $userId and ug.userGroupRole not in ('guest', 'reviewer') "
		 . "order by ug.groups";
	$result = $db->query($sql);
	isMdb2Error($result, "Error selecting user groups for allowed inserts");
	$data = $result->fetchAll(MDB2_FETCHMODE_ASSOC, true);
	return $data;
}

function checkGroupEditAuthorization() {
	global $objInfo;
	$role = $objInfo->getUserGroupRole();
	return ($role == 'administrator' || $role == 'coordinator') ? true : false;
}
?>
