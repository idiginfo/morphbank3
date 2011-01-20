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
