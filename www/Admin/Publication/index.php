<?php



if ($_GET['pop']) {
	include_once('pop.inc.php');
} else {
	include_once('head.inc.php');
}
include_once('Admin/admin.functions.php');
include_once('tsnFunctions.php');
include_once('editPublication.php');

checkIfLogged();
groups();

// The beginning of HTML
$title = 'Edit Publication';
initHtml($title, null, null);

// Add the standard head section to all the HTML output.
echoHead(false, $title);
//EditPublication();

finishHtml();
?>
