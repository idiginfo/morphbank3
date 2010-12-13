<?php
require_once('../configuration/image.server.php');
define('PHP_ENTRY',0);// valid Web app entry point

$requestServer = $_SERVER['REMOTE_ADDR'];
$responseServer = $_SERVER['SERVER_NAME'];
if($requestServer == $responseServer) { // request is from ME!
	echo "don't call me again like this! $requestServer is calling $responseServer\n";
	exit;
}

// Get object id from parameters, if available
$id = $_REQUEST['id'];
$imgType = $_REQUEST['imgType'];
$imgSize = $_REQUEST['imgSize'];

// session Id from server for use in accessing private images
$sessionId = $_REQUEST['sessionId'];
$requestor = $_SERVER['REMOTE_ADDR'];

// for testing purposes
if (empty($id) || empty($imgType)){
	header("HTTP/1.1 400 Bad Request");
	return;
}

if (!empty($id)) {
	// request is for an image file
	include_once('image.class.php');
	$image = new Image($id, $imgType, $imgSize, $sessionId);
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

if (empty($config->imgServerAltUrl) || serverRequest($requestor)) {
	// no one else to ask
	header("HTTP/1.1 401 Unauthorized");
	return;
}

header('Content-Type: image/png');
readfile($config->webPath.'/style/webImages/'.$config->imgPrivate);

function isModifiedSince($fileTime) {
	if(!array_key_exists("HTTP_IF_MODIFIED_SINCE", $_SERVER)) return true;
	$requestModTime = strtotime(preg_replace('/;.*$/','',$_SERVER["HTTP_IF_MODIFIED_SINCE"]));
	return $requestModTime < $fileTime;
}

function serverRequest($serverName){
	global $config;
	if ($serverName==$congif->imgServer) return true;
	return false;
}


