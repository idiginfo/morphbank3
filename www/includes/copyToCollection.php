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

include_once ('collectionFunctions.inc.php');
include_once ('http_build_query.php');
//echo http_build_query($_POST);

$postElements = count($_POST);
$numObjs = $postElements-1; // collectionId one less

/*
echo "<br>before";
foreach ($_POST as $k => $v) {
	echo "<br>\$_POST[$k] => $v"; 	
}
*/

//exit;
if ($objInfo->getUserId() == NULL)
	header('Location:'.$config->domain.'Submit/');
elseif ($objInfo->getUserGroupId() == NULL)
	header('Location:'.$config->domain.'Submit/groups.php');
else {
	
	$collectionIdArray = getIdArrayFromPost();
	//========================
	//Adminlogin();
	$numObjs = count($collectionIdArray);
	//var_dump($collectionIdArray);
	//exit(0);
	insertObjects( $collectionIdArray, NULL, $_GET['id'], $numObjs);
	updateCollectionKeywords($_GET['id']);
	header('Location:'.$config->domain.'myCollection/index.php?collectionId='.$_GET['id']);
}
?>
