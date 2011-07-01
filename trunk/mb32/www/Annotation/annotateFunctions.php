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


/**
 * File name: annotateFunctions.php
 * @package Morphbank2
 * @subpackage Annotate
 */

/**
 * Echo messages
 * @param integer $code
 * @return void
 */
function getAnnotateMsg($code) {
	if ($code == 2) {//
		echo '<div class="searchError">You do not have permissions to add annotation</div><br /><br />'."\n";
	} elseif ($code == 3) {//
		echo '<div class="searchError">Conflict detected in selecting either a new taxon or agree, disagree, or qualify rank with a previous determination</div><br /><br />'."\n";
	} elseif ($code == 4) {
		echo '<div class="searchError">Error creating annotation object</div><br /><br />'."\n";
	}
	return;
}

function displayImageTitles($imageId) {
	global $config;
	echo "<h2>Image Record [";
	$viewUrl = bischenPageUrl($imageId);
	$imageViewerUrl = $config->domain . 'Show/imageViewer/index.php?id='.$imageId;
	echo '<a href="' .$imageViewerUrl.'" target="blank" title="Click to display image annotation tool">' . $imageId . '</a>';
	echo "-" . getImageTSN($imageId) . "]</h2><br/><hr/>";
}

/**
 * Get objectType from BaseObject
 * @param int $id
 */
function getBaseObjectTypeId($id) {
	$db = connect();
	$sql = "select objectTypeId from BaseObject where id = ?";
	$objTypeId = $db->getOne($sql, null, array($id));
	isMdb2Error($objTypeId, 'Selecting object type id');
	return $objTypeId;
}

/**
 * Checks id or collection for Taxon Concept. If one exists, redirect.
 * Array of object ids with object types is returned
 * 
 * @param $id
 * @param $collectionId
 * @return array $objArray object id/type
 */
function checkForTaxonConcept($id, $collectionId) {
	if (empty($id) && empty($collectionId)) return false;
	if (!empty($id)) {
		$objectType = getBaseObjectTypeId($id);
		if ($objectType == "Taxon Concept") redirectTsn($id);
		$objArray[] = array('objectid' => $id, 'objecttypeid' => $objectType);
	}
	if (!empty($collectionId)) {
		if ($objArray = getObjectIdsFromCollection($collectionId)) {
			foreach ($objArray as $object) {
				if ($object['objecttypeid'] == "Taxon Concept") {
					redirectTsn($object['objectid']);
					break;
				}
			}
		}	
	}
	return $objArray;
}

/**
 * Redirect for taxon concept trying to be edited via mass annotation
 * @param $id
 */
function redirectTSN($id) {
	$sql = "SELECT tsn from TaxonConcept where id = ?";
	$tsn = $db->getOne($sql, null, array($id));
	isMdb2Error($tsn, 'Selecting tsn from Taxon Concept');
	header("location: /Admin/TaxonSearch/annotateTSN.php?tsn=$tsn");
	exit();
}

/**
 * Get object ids for objects in collection
 * @param int $collectionId
 */
function getObjectIdsFromCollection($collectionId) {
	if (empty($collectionId)) return;
	$db = connect();
	$sql = "select c.objectId, b.objectTypeId from CollectionObjects c 
			left join BaseObject b on b.id = c.objectId where c.collectionId = ?";
	$rows = $db->getAll($sql, null, array($collectionId), null, MDB2_FETCHMODE_ASSOC);
	isMdb2Error($rows, "Selecting object ids from collection");
	return $rows;
}

/**
 * Display select box of annotation types
 */
function displayTypeofAnnotations() {
	$db = connect();
	$sql = "select annotationType as name from AnnotationType";
	$rows = $db->queryAll($sql, null, MDB2_FETCHMODE_ASSOC);
	
	$type = isset($_GET['type']) ? $_GET['type'] : 'General';
	echo '<td><select id="annotationType" name="typeAnnotation" size="1" tabindex="1" title="Select the type of Annotation you wish to enter">';
	foreach ($rows as $row) {
		$selected = $type == $row['name'] ? 'selected="selected"' : '';
		echo '<option value="' . $row['name'] . '" ' . $selected . '>' . $row['name'] . '</option>';
	}
	echo '</selected></td>';
}

/**
 * Display list of image in collection
 * @param array $objArray
 * @param int $collectionId
 */
