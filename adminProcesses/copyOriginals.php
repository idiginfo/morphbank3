#-------------------------------------------------------------------------------
# Copyright (c) 2010 Greg Riccardi, Fredrik Ronquist.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Public License v2.0
# which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# 
# Contributors:
#   Fredrik Ronquist - conceptual modeling and interaction design
#   Austin Mast - conceptual modeling and interaction design
#   Greg Riccardi - initial API and implementation
#   Wilfredo Blanco - initial API and implementation
#   Robert Bruhn - initial API and implementation
#   Christopher Cprek - initial API and implementation
#   David Gaitros - initial API and implementation
#   Neelima Jammigumpula - initial API and implementation
#   Karolina Maneva-Jakimoska - initial API and implementation
#   Katja Seltmann - initial API and implementation
#   Stephen Winner - initial API and implementation
#-------------------------------------------------------------------------------
<?php
/**
 * This script moves files from old to new file strategy
 */
define('PHP_ENTRY',0);

require_once('../configuration/image.server.php');
include_once("imageFunctions.php");

$STATUS_FILE = "/data/scratch/copystatus.txt";
$BACKUP_IMAGE_PATH_ROOT = "/data/images/originals/";
 

// OPTIONAL fields

$SELECT_LIMIT = " order by i.id limit 20 ";
$SLEEP_AFTER_MKDIR = 1;
$rows = true;
while ($rows){
	echo date("H:i:s\n");
	// get the id of the last image processed
	$file = fopen($STATUS_FILE,'r');
	$lastId = fread($file, 20);
	fclose($file);
	if (empty($lastId)) $lastId = 0;

	$imageSql = "select  i.id,  i.imageType "
	." from Image i where i.accessNum > 0  and i.id > $lastId $SELECT_LIMIT ";

	echo "SQL: $imageSql\n";
	$db = connect();
	$result = $db->query($imageSql);
	if(PEAR::isError($result)){
		echo("Error in Missing SQL query".$result->getUserInfo()." $sql\n");
		die();
	}

	$rows = false;
	while(list($id,$imageType) = $result->fetchRow()){
		$rows = true;
		$lastId = $id;
		$imageCount++;
		// get fields i.id, i.accessNum, i.imageType, m.problems
		$message = copyOriginalFile($id, $imageType);
		if(!empty($message)) echo "$message\n";
		if ($imageCount % 100 == 0){
			echo "No. images: $imageCount\tlast id: $id \t last message $message\n";
		}
	}
	// refresh the status file with the last id
	$file = fopen($STATUS_FILE,'w');
	fwrite($file, $lastId);
	fclose($file);

}

echo "\n\nTotal images checked: ".$imageCount."\n";

echo date("H:i:s\n");


function copyOriginalFile($id, $imageType){

	$message = 'File $id ';

	if ($imageType=="jpg") $imageType = "jpeg"; // jpg original stored in jpeg
	$originalImgPath = getImageFilePath($id, $imageType);
	$backupImgPath = getBackupFilePath($id, $imageType);
	$originalOK = file_exists($originalImgPath);
	if (!$originalOK){
		$message .= ":missing $imageType original $originalImgPath";
		return $message;
	}

	$message .= copyFile($originalImgPath,$backupImgPath);
	return $message;
}

function copyFile($oldPath, $newPath){

	if (file_exists($newPath) && filemtime($oldPath) == filemtime($newPath)) {
		$message = "no copy required";
		return $message;
	}
	$copy = "cp -p $oldPath $newPath";
	$res = shell_exec ($copy);
	echo "$copy\n";
	$message = "file $oldPath copied to $newPath";
	return $message;
}

?>
