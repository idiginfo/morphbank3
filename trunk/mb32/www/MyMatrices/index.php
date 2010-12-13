<?php


include_once('head.inc.php');
include_once('mainMyMatrices.php');

// The beginnig of HTML
$title =  'My Matrices';
initHtml( $title, NULL, NULL);

// Add the standard head section to all the HTML output.
echoHead( false, $title);

// Output the content of the main frame
mainMyMatrices();

// Finish with end of HTML
finishHtml();
?>
