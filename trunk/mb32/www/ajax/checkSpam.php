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

if (isset($_GET['id'])) {
	
	$sql = 'SELECT * FROM Spam WHERE id = '.$_GET['id'];
	
	$result = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if ($result) {
		$spamArray = mysqli_fetch_array($result);	
		
		if (strtolower($spamArray['code']) == strtolower($_GET['code'])) {
			
			$jsonString = "spamObj = { result : true }";
		} 
		else
			$jsonString = "spamObj = { result : false }";
	}
	else 
		$jsonString = "spamObj = { result : false }";
}
else 
	$jsonString = "spamObj = { result : false }";

echo $jsonString;

?>
