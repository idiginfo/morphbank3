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


require_once(dirname(dirname(__FILE__)) . '/configuration/app.server.php');
$config->errorRedirect = 0;

// Connect to MySQL server
$link = Adminlogin();

if (!$link)
echo "Error connecting to $database";

mysqli_select_db($link,'mbDemo');
echo date("H:i:s\n");

$result = mysqli_query($link, "SELECT DISTINCT(id) AS LocId FROM Locality ORDER BY id");

if (!$result) {
	echo "Could not successfully run query ($sql) from DB: " . mysqli_error($link);
	exit;
}

if (mysqli_num_rows($result) == 0) {
	echo "No rows found, nothing to print so am exiting";
	exit;
}
while($array = mysqli_fetch_array($result)){

	$imagecountsql = 'SELECT COUNT(*) AS imgcount FROM Image LEFT JOIN Specimen on Specimen.id = Image.specimenId  LEFT JOIN Locality on Locality.id = Specimen.localityId
			 WHERE Locality.id =' .$array['LocId']. ';';

	$imgcount = mysqli_query($link, $imagecountsql) or die('Could not run query' .mysqli_error($link));
	//$num = mysqli_result(mysqli_query($link, $imagecountsql),0);
	$num = mysqli_fetch_array($imgcount);

	$imgcountsql = 'UPDATE Locality SET imagesCount = '.$num['imgcount'].' WHERE id = ' .$array['LocId']. ';';
	//echo  $array['LocId'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $num. '&nbsp;&nbsp;&nbsp;' .$imgcountsql. '<br>';
	mysqli_query($link, $imgcountsql) or die ('Could not run query' . mysqli_error($link));

}
echo date("H:i:s\n");

?>
