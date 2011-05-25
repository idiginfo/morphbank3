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

include_once('mbMenu_data.php');
include_once('tsnFunctions.php');
include_once('urlFunctions.inc.php');
require_once('Annotation/annotateFunctions.php');

$popUrl = (isset($_GET['pop'])) ? "/Show/?pop=Yes&amp;id=" : "/?id=";

$specimenId = '';
$viewId = '';
$tsnId = '';
$locationId = '';

$specimenTableFields = array(
array('field' => 'id', 'label' => 'Specimen id: ', 'width' => 10, 'display' => true),
array('field' => 'basisOfRecordId', 'label' => 'Basis of record: ',
		'relatedTable' => true, 'width' => 40, 'display' => true), 
array('field' => 'sexId', 'label' => 'Sex: ', 'relatedTable' => true, 'width' => 10, 'display' => true),
array('field' => 'formId', 'label' => 'Form: ', 'relatedTable' => true, 'width' => 20,
		'display' => true), 
array('field' => 'developmentalStageId', 'label' => 'Stage: ', 'relatedTable' => true,
		'width' => 30, 'display' => true), 
array('field' => 'preparationType', 'label' => 'Preparation type: ', 'width' => 30, 'display' => false),
array('field' => 'individualCount', 'label' => 'Individual Count: ', 'width' => 30, 'display' => false),
array('field' => 'institutionCode', 'label' => 'Institution code: ', 'width' => 30, 'display' => false),
array('field' => 'collectionCode', 'label' => 'Collection code: ', 'width' => 30, 'display' => false),
array('field' => 'catalogNumber', 'label' => 'Catalog number: ', 'width' => 30, 'display' => true),
array('field' => 'previousCatalogNumber', 'label' => 'Previous catalog number: ', 'width' => 30,
		'display' => false), array('field' => 'relatedCatalogItem', 
		'label' => 'Related catalog item: ', 'width' => 30, 'display' => false), 
array('field' => 'relationshipType', 'label' => 'Relationship type: ', 'width' => 30, 'display' => false),
array('field' => 'collectionNumber', 'label' => 'Collection number: ', 'width' => 30, 'display' => false),
array('field' => 'collectorName', 'label' => 'Collector: ', 'width' => 30, 'display' => true),
array('field' => 'dateCollected', 'label' => 'Date collected: ', 'width' => 30, 'display' => true),
array('field' => 'relationshipType', 'label' => 'Relationship type: ', 'width' => 30, 'display' => false),
array('field' => 'name', 'label' => 'Determined by: ', 'width' => 30, 'display' => false),
array('field' => 'dateIdentified', 'label' => 'Date identified: ', 'width' => 30, 'display' => false),
array('field' => 'typeStatusId', 'label' => 'Type status: ', 'relatedTable' => true,
		'width' => 30, 'display' => false), 
array('field' => 'Notes', 'label' => 'Notes: ', 'width' => 30, 'display' => false)
);

$viewTableFields = array(
array('field' => 'id', 'label' => 'View id: ', 'width' => 10, 'display' => true),
array('field' => 'specimenPartId', 'relatedTable' => true, 'label' => 'Specimen part: ',
		'width' => 10, 'display' => true), 
array('field' => 'viewAngleId', 'relatedTable' => true, 'label' => 'Angle: ', 'width' => 10,
		'display' => true), 
array('field' => 'imagingTechniqueId', 'relatedTable' => true, 'label' => 'Technique: ',
		'width' => 10, 'display' => true), 
array('field' => 'imagingPreparationTechniqueId', 'relatedTable' => true, 'label' => 'Preparation: ',
		'width' => 10, 'display' => true), 
array('field' => 'developmentalStageId', 'label' => 'Stage: ', 'relatedTable' => true,
		'width' => 30, 'display' => false), 
array('field' => 'sexId', 'label' => 'Sex: ', 'relatedTable' => true, 'width' => 20,
		'display' => false), 
array('field' => 'formId', 'label' => 'Form: ', 'relatedTable' => true, 'width' => 20,
		'display' => false), 
array('field' => 'isStandardView', 'label' => 'Standard view: ', 'boolean' => true,
		'width' => 30, 'display' => false)
);

