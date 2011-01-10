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

require_once ('../configuration/app.server.php');
include_once("imageFunctions.php");
include_once("imageProcessing.php");
include_once ('bischen/makeTpc.php');


// OPTIONAL fields
$SELECT_LIMIT = " order by i.id limit 2 ";
$SLEEP_AFTER_MKDIR = 1;

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
	echo("Error in SQL query".$result->getUserInfo()." $sql\n");
	die();
}
while($row = $result->fetchRow()){
	$imageCount++;
	// get fields i.id, i.accessNum, i.imageType, m.problems
	$id = $row[0];
	$accessNum = $row[1];
	$imageType = strtolower($row[2]);
	$message = moveImageFiles($id, $accessNum, $imageType);
	if(!empty($message)) echo "files for image $id moved $message\n";
	if ($imageCount % 100 == 0){
		echo "No. images: $imageCount\tlast id: $id \t last message $message\n";
	}
}
echo "\n\nTotal images checked: ".$imageCount."\n";

// refresh the status file with the last id
$file = fopen($STATUS_FILE,'w');
fwrite($file, $id);
fclose($file);

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
function moveImageFiles($id, $accessNum, $imageType){
	$message = '';
	if ($imageType=="jpg") $imageType = "jpeg"; // jpg original stored in jpeg
	if ($imageType == 'tiff') $imageType = 'tif';
	
	// get file paths
	$originalImgPath = getOldPath($id, $accessNum, $imageType);
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

	// move original
	$message .= moveFile($originalImgPath,$originalImgPathNew);
	// move derived files
	$message .= moveFile($jpegImgPath,$jpegImgPathNew, "jpeg");
	$message .= moveFile($jpgImgPath,$jpgImgPathNew, "jpg");
	$message .= moveFile($thumbImgPath,$thumbImgPathNew, "thumb");
	$message .= moveFile($tifImgPath,$tifImgPathNew, "tif");
	$message .= moveFile($id, $jpegImgPathNew, "tpc");
	//echo "Fixed $numFixed files for id: $id file types: $message \n";
	return $message;
}

function moveFile($oldPath, $newPath, $imageType){
	if (file_exists($newPath)){
		// nothing required
	} else if (!file_exists($oldPath)){
		// attempt to create missing file and put it in the new location
		$message = ":no $imageType source file";
	} else {// copy from old to new
		$moveResult = rename($oldPath, $newPath);
		$message = ":$imageType moved from $oldPath to $newPath";
	}
	return $message;
}

function getOldPath($id, $accessNum, $imageType){
	$imgPath = getOldAccessImagePath($id, $accessNum, $imageType);
	return $imgPath['imgSrc'];
}
function getNewPath($id, $accessNum, $imageType){
	$imgPath = getNewAccessImagePath($id, $imageType);
	return $imgPath['imgSrc'];
}

?>
