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

require_once('../../configuration/image.server.php');
include_once("imageFunctions.php");
include_once("imageProcessing.php");
include_once ('bischen/makeTpc.php');

// OPTIONAL fields
$SELECT_LIMIT = " order by i.id limit 2000 ";
$SLEEP_AFTER_MKDIR = 1;
while (true){
	echo date("H:i:s\n");
	// get the id of the last image processed
	$STATUS_FILE = "/data/scratch/status.txt";
	$file = fopen($STATUS_FILE,'r');
	$lastId = fread($file, 20);
	fclose($file);
	if (empty($lastId)) $lastId = 0;

	$imageSql = "select  i.id, i.accessNum, i.imageType "
	." from Image i where i.accessNum > 0  and i.id > $lastId $SELECT_LIMIT ";

	echo "SQL: $imageSql\n";
	$db = connect();
	$result = $db->query($imageSql);
	if(PEAR::isError($result)){
		echo("Error in Missing SQL query".$result->getUserInfo()." $sql\n");
		die();
	}

	$rows= false;
	while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC)){
		$rows = true;
		$imageCount++;
		// get fields i.id, i.accessNum, i.imageType, m.problems
		$id = $row[0];
		$accessNum = $row[1];
		$imageType = strtolower($row[2]);
		$message = moveImageFiles($id, $accessNum, $imageType, $problems);
		if(!empty($message)) echo "files for image $id moved $message\n";
		if ($imageCount % 100 == 0){
			echo "No. images: $imageCount\tlast id: $id \t last message $message\n";
		}
	}
	if (!$rows) break;

	echo "\n\nTotal images checked: ".$imageCount."\n";

	// refresh the status file with the last id
	$file = fopen($STATUS_FILE,'w');
	fwrite($file, $id);
	fclose($file);
	echo date("H:i:s\n");
}

// code borrowed from includes/imageProcessing.php

/**
 * Fix problems with image files including fetching original and moving to correct location
 * Put a record in table "FileMovement" to record what was done
 *
 * @param $id
 * @param $uin
 * @param $accessNum
 * @param $fileName file name of original file
 * @param $imageType
 * @param $problems
 * @return int: number of images created/moved
 */
function moveImageFiles($id, $accessNum, $imageType, $problems){
	global $config;
	$problemArray = split(" ",$string);
	//echo "\nFixing files  for id: $id accessNum: $accessNum \n";
	$message = '';
	if ($imageType=="jpg") $imageType = "jpeg"; // jpg original stored in jpeg
	$originalImgPath = getOldPath($id, $accessNum, $imageType);
	$originalOK = file_exists($originalImgPath);
	if (!$originalOK){
		// look for alternative
		//echo "Original file $originalImgPath missing for id: $id accessNum: $accessNum \n";
		//get original file and put it in place
		// Note that if original is jpg or jpeg, this will move the file to jpeg path
		$imageType = "jpeg";
		$missingPath = $originalImagePath;
		$originalImgPath = getOldPath($id, $accessNum, $imageType);
		$originalOK = file_exists($originalImgPath);
		if ($originalOK){
			$message .= ":missing $imageType original $missingPath using jpeg";
		}
	}
	if (!$originalOK) {
		$message .= ":no source file found";
	} else {
		// get file paths
		$jpgImgPath = getOldPath($id, $accessNum, "jpg");
		$jpegImgPath = getOldPath($id, $accessNum, "jpeg");
		$thumbImgPath = getOldPath($id, $accessNum, "thumb");
		$tifImgPath = getOldPath($id, $accessNum, "tif");
		$tpcImgPath = getOldPath($id, $accessNum, "tpc");

		$originalImgPathNew = getNewPath($id, $accessNum, $imageType);
		$jpgImgPathNew = getNewPath($id, $accessNum, "jpg");
		$jpegImgPathNew = getNewPath($id, $accessNum, "jpeg");
		$thumbImgPathNew = getNewPath($id, $accessNum, "thumb");
		$tifImgPathNew = getNewPath($id, $accessNum, "tif");
		$tpcImgPathNew = getNewPath($id, $accessNum, "tpc");

		// copy original
		$message .= copyFile($originalImgPath,$originalImgPathNew, $imageType, null);
		// copy derived files
		$message .= copyFile($jpegImgPath,$jpegImgPathNew, "jpeg", $originalImgPath);
		$message .= copyFile($jpgImgPath,$jpgImgPathNew, "jpg", $jpegImgPathNew, $config->jpgSize);
		$message .= copyFile($thumbImgPath,$thumbImgPathNew, "thumb", $jpegImgPathNew, $config->thumbSize);
		$message .= copyFile($tifImgPath,$tifImgPathNew, "tif", $jpegImgPathNew);

		$message .= moveTpc($id, $jpegImgPathNew, $tpcImgPathNew);
		//		list($w, $h) = getWidthHeight($jpegImgPathNew);
		//		$message .= setWidthHeight($id, $w, $h);

	}
	//echo "id $id fixed $message\n";

	// update database
	//updateMovedImages($id, $message);
	//echo "Fixed $numFixed files for id: $id file types: $message \n";
	return $message;
}

