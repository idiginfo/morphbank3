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

<?php 
/**
 * File name: groupFunctions.php
 * @package Morphbank2
 * @subpackage Admin Group
 * @author Robert Bruhn
 */

/**
 * Retrieve groups where user is either administrator or coordinator
 * @param $userId
 */
function getUserGroups($userId) {
	$db = connect();
	$sql = "select g.id as gid, g.groupName, g.groupManagerId, g.status, ug.userGroupRole from Groups g "
		. "left join UserGroup ug on ug.groups = g.id "
		. "where ug.user = $userId and ug.userGroupRole in ('administrator', 'coordinator')";
	$rows = $db->queryAll($sql, null, MDB2_FETCHMODE_ASSOC);
	return $rows;
}

/**
 * Retrieve group information
 * @param $id
 */
function getGroupInfo($id) {
	if (empty($id)) return;
	$db = connect();
	$sql = "select g.*, ug.* from Groups g "
			. "left join UserGroup ug on ug.groups = g.id " 
			. "where g.id = ? and g.groupManagerId = ug.user";
	$row = $db->getRow($sql, null, array($id), null, MDB2_FETCHMODE_ASSOC);
	isMdb2Error($row, "Error selecting group $id information.");
	return !empty($row) ? $row : false;
}

/**
 * Retrieve group name
 * @param $id
 */
function getGroupName($id) {
	if (empty($id)) return;
	$db = connect();
	$sql = "select groupName from Groups where id = ?";
	$name = $db->getOne($sql, null, array($id));
	isMdb2Error($name, "Error selecting group name.");
	return $name;
}

/**
 * Build group search results and return as string
 * @param array $rows DB result
 */
function buildGroupTable($rows, $search = false) {
	global $objInfo, $config;
	$isAdmin = getUserGroupRole($objInfo->getUserId(), $config->adminGroup);
	
	$html = '<h1>Groups</h1>';
	$html .= '<br /><br />'."\n";
	$html .= '<table width="100%" border="1" cellpadding="5">'."\n";
	if ($rows) {
		$html .= '<tr><th>Group ID</th><th>Group Name</th><th>Group Role</th><th>Group Status</th><th></th><tr>'."\n";
		foreach ($rows as $row) {
			$html .= '<tr>'."\n";
			$html .= '<td>'.$row['gid'].'</td>';
			$html .= '<td>'.$row['groupname'].'</td>';
			/*
			 * $groupRole = empty($row['usergrouprole']) ? '' : $row['usergrouprole'];
			$html .= '<td>'.$groupRole.'</td>';
			 */
			$html .= '<td>'.$row['usergrouprole'].'</td>';
			$html .= '<td>' . ($row['status'] == 1 ? 'Public' : 'Private') . '</td>';
			$html .= '<td>';
			if ($isAdmin || $row['usergrouprole'] == 'coordinator') {
				$html .= '<a href="/Admin/Group/editGroup.php?id='.$row['gid'].'" class="button mediumButton" title="Click to edit group"><div>Edit group</div></a>';
				$html .= '<a href="/Admin/Group/editGroupMembers.php?id='.$row['gid'].'" class="button largeButton" title="Click to modify members"><div>Modify Members</div></a>';
				$html .= '<a href="/Admin/Group/addReviewer.php?id='.$row['gid'].'" class="button largeButton" title="Click to create reviewer"><div>Create Reviewer</div></a>';
			}
			$html .= '</td>';
			$html .= '</tr>'."\n";
		}
	} else {
		$html .= '<tr><td>No groups found</td></tr>'."\n";
	}
	$html .= '</table>';
	return $html;
}

/**
 * Display group search form and process searches
 */
