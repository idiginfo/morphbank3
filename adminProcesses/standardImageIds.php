<?php
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
