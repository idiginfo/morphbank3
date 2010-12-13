<?php

/*
 File name: validateUser.inc.php
 @author Neelima Jammigumpula <jammigum@scs.fsu.edu>
 @package Morphbank2
 @subpackage security

 This script directs user to login page, if not logged in, when user tries to access modules that require logging into the system.

 */
// set $SESSION_LIMITER to change 'public' behavior of response headers
// use value 'nocache' to force no caching
// use value 'public' to allow caching
$SESSION_STYLE = 'nocache';
if ($SESSION_LIMITER){
	$SESSION_STYLE = $SESSION_LIMITER;
}
session_cache_limiter($SESSION_STYLE);
session_start();

$objInfo = new sessionHandler();

if($_SESSION['userInfo']){
	$objInfo = unserialize($_SESSION['userInfo']);
}

$objInfo->setDomainName($config->domain);
$objInfo->setLink($link);

function isLoggedIn(){
	global $objInfo;
	return $objInfo->getLogged() && $objInfo->getUserGroup() != "";
}

function isAdministrator(){
	global $objInfo;
	if (!isLoggedIn()) return false;
	return $objInfo->getUserGroupRole() == 'administrator';
}

function isInGroup(){
	global $objInfo;
	return $objInfo->getUserGroup() != "";
}

function checkIfLogged(){
	global $config;
	if(!isLoggedIn()){
		header('Location:' .$config->domain. 'Submit/');
		exit();
	}
}

function checkAdministrator(){
	global $objInfo, $config;
	if(!isAdministrator()){
		header('Location:' .$config->domain. 'About/');
		exit();
	}
}

//function groups($userGroup){
function groups(){
	global $config;
	if(!isInGroup()){
		header('Location: ' .$config->domain. 'Submit/groups.php?group=\'groups\'');
		exit();
	}
}
?>
