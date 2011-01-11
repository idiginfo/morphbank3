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
