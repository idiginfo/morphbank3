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
 * This script has 2 functions:
 * 	1. Populate thumbURL field for
 * 		a. Image, Specimen, Locality, View from standardImageId
 * 		b. Collection, MbCharacter, Annotation by inference
 * 	2. Identify images with missing files and process the image files
 */
define('PHP_ENTRY',0);// valid Web app entry point

require_once('../../configuration/image.server.php');
include_once("imageFunctions.php");

$db = connect();

// OPTIONAL fields
$SELECT_LIMIT= null;// check all images
$DELETE_LIMIT= null;// clear MissingImages table
$TIF_ERROR= false;// do not mark missing tif files as error

if($OPTIONAL){
	// redefine base file path, limits on select and delete and dependence on TIF file
	OPTIONAL_INIT();
}

$imageSql = "SELECT id, accessNum, originalFileName, imageType, imageWidth, imageHeight "
." FROM Image $SELECT_LIMIT ";
$clearMissingImageSql = "delete from MissingImages $DELETE_LIMIT ";
$numDeleted =  $db->exec($clearMissingImageSql);
if(PEAR::isError($numDeleted)){
	echo("setupStmt error for $missingImageInsertSql\n".$numDeleted->getUserInfo()." $sql\n");
}
echo "Number deleted from MissingImages $numDeleted\n";

$missingImageInsertSql = "insert into MissingImages (id, accessNum, problems) values (?,?,?)";
$paramTypes = array('integer', 'integer', 'text');
$missingImageInsertStmt = $db->prepare($missingImageInsertSql,$paramTypes, MDB2_PREPARE_RESULT);
if(PEAR::isError($missingImageInsertStmt)){
	echo("setupStmt error for $missingImageInsertSql\n".$missingImageInsertStmt->getUserInfo()." $sql\n");
}

// Image
$result = $db->query($imageSql) or die(mysqli_error($link));
while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC)){
	$imageCount++;
	$id = intval($row[0]);
	$accessNum = intval($row[1]);
	//$imgPath = accessNumToPath($accessNum);
	$fileName = $row[2];
	$imageType = $row[3];
	$imageWidth = $row[4];
	$imageHeight = $row[5];
	if ($accessNum < 0 ){
		//echo "Negative access number for id: $id accesssNum: $accessNum\n";
		$problems = "negative";
	} else {
		$problems = checkForImageFiles($id, $accessNum, $imageType, $imageWidth, $imageHeight);
	}
	if ($problems){
		echo "Image problems for id: $id accessNum: $accessNum problems: $problems\n";
		$params = array($id, $accessNum, $problems);
		$stmtResults = $missingImageInsertStmt->execute($params);
		if(PEAR::isError($stmtResults)){
			echo("Error in MissingImage insert".$stmtResults->getUserInfo()." $sql\n");
		}
		$missingCount ++;
	}
	if ($imageCount % 1000 == 0){
		echo "No. images: $imageCount\tlast id: $id\tNo. missing: $missingCount\n";
	}
}
echo "\n\nTotal Missing Images: ".$missingCount."\n";
unset($objArray, $results);

function setObjArray(){
	global $results, $numRows, $objArray;
	$numRows = mysqli_num_rows($results);
	for ($i=0; $i < $numRows; $i++) {
		$objArray[$i] = mysqli_fetch_array($results);
	}
}

function checkForImageFiles($id, $accessNum, $imageType, $imageWidth, $imageHeight){
	$problems = null;
	$problems.= checkOriginalFile($id, $accessNum, $imageType);

	$problems .= checkWidthHeight($id, $accessNum, "jpeg", $imageWidth, $imageHeight);
	$problems .= checkDerivedFile($id, $accessNum, "jpg");
	$problems .= checkDerivedFile($id, $accessNum, "jpeg");
	$problems .= checkDerivedFile($id, $accessNum, "thumb");
	$problems .= checkDerivedFile($id, $accessNum, "tpc");
	if ($TIF_ERROR){
		$problems .= checkDerivedFile($id, $accessNum, "tif");
	}
	echo "No tif file for id: $id accessNum: $accessNum original type: $imageType \n";
	return $problems;
}

/**
 * Check for existence of original file
 * @param $id
 * @param $accessNum
 * @param $imageType
 * @return boolean
 */
function checkOriginalFile($id, $accessNum, $imageType){
	$imgArray = getAccessImagePath($id, $accessNum, $imageType);
	$imgPath = $imgArray['imgSrc'];
	if(file_exists($imgPath)) return "";
	return ":original for id $id missing $imgPath ";
}

function checkWidthHeight($id, $accessNum, $imageType, $imageWidth, $imageHeight){
	$imgArray = getAccessImagePath($id, $accessNum, $imageType);
	$imgPath = $imgArray['imgSrc'];
	if ($imageWidth == null || $imageWidth == '' || $imageHeight == null || $imageHeight == ''){
		return false;
	}
	if (! file_exists($imgPath)) return true; // no original
	// check width and height
	list ($fileWidth, $fileHeight, $fileType) = getimagesize($imgPath);
	if ($fileWidth != $imageWidth || $fileHeight != $imageHeight){
		return false;
	}
	return true;
}

function checkDerivedFile($id, $accessNum, $imageType) {
	$imgArray = getAccessImagePath($id, $accessNum, $imageType);
	$imgPath = $imgArray['imgSrc'];
	return file_exists($imgPath);
}
?>
