<?php


include_once('head.inc.php');
include_once('urlFunctions.php');
$id = $_GET['id'];
$sessionId = $_GET['sessionId'];
if (empty($sessionId)) $sessionId = session_id();

// The beginnig of HTML
$title = "Zooming Viewer for $id";
initHtml( $title, NULL, NULL);

// Add the standard head section to all the HTML output.
echoHead( false, $title);

echo imageServerTpcFrame($id, 800,600, $sessionId);
echo "<br/>";
//echo bischenAttributionTag();

finishHtml();

