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
$db = connect();
echo "hello '".$config->appServer."'\n";
// OPTIONAL fields
$WHERE_CLAUSE = " where b.hostserver = '".$config->appServer."' ";
$SELECT_LIMIT= null;// check all images
$DELETE_LIMIT= null;// clear MissingImages table
$TIF_ERROR= false;// do not mark missing tif files as error

if($OPTIONAL){
	// redefine base file path, limits on select and delete and dependence on TIF file
	OPTIONAL_INIT();
}

$imageSql = "SELECT i.id, originalFileName, imageType, imageWidth, imageHeight "
." FROM Image i join BaseObject b on i.id=b.id $WHERE_CLAUSE $SELECT_LIMIT ";
echo "$imageSql\n";
$clearMissingImageSql = "delete from MissingImages $DELETE_LIMIT ";
$numDeleted =  $db->exec($clearMissingImageSql);
if(PEAR::isError($numDeleted)){
	echo("setupStmt error for $missingImageInsertSql\n".$numDeleted->getUserInfo()." $sql\n");
}
echo "Number deleted from MissingImages $numDeleted\n";

$missingImageInsertSql = "insert into MissingImages (id, problems) values (?,?)";
$paramTypes = array('integer', 'text');
$missingImageInsertStmt = $db->prepare($missingImageInsertSql,$paramTypes, MDB2_PREPARE_RESULT);
if(PEAR::isError($missingImageInsertStmt)){
	echo("setupStmt error for $missingImageInsertSql\n".$missingImageInsertStmt->getUserInfo()." $sql\n");
}

// Image
$result = $db->query($imageSql) or die(mysqli_error($link));
while($row = $result->fetchRow()){
	$imageCount++;
	$id = intval($row[0]);
	echo "checking id: $id\n";
	//$accessNum = intval($row[1]);
	//$imgPath = accessNumToPath($accessNum);
	$fileName = $row[2];
	$imageType = $row[3];
	$imageWidth = $row[4];
	$imageHeight = $row[5];
	echo "checking id $id\n";
	$problems = checkForImageFiles($id, $imageType, $imageWidth, $imageHeight);
	if ($problems){
		echo "Image problems for id: $id accessNum: $accessNum problems: $problems\n";
		$params = array($id, $accessNum, $problems);
		$stmtResults = $missingImageInsertStmt->execute($params);
		if(PEAR::isError($stmtResults)){
			echo("Error in MissingImage insert".$stmtResults->getUserInfo()." $sql\n");
		}
		$missingCount ++;
	}
	if ($imageCount % 1 == 0){
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

function checkForImageFiles($id, $imageType, $imageWidth, $imageHeight){
	$problems = null;
	$problems.= checkOriginalFile($id, $imageType);

	$problems .= checkWidthHeight($id, "jpeg", $imageWidth, $imageHeight);
	$problems .= checkDerivedFile($id, "jpg");
	$problems .= checkDerivedFile($id, "jpeg");
	$problems .= checkDerivedFile($id, "thumb");
	$problems .= checkDerivedFile($id, "tpc");
	if ($TIF_ERROR){
		$problems .= checkDerivedFile($id, "tif");
		echo "No tif file for id: $id original type: $imageType \n";
	}
	return $problems;
}

/**
 * Check for existence of original file
 * @param $id
 * @param $accessNum
 * @param $imageType
 * @return boolean
 */
function checkOriginalFile($id, $imageType){
	$imgArray = getAccessImagePath($id, $accessNum, $imageType);
	$imgPath = $imgArray['imgSrc'];
	if(file_exists($imgPath)) return "";
	return ":original for id $id missing $imgPath ";
}

function checkWidthHeight($id, $imageType, $imageWidth, $imageHeight){
	$imgArray = getAccessImagePath($id, $imageType);
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
