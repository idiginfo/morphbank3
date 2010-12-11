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
 *Script that handles all the individual collections.
 *
 *This script is called by index.php, and has most of the functions to manipulate single collections.
 *The ability to sort, move, save, copy, move etc objects of a collection along with the javascript
 *is inluded in this file.
 *
 *File: index.php
 *@package morphbank2
 *@subpackage collections
 *
 */

include_once('imageFunctions.php');
include_once('tsnFunctions.php');
include_once('collectionFunctions.inc.php');

$imgSize = (isset($_POST['imgSize'])) ? $_POST['imgSize'] : '80';
$iconFlag = $_POST['iconFlag']? $_POST['iconFlag']: "true";

if ($_POST['toolFlag'] == 'sort') {
	if ($_POST['flagData'] == "toolbar1") {
		$sortBy = $_POST['sortBy'];
		$order = $_POST['order'];
	} elseif ($_POST['flagData'] == "toolbar2") {
		$sortBy = $_POST['sortBy2'];
		$order = $_POST['order2'];
	} else {
		$sortBy = "taxon";
		$order = "asc";
	}
} else {
	$sortBy = "taxon";
	$order = "asc";
}

if ($_GET['imgSize'])
$imgSize = $_GET['imgSize'];

/***********************************************************************************
 * Main Function.  All other functions are called from here.                                                                  *
 ***********************************************************************************/
function mainMyCollection($collectionId, $loggedIn)
{
	global $link;
	
	
	
	
	global $annotationMenuOptions;
	global $annotationMenu;
	global $annotationid;
	global $imageId;
	global $objInfo, $config, $imgSize, $sortBy, $order, $iconFlag;

	$collectionType = getCollectionType($collectionId);

	if ($collectionType == "MbCharacter") {
		$phyloChar = new PhyloCharacter($link, $collectionId);

		if (isset($_POST['imageindex'])) {
			$ImageArray = getFieldOrder($_POST['imageindex'],"ImageId:[");
			$newArray = checkStateOrder($ImageArray, $collectionId);

			$phyloChar->updateCharacter($newArray);
		}
	}

	if ($_POST['toolFlag']) {
		$objectCount = ($collectionType == "Collection") ? getCollectionCount($collectionId) : (PhyloCharacter::getImageIds($collectionId) + getCollectionCount($collectionId));

		if ($_POST['toolFlag'] != 'default') {
			//echo $sql.$_POST['imageindex'];
			if ($_POST['toolFlag'] == 'delete' && checkDateToPublish( $collectionId, $objInfo->getUserId(), $objInfo->getUserGroupId() ) ) {

				deleteObjects($collectionId, $objectCount, $collectionType);

			} elseif ($_POST['toolFlag'] == 'copy') {
				$idArray = getIdArrayFromPost();
				insertObjects($idArray, $collectionId, $_POST['flagData'], $objectCount);
				updateCollectionKeywords($_POST['flagData']);
			} elseif ($_POST['toolFlag'] == 'move' && checkDateToPublish( $collectionId, $objInfo->getUserId(), $objInfo->getUserGroupId() ) ) {
				$idArray = getIdArrayFromPost();

				insertObjects($idArray, $collectionId, $_POST['flagData'], $objectCount);
				updateCollectionKeywords($_POST['flagData']);
				deleteObjects($collectionId, $objectCount);
			} elseif ($_POST['toolFlag'] == 'create') {
				$idArray = getIdArrayFromPost();

				$newCollectionId = createCollection($idArray, $objInfo->getUserId(), $objInfo->getUserGroupId());
				if ($_POST['flagData'] == 'move' && checkDateToPublish( $collectionId, $objInfo->getUserId(), $objInfo->getUserGroupId() ))
				deleteObjects($collectionId, $objectCount);

				echo '<form name="redirect" action="'.$config->domain.'MyManager/index.php" method="get">';
				echo '<input type="hidden" name="newCollectionId" value="'.$newCollectionId.'" />';
				echo '<input type="hidden" name="tab" value="collectionTab" />';
				echo '<script type="text/javascript" language="JavaScript">';
				echo 'document.redirect.submit();</script></form>';
			} elseif ($_POST['toolFlag'] == 'label' && checkDateToPublish( $collectionId, $objInfo->getUserId(), $objInfo->getUserGroupId() ) ) {

				//echo 'test tool flag ok';
				labelObjects($collectionId, $_POST['flagData'], $objectCount);
			} elseif ($_POST['toolFlag'] == 'annotate') {
				$count = 0;
				for ($i = 1; $i <= $objectCount; $i++) {
					if ($_POST['object'.$i.''])
					$count++;
				}
				if ($count > 0) {
					$massAnnotateUrl = $config->domain . 'Annotation/index.php?collectionId='.$collectionId.'&pop=yes';
					echo '<script type="text/javascript" language="JavaScript">';
					echo 'openPopup(\''.$massAnnotateUrl.'\');';
					echo '</script>';
				} else {
					$annotateError = 1;
				}

			} elseif ($_POST['toolFlag'] == 'annotateSingle') {
				echo '<form name="massAnnotateForm" action="'.$config->domain;
				echo 'Annotation/index.php" method="get">';
				echo '<input type="hidden" name="id" value="'.$_POST['flagData'].'" />';
				//<input type="hidden" name="collectionId" value="'.$collectionId.'" />
				echo'<script type="text/javascript" language="JavaScript">';
				echo '<!--';
				echo '//document.massAnnotateForm.submit();';
				echo 'openPopup(\''.$config->domain.'Annotation/index.php?id='.$_POST['flagData'].'\');';
				echo '//--></script></form>';

			} elseif ($_POST['toolFlag'] == 'annotateSingleTaxon') {
				echo '<form name="massAnnotateForm" action="'.$config->domain;
				echo 'Admin/TaxonSearch/annotateTSN.php" method="get">';
				echo '<input type="hidden" name="tsn" value="'.$_POST['flagData'].'" />';
				echo '//<input type="hidden" name="collectionId" value="'.$collectionId.'" />';
				echo'<script type="text/javascript" language="JavaScript">';
				echo '<!-- document.massAnnotateForm.submit();';
				echo '//--></script></form>';
			}
		}
	}

	if ($_GET['objectId'] && checkDateToPublish( $_GET['collectionId'],
	$objInfo->getUserId(), $objInfo->getUserGroupId() )) {
		if ($collectionType == "MbCharacter") {
			updateTitle_phylo($_GET['objectId'], $phyloChar, $_GET['title']);
		} else {
			updateTitle($_GET['objectId'], $collectionId, $_GET['title']);
		}
	}

	echo '<div class="mainGenericContainer" style="min-width:900px;">';
	//Error message if user attempts to mass annotate no objects
	if ($annotateError == 1){
		echo '<h2 class="red">You must select at least one object to annotate!</h2><br /><br />';
	}

	if(isset($_POST['imageindex']) && checkDateToPublish( $collectionId, $objInfo->getUserId(), $objInfo->getUserGroupId() ) ) {
		if ($_POST['toolFlag'] == 'sort') {
			$sortedImageArray = sortCollection($collectionId, $sortBy, $order);
			//for ($i = 0; $i < count($sortedImageArray); $i++)
			//echo $sortedImageArray[$i];
			reOrderCollection($collectionId, $sortedImageArray);

		} elseif ($collectionType == "MbCharacter") {
			$ImageArray = getFieldOrder($_POST['imageindex'],"ImageId:[");
			$newArray = checkStateOrder($ImageArray, $collectionId);

			if ($_POST['toolFlag'] == 'delete') {
				$newArray = deleteFromArray($newArray);
			}
			$phyloChar->updateCharacter($newArray);
		} else {
			$ImageArray = getFieldOrder($_POST['imageindex'],"ImageId:[");
			reOrderCollection($collectionId, $ImageArray);
		}

		if ($_POST['toolFlag'] == 'createState') {
			$array = explode("_", $_POST['flagData']);
			$size = count($array);

			if (empty($array[0])) {
				$phyloChar->makeState(NULL, $array[2], $array[1] );
				CharacterKeywords($link, $collectionId , "Update");
			} else {
				$idArray = explode("-", $array[0]);
				$phyloChar->makeState($idArray, $array[2], $array[1] );
				CharacterKeywords($link, $collectionId , "Update");
			}
		}
	}

	if ($collectionType == "MbCharacter") {
		$rows = $phyloChar->getIdsFromCharacter();
		$arraySize = count($rows);

	} else {
		$rows = getArrayOfObjects($collectionId);
		$arraySize = getCollectionCount($collectionId);
	}

	echo '<form name="collectionForm" action="index.php?collectionId=';
	echo $collectionId.'" method="post" onsubmit="junkdrawer.inspectListOrder(\'boxes\');">';

	//echo $_POST['toolFlag'];
	echo ' <h1>My Collections/Characters</h1>&nbsp;';
	echo '<a href="';
	echo $config->domain.'About/Manual/characterCollections.php" target="manual">(Need Help...)</a>';
	echo '&nbsp;<a href="'.$config->domain.'Help/feedback/" target="feedback">(Feedback...)</a>';
	echo '<table id="collectionTools" class="collectionTable" width="100%" ';
	echo 'border="0" cellspacing="0" cellpadding="0" > ';
	echo '<tr>';
	if ($loggedIn) {
		echo '<td id="collectionToolbar" valign="top" width="190" rowspan="2">';

		outputTools($collectionId, $collectionType);
		outputUserCollections($objInfo->getUserId(), $objInfo->getUserGroupId(), $collectionId, $collectionType );
		echo '</td>';
	}
	echo '<td valign="top" id="collectionTiles" style="border-bottom-width:0px;">';
	echo '<img src="/style/webImages/minWidth.gif" width="700" height="1" alt="" border="0" />';
	echoTitle($collectionId, $arraySize);
	//echo $_POST['imageindex'];

	//if(!checkDateToPublish($collectionId, $objInfo->getUserId(), $objInfo->getUserGroupId()) || $loggedIn == FALSE)
	//echo '<br /><h3 class="red">&nbsp;&nbsp;This Collection is Published.  You May Only Annotate or Copy Objects to Another Collection.</h3><br /><br />';

	if ($collectionType == "MbCharacter") {
		$rows = $phyloChar->getIdsFromCharacter();
		$arraySize = count($rows);
	} else {
		$rows = getArrayOfObjects($collectionId);
		$arraySize = getCollectionCount($collectionId);
	}
	$imgType = getSizedImageType($imgSize);
	//echo "image size $imgSize type $imgType<br/>";
	echo '<ul id="boxes">';
	for ($i = 0; $i < $arraySize; $i++) {
		showTile($i, $rows[$i],$imgSize, $imgType, $iconFlag);
	}

	echo '</ul>';
	echo '<input type="hidden" name="imageindex" value="default" />';
	echo '<input type="hidden" name="toolFlag" value="default" />';
	echo '<input type="hidden" name="flagData" value="default" />';
	echo '<input type="hidden" name="imgSize" value="default" />';
	echo '<input type="hidden" name="iconFlag" value="'.$iconFlag.'" />';
	echo '</td></tr>';
	echo '<tr><td valign="bottom" style="border-top-width:0px;">';
	echo '<table class="collectionBottomToolbar" cellspacing="0">';
	echo '<tr><td height="50" class="sortToolbarPadding" style="border-width:0px;">';
	echoSortToolbar($collectionId, "bottom");
	echo '</td></tr></table></td></tr></table></form></div>'; //mainGenericContainer
} // end function mainMyCollection