function displayImageList($objArray, $collectionId) {
		
	$db = connect();
	$sql = "select name from Collection where id = ?";
	$name = $db->getOne($sql, null, array($collectionId));
	isMdb2Error($name, 'Selecting collection name');
	
	$num = 0;
	foreach ($objArray as $object) {
		if ($object['objecttypeid'] == 'Image') {
			$num++;
		}
	}
	
	$sql = "select count(*) as count from CollectionObjects where collectionId = ?";
	$count = $db->getOne($sql, null, array($collectionId));
	isMdb2Error($count, 'Selecting count of collection objects');

	echo "<h2>";
	echo $num . " Images of " . $count . " objects in Collection ";
	echo '<a href="/Show/index.php?pop=yes&id=' . $collectionId . '">' . $collectionId . '</a> [' . $name . ']';
	echo "</h2><br/><hr/>";
}

/**
 * Display thumbs for images
 * @param $objArray
 */
function displayThumbs($objArray) {
	echo '<div class="scroll">
			<table border="1" bordercolor=#000000 cellspacing=0 cellpadding=4 width="770px">
				<tr >';
	foreach ($objArray as $object) {
		if ($object['objecttypeid'] == 'Image') {
			$imageData = htmlentities(getallimagedata($object['objectid']), ENT_QUOTES, "UTF-8");
			echo 		'<td height="100px">Image Record: [' . $object['objectid'] . ']<br/><br/>';
			$imgUrl = getObjectImageUrl($object['objectid'], 'thumbs');
			echo '<a href ="javascript:openPopup(\'http://morphbank4.scs.fsu.edu/Show/imageViewer/index.php?id=' 
					. trim($object['objectid']) . '&amp;pop=yes\')" title="Click to display image">
					<img src ="' . $imgUrl . '" width=100 onMouseOver="javascript:startPostIt(event,\'' 
					. $imageData . '\');" onMouseOut="javascript:stopPostIt();"</a>';
			echo 	'</td>';
		}
	}
	echo '</tr>';
	echo '</table></div>';
}

/**
 * returns scientific name for image
 * @param $imageId
 * @return string
 */
function getScientificNameFromImage($imageId) {
	$db = connect();
	$sql = "select s.tsnId from Specimen s left join Image i on i.specimenId = s.id where i.id = ?";
	$tsn = $db->getOne($sql, null, array($imageId));
	isMdb2Error($tsn, 'Selecting tsn for image');
	$scientificName = getScientificName($tsn);
	return $scientificName;
}

/**
 * Display title for images
 * @param $objArray
 */
function displayImageTitle($objArray) {
	foreach ($objArray as $object) {
		if ($object['objecttypeid'] == 'Image') {
			echo "<h1>Image Record[" . getScientificNameFromImage($object['objectid']) . "]</h1>";
		}
	}
}

/**
 * Display related annotations
 * @param array $objArray
 */
function displayRelatedAnnotations($objArray, $singleShow = false) {
	
	$size = sizeof($objArray);
	if ($size < 1) return;
	echo '<h3>Related Annotations</h3>';
	$OldResults = getRelated($objArray);
  
	// ADDED THIS CODE SO THAT THE SPECIMEN RECORD SHOWS UP AS ONE OF THE DETERMINATION RECORDS.
	$OldResults = AddSpecimenDetermination($OldResults, $objArray);

	$class = !$singleShow ? 'topBlueBorder' : '';
	if (empty($OldResults)) {
		echo '<table class="'.$class.'" width="660px"><tr><td><h3>No related Annotations</h3></td></td></table>';
		return;
	}
	
	//$DetResults = $OldResults;
	$DetResults = RemoveDuplicates($OldResults);
//	$DetResults = CountSpecimens($DetResults, $OldResults);
	$sized = sizeof($DetResults['id']);
	echo '<table width="730px" class="'.$class.'" ><tr><td>';
	echo '<table width="730px" class="'.$class.'">';
	echo '<tr><td>&nbsp;</td><td><b>Taxonomic Name</td><td><b>Taxon Author</td>';
	echo '<td><b>Prefix</b></td><td><b>Suffix</b></td>';
	echo '<td title="Number who Agreed with the Determination"><img src="/style/webImages/thumbUp.gif" alt="icon" /></td>';
	echo '<td title="Number who disagreed with the Determination"><img src="/style/webImages/thumbDown.gif" alt="icon" /></td>';

	for ($i = 0; $i < $sized; $i++) {
		echo '<tr><td>';
		if (!$singleShow) {
			echo '<input type="radio" name="Taxon" value="' . $DetResults['TsnId'][$i] . '" Title="Check here to select this Determiation"></td>';
		}
		echo '<td>' . $DetResults['TaxonName'][$i] . '</td>';
		echo '<td>' . $DetResults['TaxonAuthor'][$i] . '</td>';
		echo '<td>' . $DetResults['prefix'][$i] . '</td>';
		echo '<td>' . $DetResults['suffix'][$i] . '</td>';
		echo '<td>' . $DetResults['numAgree'][$i] . '</td>';
		echo '<td>' . $DetResults['numDisagree'][$i] . '</td>';
	}
	echo '</table></td></tr></table>';
}

