<?php

// Config script
include_once('../../includes/head.inc.php');
include_once('Presentations.php');

// The beginnig of HTML
$title =  'Presentatons and Posters';
initHtml( $title, NULL, NULL);

// Add the standard head section to all the HTML output.
echoHead( false, $title);

// Output the content of the main frame
mainpresentationText();

// Finish with end of HTML
finishHtml();
?>