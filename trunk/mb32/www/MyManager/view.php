<?php

include_once('head.inc.php');

include_once('tsnFunctions.php');
include_once('objOptions.inc.php');
include_once('imageFunctions.php');
include_once('thumbs.inc.php');
require_once ('baseObjectSearchClass.inc.php');
require_once ('classes/viewObjectClass.php');

viewObject::setupGetVars($config->displayThumbsPerPage);

include_once('parseGETvars.php');


$regenerator = new baseObjectSearch();
$countAndSearch = $regenerator->countAndSearch($phrase, 'View', $querySort, $limitBy, $limitOffset);
$total = $countAndSearch['numMatches'];
$res = $countAndSearch['search'];
if (MDB2::isError($res)){
	var_dump($res);
	die($res->getUserInfo());
}
	
$viewObject = new viewObject($link, $config, $total);

$numRows = $viewObject->getNumRows();

if ($numRows > 0) {
	for ($i = 0; $i < $numRows; $i++) {
		$resultArray[$i] = $res->fetchRow(MDB2_FETCHMODE_ASSOC);
	}
}

$viewObject->displayResults($resultArray);
//mysqli_free_result($result);

?>
