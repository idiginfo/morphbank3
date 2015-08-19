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

if (!file_exists('../configuration/config.ini')) {
	header ("location: /install/install.php");
	exit;
}

include_once('head.inc.php');
include_once('imageFunctions.php');

// Get object id from parameters, if available
$id = trim($_REQUEST['id']);
if (!empty($id)){// no 'id' parameter
	if(isset($_REQUEST['accessNum'])){// parameter 'accessNum' is an image access number
		$id = accessNumToId(trim($_REQUEST['accessNum']));// find object id from image access number
	}
}

$imgType = trim($_REQUEST['imgType']);
$imgSize = trim($_REQUEST['imgSize']);
if (!empty($id) && (!empty($imgType) || !empty($imgSize))  ) {
	// request is for an image file

	// get bots out of our images
	if ($imgType!='jpg' && $imgType!='thumbs' && $config->isBot){
		header('HTTP/1.1 403 Forbidden');
		die();
	}

	// redirect to imageserver with sessionId
	if (isLoggedIn()) $sessionId = session_id();
	$tag = getImageServerUrl($id,$imgType,$sessionId,$imgSize);
       
        //TODO make a 'head' call to check for error
        // if error, dump the proper default image
        // if not error, redirect as below
        //$response = http_head($tag);

        require_once 'HTTP/Request2.php';
        $request  = new HTTP_Request2($tag);
        $response = $request->send();

        if ($response == False) {
            header("Error in request.");
        }
        else if ($response == "HTTP/1.1 401 Unauthorized"
                || $response == "HTTP/1.1 400 Bad Request: The requested object
                is not on the server") {
            header($response);
            //TODO display image not found ?
        }
        else {
            header("Status: 302 Temporary redirect");
            header("Location: $tag");

        }
        die();
}

if (stripos($_SERVER['REQUEST_METHOD'], 'HEAD') !== FALSE){
	// HEAD request return content type
	header('Content-Type: text/html');
} elseif ( !empty($id) ) {// request is for a show page for an object
	include_once ("Show/index.php");
} elseif ($_REQUEST['search']) {// request is for a search
	//$url = $config->domain . 'Show/?search='.$_REQUEST['search'];
	//transfer to Show: no redirect
	$_REQUEST['keywords'] = $_REQUEST['search'];
	include_once ("MyManager/index.php");
	//header('Location: '.$url);

} elseif ($_REQUEST['keywords']) {// request is for a keyword search, redirect to MyManager
	//	$url = $config->domain . 'MyManager/?keywords='.$_REQUEST['keywords'];
	//	if ($_REQUEST['tab'])
	//	$url = $url . '&tab=' . $_REQUEST['tab'];
	include_once ("MyManager/index.php");
	//header('Location: '.$url);
} else { // request for home page
	include_once('mainIndexPage.php');
	
	$title = $config->welcomeMsg;
	// The beginnig of HTML
	initHtml( $title, NULL, NULL);

	// Add the standard head section to all the HTML output.
	echoHead( false, $title, false);

	// Output the content of the main frame
	mainindexPage();

	// Finish with end of HTML
	finishHtml();
}
?>
