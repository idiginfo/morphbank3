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
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

include_once('head.inc.php');
include_once('thumbs.inc.php');

checkIfLogged();
groups();

if (isset($_GET['tsn'])) {
	header('Location:' . $config->domain . 'Admin/TaxonSearch/editTSN.php?id=' . $_GET['id'] . '&prevURL=\'' . $url . '\'');
	exit;
}

if ($_GET['id']) {
	$id = $_GET['id'];
	$ObjectType = "SELECT objectTypeId, dateToPublish FROM BaseObject WHERE id = $id;";
	$row = mysqli_fetch_array(runQuery($ObjectType));
	
	if ($row) {
		if ($row['objectTypeId'] == 'View') {
			header("Location: /Edit/View/?id=$id");
			exit;
		} elseif ($row['objectTypeId'] == 'Specimen') {
			header("Location: /Edit/Specimen/?id=$id");
			exit;
		} elseif ($row['objectTypeId'] == 'Image') {
			header("Location: /Edit/Image/?id=$id");
			exit;
		} elseif (($row['objectTypeId'] == 'Location') || ($row['objectTypeId'] == 'Locality')) {
			header("Location: /Edit/Locality/?id=$id");
			exit;
		} elseif ($row['objectTypeId'] == 'Collection') {
			header("Location: /myCollection/?id=$id");
			exit;
		} elseif ($row['objectTypeId'] == 'Annotation') {
			header("Location: /Edit/Annotation/?id=$id");
			exit;
		} elseif ($row['objectTypeId'] == 'Publication') {
			header("Location: /Admin/Publication/editPublication.php?id=$id");
			exit;
		} elseif ($row['objectTypeId'] == 'Otu') {
			header("Location: /Phylogenetics/Otu/editOtu.php?id=$id");
			exit;
		} else {
			/* if the query returned a valid set, but no matches */
			$title = 'Edit';
			initHtml($title, null, null);
			/* Add the standard head section to all the HTML output */
			echoHead(false, $title);
			echo '<div class="popContainer" style="width:770px;">No matches.
				<a href = "javascript: window.close(); " class="button smallButton"><div>Cancel</div> </a> ';
		}
	} /* if the query returned a invalid record set or was null */
	else {
		echo '<div class="popContainer">
			      <div class="innerContainer7">Invalid set or Null.</div>
			  </div>';
	}
} else {
	include_once('mainEdit.php');
	/* Begin HTML */
	$title = 'Edit';
	initHtml($title, null, null);

	/* Add the standard head section to all the HTML output. */
	echoHead(false, $title);
	echo '<div class="popContainer" style="width:770px;">';
	mainEdit();
}
echo '</div>';

/* Finish with end of HTML */
finishHtml();
?>
