<?php
include_once('head.inc.php');


include_once('tsnFunctions.php');
include_once('objOptions.inc.php');
include_once('imageFunctions.php');
include_once('thumbs.inc.php');
require_once ('baseObjectSearchClass.inc.php');
require_once ('classes/annotationObjectClass.php');

annotationObject::setupGetVars($config->displayThumbsPerPage);

include_once('parseGETvars.php');

$regenerator = new baseObjectSearch();
$countAndSearch = $regenerator->countAndSearch($phrase, 'Annotation', $querySort, $limitBy, $limitOffset);
$total = $countAndSearch['numMatches'];
$res = $countAndSearch['search'];
if (MDB2::isError($res)){
	var_dump($res);
	die($res->getUserInfo());
}

$annotationObject = new annotationObject($link, $config, $total);

$numRows = $annotationObject->getNumRows();

if ($numRows > 0) {
	for ($i = 0; $i < $numRows; $i++){
		$resultArray[$i] = $res->fetchRow(MDB2_FETCHMODE_ASSOC);
	}
}
//var_dump($resultArray);
//mysqli_free_result($result);

//echo $resultArray[1]['id'];

$annotationObject->displayResults($resultArray);
//mysqli_free_result($result);

?>
