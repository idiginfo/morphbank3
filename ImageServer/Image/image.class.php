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

//these read GET from index.php and see if the image might be shown or not and resized,
//extends mbImage.class.php in includes and does not have anything to do with image
//display inside application
if (!defined('PHP_ENTRY')){ die('Cannot be run directly');}

include_once('imageFunctions.php');
include_once('checkAuthorization.php');

class Image {

	protected $mimeType;
	protected $resizedImagePath;
	protected $imageSize;
	protected $imageFilePath;
	protected $imageId;
	protected $imageExt;
	protected $imagePath; // array of image properties from imagepath.inc.php
	protected $fileSize;
	protected $fileExists;
	protected $headRequest;
	protected $authorized;

	public function __construct($id, $imageType = "thumbs", $imgSize = NULL, $sessionId = null) {
		// Use imageType jpeg for image resize and show
		if (!empty($imgSize)) $imageType = "jpeg";

		$this->imageId = $id;

		$this->imagePath = getAccessImagePath($id, $imageType);// use getObjectImagePath for standard image
		if (!empty($this->imagePath['imageId'])) {// using representative image
			$this->imageId = $this->imagePath['imageId'];
		}
		$this->imageExt = $this->imagePath['imgExt'];
		$this->imageFilePath = $this->imagePath['imgSrc'];
		$this->imageType = $this->imagePath['imgType'];
		// get the file from the remote server, if necessary
		$this->fileExists = $this->getImageFile();

		// check for an HTTP HEAD request
		$this->headRequest = ($_SERVER['REQUEST_METHOD'] == 'HEAD');

		if ($this->fileExists) {
			if (!empty($imgSize)) {
				$this->resizedImagePath = resizeImage($this->imageFilePath, $this->imgSize);
				$this->fileSize = @filesize($this->resizedImagePath);
			} else {
				$this->fileSize = @filesize($this->imageFilePath);
			}
		} else {
			//TODO estimate content-length
			$this->fileSize = null;
		}
		$this->authorized = 'blank';// mark as uninitialized
		$this->setMimeType();
	}

	/**
	 * Check if the file exists and is of the correct type. If not, get it from the server
	 * Return false if the file copy fails
	 * @return boolean
	 */
	function getImageFile(){
		global $config;
		if (checkImageFile($this->imageId, $this->imageType)) return true;
		// check to see if there is an alternate image server
		if (empty($config->imgServerAltUrl)) return false;

		$imgServerAltUrl = getImgUrl($config->imgServerAltUrl, $this->imageId, $this->imageType, $config->imgServerUrl);
		$result = @copy ($imgServerAltUrl, $this->imageFilePath);
		return checkImageFile($this->imageId, $this->imageType);
	}


	function setMimeType(){
		switch ($this->imageExt) {
			case 'jpg':
				$this->mimeType = 'Content-Type: image/jpeg';
				break;
			case 'jpeg':
				$this->mimeType = 'Content-Type: image/jpeg';
				break;
			case 'thumbs':
				$this->mimeType = 'Content-Type: image/jpeg';
				break;
			case 'bmp':
				$this->mimeType = 'Content-Type: image/bmp';
				break;
			case 'png':
				$this->mimeType = 'Content-Type: image/png';
				break;
			case 'tiff':
				$this->mimeType = 'Content-Type: image/tiff';
				break;
			case 'tif':
				$this->mimeType = 'Content-Type: image/tiff';
				break;
			case 'dng':
				$this->mimeType ='Content-Type: application/force-download';
				break;
			case 'tpc':
				// special case see outputMimeType for disposition
				$this->mimeType = 'Content-Type: application/octet-stream';
		}
	}

	public function outputMimeType() {
		header("Cache-Control: public");
		header($this->mimeType);
		header('Last-Modified: ' . date("r", $this->getFileTime()));
		// additional header info for some types
		if ($this->imageType == 'dng') {
			$fileName = $this->imageId.'.'.$this->imageExt;
			header("Content-Disposition: inline; filename=\"".$this->imagePath['imgFileName']);
			header("Content-Transfer-Encoding: Binary");
			if ($this->fileSize!=null) header("Content-length: $this->fileSize");
			header("Content-Disposition: attachment; filename=\"$fileName\"");
		} else if ($this->imageType == 'tpc') {
			header("Content-Disposition: inline; filename=\"$filePath\"");
			header('Content-Transfer-Encoding: Binary');
		}
	}

	/**
	 * function to call to show the image after object is created
	 * @return
	 */
	public function showImage() {
		$imgFilePath = $this->imageFilePath;
		if ($this->resizedImagePath) $imgFilePath = $this->resizedImagePath;
		if (!file_exists($imgFilePath)){
			return false;
		}
		//TODO get and check mime type
		if ($this->imageType!='tpc'){
			list($w,$h,$t) = @getimagesize($imgFilePath);
			if ($w==0) return false;
		}
		$this->outputMimeType();
		if (!$this->headRequest) {
			readfile($imgFilePath);
		}
		return true;
	}

	function allowedBotImages() {
		switch ($this->imageExt) {
			case 'jpg':
			case 'thumbs':
				return true;
				break;
			default:
				return false;
		}
	}

	/**
	 * Return the authorization status
	 * Call checkAuthorization if authorized is 'blank'
	 * @return unknown_type
	 */
	function getAuthorized(){
		if ($this->imageType == "thumbs") {
			$this->authorized = true;
		} else if ($this->authorized == 'blank'){
			$authorizedMessage = checkAuthorization($this->imageId);
			// if message starts with "true"
			if (strncasecmp("true",$authorizedMessage,4)==0){
				$this->authorized = true;
			} else {
				$this->authorized = false;
			}
		}
		return ($this->authorized);
	}
	function getMimeType() {
		return $this->mimeType;
	}
	function getResizedImagePath(){
		return $this->resizedImagePath;
	}
	function getImageSize() {
		return $this->imageSize;
	}
	function getImageFilePath(){
		return $this->imageFilePath;
	}
	function getImageId(){
		return $this->imageId;
	}
	function getImageExt(){
		return $this->imageExt;
	}
	function getFileSize(){
		return $this->fileSize;
	}
	function getSessionId(){
		return $this->sessionId;
	}
	function getFileTime(){
		return @filemtime($this->imageFilePath);
	}
}
?>
