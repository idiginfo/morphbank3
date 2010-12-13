<?php
/**
 * Return a 4-tuple of values: bytes+width+height+imageType for the requested image
 *
 */

define('PHP_ENTRY',0);// valid Web app entry point
include_once('imageFunctions.php');
// Get object id from parameters, if available
$id = $_REQUEST['id'];
$imgType = strtolower($_REQUEST['imgType']);

$requestServer = $_SERVER['REMOTE_ADDR'];
$responseServer = $_SERVER['SERVER_NAME'];
if($requestServer == $responseServer) { // request is from ME!
	echo "don't call me again like this! $requestServer is calling $responseServer\n";
	exit;
}

if (!empty($id)) {
	$filePath = getImageFilePath($id, $imgType);
	if (!file_exists($filePath)){
		// create request to alt server for size
		$sizes = getRemoteImageSize($id, $imgType);
		echo $sizes;
	} else {
		$wh = checkImageFile($id, $imgType);
		if ($imgType == 'tpc' || $imgType == 'dng'){ // types where getimagesize is wrong
			//TODO decide what to return for tpc reference within an image tag
			$wh = checkImageFile($id, 'jpeg');
		}
		$size = getFileSize($id, $imgType);
		echo "$size+$wh+$imgType";
	}
	return;
}
//TODO return error code in header
echo "0+0+0";

