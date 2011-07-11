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

include_once ('urlFunctions.inc.php');

$specimenArray = getSpecimenData($id);	// all the data for the specimen show
$baseObjectArray = getBaseObjectData($id);	// all the base object data
$specimenTableFields = returnSpecimenArray(); // table fields, labels and other info for the specimen section to use as indexes in the $specimenArray
$collectionTableFields = returnCollectionArray(); // table fields, labels and other possible info for the collection section to use as indexes in the $specimenArray
$localityTableFields = returnLocalityArray(); // table fields, labels and other possible info for the locality section to use as indexes in the $specimenArray
$imageArrayOfSpecimen = getImagesOfSpecimen($id); // image ids of related images. False if no images.
$imgCount = count($imageArrayOfSpecimen);
$borderClassName = $imgCount >= 2 ? "blueBorder" : "bottomBlueBorder";
$popUrl = (isset($_GET['pop'])) ? "/Show/?pop=Yes&amp;id=" : "/?id=";
//$imageId = $specimenArray['standardImageId'];

$imageId = getObjectImageId($id);
$imgUrl = getImageUrl($imageId, 'jpg');

//	Include tsnFuntion file for needed functions
include_once( 'tsnFunctions.php');
// Output the content of the main frame
if (isset($_GET['pop'])){
	echo '<div class="popContainer">';
} else {
	echo '<div class="mainGenericContainer">';
}

echo "<h2>Specimen Record: [$id]&nbsp;";
$tsnName = getTsnName ($specimenArray['tsnId']);
echo printTsnNameLinks($tsnName);
echo '</h2>
		<table class="topBlueBorder" width="100%" cellspacing="0" cellpadding="2">
		<tr><td class="firstColumnNoPadding" width="50%" valign="top">						
		<div class="popCellPadding" >';
showBaseObjectData($baseObjectArray);
echo '<hr align="left" width="90%" />';
echo showDataTable($specimenTableFields, $specimenArray);

showSpecimenData();
echo '<br /><br /></div>';
echo '</td><td width="50%">';

echo showMediumImage($imageId);

if (empty($imageId)){
	echo '<br/>No image for this specimen';
} else {
	echo '<a href="'.showUrl($imageId).'" ><p>Go to image detail page</p></a>';
}

echo '</td></tr></table>';
echo '<table class="blueBorder" width="100%" cellspacing="0" cellpadding="2">
		<tr><td class="firstColumn" width="50%" valign="top">';
echo showDataTable($collectionTableFields, $specimenArray, 'Collection');


echo '</td><td width="50%">';
showLocality($specimenArray['locationId']);

echo '</td></tr></table>
	<table class="blueBorder" width="100%" cellspacing="0" cellpadding="2">
	<tr><td class="firstColumn" width="50%">
	<h3>Determination</h3><br /><br />';
showTsnData($specimenArray['tsnId']);
echo '</td><td width="50%" valign="top">';
showDeterminationAnnotation($id);
echo '</td></tr></table>';
echo '<table class="blueBorder" width="100%" cellspacing="0" cellpadding="2">
		<tr><td class="firstColumn" width="50%" valign="top">';
showExternalLinks($id);
echo '</td><td width="50%" valign="top">
		<h3>Other Annotations</h3><br /><br /><br /><br />
		<table><tr><td></td></tr></table></td></tr>
		</table>
		<table class="'.$borderClassName.'" width="100%" cellspacing="0" cellpadding="2">
			<tr><td><h3>Notes</h3><br /><br />';
echo $specimenArray['notes'].'</td></tr></table>';
showImagesOfSpecimen($imageArrayOfSpecimen);
showRelatedObjects($id);
echo '	  </div>'; // popContainer

/**
 *	Function to return an array of image id's where the specimen has more than one image associated with it
 */
function getImagesOfSpecimen($id) {
	global $link;
	$sql = 'SELECT Image.id AS imageId FROM Image INNER JOIN Specimen ON Image.specimenId = Specimen.id WHERE Image.specimenId = '.$id.' ';
	$results = mysqli_query($link, $sql) or die(mysqli_error($link));
	if ($results) {
		for ($i = 0; $i < mysqli_num_rows($results); $i++) {
			$array[$i] = mysqli_fetch_array($results);
			$array[$i] = $array[$i]['imageId'];
		}
		return $array;
	} else {
		return FALSE;
	}
}

function showSpecimenData() {
	global $specimenArray, $specimenTableFields;
	$size = count($specimenTableFields);
	echo '	<table>';
	for ($i=0; $i < $size; $i++) {
		showArrayItem($specimenTableFields,$specimenArray);
	}
	echo '	</table>';
}

