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

if (!defined('PHP_ENTRY')){ die('Cannot be run directly');}

/**
 * File name: imageFunctions.php
 * This file provides image access functions that incorporate metadata properties
 *
 * Main function is getObjectImagePath which returns the imgPath from imagepath.inc.php
 * 	The imgPath is associated with the thumbnail image of the object
 *
 * Additional properties added to imagepath
 * 	imageId: the id of the image that represents the object
 * 	fileExists and urlExists: true if file and url are available
 * 	authorized: true if image can be viewed by the user/group
 *
 * TODO add some technique for finding and estimating sizes of non-local images
 *
 */
include_once("imagepath.inc.php");

/**
 * Return the default image for a particular type if access restriction
 * @param $style	can be 'notfound' or 'private'
 * @return unknown_type
 */
function getDefaultImagePath($style){
	global $DEFAULT_IMAGES;
	$imageId = null;
	$imgUrl = $DEFAULT_IMAGES[$style];
	$imgSrc = $DEFAULT_IMAGES[$style.'Path'];
	return array('imgUrl'=> $imgUrl, 'imgSrc'=>$imgSrc);
}


/**
 * check type of file for consistency with imageType
 * return width and height, if getimagesize returns them
 *
 * @param $imgType
 * @return string width+height if file exists and is of the correct image type
 */
function checkImageFile($id, $imageType, $imageFile = null){
	// file exists, check for file consistency
	if ($imageFile == null) $imageFile = getImageFilePath($id, $imageType);
	if (!file_exists($imageFile)) return false;
	$imageType = strtolower($imageType);
	if ($imageType == 'tpc'){
		$tpcProps = getTpcProperties($imageFile);
		if ($tpcProps) return true;
		return false;
	}
	list ($width, $height, $imageTypeCode) = @getimagesize($imageFile);
	$wh = $width.'+'.$height;
	if($imageType=='jpg' || $imageType == 'thumb'|| $imageType == 'thumbs' || $imageType == 'jpeg') {
		if ($imageTypeCode == IMAGETYPE_JPEG) return $wh;
	} else if($imageType=='gif') {
		if ($imageTypeCode == IMAGETYPE_GIF) return $wh;
	} else if($imageType=='png') {
		if ($imageTypeCode == IMAGETYPE_PNG) return $wh;
	} else if($imageType=='bmp') {
		if ($imageTypeCode == IMAGETYPE_BMP) return $wh;
	} else if($imageType=='tiff') {
		if ($imageTypeCode == IMAGETYPE_TIFF_II || $imageTypeCode == IMAGETYPE_TIFF_MM) return $wh;
	} else if ($imageType == 'dng'){
		// note that a this does not distinguish dng from any other tiff
		if ($imageTypeCode == IMAGETYPE_TIFF_II || $imageTypeCode == IMAGETYPE_TIFF_MM) return $wh;
	}

	// unused codes
	//9 	IMAGETYPE_JPC
	//10 	IMAGETYPE_JP2
	//11 	IMAGETYPE_JPX
	//12 	IMAGETYPE_JB2
	//13 	IMAGETYPE_SWC
	//14 	IMAGETYPE_IFF
	//15 	IMAGETYPE_WBMP
	//16 	IMAGETYPE_XBM

	return false;
}

function getDefaultImage($defaultName){
	global $config;
	$returnArray = array();
	$returnArray['imgSrc'] = $config->webPath.'/style/webImages/defaultThumbs/'.$defaultName.'.png';
	$returnArray['imgUrl'] = $config->appServerBaseUrl.'/style/webImages/defaultThumbs/'.$defaultName.'.png';
	return $returnArray;
}

function getDefaultObjectTypePath($objectTypeId){
	$objectType = strtolower($objectTypeId);
	switch ($objectType) {
		case "mbcharacter":
			return getDefaultImage('collection');
		case "taxonconcept":
			return getDefaultImage('taxonConcept');
		case "taxon name":
			return getDefaultImage('taxonName');
		case "characterstate":
			return getDefaultImage('characterState');
		case "phylocharstate":
			return getDefaultImage('characterState');
	}
	return getDefaultImage($objectType);
}

/**
 * Call php getimagesize if $imgPath is a local file
 * otherwise return (0,0,0) (for unknown)
 * @param $imgPath
 * @return unknown_type
 */
function getImgSize($filePath){
	if (! file_exists($filePath)) return false;
	return @getimagesize($filePath);
}

function getFileSize($id, $imgType){
	$filePath = getImageFilePath($id, $imgType);
	return filesize($filePath);
}

function getTpcProperties($tpcFilePath){
	global $config;
	include_once ("bischen/tileserver/TilepicParser.inc");
	if (!file_exists($tpcFilePath)) return false;
	$tilePicParser = new TilePicParser($tpcFilePath);
	if ($tilePicParser->error) return false;
	$properties = $tilePicParser->properties;
	if (empty($properties['width'])) return false;
	return $properties;
}

/**
 * Create a resized image file according to parameters. Return path of new image file
 * @param $imageFilePath
 * @param $imageSize
 * @return string
 */
function resizeImage($imageFilePath, $imageSize) {
	global $config;
	$resizedImagePath = $config->imgTmpDir.mktime().'.jpeg';
	$imgSizeArray = explode("x", $imgSize);
	$cmd = $config->imagemagik.'convert'.$imageFilePath.' -resize '.$imgSizeArray[0].'x'.$imgSizeArray[1]
	.' '.$resizedImagePath;
	$string = shell_exec($cmd);
	if(file_exists($resizedImagePath)) {
		return $resizedImagePath;
	}
	return null;
}

function getRemoteImageSize($id, $imageType){
	$imageUrl = getImageSizeUrl($id, $imageType);
	if (empty($imageUrl)) return false;
	$handle = fopen($imageUrl,'r');
	if (!$handle) return false;
	$contents = '';
	while (!feof($handle)) {
		$contents .= fread($handle, 100);
	}
	fclose($handle);
	return $contents;
}

function getImageSizeUrl($id, $imgType){
	global $config;
	if (!$config->imgServerAltUrl) return null;
	return $config->imgServerAltUrl."imageSizes.php/?id=$id&imgType=$imgType";
}

?>
