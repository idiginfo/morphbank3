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
// include_once('imageFunctions.php'); use this in the future when sure that everybody is not using thumbs.inc.php

function outputArrayImages($array, $total, $callingPage = "")
{
	// Required for the $numPerPage variable.
	//

	global $config;
	// declared in mbMenu_data.php
	global $mainMenuOptions;
	global $objInfo;
	$browseByTaxonHref = $mainMenuOptions[6]['href'];
	$browseByNameHref = $mainMenuOptions[7]['href'];

	include_once('objOptions.inc.php');
	include_once('tsnFunctions.php');

	// Number of thumbs to display.
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

	echoJSFuntionShow();
	echo '<div class="imagethumbspageHeader">
      <form name="operationForm" action="#">
      <table border="0" cellpadding="0" width="95%">';
	printPagesTool();
	if (($objInfo->getUserId() != null) && !isset($_GET['pop']))
	printOperationTool();
	echo '  </table>
      </form>';

	printLinks($total, $numParPage, $offset, $callingPage);
	echo ' &nbsp; (' . $total . ' images)</div><br />';
	if (!isset($_GET['pop']))
	echo '<form name="imageListForm" method="post" action="processSelection.php">';
	// Update myObjOptions with the right values
	$myObjOptions = $objOptions;

	$myObjOptions['Viewer'] = true;

	if ($_GET['pop']) {
		$myObjOptions['Info'] = true;
		$myObjOptions['Select'] = true;
		$myObjOptions['Edit'] = false;
		$myObjOptions['Annotate'] = false;
	}
	// In the future will change
	//===========================
	$color[0] = $config->lightListingColor;
	$color[1] = $config->darkListingColor;
	echo '<div class="imagethumbspage">';
	for ($i = 0; $i < $sizeOfArray; $i++) {
		//$array[$i] = imageDetailsArrayFromId($array[$i]['imageId']);
		//$array[$i] = $array[$i];
		//<td colspan="4" class="line"></td>
		$colorIndex = $i % 2;
		$row = $array[$i];
		$imageId = $row['imageId'];
		$img = getObjectImageUrl($imageId, 'thumbs');

		$jpg = getObjectImageUrl($imageId, 'jpeg');
		$originalUrl = getOriginalUrl($imageId, $row['imageType']);

		$tsnName = getTsnName($row['tsn']);

		echo '<div id="row' . ($i + 1) . '" class="imagethumbnail" style="background-color:';
		echo $color[$colorIndex] . ';">';
		echo '<table><tr><td class="greenBottomBorder">';
		if ((!isset($_GET['pop'])) && ($objInfo->getUserId() != null)){
			echo '<input id="box' . ($i + 1) . '" type="checkbox" name="object';
			echo ($i + 1) . '" value="' . $imageId . '" onclick="swapColor(\'';
			echo ($i + 1) . '\')"/>&nbsp;';
		}
		echo '<span>Image [' . $imageId . ']</span> &nbsp;' ;
		echo printTsnNameLinks($tsnName);
		echo '</td><td class="browseRight greenBottomBorder">';
		echo printOptions($myObjOptions, $imageId, 'Image');
		echo '</td><td rowspan="4" class="browseRight browseImageCell">';
		echo thumbnailTag($imageId, $img);
		echo '</td></tr>';
		echo '<tr><td>View: ' . $row['specimenPartName'] . '/';
		echo  $row['viewAngleName'] . '</td>';
		echo '<td class="browseRight">Dim: ' . $row['imageWidth'] . 'x';
		echo $row['imageHeight'] . '</td></tr>';
		echo '<tr><td>Specimen: ' . $row['sexName'] . '/' . $row['specimenDevelStageName'];
		echo '/' . $row['specimenFormName'] . '</td>';
		echo '<td class="browseRight">[<a href="' . $jpeg . '">jpg</a>] &nbsp; [<a href="';
		echo $originalUrl . '">Original</a>]</td></tr>';
		echo '<tr><td>Technique: ' . $row['imagingTechniqueName'] . '/';
		echo $row['imagingPreparationTechniqueName'] . '</td>';
		echo '<td class="browseRight">Original: ' . $row['imageType'] . '</td>';
		echo '</tr></table></div>';
	}

	echo '</div>';
	if (!isset($_GET['pop']))
	echo '<input type="hidden" name="collectionId" value="" />
      </form>';

	echo '<div class="imagethumbspageHeader"><br/>';
	printLinks($total, $numParPage, $offset, $callingPage);
	echo ' &nbsp; (' . $total . ' images)</div><br />';
	echo '<span id="checkPrivId" style="display:none;">&nbsp;</span>';
}