function showTile($i, $row, $imgSize, $imgType, $iconFlag){
	global $config;
	$styleArray = getStyleArray($row, $imgSize, $imgType, $iconFlag);
	$objectId = $row['objectId'];
	$thumbId = $styleArray['thumbId'];
	$objectTypeId= $row['objectTypeId'];
	$title = 'ObjectId:['.$objectId.']';
	$objectTitle = $row['objectTitle'];
	$postItContent = $config->domain . 'ajax/postItSource.php?id='.$objectId
	.'&objectTypeId='.$objectTypeId;

	$url = $config->domain . 'myCollection/editTitle.php?objectId='.$objectId
	.'&amp;collectionId='.$collectionId.'&amp;imgSize='.$imgSize;

	if ($imgSize < 100) {
		if ($iconFlag === "false") $numChars = 12;
		else $numChars = 12;
		$objectTitle = wordwrap($objectTitle, $numChars, "<br />", 1);
	}

	// if the tile is a character state, add the value of the state to the title
	if ($objectTypeId == "CharacterState") {
		$objectTitle .= '('.getStateValue($objectId).')';
	}

	// display image
	echo "\n\n".'<li style="'.$styleArray['liStyle'].'" >';
	echo '<img src="'.$styleArray['imgUrl'].'" class="moveCursor" '.$styleArray['imgSize'];
	echo ' title="" alt="'.$title.'" ondblclick="javascript: openPopup(\'';
	echo bischenPageUrl($thumbId, null).'\', \'true\',\'true\')"';
	echo ' onmouseover="javascript:startPostItSpry( event, \''.$postItContent;
	echo '\');" onmouseout="javascript:stopPostItSpry();" /><br/>'."\n";
	echo '<br />';

	// display icons
	if ($iconFlag === "false") { // if the iconFlag variable is set to boolean FALSE (ex. Dont display icons)
		if ($imgSize < 100) {
			$tableAlign = "left";
		} else {
			$tableAlign = "center";
		}
		echo'<table id="noIcons" cellpadding="0" cellspacing="0" align="'.$tableAlign.'"><tr>';
		echo '<td align="left" valign="top"><input id="inputId'.$i.'" class="pointerCursor" ';
		echo 'type="checkbox" name="object'.$row['objectOrder'].'" value="';
		echo $objectId.'" onclick="javascript: swapColor(this, \'inputId';
		echo $i.'\', false);" /></td>';
		echo '<td align="left" valign="top">'.$objectTitle.'</td></tr>';
		echo'</table>';
	} else { // if iconFlag isn't FALSE or isn't set then display icons
		// Size of the "li", minus the collectionIcon Div(88) divided by 2 gives the proper left to center the icons.
		$iconStyleLeft = round(($styleArray['liWidth'] - 88) / 2); 
 		echo $objectTitle.'<br />';
		
		echo '<div id="iconSet'.$i.'" class="collectionIcons" align="center" style="left:';
		echo $iconStyleLeft.'px;">';

		echo '<input id="inputId'.$i.'" class="pointerCursor" type="checkbox" name="object';
		echo ($i+1).'" value="'.$objectId.'" onclick="javascript: swapColor(this, \'inputId';
		echo $i.'\', true);" />';
		if ($objectTypeId != "CharacterState") {
			echo'<a href="javascript:openTitleEdit(\''.$url.'\')">';
			echo '<img src="';
			echo '/style/webImages/edit-trans.png" width="16" height="16" class="collectionIcon" ';
			echo 'align="top" alt="image" title="Click to Edit Title" /></a>';

			$annotationId = hasAnnotation($objectId);
			//$annotationType = ($objectTypeId == "TaxonConcept") ? "annotateSingleTaxon" : "annotateSingle" ;
			if ($objectTypeId == "TaxonConcept") {
				$annotationUrl =  $config->domain.'Admin/TaxonSearch/annotateTSN.php?tsn=';
			} else {
				$annotationUrl =  $config->domain.'Annotation/index.php?id='  ;
			}
			// if image (or object) has an annotation, display a different icon, that opens up the first annotation.  User can add additional annotations from there.
			if ($annotationId){
				echo '<a href="'.$config->domain.'?id='.$annotationId.'">';
				echo getAnnotateImageTag("Click to Annotate", 'class="collectionIcon" align="top"');
				echo '</a>';
			} else {
				echo'<a href="'.$annotationUrl.$objectId.'" >';
				echo getAnnotateImageTag("Click to Annotate", 'class="collectionIcon"');
				echo '</a>';
			}
			echo showDetailTag($objectId);
		}
		echo '</div>';
	}
	echo '</li>';
}


