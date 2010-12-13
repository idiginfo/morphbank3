<?php
include_once('head.inc.php');

// The beginnig of HTML
$title =  'About - History';
initHtml( $title, NULL, NULL);

// Add the standard head section to all the HTML output.
echoHead( false, $title);

$historyHtml = file_get_contents('content/history.html', true);
// Output the content of the main frame
echo '<div class="mainGenericContainer" >'.$historyHtml.'</div>';

// Finish with end of HTML
finishHtml();
?>