$locationTableFields = array(
array('field' => 'id', 'label' => 'Locality Id: ', 'width' => 10, 'display' => true),
array('field' => 'continentOceanCode', 'relatedTable' => true, 'label' => 'Continent ocean: ',
		'width' => 10, 'display' => true), 
array('field' => 'countryCode', 'relatedTable' => true, 'label' => 'Country: ', 'width' => 10,
		'display' => true), 
array('field' => 'locality', 'label' => 'Locality: ', 'width' => 10, 'display' => true),
array('field3' => 'latitude', 'field4' => 'Latitude: ', 'width' => 10, 'display' => true),
array('field3' => 'longitude', 'field4' => 'Longitude: ', 'width' => 10, 'display' => true),
array('field' => 'coordinatePrecision', 'label' => 'Coordinate precision: ', 'width' => 30,
		'display' => false), 
array('field' => 'minimumElevation', 'label' => 'Min. elevation: ', 'width' => 20, 'display' => false),
array('field' => 'maximumElevation', 'label' => 'Max. elevation: ', 'width' => 20, 'display' => false),
array('field' => 'minimumDepth', 'label' => 'Minimum depth: ', 'width' => 30, 'display' => false),
array('field' => 'maximumDepth', 'label' => 'Maximum depth: ', 'width' => 30, 'display' => false)
);

// simply echos the following contents to the web browser.
// This helps keep the main scripts simpler to read.

$imgRecord = getImageData($id);
$specimenRecord = getSpecimenData($specimenId);

$imgUrl = getImageUrl($id, 'jpg');

$width = $imgRecord['imageWidth'];
$height = $imgRecord['imageHeight'];

if (isset($_GET['pop'])) {
	echo '<div class="popContainer" style="width:800px">';
} else {
	echo '<div class="mainGenericContainer" style="width:800px">';
}
echo '<table border="0" width="100%"><tr><td><h2>Image Record: ['.$id.']&nbsp;&nbsp;';
$tsnName = getTsnName($tsnId);
echo printTsnNameLinks($tsnName);
echo '</h2></td><td align="right">';
if ($imgArray['serverLogo']) {
	echo '<img src="'.$imgArray['serverLogo'].'" height="30" alt="Logo" />';
} else {
	echo '<img src="/style/webImages/mbLogo.png" height="30" />';
}
echo '</td></tr></table>
		<table class="topBlueBorder" width="100%">
		<tr><td class="firstColumn" valign="top" width="50%">';
showImageData($id);
echo '</td><td valign="top" width="50%">';
global $imageType;
//TODO check for tif file generate if not present.
//if ($imageType == 'DNG'){
//	echo '<a href="javascript: alert(\'FSI Viewer is not available for this image at present. ';
//	echo '\nContact Morphbank Administrators to request creation of the viewable image for id=';
//	echo $id.'\');" title="See image">';
//}

echo showMediumImage($id);
//echo showMorphsterFrame($id);

echo '</td></tr></table><table class="blueBorder" width="100%">
	<tr valign="top"><td class="firstColumn" width="50%">';
showSpecimenData($specimenId, $imgId);
echo '</td><td valign="top" width="50%">';
showLocality($locationId);
echo '</td></tr></table><table class="blueBorder" width="100%">
	<tr valign="top"><td class="firstColumn" width="50%">';
showDeterminationData();
echo '</td><td width="50%">';
showExternalLinks($id);
echo '</td></tr></table>
	<table class="blueBorder" width="100%">
	<tr valign="top">
	<td class="firstColumn imageAnnotation" valign="top" width="50%">';
showDeterminationAnnotation($specimenId, false, true);
echo '</td>	
	<td class="imageAnnotation" valign="top" width="50%">';
showImageAnnotations($id, true);
echo '</td></tr></table>';
echo '<table class="bottomBlueBorder" width="100%">
	<tr valign="top">
	<td class="imageAnnotation" valign="top">';
$array = array(array('objectid' => $id)); // $array[0]['objectid'] matches objArray for function
displayRelatedAnnotations($array, true);
echo '</td></tr></table>';
showRelatedObjects($id);
// popContainer
echo '</div>';

//================================
function getImageData($imgId) {
	global $link;
	if ($imgId == null)
	return false;
	$sql = "SELECT *, IF(ISNULL(magnification), 'NULL', magnification) as "
	."magnificationString FROM Image WHERE Image.id=$imgId";
	$result = mysqli_query($link, $sql);

	if (!$result) return false;
	$imgRecord = mysqli_fetch_array($result);
	global $specimenId, $viewId;
	$specimenId = $imgRecord['specimenId'];
	$viewId = $imgRecord['viewId'];
	mysqli_free_result($result);
	return $imgRecord;
}

