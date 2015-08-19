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

if ($_REQUEST['pop']) {
	include_once('pop.inc.php');
} else {
	include_once('head.inc.php');
}
include_once('updateObjectKeywords.php');
include_once('updater.class.php');
global  $objInfo;

$db = connect();

$title = 'Updated Annotation Data';
initHtml($title, null, null);
echoHead(false, $title);

echo '<div class="mainGenericContainer" style="width:700px">';


$userId = $objInfo->getUserId();
$groupId = $objInfo->getUserGroupId();

$annotationId = $_POST['annotationId'];
$title = $_POST['title'];
$typeAnnotation = $_POST['typeAnnotation'];
$typeDetAnnotation = $_POST['typeDetAnnotation'];
$comment = $_POST['comment'];
$dateToPublish = $_POST['dateToPublish'];
$resourcesused = $_POST['resourcesused'];
$PrevURL = $_POST['PrevURL'];

$xLocation = isset($_POST['xLocation']) ? $_POST['xLocation'] : 0;
$yLocation = isset($_POST['yLocation']) ? $_POST['yLocation'] : 0;

$annotationMarkup = isset($_POST['arrowc']) ? $_POST['arrowc'] : '';
$annotationLabel = isset($_POST['annotationLabel']) ? $_POST['annotationLabel'] : '';

if (isset($_POST['XMLData'])) {
	$source = $_FILES['XMLData']['tmp_name'];
	$XMLData = file_get_contents($source);
	$XMLData = addslashes($XMLData);
} else {
	$XMLData = null;
}


if ($typeAnnotation == "Determination") {
	if ($resourcesused == "") {
		echo '<H3>RECOURCES USED CANNOT BE BLANK - HIT THE BACK BUTTON<H3>';
		GoBackOne();
		die();
	}
	if (isset($_POST['Taxon']) && $_POST['typeDetAnnotation'] != "newdet") {
		$taxon = $_POST['Taxon'];
		$typeDetAnnotation = $_POST['typeDetAnnotation'];

		if (isSpecimen($taxon)) {
			$sql = "select * from Specimen where id = ?";
      $row = $db->getRow($sql, null, array($taxon), null, MDB2_FETCHMODE_ASSOC);
      isMdb2Error($row);
      
			$qtsn = $row['tsnid'];
			$TSNData = GetTSNdata($qtsn);
			$Rank_Id = $TSNData['rank_id'];
			$Kingdom_Id = $TSNData['kingdomid'];
			$Rank_Name = $TSNData['rankname'];
		} else {
			$sql = "select * from DeterminationAnnotation where annotationId = ?";
      $row = $db->getRow($sql, null, array($taxon), null, MDB2_FETCHMODE_ASSOC);
      isMdb2Error($row, "Selecting DeterminationAnnotation");
      
			$qtsn = $row['tsnid'];
			$Rank_Id = $row['rankid'];
			$Kingdom_Id = $row['kingdomid'];
			$Rank_Name = $row['rankname'];
			$suffix = $row['suffix'];
			$prefix = $row['prefix'];
		}
	} elseif (isset($_POST['TSN']) && $_POST['TSN'] != "0" && $_POST['typeDetAnnotation'] == "newdet") {
		$typeDetAnnotation = $_POST['typeDetAnnotation'];
		$qtsn = $_POST['TSN'];
		$TSNData = GetTSNdata($qtsn);
		$Rank_Id = $TSNData['rank_id'];
		$Kingdom_Id = $TSNData['kingdom_id'];
		$Rank_Name = $TSNData['rank_name'];
		$typeDetAnnotation = "agree";
	} else {
		echo '<H3>CONFLICT DETECTED IN SELECTING ETHER A NEW TAXON OR AGREE,DISAGREE, OR QUALIFY LOWEST RANK WITH A PREVIOUS DETERMINATION</H3><BR>';
		ECHO '<H3>HIT THE BACK BUTTON</H3>';
		GoBackOne();
		die();
	}

	if ($typeDetAnnotation == 'agreewq' || $typeDetAnnotation == "newdet") {
		if (isset($_POST['prefix'])) $prefix = $_POST['prefix'];
		if (isset($_POST['suffix'])) $suffix = $_POST['suffix'];
    $typeDetAnnotation = 'agree';
	}
	$sourceOfId = $_POST['sourceOfId'];

	if (isset($_POST['myCollectionId'])) {
		$myCollectionId = $_POST['myCollectionId'];
	} else {
		$myCollectionId = null;
	}
	$materialsUsedInId = $_POST['materialsUsedInId'];
	$resourcesused = $_POST['resourcesused'];
	$annotationLabel = $_POST['annotationLabel'];
	$externalURL = null;
}


