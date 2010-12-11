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
 * File name: mainBrowseByCollection.php
 *
 *
 * @author Wilfredo Blanco <blanco@scs.fsu.edu>
 * @package Morphbank2
 * @subpackage Browse
 *
 */


include_once('thumbs.inc.php');
include_once('urlFunctions.inc.php');
include_once('resultControls.class.php');

// Menu link from mbMenu_data.php
$browseByImageHref = $config->domain . 'Browse/ByImage/';
$browseByCollection = $config->domain . 'Browse/ByCollection/';
$collectionHref = $config->domain . 'Show/';
$collectionSql = null;

function mainBrowseByCollection($title) {
	initGetVariables();
	global $collectionSql;

	if (isset($_GET['pop'])) {
		echo '<div class="popContainer" style="width:760px">';
	} else {
		echo '<div class="mainGenericContainer" style="width:760px">';
	}

	echo '<h1 align="center">' . $title . '</h1> <p></p>
      <table class="manageBrowseTable" width="100%" cellspacing="0" border="0">
      <tr><td id="rightBorder" width="150px" valign="top">
      <div class="browseFieldsContainer">';
	makeFilterForm();
	echo '</div></td><td valign="top">';
	//lecho $collectionSql;
	showListOfCollection();
	echo '</td></tr></table></div>';
}

function initGetVariables() {
	global $config;

	if (!isset($_GET['submit2'])) $_GET['submit2'] = '2';
	if ($_GET['offset'] == null) $_GET['offset'] = 0;
	if ($_GET['resetOffset'] == 'on') {
		$_GET['offset'] = 0;
		$_GET['goTo'] = "";
		$_GET['resetOffset'] = 'off';
	}
	if ($_GET['collectionKeywords']){
		$_GET['collectionKeywords'] 
		= trim(ereg_replace('\]|[\[/\^\@\#!\$\+\?`=\|\{\}%&\*\(\)<>~\]', '', $_GET['collectionKeywords']));
	}
	if (!isset($_GET['goTo']) || !is_numeric($_GET['goTo'])) $_GET['goTo'] = "";
	if (is_numeric($_GET['goTo'])) $_GET['goTo'] = round((int)$_GET['goTo']);
	
	if ($_GET['numPerPage'] == null) $_GET['numPerPage'] = $config->displayThumbsPerPage;
}

function makeFilterForm() {
	$resultControls = new resultControls;
	$resultControls->displayForm();

	global $objInfo, $collectionSql;
	$collectionSql = $resultControls->createSQL($objInfo);
}

