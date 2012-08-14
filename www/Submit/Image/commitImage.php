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

include_once('imageFunctions.php');
include_once('updater.class.php');
include_once('objectFunctions.php');
include_once('processImageRemote.php');
include_once('updateObjectKeywords.php');
include_once('extLinksRefs.php');
include_once('urlFunctions.inc.php');

$userId = $objInfo->getUserId();
$groupId = $objInfo->getUserGroupId();
$indexUrl = 'index.php?'.getParamString($_REQUEST);
if(!checkAuthorization(null, $userId, $groupId, 'add')){
	header ("location: index.php");
	exit;
}

$specimenId      = trim($_REQUEST['SpecimenId']);
$viewId          = trim($_REQUEST['ViewId']);
$magnification   = trim($_REQUEST['Magnification']);
$publishDate     = trim($_REQUEST['DateToPublish']);
$contributor     = trim($_REQUEST['Contributor']);
$copyright       = trim($_REQUEST['Copyright']);
$photographer    = trim($_REQUEST['photographer']);
$eol             = isset($_REQUEST['eol']) ? 1 : NULL;

$db = connect();

// Check view id if entered
if (!empty($viewId)) {
  $count = $db->getOne("select count(*) as count from View where id = ?", null, array($viewId));
  isMdb2Error($count, "Verifying View Id exists.");
  if ($count == 0) {
    header("location: $indexUrl&code=2");
    exit;
  }
}

// Check specimen id if entered
if (!empty($specimenId)) {
  $count = $db->getOne("select count(*) as count from Specimen where id = ?", null, array($specimenId));
  isMdb2Error($count, "Verifying Specimen id exists");
  if ($count == 0) {
    header("location: $indexUrl&code=3");
    exit;
  }
}

if(!$publishDate) {
	$dateToPublish = date('Y-m-d', (mktime(0, 0, 0, date("m") +6, date("d") - 1, date("Y"))));
} else {
	$dateToPublish = date("Y-m-d", strtotime($publishDate));
}

if (!empty($_FILES['ImageFile']['tmp_name'])) {
  // Accepted image types
  $image_mimes = array(
    'image/bmp',
    'image/gif',
    'image/jpeg',
    'image/tiff',
    'image/png',
  );
  
  $finfo = new finfo(FILEINFO_MIME_TYPE);
  $mime_type = $finfo->file($_FILES['ImageFile']['tmp_name']);
  if (!in_array($mime_type, $image_mimes)) {
    header("location: $indexUrl&code=10");
    exit;
  }
   
  $image_error = FALSE;
  
	// Insert Image Object returning id
	$params = array($db->quote("Image"), $contributor, $groupId, $userId, $db->quote($dateToPublish,'date'), $db->quote("Image added"), $db->quote(NULL));
	$result = $db->executeStoredProc('CreateObject', $params);
	if(isMdb2Error($result, 'Create Object procedure')) {
		header("location: $indexUrl&code=4");
		exit;
	}
	$id = $result->fetchOne();
	if(isMdb2Error($id, 'Error retrieving new id of BaseObject')) {
		header("location: $indexUrl&code=5");
		exit;
	}
	
	clear_multi_query($result);
	
	if (empty($id)) {
		errorLog("New BaseObject id empty");
		header("location: $indexUrl&code=6");
		exit;
	}
	
	$newFileName = $_FILES['ImageFile']['name'];
	$tmpName = $_FILES['ImageFile']['tmp_name'];
	list($message, $width, $height, $type) = processImageRemote($id, $tmpName, $newFileName);
	if (!$width){
    $image_error = TRUE;
		errorLog("Image processing failed: ".$message, null, 6);
	}
	
	$sql = "select max(accessNum) as accessNum from Image";
	$accessNum = $db->queryOne($sql);
	if(isMdb2Error($accessNum, 'Error retrieving access number from Image')) {
		header("location: $indexUrl&code=8");
		exit;
	}
	$accessNum = $accessNum + 1;
	
	// prepare update
	$imageUpdater = new Updater($db, $id, $userId , $groupId, 'Image');
	$imageUpdater->addField('specimenId', $specimenId, null);
	$imageUpdater->addField('viewId', $viewId,null);
	$imageUpdater->addField('magnification', $magnification, null);
	$imageUpdater->addField('copyrightText', $copyright, null);
	$imageUpdater->addField('creativeCommons', $defaultCreativeCommons, null);
	$imageUpdater->addField('photographer', $photographer, null);
	$imageUpdater->addField('dateToPublish', $dateToPublish, null);
	$imageUpdater->addField('userId', $contributor, null);
	$imageUpdater->addField('groupId', $groupId, null);
	$imageUpdater->addField('originalfilename', $newFileName, null);
	$imageUpdater->addField('imageWidth', $width, null);
	$imageUpdater->addField('imageHeight', $height, null);
	$imageUpdater->addField('imageType', $type, null);
	$imageUpdater->addField('accessNum', $accessNum, null);
    $imageUpdater->addField('eol', $eol, null);
	$numRows = $imageUpdater->executeUpdate();
	if (is_string($numRows)) { // Error returned
		header("location: /Edit/Image/?code=13&id=$id");
		exit;
	}
	
	// Update thumbUrl for BaseObject
	$boUpdater = new Updater($db, $id, $userId , $groupId, 'BaseObject');
	$boUpdater->addField('thumbUrl', $id, null);
	$numRowsBo = $boUpdater->executeUpdate();
	if (is_string($numRowsBo)) { // Error returned
		header("location: /Edit/Image/?code=14&id=$id");
		exit;
	}
	
  if (!empty($specimenId)) addImageToObject('Specimen', $id, $specimenId);
	if (!empty($viewId)) addImageToObject('View', $id, $viewId);

	if ($numRows == 1) {
		updateKeywordsTable($id, 'insert');
	}
	
	$insertLinkRes = insertLinks($id, $_REQUEST);
	$insertRefRes  = insertReferences($id, $_REQUEST);
	if(!$insertLinkRes || !$insertRefRes) {
		header("location: /Edit/Image/?code=15&id=$id");
		exit;
	}

  // Error if image process failed
  if ($image_error) {
    header("location: /Edit/Image/?code=8&id=$id");
    exit;
  }

	header ("location: $indexUrl&code=1&id=$id");
	exit;
}
header ("location: $indexUrl&code=9");
exit;
