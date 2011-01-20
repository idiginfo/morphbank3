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

$link = Adminlogin();
mysqli_select_db($link, "MB30");
$sql = "SELECT id FROM Specimen WHERE (standardImageId = 0 OR standardImageId IS NULL) ";
echo date("H:i:s\n");

$results = mysqli_query($link, $sql);
if ($results) {
	$numRows = mysqli_num_rows($results);
	for ($i=0; $i < $numRows; $i++) {
		$specimenArray[$i] = mysqli_fetch_array($results);
	}
	$spCount = 0;
	for ($i=0; $i < $numRows; $i++) {
		$sql = 'SELECT Image.id FROM Image WHERE Image.specimenId = '.$specimenArray[$i]['id'].' ORDER BY Image.id ASC LIMIT 0,1 ';
		//echo $sql."\n";
		$result = mysqli_query($link, $sql);
		if ($result) {
			$idArray= mysqli_fetch_array($result);
			if ($idArray['id']) {
				$updateSql = 'UPDATE Specimen SET standardImageId = '.$idArray['id'].' WHERE id = '.$specimenArray[$i]['id'];
				//echo $updateSql."\n";
				mysqli_query($link, $updateSql);
				$updateSql = 'UPDATE BaseObject SET thumbURL = '.$idArray['id'].' WHERE id = '.$specimenArray[$i]['id'];
				//echo $updateSql."\n";
				mysqli_query($link, $updateSql);
				$spCount++;
			}
		}
	}
}

$sql = "SELECT View.id FROM View WHERE (standardImageId = 0 OR standardImageId IS NULL) ";
$results = mysqli_query($link, $sql);
if ($results) {
	$numRows = mysqli_num_rows($results);
	for ($i=0; $i < $numRows; $i++) {
		$viewArray[$i] = mysqli_fetch_array($results);
	}
	$viewCount = 0;
	for ($i=0; $i < $numRows; $i++) {
		$sql = 'SELECT Image.id FROM Image WHERE Image.viewId = '.$viewArray[$i]['id'].' ORDER BY Image.id ASC LIMIT 0,1 ';
		//echo $sql."\n";
		$result = mysqli_query($link, $sql);

		if ($result) {
			$idArray= mysqli_fetch_array($result);
			if ($idArray['id']) {
				$updateSql = 'UPDATE View SET standardImageId = '.$idArray['id'].' WHERE id = '.$viewArray[$i]['id'];
				//echo $updateSql."\n";
				mysqli_query($link, $updateSql);
				$updateSql = 'UPDATE BaseObject SET thumbURL = '.$idArray['id'].' WHERE id = '.$viewArray[$i]['id'];
				//echo $updateSql."\n";
				mysqli_query($link, $updateSql);
				$viewCount++;
			}
		}
	}
	echo "\n\nTotal Specimens: ".$spCount."\n";
	echo "\n\nTotal Views: ".$viewCount."\n\n";

}
echo date("H:i:s\n");

?>
