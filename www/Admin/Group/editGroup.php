<?php
/**
 * File name: editGroup.php
 * @package Morphbank2
 * @subpackage Admin Group
 * @author Robert Bruhn
 */

include_once('head.inc.php');
include_once('showFunctions.inc.php');
include_once('groupFunctions.php');

$includeJavaScript = array('jquery.1-4-2.min.js', 'jquery-ui-1.8.min.js', 'jquery.validate.min.js', 'formMethods.js');

// The beginnig of HTML
$title = 'Edit Group';
initHtml($title, null, $includeJavaScript);

// Add the standard head section to all the HTML output.
echoHead(false, $title);
echo '<div class="mainGenericContainer" style="width:600px">';

$id      = $_GET['id'];
$groupId = $objInfo->getUserGroupId();
$userId  = $objInfo->getUserId();
$code    = $_GET['code'];

// Check authorization
if (!checkGroupEditAuthorization()) {
	echo getNonAuthMessage(5) . "<br /><br />";
} else {
	echo "<h1>Edit Group</h1><br /><br />";
	getGroupEditMsg($id, $code);
	displayGroupForm($id);
}

echo '</div>';
// Finish with end of HTML
finishHtml();
