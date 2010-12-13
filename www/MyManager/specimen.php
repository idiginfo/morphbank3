<?php

include_once('head.inc.php');

include_once('tsnFunctions.php');
include_once('objOptions.inc.php');
include_once('imageFunctions.php');
include_once('thumbs.inc.php');
require_once ('baseObjectSearchClass.inc.php');
require_once ('classes/specimenObjectClass.php');

specimenObject::setupGetVars($config->displayThumbsPerPage);

include_once('parseGETvars.php');


//echo $query;

$regenerator = new baseObjectSearch();

$countAndSearch = $regenerator->countAndSearch($phrase, 'Specimen', $querySort, $limitBy, $limitOffset);
$total = $countAndSearch['numMatches'];
$res = $countAndSearch['search'];
if (MDB2::isError($res)){
	var_dump($res);
	die($res->getUserInfo());
}

$specimenObject = new specimenObject($link, $config, $total);

$numRows = $specimenObject->getNumRows();

if ($numRows > 0) {
	for ($i = 0; $i < $numRows; $i++) {
		$resultArray[$i] = $res->fetchRow(MDB2_FETCHMODE_ASSOC);
	}
}
//var_dump($resultArray);
//mysqli_free_result($result);

//echo $resultArray[1]['id'];

$specimenObject->displayResults($resultArray);
//mysqli_free_result($result);


?>
