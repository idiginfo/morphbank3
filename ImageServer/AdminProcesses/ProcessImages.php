<?php
/**
 * This script creates missing image files, as necessary
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
	list($message, $w, $h) = fixImageFiles($id, $fileName, $imageType, $problems, $uin,
	$FILE_SOURCE_DIR, $width, $height);
	if($message[0]=='F') echo $message."\n";
	if ($imageCount % 1000 == 0){
		echo "No. images: $imageCount\tlast id: $id\tlast message: $message\t last width $w height $h layers $l\n";
	}
}
echo "\n\nNumber of image objects checked: ".$imageCount."\n";
echo "\n\nNumber of image files fixed: ".$numFixed."\n";

?>
