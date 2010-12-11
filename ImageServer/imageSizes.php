#-------------------------------------------------------------------------------
# Copyright (c) 2010 Greg Riccardi, Fredrik Ronquist.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Public License v2.0
# which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# 
# Contributors:
#   Fredrik Ronquist - conceptual modeling and interaction design
#   Austin Mast - conceptual modeling and interaction design
#   Greg Riccardi - initial API and implementation
#   Wilfredo Blanco - initial API and implementation
#   Robert Bruhn - initial API and implementation
#   Christopher Cprek - initial API and implementation
#   David Gaitros - initial API and implementation
#   Neelima Jammigumpula - initial API and implementation
#   Karolina Maneva-Jakimoska - initial API and implementation
#   Katja Seltmann - initial API and implementation
#   Stephen Winner - initial API and implementation
#-------------------------------------------------------------------------------
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

