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

include_once('head.inc.php');




$id = $_GET['id'];

$tsnArray = $objInfo->getUserInfo();
$groupTSN = $objInfo->getUserGroupInfo();

$sql = 'SELECT tsnId FROM Specimen INNER JOIN Image ON Specimen.id = Image.specimenId WHERE Image.id = '.$id;

$result = mysqli_query($link, $sql);

if ($result) {
	$array = mysqli_fetch_array($result);
	$objectTSN = $array['tsnId'];
	$userTSN = $tsnArray[2];
	$groupTSN = $groupTSN[3];

	if (!taxonPermit($objectTSN, $userTSN, $groupTSN)) {
		echo "FALSE";
	} else {
		echo "TRUE";
	}

} else {
	echo "FALSE";
}

?>
