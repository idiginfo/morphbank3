<?php

$viewArray = getViewData($id);
$baseObjectArray = getBaseObjectData($id);
$popUrl = (isset($_GET['pop'])) ? "/Show/?pop=Yes&amp;id=" : "/?id=";
$className = checkForExtLinks($id) ? "topBlueBorder" : "blueBorder";
//var_dump($viewArray);
$tsnName = getTsnName ($viewArray['viewTsn']);
$imageId = getObjectImageId($id);

$imgUrl = getImageUrl($imageId, 'jpg');

//$path = pathinfo($_SERVER['PHP_SELF']);
// Output the content of the main frame
if (isset($_GET['pop'])) {
	echo '<div class="popContainer">';
} else {
	echo '<div class="mainGenericContainer">';
}

printTitle();
echo '<table class="'.$className.'" width="100%" cellspacing="0" cellpadding="0">
		<tr>
		<td class="firstColumn" width="50%" valign="top">
		<div class="popCellPadding">';
showBaseObjectData($baseObjectArray);
echo'<hr align="left" width="90%" />
	<table border="0"><tr>
		<th align="right" width="135">Specimen Part:</th><td>'.$viewArray['specimenPart'].'</td>
		</tr><tr>
		<th align="right">View Angle:</th><td>'.$viewArray['viewAngle'].'</td>
		</tr><tr>
		<th align="right">Sex:</th><td>'.$viewArray['sex'].'</td></tr>
		<tr><th align="right">Form:</th><td>'.$viewArray['form'].'</td></tr>
		<tr><th align="right">Stage:</th><td>'.$viewArray['developmentalStage'].'</td>
		</tr><tr>
		<th align="right">Technique:</th><td>'.$viewArray['imagingTechnique'].'</td>
		</tr><tr>
		<th align="right">Preparation:</th><td>'.$viewArray['imagingPreparationTechnique'].'</td>
		</tr><tr>
		<th align="right" valign="bottom">View Applicable to:</th><td valign="bottom">';
echo printTsnNameLinks($tsnName);
echo '</td></tr></table>
	<hr align="left" width="90%" /><table border="0">
	<tr><th align="right">Example Image:</th><td><a href="';
echo $popUrl.$viewArray['standardImageId'].'">['.$viewArray['standardImageId'].']</a> </td></tr>';
imagesWithRelatedView($id);
echo'</table></div></td><td width="50%">';
global $imageType;
//TODO check for tif file generate if not present.
//if ($imageType == 'DNG'){
//	echo '<a href="javascript: alert(\'FSI Viewer is not available for this image at present. ';
//	echo '\nContact Morphbank Administrators to request creation of the viewable image for id=';
//	echo $id.'\');" title="See image">';
//}

echo showMediumImage($id);
echo '</td></tr></table>';
if (checkForExtLinks($id)) {
	echo'<table class="bottomBlueBorder" width="100%" border="0" cellspacing="0" cellpadding="2">
		<tr><td>
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		<tr><td>';
	showExternalLinks($id);
	echo'</td></tr></table></td></tr></table>';
}
showRelatedObjects($id);
echo '							  </div>'; // popContainer

function printTitle () {
	global $config, $id, $tsnName;
	include_once ('tsnFunctions.php');

	//$arrayOfParents = getArrayParents($viewTsn);
	//$arraySize = count($arrayOfParents);

	$title = getViewTitle($id);
	echo '<h2>View Record: ['.$id.'] '.$title.'<br /> Applicable to ';
	echo printTsnNameLinks($tsnName);
	echo'</h2>';
}

function getViewTitle ($id) {
	global $link;
	$sql = 'SELECT viewName '
	.'FROM View '
	.'WHERE View.id = '.$id;
	$result = mysqli_query($link, $sql) or die(mysqli_error($link));
	if ($result) {
		$num = mysqli_num_rows($result);
		if ($num = 1) {
			$array = mysqli_fetch_array($result);
			$title =$array['viewName'];
			return $title;
		} else {
			return "Query Error";
		}
	} else {
		return "Query Error";
	}
}

function getViewData($id) {
	global $link;
	$sql = 'SELECT View.imagingTechnique AS imagingTechnique, View.imagingPreparationTechnique AS imagingPreparationTechnique, '
	.'View.specimenPart AS specimenPart, View.viewAngle AS viewAngle, View.sex AS sex, View.form AS form, View.id AS id, '
	.'View.developmentalStage AS developmentalStage, View.standardImageId, '
	.'View.viewTsn AS viewTsn '
	.'FROM View '
	.'LEFT JOIN Image ON View.id = Image.viewId '
	.'LEFT JOIN Specimen ON Specimen.id = Image.specimenId ';
	$sql .= 'WHERE View.id = '.$id.'';
	$result = mysqli_query($link, $sql) or die(mysqli_error($link));
	if ($result) {
		$total = mysqli_num_rows($result);
		if ($total = 1) {
			$array = mysqli_fetch_array($result);
			return $array;
		}
	}
}

function imagesWithRelatedView($viewId) {
	global $link, $config;
	$url = $config->domain . 'Browse/ByImage/?viewId_Kw=id&amp;viewKeywords='.$viewId;
	$sql ='SELECT COUNT(*) AS count FROM Image WHERE viewId = \''.$viewId.'\'';
	$result = mysqli_query($link, $sql) or die(mysqli_error($link));
	if($result) {
		$count=mysqli_fetch_array($result);
		if ($count['count'] >= 2) {
			echo '<tr>
					<th align="right">
						Images for view:
					</th>';
			if (isset($_GET['pop'])) {
				$opener = ($_GET['opener'] == "true")? "opener.opener":"opener";
				echo'	<td>
					'.$count['count'].'&nbsp;<a href="#" onclick="javascript:'.$opener.'.location.href=\''.$url.'\';'.$opener.'.focus();">
					<img src="/style/webImages/camera-min.gif" alt="images" title="click to see images of this view" align="absbottom" /></a>
					</td>';
			} else {
				echo '<td>'.$count['count'].'&nbsp;<a href="'.$url.'">';
				echo '<img src="/style/webImages/camera-min.gif" alt="images" title="click to see images of this view" align="absbottom" /></a>
				</td>';
			}
			echo'</tr>';
		}
	}
}
?>
