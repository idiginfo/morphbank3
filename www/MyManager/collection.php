<?php

include_once('head.inc.php');


include_once('tsnFunctions.php');
include_once('objOptions.inc.php');
include_once('imageFunctions.php');
include_once('thumbs.inc.php');
require_once ('baseObjectSearchClass.inc.php');
require_once ('classes/collectionObjectClass.php');

collectionObjectMM::setupGetVars($config->displayThumbsPerPage);

include_once('parseGETvars.php');

$regenerator = new baseObjectSearch();

if (isset($_GET['characterCollectionToggle'])) {
	if ($_GET['characterCollectionToggle'] == "both") {
		$objectType = 'Collection\' OR Keywords.objectTypeId = \'MbCharacter ';
	} elseif ($_GET['characterCollectionToggle'] == "character") {
		$objectType = 'MbCharacter';
	} elseif ($_GET['characterCollectionToggle'] == "collection") {
		$objectType = 'Collection';
	} else {
		$objectType = 'Collection\' OR Keywords.objectTypeId = \'MbCharacter ';
	}
} else {
	$objectType = 'Collection\' OR Keywords.objectTypeId = \'MbCharacter ';
}

$countAndSearch = $regenerator->countAndSearch($phrase, 'Collection', $querySort, $limitBy, $limitOffset);
$total = $countAndSearch['numMatches'];
$res = $countAndSearch['search'];
if (MDB2::isError($res)){
	var_dump($res);
	die($res->getUserInfo());
}

$collectionObject = new collectionObjectMM($link, $config, $total);

$numRows = $collectionObject->getNumRows();

if ($numRows > 0) {
	for ($i = 0; $i < $numRows; $i++) {
		$resultArray[$i] = $res->fetchRow(MDB2_FETCHMODE_ASSOC);
	}
}
//var_dump($resultArray);
//mysqli_free_result($result);

//echo $resultArray[1]['id'];

$collectionObject->displayResults($resultArray);
//mysqli_free_result($result);

?>
