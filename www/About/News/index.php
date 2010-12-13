<?php



include_once('head.inc.php');
include_once('News.php');

// The beginnig of HTML
$title =  'News and Updates';
initHtml( $title, NULL, NULL);

// Add the standard head section to all the HTML output.
echoHead( false, $title);

// Output the content of the main frame
mainnewsText();

// Finish with end of HTML
finishHtml();
?>
