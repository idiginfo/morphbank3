<?php
/**
 * Check with the Web server for authorization to allow access to the image
 *
 * @param $sessionId
 * @param $id
 * @param $function
 * @return boolean
 */
function checkAuthorization($id, $sessionId = null, $function = 'view'){

	global $config;
	// if the request comes from application, say yes.
	if (approveRequestor()) return true;

	include_once ("HTTP/Request.php");

	$request = new HTTP_Request($config->appServerBaseUrl."checkImageAuthorization.php?$id");
	$request->setMethod(HTTP_REQUEST_METHOD_POST);

	// add parameters
	$request->addPostData('id', $id);
	if (empty($sessionId)){// if not passed, in check HTTP parameter
		$sessionId = $_REQUEST['sessionId'];
	}
	if (!empty($sessionId)){
		$request->addPostData('sessionId', $sessionId);
	}
	if ($function != 'view'){
		$request->addPostData('function', $function);
	}
	// for sending image file $request->addPostFile('image', 'profile.jpg', 'image/jpeg');
	if (PEAR::isError($request->sendRequest())) {
		return false;
	}
	$response = $request->getResponseBody();
	if ($response == "true") return true;
	return $response;
}

function checkUpdateAuthorization($id, $sessionId, $function){

	global $UPDATE_AUTH_URL;
	include_once ("HTTP/Request.php");

	$request = new HTTP_Request($UPDATE_AUTH_URL);
	$request->setMethod(HTTP_REQUEST_METHOD_POST);

	// add parameters
	if (!empty($id)){
		$request->addPostData('id', $id);
	}
	if (empty($sessionId)){// no update if no session
		return false;
	}
	$request->addPostData('sessionId', $sessionId);
	$request->addPostData('function', $function);

	// for sending image file $request->addPostFile('image', 'profile.jpg', 'image/jpeg');
	if (PEAR::isError($request->sendRequest())) {
		return false;
	}
	$response = $request->getResponseBody();
	if ($response == "true") return true;
	return $response;
}

function approveRequestor(){
	$requestor = split('.',$_SERVER['REMOTE_HOST']);
	if ($requestor[1]=='morphbank' && $requestor[2]=='net'
	&& $requestor[0]!='isample'){
		return true;
	}
	return false;
}
?>
