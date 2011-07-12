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
include_once('processImageRemote.php');
include_once('objectFunctions.php');
include_once('updateObjectKeywords.php');
include_once('extLinksRefs.php');

$id = trim($_POST['objId']);
$indexUrl = "index.php?id=$id";
$userId = $objInfo->getUserId();
$groupId =$objInfo->getUserGroupId();
if (!checkAuthorization($id, $userId, $groupId, 'edit')) {
	header ("location: index.php");
	exit;
}

$imageFile       = trim($_POST['ImageFile']);
$specimenId      = $_POST['SpecimenId'];
$viewId          = $_POST['ViewId'];
$magnification   = trim($_POST['Magnification']);
$publishDate     = trim($_POST['DateToPublish']);
$contributor     = $_POST['Contributor'];
$copyright       = trim($_POST['Copyright']);
$photographer    = trim($_POST['photographer']);
$eol             = isset($_POST['eol']) ? 1 : NULL;

$db = connect();

// Check view id if entered
if (!empty($viewId)) {
  $count = $db->getOne("select count(*) as count from View where id = ?", null, array($viewId));
  if (isMdb2Error($count, "View id does not exist", 6)) {
    header("location: $indexUrl&code=3");
    exit;
  }
}

// Check specimen id if entered
if (!empty($specimenId)) {
  $count = $db->getOne("select count(*) as count from Specimen where id = ?", null, array($specimenId));
  if (isMdb2Error($count, "Specimen id does not exist", 6)) {
    header("location: $indexUrl&code=5");
    exit;
  }
}

// Get BaseObject data
$baseObj = getObjectData('BaseObject', $id);
if (is_string($baseObj)) { // Error returned
	header("location: $indexUrl&code=6");
	exit;
}
$baseObjUpdater = new Updater($db, $id, $userId, $groupId, 'BaseObject');
$baseObjUpdater->addField('datetopublish', $publishDate, $baseObj['datetopublish']);
$baseObjUpdater->addField('userId', $contributor, $baseObj['userid']);

// Get image info and prepare update
$imgObj = getObjectData('Image', $id);
if (is_string($imgObj)) { // Error returned
	header("location: $indexUrl&code=7");
	exit;
}
$imgUpdater = new Updater($db, $id, $userId , $groupId, 'Image');
$imgUpdater->addField('specimenId', $specimenId, $imgObj['specimenid']);
$imgUpdater->addField('viewId', $viewId, $imgObj['viewid']);
$imgUpdater->addField('magnification', $magnification, $imgObj['maginification']);
$imgUpdater->addField('photographer', $photographer, $imgObj['photographer']);
$imgUpdater->addField('copyrightText', $copyright, $imgObj['copyrighttext']);
$imgUpdater->addField('dateToPublish', $publishDate, $imgObj['datetopublish']);
$imgUpdater->addField('userId', $contributor, $imgObj['userid']);
$imgUpdater->addField('eol', $eol, $imgObj['eol']);
	
if (!empty($_FILES['ImageFile']['tmp_name'])) {
  $image_error = FALSE;
	$newFileName = $_FILES['ImageFile']['name'];
	$tmpName = $_FILES['ImageFile']['tmp_name'];
	list($message, $width, $height, $type) = processImageRemote($id, $tmpName, $newFileName);
	if (!$width){
    $image_error = TRUE;
    errorLog("Image processing failed: ".$message, null, 6);
	}
	$imgUpdater->addField('originalfilename', $newFileName, $imgObj['originalfilename']);
	$imgUpdater->addField('imageWidth', $width, $imgObj['imagewidth']);
	$imgUpdater->addField('imageHeight', $height, $imgObj['imageheight']);
	$imgUpdater->addField('imageType', $type, $imgObj['imageType']);
}

// Update BaseObject
$numRowsBO = $baseObjUpdater->executeUpdate();
if (is_string($numRowsBO)) { // Error returned
	header("location: $indexUrl&code=9");
	exit;
}

// Update Image
$numRowsImg = $imgUpdater->executeUpdate();
if (is_string($numRowsImg)) { // Error returned
	header("location: $indexUrl&code=10");
	exit;
}

// Update image associations and counts
if (!empty($specimenId)) replaceImage("Specimen", $id, $specimenId, $imgObj['specimenid']);
if (!empty($viewId)) replaceImage("View", $id, $viewId, $imgObj['viewid']);


// Update keywords
if ($numRowsImg == 1 || $numRowsBO == 1) {
	updateKeywordsTable($id, 'update');
}

// Update and Add external links and unique references
$insertLinkRes = insertLinks($id, $_POST);
$updateLinkRes = updateLinks($id, $_POST);
$insertRefRes  = insertReferences($id, $_POST);
$updateRefRes  = updateReferences($id, $_POST);
if(!$insertLinkRes || !$insertRefRes) {
	header("location: $indexUrl&code=11");
	exit;
}
if(!$updateLinkRes || !$updateRefRes) {
	header("location: $indexUrl&code=12");
	exit;
}

if ($image_error) {
  header("location: /Edit/Image/?code=8&id=$id");
  exit;
}
header("location: $indexUrl&code=1");
exit;
