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

/**
 * Display User search form and search results
 */
function searchUser() {
	$type = $_REQUEST['type'];
	$term = $_REQUEST['term'];

	$emailChecked = $type == 'email' || empty($type) ? 'checked' : '';
	$uinChecked = $type == 'uin' ? 'checked' : '';
	$idChecked = $type == 'id' ? 'checked' : '';
	$nameChecked = $type == 'name' ? 'checked' : '';
	echo '<h1>Search Users</h1>';
	echo '<br /><br />';
	echo '<form id="frmSearch" method="get" action="/Admin/User/">'."\n";
	echo '<b>Search Term</b> <input type="text" name="term" value="'.$term.'" />&nbsp;&nbsp;&nbsp;'."\n";
	echo '<b>User Id</b><input type="radio" name="type" value="id" '.$idChecked.' />&nbsp;&nbsp;'."\n";
	echo '<b>User Name</b><input type="radio" name="type" value="uin" '.$uinChecked.' />&nbsp;&nbsp;'."\n";
	echo '<b>Name</b><input type="radio" name="type" value="name" '.$nameChecked.' />'."\n";
	echo '<b>Email</b><input type="radio" name="type" value="email" '.$emailChecked.' />&nbsp;&nbsp;'."\n";
	echo '<br /><br />';
	echo '<input type="button" class="button smallButton" onClick="parent.location=\'/Admin/User/add\'" style="float:right" value="Add User" />';
	echo '<input type="submit" class="button smallButton" value="Search" style="float:right; margin-right:20px;" />&nbsp;&nbsp;'."\n";
	echo '</form>'."\n";
	echo '<br />';
	echo '<hr style="clear:both; margin-top:20px;" />';
	
	if (!empty($term)) {
		switch ($type) {
			case 'uin':
				$col = 'uin like';
				$param = array('%'.$term.'%');
				break;
			case 'name':
				$col = 'name like';
				$param = array('%'.$term.'%');
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
		$sql = "select * from User where $col ?";
		$results = $db->getAll($sql, null, $param, null, MDB2_FETCHMODE_ASSOC);
		isMdb2Error($results, "Search User Query");
		$html = buildUserTable($results);
		echo $html;
	}
}

/**
 * Build user search results and return as string
 * @param array $array
 * @param array $html
 */
function buildUserTable($array) {
	$html = '<br /><br />'."\n";
	$html .= '<table width="100%" border="1" cellpadding="5">'."\n";
	if ($array) {
		$html .= '<tr><th>User Id</th><th>User Name</th><th>Name</th><th>Email</th><tr>'."\n";
		foreach ($array as $row) {
			$html .= '<tr>'."\n";
			$html .= '<td><a href="edit/'.$row['id'].'">'.$row['id'].'</a></td>';
			$html .= '<td>'.$row['uin'].'</td>';
			$html .= '<td>'.$row['name'].'</td>';
			$html .= '<td>'.$row['email'].'</td>';
			$html .= '</tr>'."\n";
		}
	} else {
		$html .= '<tr><td>No users found</td></tr>'."\n";
	}
	$html .= '</table>';
	return $html;
}

/**
 * Display form for adding User
 */
function addUser($array) {
	global $objInfo, $config;
	$groupId = $objInfo->getUserGroupId();
	if ($groupId != $config->adminGroup) {
		echo getMessage(6);
		return;
	}
	showForm('add', $array);
	return;
}

function newUser($array) {	
	showForm('new', $array);
	return;
}

/**
 * Edit existing user
 * @param $id
 */
function editUser($id) {
	global $objInfo, $config;
	
	$userId = $objInfo->getUserId();
	$groupId = $objInfo->getUserGroupId();

	if (empty($id)) {
		$id = $userId; // user editing self
	} elseif ($userId != $id && $groupId != $config->adminGroup) {
		echo getMessage(2);
		return;
	}
	
	$db = connect();
	$sql = "select * from User where id = ?";
	$row = $db->getRow($sql, null, array($id), null, MDB2_FETCHMODE_ASSOC);
	if (isMdb2Error($row, "Edit User Query")) {
		getMessage(3);
		return;
	}
	showForm('edit', $row);
	return;
}

/**
 * Show user form
 * @param string $action (new, edit, signup)
 * @param array $row User array if editing
 */
function showForm($action, $row) {
	global $objInfo, $config;
	$groupId = $objInfo->getUserGroupId();
	
	$edit = false;
	$new  = false;
	if ($action == 'edit'){
		$edit = true;
		$formId = 'frmEditUser';
		$userInfo = '<br /><br /><h2>'.$row['first_name'].' '.$row['last_name'].' with user id: '.$row['uin'].'</h2><br/<br/>';
		$action = '/Admin/User/modifyUser.php';
		$buttonText = 'Update';
		$title = '<br /><h1>Update User</h1>';
		$passwordRequired = false;
	} elseif ($action == 'add') {
		$formId = 'frmAddUser';
		$userInfo = "<br /><br />";
		$action = '/Admin/User/commitUser.php';
		$buttonText = 'Add';
		$title = '<br /><h1>Add User</h1>';
		$passwordRequired = true;
	} elseif ($action == 'new') {
		$new = true;
		$formId = 'frmNewUser';
		$userInfo = "<br /><br />";
		$action = '/Admin/User/commitUser.php';
		$buttonText = 'Submit';
		$title = '<br /><h1>New User</h1>';
		$passwordRequired = true;
	}
	echo $title;
	echo $userInfo;
	echo '<strong><span class="req">* Required</span></strong>';

	echo '<form method="post" action="'.$action.'" id="'.$formId.'" enctype="multipart/form-data">';
	echo '<table width="600px" border="0">'."\n";
	
	if ($edit) echo getFormHiddenField('id', $row['id']);
	
	echo getFormFieldRow('First Name', '', 'first_name', 'text',32, $row['first_name'], true);
	echo getFormFieldRow('Last Name', '', 'last_name', 'text',32, $row['last_name'], true);
	echo getFormFieldRow('Middle Initial', '', 'middle_init', 'text',32, $row['middle_init'], false);
	echo getFormFieldRow('Suffix', '', 'suffix', 'text', 10, $row['suffix'], false);
	
	if (!$edit)	echo getFormFieldRow('User Name', '', 'uin', 'text', 32, $row['uin'], true);
	
	echo getFormPasswordField($passwordRequired);
	
	if ($groupId == $config->adminGroup) echo printaccountstatus($row['status']);
	
	echo getFormFieldRow('Email Address', '', 'email', 'text',32, $row['email'], true);
	echo getFormFieldRow('Affiliation', '', 'affiliation', 'text',32, $row['affiliation'], true);
	echo getFormFieldRow('Street Address', '', 'street1', 'text',32, $row['street1'], false);
	echo getFormFieldRow('Street Address', '', 'street2', 'text',32, $row['street2'], false);
	echo getFormFieldRow('City', '', 'city', 'text',32, $row['city'], false);
	echo getStateSelector('State', 'Select a state', 'state', $row['state'],false);
	echo getCountrySelector('Country','Select a country','country',$row['country'],true);
	echo getFormFieldRow('Zip/Postal Code', '', 'zipcode', 'text',10, $row['zipcode'], false);
	
	if ($edit) echo getGroupSelect('Preferred Group', 'preferredgroup', $row['id'], $row['preferredgroup'], false);
	if (!$edit) {
		echo printMailingList();
		if ($new) {
			echo getFormFieldRow('Your Resume/CV', 'Upload you resume/cv', 'userresume', 'file', 32, '', true);
			echo printCVNote();
			echo printSpamPrevent();
		}
	}
		
	if (!$new) echo getFormFieldRow('User logo', 'Add/Change personal/organizational logo','userlogo','file', 32, '', false);
	if (!$new) echo getFormFieldRow('User link', 'Logo URL', 'logourl', 'text', 32, $row['logourl'], false);
	
	echo printButtons($buttonText);

	echo '</table></form></div>';
	
	return;
}

/**
 * View and manage existing cv files
 * @param $array
 */
function viewCV($array) {
	global $config;
	
	if (isset($array['delete']) && !empty($array['delete'])) {
		if (file_exists($config->cvFolder . $array['delete'])) {
			unlink($config->cvFolder . $array['delete']);
		}
	}
	if ($handle = opendir($config->cvFolder)) {
		while (false !== ($file = readdir($handle))) {
			echo '<ul style="list-style:none">';
			if ($file != "." && $file != "..") {
				echo '<li><a href="/Admin/User/getCV.php?cv='.$file.'" target="_blank">'.$file.'</a>&nbsp;&nbsp;';
				echo '<a href="/Admin/User/cv?delete='.$file.'"><img src="/style/webImages/delete-trans.png" /></a></li>';
			}
			echo '</ul>';
		}
		closedir($handle);
	} else {
		echo "No files exist";
	}
}

/**
 * Get messages for given codes
 * @param $code
 */
function getMessage($code) {
	if ($code == 1) {
		return "<br /><br /><h3>You have updated user successfully</h3><br /><br />";
	} elseif ($code == 2) {
		echo '<br /><br /><div class="searchError">You do not have permission to edit this user</div>'."\n";
	} elseif ($code == 3) {
		return '<br /><br /><div class="searchError">Error selecting user information</div>'."\n";
	} elseif ($code == 4) {
		return '<br /><br /><div class="searchError">No changes submitted for user</div>'."\n";
	} elseif ($code == 5) {
		return '<br /><br /><div class="searchError">Internal processing error while updating user</div>'."\n";
	} elseif ($code == 6) {
		return '<br /><br /><div class="searchError">You do not have permissions to add users</div>'."\n";
	} elseif ($code == 7) {
		return '<br /><br /><div class="searchError">Error uploading file</div>'."\n";
	} elseif ($code == 8) {
		return '<br /><br /><div class="searchError">Spam code does not match image</div>'."\n";
	} elseif ($code == 9) {
		return '<br /><br /><div class="searchError">Error inserting BaseObject for user</div>'."\n";
	} elseif ($code == 10) {
		return '<br /><br /><div class="searchError">Error updating User</div>'."\n";
	} elseif ($code == 11) {
		return '<br /><br /><div class="searchError">Error inserting BaseObject for Groups</div>'."\n";
	} elseif ($code == 12) {
		return '<br /><br /><div class="searchError">Error updating Groups</div>'."\n";
	} elseif ($code == 13) {
		return '<br /><br /><div class="searchError">Error inserting user into UserGroup</div>'."\n";
	} elseif ($code == 14) {
		return "<br /><br /><h3>Thank you. An email has been sent to the administrator.<br />You will be contacted shortly when your account is activated.<br />Please allow 24 to 48h before submitting the form again.</h3><br /><br />";
	} elseif ($code == 15) {
		return "<br /><br /><h3>You have added user successfully</h3><br /><br />";
	} elseif ($code == 16) {
		return '<br /><br /><div class="searchError">A resmue upload is required</div>'."\n";
	} elseif ($code == 17) {
		return '<br /><br /><div class="searchError">Please fill in all required fields</div>'."\n";
	}
	return;
}

/**
 * Print buttons for form submit
 * @param $buttonText
 */
function printButtons($buttonText) {
	$button .= '<table width="600"><tr>';
	$button .= '<td align="right">';
	$button .= '<input type="submit" class="button smallButton" value="' . $buttonText . '" />';
	$button .= '</td>';
	$button .= '</tr></table>';
	return $button;
}

/**
 * Displays the account status of a user on the User Add/Update screen.
 * @param $val
 */
function printaccountstatus($val) {
	if ($val == 1) {
		$aselected = 'selected';
		$dselected = null;
	} else {
		$aselected = null;
		$dselected = 'selected';
	}
	$html = '<tr><td><b>Account Status: </b></td>'
	.' <td><select name="accountstatus" title="Activate or Deactivate">'
	.' <option value="1" ' . $aselected . '>Active Account</option>'
	.'<option value="0" ' . $dselected . '>Inactive Account</option>'
	.'</select>'
	.' </td></tr>';
	return $html;
}

/**
 * Print mailing list checkbox
 */
function printMailingList() {
	$html = '<tr><td colspan="2">';
	$html .= '<b>Subscribe to the Morphbank mailing list</b>&nbsp;';
	$html .= '<input type="checkbox" name="subscription" value="1" checked="checked"><br /><br /></td>';
	$html .= '</tr>';
	return $html;	
}

/**
 * Print note for ResumsCV
 */
function printCVNote() {
	$html = '<tr><td colspan="2">';
	$html .= '<span style="color:#17256B"><b>Note: Your resume/CV will be used for verification of your expertise</b></span>';
	$html .= '</td></tr>';
	return $html;
}

/**
 * Display spam protection for signup form
 */
function printSpamPrevent() {
	
	$codeArray = getSpamCode();
	$html = '
	<tr><td colspan="2"><br /><br />
	<table border="0" width="100%">  
    	<tr>
        	<td><br /><h3>Please type the letters in this picture into the box below. </h3><br /><em>(To prevent spam. Not case sensitive.)</em><br /><br /></td>
	    </tr>
	    <tr>
	        <img src="/style/webImages/codes/' . $codeArray['graphic'] . '" alt="graphic" /></td>
	    </tr>
	    <tr>
	        <td>
	        	<input type="text" name="spamcode" value="" />
	        	<input type="hidden" name="spamid" id="spamid" value="'.$codeArray['id'].'" />
	        </td>
	    </tr>
	</table></td></tr>';
	return $html;
}
