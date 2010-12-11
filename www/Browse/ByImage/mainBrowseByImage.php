#-------------------------------------------------------------------------------
# Copyright (c) 2010 Greg Riccardi, Fredrik Ronquist.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Public License v2.0
# which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# 
# Contributors:
#   Fredrik Ronquist - conceptual modeling and interaction design
#   Austin Mast - conceptual modeling and interaction design
#   Greg Riccardi - initial API and implementation
#   Wilfredo Blanco - initial API and implementation
#   Robert Bruhn - initial API and implementation
#   Christopher Cprek - initial API and implementation
#   David Gaitros - initial API and implementation
#   Neelima Jammigumpula - initial API and implementation
#   Karolina Maneva-Jakimoska - initial API and implementation
#   Katja Seltmann - initial API and implementation
#   Stephen Winner - initial API and implementation
#-------------------------------------------------------------------------------
<?php

include('mbMenu_data.php');
include_once('resultControls.class.php');

include_once('thumbs.inc.php');
include_once('imageFunctions.php');
include_once('listImageThumbs.inc.php');


// Menu links from mbMenu_data.php
$browseByImage = '/Browse/ByImage/';
$browseByTaxonHref = $mainMenuOptions[6]['href'];
$browseByNameHref = $mainMenuOptions[7]['href'];
$browseByViewHref = $mainMenuOptions[8]['href'];
$browseBySpecimenHref = $mainMenuOptions[14]['href'];
$browseByLocationHref = $mainMenuOptions[15]['href'];
$imageSql = null;
$countImageSql = null;

// simply echos the following contents to the web browser.
// This helps keep the main scripts simpler to read.
function mainBrowseByImage($title) {
	global $imageSql, $countImageSql;

	initGETVariables();

	echo '  <div class="mainGenericContainer" style="width:820px">
      <h1 align="center">' . $title . '</h1> <p></p>
  
      <table class="manageBrowseTable" width="100%" cellspacing="0">
      <tr>
        <td id="rightBorder" width="210px" height="500px" valign="top">
        <div class="browseFieldsContainer">';
	makeFilterForm();
	echo '    </div>
        </td>
        <td valign="top">';
	showListOfImages();
	//mainGenericContainer
	echo '</td></tr></table></div>';
}

function initGetVariables() {
	global $config;

	if (!$_GET['activeSubmit'])	$_GET['activeSubmit'] = 2;

	if ($_GET['activeSubmit'] == 1) {
		// Submiting the first form
		$_GET['tsnKeywords'] = '';
		$_GET['viewKeywords'] = '';
		$_GET['spKeywords'] = '';
		$_GET['localityKeywords'] = '';
	}

	if ($_GET['activeSubmit'] == 2) {
		// Submiting the second form
		$_GET['keywords'] = '';
	}

	// This is to remove any punctuation like slashes (except double quotes) so they will show back up in the form fields
	if ($_GET['keywords'])
	//$_GET['keywords'] = trim(ereg_replace('[^A-Za-z0-9"[:space:]]', '', $_GET['keywords']));
	$_GET['keywords'] = trim(ereg_replace('\]|[\[/\^\@\#!\$\+\?`=\|\{\}%&\*\(\)<>~\]', '', $_GET['keywords']));

	if ($_GET['tsnKeywords'])
	$_GET['tsnKeywords'] = trim(ereg_replace('\]|[\[/\^\@\#!\$\+\?`=\|\{\}%&\*\(\)<>~\]', '', $_GET['tsnKeywords']));

	if ($_GET['spKeywords'])
	$_GET['spKeywords'] = trim(ereg_replace('\]|[\[/\^\@\#!\$\+\?`=\|\{\}%&\*\(\)<>~\]', '', $_GET['spKeywords']));

	if ($_GET['viewKeywords'])
	$_GET['viewKeywords'] = trim(ereg_replace('\]|[\[/\^\@\#!\$\+\?`=\|\{\}%&\*\(\)<>~\]', '', $_GET['viewKeywords']));

	if ($_GET['localityKeywords'])
	$_GET['localityKeywords'] = trim(ereg_replace('\]|[\[/\^\@\#!\$\+\?`=\|\{\}%&\*\(\)<>~\]', '', $_GET['localityKeywords']));

	// Taxon get variables initialization ===============
	if (isset($_GET['tsn'])) {
		// coming from Browse Tree/Taxon names
		$_GET['tsnId_Kw'] = 'id';
		$_GET['tsnKeywords'] = $_GET['tsn'];
	}
	if (!isset($_GET['tsnId_Kw']))
	$_GET['tsnId_Kw'] = 'keywords';
	//===================================================

	// View get variables initialization ===============
	if (isset($_GET['viewId'])) {
		// Coming from Browse by View
		$_GET['viewId_Kw'] = 'id';
		$_GET['viewKeywords'] = $_GET['viewId'];
	}
	if (!isset($_GET['viewId_Kw']))
	$_GET['viewId_Kw'] = 'keywords';
	//===================================================

	// Specimen get variables initialization ============
	if (isset($_GET['specimenId'])) {
		// Coming from Browse by Specimen
		$_GET['spId_Kw'] = 'id';
		$_GET['spKeywords'] = $_GET['specimenId'];
	}
	if (!isset($_GET['spId_Kw']))
	$_GET['spId_Kw'] = 'keywords';
	//===================================================

	// Locality get variables initialization ============
	if (isset($_GET['localityId'])) {
		// Coming from Browse by Specimen
		$_GET['localityId_Kw'] = 'id';
		$_GET['localityKeywords'] = $_GET['localityId'];
	}
	if (!isset($_GET['localityId_Kw']))
	$_GET['localityId_Kw'] = 'keywords';
	//===================================================

	if (!isset($_GET['offset'])) $_GET['offset'] = 0;

	if ($_GET['resetOffset'] == 'on') {
		$_GET['offset'] = 0;
		$_GET['goTo'] = "";
		$_GET['resetOffset'] = 'off';
	}

	if (!isset($_GET['goTo']) || !is_numeric($_GET['goTo'])) $_GET['goTo'] = "";
	if (is_numeric($_GET['goTo'])) $_GET['goTo'] = round((int)$_GET['goTo']);
	if (!isset($_GET['numPerPage'])) $_GET['numPerPage'] = $config->displayThumbsPerPage;
}