/**
 * Get related annotations
 * @param $imageArray
 */
function getRelated($objArray)
{
	$db = connect();

	foreach ($objArray as $object) {
		$sql  = "select a.id, a.objectId, da.specimenId, da.tsnId, da.typeDetAnnotation, da.prefix, da.suffix "
			  . "from Annotation a "
			  . "left join DeterminationAnnotation da on da.annotationId = a.id "
			  . "where a.objectId = ?";
		$rows = $db->getAll($sql, null, array($object['objectid']), null, MDB2_FETCHMODE_ASSOC);
		isMdb2Error($rows, "Selecting related annotations");
		$counter = 0;
    
    if (!$rows) return;
		
    foreach($rows as $row) {
      $DetResults['id'][$counter] = $row['id'];
      $DetResults['objectId'][$counter] = $row['objectid'];
      $DetResults['specimenId'][$counter] = $row['specimenid'];
      $DetResults['TsnId'][$counter] = $row['tsnid'];
      $DetResults['TaxonName'][$counter] = getScientificName($row['tsnid']);
      if ($row['tsnid'] > "999000000") {
        $DetResults['TaxonAuthor'][$counter] = "Temporary TSN Name";
      } else {
        $DetResults['TaxonAuthor'][$counter] = getTaxonAuthor($row['tsnid']);
      }
      $DetResults['typeDetAnnotation'][$counter] = $row['typedetannotation'];
      $DetResults['prefix'][$counter] = $row['prefix'];
      $DetResults['suffix'][$counter] = $row['suffix'];
      if ($row['typedetannotation'] == 'disagree') {
        $DetResults['numAgree'][$counter] = 0;
        $DetResults['numDisagree'][$counter] = 1;
      } else {
        $DetResults['numAgree'][$counter] = 1;
        $DetResults['numDisagree'][$counter] = 0;
      }
      $counter++;
    }
	}
	return $DetResults;
}

// ****************************************************************************************************
// Added the following functions to include specimen data with the determination data.                *
// ****************************************************************************************************

function AddSpecimenDetermination($OldResults, $objArray) {
	$size = sizeof($OldResults['id']);
	
	foreach ($objArray as $object) {
		$specimenData = getSpecimenDeterminationData(getSpecimenId($object['objectid']));
    if (empty($specimenData)) return;
		$OldResults['id'][$size] = $specimenData['specimenid'];
		$OldResults['objectId'][$size] = $object['objectid'];
		$OldResults['specimenId'][$size] = $specimenData['specimenid'];
		$OldResults['TsnId'][$size] = $specimenData['tsnid'];
		$OldResults['TaxonName'][$size] = $specimenData['taxonname'];
		if ($specimenData['tsnId'] > "999000000") {
			$OldResults['TaxonAuthor'][$size] = "Temporary TSN Name";
		} else {
			$OldResults['TaxonAuthor'][$size] = $specimenData['taxonauthor'];
		}
		$OldResults['typeDetAnnotation'][$size] = 'agree';
		$OldResults['prefix'][$size] = "none";
		$OldResults['suffix'][$size] = "none";
		$OldResults['numAgree'][$size] = 1;
		$OldResults['numDisagree'][$size] = 0;
		$size++;
	}
	return $OldResults;
}

/**
 * Get Specimen id 
 * @param $imageId
 */
function getSpecimenId($imageId) {
	$db = connect();
	$sql = "select specimenId from Image where id = ?";
	$specimenId = $db->getOne($sql, null, array($imageId));
	isMdb2Error($specimenId, 'Selecting specimen id for image');
	return $specimenId;
}

/**
 * Get determination data for specimen
 * @param integer $specimenId
 */
function getSpecimenDeterminationData($specimenId) {
	$db = connect();
	$sql = "select * from Specimen where id = $specimenId";
	$row = $db->queryRow($sql, null, MDB2_FETCHMODE_ASSOC);
	isMdb2Error($row, "Selecting specimen determination data for specimen id $specimenId");
	
	$data['specimenid'] = $specimenId;
	$data['tsnid'] = $row['tsnid'];
	$data['taxonname'] = getScientificName($row['tsnid']);
	$data['taxonauthor'] = getTaxonAuthor($row['tsnid']);
	return $data;
}

