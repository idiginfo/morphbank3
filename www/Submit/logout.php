<?php
/*
 File name: logout.php
 @author Neelima Jammigumpula <jammigum@scs.fsu.edu>
 @package Morphbank2
 @subpackage Submit
 */

include_once('head.inc.php');

if ($objInfo->getLogged() == true) {
	$objInfo->destroySession();
	header('Location:' .$config->domain);
}else{
	header('Location:'.$config->domain);
}
