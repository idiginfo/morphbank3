<?php
if ($_REQUEST['pop']) {
	include_once('pop.inc.php');
} else {
	include_once('head.inc.php');
}

include_once('tsnFunctions.php');
include_once('Admin/admin.functions.php');
include_once('mainTaxon.php');


// The beginning of HTML
$title = 'Taxon Name Search';
initHtml($title, null, null);
//********************************************************************
// Add the standard head section to all the HTML output.             *
//********************************************************************
// Output the content of the main frame

echoHead(false, $title);


mainTaxon();

finishHtml();
?>
