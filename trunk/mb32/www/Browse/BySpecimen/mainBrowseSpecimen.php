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

include_once('thumbs.inc.php');
include_once('imageFunctions.php');
include_once( 'resultControls.class.php');

// Menu link from mbMenu_data.php
$browseByImageHref = $config->domain . 'Browse/ByImage/';
$browseBySpecimenHref = $config->domain . 'Browse/BySpecimen/';
$specimenSql = NULL;

function mainBrowseSpecimen($title) {
	initGetVariables();
	global $specimenSql;
	if (isset($_GET['pop'])){
		echo '<div class="popContainer" style="width:820px">';
	} else {
		echo '<div class="mainGenericContainer" style="width:820px">';
	}
	echo'
			<h1 align="center">' .$title. '</h1> <p></p>
			<table class="manageBrowseTable" width="100%" cellspacing="0">
			<tr>
				<td id="rightBorder" width="150px" height="500px" valign="top">
				<div class="browseFieldsContainer">';
	makeFilterForm();
	echo '		</div>
				</td>
				<td valign="top">';
	//echo $specimenSql;
	showListOfSpecimen();
	echo '		</td>
			</tr>
		</table>
		</div>'; //mainGenericContainer
}

function initGetVariables() {
	global $config;
	
	if ($_GET['offset'] == NULL) {
		$_GET['offset'] = 0;
	}

	if ($_GET['resetOffset'] =='on') {
		$_GET['offset'] = 0;
		$_GET['goTo'] = "";
		$_GET['resetOffset'] = 'off';
	}
	if ($_GET['spKeywords']) {
		$_GET['spKeywords'] = trim(ereg_replace('\]|[\[/\^\@\#!\$\+\?`=\|\{\}%&\*\(\)<>~\]', '', $_GET['spKeywords']));
	}
	
	if (!isset($_GET['goTo']) || !is_numeric($_GET['goTo'])) $_GET['goTo'] = "";
	if (is_numeric($_GET['goTo'])) $_GET['goTo'] = round((int)$_GET['goTo']);
	
	if (!isset($_GET['numPerPage'])) {
		$_GET['numPerPage'] = $config->displayThumbsPerPage;
	}
}

function makeFilterForm( ) {
	$resultControls = new resultControls;
	$resultControls->displayForm();
	global $objInfo, $specimenSql, $specimenCountSql;
	$specimenSql = $resultControls->createSQL( $objInfo);
	$specimenCountSql = $resultControls->createCountSQL( $objInfo);
}

function countTotalSpecimenRecords() {
	global $link, $objInfo; // session variable
	$sql = 'SELECT count(*) as total FROM BaseObject ';
	$sql .= 'WHERE objectTypeId = \'Specimen\'';
	$row = mysqli_fetch_array (mysqli_query($link, $sql));
	$total = $row['total'];
	return $total;
}

