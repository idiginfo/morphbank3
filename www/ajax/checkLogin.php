<?php
session_register('userInfo');

include_once ('menu.inc.php');

$objInfo = new sessionHandler();

// call this in separate method, not constructor.
$objInfo->setDomainName($config->domain);

if($objInfo->checkLogin($_POST['username'], $_POST['password'], $link)) {
	$_SESSION['userInfo'] = serialize($objInfo);

	//echo "objInfo = {'loginBox' : '".outputLoginInfo()."', 'groupMenu' : '".populateGroupMenu()."' }";
	echo '
	<div id="ajaxLoginInfoId">';
	outputLoginInfo();
	echo '</div>';

	echo '<div id="ajaxGroupListId">';
	populateGroupMenu();
	echo '</div>';
	
	echo '<div id="ajaxToolMenuId">';
	populateToolMenuContents($objInfo);
	echo '</div>';
} else {
	// For login form to work correctly with incorrect login, must only return false
	echo 'false';
}

?>
