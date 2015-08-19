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

define('PHP_ENTRY',0); // valid entry point

require_once('../../configuration/image.server.php');
include_once("imageFunctions.php");
include_once ('imageProcessing.php');
$db = connect();

// OPTIONAL fields
$SELECT_LIMIT = " and i.id>=480000 ";
$OUTPUT_DIR = null;

// optional parameter
$FILE_SOURCE_DIR = $config->fileSource;

if ($argc>1){
	$FILE_SOURCE_DIR = $argv[1]."/";
}

echo "Looking for files in directory $FILE_SOURCE_DIR\n";
if($OPTIONAL){
	OPTIONAL_INIT();
}

$missingSql = "select  b.id, u.uin, i.accessNum, i.originalFileName, i.imageType, "
." '', imageWidth, imageHeight "
." from ((BaseObject b join Image i on b.id = i.id) join User u on b.userId = u.id) "
." left "
." join MissingImages m on b.id = m.id "
." where i.accessNum > 0 and originalfilename is not null $SELECT_LIMIT ";

//." and  b.dateLastModified > now() - interval 5 day ";

echo "SQL: $missingSql\n";
$result = $db->query($missingSql) or die($db->getUserInfo());
if(PEAR::isError($result)){
	echo("Error in Missing SQL query".$result->getUserInfo()." $sql\n");
	die();
}
$imageCount = 0;
while($row = $result->fetchRow()){
	$imageCount++;
	// get fields b.id, u.uin, i.accessNum, i.originalFileName, i.imageType, m.problems
	$id = $row[0];
	$uin = $row[1];
	$accessNum = $row[2];
	$fileName = $row[3];
	$imageType = strtolower($row[4]);
	$problems = $row[5];
	$width = $row[6];
	$height = $row[7];
	list($message, $w, $h) = fixImageFiles($id, $fileName, $imageType, $problems, 
	$FILE_SOURCE_DIR, $width, $height);
	if($message[0]=='F') echo $message."\n";
	if ($imageCount % 1000 == 0){
		echo "No. images: $imageCount\tlast id: $id\tlast message: $message\t last width $w height $h layers $l\n";
	}
}
echo "\n\nNumber of image objects checked: ".$imageCount."\n";
echo "\n\nNumber of image files fixed: ".$numFixed."\n";

?>
