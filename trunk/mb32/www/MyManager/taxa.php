<?php

include_once('head.inc.php');

include_once('tsnFunctions.php');
include_once('objOptions.inc.php');
include_once('imageFunctions.php');
include_once('thumbs.inc.php');
require_once ('baseObjectSearchClass.inc.php');
require_once ('classes/taxaObjectClass.php');
taxaObject::setupGetVars($config->displayThumbsPerPage);

include_once('parseGETvars.php');

$objectType = (isset($_GET['taxaOtuToggle'])) ? $_GET['taxaOtuToggle'] : "both";
$regenerator = new baseObjectSearch();
$countAndSearch = $regenerator->countAndSearch($phrase, null, $querySort, $limitBy, $limitOffset, 
"select * from Taxa","select count(*) from Taxa ");
$total = $countAndSearch['numMatches'];
$res = $countAndSearch['search'];
isMdb2Error($res);
	
$taxaObject = new taxaObject($link, $config, $total);

$numRows = $taxaObject->getNumRows();

$resultArray = array();
while ($row= $res->fetchRow(MDB2_FETCHMODE_ASSOC)){
	$resultArray[] = $row;
}

$taxaObject->displayResults($resultArray);

?>