function echoJSFuntionShow()
{
	global $config;

	echo '

  <script language="JavaScript" type="text/javascript">
    <!--
      function checkPriv(objectId) {
        Spry.Utils.updateContent("checkPrivId", "/ajax/checkPriv.php?id="+objectId);
        
        if (document.getElementById("checkPrivId").innerHTML == "FALSE")
          alert("You do not have privledge.");
        else {
          window.location = "' . $config->domain . 'Annotation/?id="+objectId;
        }
      }
  
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
            
      var newColorHex = "c8f3d3";
      var blueHex = "e5e5f5";
      var whiteHex = "ffffff";
      
      var newColor = HexToDec(newColorHex);  
      var blueColor = HexToDec(blueHex);
      var whiteColor = HexToDec(whiteHex);
      
      function swapColor(id) {
        /* "id" is the id of the div to change color.  "checkBoxName" is the name of the checkbox */
        
        //var idName = "changeMe"+id;
        var idName = "row"+id;
        var checkBoxId = "box"+id;
        
        var chkBox = document.getElementById(checkBoxId);
        var divToChange = document.getElementById(idName).style;
        
        var originalColor;
        
        if (id%2 == 0) {
          originalColor = blueColor;
          originalColorHex = "e5e5f5";
        }
        else {
          originalColor = whiteColor;
          originalColorHex = "ffffff";
        }
        
        //alert(idName+" "+checkBoxId+" "+newColor+" "+originalColor+" "+divToChange.backgroundColor);
        if (divToChange.backgroundColor==originalColor || divToChange.backgroundColor=="" || divToChange.backgroundColor=="#"+originalColorHex) {
          chkBox.checked=true;
          divToChange.backgroundColor=newColor;          
        }
        else {
          chkBox.checked=false;
          divToChange.backgroundColor=originalColor;          
        }    
      }
      
      function HexToDec(hexNumber)  {
        hexNumber = hexNumber.toUpperCase();
        
        a = GiveDec(hexNumber.substring(0, 1));
        b = GiveDec(hexNumber.substring(1, 2));
        c = GiveDec(hexNumber.substring(2, 3));
        d = GiveDec(hexNumber.substring(3, 4));
        e = GiveDec(hexNumber.substring(4, 5));
        f = GiveDec(hexNumber.substring(5, 6));
        
        x = (a * 16) + b;
        y = (c * 16) + d;
        z = (e * 16) + f;
        
        if (IE)
          return "rgb("+x+","+y+","+z+")";
        else
          return "rgb("+x+", "+y+", "+z+")"; 
      }
      
      function GiveDec(Hex) {
         if(Hex == "A")
          Value = 10;
         else
         if(Hex == "B")
          Value = 11;
         else
         if(Hex == "C")
          Value = 12;
         else
         if(Hex == "D")
          Value = 13;
         else
         if(Hex == "E")
          Value = 14;
         else
         if(Hex == "F")
          Value = 15;
         else
          Value = eval(Hex);
      
         return Value;
      }
     
      function checkAllImages() {
        numPerPage = document.imageListForm.elements.length;
        
        var divToChange;
        var count = 1;
        for (var x = 0; x < numPerPage; x++) {
          if (document.imageListForm.elements[x].type == "checkbox") {
            document.imageListForm.elements[x].checked = true;
            divToChange = "row"+count;
            document.getElementById(divToChange).style.backgroundColor=newColor;
            count++;
          }
        }
      }
      
      function unCheckAllImages() {
        numPerPage = document.imageListForm.elements.length;
        
        var colorArray = new Array();
        colorArray[0] = blueColor;
        colorArray[1] = whiteColor;
        
        var divToChange;
        var count = 1;
        var index;
        for (var x = 0; x < numPerPage; x++) {
          if (document.imageListForm.elements[x].type == "checkbox") {
            document.imageListForm.elements[x].checked = false;
            index = count%2;
            divToChange = "row"+count;
            document.getElementById(divToChange).style.backgroundColor=colorArray[index];
            count++;
          }        
        }
      }
      
      function operationSubmit( ) {
        operation = document.operationForm.collectionList.value;
        
        if (operation != "") {
          if (operation != "copyToNewCollection") {
            document.imageListForm.action = "copyToCollection.php";
            document.imageListForm.collectionId.value = document.operationForm.collectionList.value;
            var confirmCopy = confirm("Copy Images to Existing Collection?");
            
            if (confirmCopy)
              document.imageListForm.submit();
          } else {
            document.imageListForm.action = "copyToNewCollection.php";
            document.imageListForm.submit();     
          }
        }
      }
      
      function operationListOnChange( form) {
        
        if (document.getElementById) {
            collectionList = document.getElementById("collectionListId");
          if (!collectionList) return;
          if (form.operationList.value == "copyToCollection") {
            collectionList.style.display = "inline";
          }
            else 
              collectionList.style.display = "none";
        }
      }
    // -->
    </script>';
}

