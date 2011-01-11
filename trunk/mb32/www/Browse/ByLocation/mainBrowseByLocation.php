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
include_once('resultControls.class.php');
include_once('objOptions.inc.php');


// Menu link from mbMenu_data.php
$browseByImageHref = '/Browse/ByImage/';
$browseByLocationHref = '/Browse/ByLocation/';
$localitySql = null;

function mainBrowseByLocation($title)
{
	initGetVariables();
	global $localitySql;

	if (isset($_GET['pop']))
	echo '<div class="popContainer" style="width:760px">';
	else
	echo '<div class="mainGenericContainer" style="width:760px">';

	echo '
      <h1 align="center">' . $title . '</h1> <p></p>
      
      <table class="manageBrowseTable" width="100%" cellspacing="0">
      <tr>
        <td id="rightBorder" width="150px" valign="top">
        <div class="browseFieldsContainer">';
	makeFilterForm();
	echo '    </div>
        </td>
        <td valign="top">';
	//echo $localitySql;
	showListOfLocalities();
	echo '    </td>
      </tr>
      </table>
    </div>';
}

function initGetVariables()
{
	global $config;

	if (!isset($_GET['submit2']))
	$_GET['submit2'] = '2';

	if ($_GET['offset'] == null)
	$_GET['offset'] = 0;

	if ($_GET['resetOffset'] == 'on') {
		$_GET['offset'] = 0;
		$_GET['goTo'] = "";
		$_GET['resetOffset'] = 'off';
	}
	if ($_GET['localityKeywords'])
	$_GET['localityKeywords'] = trim(ereg_replace('\]|[\[/\^\@\#!\$\+\?`=\|\{\}%&\*\(\)<>~\]', '', $_GET['localityKeywords']));

	if (!isset($_GET['goTo']) || !is_numeric($_GET['goTo'])) $_GET['goTo'] = "";
	if (is_numeric($_GET['goTo'])) $_GET['goTo'] = round((int)$_GET['goTo']);

	if ($_GET['numPerPage'] == null)
	$_GET['numPerPage'] = $config->displayThumbsPerPage;
}

function makeFilterForm()
{
	$resultControls = new resultControls;
	$resultControls->displayForm();

	global $objInfo, $localitySql;
	$localitySql = $resultControls->createSQL($objInfo);
}

function echoJSPagesTool()
{
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

function showListOfLocalities()
{
	global $link, $localitySql;

	if (is_null($localitySql)) {
		printEmptySet();
		return;
	}

	//echo $localitySql;
	$result = mysqli_query($link, $localitySql);
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
			global $browseByLocationHref;
			outputArrayLocalities($array, $total, $browseByLocationHref);
		}
		mysqli_free_result($result);
	} else {
		echo '<div class="error"><br/>Error, please contact the administration group<br/><br/>' . $sql . "<br/><br/>" . mysqli_error($link) . '</div> ';
	}
}

function printPagesTool()
{
	global $config;

	// How many to show
	echoJSPagesTool();
	echo '<form action="#" name="operationForm" ><p>Show:
      <select name="numPerPage" onchange="changeNumPerPage(this.form);return false;" >';
	for ($i = 1; $i <= $config->displayThumbsPerPageSelect; $i++) {
		$value = $i * 10;
		echo '<option value="' . $value . '" ';
		if ($value == $_GET['numPerPage'])
		echo 'selected="selected"';
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


function outputArrayLocalities($array, $total, $browseByLocationHref)
{
	global $config;
	global $sortByFields, $browseThumbList, $browseByImageHref;

	//Number of localities to display
	$sizeOfArray = count($array);

	// Set the value of offset to that passed in the URL, else 0.
	if (isset($_GET['offset']))
	$offset = $_GET['offset'];
	else
	$offset = 0;

	if (isset($_GET['numPerPage']))
	$numParPage = $_GET['numPerPage'];
	else
	$numParPage = $numPerPage;

	echo '<div class="imagethumbspageHeader">';
	printPagesTool();
	printLinks($total, $numParPage, $offset, $browseByLocationHref);
	echo ' &nbsp; (' . $total . ' localities)</div><br />';

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
	echo '<div class="imagethumbspage">';
	for ($i = 0; $i < $sizeOfArray; $i++) {
		$colorIndex = $i % 2;
		$showCameraHtml = "";
		if (!$_GET['pop'] && $array[$i][$sortByFields[11]['field']])
		// no pop and imagesCount > 0
		$showCameraHtml = '<a href="' . $browseByImageHref . '?localityId=' . $array[$i][$sortByFields[0]['field']] . '">
          <img border="0" src="/style/webImages/camera-min16x12.gif" title="List of images" alt="link"/></a>';




		echo '
    <div id="row' . ($i + 1) . '" class="imagethumbnail" style="background-color:' . $color[$colorIndex] . ';">
        <table>
          <tr>
            <td class="greenBottomBorder">
              <span  title="Location Id"> Locality [' . $array[$i][$sortByFields[0]['field']] . ']</span> 
               &nbsp;' . $array[$i][$sortByFields[1]['field']] . ' / ' . $array[$i][$sortByFields[2]['field']] . '
            </td>
            <td class="browseRight greenBottomBorder">' . printOptions($myObjOptions, $array[$i][$sortByFields[0]['field']], 'Location', $array[$i][$sortByFields[3]['field']]) . '</td>
          </tr>
          <tr>
            <td>' . $sortByFields[3]['label'] . ': ' . $array[$i][$sortByFields[3]['field']] . '</td>
            <td class="browseRight">' . $sortByFields[11]['label'] . ': ' . $array[$i][$sortByFields[11]['field']] . $showCameraHtml . '</td>
          </tr>
          <tr>
            <td>' . $sortByFields[4]['label'] . '/' . $sortByFields[5]['label'] . ': ' . $array[$i][$sortByFields[4]['field']] . ' / ' . $array[$i][$sortByFields[5]['field']] . '</td>
            <td class="browseRight">&nbsp;</td>
          </tr>
          <tr>
            <td>Elevation (m) : ' . (($array[$i][$sortByFields[7]['field']] == $array[$i][$sortByFields[8]['field']]) ? $array[$i][$sortByFields[7]['field']] : $array[$i][$sortByFields[7]['field']] . ' - ' . $array[$i][$sortByFields[8]['field']]) . '</td>
            <td class="browseRight">&nbsp;</td>
          </tr>
          
        </table>
      </div>';
	}
	echo '</div>';
	echo '<div class="imagethumbspageHeader"><br/>';
	printLinks($total, $numParPage, $offset, $browseByCollection);
	echo ' &nbsp; (' . $total . ' localities)</div><br />';
}
?>