// ****************************************************************************************
// * End of new routines.                                                                 *
//*****************************************************************************************

/**
 * Get taxon author from tsn id
 * @param unknown_type $tsnId
 */
function getTaxonAuthor($tsnId) {
	$db = connect();
	$sql = "select ta.taxon_author from Tree t
			left join TaxonAuthors ta on ta.taxon_author_id = t.taxon_author_id 
			where t.tsn = ?";
	$taxonAuthor = $db->getOne($sql, null, array($tsnId));
	isMdb2Error($row, "Selecting taxon author for taxon id $tsnId");
	return $taxonAuthor;
}


/**
 * Remove duplicates from determination results
 * @param $relAnnotations
 */
function RemoveDuplicates($DetResults) {

	$size = sizeof($DetResults['id']);
	if ($size < 2)
	return $DetResults;
	$counter = 0;
	
	$NewDetResults['id'][0] = $DetResults['id'][0];
	$NewDetResults['objectId'][0] = $DetResults['objectId'][0];
	$NewDetResults['specimenId'][0] = $DetResults['specimenId'][0];
	$NewDetResults['TsnId'][0] = $DetResults['TsnId'][0];
	$NewDetResults['TaxonName'][0] = $DetResults['TaxonName'][0];
	$NewDetResults['TaxonAuthor'][0] = $DetResults['TaxonAuthor'][0];
	$NewDetResults['typeDetAnnotation'][0] = $DetResults['typeDetAnnotation'][0];
	$NewDetResults['prefix'][0] = $DetResults['prefix'][0];
	$NewDetResults['suffix'][0] = $DetResults['suffix'][0];
	$NewDetResults['numAgree'][0] = 0;
	$NewDetResults['numDisagree'][0] = 0;
	
	for ($i = 0; $i < $size; $i++) {
		$insert = 1;
		for ($j = 0; $j < $counter; $j++) {
			if ($DetResults['TsnId'][$i] == $NewDetResults['TsnId'][$j] 
				&& $DetResults['prefix'][$i] == $NewDetResults['prefix'][$j] 
				&& $DetResults['suffix'][$i] == $NewDetResults['suffix'][$j]) {
				$insert = 0;
				$NewDetResults['numAgree'][$j] += $DetResults['numAgree'][$i];
				$NewDetResults['numDisagree'][$j] += $DetResults['numDisagree'][$i];
			}
		}
		if ($insert == 1) {
			$NewDetResults['id'][$counter] = $DetResults['id'][$i];
			$NewDetResults['objectId'][$counter] = $DetResults['objectId'][$i];
			$NewDetResults['specimenId'][$counter] = $DetResults['specimenId'][$i];
			$NewDetResults['TsnId'][$counter] = $DetResults['TsnId'][$i];
			$NewDetResults['TaxonName'][$counter] = $DetResults['TaxonName'][$i];
			$NewDetResults['TaxonAuthor'][$counter] = $DetResults['TaxonAuthor'][$i];
			$NewDetResults['typeDetAnnotation'][$counter] = $DetResults['typeDetAnnotation'][$i];
			$NewDetResults['prefix'][$counter] = $DetResults['prefix'][$i];
			$NewDetResults['suffix'][$counter] = $DetResults['suffix'][$i];
			$NewDetResults['numAgree'][$counter] = $DetResults['numAgree'][$j];
			$NewDetResults['numDisagree'][$counter++] = $DetResults['numDisagree'][$j];
		}
	}
	return $NewDetResults;
}

/**
 * Count specimens
 * @param array $relAnnotations
 * @param array $detResults
 */
