<?php

$ANNOTATION_SERVICE_URL = $config->appServerBaseUrl.'annotationService.php';

function setupPostRequest($method, $sessionId){
	$request = new HTTP_Request($ANNOTATION_SERVICE_URL);
	$request->setMethod(HTTP_REQUEST_METHOD_POST);

	$request->addPostData('method', $method);
	if (empty($sessionId)){// if not passed, in check HTTP parameter
		$sessionId = $_REQUEST['sessionId'];
	}
	if (!empty($sessionId)){
		$request->addPostData('sessionId', $sessionId);
	}
	return $request;
}
/**
 * Request the creation of an annotation
 * Request is transmitted to the service environment for processing
 *
 * @param $id
 * @param $sessionId
 * @param $properties
 * @return array of result information including 'success' boolean and 'message' string
 */
function createAnnotation($id, $sessionId, $properties){
	$request = setupPostRequest('create', $sessionId);
	// add parameters
	$request->addPostData('id', $id);

	if (PEAR::isError($request->sendRequest())) {
		echo "post request error ".$request->getUserInfo();
		return false;
	}
	$response = $request->getResponseBody();
	if ($response == "true") return true;
	return $response;
}


function deleteAnnotation($annotationId, $sessionId){
	$request = setupPostRequest('delete', $sessionId);
	$request->addPostData('annotationId', $annotationId);

	if (PEAR::isError($request->sendRequest())) {
		echo "post request error ".$request->getUserInfo();
		return false;
	}
	$response = $request->getResponseBody();
	if ($response == "true") return true;
	return $response;

}

/**
 * get the annotations related to object $id from the server
 * Present the server information according to the needs of the Bischen label service
 *
 * @param $id
 * @param $sessionId
 * @return array of Label objects, each an associative array
 */
function fetchAnnotations($id, $sessionId){

	$request = setupPostRequest('fetch', $sessionId);
	$request->addPostData('id', $id);

	if (PEAR::isError($request->sendRequest())) {
		echo "post request error ".$request->getUserInfo();
		return false;
	}
	$response = $request->getResponseBody();
	if ($response == "true") return true;
	 $response;
	//TODO turn $response into label array

}
?>
