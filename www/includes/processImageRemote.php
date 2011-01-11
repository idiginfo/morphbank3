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
