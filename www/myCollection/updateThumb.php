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

include_once('head.inc.php');
include_once('collectionFunctions.inc.php');

$link = AdminLogin();

$collectionId = (isset($_GET['collectionId'])) ? $_GET['collectionId'] : 0;
$thumbId = (isset($_GET['thumbId'])) ? $_GET['thumbId'] : 0;

if ($thumbId != 0 && $collectionId != 0) {

	if (createCollectionThumb($collectionId, $thumbId))
		echo '<div id="updateSuccess">'.$thumbId.'</div>';
	else
		echo '<div id="updateFail1">&nbsp;</div>';
}
else {
	echo '<div id="updateFail2">&nbsp;</div>';
}


?>
