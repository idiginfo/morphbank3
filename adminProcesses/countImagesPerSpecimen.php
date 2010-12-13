<?php

/**
 * File name: countImagesPerSpecimen.php
 * @package MB30
 * @subpackage: Admin/Processes
 * @author Neelima Jammigumpula  <jammigum@scs.fsu.edu>
 */

require_once ('../configuration/app.server.php');

$link = Adminlogin();

if (!$link)
echo "Error connecting to $database";

$sql = "SELECT Specimen.id, COUNT(Image.id) as imagesCount "
." FROM Specimen left join Image on Specimen.id = specimenId GROUP BY specimenId";
	
echo date("H:i:s\n");
$result = mysqli_query($link, $sql);
if (!$result) die("Invalid query: \n".$sql."\n". mysqli_error($link));

while ($row = mysqli_fetch_array($result)) {

	$sql = 'UPDATE Specimen SET imagesCount='.$row[1].' WHERE id='.$row[0];
	$results = mysqli_query($link, $sql);
	if (!$results) die("Invalid query: \n".$sql."\n". mysqli_error($link));
	//echo $sql."\n";

}

echo date("H:i:s\n");
?>