//***************************************************
//modified to truncate lat and long to 5 decimal places, if longer than 5 does not add.  uses includes/showFunctions.inc.php
// k.seltmann july 2008
//***************************************************
function showLocalityData() {
	global $specimenArray, $localityTableFields;
	$size = count($localityTableFields);
	echo '	<table>';
	showIdWithEditLink('Locality',$localityId);
	for ($i=0; $i < $size; $i++) {
		if($localityTableFields[$i]['display']) {
			echo '<tr>' ;
			if(!$localityTableFields[$i]['elevation'] && !$localityTableFields[$i]['depth'])
			echo '<th>'.$localityTableFields[$i]['label'].'</th>';
			if ($localityTableFields[$i]['href']){
				echo '<td><a href="'.$popUrl.$specimenArray[$localityTableFields[$i]['field']].'">'.$specimenArray[$localityTableFields[$i]['field']].'</a></td>';
			} elseif ($localityTableFields[$i]['elevation']) {
				if ($specimenArray[$localityTableFields[$i]['field1']] == '' || $specimenArray[$localityTableFields[$i]['field2']] == '' ) {
					echo '<tr>
								<th>'.$localityTableFields[$i]['label'].'</th><td> '.$specimenArray[$localityTableFields[$i]['field1']].'  '.$specimenArray[$localityTableFields[$i]['field2']].' </td>
							  </tr>';
				} else {
					echo '<tr>
									<th>'.$localityTableFields[$i]['label'].'</th><td> '.$specimenArray[$localityTableFields[$i]['field1']].' &nbsp;-&nbsp; '.$specimenArray[$localityTableFields[$i]['field2']].' </td>
							  </tr>';
				}
			} 	elseif ($localityTableFields[$i]['depth']) {
				if ($specimenArray[$localityTableFields[$i]['field1']] == '' || $specimenArray[$localityTableFields[$i]['field2']] == '' ) {
					echo '<tr>
								<th>'.$localityTableFields[$i]['label'].'</th><td> '.$specimenArray[$localityTableFields[$i]['field1']].'  '.$specimenArray[$localityTableFields[$i]['field2']].'</td>
							  </tr>';
				} else {
					echo '<tr>
								<th>'.$localityTableFields[$i]['label'].'</th><td> '. mb_convert_encoding($specimenArray[$localityTableFields[$i]['field1']].' &nbsp;-&nbsp; '.$specimenArray[$localityTableFields[$i]['field2']],"HTML-ENTITIES","UTF-8") .'</td>
							  </tr>';
				}
			} elseif ($localityTableFields[$i]['latitude'] || $localityTableFields[$i]['longitude']) {
				echo '<tr>
									<th>'.$localityTableFields[$i]['field4'].'</th><td> ' .truncateValue($specimenArray[$localityTableFields[$i]['field3']]). '</td>
								 	 </tr>';
			} else {
				echo '<td>'. mb_convert_encoding($specimenArray[$localityTableFields[$i]['field']],"HTML-ENTITIES","UTF-8") .'</td>';
			}
			echo '			</tr>';
		}
	}
	echo '	</table>';
}

function showImagesOfSpecimen($imageArrayOfSpecimen) {
	global $popUrl, $config, $specimenArray;
	if ($imageArrayOfSpecimen) {
		$imgCount = count($imageArrayOfSpecimen);
		if ($imgCount > 1) {
			$thumbLimit = ($imgCount < 25) ? $imgCount : 25;
			echo '			<table class="bottomBlueBorder" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="bottom">
						<h3>Additional Images for specimen: '.$specimenArray['specimenId'].'</h3><br /><br />';
			echo 'Showing '.$thumbLimit.' of '.$imgCount.' Images: ';
			if ($_GET['pop']){
				$opener = ($_GET['opener'] == "true")? "opener.opener":"opener";
				echo '<a href="#" onclick="javascript:'.$opener.'.location.href=\''.$config->domain.'Browse/ByImage/?spId_Kw=id&amp;spKeywords='.$specimenArray['specimenId'].'\';'.$opener.'.focus();" >';
				//echo 'javascript:loadInOpener(\''.$config->domain.'Browse/ByImage/?spId_Kw=id&amp;spKeywords='.$specimenArray['specimenId'].'\')" >';
			} else {
				echo '<a href="'.$config->domain.'Browse/ByImage/?spId_Kw=id&amp;spKeywords='.$specimenArray['specimenId'].'" >';
			}
			echo '<img src="/style/webImages/camera-min16x12.gif" alt="imges" title="click to see images" /></a><br />
						<div class="specimenThumbOverflow">
							<table cellpadding="0" cellspacing="2">
								<tr>';
			for ($i = 0; $i < $thumbLimit; $i++) {
				$imageId = $imageArrayOfSpecimen[$i];
				$thumbUrl = getImageUrl($imageId, 'thumbs');
				echo '<td>';
				echo thumbnailTag($imageId, $thumbUrl);
				echo '</td>';
			}
			echo '								</tr>
				</table>
				</div>';
			echo '					</td>
				</tr>
				</table>';
		}
	}
}

/**
 *	Function to return an array of image id's where the specimen has more than one image associated with it
 */

