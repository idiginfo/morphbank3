<?php

include_once('head.inc.php');

// The beginnig of HTML
$title = 'About '.$config->appName;
initHtml($title, null, null);

// Add the standard head section to all the HTML output.
echoHead(false, $title);

$aboutHtml = file_get_contents('content/about.html', true);
echo '<div class="mainGenericContainer">'. $aboutHtml . '</div>';

// Finish with end of HTML
finishHtml();
?>