function echoJSCollection() {
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

function showListOfCollection() {
	global $link, $collectionSql;

	if (is_null($collectionSql)) {
		printEmptySet();
		return;
	}
	//echo $collectionSql;
	$result = mysqli_query($link, $collectionSql);
	if ($result) {
		$total = mysqli_num_rows($result);
		if ($total == 0) {
			printEmptySet();
			return;
		}
		
		if ($_GET['goTo'] != "") {
			//$newOffset =(int)(($_GET['goTo']-1)/$_GET['numPerPage']);
			$newOffset = (int)($_GET['goTo']) ? $_GET['goTo'] - 1 : $_GET['offset'] / $_GET['numPerPage'];
			if ($newOffset * $_GET['numPerPage'] < $total)
			$_GET['offset'] = $newOffset * $_GET['numPerPage'];
		}
		$numRows = ($total - $_GET['offset']) >= $_GET['numPerPage'] ? $_GET['numPerPage'] : $total - $_GET['offset'];

		if ($numRows > 0) {
			mysqli_data_seek($result, $_GET['offset']);
			for ($i = 0; $i < $numRows; $i++)
			$array[$i] = mysqli_fetch_array($result);
			global $browseByCollection;
			outputArrayCollections($array, $total, $browseByCollection);
		}
		mysqli_free_result($result);
	} else {
		echo '<div class="error"><br/>Error, please contact the administration group<br/><br/>' . $collectionSql . "<br/><br/>" . mysqli_error($link) . '</div> ';
	}
}

function printPagesTool() {
	global $config;

	// How many to show
	echoJSCollection();
	echo '<form action="#" name="operationForm"><p>Show:
      <select name="numPerPage" onchange="changeNumPerPage(this.form);return false;" >';
	for ($i = 1; $i <= $config->displayThumbsPerPageSelect; $i++) {
		$value = $i * 10;
		echo '<option value="' . $value . '" ';
		if ($value == $_GET['numPerPage'])
		echo 'selected="selected"';
		echo '>' . $value . '</option>';
	}
	echo '</select>hits per page';
	// Page
	echo ' &nbsp;&nbsp; Page: <input align="top" name="goTo" size="5" type="text" value="' ;
	echo $_GET['goTo'] . '" />';
	echo '<a href="javascript: gotoPageOnClick();" class="button smallButton"><div>Go</div></a>';
	echo '</p></form >';
}

function outputArrayCollections($array, $total, $browseByCollection) {
	global $config;
	global $sortByFields, $browseThumbList, $browseByImageHref, $collectionHref;

	include_once('objOptions.inc.php');
	//Number of collections to display
	$sizeOfArray = count($array);

	// Set the value of offset to that passed in the URL, else 0.
	if (isset($_GET['offset'])) {
		$offset = $_GET['offset'];
	} else {
		$offset = 0;
	}

	if (isset($_GET['numPerPage'])) {
		$numParPage = $_GET['numPerPage'];
	} else {
		$numParPage = $numPerPage;
	}

	echo '<div class="imagethumbspageHeader">';
	printPagesTool();
	printLinks($total, $numParPage, $offset, $browseByCollection);
	echo ' &nbsp; (' . $total . ' collections)</div><br />';

	// Update myObjOptions with the right values
	$myObjOptions = $objOptions;
	global $objInfo;
	if ($_GET['pop']) {
		$myObjOptions['Info'] = true;
		$myObjOptions['Select'] = false;
		$myObjOptions['Edit'] = false;
		$myObjOptions['Annotate'] = false;
		$myObjOptions['Copy'] = false;
	} else {
		$myObjOptions['Annotate'] = false;
		$myObjOptions['Edit'] = false;
		$myObjOptions['Copy'] = true;
	}
	// Loop
	//===========================
	$color[0] = $config->lightListingColor;
	$color[1] = $config->darkListingColor;
	echo '<div class="imagethumbspage">';
	for ($i = 0; $i < $sizeOfArray; $i++) {
		$id = $array[$i][$sortByFields[0]['field']];
		$colorIndex = $i % 2;
		$showCameraHtml = "";
		if (!$_GET['pop'] && $array[$i][$sortByFields[6]['field']])
		// no pop and imagesCount > 0
		$showCameraHtml = showTag($id).getCameraViewTag().'</a>';
		echo '<div id="row' . ($i + 1) . '" class="imagethumbnail" style="background-color:'; 
		echo $color[$colorIndex] . ';"><table><tr><td class="greenBottomBorder">';
		echo '<span  title="Collection Id">Collection [' . $id . ']</span>&nbsp;'; 
		echo htmlentities($array[$i][$sortByFields[5]['field']], ENT_QUOTES, "UTF-8"); 
		echo '</td><td class="browseRight greenBottomBorder">' ;
		echo printOptions($myObjOptions, $id, 'Browse Collection', $array[$i][$sortByFields[5]['field']]); 
		echo '</td></tr><tr><td>' ;
		echo $sortByFields[1]['label'] . ': ' . $array[$i][$sortByFields[1]['field']]; 
		echo '</td><td class="browseRight">' . $sortByFields[6]['label'] . ': '; 
		echo $array[$i][$sortByFields[6]['field']] . $showCameraHtml ;
		echo '</td></tr><tr><td>' . $sortByFields[2]['label'] . ': ' ;
		echo $array[$i][$sortByFields[2]['field']] . '</td><td class="browseRight">&nbsp;</td></tr>';
		echo '</table></div>';
	}
	echo '</div>';
	echo '<div class="imagethumbspageHeader"><br/>';
	printLinks($total, $numParPage, $offset, $browseByCollection);
	echo ' &nbsp; (' . $total . ' collections)</div><br />';
}
?>
