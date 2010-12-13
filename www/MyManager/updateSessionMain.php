<?php

// this script is called via a AJAX GET call and puts the variables for the mymanager in the session
include_once('head.inc.php');

$link = AdminLogin();

$keywords = isset($_GET['keywords']) ? $_GET['keywords'] : "";
$goTo = isset($_GET['goTo']) ? $_GET['goTo'] : "";
$numPerPage = isset($_GET['numPerPage']) ? $_GET['numPerPage'] : 20;
$currentTab = isset($_GET['tab']) ? $_GET['tab'] : "imageTab";
$taxaToggle = isset($_GET['taxaOtuToggle']) ? $_GET['taxaOtuToggle'] : "both";
$collectionToggle = isset($_GET['characterCollectionToggle']) ? $_GET['characterCollectionToggle'] : "both";

$limitBy = array("contributor" => NULL, "submitter" => NULL, "current" => NULL, "any" => NULL);
$limitBy['contributor'] =  isset($_GET['limit_contributor']) ? $_GET['limit_contributor'] : NULL;
$limitBy['submitter'] =  isset($_GET['limit_submitter']) ? $_GET['limit_submitter'] : NULL;
$limitBy['current'] =  isset($_GET['limit_current']) ? $_GET['limit_current'] : NULL;
$limitBy['any'] =  isset($_GET['limit_any']) ? $_GET['limit_any'] : NULL;

$objInfo = new sessionHandler;

if($_SESSION['userInfo']){
	$objInfo = unserialize($_SESSION['userInfo']);
}

$objInfo->setKeywords($keywords);
$objInfo->setCurrentPage($goTo);
$objInfo->setNumPerPage($numPerPage);
$objInfo->setCurrentTab($currentTab);
$objInfo->setLimitBy($limitBy);
$objInfo->setTaxaToggle($taxaToggle);
$objInfo->setCollectionToggle($collectionToggle);


$_SESSION['userInfo'] = serialize($objInfo);

?>
