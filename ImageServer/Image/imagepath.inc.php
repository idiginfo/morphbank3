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
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

if (!defined('PHP_ENTRY')){ die('Cannot be run directly');}

function getStandardImageUrl($id, $imgType, $sessionId = null){
	global $config;
	return getImgUrl($config->imgServerUrl, $id, $imgType, $sessionId = null);
}

function getImgUrl($baseUrl, $id, $imgType, $sessionId = null){
	$url = "$baseUrl?id=$id&imgType=$imgType";
	if (!empty($sessionId)) {
		$url .= "&sessionId=$sessionId";
	}
	return $url;
}

function getImageFilePath($id, $imageType){
	$imgArray = getAccessImagePath($id, $imageType);
	$imgPath = $imgArray['imgSrc'];
	return $imgPath;
}

/**
 * Return directory path for image files for $id
 * path does not include file name
 * @param $id
 * @return string
 */
function getFilePath($id){
	global $DIR_DEPTH, $PAD_LENGTH;
	// First pad image ID to 9 characters
	$id_pad = str_pad($id, 9, "0", STR_PAD_LEFT);
	$directory = substr($id_pad,0,3);
	$directory .= "/" . substr($id_pad,3,3);
	return $directory;
}

function getImageFileName($id, $imgType){
	return "$id.$imgType";
}

function getAccessImagePath ($id, $imgType = 'thumbs', $originalImgType = "", $sessionId = null) {
	global $config;
	$imgPath = array ('imageId' => $id);
	$imgPath = setTypeExtDir($imgPath, $id, $imgType, $originalImgType);
	$imgPath['imgFileName'] = getImageFileName($id, $imgPath['imgExt']);
	$directoryStucture = getFilePath($id);
	$basePath = $imgPath['imgDir']."/$directoryStucture";
	$origBasePath = $imgPath['originalImgDir']."/$directoryStucture";
	$imgPath['imgPath'] = $basePath."/".$imgPath['imgFileName'];
	$imgPath['imgSrc'] = $config->imgRootPath.$imgPath['imgPath'];
	$imgPath['imgUrl'] = getStandardImageUrl($id, $imgPath['imgType'], $sessionId);
	$imgPath['original'] = getStandardImageUrl($id, $imgPath['originalImgType'], $sessionId);
	$imgPath['originalSrc'] = $config->imgRootPath.$origBasePath
	.getImageFileName($id, $imgPath['originalImgType'] );

	$pathOK = makeDirectories($config->imgRootPath.$basePath);
	return $imgPath;
}

function getBackupFilePath($id, $imgType){
	global $BACKUP_IMAGE_PATH_ROOT;
	$imgType = strtolower($imgType);
	if ($imgType=='tiff') $imgType = 'tif';
	$imgFileName = getImageFileName($id, $imgType);
	$directoryStucture = getFilePath($id);
	$imgDir = $BACKUP_IMAGE_PATH_ROOT.$directoryStucture;
	makeDirectories($imgDir);
	$imgSrc = $imgDir.'/'.$imgFileName;
	return $imgSrc;
}

function makeDirectories($path){
	if (file_exists($path)) return true;
	umask(0);
	mkdir($path,0777,true);
	return file_exists($path);
}

/**
 * Set the type, extension and directory attributes for the image
 * Return the values through the imgPath array variables
 * This routine standardizes the values of the attributes to remove dependencies on
 * the exact values coming from the caller. See comments below.
 * @param $imgPath
 * @param $id
 * @param $imgType
 * @param $originalImgType
 * @return associative array
 */
function setTypeExtDir($imgPath, $id, $imgType = 'thumbs', $originalImgType = ""){
	// rules for standardizing image attributes
	// original: tif or tiff dir: tiff ext: tif
	// original: jpg         dir: jpeg ext: jpeg
	// original: jpeg        dir: jpeg ext: jpeg
	// original: other       dir: tiff ext: other
	// imgType: tif or tiff dir: tiff ext: tif
	// imgType: jpg         dir: jpg  ext: jpg
	// imgType: jpeg        dir: jpeg ext: jpeg
	// imgType: thumbs       dir: thumbs ext: jpg

	// make original properties consistent
	$originalImageType = strtolower($originalImgType);
	$originalImageType = ($originalImageType == "jpg") ? "jpeg" : $originalImageType;
	$originalImageDir = ($originalImageType != "jpeg") ? "tiff": $originalImageType;

	//make type consistent
	$imageType = strtolower($imgType);

	if ($imageType=='thumb') $imageType = 'thumbs';

	// make image directory extension consistent
	$imageDir = ($imageType == "tif") ? "tiff" : $imageType;
	$imageExt = ($imageType == 'thumbs') ? "jpg" : $imageType;
	$imageExt = ($imageType == 'tiff') ? "tif" : $imageExt;

	if ($imageType == 'dng' || $imageType == "bmp" || $imageType == "gif" || $imageType == "png"){
		$imageDir='tiff';
		$imageExt=$imageType;
	} else if ($imageType == "tpc") { //tilepic
		$imageDir = 'tpc';
		$imageExt = 'tpc';
	} else if ($imageType != 'thumbs' && $imageType != 'jpg' && $imageType != 'jpeg'
	&& $imageType != 'tif' && $imageType != 'tiff') {
		// case of unknown type, return thumbnail
		$imageDir = 'thumbs';
		$imageExt = 'jpg';
	}
	// setup return values
	$imgPath['imgDir']= $imageDir;
	$imgPath['imgExt']= $imageExt;
	$imgPath['imgType'] = $imageType;
	$imgPath['originalImgType'] = $originalImageType;
	$imgPath['originalImgDir'] = $originalImageDir;
	return $imgPath;
}
?>
