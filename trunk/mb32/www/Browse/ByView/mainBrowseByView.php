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
/**
 * File name: mainBrowseView.php
 * Lists Views.
 *
 * @author Wilfredo Blanco <blanco@scs.fsu.edu>
 * @package Morphbank2
 * @subpackage Browse
 *
 */


include_once('thumbs.inc.php');
include_once('imageFunctions.php');
include_once('tsnFunctions.php');
include_once('resultControls.class.php');

$browseByImageHref = $config->domain . 'Browse/ByImage/';
$browseByViewHref = $config->domain . 'Browse/ByView/';
$viewSql = null;

function mainBrowseByView($title) {
	initGetVariables();
	global $viewSql;
	if (isset($_GET['pop'])) {
		echo '<div class="popContainer" style="width:820px">';
	} else {
		echo '<div class="mainGenericContainer" style="width:820px">';
	}

	echo '
      <h1 align="center">' . $title . '</h1> <p></p>
      <table class="manageBrowseTable" width="100%" cellspacing="0">
      <tr>
        <td id="rightBorder" width="150px" height="500px" valign="top">
        <div class="browseFieldsContainer">';
	makeFilterForm();
	echo '    </div>
        	</td>
        	<td valign="top">';
	//echo $viewSql;
	showListOfViews();
	echo '    </td>
      </tr>
      </table>
    </div>'; //mainGenericContainer
}

function initGetVariables() {
	global $config;
	if (!isset($_GET['offset'])) {
		$_GET['offset'] = 0;
	}

	if ($_GET['resetOffset'] == 'on') {
		$_GET['offset'] = 0;
		$_GET['goTo'] = "";
		$_GET['resetOffset'] = 'off';
	}
	if ($_GET['viewKeywords']) {
		$_GET['viewKeywords'] = trim(ereg_replace('\]|[\[/\^\@\#!\$\+\?`=\|\{\}%&\*\(\)<>~\]', '', $_GET['viewKeywords']));
	}

	if (!isset($_GET['goTo']) || !is_numeric($_GET['goTo'])) $_GET['goTo'] = "";
	if (is_numeric($_GET['goTo'])) $_GET['goTo'] = round((int)$_GET['goTo']);

	if (!isset($_GET['numPerPage'])) {
		$_GET['numPerPage'] = $config->displayThumbsPerPage;
	}
}

function makeFilterForm() {
	$resultControls = new resultControls;
	$resultControls->displayForm();
	global $objInfo, $viewSql, $viewCountSql;
	$viewSql = $resultControls->createSQL($objInfo);
	$viewCountSql = $resultControls->createCountSQL( $objInfo);
}

function countTotalViewRecords() {
	global $link, $objInfo; // session variable
	$sql = 'SELECT count(*) as total FROM BaseObject ';
	$sql .= 'WHERE objectTypeId = \'View\'';
	$row = mysqli_fetch_array (mysqli_query($link, $sql));
	$total = $row['total'];
	return $total;
}

function countViewRecords() {
	global $link, $objInfo, $viewCountSql; // session variable
	$row = mysqli_fetch_array (mysqli_query($link, $viewCountSql));
	$total = $row[0];
	return $total;
}

function echoJSPagesTool() {
	echo '<script language="JavaScript" type="text/javascript">
    <!--
      function changeNumPerPage(form) {
        if (document.getElementById){
            numPerPage = document.getElementById("numPerPage");
          numPerPage.value = form.numPerPage.value;
          
          goTo = document.getElementById("goTo");
          goTo.value = "";
          
          resetOffset = document.getElementById("resetOffset");
          resetOffset.value = "on";
          
          document.resultControlForm.submit();
        }
      }
     // -->
    </script>';
}

function showListOfViews() {
	global $link, $viewSql;
	if (is_null($viewSql)) {
		printEmptySet();
		return;
	}


	$newOffset =  $_GET['offset'];
	$numRows = $_GET['numPerPage'];
	$numMatches = countViewRecords();
	if ($_GET['goTo'] != "") {
		$page = is_numeric($_GET['goTo']) ? $_GET['goTo'] : 1;
		$newOffset = ($page - 1) * $numRows;
		$_GET['offset'] = $newOffset;
	}

	$compareOffset = $numMatches - $newOffset;
	if($compareOffset < $numRows) $numRows = $compareOffset;
	$limitClause = "  limit $newOffset, $numRows ";
	//echo "<br/>View SQL: $viewSql $limitClause <br/>";

	$result = mysqli_query($link, $viewSql.$limitClause) or die(mysqli_error($link));
	if ($result) {
		$total = mysqli_num_rows($result);
		if ($total > 0) {
			for ($i = 0; $i < $total; $i++){
				$array[$i] = mysqli_fetch_array($result);
			}
			global $browseByLocationHref;
			outputArrayViews($array, $numMatches, $browseByLocationHref);
		}
		mysqli_free_result($result);
	} else {
		echo '<div class="error"><br/>Error, please contact the administration group<br/><br/>' . $sql . "<br/><br/>" . mysqli_error($link) . '</div> ';
	}
}

