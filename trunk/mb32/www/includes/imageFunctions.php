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

/**
 * Returns a well-formed URL suitable for presentation to an ImageServer
 * @param $baseUrl: the URL of the ImageServer
 * @param $id
 * @param $imgType
 * @param $sessionId
 * @return string
 */
function getFullImageUrl($baseUrl, $id, $imgType, $sessionId = null){
	$url = "$baseUrl?id=$id&imgType=$imgType";
	//TODO eliminate sessionId from public url
	// if (!isPublic($id) && !empty($sessionId)) {
	if (isLoggedIn() && !empty($sessionId)) {
		$url .= "&sessionId=".$sessionId;
	}
	return $url;
}

function getOriginalUrl($id, $originalImgType){
	return getImageUrl($id,$originalImgType);
}

/**
 * Returns the URL for an image, based on global parameters for base URL and session id
 * @param int $id
 * @param string $imgType
 * @return string
 */
function getImageUrl($id, $imgType){
	global $config;
	return getFullImageUrl($config->imgServerUrl, $id, $imgType, session_id());
}

function getImageSizeUrl($id, $imgType){
	global $config;
	return $config->imgServerUrl."imageSizes.php?id=$id&imgType=$imgType";
}

/**
 * Get the URL of the appropriate image of the proper width in pixels
 * @param int $id
 * @param int $widthInPixels
 */
function getSizedImageType($widthInPixels){
	if ($widthInPixels <=90) $imgType = 'thumb';
	else if ($widthInPixels <=400) $imgType = 'jpg';
	else $imgType = 'jpeg';
	return $imgType;
}

/**
 * Get the id of the representative image for the object
 * @param $objId
 * @return unknown_type
 */
function getObjectImageId($objId){
	if (intval($objId)<1) return null; // no integer object id
	$sql = "select thumbUrl, objecttypeid from BaseObject where id=$objId";
	$db = connect();
	list($thumbUrl, $objectType) = $db->getRow($sql);
	if (PEAR::isError($row)){
		die($row->getUserInfo());
	}
	if ($objectType=='Image') return $objId;
	return $thumbUrl;
}

/**
 * Get the image path for object $id using the thumburl field
 * @param $id
 * @return unknown_type
 */
function getObjectImageUrl($objId, $imgType = 'thumbs'){
	$thumbUrl = getObjectImageId($objId);
	if (empty($thumbUrl)){
		return getDefaultImageUrl('notfound');
	}
	if (intval($thumbUrl) > 0) {
		return getImageUrl($thumbUrl, $imgType);
	}
	if (intval($imageId)==0){ // not an integer
		return $thumbUrl;
	}
}

function getDefaultImageUrl($style){
	global $defaultImage;
	return $defaultImage[$style];
}

/**
 * Used by index.php to support URL by access number
 * @param $accessNum
 * @return unknown_type
 */
function accessNumToId ($accessNum) {
	global $link;

	$sql = 'SELECT id FROM Image where accessNum='.$accessNum;
	$result = mysqli_query($link, $sql);

	if ($result) {
		$idArray = mysqli_fetch_array($result);
		return $idArray['id'];
	}
	return FALSE;
}

function isPublished( $imageId = NULL) {
	if (!$imageId || $imageId == "") return FALSE;
	
	$db = connect();
	$sql = 'SELECT dateToPublish<=now() as published FROM Image WHERE id='.$imageId.';';
	$published = $db->queryOne($sql);
	if (PEAR::isError($published)){
		die($published->getUserInfo());
	}
	if (isset($published)) return $published=="0"?FALSE:TRUE;
	return FALSE;
}


function getDefaultObjectTypeUrl($objectTypeId){
	$objectType = strtolower($objectTypeId);
	switch ($objectType) {
		case "view":
			return getDefaultImageUrl('view');
		case "specimen":
			return getDefaultImageUrl('specimen');
		case "locality":
			return getDefaultImageUrl('locality');
		case "annotation":
			return getDefaultImageUrl('annotation');
		case "collection":
		case "mbcharacter":
			return getDefaultImageUrl('collection');
		case "groups":
			return getDefaultImageUrl('groups');
		case "news":
			return getDefaultImageUrl('news');
		case "publication":
			return getDefaultImageUrl('publication');
		case "taxonconcept":
			return getDefaultImageUrl('taxonConcept');
		case "taxon name":
			return getDefaultImageUrl('taxonName');
		case "user":
			return getDefaultImageUrl('user');
		case "characterstate":
			return getDefaultImageUrl('characterState');
		case "phylocharstate":
			return getDefaultImageUrl('characterState');
		default:
			return getDefaultImageUrl('defaultThumbNail');
	}
}