if ($title == "") {
	echo '<H3>TITLE CANNOT BE BLANK - HIT THE BACK BUTTON<H3>';
	GoBackOne();
	die();
}
if ($comment == "") {
	echo '<H3>COMMENT CANNOT BE BLANK - HIT THE BACK BUTTON<H3>';
	GoBackOne();
	die();
}

/**********************************************************************************
 if(isset($_POST['Taxon'])) echo "It is set";
 else exit; ************
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

// *******************************************************************************************
//* Update Annotation Record                                                                 *
//********************************************************************************************

$params = array($typeAnnotation, $xLocation, $yLocation, $annotationMarkup, $title, $comment, $XMLData, $annotationLabel, $annotationId);
$sql = "update Annotation SET typeAnnotation = ?, xLocation = ?, yLocation = ?, annotationMarkup = ?, 
          title = ?, comment = ?, XMLData = ?, annotationLabel = ? where id = ?";
$stmt = $db->prepare($sql);
$num_rows = $stmt->execute($params);
isMdb2Error($num_rows, "Updating Annotation $annotationId");
echo '<span style="color:#17256B"><b>Annotation Record with id=[' . $annotationId . '] updated successfuly.</b></span><br/>';
UpdateToPublishDate($annotationId, $dateToPublish);


if ($typeAnnotation == "Determination") {
  $params = array($qtsn, $Rank_Id, $typeDetAnnotation, $sourceOfId, $materialsUsedInId, $prefix, $suffix, $resourcesused);
  $sql = "Update DeterminationAnnotation set tsnId = ?, rankId = ?, typeDetAnnotation = ?, sourceOfId = ?, materialsUsedInId = ?, 
          prefix = ?, suffix = ?, resourcesused = ?";
	// don't update kingdome or rank they are empty
	if (!empty($Kingdom_Id)) {
    $params = array_merge($params, array($Kingdom_Id));
    $sql .= ", kingdomId = ?";
	}
	if (!empty($Rank_Name)) {
    $params = array_merge($params, array($Rank_Name));
		$sql .= ", rankName = ?";
	}
  $params = array_merge($params, array($annotationId));
  $sql .= " where annotationId = ?";
  $stmt = $db->prepare($sql);
  $num_rows = $stmt->execute($params);
  isMdb2Error($num_rows, "Updating DeterminationAnnotation");
}

function isSpecimen($id) {
	$db = connect();
  
	$sql = "select objectTypeId from BaseObject where id = ?";
  $objectTypeId = $db->getOne($sql, null, array($id));
  isMdb2Error($objectTypeId, "Check group name exists");
  
	if ($objectTypeId == "Specimen") {
		return true;
	} else {
		return false;
	}
}

function UpdateToPublishDate($id, $dateToPublish) {
  $db = connect();
  
  $params = array($dateToPublish, $db->mdbNow(), $id);
  $sql = "update BaseObject set dateToPublish = ?, dateLastModified = ? where id = ?";
  $stmt = $db->prepare($sql);
  $num_rows = $stmt->execute($params);
  isMdb2Error($num_rows, "Updating publish date in BaseObject");

  return;
}

function GetTSNdata($tsn) {
  $db = connect();
  
  $sql = "select * from TaxonomicUnits where tsn = ?";
  $row = $db->getRow($query, null, array($tsn), null, MDB2_FETCHMODE_ASSOC);
  isMdb2Error($row, "Select row from TaxonomicUnits");
  if (empty($row)) return;
	
  $sql = "select rank_name from TaxonUnitTypes where rank_id = $row[rank_id] and kingdom_id = $row[kingdom_id]";
  $rank_name = $db->queryOne($sql);
  isMdb2Error($rank_name, "Selecting rank name");
  
  $data['rank_id'] = $row['rank_id'];
	$data['kingdom_id'] = $row['kingdom_id'];
	$data['rank_name'] = $rank_name;
  
	return $data;
}

function GoBackOne() {
	global $config;
	echo '<BR><BR><TABLE align="right" border="0">';
	echo '<TR><TD><A HREF="' . $config->domain . 'Annotation/annotationManager.php"><img src="/style/webImages/buttons/return.png"></a></TD></TR></TABLE>';
}

echo '<table align="right">
    <tr>
  <td><a href = "javascript: window.close();"class="button smallButton"><div>Close</div> </a></td>
  </tr>
  </table>';


echo "</div>";
finishHtml();
