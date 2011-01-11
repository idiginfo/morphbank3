<?php
/**
* Copyright (c) 2011 Greg Riccardi, Fredrik Ronquist.
* All rights reserved. This program and the accompanying materials
* are made available under the terms of the GNU Public License v2.0
* which accompanies this distribution, and is available at
* http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
* 
* Contributors:
*   Fredrik Ronquist - conceptual modeling and interaction design
*   Austin Mast - conceptual modeling and interaction design
*   Greg Riccardi - initial API and implementation
*   Wilfredo Blanco - initial API and implementation
*   Robert Bruhn - initial API and implementation
*   Christopher Cprek - initial API and implementation
*   David Gaitros - initial API and implementation
*   Neelima Jammigumpula - initial API and implementation
*   Karolina Maneva-Jakimoska - initial API and implementation
*   Deborah Paul - initial API and implementation implementation
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

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