function getViewData($viewId) {
	global $link;
	$sql = 'SELECT View.*, '.'imagingTechnique as imagingTechniqueIdName, '
	.'imagingPreparationTechnique as imagingPreparationTechniqueIdName, '
	.'specimenPart as specimenPartIdName, '
	.'viewAngle as viewAngleIdName, '
	.'Sex as sexIdName, '
	.'Form as formIdName, '
	.'developmentalStage as developmentalStageIdName '
	.'FROM View '
	.'WHERE View.id = '.$viewId.' ';
	//echo $sql;
	//TODO change to MDB2, must also change field names to lowercase
	$result = mysqli_query($link, $sql);
	if (!$result)
	return false;
	$viewRecord = mysqli_fetch_array($result);
	mysqli_free_result($result);
	return $viewRecord;
}

function showViewData($viewId, $imgId) {
	$viewRecord = getViewData($viewId);
	if (!$viewRecord) {
		echo '<div class="error">No view found for this image';
		echo "<br/>Image id: $imgId and view id: $viewId</div>";
		return;
	}
	// print out the labels
	global $viewTableFields;
	global $config, $popUrl;
	$arraySize = count($viewTableFields);
	echo '<table width="100%" border="0">';
	for ($i = 0; $i < $arraySize; $i++) {
		$viewFields = $viewTableFields[$i];
		if ($viewFields['display']) {
			echo '<tr><th>'.$viewFields['label'].'</th><td align="left" width="65%">';
			if ($viewFields['field'] == 'id') {
				echo '<a href="'.$popUrl.$viewId.'">'
					.$viewRecord[$viewFields['field']].'</a>';
			} else if ($viewFields['relatedTable']) {
				echo $viewRecord[$viewFields['field'].'Name'];
			} else if ($viewFields['boolean']) {
				if ($viewRecord[$viewFields['field']] == 1) echo 'YES';
				else echo 'NO';
			} else  {
				echo $viewRecord[$viewFields['field']];
			}
			echo '</td>
		</tr>';
		}
	}
	echo '</table>';
}