function fileModTime($path){
	if ($WINDOWS) {
		$mtime = filemtime($path);
	} else {
		$mtime = exec ('stat -c %Y '. escapeshellarg ($path));
	}
	return $mtime;
}

function copyFile($oldPath, $newPath, $imageType, $originalPath = null, $size = null){
	global $config;
	$copy = true;
	//TODO check time stamps!
	if (file_exists($newPath)){
		if (file_exists($oldPath) && fileModTime($oldPath)>fileModTime($newPath)) {
			$copy = true;
		} else {
			// nothing required
			$copy = false;
		}
	}

	if (!file_exists($oldPath)){
		// attempt to create missing file and put it in the new location
		if ($imageType != 'tif' || $config->processTiff){
			$message = convertOriginal($originalPath, $newPath, $imageType, $size);
			$copy = true;
		} else {
			$message = ":no $imageType source file";
			$copy = false;
		}
	}
	if (!empty($originalPath) && fileModTime($originalPath)>fileModTime($newPath)) {
		$copy = true;
	}
	if ($copy) {// copy from old to new
		$copyResult = copy($oldPath, $newPath);
		$message = ":$imageType copied";
	}
	return $message;
}

/**
 * Create a new tpc file if the target file is missing
 * -- No tpc files are in the old file system
 * @param $id
 * @param $srcPath
 * @param $tpcPath
 * @return unknown_type
 */
function moveTpc ($id, $srcPath, $tpcPath) {
	if (!file_exists($tpcPath)){
		//echo "No file for path '$tpcPath'\n";
		$message = convertTpc($id, $srcPath, $tpcPath);
	} else {
		//$message .= ":tpc already present";
	}
	return $message;
}

//function convertTpc($id, $tpcImgPath = null,  $imgSrc = null){
//	$success = makeTilePic($id, $tpcImgPath, $imgSrc);
//	if ($success) return ":tpc created layers ".$success['layers'];
//	return ":no tpc created";
//}
//
function updateMovedImages($id, $message){
	$db = connect();
	$dbMessage = $db->quote($message);
	$updateSql = "update MovedImages set message = concat(message,$dbMessage), dateModified=now() where id=$id";
	$insertSql = "insert into MovedImages(id,message,dateModified) values($id, $dbMessage, now())";
	$db = connect();
	$result = $db->query($insertSql);
	if(!PEAR::isError($result)) {
		//echo "insert $id ok\n";
		return;
	}
	$result = $db->query($updateSql);
	if(!PEAR::isError($result)) {
		//echo "update $id ok\n";
		return;
	}
	echo " update failed ".$result->getUserInfo()."\n";
}


function getOldPath($id, $accessNum, $imageType){
	$imgPath = getOldAccessImagePath($id, $accessNum, $imageType);
	return $imgPath['imgSrc'];
}
function getNewPath($id, $accessNum, $imageType){
	$imgPath = getNewAccessImagePath($id, $accessNum, $imageType);
	return $imgPath['imgSrc'];
}

?>
