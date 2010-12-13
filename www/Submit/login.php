<?php
 /*
   File name: login.php 
   @author Neelima Jammigumpula <jammigum@scs.fsu.edu>
   @package Morphbank2
   @subpackage Submit

*/

session_register('userInfo');

$link = Adminlogin();
$mySessionHandler = new sessionHandler($link);      

if($mySessionHandler->checkLogin($_POST['username'], $_POST['password'], $link) == 'true') {
	$_SESSION['userInfo'] = serialize($mySessionHandler);
	header('Location: /Submit/updateInfo.php?uin='.$_POST['username'].'&fromLogin=true');
}else{
	header('Location: /Submit/?login=failed');
}