function searchGroup() {
	$type = $_REQUEST['type'];
	$term = $_REQUEST['term'];

	$emailChecked = $type == 'email' ? 'checked' : '';
	$uinChecked = $type == 'uin' ? 'checked' : '';
	$idChecked = $type == 'id' ? 'checked' : '';
	$nameChecked = $type == 'name' || empty($type) ? 'checked' : '';
	echo '<h1>Search Groups</h1>';
	echo '<br /><br />';
	echo '<form id="frmSearch" method="get" action="/Admin/Group/">'."\n";
	echo '<input type="hidden" name="search" value="true" />'."\n";
	echo '<b>Search Term</b> <input type="text" name="term" value="'.$term.'" /><br />'."\n";
	echo '<b>Group Name</b><input type="radio" name="type" value="name" '.$nameChecked.' />&nbsp;&nbsp;'."\n";
	echo '<b>User Email</b><input type="radio" name="type" value="email" '.$emailChecked.' />&nbsp;&nbsp;'."\n";
	echo '<b>Username</b><input type="radio" name="type" value="uin" '.$uinChecked.' />&nbsp;&nbsp;'."\n";
	echo '<b>User Id</b><input type="radio" name="type" value="id" '.$idChecked.' />&nbsp;&nbsp;'."\n";
	echo '<br /><br />';
	echo '<input type="submit" class="button smallButton" value="Search" style="float:left; margin-right:20px;" />&nbsp;&nbsp;'."\n";
	echo '</form>'."\n";
	echo '<form id="frmAddGroup" method="post" action="commitGroup.php">'."\n";
	echo '<input type="text" id="grpName" name="groupname" value="Enter New Group Name" style="float:right;" />'."\n";
	echo '<br /><br />';
	echo '<input type="submit" class="button mediumButton" value="Add Group" title="Click to add new group" style="float:right; margin-right:20px;" />&nbsp;&nbsp;'."\n";
	echo '</form>';
	echo '<hr style="clear:both; margin-top:20px;" />';
	
	if (!empty($term)) {
		switch ($type) {
			case 'name':
				$col = 'g.groupName like';
				$param = array('%'.$term.'%');
				$groupRole = '';
				break;
			case 'uin':
				$col = 'u.uin like';
				$param = array('%'.$term.'%');
				$groupRole = ', ug.userGroupRole';
				break;
			case 'id':
				$col = 'ug.user =';
				$param = array($term);
				$groupRole = ', ug.userGroupRole';
				break;
			default:
				$col = 'u.email like';
				$param = array('%'.$term.'%');
				$groupRole = ', ug.userGroupRole';
				break;
		}
		$db = connect();
		$sql = "select g.id as gid, g.groupName, g.groupManagerId, g.status $groupRole from Groups g "
		. "left join UserGroup ug on ug.groups = g.id "
		. "left join User u on u.id = ug.user "
		. "where $col ? group by g.id order by g.groupName";
		$rows = $db->getAll($sql, null, $param, null, MDB2_FETCHMODE_ASSOC);
		isMdb2Error($results, "Search Group Query");
		return $rows;
	}
}

/**
 * Display User search form and search results
 */
function searchUser($id, $gmId, $users) {
	$type = $_GET['type'];
	$term = $_GET['term'];
	
	$userIds = '';
	foreach ($users as $user) {
		$userIds .= $user['id'] . ',';
	}
	$userIds = rtrim($userIds, ',');

	$emailChecked = $type == 'email' || empty($type) ? 'checked="checked"' : '';
	$uinChecked = $type == 'uin' ? 'checked="checked"' : '';
	$idChecked = $type == 'id' ? 'checked="checked"' : '';
	$fnameChecked = $type == 'fname' ? 'checked="checked"' : '';
	$lnameChecked = $type == 'lname' ? 'checked="checked"' : '';
	echo '<h1>Search Users</h1>';
	echo '<br /><br />';
	echo '<form id="frmUserSearch" method="get" action="editGroupMembers.php">'."\n";
	echo '<input type="hidden" name="search" value="true">'."\n";
	echo '<input type="hidden" name="id" value="'.$id.'">'."\n";
	echo '<input type="hidden" name="gmId" value="'.$gmId.'">'."\n";
	echo '<b>Search Term</b> <input type="text" name="term" value="'.$term.'" /><br />'."\n";
	echo '<b>Email</b><input type="radio" name="type" value="email" '.$emailChecked.' />&nbsp;&nbsp;'."\n";
	echo '<b>Username</b><input type="radio" name="type" value="uin" '.$uinChecked.' />&nbsp;&nbsp;'."\n";
	echo '<b>User Id</b><input type="radio" name="type" value="id" '.$idChecked.' />&nbsp;&nbsp;'."\n";
	echo '<b>First Name</b><input type="radio" name="type" value="fname" '.$fnameChecked.' />'."\n";
	echo '<b>Last Name</b><input type="radio" name="type" value="lname" '.$lnameChecked.' />'."\n";
	echo '<br /><br />';
	echo '<input type="submit" class="button smallButton" value="Search" style="float:left; margin-right:20px;" />&nbsp;&nbsp;'."\n";
	echo '</form>'."\n";
	echo '<hr style="clear:both; margin-top:20px;" />';
	
	if (!empty($term)) {
		switch ($type) {
			case 'uin':
				$col = 'uin like';
				$param = array('%'.$term.'%');
				break;
			case 'fname':
				$col = 'first_Name like';
				$param = array($term.'%');
				break;
			case 'lname':
				$col = 'last_Name like';
				$param = array($term.'%');
				break;
			case 'id':
				$col = 'id =';
				$param = array($term);
				break;
			default:
				$col = 'email like';
				$param = array('%'.$term.'%');
				break;
		}
		
		$db = connect();
		$sql = "select distinct * from User where $col ? and id not in ($userIds) and last_Name <> 'Reviewer' order by last_Name, first_Name";
		$results = $db->getAll($sql, null, $param, null, MDB2_FETCHMODE_ASSOC);
		isMdb2Error($results, "Search User to add to group");
		showUsers($id, $gmId, $results, false);
	}
}