// Checks to see if there are any leading objects in the array list, which would represent objects without a state (since objects are to the right of a state).
// If will put those objects into the "Undesigniated State" at the beginning of that state.
// Also will remove objects from the array that have been "deleted"
function checkStateOrder($array, $collectionId) {
	global $link;

	$objectType = getObjectType($array[0]);

	if ($objectType == "CharacterState" || $objectType == "PhyloCharState")
	return $array;
	else {
		$indexOfLastImage = 0;
		// find out how many images are in the begining of the list before the first state
		foreach($array as $k => $v) {
			if (getObjectType($v) == "PhyloCharState" || getObjectType($v) == "CharacterState" ) {
				break;
			} else {
				$indexOfLastImage = $k;
			}
		}

		$indexOfTargetState = 0;

		foreach($array as $k => $v) {
			if (getStateTitle($v, $collectionId) == "Undesignated state") {
				break;
			} else {
				$indexOfTargetState = ($k+1);
			}
		}

		$tempArray = array();
		for ($i = 0; $i <= $indexOfLastImage; $i++ ) {
			$tempArray[$i] = $array[$i];
		}
		for ($i = 0; $i <= $indexOfLastImage; $i++ ) {
			array_shift($array);
		}
		array_splice($array, $indexOfTargetState, 0, $tempArray);
		return $array;
	}
}

function deleteFromArray ($array) {
	$newArray = $array;
	$idArray = getIdArrayFromPost();
	$index = 0;
	$posArray = array();
	foreach($idArray as $deleteId) {
		foreach($array as $k => $v) {
			if ($deleteId['id'] == $v) {
				$posArray[$index] = $k;
			}
		}
		$index++;
	}

	for ($i=(count($posArray)-1); $i >= 0; $i-- ) {
		array_splice($newArray, $posArray[$i], 1);
	}
	return $newArray;
}

function getStateTitle($id, $collectionId) {
	global $link;

	$sql = 'SELECT objectTitle FROM CollectionObjects WHERE collectionId = '.$collectionId
	.' AND objectId ='.$id;
	$result = mysqli_query($link, $sql);
	if ($result) {
		$array = mysqli_fetch_array($result);
		return $array['objectTitle'];
	}
	return FALSE;
}

function getNewStateValue($id) {
	global $link;

	$sql = 'SELECT COUNT(*) as count FROM CollectionObjects WHERE collectionId ='.$id;

	$result = mysqli_query($link, $sql);

	if ($result) {
		$array = mysqli_fetch_array($result);
		return ($array['count'] -1);
	}
	return FALSE;
}

function getStateValue($id) {
	global $link;

	$sql = 'SELECT charStateValue FROM CharacterState WHERE id ='.$id;

	$result = mysqli_query($link, $sql);

	if ($result) {
		$array = mysqli_fetch_array($result);
		return $array['charStateValue'];
	}
	return FALSE;
}

function getObjectType($id) {
	global $link;

	$sql = 'SELECT objectTypeId FROM BaseObject WHERE id='.$id;

	$result = mysqli_query($link, $sql);

	if ($result) {
		$array = mysqli_fetch_array($result);

		return $array['objectTypeId'];
	}
	return FALSE;
}

function getCollectionType($id) {
	global $link;

	$sql = 'SELECT objectTypeId FROM BaseObject WHERE id='.$id;

	$result = mysqli_query($link, $sql);

	if ($result) {
		$array = mysqli_fetch_array($result);
		return $array['objectTypeId'];
	}
	return FALSE;
}

function hasAnnotation($id) {
	global $link;

	$sql = 'SELECT id FROM Annotation WHERE objectId = '.$id.' LIMIT 1';

	$result = mysqli_query($link, $sql);

	if ($result) {
		$array = mysqli_fetch_array($result);

		if (!empty($array)) {
			return $array['id'];
		} else {
			return FALSE;
		}
	}
	return FALSE;
}

/**
 * Function will sort and reorder the collection according to specified paramaters.
 * @param $collectionId
 * @param $sortBy
 * @param $order
 * @return unknown_type
 */
