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

<?php 
/*
 * Script to move original images to backup
 */
require_once ('../configuration/image.server.php');

$STATUS_FILE = "/data/scratch/movestatus.txt";
$BACKUP_IMAGE_PATH_ROOT = "/data/images/originals/";

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
	while($row = $result->fetchRow()){
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

	$problemArray = split(" ",$string);
	//echo "\nFixing files  for id: $id accessNum: $accessNum type: $imageType\n";
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
		// Get new file path
		$newImgPath = getNewPath($id, $accessNum, $imageType);
		// Move file
		$moveOk = rename($originalImgPath, $newImgPath);
		if (!$moveOk) { 
			$message .= ":unable to move $originalImgPath to $newImdPath";
		} else {
			$message .= ":image $originalImgPath moved to $newImdPath";
		}
	}
	return $message;
}


function getOldPath($id, $accessNum, $imageType){
	$imgPath = getOldAccessImagePath($id, $accessNum, $imageType);
	//echo "path for $id access $accessNum of type $imageType is ". $imgPath['imgSrc']."\n";
	return $imgPath['imgSrc'];
}
function getNewPath($id, $accessNum, $imageType){
	$imgPath = getImageFilePath($id, $imageType);
	return $imgPath;
}
?>