function printPagesTool()
{
	global $config;

	// How many to show

	echo '<tr><td colspan="4" valign="top">Show:';
	echo ' <select name="numPerPage" onchange="changeNumPerPage(this.form);return false;" >';
	for ($i = 1; $i <= $config->displayThumbsPerPageSelect; $i++) {
		$value = $i * 10;
		echo '<option value="' . $value . '" ';
		if ($value == $_GET['numPerPage'])
		echo 'selected="selected"';
		echo '>' . $value . '</option>';
	}
	echo '</select>hits per page';
	// Page
	echo ' &nbsp;&nbsp; Page:<input align="top" name="goTo" size="5" type="text" value="';
	echo $_GET['goTo'] . '" />';
	echo '<a href="javascript: gotoPageOnClick();" class="button smallButton"><div>Go</div></a>';
	echo '</td>';

	echo '</tr><tr><td colspan="4"><img src="/style/webImages/blueHR-trans.png" width="585" height="4" class="blueHR" style="margin-bottom:5px;" alt="" />';
	echo '</td></tr>';
}

// use Steve module => this is temporaly
function getUserCollectionArray($userId, $groupId)
{
	global $link;
	

	if ($groupId == null || $userId==null) return false;
	// You can't copy to a published collection.
	// TODO get rid of this restriction
	$sql = 'SELECT * FROM BaseObject  ' . 'WHERE userId = ' . $userId . ' AND groupId = '
	. $groupId . ' AND objectTypeId="Collection"' . 'AND dateToPublish > NOW() ' . 'ORDER BY id ASC ';

	$results = mysqli_query($link, $sql);
	if ($results) {
		$numRows = mysqli_num_rows($results);
		for ($i = 0; $i < $numRows; $i++)
		$collectionArray[$i] = mysqli_fetch_array($results);
		return $collectionArray;
	} else
	return false;
}

function printOperationTool()
{
	global  $objInfo;

	$listOfCollection = false;
	if ($objInfo->getUserId() != null && $objInfo->getUserGroupId() != null) {
		$listOfCollection = getUserCollectionArray($objInfo->getUserId(), $objInfo->getUserGroupId());
	}
	$operation = array(array('label' => 'Select an operation', 'value' => '',
	'formAction' => '', 'visible' => true, 'selected' => false), 
	array('label' => '--------------------', 'value' => '',
	'formAction' => '', 'visible' => true, 'selected' => false), 
	array('label' => 'Copy to a new collection', 'value' => 'copyToNewCollection',
	'formAction' => 'copyToNewCollection.php', 'visible' => true, 'selected' => true));

	echo '<!--hr/ width="95%" align="left"--><tr><td width="70px">';
	echo '<a href="javascript: checkAllImages();" class="button smallButton"';
	echo ' title="Check all images in the current page" ><div>Check All</div></a>';
	echo '</td><td width="70px">';
	echo '<a href="javascript: unCheckAllImages();" class="button smallButton"';
	echo ' title="Uncheck all images in the current page" ><div>UnCheck</div></a>';
	echo '</td><td align="center">';
	echo '<select name="collectionList" size="1" style="display:block; width:275px;">';
	for ($i = 0; $i < count($operation); $i++) {
		if ($operation[$i]['visible']) {
			echo '<option value="' . $operation[$i]['value'] . '" ';
			if ($operation[$i]['selected'])
			echo 'selected="selected" ';
			echo '>' . $operation[$i]['label'] . '</option>';
		}
	}
	echo '<option value="' . $operation[1]['value'] . '">' . $operation[1]['label'] . '</option>';
	if ($listOfCollection) {
		$numCollection = count($listOfCollection);
		for ($i = 0; $i < $numCollection; $i++)
		echo '<option value="' . $listOfCollection[$i]['id'] . '">Copy to: ' . $listOfCollection[$i]['name'] . '</option>';
	}
	echo '</select>
        </td>
        <td align="right">';

	echo '<a href="javascript: operationSubmit();" class="button smallButton"><div>Submit</div></a>';
	echo '</td></tr>';
	echo '<tr><td colspan="4"><img src="/style/webImages/blueHR-trans.png" width="585" height="4" class="blueHR" style="margin-bottom:5px;" ';
	echo 'alt="" /></td></tr>';
	echo '<!--hr/ width="95%" align="left"-->';
}
?>