function sortCollection($collectionId, $sortBy, $order) {
	global $link;

	$imageArray = getArrayOfObjects($collectionId);
	$numRows = count($imageArray);

	$sql = 'SELECT Image.id '
	.'FROM Image INNER JOIN Specimen ON Image.specimenId = Specimen.id '
	.'INNER JOIN View ON Image.viewId = View.id ';

	if ($sortBy == 'taxon') {
		$sql .= 'INNER JOIN Tree ON Specimen.tsnId = Tree.tsn ';
	}

	if ($sortBy == 'SpecimenPart.name')
	$sql .= 'INNER JOIN SpecimenPart ON View.specimenPart = SpecimenPart.name ';

	if ($sortBy == 'ViewAngle.name') {
		$sql .= 'INNER JOIN ViewAngle ON View.viewAngle = ViewAngle.name ';
	}

	if ($sortBy == 'locality') {
		$sql .= 'INNER JOIN Locality ON Specimen.localityId = Locality.id '
		.'INNER JOIN Country ON Locality.country = Country.name '
		.'INNER JOIN ContinentOcean ON Locality.continentOcean = ContinentOcean.name ';
	}
	$sql .= 'WHERE ';
	for ($i = 0; $i < $numRows; $i++) {
		$sql .= 'Image.id = '.$imageArray[$i]['objectId'].' ';
		if ($i != ($numRows - 1))
		$sql .= ' OR ';
	}

	if ($sortBy == "locality"){
		$sql .= "ORDER BY ContinentOcean.description $order, Country.description "
		."$order., Locality.locality .$order";
	} else {
		if ($sortBy == "taxon"){
			$sql .= "ORDER BY Tree.unit_name1 $order, Tree.unit_name2 $order ";
		} else {
			$sql .= 'ORDER BY '.$sortBy.' '.$order;
		}
	}

	$results = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	if ($results) {
		for ($i = 0; $i < $numRows; $i++) {
			$array[$i] = mysqli_fetch_array($results);
			$array[$i] = $array[$i]['id'];
		}
		return $array;
	}
	return FALSE;
}

/**
 * Function to delete checked objects from a collection.
 * @param $collectionId
 * @param $objectCount
 * @param $collectionType
 * @return unknown_type
 */
function deleteObjects($collectionId, $objectCount, $collectionType = "Collection") {
	global $link;

	if ($collectionType == "Collection" || $collectionType == "Otu") {
		for ($i = 1; $i <= $objectCount; $i++) {
			if ($_POST['object'.$i.'']) {
				$sql = 'DELETE FROM CollectionObjects WHERE collectionId = '.$collectionId.' AND objectId = '.$_POST['object'.$i.''].' ';
				mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
			}
		}
	} elseif ($collectionType == "MbCharacter") {
		$idArray = getIdArrayFromPost();
		$phyloChar = new PhyloCharacter($link, $collectionId);
		foreach($idArray as $imageId) {
			$stateId = $phyloChar->getStateForImage($imageId['id']);
			$phyloChar->deleteObjectFromCollection($stateId , $imageId['id']);
		}
	}
}

/**
 * Function that will label all checked objects in a collection
 * @param $collectionId
 * @param $labelBy
 * @param $objectCount
 * @return unknown_type
 */
function labelObjects($collectionId, $labelBy, $objectCount) {
	global $link;

	include_once ('tsnFunctions.php');
	//echo 'test labelObjects ok';
	if ($labelBy == 'taxon' && !empty($imageIdArray[$i])) {
		//echo 'test targetCollection ok';
		$sql = 'SELECT Tree.scientificName, Image.id FROM Image LEFT JOIN Specimen'
		.' ON Image.specimenId = Specimen.id LEFT JOIN Tree ON Specimen.tsnId = Tree.tsn WHERE ';

		$count = 0;
		for ($i = 1; $i <= $objectCount; $i++) {
			if ($_POST['object'.$i.'']) {
				$imageIdArray[$count] = $_POST['object'.$i.''];
				$count++;
			}
		}

		for ($i = 0; $i < $count; $i++) {
			$sql .= 'Image.id = '.$imageIdArray[$i].' ';
			if ($i != ($count - 1))
			$sql .= 'OR ';
		}

		$results = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
		if ($results) {
			for ($i = 0; $i < $count; $i++)
			$array[$i] = mysqli_fetch_array($results);
		}
		//mysqli_autocommit($link, TRUE);
		for ($i = 0; $i < $count; $i++) {
			$titleString = trim($array[$i]['scientificName']);
			$updateSql = 'UPDATE CollectionObjects SET objectTitle = "'.$titleString.'" WHERE (collectionId = '.$collectionId.' AND objectId = '.$array[$i]['id'].') ';
			mysqli_query($link, $updateSql);
		}
	} elseif ($labelBy == 'taxonSpecies') {
		$sql = 'SELECT Tree.unit_name1, Tree.unit_name2, Tree.unit_name3, Tree.unit_name4, Image.id FROM Image LEFT JOIN Specimen ON Image.specimenId = Specimen.id LEFT JOIN Tree ON Specimen.tsnId = Tree.tsn WHERE (';

		$count = 0;
		for ($i = 1; $i <= $objectCount; $i++) {
			if ($_POST['object'.$i.'']) {
				$imageIdArray[$count] = $_POST['object'.$i.''];
				$count++;
			}
		}

		for ($i = 0; $i < $count; $i++) {
			$sql .= 'Image.id = '.$imageIdArray[$i].' ';

			if ($i != ($count - 1))
			$sql .= 'OR ';
		}

		$sql .= ' ) ';

		$results = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);

		if ($results) {
			for ($i = 0; $i < $count; $i++)
			$array[$i] = mysqli_fetch_array($results);
		}

		for ($i=0; $i < $count; $i++) {
			if ($array[$i]['unit_name4'] != '') {
				$titleArray[$i] = $array[$i]['unit_name4'];
			} elseif ($array[$i]['unit_name3'] != '') {
				$titleArray[$i]  = $array[$i]['unit_name3'];
			} elseif ($array[$i]['unit_name2'] != '') {
				$titleArray[$i]  = $array[$i]['unit_name2'];
			} else {
				$titleArray[$i]  = $array[$i]['unit_name1'];
			}
		}

		for ($i = 0; $i < $count; $i++) {
			$sql = 'UPDATE CollectionObjects SET objectTitle = \''.$titleArray[$i].'\' WHERE collectionId = \''.$collectionId.'\' AND objectId = \''.$array[$i]['id'].'\'; ';
			mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
		}

	} elseif ($labelBy == 'specimenId') {
		$sql = 'SELECT id, specimenId FROM Image WHERE ';

		$count = 0;
		for ($i = 1; $i <= $objectCount; $i++) {
			if ($_POST['object'.$i.'']) {
				$imageIdArray[$count] = $_POST['object'.$i.''];
				$count++;
			}
		}

		for ($i = 0; $i < $count; $i++) {
			$sql .= 'Image.id = '.$imageIdArray[$i].' ';
			if ($i != ($count - 1)) $sql .= 'OR ';
		}

		$results = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);

		if ($results) {
			for ($i = 0; $i < $count; $i++)
			$array[$i] = mysqli_fetch_array($results);
		}

		for ($i = 0; $i < $count; $i++) {
			$sql = 'UPDATE CollectionObjects SET objectTitle = \''.$array[$i]['specimenId'].'\' WHERE collectionId = \''.$collectionId.'\' AND objectId = \''.$array[$i]['id'].'\' ';
			mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
			//echo $sql;
		}

	} elseif ($labelBy == 'specimenPart') {
		$sql = 'SELECT View.specimenPart, Image.id '
		.'FROM Image INNER JOIN View ON Image.viewId = View.id WHERE ';

		$count = 0;
		for ($i = 1; $i <= $objectCount; $i++) {
			if ($_POST['object'.$i.'']) {
				$imageIdArray[$count] = $_POST['object'.$i.''];
				$count++;
			}
		}

		for ($i = 0; $i < $count; $i++) {
			$sql .= 'Image.id = '.$imageIdArray[$i].' ';

			if ($i != ($count - 1))
			$sql .= 'OR ';
		}

		$results = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
		//echo $sql;

		if ($results) {
			for ($i = 0; $i < $count; $i++)
			$array[$i] = mysqli_fetch_array($results);
		}

		for ($i = 0; $i < $count; $i++) {
			$sql = 'UPDATE CollectionObjects SET objectTitle = \''.$array[$i]['specimenPart'].'\' WHERE collectionId = \''.$collectionId.'\' AND objectId = \''.$array[$i]['id'].'\' ';
			mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
			//echo $sql;
		}

	} elseif ($labelBy == 'viewAngle') {
		$sql = 'SELECT View.viewAngle, Image.id '
		.'FROM Image INNER JOIN View ON Image.viewId = View.id '
		.'WHERE ';

		$count = 0;
		for ($i = 1; $i <= $objectCount; $i++) {
			if ($_POST['object'.$i.'']) {
				$imageIdArray[$count] = $_POST['object'.$i.''];
				$count++;
			}
		}

		for ($i = 0; $i < $count; $i++) {
			$sql .= 'Image.id = \''.$imageIdArray[$i].'\' ';

			if ($i != ($count - 1))
			$sql .= 'OR ';
		}

		$results = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
		//echo $sql;

		if ($results) {
			for ($i = 0; $i < $count; $i++)
			$array[$i] = mysqli_fetch_array($results);
		}

		for ($i = 0; $i < $count; $i++) {
			$sql = 'UPDATE CollectionObjects SET objectTitle = \''.$array[$i]['viewAngle'].'\' WHERE collectionId = \''.$collectionId.'\' AND objectId = \''.$array[$i]['id'].'\' ';
			mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
			//echo $sql;
		}
	}
}


