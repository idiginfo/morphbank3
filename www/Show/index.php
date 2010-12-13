<?php
/**
 *	Script that decides what type of object is requested, and shows it.
 *
 *	This script checks for GET variable (id) and imgType.  If imgType and (id) are given, script check to
 *	see if the object type is an image.  If TRUE, then displays just the image according to the type given, (either jpg, jpeg, or tiff)
 *	If there is just an id and no imgType, then object type is checked and calls the appropriate module to display that MB object.
 *
 *	If the query has no results an error message is outputted.
 *
 *	File: index.php
 *	@package Show
 *
 */

if ( isset($_GET['imgType']) && isset($_GET['id']) ) {
	$url = '/?id='.$_GET['id'].'&imgType='.$_GET['imgType'];
	header('Location: '.$url);
} elseif ( isset($_GET['imgType']) && isset($_GET['accessNum']) ) {
	$url = '/?accessNum='.$_GET['accessNum'].'&imgType='.$_GET['imgType'];
	header('Location: '.$url);
}

if ($_GET['pop']) {
	include_once('pop.inc.php');
} else {
	include_once('head.inc.php');
}

include_once('imageFunctions.php');
include_once('tsnFunctions.php');

$browseByTaxonHref= $config->domain . 'Browse/ByTaxon/';
$browseByNameHref= $config->domain . 'Browse/ByName/';

include_once( 'showFunctions.inc.php');

if ($_GET['search']) {
	$url = $config->domain . 'Show/xmlSearch/?search='.$_GET['search'];
	header('Location: '.$url);
}

// Checks to see if get variable "pop" exsists, then include correct html header


$idStr =  $_REQUEST['id'];
if (!($id = intval($idStr))) $id = 0;

if (isset($_GET['tsn'])) {
	$objectType = "Taxa";
	include_once('taxon/singleTaxon.inc.php');
	exit;
} else	{

	if ($id <= 0 || !checkAuthorization($id)){
		//TODO make separate file and show message
		$title = "Object $id not available";
		$includeText .= '<div class="popContainer" style="width:770px;"><div class="innerContainer7">';
		$includeText .= getNonAuthDiv(getNonAuthCode())."<br/>";
		$includeText .= '</div></div><div class="minHeight">&nbsp;</div>';
	} else {
		$checkObjectType = 'SELECT objectTypeId FROM BaseObject WHERE id = '.$id.' ';
		$db = connect();
		$result = $db->query($checkObjectType);
		if (PEAR::isError($result)){
			$title = "Database error";
			$includeText = $result->getUserInfo();
		} else {
			//echo $checkObjectType;
			if ($objectType = $result->fetchOne()) {
				$object = strtoupper($objectType);
				if ($object == 'VIEW') {
					$objectType = "View";
					$includeFile = 'view/singleView.inc.php';
				} elseif ($object == 'SPECIMEN') {
					$objectType = "Specimen";
					$includeFile = 'specimen/singleSpecimen.inc.php';
				} elseif ($object == 'IMAGE'){
					$objectType = "Image";
					$includeFile = 'image/singleImage.inc.php';
				} elseif ($object == 'USER'){
					$objectType = "User";
					$includeFile = 'user/singleUser.inc.php';
				} elseif ($object == 'GROUPS'){
					$objectType = "Groups";
					$includeFile = 'group/singleGroup.inc.php';
				} elseif ($object == 'PUBLICATION'){
					$objectType = "Publication";
					$includeFile = 'publication/singlePublication.inc.php';
				} elseif ($object == 'LOCALITY' || $object == 'LOCATION'){
					$objectType = "Location";
					$includeFile = 'location/singleLocation.inc.php';
				} elseif ($object == 'NEWS'){
					$objectType = "News";
					$includeFile = 'news/singleNews.inc.php';
				} elseif ($object == 'MYCOLLECTION' || $object =='COLLECTION' || $object =='MBCHARACTER'
				|| $object =='OTU'){
					$objectType = "Collection";
					$url = $config->domain . 'myCollection/?id='.$id;
					header("Location:$url");
					exit();
				} elseif ($object == 'ANNOTATION'){
					// if the BaseObject is an annotation, check if it is a TaxonName annotation (which is of a TaxonConcept)
					// then get the TSN for that TaxonConcept and redirect to the annotateTSN.php
					$objectType = "Annotation";
					$annotationType = checkAnnotationType($id);
					if ($annotationType['typeAnnotation'] == "TaxonName") {
						$tsn = getTaxonConceptTSN($annotationType['objectId']);
						$taxonConceptId = $annotationType['objectId'];
						$includeFile = 'annotation/taxonNameAnnotation.inc.php';
					} else {
						$includeFile = 'annotation/singleAnnotation.inc.php';
					}
				} else {
					// if the query returned an object with no show page
					ob_start();
					echo '<div class="popContainer" style="width:770px;"><div class="innerContainer7">';
					echo '<h1>'.$array['objectTypeId'].' with id '.$id;
					$baseObjectArray = getBaseObjectData ($id);
					showBaseObjectData($baseObjectArray);
					echo '</div></div><div class="minHeight">&nbsp;</div>';
					$includeText = ob_get_contents();
					ob_end_clean();
				}
				$title =  "$objectType $objectTypeId $id";
			} else {
				$title = "Object $id not available";
				$includeText .= '<div class="popContainer" style="width:770px;"><div class="innerContainer7">';
				$includeText .= '<b>There is no object with id '.$id.'</b>';
				$includeText .= '<div class="minHeight">&nbsp;</div>';
			}
		}
	}
}
initHtml( $title, NULL, NULL);
echoHead( false, $title, false);
if(!empty($includeFile)) include_once($includeFile);
if(!empty($includeText)) echo $includeText;
finishHtml();