function CountSpecimens($DetResults, $OrigResults)
{
	$db = connect();
	
	$size = sizeof($DetResults['id']);
	$origsize = sizeof($OrigResults['id']);
	
	$querypt1 = "SELECT DISTINCT specimenId,tsnId,prefix,suffix from DeterminationAnnotation where ";
	for ($i = 0; $i < $size; $i++) {
		$querypt2 = "";
		$querypt2 = " tsnId= " . $DetResults['TsnId'][$i];
		$querypt2 .= ' and prefix="' . $DetResults['prefix'][$i] . '"';
		$querypt2 .= ' and suffix="' . $DetResults['suffix'][$i] . '"';
		$querypt2 .= ' and (specimenId = "' . $OrigResults['specimenId'][$i] . '" ';
		for ($j = 1; $j < $origsize; $j++)
		$querypt2 .= ' or specimenId="' . $OrigResults['specimenId'][$j] . '"';
		$querypt2 .= ')';
		$query = $querypt1 . $querypt2;
		$results = $db->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
		isMdb2Error($results, "Selecting information for specimen count");
		if ($results) {
			$numrows = count($results);
		} else {
			$numrows = 0;
		}
		// ****************************************************************************************
		// **  If the number of rows is empty then that means that we have the siutation          *
		// **  where we have a specimen with no determination annotations.  So we count           *
		// **  the actual number of specimens and replace that number.                            *
		// **  Take each one of the unique taxon records and count the number of related          *
		// **  specimens in the input stream that contain that exact same taxonomic id.           *
		// ****************************************************************************************
		if ($numrows == 0) {
			$querypt3 = "";
			$querypt4 = "";
			$query5 = "";
			$querypt3 = 'select distinct id from Specimen where tsnId="' . $DetResults['TsnId'][$i] . '" and ( id = "' . $DetResults['specimenId'][$i] . '" ';
			for ($j = 1; $j < $origsize; $j++)
			$querypt4 .= ' or id="' . $OrigResults['specimenId'][$j] . '"';
			$querypt4 .= ')';
			$query5 = $querypt3 . $querypt4;
			$results = $db->queryAll($query5, null, MDB2_FETCHMODE_ASSOC);
			isMdb2Error($results, "Selecting specimen information for specimen count");
			if ($results) {
				$numrows = count($results);
			} else {
				$numrows = 0;
			}
		}
		$DetResults['numSpecimens'][$i] = $numrows;
	}
	return $DetResults;
}

/**
 * Get materials select
 */
function getMaterialsExamined() {
	$MEArray[0] = "Image";
	$MEArray[1] = "Specimen";
	$MEArray[2] = "DNA Sequence";
	$MEArray[3] = "DNA Fingerprinting";
	$CurrIndex = 3;
	
	$db = connect();
	$sql = "select distinct materialsUsedInId from DeterminationAnnotation";
	$rows = $db->queryAll($sql, null, MDB2_FETCHMODE_ASSOC);
	isMdb2Error($rows, "Selecting material examined information");
	if (count($rows) < 1) {
		return $MEArray;
	}
	
	foreach ($rows as $row) {
		if ($row['materialsusedinid'] != "Image" && $row['materialsusedinid'] != "Specimen" && $row['materialsusedinid'] != "DNA Sequence" && $row['materialsusedinid'] != "DNA Fingerprinting") {
			$MEArray[$CurrIndex++] = $row['materialsusedinid'];
		}
	}
	return $MEArray;
}

// Functions used in addMassAnnotation.php

/**
 * Create thumb for annotation
 * @param $id
 * @param $objectId
 */
function createAnnotationThumb($id, $objectId = false) {
	$db = connect();
	// if there is no object Id given (only an annotationId), then pick the first object in the collection for the thumb
	if (!$objectId) {
		$sql = 'SELECT CollectionObjects.objectId FROM CollectionObjects WHERE CollectionObjects.collectionId=' . $id . ' ORDER BY objectOrder LIMIT 1 ';
		$thumbId = $db->queryOne($sql);
		isMdb2Error($thumbId, "Selecting object id from collection");
	} else {
		// other wise, use the objectId given for the thumbURL in B.O.
		$thumbId = $objectId;
	}
	// find the thumbURL for the object (could be a specimen or view etc) defined in imageFunctions.
	$thumbURL = getObjectImageId($thumbId);
	// Last... set the thumbURL for the collection, to the thumbURL of the object chosen to represent the collection
	if (setThumb($id, $thumbURL))
	return true;
	else
	return false;
}

/**
 * Get taxon information for Determination
 * @param $tsn
 */
function getTsnData($tsn) {
	$db = connect();
	$sql = "select * from Taxa where tsn = " . $tsn;
	$row = $db->queryRow($sql, null, MDB2_FETCHMODE_ASSOC);
	isMdb2Error($row, "Selecting taxa information for tsn: $tsn");

	$data['rank_id'] = $row['rank_id'];
	$data['kingdom_id'] = $row['kingdom_id'];
	
	$sql = "select rank_name from TaxonUnitTypes where rank_id = " . $row['rank_id'] . " and kingdom_id = " . $row['kingdom_id'];
	$rank_name = $db->queryOne($sql);
	isMdb2Error($rank_name, "Selecting rank name");
	
	$data['rank_name'] = $rank_name;
	return $data;
}