/***********************************************************************************
 *Function which will output a sort toolbar with various tools to sort, save and check/uncheck objects in a collection.
 ***********************************************************************************/
/**
 * Function which will output a sort toolbar with various tools to sort,
 * save and check/uncheck objects in a collection.
 * @param $collectionId
 * @param $toolbar
 * @return unknown_type
 */
function echoSortToolbar($collectionId, $toolbar) {
	global $sortBy, $order,  $imgSize, $iconFlag, $objInfo, $loggedIn, $isMyCollection;

	$collectionType = getCollectionType($collectionId);

	$sortVarName = ($toolbar == "top") ? "sortBy" : "sortBy2";
	$orderVarName = ($toolbar == "top") ? "order" : "order2";
	$toolbarVarName = ($toolbar == "top") ? "toolbar1" : "toolbar2";

	$checkAllImgName = ($toolbar == "top") ? "checkAllHover" : "checkAllHover2";
	$unCheckAllImgName = ($toolbar == "top") ? "unCheckAllHover" : "unCheckAllHover2";
	$sortImgName = ($toolbar == "top") ? "sortShortHover" : "sortShortHover2";
	$saveOrderImgName = ($toolbar == "top") ? "saveOrderHover" : "saveOrderHover2";

	echo '<a href="javascript: checkall('.$iconFlag;
	echo ');" class="button smallButton" title="Click to select all of the objects in this collection." >';
	echo '<div>Check All</div></a>&nbsp;&nbsp;&nbsp;<a href="javascript: uncheckall(';
	echo $iconFlag.');" class="button smallButton" title="Click to de-select all of the objects in';
	echo ' this collection." ><div>Uncheck</div></a>';

	if ($loggedIn && $isMyCollection && checkDateToPublish( $collectionId, $objInfo->getUserId(), $objInfo->getUserGroupId() ) ) {
		if ($collectionType == "Collection") {
			echo '<h3>&nbsp;|&nbsp;Sort By:<select name="'.$sortVarName.'" ><option value="taxon" ';
			if ($sortBy == "taxon") {
				echo'selected=\'selected\' ';
			}
			echo '>Taxon Name</option><option value="Image.specimenId" ';
			if ($sortBy == "Image.specimenId") {
				echo'selected=\'selected\' ';
			}
			echo '>Specimen Id</option><option value="SpecimenPart.name" ';
			if ($sortBy == "SpecimenPart.name") {
				echo'selected=\'selected\' ';
			}
			echo '>Specimen Part</option><option value="ViewAngle.name" ';
			if ($sortBy == "ViewAngle.name") {
				echo'selected=\'selected\' ';
			}
			echo '>View Angle</option><option value="locality" ';
			if ($sortBy == "locality") {
				echo'selected=\'selected\' ';
			}
			echo '>Location</option><option value="Specimen.CollectorName" ';
			if ($sortBy == "Specimen.CollectorName") {
				echo'selected=\'selected\' ';
			}
			echo '>Collector</option><option value="Specimen.dateCollected" ';
			if ($sortBy == "Specimen.dateCollected") {
				echo'selected=\'selected\' ';
			}
			echo '>Date Collected</option></select>';
			echo '<input type="radio" name="'.$orderVarName.'" value="asc" ';
			if ($order == "asc") {
				echo'checked=\'checked\' ';
			}
			echo '/>Asc &nbsp;<input type="radio" name="'.$orderVarName.'" value="desc" ';
			if ($order == "desc") {
				echo'checked=\'checked\' ';
			}
			echo' />Desc </h3><a href="javascript: submitForm(\'sort\', \'';
			echo $toolbarVarName.'\', \''.$imgSize.'\');" class="button smallButton" ';
			echo 'title="Click to sort the objects in this collection, according to the selected';
			echo ' criteria."><div>Sort</div></a>';
		}
		echo '&nbsp;|&nbsp;<a href="javascript:submitForm(\'default\', \'default\', \'';
		echo $imgSize.'\');" class="button mediumButton" title="Click here to save the';
		echo ' order of your collection." name="'.$saveOrderImgName.'" ><div>Save Order</div></a>';
	}

}

/***********************************************************************************
 *Function to print out the title of the collection and the buttons to choose the size of the tiles.
 ***********************************************************************************/
