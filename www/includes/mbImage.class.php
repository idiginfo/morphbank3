<?php

/************************************************************************************************
 *  Author: Steven Winner                                                                    	*
 *  Date:   3/21/2008                                                                            *
 *																								*
 *  Description:  																				*
 *  Class that is constructed with a database link and an image id.  							*
 *  $imgType is optional.  Defaults to the thumbnail.  Can be "jpg", "jpeg", or "tiff" 			*
 *  Class will set up the object with all relevant Url's, paths, and img tags for that image. 	*
 *  Class for display inside mb website//look at readImage.class.php for external url																								*
 *																								*
 *  Usage: 																						*
 *  $imageObject = new mbImage($link, $id [, $imgType ]); // $imgType is optional.  				*
 *  																								*
 *																								*
 *  echo '<img src="'.$imageObject->getImgUrl().'" />'; 											*
 *  			or 																					*
 *  echo $imageObject->getImgTag();  // will just output an html img tag 						*
 *																			                  	*
 *                                                                                             	*
 *  Modified: 3/24/2008  													                  	*
 ************************************************************************************************/
include_once("imageFunctions.php");
include_once("urlFunctions.inc.php");

class mbImage {

	protected $id;					// image id
	protected $imgType;				// type of image (i.e. jpg, jpeg, tiff
	protected $imgUrl;				// url for the image
	//TODO fix imgUrlOriginal currently incorrect
	protected $imgUrlOriginal;		// url to the originally uploaded image (could be many different types, not just tiff)

	protected $jpeg;
	protected $jpg;
	protected $tiff;

	protected $preferredServer;		// preferred server set in the session
	protected $serverLogo;			// preferred server logo (if exists)
	protected $originalImgType; // type of image the originaly uploaded image was
	protected $defaultImage;		// image to show if the image requested doesn't exist or is not allowed to be viewed
	protected $objInfoRef;			// reference to the sessionHandler object $objInfo
	public $test;

	// constructor will set up the thumbnail by default
	public function __construct($link, $id = NULL, $imgType = "thumbs") {
		global $objInfo;
		$link = adminLogin();
		$this->id = $id;
		$this->objInfoRef =& $objInfo; // reference to session object.  No need to copy, reference should be fine.
		if ($id != NULL) $this->setUpImage($id, $imgType);
	}

	// sets up the object with a new image id, and calls setUpImage to set up all members of the class
	public function setNewImage($id, $imgType = "thumbs") {
		// set up the object with new info
		if (!empty($id)) {
			$this->setUpImage($id, $imgType);
		} else {
			$this->setUpDefault();
		}
	}

	// method that will assign all the values for the image url etc to the member variables
	public function setUpImage ($id,$imgType) {
		$this->id = $id;
		$imgUrl = getImageUrl($id, $imgType);
		$this->imgType = $imageType;
		$this->urlExists = true;
		$this->originalImgType = $this->getOriginalImgTypeFromDB();
		$this->imgUrlOriginal = getImageUrl($id, $this->originalImgType);
	}

	// sets up the default image to show if image is not there or is not allowed to be viewed
	public function setUpDefault() {
		global $defaultImage;
		$this->imgUrl =  $defaultImage['notfound'];
	}

	// Getter methods

	public function getImgUrl() {
		return $this->imgUrl;
	}

	public function getImgType() {
		return $this->imgType;
	}

	public function getImgUrlOriginal() {
		return $this->imgUrlOriginal;
	}

	public function getServerLogo() {
		return $this->serverLogo;
	}

	public function getOriginalImgType() {
		return $this->originalImgType;
	}

	public function getJpeg() {
		return getImageUrl($this->id,'jpeg');
	}

	public function getJpg() {
		return getImageUrl($this->id, 'jpg');
	}

	public function getTiff() {
		return getImageUrl($this->id, 'tiff');
	}

	public function getOriginalImgTypeFromDB() {
		$link = adminLogin();
		$sql = 'SELECT imageType FROM Image where id='.$this->id;

		$result = mysqli_query($link, $sql);
		if ($result) {
			$record = mysqli_fetch_array($result);
			return $record['imageType'];
		} else {
			return FALSE;
		}
	}

	// Uses a pear class to check if the response code to a http object is 200 (true)
	// Checks to see if an image is published
	public function isPublished( $imageId = NULL) {
		$link = adminLogin();
		if (!$imageId || $imageId == "") return FALSE;

		$sql = 'SELECT dateToPublish<=CURDATE() as published FROM Image WHERE id='.$imageId.';';
		$result = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
		$row = mysqli_fetch_array($result);
		if ( isset($row['published']) )
		return $row['published']=="0"?FALSE:TRUE;

		return FALSE;
	}

	// Method that will check a logged in user's permissions allow them to view the image.
	// Returns TRUE automatically if loged into MBAdmin
	//TODO test the use of checkAuthorization
	public function checkPublished() {
		if (empty($this->id)) return FALSE;
		if ($this->objInfoRef) {
			$userId = $this->objInfoRef->getUserId();
			$groupId = $this->objInfoRef-> getUserGroupId();
		}
		return checkAuthorization($this->id, $userId, $groupId, 'view');
	}

	// Method that will check if an image is physically there and has read permissions
	public function checkPhysical() {
		return $this->urlExists;
	}

}

?>