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


if (empty($_REQUEST['sessionId'])) {
	session_cache_limiter($SESSION_STYLE);
	session_start();
	
	$objInfo = new MbSessionHandler();
	
	if($_SESSION['userInfo']){
		$objInfo = unserialize($_SESSION['userInfo']);
	}
	
	$objInfo->setDomainName($config->domain);
	$objInfo->setLink($link);
}

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
