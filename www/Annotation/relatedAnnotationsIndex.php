<?php
include_once('head.inc.php');
include_once('Admin/admin.functions.php');
include_once('relatedAnnotations.php');
include_once('postItFunctions.inc.php');

// The beginnig of HTML
$title = 'Related Annotations';

initHtml($title, null, null);
echoHead(false, $title);
setUpPostIt();
if (isset($_GET['id'])) {
	$imageId = $_GET['id'];
} else {
	die;
}


// Output the content of the main frame
$PrevURL = $_SERVER['HTTP_REFERER'];
relatedAnnotations($imageId, $PrevURL);
// Finish with end of HTML
finishHtml();
?>
