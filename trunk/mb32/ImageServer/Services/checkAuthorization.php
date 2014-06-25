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
include_once ("HTTP/Request2.php");

function checkAuthorization($id, $sessionId = null, $function = 'view'){
	global $config;
	// if the request comes from application, say yes.
	if (approveRequestor()) return true;
    $request = new HTTP_Request2($config->appServerBaseUrl."checkImageAuthorization.php?$id", HTTP_Request2::METHOD_POST);

	// add parameters
	$request->addPostParameter('id', $id);
	if (empty($sessionId)){// if not passed, in check HTTP parameter
		$sessionId = $_REQUEST['sessionId'];
	}
	if (!empty($sessionId)){
		$request->addPostParameter('sessionId', $sessionId);
	}
	if ($function != 'view'){
		$request->addPostParameter('function', $function);
	}

    try {
        $response = $request->send();
        if (200 == $response->getStatus()) {
            $body = $response->getBody();
            return $body;
        } else {
            echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' . $response->getReasonPhrase();
            return false;
        }
    } catch (HTTP_Request2_Exception $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }

}

function approveRequestor(){
	$requestor = explode('.',$_SERVER['REMOTE_HOST']);
	if ($requestor[1]=='morphbank' && $requestor[2]=='net'
	&& $requestor[0]!='isample'){
		return true;
	}
	return false;
}

function getIdFromURI($extId){
	global $config;
	$requestUri = $config->appServerBaseUrl."getIdFromURI.php";
    $request = new HTTP_Request2($requestUri, HTTP_Request2::METHOD_POST);

	// add parameters
	$request->addPostParameter('uri', $extId);

    try {
        $response = $request->send();
        if (200 == $response->getStatus()) {
            $body = $response->getBody();
            $id = intval($body);
            return $id;
        } else {
            return false;
        }
    } catch (HTTP_Request2_Exception $e) {
        return false;
    }
}
?>
