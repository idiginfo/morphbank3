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
	
	require_once ("HTTP/Request2.php");
	
	// get the mimetype
	$imageSizes = @getimagesize($imageFilePath);
	if (!$imageSizes) {
		// not a valid php image? maybe it's dng
		$imageMimeType = "application/octet-stream";
	} else {
		$imageMimeType = $imageSizes["mime"];
	}

	$request = new HTTP_Request2($config->imgServerUrl.'Image/imageFileUpload.php', HTTP_Request2::METHOD_POST);

	// add parameters
	$request->addPostParameter('id', $id);
	$request->addPostParameter('fileName', $imageFileName);
	$request->addUpload('image', $imageFilePath, $imageMimeType);

    try {
        $response = $request->send();
        if (200 == $response->getStatus()) {
            $body = $response->getBody();
            return explode('^',$body) ;
        } else {
            $body = $response->getBody();
            return array($body);
        }
    } catch (HTTP_Request2_Exception $e) {
        return false;
    }
}