function makeFilterForm() {
	global $objInfo, $imageSql, $countImageSql;

	$resultControls = new resultControls;
	$resultControls->displayForm();
	$imageSql = $resultControls->createSQL($objInfo);
	$countImageSql = $resultControls->createCountSQL( $objInfo);
}

function countTotalImageRecords() {
	global $link, $objInfo; // session variable
	$sql = 'SELECT count(*) as total FROM BaseObject ';
	$sql .= 'WHERE objectTypeId = \'Image\'';
	$row = mysqli_fetch_array (mysqli_query($link, $sql));
	$total = $row['total'];
	return $total;
}

function countImageRecords() {
	global $link, $objInfo, $countImageSql; // session variable
	$row = mysqli_fetch_array (mysqli_query($link, $countImageSql));
	$total = $row[0];
	return $total;
}

function showListOfImages() {
	global $link, $imageSql;

	if (is_null($imageSql)) {
		printEmptySet();
		return;
	}
	
	$newOffset =  $_GET['offset'];
	$numRows = $_GET['numPerPage'];
	$numMatches = countImageRecords();
	if ($_GET['goTo'] != "") {
		$page = is_numeric($_GET['goTo']) ? $_GET['goTo'] : 1;
		$newOffset = ($page - 1) * $numRows;
		$_GET['offset'] = $newOffset;
	}

	$compareOffset = $numMatches - $newOffset;
	if($compareOffset < $numRows) $numRows = $compareOffset;
	$limitClause = "  limit $newOffset, $numRows ";
	$result = mysqli_query($link, $imageSql.$limitClause) or die(mysqli_error($link));

	if ($result) {
		$total = mysqli_num_rows($result);
		if ($total > 0) {
			for ($i = 0; $i < $numRows; $i++) {
				$array[$i] = mysqli_fetch_array($result);
			}
			global $browseByImage;
			outputArrayImages($array, $numMatches, $browseByImage);
		} else {
			echo '<div class="error"><br />&nbsp;Images cannot be viewed at this time. This may be due to the images publication date.</div> ';
		}
		mysqli_free_result($result);
	} else {
		echo '<div class="error"><br/>Error, please contact the administration group<br/><br/>' . $sql . "<br/><br/>" . mysqli_error($link) . '</div> ';
	}
}
?>
