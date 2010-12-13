<?php
/**
 * This script moves files from old to new file strategy
 */
require_once ('../configuration/app.server.php');
include_once("imageFunctions.php");
include_once("imageProcessing.php");
include_once ('bischen/makeTpc.php');

$SLEEP_AFTER_MKDIR = 1;
$STATUS_FILE = "/data/scratch/status";
if ($argc > 1) {
	$STATUS_FILE .= $argv[1];
}
$STATUS_FILE .= ".txt";
$NUMBER_PER_LOOP = 2000;


while(true){
	echo "Time: ".date("H:i:s")."\n";
	// get the id of the last image processed

	$file = fopen($STATUS_FILE,'r');
	$lastId = fread($file, 20);
	fclose($file);

	if (empty($lastId)) $lastId = 0;

	$rows = false;
	for ($id = $lastId+1; $id < $lastId + $NUMBER_PER_LOOP; $id++){
		$imageCount++;
		// get fields i.id, i.accessNum, i.imageType, m.problems
		$message = makeTpcFile($id);
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

}

// code borrowed from includes/imageProcessing.php

function makeTpcFile($id){
	$message = '';
	$jpegImgPath = getNewPath($id, $accessNum, "jpeg");
	$tpcImgPath = getNewPath($id, $accessNum, "tpc");

	if( !file_exists($jpegImgPath) ||  file_exists($tpcImgPath)){
		return;
	}

	$message = convertTpc($id, $jpegImgPath, $tpcImgPath);
	//echo "Fixed $numFixed files for id: $id file types: $message \n";
	return $message;
}

function getNewPath($id, $accessNum, $imageType){
	$imgPath = getNewAccessImagePath($id, $imageType);
	return $imgPath['imgSrc'];
}

?>
