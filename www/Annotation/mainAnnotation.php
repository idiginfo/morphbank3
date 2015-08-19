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

//* @package: Annotation                                                             *
//* Description:  This is the MorphBank Annotation Tool.  The basic                  *
//*     idea is to allow authorized users to view images and then enter comments     *
//*     about those images.  There is not restriction as to the number of comments   *
//*     that can be made. Associated with the comments is a title, keywords, type    *
//*     annotation, and a d 256 block of characters for comments.                    *
//***********************************************************************************/

// function mainAnnotation($imageArray, $myCollectionId, $PrevURL) {

function mainAnnotation($objArray, $collectionId) {
	global $objInfo;
	
	global $annotationMenuOptions, $objInfo, $annotationMenu, $annotationid;
	global $config;

	$PrevURL = $_SERVER['HTTP_REFERER'];
	
	$db = connect();

	$userId   = $objInfo->getUserId();
	$groupId  = $objInfo->getUserGroupId();
	$userName = $objInfo->getName();

	$_SESSION['userid'] = $userId;
	$_SESSION['groupid'] = $groupId;
	
	$PublishDate = date('Y-m-d', (mktime(0, 0, 0, date("m") + 6, date("d") - 1, date("Y"))));
	
	/**
	 * Added to filter out anything that is not an image
	 */
	foreach ($objArray as $key => $object) {
		if ($object['objecttypeid'] != 'Image') {
			unset($objArray[$key]);
		}
	}
	$arrayCount = count($objArray);
	
	if ($arrayCount > 1) {
		echo '<style type="text/css">';
		echo '.scroll{width:740px; height: 175px; overflow: scroll;}';
		echo '</style>';
	}
	
	$tsnId = getTsnByType($objArray[0]['objectid'], "Image");
	
	echo '<form name="frmAnnotate" id="frmAnnotate" method="post" action="addMassAnnotation.php" enctype="multipart/form-data">';
	echo isset($_GET['pop']) ? '<input type="hidden" name="pop" value="yes" />' : '';
	echo '<input type="hidden" name="PrevURL" value="' . $PrevURL . '">';
	echo '<input type="hidden" name="taxon_name" id="taxon_name" value="/Admin/TaxonSearch/annotateTSN.php?tsn='.$tsnId.'">';
	foreach ($objArray as $key => $object) {
		echo '<input type="hidden" name="objArray['.$key.'][objectid]" value="' . $object['objectid'] . '">';
		echo '<input type="hidden" name="objArray['.$key.'][objecttypeid]" value="' . $object['objecttypeid'] . '">';
	}
	echo  '<input type="hidden" name="collectionId" value="' . $collectionId . '">';
	if ($arrayCount > 1) {
		displayImageList($objArray, $collectionId);
		displayThumbs($objArray);
	} else {
		displayImageTitle($objArray);
	}
	echo "<br/>";
	echo '<table class="topBlueBorder" style="z-index=1;">
			<tr><td><b>Type of Annotation <span class="req">*</span></b></td><td>';
			displayTypeofAnnotations();
	echo '</tr></table width="740px">';
	
	echo '<div id="ddiv" style="display:none;">';
	displayRelatedAnnotations($objArray);
	echo '<h3>Determination Annotation</h3>';
	echo '<table class="topBlueBorder" width="740">';
	echo '<tr><td Colspan=2 Align="Center" Border=3><b>Determination Data Fields</b></td></tr>';
	echo '<tr>';
	echo '<tr>';
	echo '  <td><b>Determination Action <span class="req">*</span></b></td>';
	echo '  <td colspan="2">
				<select id="typeDetAnnotation" name="typeDetAnnotation" tabindex="3" title="Select whether you agree, disagree, or qualify lowest rank or choose a new name.">';
	echo '       <option Value="agree">Agree - choose name above</option>';
	echo '       <option value="disagree">Disagree - choose name above</option>';
	echo '       <option value="agreewq">Qualify lowest rank - choose name above</option>';
	echo '       <option value="newdet" selected="Selected">Give different name - choose name below</option>';
	echo '   </Select></td>';
	echo '<tr id="determinationTD">';
	echo '  <td><b>New Taxon</b></td>';
	echo '  <td><input type="text" readonly id="Determination" name="Determination" size="35" "tabindex="4" value=""
	                   title="Select the taxon name that best describes this specimen or collection">';
	echo '<a href="javascript: pop(\'TSN\', \'/Admin/TaxonSearch/index.php?tsn=0&searchonly=0&annotation=1&pop=yes\')" title="Click to select a Taxon name">';
	echo '<img src="/style/webImages/selectIcon.png" Border="0" alt="Select TSN"></a>';
	echo '<input type="hidden" id="tsnId" name="tsnId" value="0"/></td>';
	echo '</tr></table>';
	echo '<div id="prepost" style="display:blank;">';
	echo '<table class="topBlueBorder" width="740">';
	echo '<tr><td width="310px"><b>Prefix of lowest rank</b></td>';
	echo '<td><Select title="Select a prefix with agreement with qualification or new taxon"  tabindex="5" name="prefix" size="1">';
	echo '   <option value="none" selected="selected">None</option>';
	echo '   <option value="aff">aff - akin to</option>';
	echo '   <option value="cf">cf - compare with</option>';
	echo '   <option value="forsan">forsan - perhaps</option>';
	echo '   <option value="near">near - close to</option>';
	echo '   <option value="?">? - Questionable</option>';
	echo '</select></td></tr>';
	echo '<tr><td><b>Suffix of lowest rank</b></td>';
	echo '<td><select name="suffix" size="1" tabindex="6" title="Select a suffix with agreement with qualification or new taxon">';
	echo '   <option value="none" selected="selected">None</option>';
	echo '   <option value="sensu lato">Senso Latu - In the broad sense</option>';
	echo '   <option value="sensu stricto">Senso Stricto - In the narrow sense</option>';
	echo '</select></td></tr></Table>';
	echo '</div>';
	echo '<table class="topBlueBorder" width="740">';

	echo '<td width="310px"><b>Materials used in Id</b></td>';
	echo '<td><Select name="materialsUsedInId" size="1" tabindex="7" title="Identify the type of materials used in the identification">';
	$MEArray = getMaterialsExamined();
	$size = sizeof($MEArray);
	for ($i = 0; $i < $size; $i++) {
		echo '<option value="' . $MEArray[$i] . '">' . $MEArray[$i] . '</option>';
	}
		echo '</select></td>';
		echo '</tr>';
		echo '<tr>';

	echo '  <td><b>Source of Identification <span class="req">*</span></b></td>';
	echo '  <td><input type="text" name="sourceOfId" tabindex="8" size="35" value="' . $userName . '"
                title="Enter the person who made the determination, defaults to user">';
	echo '</tr>';
	echo '<tr>';
	echo '  <td><b>Resources used in Identification <span class="req">*</span></b></td>';
	echo '  <td><input type="text" name="resourcesused" size="64" tabindex="9"
                       title="Enter the citations for literature or other resouces used in identification of specimen, including expert opinion">';
	echo '</tr></table>';
	echo '</div>';
	
	// **************************************************************************************************
	// **   FIELDS COMMON TO ALL ANNOTATIONS AND WILL ALWAYS APPEAR.                                    *
	// **************************************************************************************************
	$comments = "";
	if ($arrayCount > 1) {
		$comments = "Annotation related to the following images: ";
		foreach ($objArray as $object) {
			$comments .= $object['objectid'] . ', ';
		}
		$comments = rtrim($comments, ', ');
		$comments .= ' of Collection id [' . $collectionId . ']';
	}

	echo '<h3>Common Annotation Fields</h3>';
	echo '<table class="topBlueBorder" width="740">';
	echo '<tr>
			<td><b>Title <span class="req">*</span></b></td>
			<td>
				<input type="text" tabindex="10" id="title" name="title" size="40"
                  value="" title="Enter a title for the Annotation up to 40 characters">
            </td>
          </tr>';
	echo '<tr>
			<td><b>Comments <span class="req">*</span></b></td>
			<td><textarea name="comment" rows="5" cols="64"
                 tabindex="11" title="Enter comments associated with this annotation">' . $comments . '</textarea>
            </td>
          </tr>';
	
	if ($arrayCount < 2) {
		echo '<tr><td><b>Image Label:</b></td><td><input type="text" name="annotationLabel" tabindex="12" size=35 title="The label can be entered here or during arrow placement">';

		//change made by Karolina
		echo '&nbsp;<a href="javascript:openPopup(\'' . $config->domain . 'Annotation/ckhadd.php?id=' . $objArray[0]['objectid'] . '\', 850, 750)">
		<img src="/style/webImages/selectIcon.png"></td></tr>';
		//end of change

		echo '<tr colspan=3><td><b>X-Coord </b></td><td><input type="text" name="xLocation" size=10 value="0" tabindex="13" title="click image to assign coordinate location"></td></tr>';
		echo '<tr colspan=3><td><b>Y-Coord </b></td><td><input type="text" name="yLocation" size=10 value="0" tabindex="14" title="click image to assign coordinate location"></td></tr>';
		echo '<tr><td><b>Type of markup</b></td><td><input type="text" name="arrowc" size=20 value="0" tabindex="14" title=""></td></tr>';
		echo '</table>';
	}

	else
	echo '</table>';
	echo '<div id="XML" style="display:none">';
	echo '<table class="topBlueBorder" width="740px"><tr>';
	echo '   <td><b>XML Data</b></td><td Align="left"><input type="file" name="XMLData" size="40"';
	echo '      tabindex="15"  title="Browse for an XML file to include with the Annotation">';
	echo '</td></tr>';
	echo '</table></div>';
	echo '<table class="topBlueBorder"  width="740">';
	echo '<tr ><td width="45%" ><b>Date To Publish (YYYY/MM/DD) <span class="req">*</span></b></td>';
	echo ' <td align="left"><input type="text" name="dateToPublish" size="10" tabindex="15" maxlength="10" ';
	echo ' value="' . $PublishDate . '" ';
	//echo " onfocus=\"javascript:vDateType='2';\" ";
	//echo ' onblur="DateFormat(this,this.value, event,true,2);"
	echo '    title="Enter the date to make this annotation public.  Defaults to 6 months in the future." />';
	echo '  </td><td>&nbsp;</td>';
	echo '</tr>
     </table>';
	echo '<br/><b><span class="req">*-Required</span></b>
     <table align="right"><tr border="0">
         <td><input type="submit" class="button smallButton" value="Submit" /></td>
         <td><a href="javascript: window.close();" class="button smallButton right" title="Click to return to previous page">
       <div>Cancel</div></a></td>
       </tr>
     </table>';

	echo '</form>';
}
