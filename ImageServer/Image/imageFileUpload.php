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

// TODO make this file capable of handling an image file upload request
// POST only
// id param must be set
// file is either passed by the url parameter or as an inline file
define('PHP_ENTRY',0);// valid Web app entry point

// must be POST request from the Web server to be accepted
$remoteIp = gethostbyname($_SERVER['REMOTE_ADDR']);
$webServerIp = gethostbyname($config->appServer);

//if ($remoteIp !=  $webServerIp || 'POST' != $_SERVER['REQUEST_METHOD']){
if ('POST' != $_SERVER['REQUEST_METHOD']){
	return (0);
}

include_once 'imageProcessing.php';

// get the file and fix it

$id = $_REQUEST['id'];
$tmpName = $_FILES['image']['tmp_name'];
//TODO handle other types of requests
$urlName = $_REQUEST['url'];
$fileName = $_REQUEST['fileName'];
if (empty($id)){
	header("HTTP/1.0: 400 Bad Request");
	echo("no variable 'id' provided");
	die();
} 
if (empty($tmpName)){
	header("HTTP/1.0: 400 Bad Request");
	echo("no image file provided");
	die();
} 
if(empty($fileName)){
	header("HTTP/1.0: 400 Bad Request");
	echo("no variable 'fileName' provided");
	die();
} 
	
$imageType = replaceOriginal ($id, $tmpName, $fileName, null);
list($message,$width,$height) = fixImageFiles($id, $tmpName, $imageType);
if (empty($width)){
	header("HTTP/1.0: 400 Bad Request");
	echo "cannot process image file: $message";
	die();
}
echo "$message^$width^$height^$imageType";

