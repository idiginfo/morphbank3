<?php

include_once('head.inc.php');

// The beginnig of HTML
$title =  'About - Collaborations';
initHtml( $title, NULL, NULL);

// Add the standard head section to all the HTML output.
echoHead( false, $title);

$aboutCollaborations = file_get_contents('content/collaborations.html', true);
// Output the content of the main frame
echo '<div class="mainGenericContainer" style="width:760px">'.$aboutCollaborations.'</div>';

// Finish with end of HTML
finishHtml();
?>
