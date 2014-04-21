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

session_register('userInfo');

include_once ('menu.inc.php');

$objInfo = new MbSessionHandler();

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