function getImageAccessNum ( $imageId) {
	if (!$imageId) return "";
	$link = Adminlogin();
	$sql = 'SELECT accessNum FROM Image WHERE id='.$imageId.';';
	$result = mysqli_query($link, $sql) or die("Could not run the query\n");
	$record = mysqli_fetch_array($result);

	return $record['accessNum'];
}

function imageDetailsArrayFromId($id) {
	global $link;

	// Simply takes an image ID, and searchs the database for the various information related to that Image.
	$sql = 'SELECT imageHeight, imageWidth, imageType, accessNum, '
	.'View.imagingTechnique as imagingTechniqueName, '
	.'View.imagingPreparationTechnique as imagingPreparationTechniqueName, '
	.'View.specimenPart as specimenPartName, '
	.'Specimen.tsnId as tsn, '
	.'View.sex as sexName, '
	.'View.viewName as viewName, '
	.'Specimen.developmentalStage as specimenDevelStageName, '
	.'Specimen.form as specimenFormName, '
	.'View.viewAngle as viewAngleName '
	.'FROM Image '
	.'LEFT JOIN Specimen ON Image.specimenId = Specimen.id '
	.'LEFT JOIN View ON Image.viewId = View.id '
	.'WHERE Image.id = '.$id;

	$result = mysqli_query($link, $sql);

	if ($result) {
		return mysqli_fetch_array($result);
	} else {
		return;
	}
}
/**
 *  return alt="txt" with appropriate google image info for image
 *  the alt text is created and stored as part of the keywords generation in base_keywords.php
 *  maybe this function belongs in some other php file
 *  GR 3/24/09
 *
 * @param int $id
 * @param string $defaultText
 * @return string
 */
function getImageAlt($id, $defaultText = 'image'){
	$db = connect();
	$sql = 'select imageAltText from BaseObject where id='.$id;
	$altText = $db->queryOne($sql);
	if (PEAR::isError($altText)){
		die($thumbUrl->getUserInfo());
	}
	if ($altText != null && strlen($altText)!=0){
		return ' alt="'.$altText.'" ';
	} else {
		return ' alt="'.$defaultText.'" ';
	}
}

/**
 * Call php getimagesize if $imgPath is a local file
 * otherwise return null (for unknown)
 * @param $imgPath
 * @return unknown_type
 */
function getSafeImageSize($filePath){
	if (file_exists($filePath)){
		return getimagesize($filePath);
	}

	return array(0,0,0);
}

function getDBImageSize($imageId){
	$db = connect();
	$row = $db->queryRow("select imageWidth,imageHeight from Image where id=$imageId");
	//if (empty($row)) return null;
	return $row;
}

function getRemoteImageSize($id, $imageType){
	$imageUrl = getImageSizeUrl($id, $imageType);
	$handle = fopen($imageUrl,'r');
	if (!handle) return false;
	$contents = '';
	while (!feof($handle)) {
		$contents .= fread($handle, 100);
	}
	fclose($handle);
	$sizes = explode('+',$contents);
	return $sizes;
}

function checkTpc($id){
	return true;
}

/**
 * Sets/unsets whether image is available for EOL
 * @param string $where sql clause
 * @param boolean $set true to set, false to unset
 * @return unknown_type
 */
function setEOL($where, $set = true){
	$db = connect();
	$setValue = !$set ? "eol=0" : "eol=1";
	$query = "update Image set " . $setValue . " where id in (select id from BaseObject where " . $where . " and objectTypeId = 'image')";
	$affectedRows = $db->exec($query);
	if(PEAR::isError($afftectedRows)){
		return "Error updating eol in Image.\n" . $affectedRows->getUserInfo() . " " . $query . "\n";
	}
	return $affectedRows;
}

/**
 * Update the database fields from file info or parameters
 * @param $id
 * @param $width
 * @param $height
 * @return message as string
 */
function setWidthHeight($id, $width, $height){
	$db = connect();
	if (empty($width) || empty($height)){
		return ": setWidthHeigh empty values ($width, $height)";
	}
	$query ="UPDATE Image SET imageWidth=$width, imageHeight=$height ";
	$query .= "WHERE id=$id";
	$db = connect();
	$numUpdated = $db->query($query);
	if(PEAR::isError($numUpdated)){
		echo ("Error in width/height update query: [$query] info:" . $numUpdated->getUserInfo());
	}
	if ($numUpdated != 1){
		$message = "";
	} else {
		$message = ":new width $width height $height ";
	}
	return $message;
}