function countSpecimenRecords() {
	global $link, $objInfo, $specimenCountSql; // session variable
	$row = mysqli_fetch_array (mysqli_query($link, $specimenCountSql));
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

function showListOfSpecimen() {
	global $link, $specimenSql;
	if (is_null($specimenSql)) {
		printEmptySet();
		return;
	}
	
	$newOffset =  $_GET['offset'];
	$numRows = $_GET['numPerPage'];
	$numMatches = countSpecimenRecords();
	if ($_GET['goTo'] != "") {
		$page = is_numeric($_GET['goTo']) ? $_GET['goTo'] : 1;
		$newOffset = ($page - 1) * $numRows;
		$_GET['offset'] = $newOffset;
	}
	
	$compareOffset = $numMatches - $newOffset;
	if($compareOffset < $numRows) $numRows = $compareOffset;
	$limitClause = "  limit $newOffset, $numRows ";
	//echo "<br/>Specimen SQL: $specimenSql $limitClause <br/>";

	$result = mysqli_query($link, $specimenSql.$limitClause) or die(mysqli_error($link));
	//echo $specimenSql;
	if ($result) {
		$total = mysqli_num_rows($result);
		if ($total > 0) {
			for ($i = 0; $i < $total; $i++){
				$array[$i] = mysqli_fetch_array($result);
			}
			global $browseByLocationHref;
			outputArraySpecimens($array, $numMatches, $browseByLocationHref);
		}
		mysqli_free_result($result);
	} else {
		echo '<div class="error"><br/>Error, please contact the administration group<br/><br/>'.$sql."<br/><br/>".mysqli_error($link).'</div> ';
	}
}

function printPagesTool() {
	global $config;
	// How many to show
	echoJSPagesTool();
	echo '<form action="#"  name="operationForm"><p>Show:
			<select name="numPerPage" onchange="changeNumPerPage(this.form);return false;" >';
	for ($i=1; $i<= $config->displayThumbsPerPageSelect; $i++) {
		$value = $i*10;
		echo '<option value="'.$value.'" ';
		if ($value == $_GET['numPerPage']) echo 'selected="selected"';
		echo '>'.$value.'</option>';
	}
	echo '</select>
			hits per page';
	// Page
	echo ' &nbsp;&nbsp; Page:
			<input align="top" name="goTo" size="5" type="text" value="'.$_GET['goTo'].'" />
			<a href="javascript: gotoPageOnClick();" class="button smallButton"><div>Go</div></a>
	</p></form >';
}

function outputArraySpecimens ( $array, $total, $specimenList) {
	global  $link, $config;
	global $sortByFields, $browseThumbList, $browseByImageHref, $objInfo;
	include_once( 'objOptions.inc.php');
	include_once( 'tsnFunctions.php');
	// Number of thumbs to display.
	$sizeOfArray = count($array);
	// Set the value of offset to that passed in the URL, else 0.
	if(isset($_GET['offset'])) {
		$offset = $_GET['offset'];
	} else {
		$offset = 0;
	}
	if(isset($_GET['numPerPage'])) {
		$numPerPage = $_GET['numPerPage'];
	}
	// List of pages
	echo '<div class="imagethumbspageHeader">';
	printPagesTool();
	printLinks($total, $numPerPage, $offset, $browseBySpecimenHref);
	echo' &nbsp; ('.$total.' specimens)</div><br />';

	// Update myObjOptions with the right values
	$myObjOptions = $objOptions;
	$myObjOptions['Annotate'] = FALSE;
	if ($_GET['pop']) {
		$myObjOptions['Info'] = FALSE;
		$myObjOptions['Select'] = TRUE;
		$myObjOptions['Edit'] = FALSE;
		$myObjOptions['Annotate'] = FALSE;
	}
	// Loop
	//===========================
	$color[0] = $config->lightListingColor;
	$color[1] = $config->darkListingColor;
	$i=0;
	echo '<div class="imagethumbspage">';
	$userId = $objInfo->getUserId() ? $objInfo->getUserId() : 0;
	$groupId = $objInfo->getUserGroupId() ? $objInfo->getUserGroupId() : 0;
	//	echo $userId;
	//	echo $groupId;
	$opener = isset($_GET['pop']) ? "&amp;opener=true":"&amp;opener=false";
	//echo $userId .'<br />';
	//echo $groupId.'<br />';
	for ($i=0; $i < $sizeOfArray; $i++) {
		$specimenId = $array[$i][$sortByFields[0]['field']];
		$colorIndex = $i%2;
		$showCameraHtml = "";
		if (!$_GET['pop'] && $array[$i][$sortByFields[10]['field']]) // not pop and imagesCount > 0
		$showCameraHtml = '<a href="'.$browseByImageHref.'?specimenId='.$specimenId.'">
					<img border="0" src="/style/webImages/camera-min16x12.gif" alt="images" title="List of images" /></a>';

		$tsnName = getTsnName( $array[$i][$sortByFields[9]['field']]); // return tsnName['name'] and tsnName['tsn']
		$thumbUrl = $array[$i][$sortByFields[13]['field']];//thumbUrl
		// if there is no standard image id for a specimen, take the first image associated with that specimen
		if (intval($thumbUrl) > 0) {
			$imageId = intval($thumbUrl);
			$imgUrl = getImageUrl($imageId, 'thumbs');
		} else if (intval($imageId)==0){ // not an integer
			$imgUrl = $thumbUrl;
			$imageId = null;
		} else { // no thumbUrl at all
			$imageId = null;
			$imgUrl = '/style/webImages/' . $config->notFoundImg;
		}

		echo'
		<div id="row'.($i+1).'" class="imagethumbnail" style="background-color:'.$color[$colorIndex].';">
				<table>
					<tr>
						<td class="greenBottomBorder">
							<span class="idField" title="' .$sortByFields[0]['label']. '"> Specimen ['.$specimenId.']</span> 
							&nbsp;'.printTsnNameNoLinks( $tsnName).'
						</td>
						<td class="browseRight greenBottomBorder">'.printOptions($myObjOptions, $specimenId, 'Specimen', $tsnName['name']).'</td>
						<td rowspan="5" class="browseRight browseImageCell">';
		echo thumbnailTag($specimenId, $imgUrl);
		echo '</td>
					</tr>
					<tr>
						<td>'.$sortByFields[1]['label'].' / '.$sortByFields[5]['label'].' : '.$array[$i][$sortByFields[1]['field']].' / '.$array[$i][$sortByFields[5]['field']].'</td>
						<td class="browseRight"><div style="white-space:nowrap;">'.$sortByFields[10]['label'].': '.$array[$i][$sortByFields[10]['field']].'</div></td>
					</tr>
					<tr>
						<td>'.$sortByFields[4]['label'].' / ' .$sortByFields[2]['label']. ' / ' .$sortByFields[3]['label']
		.': '.$array[$i][$sortByFields[4]['field']]
		.' / '.$array[$i][$sortByFields[2]['field']]
		.' / '.$array[$i][$sortByFields[3]['field']].'
						</td>						
						<td class="browseRight">&nbsp;</td>
					</tr>
					<tr>
						<td>'.$sortByFields[7]['label'].' / '.$sortByFields[11]['label'].' / '.$sortByFields[12]['label']
		.': '.$array[$i][$sortByFields[7]['field']]
		.' / '.$array[$i][$sortByFields[11]['field']]
		.' / '.$array[$i][$sortByFields[12]['field']].'
						</td>
						<td class="browseRight">&nbsp;</td>
					</tr>
					<tr>
						<td>'.$sortByFields[6]['label'].': '.htmlentities($array[$i][$sortByFields[6]['field']], ENT_QUOTES, "UTF-8").'</td>
						<td>&nbsp;</td>
					</tr>
				</table>
			</div>';
	}
	echo '</div>';
	// List of pages again
	echo '<br/><div class="imagethumbspageHeader">';
	printLinks($total, $numPerPage, $offset, $browseBySpecimenHref);
	echo' &nbsp;('.$total.' specimens)</div><br/>';
}
?>
