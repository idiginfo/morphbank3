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

//TODO return image id so that a request can be serviced for any object with a thumburl

$sessionId = $_REQUEST['sessionId'];
if (!empty($sessionId) && preg_match('/^[a-z0-9]*$/', $sessionId)) {
	session_id($sessionId);
	session_start();
}

// must be POST request from the Web server to be accepted
$remoteIp = gethostbyname($_SERVER['REMOTE_ADDR']);
$imageServerIp = gethostbyname($config->imgServer);

$id = $_REQUEST['id'];
$function = $_REQUEST['function'];
if (empty($function)) $function = 'view';
$r = '';

include_once ('includes/imageFunctions.php');

// get the representative image for the object
$imageId = getObjectImageId($id);

$public = isPublic($imageId);

if ($public && $function=='view') {
	$authorized = true;
	$r .= "empty session authorized: $authorized<br/>";
} else if ($remoteIp !=  $imageServerIp || 'POST' != $_SERVER['REQUEST_METHOD']){
	// not a post request from THE image server
	//TODO add function isImageServer to check for any authorized server
	$authorized = false;
	$r .= "unauthorized requesting server: $remoteIp ";

} else {// legitimage request for non-public object
	$objInfo = resetObjInfo();
	$userId = $objInfo->getUserId();
	$groupId = $objInfo->getUserGroupId();
	$authorized = checkAuthorization($imageId, $userId, $groupId, $function);
	$r.= "checkAuth userId $userId, authorized $authorized";
	$r.= "\nNonAuth code is: ".(getNonAuthCode())."\n";
}

if ($authorized === true || $authorized == 1) {
	echo "true $imageId";
} else {
	echo "false $r";
}

?>
