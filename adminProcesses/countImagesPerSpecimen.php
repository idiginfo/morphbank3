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
