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

if (checkAuthorization('ViewAngle', $objInfo->getUserId(), $objInfo->getUserGroupId(), 'Modify')) {
	for ($i = 0; $i < $rows; $i++) {
		if ($query[$i]) {
			//echo $query[$i];
			$results = mysqli_query($link, $query[$i]);

			if ($results) {
				//UpdateBaseObject($id[$i], "Modified ViewAngle");
				//echo 'Id: ' .$id[$i]. 'Modified From: ' .$modifiedFrom[$i]. 'Modified To: ' .$modifiedTo[$i]. '<br />';
				History($id[$i], $objInfo->getUserId(), $objInfo->getUserGroupId(), $modifiedFrom[$i], $modifiedTo[$i], "ViewAngle");
			} else {
				echo $query . '<BR>';
				echo mysqli_error($link);
			}
		}
	}

	$url = 'index.php';
	//$url = 'index.php?code=1&id='.$curr['id'];
	header("location: " . $url);
	exit;
} else {


	$url = '../mailpage.php?message=' . $message;
	header("location: " . $url);
	exit;
}
// end of else part of checkAuthorization
?>
