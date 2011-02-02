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

$db = connect();

// Check for valid view id
$sql = "select id from View where id = ?";
$vid = $db->getOne($sql, array('integer'), array($viewId));
if (isMdb2Error($vid, "Error verifying view id", 5)) {
	header("location: $indexUrl&code=2");
	exit;
}
if (empty($vid)) {
	errorLog("View Id is empty");
	header("location: $indexUrl&code=2");
	exit;
}

// Check for valid specimen id and get locality id for image update
$sql = "select count(*) as count from Specimen where id = ?";
$count = $db->getOne($sql, array('integer'), array($specimenId));
if ($count == 0 || isMdb2Error($count, "Specimen does not exists.", 5)) {
	header("location: $indexUrl&code=3");
	exit;
}

if(!$publishDate) {
	$dateToPublish = date('Y-m-d', (mktime(0, 0, 0, date("m") +6, date("d") - 1, date("Y"))));
} else {
	$dateToPublish = date("Y-m-d", strtotime($publishDate));
}


if (!empty($_FILES['ImageFile']['tmp_name'])) {
	// Insert Object and Locality returning id
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
		errorLog("Image processing failed: ".$message);
		// Remove Image record
		$sql = "delete from Image where id = $id limit 1";
		$affRows = $db->exec($sql);
		isMdb2Error($affRows, 'Deleting Image record after Image process failure');
		// Remove BaseObject record
		$sql = "delete from BaseObject where id = $id limit 1";
		$affRows = $db->exec($sql);
		isMdb2Error($affRows, 'Deleting BaseObject record after Image process failure');
		header("location: $indexUrl&code=7&id=$id");
		exit;
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
	
	if (!empty($localityId)) {
		addImageToObject('Locality', $id, $localityId);
	}
	addImageToObject('Specimen', $id, $specimenId);
	addImageToObject('View', $id, $viewId);

	if ($numRows == 1) {
		updateKeywordsTable($id, 'insert');
	}
	
	$insertLinkRes = insertLinks($id, $_REQUEST);
	$insertRefRes  = insertReferences($id, $_REQUEST);
	if(!$insertLinkRes || !$insertRefRes) {
		header("location: /Edit/Image/?code=15");
		exit;
	}
	
	header ("location: $indexUrl&code=1&id=$id");
	exit;
}
header ("location: $indexUrl&code=9");
exit;