function getSpecimenData ($id) {
	 $link = adminlogin();
	$sql = 'SELECT Specimen.*, Specimen.id AS specimenId, localityid AS locationId, '
	.'Image.id AS imageid, '
	.'Specimen.sex AS sex, Specimen.form AS form, Specimen.DevelopmentalStage AS developmentalStage, '
	.'BasisOfRecord.description AS basisOfRecord, '
	.'DATE_FORMAT(Specimen.dateCollected, "%Y-%m-%d") AS date '
	.'FROM Specimen '
	.'LEFT JOIN Image ON Specimen.id = Image.specimenId '
	.'LEFT JOIN BasisOfRecord ON Specimen.basisOfRecordId = BasisOfRecord.name ';
	$sql .= 'WHERE Specimen.id = '.$id.'';
	//echo $sql;
	$result = mysqli_query($link, $sql) or die(mysqli_error($link));
	if ($result) {
		$numRows = mysqli_num_rows($result);
		if ($numRows = 1) {
			$array = mysqli_fetch_array($result);
			return $array;
		}
	}
}

function returnSpecimenArray () {
	global $popUrl;
	$array =  array ( 	0=>		array('field' => 'specimenId',
									  'label' => 'Specimen Id: ',
									  'width' => 10,
									  'display' => TRUE),
	1=>		array('field' => 'basisOfRecord',
									  'label' => 'Basis of Record: ',
									  'width' => 10,
									  'display' => TRUE),
	2=>		array('field' => 'sex',
									  'label' => 'Sex: ',
									  'width' => 10,
									  'display' => TRUE),
	3=>		array('field' => 'form',
									  'label' => 'Form: ',
									  'width' => 10,
									  'display' => TRUE),
	4=>		array('field' => 'typeStatus',
									  'label' => 'Type Status: ',
									  'width' => 10,
									  'display' => TRUE),
	5=>		array('field' => 'developmentalStage',
									  'label' => 'Stage: ',
									  'width' => 10,
									  'display' => TRUE),
	6=>		array('field' => 'name',
									  'label' => 'DeterminedBy: ',
									  'width' => 30,
									  'display' => TRUE),
	7=>		array('field' => 'comment',
									  'label' => 'Determination Notes: ',
									  'width' => 30,
									  'display' => TRUE)
	);
	return $array;
}

function returnCollectionArray () {
	$array =  array ( 	0=>		array('field' => 'collectorName',
									  'label' => 'Collector: ',
									  'width' => 10,
									  'display' => TRUE),
	1=>		array('field' => 'institutionCode',
									  'label' => 'Institution: ',
									  'width' => 10,
									  'display' => false),
	2=>		array('field' => 'collectionCode',
									  'label' => 'Collection Code: ',
									  'width' => 10,
									  'display' => false),
	3=>		array('field' => 'catalogNumber',
									  'label' => 'Catalog: ',
									  'width' => 10,
									  'display' => false),
	4=>		array('field' => 'collectionNumber',
									  'label' => 'Collection Num: ',
									  'width' => 10,
									  'display' => false),
	5=>		array('field' => 'earliestDateCollected',
									  'label' => 'Earliest Date Collected: ',
									  'width' => 10,
									  'display' => false),
	6=>		array('field' => 'latestDateCollected',
									  'label' => 'Latest Date Collected: ',
									  'width' => 10,
									  'display' => false),
	7=>		array('field' => 'date',
									  'label' => 'Date Collected: ',
									  'width' => 10,
									  'display' => TRUE),
	8=>		array('field' => 'barcode',
									'label' => 'Barcode: ',
									 'width' => 10,
									 'display' => FALSE)
	);
	return $array;
}

function returnLocalityArray () {
	$array =  array ( 	0=>		array('field' => 'localityId',
									  'label' => 'Locality Id: ',
									  'width' => 10,
									  'href' => TRUE,
									  'display' => TRUE),
	1=>		array('field' => 'locality',
									  'label' => 'Locality: ',
									  'width' => 10,
									  'display' => TRUE),
	2=>		array('field' => 'continent',
									  'label' => 'Continent: ',
									  'width' => 10,
									  'display' => false),
  3=>		array('field' => 'ocean',
									  'label' => 'Ocean: ',
									  'width' => 10,
									  'display' => false),
	4=>		array('field' => 'country',
									  'label' => 'Country: ',
									  'width' => 10,
									  'display' => false),
  5=>		array('field' => 'state',
									  'label' => 'State: ',
									  'width' => 10,
									  'display' => false),
  6=>		array('field' => 'county',
									  'label' => 'County: ',
									  'width' => 10,
									  'display' => false),
	7=>		array('field3' => 'latitude',
									  'field4' => 'Latitude: ',
									  'width' => 10,
									  'latitude' => TRUE,
									  'display' => false),
	8=>		array('field3' => 'longitude',
									  'field4' => 'Longitude: ',
									  'longitude' => TRUE,
									  'width' => 10,
									  'display' => false),
	9=>		array('field' => 'coordinatePrecision',
									  'label' => 'Precision: ',
									  'width' => 10,
									  'display' => false),

	10=>		array('field1' => 'minimumElevation',
									  'field2' => 'maximumElevation',
									  'label' => 'Elevation (m): ',
									  'elevation' => TRUE,
									  'display' => false),
	11=>		array('field1' => 'minimumDepth',
									  'field2' => 'maximumDepth',
									  'label' => 'Depth (m): ',
									  'depth' => TRUE,
									  'display' => false)
	);
	return $array;
}
?>