/**
 * Show users from search or group members
 * @param $id
 * @param $gmId
 * @param $array
 * @param $current
 */
function showUsers($id, $gmId, $rows, $current = true) {
	
	
	$returnUrl = getReturnUrl();
	
	if ($current) {
		echo '<br></br><h2>Current Group Members</h2>';
		$frmAction = 'modifyGroupMembers.php';
		$txtButton = 'Update';
	} else {
		echo '<br></br><h2>Choose Members To Add</h2>';
		$frmAction = 'commitGroupMembers.php';
		$txtButton = 'Add';
	}
	
	if (!empty($rows)) {
		echo '<br />';
		echo '<form id="frmMemberGroup" name="'.$frmAction.'" method="post" action="'.$frmAction.'">'."\n";
		echo '<input type="hidden" name="id" value="' . $id . '" />'."\n";
		echo '<input type="hidden" name="gmId" value="' . $gmId . '" />'."\n";
     	echo '<input type="hidden" name="returnUrl" value="' . $returnUrl . '">'."\n";
		echo '<table width="100%" border="2">
				<tr>
    				<td align="center"><b>User Name</b></td>
				    <td align="center"><b>User Role</b></td>
				</tr>';
		$count = 0;
		foreach ($rows as $row) {
			$role = UserinGroup($id, $row['id']);
			$checked = !empty($role) ? 'checked="checked"' : '';
			echo '<tr><td valign="middle">'."\n";
			echo 	'<input type="checkbox" name="user['.$count.'][user]" '.$checked.' value="'.$row['id'].'" />';
			echo    '&nbsp;&nbsp;' . $row['name'];
			echo '<input type="hidden" name="rowid[]" value="' . $row['id'] . '" />';
			echo '</td><td>'."\n";
			echo buildRoleSelect($id, $gmId, $row['id'], $role, $count);
			echo '</td></tr>'."\n";
			$count++;
		}
		echo '</table>'."\n";
	} else {
		getMemberEditMsg($id, 4);
	}
	echo "<br></br>";
	echo '<div class="frmError" style="text-align:right"></div>';
	echo '<table align="right" border="0">
	      	<tr>
	        	<td><input type="submit" class="button smallButton" value="'.$txtButton.'" /></td>
	            <td><a href="' . $returnUrl . '" class="button smallButton" title="Click to return to Group page">
	                <div>Return</div></a>
	            </td>
	        </tr>
	      </table>
	     </form>';
}

/**
 * Get group users
 * @param $id
 * @param $return Returns array of users instead of list
 */
function getGroupUsers($id, $return = false) {
	$db = connect();
	$sql = "select * from User u left join UserGroup ug on ug.user = u.id where ug.groups = ? order by last_name";
	$results = $db->getAll($sql, null, array($id), null, MDB2_FETCHMODE_ASSOC);
	isMdb2Error($results, "Error selecting users for group id $id");
	
	if ($return) return $results;
	
	$list = '';
	if (!empty($results)) {
		foreach ($results as $row) {
			$list .= $row['name'] . "\n";
		}
	}
	return $list;
}

/**
 * Get status select options
 * @param $val
 */
function getStatusOptions($val = 0) {
	$publicSelect = $val == 1 ? 'selected="selected"' : '';
	$privateSelect = $val == 0 ? 'selected="selected"' : '';
	$html = '<option value="1" ' . $publicSelect . '>Public group</option>
             <option value="0" ' . $privateSelect . '>Not a public group</option>';
	return $html;
}

/**
 * Get reviewer
 * @param $id
 */
function getReviewer($id) {
	$db = connect();
	$sql = "select u.id from User u "
		. "left join UserGroup ug on ug.user = u.id "
		. "where u.last_Name='Reviewer' and ug.groups = ?";
	$reviewerId = $db->getOne($sql, null, array($id));
	isMdb2Error($reviewerId, 'Select reviewer id: ' . $sql);
	return $reviewerId;
}

/**
 * Display group edit form
 * @param $id
 */
function displayGroupForm($id = null) {
	global $objInfo;
	
	$returnUrl     = getReturnUrl();
	$row           = getGroupInfo($id);
	$userList      = getGroupUsers($id);
	$statusOptions = getStatusOptions($row['status']);
	$reviewerId    = getReviewer($id);
	include_once('frmGroup.php');
}

/**
 * Display add reviewer form
 * @param $id
 */