// Routines to update standard image ids and thumburls
function replaceImage($objType, $imageId, $newObjId, $oldObjId, $imageCount = 1){
	if ($newObjId == $oldObjId) return true;
	$result = removeImageFromObject($objType, $imageId, $oldObjId, $imageCount);
	$result = addImageToObject($objType, $imageId, $newObjId, $imageCount);
	if ($objType=='Specimen'){
		// Update Locality and Taxon count
		list($newTsnId, $newLocId) = array_values(getObjectData('Specimen', $newObjId, 'tsnId, localityId'));
		list($oldTsnId, $oldLocId) = array_values(getObjectData('Specimen', $oldObjId, 'tsnId, localityId'));
		removeImageFromObject('Locality', $imageId, $newLocId, $oldLocId, $imageCount);
		addImageToObject('Locality', $imageId, $newLocId, $imageCount);
		updateImageCountForTaxon($newTsnId, $oldTsnId, $imageCount);
	}
}

/**
 * Remove image from object
 * update image count, standard image, and thumburl
 * @param $objType
 * @param $imageId
 * @param $objId
 */
function removeImageFromObject($objType, $imageId, $objId, $imageCount = 1){
	$db = connect();
	if (empty($objId)||empty($imageId) || empty($imageCount)) return true;
	// get type-specific sql statements
	if ($objType=='View' || $objType=='Specimen' || $objType='Locality'){
		$countUpdateSql = "update $objType set imagesCount=imagesCount-$imageCount where id=$objId";
	} else {
		return false;
	}
	// update count
	$updateCount = $db->exec($countUpdateSql);
	isMdb2Error($updateCount, "update image count for $objType", false);
	// update standardImageId
	$result = replaceStandardImage($objType, $imageId, $objId);
	return true;
}

/**
 * Add image to object
 * update image count, standard image, and thumburl
 * @param $objType
 * @param $imageId
 * @param $objId
 */
function addImageToObject($objType, $imageId, $objId, $imageCount = 1){
	$db = connect();
	
	if (empty($imageId) || empty($imageCount)) return true;
	// increment image count
	if ($objType=='View' || $objType=='Specimen' || $objType=='Locality'){
		$countUpdateSql = "update $objType set imagesCount=imagesCount+$imageCount where id=$objId";
	} else {
		return false;
	}
	$updateCount = $db->exec($countUpdateSql);
	isMdb2Error($updateCount);
	// update standardImageId
	$result = replaceStandardImage($objType, $imageId, $objId);

}

/**
 * If the thumbUrl of objId is null or equal to imageId,
 * find a new thumbUrl
 * Update standardImageId if objType has such a field
 * @param unknown_type $objType
 * @param unknown_type $imageId
 * @param unknown_type $objId
 */
function replaceStandardImage($objType,$imageId,$objId){
	$db = connect();
	$stdId = getObjectImageId($objId);
	if (!empty($stdId) && $stdId != $imageId) return true; //
	// update thumb
	$newImageId = findNewStandardImage($objType,$objId);
	if (empty($newImageId)) {
		$newImageId = 'null';
		//TODO remove standardimageid
	}
	$updateThumbSql = "update BaseObject set thumbUrl=$newImageId where id=$objId";
	$thumbCount = $db->exec($updateThumbSql);
	isMdb2Error($thumbCount);
	if($objType=='View'||$objType=='Specimen'){
		$updateStndImageSql = "update $objType set standardimageid=$newImageId where id=$objId";
		$stdIdCount = $db->exec($updateStndImageSql);
		isMdb2Error($stdIdCount);
	}
	return true;
}

function findNewStandardImage($objType, $objId){
	$db = connect();

	if ($objType=='Image') return $objId;

	if ($objType=='View') $field='viewId';
	else if ($objType='Specimen') $field='specimenId';
	else {
		//echo "unimplemented type";
		return null;
	}

	$sql = "SELECT min(id) FROM Image WHERE $field=$objId ";
	$stdId = $db->queryOne($sql);
	isMdb2Error($stdId);
	return $stdId;
}

function moveImagesforSpecimen($newSpecimenId, $oldSpecimenId, $imageCount=1){

}

/**
 * Update image count on Tree
 * @param $newTsn
 * @param $oldTsn
 * @param $imageCount
 * @return bool
 */
function updateImageCountForTaxon($newTsn, $oldTsn, $imageCount = 1){
	updateImagesCountOnTree($newTsn, $imageCount);
	updateImagesCountOnTree($oldTsn, -$imageCount);
	return;
}

