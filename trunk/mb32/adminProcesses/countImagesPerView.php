<?php

/**
 * File name: countImagesPerView.php
 * @package MB
 * @subpackage: adminProcesses
 * @author Wilfredo Blanco  <blanco@csit.fsu.edu>
 */

/**
 * This script counts the number of images by views
 */


$startTime = time();

require_once ('../configuration/app.server.php');

$link = Adminlogin();
if (!$link)
echo "Error connecting to $database";
echo date("H:i:s\n");

countImagesPerView();

function resetImagesCountField() {

	global $link;
	echo  "Reset imagesCount field\n";
	$sql = "UPDATE View SET imagesCount=0";
	$result = mysqli_query($link, $sql);
	if (!$result) die('Invalid query: ' . mysqli_error($link));
}

function countImagesPerView( ) {

	global $link;

	resetImagesCountField();

	echo  "Counting images\n";
	$sql = "SELECT viewId, COUNT(*) as imagesCount
			FROM Image 
			GROUP BY viewId 
			ORDER BY viewId";
		
	// get all views
	$result = mysqli_query($link, $sql);
	if (!$result) die("Invalid query: \n".$sql."\n". mysqli_error($link));

	while ($row = mysqli_fetch_array($result)) {
		updateImagesPerView ( $row['viewId'], $row['imagesCount']);
	}

}

function updateImagesPerView ( $viewId = 0, $imagesCount = 0) {

	global $link;
	if ($viewId == 0) return 0;

	$sql = 'UPDATE View SET imagesCount='.$imagesCount.' WHERE id='.$viewId;
	$result = mysqli_query($link, $sql);
	if (!$result) die("Invalid query: \n".$sql."\n". mysqli_error($link));
	echo $sql."\n";
}

echo "This process took: ".time()-$startTime." seconds";

echo date("H:i:s\n");
?>
