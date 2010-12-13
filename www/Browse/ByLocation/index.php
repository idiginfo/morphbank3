<?php
if ($_REQUEST['pop']) {
	include_once('pop.inc.php');
} else {
	include_once('head.inc.php');
}
include_once('mainBrowseByLocation.php');

// The beginnig of HTML
$title = 'Browse - Locality';
initHtml($title, null, null);

// Add the standard head section to all the HTML output.
echoHead(false, $title);

include_once('postItFunctions.inc.php');
setupPostIt();

// Output the content of the main frame
mainBrowseByLocation("Locality");

// Finish with end of HTML
finishHtml();
?>