function displayReviewerForm($id) {
	$returnUrl = getReturnUrl();
	$grpName   = getGroupName($id);
	include_once('frmReviewer.php');
}

/**
 * Build select box for user roles
 * @param int $id Group id
 * @param string $role Current role
 */
function buildRoleSelect($id, $gmId, $userId, $role, $count) {
	global $config;
	
	$role = $gmId == $userId ? 'coordinator' : strtolower($role);
	
	if (isset($_REQUEST['search'])) {
		$roles = ($id == $config->adminGroup) ? 
			array('administrator') : array('leadscientist', 'scientist', 'guest');
	} else {
		$roles = ($id == $config->adminGroup) ? 
			array('administrator', 'coordinator') : array('coordinator', 'leadscientist', 'scientist', 'guest');
	}
	
	$html = '';
	if ($role == 'reviewer') {
		$html = '<input size="13" type="text" name="user['.$count.'][usergrouprole]" value="reviewer" readonly="readonly" />';
	} else {
		$html  = '<select class="duplicate" name="user['.$count.'][usergrouprole]">';
		$html .= '<option value="">None</option>';
		foreach ($roles as $type) {
			$selected = $role == $type ? 'selected="selected"' : '';
			$html .= '<option value="'.$type.'" '.$selected.'>'.$type.'</option>';
		}
		$html .= '</selected>';
	}
	return $html; 
}

/**
 * Get return url
 */
function getReturnUrl() {
	if (!isset($_REQUEST['returnUrl'])) {
		if (preg_match('/code=/', $_SERVER['QUERY_STRING'])) {
			$returnUrl = '/Admin/Group/';
		} else {
			$returnUrl = $_SERVER['HTTP_REFERER'];
		}
	} else {
		$returnUrl = $_REQUEST['returnUrl'];
	}
	return $returnUrl;
}

/**
 * Echo messages
 * @param integer $id
 * @param integer $code
 * @return void
 */
function getGroupEditMsg($id, $code) {
	if (empty($code)) return;
	if ($code == 1) {
		echo "<h3>You have successfully updated the <a href=\"/?id=$id\">Group with id $id</a></h3><br /><br />\n";
	} elseif ($code == 2) {
		echo '<div class="searchError">No groups with ID $id exist</div><br /><br />';
	} elseif ($code == 3) {
		echo '<div class="searchError">Error selecting group $id information</div><br /><br />';
	} elseif ($code == 4) {
		echo '<div class="searchError">Error preparing update query for user group</div><br /><br />';
	} elseif ($code == 5) {
		echo '<div class="searchError">Error updating user roles for group</div><br /><br />';
	} elseif ($code == 6) {
		echo '<div class="searchError">No changes submitted for group</div><br /><br />';
	} elseif ($code == 7) {
		echo '<div class="searchError">Internal processing error while updating group</div><br /><br />';
	} elseif ($code == 8) {
		echo "<h3>You have successfully added the <a href=\"/?id=$id\">Group with id $id</a></h3><br /><br />\n";
	}
	return;
}

/**
 * Echo messages
 * @param integer $id
 * @param integer $code
 * @return void
 */
function getMemberEditMsg($id, $code) {
	if (empty($code)) return;
	if ($code == 1) {
		echo "<h3>You have successfully updated the <a href=\"/?id=$id\">Group with id $id</a></h3><br /><br />\n";
	} elseif ($code == 2) {
		echo '<div class="searchError">You do not have permission to modify members</div><br /><br />';
	} elseif ($code == 3) {
		echo '<div class="searchError">Group Id missing</div><br /><br />';
	} elseif ($code == 4) {
		echo '<div class="searchError">No search results found</div><br /><br />';
	}
	return;
}

/**
 * Echo messages
 * @param integer $id
 * @param integer $code
 * @return void
 */
function getIndexMsg($code) {
	if (empty($code)) return;
	if ($code == 2) {
		echo '<div class="searchError">You do not have permissions</div><br /><br />';
	} elseif ($code ==3) {
		echo '<div class="searchError">Group name exists</div><br /><br />';
	}
	return;
}

/**
 * Echo messages
 * @param integer $id
 * @param integer $code
 * @return void
 */
function getReviewerMsg($id, $code) {
	if (empty($code)) return;
	if ($code == 1) {
		echo "<h3>You have successfully added a Reviewer to <a href=\"/?id=$id\">Group id $id</a></h3><br /><br />\n";
	}
	return;
}

/**
 * Echo messages
 * @param integer $id
 * @param integer $code
 * @return void
 */
function getGroupAddMsg($id, $code) {
	if (empty($code)) return;
	if ($code == 1) {
		echo "<h3>You have successfully added the <a href=\"/?id=$id\">Group with id $id</a></h3><br /><br />\n";
	}
	return;
}