function echoTitle($collectionId, $collectionSize) {
	global $link,  $imgSize, $objInfo, $sortBy, $order ;

	$query = "Select * from BaseObject where id='".$collectionId."' ";
	$results = mysqli_query($link, $query);
	$row = mysqli_fetch_array($results);
	//$objectCount = getCollectionCount($collectionId);

	//$titleLength = strlen($row['name']);

	$checkedOn = ($_POST['postItToggle'] == "true") ? "checked='checked'" : "";
	$checkedOff = ($_POST['postItToggle'] == "false") ? "checked='checked'" : "";

	$iconCheckedOn = ($_POST['iconFlag'] == "true") ? "checked='checked'" : "";
	$iconCheckedOff = ($_POST['iconFlag'] == "false") ? "checked='checked'" : "";

	if (!$_POST['toggle'])
	$checkedOn = "checked='checked'";
	if (!$_POST['iconFlag'])
	$iconCheckedOn = "checked='checked'";

	echo '<table id="collectionTitle" width="100%" border="0" cellspacing="0" cellpadding="10">';
	echo '<tr><td class="bottom"><h1 align="center">'.$row['name'].'</h1>&nbsp;&nbsp;';
	echo '<h3>(<strong style="color:#ff0000;">['.$collectionSize.']</strong> images)</h3>';
	echo '</td></tr><tr><td id="imageSizeCtrlRow" class="bottom">';
	echo '<div class="carpe_horizontal_slider_display_combo"><h2>Image Size:</h2></div>';
	echo '<div class="carpe_horizontal_slider_display_combo">';
	echo '<div class="carpe_horizontal_slider_track" style="">';
	echo '<div class="carpe_slider_slit" style="">&nbsp;</div>';
	echo '<div class="carpe_slider" id="slider2" display="imageSlider" distance="100"';
	echo ' style="left:'.(($imgSize-80)*.2).'px; ">&nbsp;</div></div>';
	echo '<div class="carpe_slider_display_holder" style="">';
	echo '<input class="carpe_slider_display" name="testSlider" id="imageSlider" type="text"';
	echo 'from="80" to="580" value="'.$imgSize.'" valuecount="50" />';
	echo '<div class="safariBorder"></div></div></div>';
	echo '<h2>Post It:</h2><input type="radio" name="postItToggle" value="true" ';
	echo 'onclick="togglePostIt(true);" title="Click to toggle yellow post it notes on." ';
	echo $checkedOn.' />On&nbsp;&nbsp;<input type="radio" name="postItToggle" value="false"';
	echo ' onclick="javascript:togglePostIt(false);" title="Click to toggle yellow post';
	echo ' it notes off." '.$checkedOff.' />Off<h2>&nbsp;|&nbsp;';
	echo 'Icons:</h2>&nbsp;<input type="radio" name="iconToggle" value="true"';
	echo ' onclick="toggleIcons(true, \''.$imgSize.'\');" title="Click to toggle icons in each tile on." ';
	echo $iconCheckedOn.' />On&nbsp;&nbsp;<input type="radio" name="iconToggle" value="false"';
	echo ' onclick="toggleIcons(false, \''.$imgSize.'\');" title="Click to toggle icons in each tile off." ';
	echo $iconCheckedOff.' />Off ';

	echo'</td></tr><tr><td class="bottom sortToolbarPadding">';
	echoSortToolbar($collectionId, "top");
	echo '</td></tr></table>';
}

/***********************************************************************************
 *   Function will output a list of the collections that the user ownes for the group in which they are currently logged in as.  Clicking a collection will bring up that collection.
 ***********************************************************************************/
function outputUserCollections($userId, $groupId, $collectionId, $collectionType = "Collection") {
	global $config;
	echo '<div id="collectionList"><div class="CollapsiblePanelTab"';
	echo ' onmouseover="this.className=\'CollapsiblePanelTabHover\';"';
	echo ' onmouseout="this.className=\'CollapsiblePanelTab\'">My Collections</div>';
	echo '<div class="panelContent">';

	$collectionArray = getUserCollectionArray($userId, $groupId);
	$characterArray = getUserCharacterArray($userId, $groupId);
	$otuArray = getUserOtuArray($userId, $groupId);

	$color = '';

	if ($collectionArray) {
		echo '<table width="100%" border="0">';

		$arrayCount = count($collectionArray);
		for ($i = 0; $i < $arrayCount; $i++) {
			if ($collectionArray[$i]['dateToPublish'] < $collectionArray[$i]['now']) {
				$color = 'color:#999;';
			} else {
				$color = '';
			}

			$title = wordwrap($collectionArray[$i]['name'], 20, '<br />', 1);
			echo '<tr><td><a href="index.php?collectionId=';
			echo $collectionArray[$i]['id'].'" style="'.$color.' ';
			if ($collectionId == $collectionArray[$i]['id']){
				echo ' background-color:#92979b; color:#fff; font-weight:bold; ';
			}
			echo'">'.($i+1).')&nbsp;&nbsp;'.$title.'&nbsp;<strong>[';
			echo getCollectionCount($collectionArray[$i]['id']).']</strong></a></td></tr>';
		}
		echo '</table>';
	}

	echo '</div></div><br/><br/>';
	echo '<div id="characterList">';
	echo '<div class="CollapsiblePanelTab" onmouseover="this.className=\'CollapsiblePanelTabHover\';"';
	echo ' onmouseout="this.className=\'CollapsiblePanelTab\'">My Characters</div>';
	echo '<div class="panelContent">';

	if ($characterArray) {
		echo '<table width="100%" border="0">';

		$arrayCount = count($characterArray);
		for ($i = 0; $i < $arrayCount; $i++) {
			if ($characterArray[$i]['dateToPublish'] < $characterArray[$i]['now']) {
				$color = 'color:#999;';
			} else {
				$color = '';
			}
			echo '<tr><td><a href="index.php?collectionId='.$characterArray[$i]['id'].'" style="';
			echo $color.' ';
			if ($collectionId == $characterArray[$i]['id']) {
				echo ' background-color:#92979b; color:#fff; font-weight:bold; ';
			}
			echo'">'.($i+1).')&nbsp;&nbsp;'.$characterArray[$i]['name'].'&nbsp;<strong>[';
			echo PhyloCharacter::getImageIds($characterArray[$i]['id']).']</strong></a></td></tr>';
		}
		echo '</table>';
	}
	echo'</div></div><br /><br />';
	echo '<div id="otuList">';
	echo '<div class="CollapsiblePanelTab" onmouseover="this.className=\'CollapsiblePanelTabHover\';"';
	echo ' onmouseout="this.className=\'CollapsiblePanelTab\'">My OTUs</div>';
	echo '<div class="panelContent">';

	if ($otuArray) {
		echo '<table width="100%" border="0">';

		$arrayCount = count($otuArray);
		for ($i = 0; $i < $arrayCount; $i++) {
			if ($otuArray[$i]['dateToPublish'] < $otuArray[$i]['now']) {
				$color = 'color:#999;';
			} else {
				$color = '';
			}
			echo '<tr>';
			echo '<td><a href="index.php?collectionId='.$otuArray[$i]['id'].'" style="'.$color.' ';
			if ($collectionId == $otuArray[$i]['id']) {
				echo ' background-color:#92979b; color:#fff; font-weight:bold; ';
			}
			echo'">'.($i+1).')&nbsp;&nbsp;'.$otuArray[$i]['name'].'&nbsp;<strong>[';
			echo getCollectionCount($otuArray[$i]['id']).']</strong></a></td></tr>';
		}
		echo '</table>';
	}
	echo'</div></div> <br /><br />';

	echo '<center><a  href="'.$config->domain.'MyManager/index.php?tab=collectionTab"';
	echo ' class="button xlButton" title="Go to the Colletion Manager"><div>Collection';
	echo ' Manager</div></a></center>';
}

/***********************************************************************************
 *Function to output all the tools available for the collection.                 *
 ***********************************************************************************/
