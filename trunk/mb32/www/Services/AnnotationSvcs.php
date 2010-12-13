<?php
/**
 * Check an authorization request from the update server
 *
 */

$sessionId = $_REQUEST['sessionId'];
if(!empty($sessionId)){
	session_id($sessionId);
	session_start();
}

// must be POST request from the Web server to be accepted
$remoteIp = gethostbyname($_SERVER['REMOTE_ADDR']);
$imageServerIp = gethostbyname($UPDATE_SERVER_NAME);

$id = $_REQUEST['id'];
$function = $_REQUEST['function'];

$public = isPublic($id);
if ($public && (empty($function) || $function=='view')){
	$authorized = true;
} else if ($remoteIp !=  $imageServerIp /*|| 'POST' != $_SERVER['REQUEST_METHOD']*/){
	$authorized = false;
} else {
	session_id($session_id);
	$objInfo = resetObjInfo();
	$userId = $objInfo->getUserId();
	$groupId = $objInfo->getUserGroupId();
	$authorized = checkAuthorization($id, $userId, $groupId, $function);
}

if ($authorized === true || $authorized == 1) {
	echo "true";
} else {
	echo "false";
}

?>
