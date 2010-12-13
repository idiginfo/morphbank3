<?php
include_once('head.inc.php');



$objectTSN = (isset($_GET['objectTSN'])) ? $_GET['objectTSN'] : NULL;
$userTSN = (isset($_GET['userTSN'])) ? $_GET['userTSN'] : NULL;
$groupTSN = (isset($_GET['groupTSN'])) ? $_GET['groupTSN'] : NULL;

/*
if (!taxonPermit($objectTSN, $userTSN, $userTSN))
	echo "FALSE";
else
*/
	echo "TRUE";
?>
