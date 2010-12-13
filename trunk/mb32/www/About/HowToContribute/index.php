<?php
include_once('head.inc.php');

// The beginnig of HTML
$title =  'About - How to contribute';
initHtml( $title, NULL, NULL);

// Add the standard head section to all the HTML output.
echoHead( false, $title);

$contributeHtml = file_get_contents('content/contribute.html', true);
// Output the content of the main frame
echo '<div class="mainGenericContainer" style="width:700px">'.$contributeHtml.'</div>';

// Finish with end of HTML
finishHtml();
?>
