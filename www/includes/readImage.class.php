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

//these read GET from index.php and see if the image might be shown or not and resized, extends mbImage.class.php in includes and does not have anything to do with image display inside morphbank

include_once('mbImage.class.php');

class readImage extends mbImage {

	protected $mimeTypeArray;
	protected $resizedImgPath;
	protected $imgSize;
	protected $fileSize;
	protected $headRequest;

	public function __construct($link, $id, $imgType = "thumbs", $imgSize = NULL) {

		// if the imgSize is NULL (not given) then use supplied imgType.  Else use jpeg for image resize and show
		$this->imgType = ($imgSize == NULL) ? $imgType : "jpeg";

		parent::__construct($link, $id, $imgType);
		$this->imgPath = $this->getImage();

		// for an HTTP HEAD request, return only the header
		$this->headRequest = stripos($_SERVER['REQUEST_METHOD'], 'HEAD') !== FALSE;

		$this->mimeTypeArray = array(
				'jpg' => 'Content-Type: image/jpeg',
				'jpeg' => 'Content-Type: image/jpeg',
				'thumbs' => 'Content-Type: image/jpeg',
				'bmp' => 'Content-Type: image/bmp',
				'png' => 'Content-Type: image/png',
				'tiff' => 'Content-Type: image/tiff',
				'tif' => 'Content-Type: image/tiff');
		if ($this->id != null){
			if ($imgSize != NULL) {
				$this->resizeImg($imgSize);
			} else {
				if (file_exists($this->imgPath)) {
					$this->fileSize = filesize($this->imgPath);
				}
				//TODO estimate content-length
			}
		}
	}

	public function setMimeType($type, $fileSize) {
		// if the imageType is in the mimeTypeArray (which means the browser can read it.
		// then set the mime type appropriately
		if (array_key_exists($type, $this->mimeTypeArray) ) {
			header($this->mimeTypeArray[$type]);
			if ($fileSize!=null) header("Content-length: $fileSize");
		}
		// else if the file is a dng, then download
		//TODO add bmp and png as needed
		elseif (strtolower($type) == "dng") {
			//if ((file_exists($file))) {
			$fileName = $this->id.'.'.$this->imgExt;
			header("Content-Type: application/force-download");
			header("Content-Disposition: inline; filename=\"$fileName\"");
			header("Content-Transfer-Encoding: Binary");
			if ($fileSize!=null) header("Content-length: $fileSize");
			header('Content-Type: application/octet-stream');
			header("Content-Disposition: attachment; filename=\"$fileName\"");
			//}
		} else {
			header($this->mimeTypeArray['jpg']);
		}
	}

	/**
	 * function to call to show the image after object is created
	 * @return
	 */
	public function showImage() {
		$this->setMimeType($this->imgType, $this->fileSize);
		if (!$headRequest) {
			if ($this->resizedImgPath) {
				readfile($this->resizedImgPath);
			} else {
				readfile($this->imgPath);
			}
		}
		exit(0);
	}

	/**
	 * return a reference to the image, as a file if public
	 * @return unknown_type
	 */
	public function getImage() {
		global $defaultImage;
		//TODO allow secure access to images
		if ($this->id == null || !$this->check()){ // id is not an image
			$this->imgType = "png";
			return $defaultImage['notfoundPath'];
		} else if ($this->checkPublished()){
			return $this->getImgPath(); // This uses the unix path
		} else { // return a default image
			$this->imgType = "png";
			return $defaultImage['privatePath'];
		}
	}

	public function resizeImg($imgSize) {
		global $config;
		$this->imgSize = $imgSize;
		$imgSizeArray = explode("x", $imgSize);
		$this->resizedImgPath = $config->imgTmpDir.mktime().'.jpeg';
		if(!$this->headRequest){
			$cmd = 'convert '.$this->imgPath.' -resize '.$imgSizeArray[0].'x'.$imgSizeArray[1].' '.$this->resizedImgPath;
			$string = shell_exec($cmd);
			$this->fileSize = filesize($this->resizedImgPath);
		} else {
			//TODO estimate file size of image
			$this->fileSize = null;
		}
	}

	//checks to see if the object is an image
	public function check() {
		$sql = 'SELECT objectTypeId FROM BaseObject WHERE id='.$this->id;
		$result = mysqli_query($this->link, $sql);
		if ($result) {
			$object = mysqli_fetch_row($result);
			return ($object[0] == "Image") ? true : false;
		} else {
			return false;
		}
	}
}
?>
