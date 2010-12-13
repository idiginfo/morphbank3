<?php

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
