<?php
// all edits are in popup so set pop to yes
$_GET['pop'] = "yes";


if ($_REQUEST['pop']) {
	include_once('pop.inc.php');
} else {
	include_once('head.inc.php');
}

include_once('editAnnotation.php');

if ($_GET['pop'] == 'yes')
checkIfLogged($_GET['pop']);
else
checkIfLogged('no');

//checkIfLogged();
groups();


// The beginnig of HTML
$title = 'Edit Annotation';
initHtml($title, null, null);

// Add the standard head section to all the HTML output.
echoHead(false, $title);
echo '<script language="JavaScript" type="text/javascript" src="' . $config->domain . 'js/formValidation.js"></script>';
if (isset($_GET['id'])) {
	$imageId = $_GET['id'];
	$PrevURL = $_SERVER['HTTP_REFERER'];
	editAnnotation($imageId, $PrevURL);
}

// Finish with end of HTML
finishHtml();
?>

