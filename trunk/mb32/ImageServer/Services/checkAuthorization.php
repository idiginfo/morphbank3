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
