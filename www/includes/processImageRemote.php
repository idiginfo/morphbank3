<?php
/**
 * Send a file to the Image server for inclusion in the image file system
 *
 * @param $id 			id of the image file
 * @param $imageFile 	name and path of the file to be sent
 * @param $imageType	mime type of the file
 * @return array
 */
function processImageRemote($id, $imageFilePath, $imageFileName){
	global $config;
	
	if (empty($id)) return "BaseObject ID passed to processImageRemote() is empty";
	
	include_once ("HTTP/Request.php");
	// get the file type from the extension
	$parts = split('.',$imageFileName);
	// get the mimetype
	$imageSizes = @getimagesize($imageFilePath);
	if (!$imageSizes) {
		// not a valid php image? maybe it's dng
		$imageMimeType = "application/octet-stream";
	} else {
		$imageMimeType = $imageSizes["mime"];
	}

	$request =& new HTTP_Request($config->imgServerUrl.'Image/imageFileUpload.php');
	$request->setMethod(HTTP_REQUEST_METHOD_POST);

	// add parameters
	$request->addPostData('id', $id);
	$request->addPostData('fileName', $imageFileName);
	$request->addFile('image', $imageFilePath, $imageMimeType);
	$response = $request->sendRequest();
	if (PEAR::isError($response)) {
		return $response->getUserInfo();
	}
	$response = $request->getResponseBody();
	return split('\^',$response) ;
}
?>
