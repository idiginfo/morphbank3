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

$db = connect();

$oneBaseObjectSql = "SELECT thumbURL FROM BaseObject WHERE id=?";
$idParams = array('integer');
$oneBaseObjectStmt = setupStmt($oneBaseObjectSql, $idParams);
$baseObjectSql = "SELECT id, thumbURL, userId, groupId FROM BaseObject";
$imageSql = "SELECT id, accessNum, originalFileName, imageType FROM Image ";
$specimenSql = "SELECT Specimen.id, Specimen.standardImageId FROM Specimen ";
$viewSql = "SELECT View.id, View.standardImageId FROM View ";
$collectionSql = "SELECT BaseObject.id FROM BaseObject WHERE BaseObject.objectTypeId='Collection' ";
$collectionObjectsSql = "SELECT objectId FROM CollectionObjects WHERE collectionId=? ORDER BY objectOrder LIMIT 1 ";
$characterSql = "SELECT BaseObject.id FROM BaseObject WHERE BaseObject.objectTypeId='MbCharacter' ";
$annotationSql = "SELECT Annotation.id, Annotation.objectId FROM Annotation WHERE Annotation.objectTypeId = 'Image' ";

$thumbUpdateSql = "UPDATE BaseObject SET thumbURL = ? WHERE id = ?";
$thumbParams = array('integer', 'integer');
$thumbUpdateStmt = setupStmt($thumbUpdateSql, $thumbParams);

//TODO get convert procedural to object-oriented database access
// Image
$results = $db->query($imageSql) or die(mysqli_error($link));
if ($results) {
	setObjectArray();
	$imageCount = 0;
	for ($i=0; $i < $numRows; $i++) {
		$id = $objArray[$i][0];
		$updateResults = updateThumbUrl($id,$id);
		$imageCount++;
	}
}
echo "\n\nTotal Images: ".$imageCount."\n";
unset($objArray, $results);

// Specimen
$results = mysqli_query($link, $specimenSql) or die(mysqli_error($link));;
if ($results) {
	setObjectArray();
	$spCount = 0;
	for ($i=0; $i < $numRows; $i++) {
		$id = $objArray[$i][0];
		$updateResults = updateThumbUrl($id,$id);
		$imageCount++;
				$spCount++;
	}
}
echo "\nTotal Specimens: ".$spCount."\n";
unset($objArray, $results);

// View
$results = mysqli_query($link, $viewSql) or die(mysqli_error($link));;
if ($results) {
	setObjectArray();
	$viewCount = 0;
	for ($i=0; $i < $numRows; $i++) {
		mysqli_query($link, $updateSql);
		$viewCount++;
	}
}
echo "\nTotal Views: ".$viewCount."\n";
unset($objArray, $results);

// Collection
$results = mysqli_query($link, $collectionSql) or die(mysqli_error($link));;
if ($results) {
	setObjectArray();
	$collectionCount = 0;
	for ($i=0; $i < $numRows; $i++) {
		//$updateSql = "SELECT objectId FROM CollectionObjects WHERE collectionId='.$collectionArray[$i]['id'].' ORDER BY objectOrder LIMIT 1 ";
		//echo $updateSql."\n";
		$result = mysqli_query($link, $updateSql);
		if ($result) {
			$idArray = mysqli_fetch_array($result);
			//$sql = 'SELECT thumbURL FROM BaseObject WHERE id='.$idArray['objectId'];
			$result = mysqli_query($link, $sql);
			if ($result) {
				$thumbURL = mysqli_fetch_array($result);
				mysqli_query($link, $updateSql);
				$collectionCount++;
			}
		}
	}
}
echo "\nTotal Collections: ".$collectionCount."\n\n";
unset($objArray, $results);

// MbCharacter
$results = mysqli_query($link, $characterSql);
if ($results) {
	setObjectArray();
	$characterCount = 0;
	for ($i=0; $i < $numRows; $i++) {
		$imageId = PhyloCharacter::getThumb($characterArray[$i]['id']);
		if (!empty($imageId)) {
			mysqli_query($link, $updateSql);
			$characterCount++;
		}
	}
}
echo "\nTotal Characters: ".$characterCount."\n\n";
unset($objArray, $results);

// Annotation
$results = mysqli_query($link, $annotationSql);
if ($results) {
	setObjectArray();
	$annotationCount = 0;
	for ($i=0; $i < $numRows; $i++) {
		//$base_sql = 'SELECT thumbURL FROM BaseObject WHERE id='.$annotationArray[$i]['objectId'];
		$thumbResult = mysqli_query($link, $sql);
		if ($thumbResult) {
			$thumbArray = mysqli_fetch_array($thumbResult);
			mysqli_query($link, $updateSql);
			$annotationCount++;
		}
	}
}
echo "\nTotal Annotations: ".$annotationCount."\n\n";
unset($objArray, $results);

function setObjArray(){
	global $results, $numRows, $objArray;
	$numRows = mysqli_num_rows($results);
	for ($i=0; $i < $numRows; $i++) {
		$objArray[$i] = mysqli_fetch_array($results);
	}
}
// sample code
function setupStmt($sql, $paramTypes){
	global $db;
	$stmt = $db->prepare($sql,$paramTypes, MDB2_PREPARE_RESULT);
	if(PEAR::isError($stmt)){
		echo("setupStmt error for $sql\n".$stmt->getUserInfo()." $sql\n");
	}
	return $stmt;
}

function processStmt($stmt, $params){
	//$params = array($keywordText, $imageAltText, $xml_output, $id);
	$stmtResults = $stmt->execute($params);
	if(PEAR::isError($stmt)){
		echo("processStmt error for $sql\n".$stmt->getUserInfo()." $sql\n");
	}
	return $stmtResults;
}

function updateThumbUrl($thumbUrl, $id){
	$params = array($thumbUrl, $id);
	$results = processStmt($thumbUpdateStmt, $params);
	return $results;
}

?>
