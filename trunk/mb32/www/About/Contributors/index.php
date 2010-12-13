<?php

include_once('head.inc.php');
include_once('mainAboutContrib.php');


// The beginnig of HTML
$title =  'About - Contributors';
initHtml( $title, NULL, NULL);

// Add the standard head section to all the HTML output.
echoHead( false, $title);

// Output the content of the main frame
mainAboutContributors();

// Finish with end of HTML
finishHtml();
?>
