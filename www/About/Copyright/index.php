<?php
include_once('head.inc.php');

// The beginnig of HTML
$title =  'About - Copyright/Citation';
initHtml( $title, NULL, NULL);

// Add the standard head section to all the HTML output.
echoHead( false, $title);

ob_start();
include ('content/citation.php');
$mainCitation = ob_get_contents();
ob_end_clean();

// Output the content of the main frame
echo '<div class="mainGenericContainer" >'.$mainCitation.'</div>';

// Finish with end of HTML
finishHtml();
?>