function outputTools($collectionId, $collectionType = "Collection") {
	global $config,  $imgSize, $collectionId, $objInfo, $loggedIn, $isMyCollection;
	$message = "Delete Selected Objects From Collection?";
	echo '<div class="collectionToolsContainer">';
	echoMenus();
	if ($loggedIn) {
		echo '<table width="100%" border="0"><tr><th>Tools</th></tr>';
		echo '<tr><td><a href="#" onmouseover="hideAllCollectionMenus();expandMenu(\'copyMenu\');';
		echo 'stopCollectionTime();" onmouseout="startCollectionTime();">Copy Checked Objects...</a></td>';
		echo '</tr><tr><td>';
		if (checkDateToPublish( $collectionId, $objInfo->getUserId(), $objInfo->getUserGroupId() )) {
			echo '<a href="#" onmouseover="hideAllCollectionMenus();expandMenu(\'moveMenu\');stopCollectionTime();" onmouseout="startCollectionTime();">Move Checked Objects...</a>';
		}
		echo '</td></tr><tr><td>';
		if (checkDateToPublish( $collectionId, $objInfo->getUserId(), $objInfo->getUserGroupId() )
		&& $collectionType=="Collection"){
			echo '<a href="#" onmouseover="hideAllCollectionMenus();expandMenu(\'labelMenu\');';
			echo 'stopCollectionTime();" onmouseout="startCollectionTime();">';
			echo 'Label Checked Objects...</a>';
		}
		echo '</td></tr><tr>';
		echo '<td><a href="javascript: submitForm(\'annotate\', \'default\', \'';
		echo $imgSize.'\');" >Annotate Checked Objects </a></td></tr>';
		echo '<tr><td>';
		if (checkDateToPublish( $collectionId, $objInfo->getUserId(), $objInfo->getUserGroupId() )
		&& $collectionType != "Otu") {
			echo '<a href="javascript: setThumb(\''.$collectionId.'\');" >Set Collection Thumbnail</a>';
		}
		echo '</td></tr>';

		if ($collectionType == "MbCharacter" &&  $isMyCollection && checkDateToPublish( $collectionId, $objInfo->getUserId(), $objInfo->getUserGroupId() )) {
			echo '<tr><td><a href="javascript: showCreateState();" >Create State </a></td>';
			echo '</tr><tr><td><a href="'.$config->domain.'Phylogenetics/Character/editCharacter.php?id=';
			echo $collectionId.'" >Edit Character</a></td></tr>';
		}

		if ($collectionType == "Otu") {
			echo '<tr><td><a href="'.$config->domain.'Phylogenetics/Otu/editOtu.php?id=';
			echo $collectionId.'" >Edit OTU</a></td></tr>';
		}
		echo'<tr><td>';
		if (checkDateToPublish( $collectionId, $objInfo->getUserId(), $objInfo->getUserGroupId() )) {
			echo '<a href="javascript:confirmAction(\'delete\', \'default\', \''.$imgSize.'\');" title="Deletes the checked objects from the collection."><b>Delete Checked Objects</b></a>';
		}
		echo '</td></tr>';
		echo '<tr><td>&nbsp;</td></tr></table>';
	}
	echo'</div>';
}

/***********************************************************************************
 *Function that will echo out the hidden mouse over menus for the tools.         *
 ***********************************************************************************/
function echoMenus() {
	global $objInfo, $collectionId, $imgSize;
	$collectionArray = getUserCollectionArray($objInfo->getUserId(), $objInfo->getUserGroupId());
	$arrayCount = count($collectionArray);

	$stateValue = getNewStateValue($collectionId);

	$message = "Label Checked Objects?";

	echo '<div id="copyMenu" class="collectionMenu collectionListSubMenu" style="display:none"';
	echo ' onmouseover="stopCollectionTime();" onmouseout="startCollectionTime();">';
	echo '<table><tr><th>Copy to...</th></tr>';
	echo '<tr><td><a href="javascript:submitForm(\'create\', \'default\', \'';
	echo $imgSize.'\');"><b class="red">***New Collection***</b></a></td>';
	echo '</tr><tr><td>&nbsp;</td></tr>';
	for ($i=0;$i<$arrayCount;$i++) {
		if ($collectionArray[$i]['id'] != $collectionId) {
			if ($collectionArray[$i]['dateToPublish'] > $collectionArray[$i]['now']) {
				echo '<tr><td><a href="javascript:submitForm(\'copy\', \''.$collectionArray[$i]['id'];
				echo '\', \''.$imgSize.'\');">'.$collectionArray[$i]['name'].'&nbsp;';
				echo '<strong>['.getCollectionCount($collectionArray[$i]['id']);
				echo ']</strong></a></td></tr>';
			}
		}
	}
	echo '<tr><td>&nbsp;</td></tr></table></div>';
	echo '<div id="moveMenu" class="collectionMenu collectionListSubMenu" style="display:none"';
	echo ' onmouseover="stopCollectionTime();" onmouseout="startCollectionTime();">';
	echo '<table><tr><th>Move to...</th></tr><tr>';
	echo '<td><a href="javascript:submitForm(\'create\', \'move\', \'';
	echo $imgSize.'\');"><b class="red">***New Collection***</b></a></td>';
	echo '</tr><tr><td>&nbsp;</td></tr>';
	for ($i=0;$i<$arrayCount;$i++) {
		if ($collectionArray[$i]['id'] != $collectionId) {
			if ($collectionArray[$i]['dateToPublish'] > $collectionArray[$i]['now']) {
				echo '<tr><td><a href="javascript:submitForm(\'move\', \'';
				echo $collectionArray[$i]['id'].'\', \''.$imgSize.'\');">';
				echo $collectionArray[$i]['name'].'&nbsp;<strong>[';
				echo getCollectionCount($collectionArray[$i]['id']);
				echo ']</strong></a></td></tr>';
			}
		}
	}
	echo '<tr><td>&nbsp;</td></tr></table></div>';
	echo '<div id="labelMenu" class="collectionMenu" style="display:none" onmouseover="stopCollectionTime();"';
	echo ' onmouseout="startCollectionTime();">';
	echo '<table><tr><th>Label Objects...</th></tr>';
	echo '<tr><td><a href="javascript:confirmAction(\'label\', \'taxon\', \'';
	echo $imgSize.'\');">By Taxon Name</a></td></tr>';
	echo '<tr><td><a href="javascript:confirmAction(\'label\', \'taxonSpecies\', \'';
	echo $imgSize.'\');">By Lowest Determined Name</a></td></tr>';
	echo '<tr><td><a href="javascript:confirmAction(\'label\', \'specimenId\', \'';
	echo $imgSize.'\');">By Specimen Id</a></td></tr>';
	echo '<tr><td><a href="javascript:confirmAction(\'label\', \'specimenPart\', \'';
	echo $imgSize.'\');">By Specimen Part</a></td></tr>';
	echo '<tr><td><a href="javascript:confirmAction(\'label\', \'viewAngle\', \'';
	echo $imgSize.'\');">By View Angle</a></td></tr></table></div>';
	echo '<div id="createStatePopup" class="collectionMenu" style="display:none; " >';
	echo '<h1>Add Character State</h1><br />';
	echo '<table><tr><td valign="bottom" width="200"><h2>State Title:&nbsp;';
	echo '</h2></td> <td><input id="stateTitleId" type="text" name="stateTitle"';
	echo ' onkeypress="return checkEnter(event);" value="" /></td></tr>';
	echo '<tr><td valign="bottom" width="200"><h2>State Value:&nbsp;</h2></td>';
	echo '<td><b>'.$stateValue.'</b>';
	echo '<input type="hidden" name="stateValue" value="'.$stateValue.'" /></td></tr>';
	echo '<tr><td colspan="2" valign="bottom">';
	echo '<a href="#" class="button smallButton right" ';
	echo 'onclick="document.getElementById(\'createStatePopup\').style.display=\'none\';">';
	echo '<div>Cancel</div></a>';
	echo '<a href="javascript: createState();" class="button smallButton right">';
	echo '<div>Submit</div></a></td></tr></table></div>';
}

