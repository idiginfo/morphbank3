<?php


if ($_GET['pop']) {
	include_once('pop.inc.php');
} else {
	include_once('head.inc.php');
}
	
include_once('singleImageViewer.php');

// The beginnig of HTML
$title = 'Image viewer';
initHtml( $title, NULL, NULL);

// Add the standard head section to all the HTML output.
echoHead( false, $title);


// Output the content of the main frame
singleImageViewer($title);

finishHtml(); 
?>
