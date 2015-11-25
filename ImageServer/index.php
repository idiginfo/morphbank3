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

define('PHP_ENTRY',0);// valid Web app entry point

include_once('checkAuthorization.php');

$requestServer = $_SERVER['REMOTE_ADDR'];
$responseServer = $_SERVER['SERVER_NAME'];
if($requestServer == $responseServer) { // request is from ME!
	echo "don't call me again like this! $requestServer is calling $responseServer\n";
	exit;
}

// Get object id from parameters, if available
$id = $_REQUEST['id'];
if (!intval($id)){// id is not an integer, must be a URI
	// get id from web server
	$uri = $id;
	$id = getIdFromURI($uri);
	if (empty($id)){
		header("HTTP/1.1 404 Not Found: No image with id '$uri'");
		return;
	}
}

$imgType = strtolower($_REQUEST['imgType']);
if ($imgType=='tif') $imgType = 'tiff';

$imgSize = $_REQUEST['imgSize'];

// session Id from server for use in accessing private images
$sessionId = $_REQUEST['sessionId'];
$requestor = $_SERVER['REMOTE_ADDR'];

// for testing purposes
if (empty($id) || empty($imgType)){
	header("HTTP/1.1 400 Bad Request");
	return;
}
die("here");

if (!empty($id)) {
	// request is for an image file
	include_once('image.class.php');
	$image = new Image($id, $imgType, $imgSize, $sessionId);
        //file cannot be found on the server
        if(!$image->getFileExists()) {
            header("HTTP/1.1 404 Not Found: The requested object is not on the server");
            exit();
        }
	if(imageRequesterIsBot() && !$image->allowedBotImages()){
		header("HTTP/1.1 403 Forbidden");
		exit();
	}
	//TODO test for authorization
	if (serverRequest($requestor) || $image->getAuthorized()===true){
		if (!isModifiedSince($image->getFileTime())) {
			header("HTTP/1.1 304 Not Modified");
			exit();
		}

		$imageShowed = $image->showImage();
		if ($imageShowed) return;
	}
}

//TODO respond to unauthorized image or no image id

if ( $image->getAuthorized()!=true){
header('Content-Type: image/png');
readfile($config->webPath.'/style/webImages/'.$config->imgPrivate);
return;
}
if (empty($config->imgServerAltUrl) || serverRequest($requestor)) {
	// no one else to ask
	header("HTTP/1.1 401 Unauthorized");
	return;
}


//TODO issue proper error message: not authorized
header('Content-Type: image/png');
readfile($config->webPath.'/style/webImages/'.$config->imgPrivate);

function isModifiedSince($fileTime) {
	if(!array_key_exists("HTTP_IF_MODIFIED_SINCE", $_SERVER)) return true;
	$requestModTime = strtotime(preg_replace('/;.*$/','',$_SERVER["HTTP_IF_MODIFIED_SINCE"]));
	return $requestModTime < $fileTime;
}

function serverRequest($serverName){
	global $config;
	if ($serverName==$config->imgServer) return true;
	return false;
}


