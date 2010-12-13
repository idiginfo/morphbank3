<?php
include_once('head.inc.php');
include_once('mainCheckTSN.php');

// The beginnig of HTML
$title = 'Admin - ITIS';
initHtml($title, null, null);

// Add the standard head section to all the HTML output.
echoHead(false, $title);

// Output the content of the main frame
mainCheckTSN();

// Finish with end of HTML
finishHtml();
?>
