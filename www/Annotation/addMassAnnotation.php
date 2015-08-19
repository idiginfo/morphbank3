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

include_once('imageFunctions.php');
include_once('showFunctions.inc.php');
include_once('updateObjectKeywords.php');
include_once('updater.class.php');
include_once('annotateFunctions.php');
include_once('Classes/UUID.php');

$userId = $objInfo->getUserId();
$groupId = $objInfo->getUserGroupId();

$pop = !empty($_POST['pop']) ? '&pop=yes' : '';

$returnUrl = "index.php?$pop";
if (!checkAuthorization(null,null,null,'annotate')){
	header("location: $returnUrl&code=2");
}

$db = connect();

// get post variables
/**
 * TODO objArray filtered in mainAnnotation() for images only. Change later for other objects
 * $objArray holds objectid and objecttypeid keys
 */

$objArray          = $_POST['objArray'];
$PrevURL           = $_POST['PrevURL'];
$imageIdString     = $_POST['imageArray'];
$title             = $_POST['title'];
$annotationType    = $_POST['typeAnnotation'];
$detAnnotationType = $_POST['typeDetAnnotation'];
$comment           = trim($_POST['comment']);//TODO careful after removing "html..." encoding
$dateToPublish     = $_POST['dateToPublish'];
$annotationMarkup  = $_POST['arrowc'];
$annotationLabel   = $_POST['annotationLabel'];
$taxon             = $_POST['Taxon']; // note that this is a specimen id
$tsn               = $_POST['tsnId'];
$prefix            = $annotationType == 'Determination' ? $_POST['prefix'] : 'none';
$suffix            = $annotationType == 'Determination' ? $_POST['suffix'] : 'none';
$collectionId      = $_POST['collectionId'];
$sourceOfId        = $_POST['sourceOfId'];
$materialsUsedInId = $_POST['materialsUsedInId'];
$resourcesused     = $_POST['resourcesused'];
$annotationLabel   = $_POST['annotationLabel'];


$xLocation = 0;
if (isset($_POST['xLocation'])) $xLocation = $_POST['xLocation'];
$yLocation = 0;
if (isset($_POST['yLocation'])) $yLocation = $_POST['yLocation'];

if (!empty($_FILES['XMLData']['tmp_name'])) {
	$source = $_FILES['XMLData']['tmp_name'];
	$XMLData = file_get_contents($source);
	$XMLData = addslashes($XMLData);
}

if ($annotationType == "Determination") {
	$tsnId = !empty($taxon) ? $taxon : (!empty($tsn) ? $tsn : '');
	if (!empty($tsnId)) {
		$tsnData   = getTsnData($tsnId);
		$rankId    = $tsnData['rank_id'];
		$kingdomId = $tsnData['kingdom_id'];
		$rankName  = $tsnData['rank_name'];
		if ($detAnnotationType == 'agreewq' || $detAnnotationType == 'newdet') {
			$detAnnotationType = 'agree';
		}
	} else {
		header("location: $returnUrl&code=3&id=".$objArray[0]['objectid']);
		exit;
	}
}


/**********************************************************************************
 *  for Each image id in the array,                                                            *
 *  1. Get the current id from the id table.                                               *
 *  2. Add one to the id field.                                                                *
 *  3. Update the id field in the id table.                                                  *
 *  4. Insert the new user record using the new id .                                         *
 *  5. Add a new record to the "baseobject" table referenceing the new record.                 *
 *  6. If the type annotation is determination, add a determination record.                    *
 *  If for any reason one of the database operations does not work during the processing       *
 *  of an annotation record, the loop is incremented to the next image or object.              *
 **********************************************************************************************/

foreach ($objArray as $object) {
	//TODO make this work for objects that are not images
	$query = 'select specimenId from Image where id=' . $object['objectid'];
	$specimenId = $db->getOne($query);
	isMdb2Error($specimenId,$query);
	// if (empty($specimenId)) continue;// no annotation for non-image?
  
	
	//TODO change this into new strategy for create and update
    $uuid = UUID::v4();
	$params = array(
        $db->quote("Annotation"),
        $userId,
        $groupId,
        $userId,
        $db->quote($dateToPublish,'date'),
        $db->quote("Annotation added"),
        $db->quote(NULL),
        $db->quote($uuid)
    );
	$result = $db->executeStoredProc('CreateObject', $params);
	if(isMdb2Error($result, 'Create Object procedure')) {
		header("location: $returnUrl&code=4");
		exit;
	}
	$id = $result->fetchOne();
	clear_multi_query($result);
 
	$annotationUpdater = new Updater($db, $id, $userId , $groupId, 'Annotation');

	$annotationUpdater->addField('objectId', $object['objectid'], null);
	$annotationUpdater->addField('objectTypeId', 'Image', null);
	$annotationUpdater->addField('typeAnnotation', $annotationType, null);
	$annotationUpdater->addField('xLocation', $xLocation, null);
	$annotationUpdater->addField('yLocation', $yLocation, null);
	$annotationUpdater->addField('title', $title, null);
	$annotationUpdater->addField('comment', $comment, null);
	$annotationUpdater->addField('xmlData', $XMLData, null);
	$annotationUpdater->addField('externalURL', $externalURL, null);
	$annotationUpdater->addField('annotationLabel', $annotationLabel, null);
	$annotationUpdater->addField('annotationMarkup', $annotationMarkup, null);

	$numRows = $annotationUpdater->executeUpdate();
	
	//TODO GOGO here

	if ($annotationType=="Determination"){
		// create DA object
    $data = array($id);
    $sql = "insert into DeterminationAnnotation (annotationId) values (?)";
    $stmt = $db->prepare($sql);
    $numRows = $stmt->execute($data);
    if (isMdb2Error($numRows, 'Inserting DeterminationAnnotation', 5)) {
      header("location: $returnUrl/?id=$id&code=5");
      exit;
    }
		
		// update DA object
		$determinationUpdater = new Updater($db, $id, $userId , $groupId, 'DeterminationAnnotation', 'annotationId');
		$determinationUpdater->addField('specimenId', $specimenId, null);
		$determinationUpdater->addField('tsnId', $tsnId, null);
		$determinationUpdater->addField('rankId', $rankId, null);
		$determinationUpdater->addField('kingdomId', $kingdomId, null);
		$determinationUpdater->addField('rankName', $rankName, null);
		$determinationUpdater->addField('typeDetAnnotation', $detAnnotationType, null);
		$determinationUpdater->addField('sourceOfId', $sourceOfId, null);
		$determinationUpdater->addField('collectionId', $collectionId, null);
		$determinationUpdater->addField('materialsUsedInId', $materialsUsedInId, null);
		$determinationUpdater->addField('prefix', $prefix, null);
		$determinationUpdater->addField('suffix', $suffix, null);
		$determinationUpdater->addField('resourcesused', $resourcesused, null);
		$determinationUpdater->addField('altTaxonName', $altTaxonName, null);

		$numRows = $determinationUpdater->executeUpdate();
	}
  
	createAnnotationThumb($id, $object['objectid']);
	updateKeywordsTable($id, 'insert');
}



// show annotation
header("location: /?id=$id");
exit;