/***********************************************************************************
 *Function that will update the title of a single object in a collection.        *
 ***********************************************************************************/
function updateTitle($objectId, $collectionId, $title) {
	global $link;

	//echo $title.'<br />';
	$titleInsert = str_replace("'", "''",str_replace("\\", "", $title));

	$sql = 'UPDATE CollectionObjects SET objectTitle = \''.$titleInsert
	.'\' WHERE collectionId = '.$collectionId.' AND objectId = '.$objectId;
	//echo $sql;
	$result = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	if ($result) return TRUE;
	return FALSE;
}

/***********************************************************************************
 *Function that will update the title of a single object in a collection.        *
 ***********************************************************************************/
function updateTitle_phylo($objectId, $phyloChar, $title) {
	global $link;

	$stateId = $phyloChar->getStateForImage($objectId);

	//echo $title.'<br />';
	$titleInsert = str_replace("'", "''",str_replace("\\", "", $title));

	$sql = 'UPDATE CollectionObjects SET objectTitle = \''.$titleInsert
	.'\' WHERE collectionId = '.$stateId.' AND objectId = '.$objectId;
	//echo $sql;
	$result = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	if ($result)
	return TRUE;
	else
	return FALSE;
}


function getFieldOrder($returnString,$field) {
	$counter = 0;
	$index = 0 ;
	$strLength = strlen($returnString);
	//echo $strLength.'<br />'.$returnString;
	$stringPosition = strpos($returnString,$field,0);
	while ($stringPosition && $counter < 100 )
	{ $counter++;
	$stringPosition = $stringPosition + 1;
	$imageArray[$index++] = ExtractId($returnString,$stringPosition);
	$stringPosition = strpos($returnString,"ImageId:[",$stringPosition);
	}
	return $imageArray;
}

function ExtractId( $inString,$position1) {
	$index1 = $position1+8;
	$index2 = strpos($inString,']',$index1);
	$length =($index2-$index1);
	$imageId = substr($inString,$index1,$length);
	return $imageId;
}

function reOrderCollection($collectionId,$imageArray) {
	//var_dump($imageArray);
	global $link;

	$numRecords = count($imageArray);

	$sql = 'SELECT objectId FROM CollectionObjects WHERE collectionId = \''.$collectionId.'\' ';
	$results = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	if ($results) {
		//echo $sql;
		$num = mysqli_num_rows($results);
		for ($i = 0; $i < $num; $i++)
		$array[$i] = mysqli_fetch_array($results);
	}
	//echo $numRecords.'<br />'.$num.'<br />';
	$value = 1;
	for($index=0; $index < $numRecords; $index++) {
		//echo 'imageArray-'.$imageArray[$index].'<br />array-'.$array[$index]['objectId'].'<br />';
		for ($j = 0; $j < $num; $j++) {
			if ($imageArray[$index] == $array[$j]['objectId']) {
				$query = "UPDATE CollectionObjects set objectOrder = ".$value." where collectionId='"
				.$collectionId."' and objectId='".$imageArray[$index]."' ";
				$results = mysqli_query($link, $query);
				$value++;
				//echo $query;
			}
		}
	}

	$sql = 'UPDATE BaseObject SET dateLastModified = NOW() WHERE id = \''.$collectionId.'\' ';
	mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
}

/**
 * Purpose of routine is to determine whether an operation is allowed.
 * @param $collectionId
 * @param $userId
 * @param $groupId
 * @return unknown_type
 */
function checkDateToPublish ($collectionId, $userId, $groupId, $operation = "edit") {
	return checkAuthorization($collectionId, $userId, $groupId, $operation);
}

function isMyCollection($collectionId, $userId) {
	global $link;
	$sql ='SELECT BaseObject.userId FROM BaseObject WHERE BaseObject.id = '.$collectionId;
	$result = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	if ($result) {
		$row = mysqli_fetch_array($result);
		if ($row['userId'] == $userId) return TRUE;
	}
	return FALSE;
}

function getStyleArray ($objectArray, $size, $imgType, $icons) {
	
	$objectId = $objectArray['objectId'];
	//echo "<pre>";var_dump($objectArray);echo "</pre>";die();
	// Try to get an image URl and size for the object
	$thumbId = getObjectImageId($objectId);
	if ($thumbId != null && $thumbId != '') {
		// object has a thumbnail
		$url = getImageUrl($thumbId, $imgType);
	} else {
		// no thumbUrl, use appropriate default
		$url = getDefaultObjectTypeUrl($objectArray['objectTypeId']);
	}
	$returnArray = array('imgUrl' => $url, 'thumbId'=>$thumbId);
	list($fileSize, $width, $height, $imgType) = getRemoteImageSize($thumbId, $imgType);
	if ($size < 100) {
		if ($width >= $height) {
			$returnArray['imgSize'] = ' width="'.$size.'" ';
		} else {
			$returnArray['imgSize'] = ' height="'.($size-5).'" ';
		}

		if ($icons === "false") {
			$liWidth = $size+2;
			$liHeight = $size+35;
			$liStyleString = 'font-size:9px; width: '.$liWidth.'px; height: '.$liHeight.'px; padding-top:1px; ';
		} else {
			$liWidth = $size+20;
			$liHeight = $size+60;
			$liStyleString = 'width: '.$liWidth.'px; height: '.$liHeight.'px; padding-top:1px; ';
		}

		$returnArray['liWidth'] = $liWidth;
		$returnArray['liStyle'] = $liStyleString;

		return $returnArray;
	} else {
		if ($width >= $height) {
			$returnArray['imgSize'] = ' width="'.$size.'" ';
		} else {
			$returnArray['imgSize'] = ' height="'.$size.'" ';
		}

		if ($icons === "false") {
			$liWidth = $size+2;
			$liHeight = $size+35;
			$liStyleString = 'width: '.$liWidth.'px; height: '.$liHeight.'px; ';
		} else {
			$liWidth = $size+25;
			$liHeight = $size+45;;
			$liStyleString = 'width: '.$liWidth.'px; height: '.$liHeight.'px; ';
		}

		$returnArray['liWidth'] = $liWidth;
		$liStyleString .= ' font-size:12px; ';
		$returnArray['liStyle'] = $liStyleString;

		return $returnArray;
	}
}
?>