function showImageData($imgId) {
	// Base object info
	$baseObjectArray = getBaseObjectData($imgId);
	if (!$baseObjectArray) {
		echo '<div class="error">Image Id not found</div>';
		exit();
	}
	global $config, $popUrl;
	showBaseObjectData($baseObjectArray);
	echo '<hr align="center" width="90%" />';
	// Image info
	global $imgRecord;
	if (!$imgRecord) {
		echo "<div class=\"error\">Image not found for id: $imgId</div>";
		exit();
	}
	echo '<table width="100%" border="0">
	<!--tr><th>Access #:</th>
		<td align="left"width="65%">'.$imgRecord['accessNum'].'</td>
	</tr-->
	<tr><th>Magnification:</th>
		<td align="left"width="65%">'.$imgRecord['magnificationString'].'</td>
	</tr>
	<tr><th>Dimension (px):</th>
		<td align="left"width="65%">'.$imgRecord['imageWidth'].'x'.$imgRecord['imageHeight'].'</td>
	</tr>
	<tr><th>Resolution (PPI):</th>
		<td align="left"width="65%">'.$imgRecord['resolution'].'</td>
	</tr>
	<tr><th>Submitted as:</th>
		<td align="left"width="65%">'.$imgRecord['imageType'].'</td>
	</tr>
	<tr><th>Original File Name:</th>
		<td align="left"width="65%">'.wordwrap($imgRecord['originalFileName'], 30, "<br />", true).'</td>
	</tr>
	<tr><th>Photographer:</th>
		<td align="left"width="65%">'.wordwrap($imgRecord['photographer'], 30, "<br />", true).'</td>
	</tr>
	</table>';
	echo '<hr align="center" width="90%" />';
	//View info
	global $viewId, $imageType;
	$imageType = strtolower($imgRecord['imageType']);
	showViewData($viewId, $imgId);
	echo '<hr align="center" width="90%" />';
	// Download
	$originalImageType = ($imageType!='jpg'?$imageType:'jpeg');
	$origUrl = getImageUrl($imgId, $originalImageType);
	$fullJpegUrl = getImageUrl($imgId,'jpeg');
	$jpgUrl = getImageUrl($imgId,'jpg');
	
	//TODO get rid of file paths in calculating file sizes
	//TODO add jpg file link for download
	
	echo '<table width="100%" border="0">
	<tr>
		<th>Download:</th>
			<td align="left" width="65%">
		 		 <a href="'.$origUrl.'">original ('.$originalImageType.')</a> ('
		 		 .human_file_size(getFileSizeUrl($imgId, $originalImageType)).')<br/>'
		 		 .'<a href="'.$fullJpegUrl.'">full sized jpeg</a> ('
		 		 .human_file_size(getFileSizeUrl($imgId,'jpeg')).')'
		 		 .'<a href="'.$jpgUrl.'">medium sized jpeg</a> ('
		 		 .human_file_size(getFileSizeUrl($imgId,'jpg')).')'
		 		 .'</td>
	</tr>
	<tr>
		<th>Copyright:</th>
			 	<td>'.$imgRecord['copyrightText'].'</td>
	</tr>
	<tr>
		<th>License:</th>
			 <td>'.$imgRecord['creativeCommons'].'</td>
	</tr>
	</table>';
}

function getSpecimenData($specimenId) {
	global $link;
	$sql = 'SELECT Specimen.id as id, basisOfRecordId, Specimen.sex AS sexId, '
	.'Specimen.form AS formId, Specimen.developmentalStage AS developmentalStageId, '
	.'Specimen.collectorName, Specimen.catalogNumber, '
	.'DATE_FORMAT(Specimen.dateCollected, "%Y-%m-%d") AS dateCollected, '
	.'tsnId, typeStatus, Specimen.name, DATE_FORMAT(dateIdentified, "%Y-%m-%d") AS dateIdentified, '
	.'localityId, '.'BasisOfRecord.description as basisOfRecordIdName '
	.'FROM Specimen '.'LEFT JOIN BasisOfRecord ON Specimen.basisOfRecordId = BasisOfRecord.name '
	.'WHERE Specimen.id = "'.$specimenId.'";';
	//echo $sql;
	$result = mysqli_query($link, $sql) or die(mysqli_error($link));
	if (!$result) return false;
	$specimenRecord = mysqli_fetch_array($result);
	global $locationId, $tsnId;
	$locationId = $specimenRecord['localityId'];
	$tsnId = $specimenRecord['tsnId'];
	mysqli_free_result($result);
	return $specimenRecord;
}

function showSpecimenData($specimenId, $imgId) {
	global $specimenRecord;
	if (!$specimenRecord) {
		echo "<div class=\"error\">No specimen for image $imgId. Specimen id is $specimenId</div>";
		echo "<div>Soon we'll be able to find the specimen for you!</div>";
		return;
	}
	echo '<h3>Specimen</h3><br/>';

	// print out the labels
	global $specimenTableFields;
	global $config, $popUrl;
	$arraySize = count($specimenTableFields);
	echo '<table width="100%" border="0">';
	for ($i = 0; $i < $arraySize; $i++) {
		$specimenFields = $specimenTableFields[$i];
		if ($specimenFields['display']) {
			echo '<tr><th>'.$specimenFields['label'].'</th><td align="left" width="65%">';
			if ($specimenRecord[$specimenFields['relatedTable']]) {
				if ($specimenRecord[$specimenFields['field'].'Name']) {
					echo '['.$specimenRecord[$specimenFields['field']].'] - '
					.$specimenRecord[$specimenFields['field'].'Name'];
				} else {
					echo ''.$specimenRecord[$specimenFields['field']];
				}
			} else {
				if ($specimenFields['field'] == 'id') {
					echo '<a href="'.$popUrl.$specimenId.'">'
					.$specimenRecord[$specimenFields['field']]
					.'</a>';
				} else {
					echo $specimenRecord[$specimenFields['field']];
				}
			}
			echo '</td></tr>';
		}
	}
	echo '</table>';
}

function showDeterminationData() {
	global $tsnId;
	echo '<h3>Determination</h3><br/>';
	showTsnData($tsnId);
}

function  showMorphsterFrame($id) {
	$tag = "<p><h3>Morphster Annotation</h3><br/><p>";
	$tag .= "<iframe src='http://services.morphbank.net/mbd/request?id=$id&method=morphster' width='100%'/></iframe></p>";
	return $tag;
}
