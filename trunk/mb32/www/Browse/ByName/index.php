<?php
include_once('head.inc.php');

include_once('mainBrowseByName.php');

// The beginnig of HTML
$title = 'Browse - Taxon names';
initHtml($title, null, null);

// Add the standard head section to all the HTML output.
echoHead(false, $title);

// Output the content of the main frame
mainBrowseByName('Taxon names');

// Finish with end of HTML
finishHtml();
?>
