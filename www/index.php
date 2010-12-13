<?php
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
	if ($imgType!='jpg' && requesterIsBot()){
		header('HTTP/1.1 403 Forbidden');
		die();
	}

	// redirect to imageserver with sessionId
	if (isLoggedIn()) $sessionId = $_REQUEST['sessionId'];
	$tag = getImageServerUrl($id,$imgType,$sessionId,$imgSize);
	header("Status: 302 Temporary redirect");
	header("Location: $tag");
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
	
	$title = "Welcome to ";
	// The beginnig of HTML
	initHtml( $title, NULL, NULL);

	// Add the standard head section to all the HTML output.
	echoHead( false, $title.$config->appName, false);

	// Output the content of the main frame
	mainindexPage();

	// Finish with end of HTML
	finishHtml();
}
?>
