<?php

/**
 * File name: countImagesPerLocality.php
 * @package MB30
 * @subpackage: Admin/Processes
 * @author Neelima Jammigumpula  <jammigum@csit.fsu.edu>
 */

require_once ('../configuration/app.server.php');

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
