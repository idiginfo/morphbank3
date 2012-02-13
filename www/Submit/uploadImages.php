<?php

/*
 * Webpage allowing users to drop image files in their personal folder
 * using Javascript.
 */

include_once('head.inc.php');

// The beginnig of HTML
$title = 'Upload images';
$includeJavaScript = array();

initHtml( $title, $includeJavaScript, NULL);

// Add the standard head section to all the HTML output.
echoHead( false, $title);

echo '<div class="mainGenericContainer" style="width:700px">';
//TODO add the uploader here
echo '</div>';

// Finish with end of HTML
finishHtml();

?>
