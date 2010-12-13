<?php
/**
 * File name: commitGroupMembers.php
 * @package Morphbank2
 * @subpackage Admin Group
 * @author Robert Bruhn
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
