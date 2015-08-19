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

include_once('head.inc.php');
include_once('extLinksRefs.php');
include_once('showFunctions.inc.php');

/* Include javascript files */
$includeJavaScript = array('jquery.1-4-2.min.js', 'jquery.validate.min.js', 'formMethods.js');

// The beginning of HTML
$title = 'Add Publication';
initHtml($title, null, $includeJavaScript);

// Output the content of the main frame
echoHead(false, $title);

echo '<div class="mainGenericContainer" style="width:760px">';

// Check authorization
if(!checkAuthorization(null, $objInfo->getUserId(), $objInfo->getUserGroupId(), 'add')){
	echo getNonAuthMessage(getNonAuthCode()) . "<br /><br />";
} else {
	echo checkPublicationMessage($_REQUEST['id'], $_REQUEST['code']);
	displayPublicationForm($_REQUEST);
}

echo '</div>';
finishHtml();

/**
 * Check for any GET message codes
 * @param array $array
 * @return void
 */
function checkPublicationMessage($id, $code){
	$msg = '<h1><b>Add Publication</b></h1><br/><br/>';
	if ($code == 1) {
		if ($_GET['pop'] == 'yes') echo "<script>opener.updatePublication(".$id.", '".$_GET['objTitle']."'); window.close();</script>";
		$msg .= "<h3>You have successfully added a <a href=\"/?id=$id\">Publication with id $id</a></h3>\n";
		$msg .= "<br/>The form has been filled in to make it easy to submit a similar publication\n";
		$msg .= "<br/><a href=\"index.php\">Click here to clear the form</a></h3><br/><br/>\n";
	} elseif ($code == 2) {
		$msg .= '<div class="searchError">Error createing BaseObject and Publication</div><br /><br />'."\n";
	} elseif ($code == 3) {
		$msg .= '<div class="searchError">Error updating new Publication</div><br /><br />'."\n";
	} elseif ($code == 4) {
		$msg .= '<div class="searchError">Error inserting external link or reference</div><br /><br />'."\n";
	} elseif ($code == 10) { // See deleteExtLink.php
		$msg .= '<div class="searchError">Error deleteing external link or reference</div><br /><br />'."\n";
	}
	return $msg;
}

/**
 * Display submit Publication form
 * @return void
 */
function displayPublicationForm($array){
	$frmId = "addPublication";
	$frmAction = "commitPublication.php";
	$frmButtonText = "Submit";
	$id = null;
	$ref = null;
	$contributor = $array['Contributor'];
	$groupid = $array['groupId'];
	$datetopublish = $array['DateToPublish'];
	include_once('publicationForm.php');
}
?>