function printPagesTool() {
	global $config;

	// How many to show
	echoJSPagesTool();
	echo '<form action="#" name="operationForm" ><p>Show:
      <select name="numPerPage" onchange="changeNumPerPage(this.form);return false;" >';
	for ($i = 1; $i <= $config->displayThumbsPerPageSelect; $i++) {
		$value = $i * 10;
		echo '<option value="' . $value . '" ';
		if ($value == $_GET['numPerPage']) echo 'selected="selected"';
		echo '>' . $value . '</option>';
	}
	echo '</select>
      hits per page';
	// Page
	echo ' &nbsp;&nbsp; Page:
      <input align="top" name="goTo" size="5" type="text" value="' . $_GET['goTo'] . '" />
      <a href="javascript: gotoPageOnClick();" class="button smallButton"><div>Go</div></a>
  </p></form >';
}

function outputArrayViews($array, $total, $browseByLocationHref) {
	global $config;
	//declared in this module
	global $sortByFields, $browseByTaxonHref, $browseByImageHref, $browseByNameHref, $objInfo, $link;

	include_once('objOptions.inc.php');

	//Number of views to display
	$sizeOfArray = count($array);
	// Set the value of offset to that passed in the URL, else 0.
	if (isset($_GET['offset'])) {
		$offset = $_GET['offset'];
	} else {
		$offset = 0;
	}

	if (isset($_GET['numPerPage'])) {
		$numPerPage = $_GET['numPerPage'];
	}

	echo '<div class="imagethumbspageHeader">';
	printPagesTool();
	printLinks($total, $numPerPage, $offset, $browseByCollection);
	echo ' &nbsp; (' . $total . ' views)</div><br />';

	// Update myObjOptions with the right values
	$myObjOptions = $objOptions;
	$myObjOptions['Annotate'] = false;
	if ($_GET['pop']) {
		$myObjOptions['Info'] = false;
		$myObjOptions['Select'] = true;
		$myObjOptions['Edit'] = false;
		$myObjOptions['Annotate'] = false;
	}
	// Loop
	//===========================
	$color[0] = $config->lightListingColor;
	$color[1] = $config->darkListingColor;

	$userId = $objInfo->getUserId() ? $objInfo->getUserId() : 0;
	$groupId = $objInfo->getUserGroupId() ? $objInfo->getUserGroupId() : 0;
	$opener = isset($_GET['pop']) ? "&amp;opener=true" : "&amp;opener=false";
	$i = 0;
	echo '<div class="imagethumbspage">';
	for ($i = 0; $i < $sizeOfArray; $i++) {
		$viewId = $array[$i][$sortByFields[0]['field']];
		$imageId = $array[$i][$sortByFields[11]['field']];
		$showCameraHtml = "";
		if (!$_GET['pop'] && $array[$i][$sortByFields[12]['field']]){
			//show camera if no pop and imagesCount > 0
			$showCameraHtml = '<a href="' . $browseByImageHref . '?viewId=' . $viewId . '">
          <img border="0" src="/style/webImages/camera-min16x12.gif" title="List of images" alt="images" /></a>';
		}
		$colorIndex = $i % 2;
		$tsnName = getTsnName($array[$i]['viewTSN']);
		if ($imageId == 0 || $imageId == null) {
			// no standard image
			$sql = "SELECT id FROM Image WHERE viewId = $viewId ORDER BY id ASC LIMIT 0,1 ";
			$result = mysqli_query($link, $sql) or die(mysqli_error($link) . $sql);
			if ($result) {
				$idResult = mysqli_fetch_array($result);
				$imageId = $idResult['id'];
				$array[$i][$sortByFields[11]['field']] = $imageId;
			} else {
				// choose default image
			}
		}
		$imgArray = getImageUrl($imageId, 'thumbs');

		echo '
    <div id="row' . ($i + 1) . '" class="imagethumbnail" style="background-color:' . $color[$colorIndex] . ';">
        <table>
          <tr>
            <td class="greenBottomBorder">
              <span title="View id">View [' . $viewId . ']</span>
              &nbsp; ' . $array[$i][$sortByFields[2]['field']] . '/' . $array[$i][$sortByFields[3]['field']] . '/' . $array[$i][$sortByFields[4]['field']] . '
            </td>
            <td class="browseRight greenBottomBorder">' . printOptions($myObjOptions, $viewId, 'View', $array[$i][$sortByFields[2]['field']] . '/' . $array[$i][$sortByFields[3]['field']] . '/' . $array[$i][$sortByFields[4]['field']]) . ' 
            </td>
            <td rowspan="4" class="browseRight browseImageCell">';
		echo thumbnailTag($viewId, $imgUrl);
		echo ' </td>
          </tr>
          <tr>
            <td>Stage/Form: ' . $array[$i][$sortByFields[5]['field']] . '/' . $array[$i][$sortByFields[6]['field']] . '</td>
            <td class="browseRight"><div style="white-space:nowrap;">' . $sortByFields[12]['label'] . ': ' . $array[$i][$sortByFields[12]['field']] . $showCameraHtml . '</div></td>
          </tr>
          <tr>
            <td>Technique: ' . $array[$i][$sortByFields[7]['field']] . '/' . $array[$i][$sortByFields[8]['field']] . '</td>
            <td class="browseRight">&nbsp;</td>
          </tr>
          <tr>
            <td>' . $sortByFields[10]['label'] . ': ' . printTsnNameLinks($tsnName) . '</td>
            <td class="browseRight">&nbsp;</td>
          </tr>
          
        </table>
      </div>  ';
	}
	echo '</div>';
	echo '<div class="imagethumbspageHeader"><br/>';
	printLinks($total, $numPerPage, $offset, $browseByCollection);
	echo ' &nbsp; (' . $total . ' views)</div><br />';
}
?>
