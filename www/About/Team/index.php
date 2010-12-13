<?php


include_once('head.inc.php');
include_once('mainAboutTeam.php');

// The beginnig of HTML
$title =  'About - Team';
initHtml( $title, NULL, NULL);

// Add the standard head section to all the HTML output.
echoHead( false, $title);

// Output the content of the main frame
mainAboutTeam();

// Finish with end of HTML
finishHtml();
?>
