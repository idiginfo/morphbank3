<?php
include_once('head.inc.php');

// The beginnig of HTML
$title =  'About - Sponsors';
initHtml( $title, NULL, NULL);

// Add the standard head section to all the HTML output.
echoHead( false, $title);

$sponsorsHtml = file_get_contents('content/sponsors.html', true);
// Output the content of the main frame
 echo '<div id="aboutSponsorsId" class="mainGenericContainer" style="width:700px">'.$sponsorsHtml.'</div>';

// Finish with end of HTML
finishHtml();
?>
