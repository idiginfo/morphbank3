<?php


include_once('head.inc.php');

include_once('mainHelpDocuments.php');

// The beginnig of HTML
$title =  'Help - Documents';
initHtml( $title, NULL, NULL);

// Add the standard head section to all the HTML output.
echoHead( false, $title);

// Output the content of the main frame
mainHelpDocuments();

// Finish with end of HTML
finishHtml();
?>
