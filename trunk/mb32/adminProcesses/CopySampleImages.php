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
$STATUS_FILE = "/data/scratch/status.txt";
$SampleDB = "MB30Sample";

while(true){
	echo "Time: ".date("H:i:s")."\n";

	// get the id of the last image processed

	$file = fopen($STATUS_FILE,'r');
	$lastId = fread($file, 20);
	fclose($file);
	if (empty($lastId)) $lastId = 0;

	$imageSql = "select  i.id, i.imageType "
	." from $SampleDB.Image i where i.id > $lastId $SELECT_LIMIT ";

	echo "SQL: $imageSql\n";
	$db = connect();
	$result = $db->query($imageSql);
	if(PEAR::isError($result)){
		echo("Error in Missing SQL query".$result->getUserInfo()." $imageSql\n");
		die();
	}
	$rows = false;

	while($row = $result->fetchRow()){
		$rows = true;
		$imageCount++;
		// get fields i.id, i.accessNum, i.imageType, m.problems
		$id = $row[0];
		$accessNum = $row[1];
		$imageType = strtolower($row[2]);
		$problems = $row[3];
		$message = moveImageFiles($id, $imageType);
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

	if (!$rows) break;
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
function moveImageFiles($id, $imageType){
	//echo "\nFixing files  for id: $id accessNum: $accessNum \n";
	$message = '';
	if ($imageType=="jpg") $imageType = "jpeg"; // jpg original stored in jpeg
	if ($imageType == 'tiff') $imageType = 'tif';
	// get file paths
	$originalImgPath = getNewPath($id, $imageType);
	$jpgImgPath = getNewPath($id, "jpg");
	$jpegImgPath = getNewPath($id, "jpeg");
	$thumbImgPath = getNewPath($id, "thumb");
	$tifImgPath = getNewPath($id, "tif");
	$tpcImgPath = getNewPath($id, "tpc");

	$originalImgPathNew = getSamplePath($id, $imageType);
	$jpgImgPathNew = getSamplePath($id, "jpg");
	$jpegImgPathNew = getSamplePath($id, "jpeg");
	$thumbImgPathNew = getSamplePath($id, "thumb");
	$tifImgPathNew = getSamplePath($id, "tif");
	$tpcImgPathNew = getSamplePath($id, "tpc");

	// copy original
	$message .= copyFile($originalImgPath,$originalImgPathNew, "original:".$imageType);
	// copy derived files
	$message .= copyFile($jpegImgPath,$jpegImgPathNew, "jpeg");
	$message .= copyFile($jpgImgPath,$jpgImgPathNew, "jpg");
	$message .= copyFile($thumbImgPath,$thumbImgPathNew, "thumb");
	$message .= copyFile($tifImgPath,$tifImgPathNew, "tif");
	$message .= copyFile($tpcImgPath, $tpcImgPathNew, "tpc");

	//echo "id $id fixed $message\n";
	//echo "Fixed $numFixed files for id: $id file types: $message \n";
	return $message;
}

function copyFile($oldPath, $newPath, $imageType){
	if (file_exists($newPath)){
		// nothing required
	} else {// copy from old to new
		$copyResult = copy($oldPath, $newPath);
		$message = ":$imageType copied from $oldPath to $newPath \n";
	}
	return $message;
}

function getSamplePath($id, $imageType){
	global $NEW_IMAGE_ROOT_PATH;
	$NEW_IMAGE_ROOT_PATH = "/data/imagestore/sample/";
	$imgPath = getNewAccessImagePath($id, $imageType);
	return $imgPath['imgSrc'];
}
function getNewPath($id, $imageType){
	global $NEW_IMAGE_ROOT_PATH;
	$NEW_IMAGE_ROOT_PATH = "/data/imagestore/";
	$imgPath = getNewAccessImagePath($id, $imageType);
	return $imgPath['imgSrc'];
}

?>
