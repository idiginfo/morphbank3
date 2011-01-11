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

$userId    = $objInfo->getUserId();
$groupId   = $objInfo->getUserGroupId();
$id        = $_POST['id'];
$userArray = $_POST['user'];

// Check authorization
$returnUrl = 'editGroupMembers.php?'.getParamString($_REQUEST);
if (!checkGroupEditAuthorization()) {
	header ("location: $returnUrl");
	exit;
}

$defaultRole = ($id == $config->adminGroup) ? 'administrator' : 'guest';

/**
 * Loop through post user array to remove empty values
 * Create new array for checked users
 */
foreach ($userArray as $key => $value) {
	if (empty($userArray[$key]['user'])) {
		unset($userArray[$key]);
	} else {
		$userArray[$key]['usergrouprole'] = empty($userArray[$key]['usergrouprole']) ? 
			$defaultRole : $userArray[$key]['usergrouprole'];
	}
}

/**
 * Loop through row ids present on form page and check for insert/update/delete
 * 
 * If $rowId in checkedUsers and not in rows array, insert user
 */
$db = connect();
foreach ($userArray as $user) {
	$data = array(
		$user['user'],
		$id,
		$userId,
		$db->mdbNow(),
		$db->mdbNow(),
		$db->mdbNow(),
		$user['usergrouprole']
	);
	$sql = "insert into UserGroup "
		. "(user, groups, userId, dateLastModified, dateCreated, dateToPublish, userGroupRole) "
		. "values (?,?,?,?,?,?,?)";
	$stmt = $db->prepare($sql);
	isMdb2Error($affectedRows, "Error preparing query to insert user in group");
	$affRows = $stmt->execute($data);
	isMdb2Error($affRows, "Error inserting user in group");
}
header("location: $returnUrl&code=1");
exit;
