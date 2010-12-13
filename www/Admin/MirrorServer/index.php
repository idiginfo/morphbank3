<?php


include_once('head.inc.php');
include_once('Admin/admin.functions.php');
include_once('mainMirror.php');

checkIfLogged();
groups();

// Check authorization
if (!isAdministrator()) {
	header("location: /Admin/User/edit");
}

// The beginnig of HTML
$title = 'Mirror Server Information';
initHtml($title, null, null);

// Add the standard head section to all the HTML output.
echoHead(false, $title);

// Output the content of the main frame
mainMirror();

// Finish with end of HTML
finishHtml();
?>
