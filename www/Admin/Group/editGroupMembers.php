<?php 
/**
 * File name: editGroupMembers.php
 * @package Morphbank2
 * @subpackage Admin Group
 * @author Robert Bruhn
 */

include_once('head.inc.php');
include_once('showFunctions.inc.php');
include_once('urlFunctions.inc.php');
include_once('Admin/admin.functions.php');
include_once('groupFunctions.php');

$includeJavaScript = array('jquery.1-4-2.min.js', 'jquery-ui-1.8.min.js', 'jquery.validate.min.js', 'formMethods.js');

// The beginnig of HTML
$title = 'Select Group Membership';
initHtml($title, null, $includeJavaScript);


// Add the standard head section to all the HTML output.
echoHead(false, $title);

$id     = $_GET['id'];
$search = $_GET['search'];
$code   = $_GET['code'];
$row    = getGroupInfo($id);
$header = isset($search) ? 'Add' : 'Modify';

echo '<div class="mainGenericContainer" style="width:600px">';
echo '<br></br><h2>'.$header.' Group Members</h2>';

echo '<p><span style="color:#17256B"><b>Group Name: '.$row['groupname'].'</b></span></p>';
$gmId = $row['groupmanagerid'];

if (!checkGroupEditAuthorization()) {
	getMemberEditMsg($id, 2);
} else {
	if (!empty($id)) {
		getMemberEditMsg($id, $code);
		$users = getGroupUsers($id, true);
		searchUser($id, $gmId, $users);
		if (empty($search)) {
			showUsers($id, $gmId, $users);
		}
	} else {
		getMemberEditMsg($id, 3);
	}
}
echo '</div>';
finishHtml();
