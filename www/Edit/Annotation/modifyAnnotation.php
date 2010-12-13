<?php

if ($_REQUEST['pop']) {
	include_once('pop.inc.php');
} else {
	include_once('head.inc.php');
}
include_once('Admin/admin.functions.php');
include_once('updateObjectKeywords.php');
global  $objInfo;

$title = 'Updated Annotation Data';
initHtml($title, null, null);
echoHead(false, $title);

echo '<div class="mainGenericContainer" style="width:700px">';


$userId = $objInfo->getUserId();
$groupId = $objInfo->getUserGroupId();


/**********************************************************************************************
 *  Author: David A. Gaitros                                                                   *
 *  Date:  June 10, 2006                                                                  *
 *
 **********************************************************************************************/
$annotationId = $_POST['annotationId'];
$title = $_POST['title'];
$typeAnnotation = $_POST['typeAnnotation'];
$typeDetAnnotation = $_POST['typeDetAnnotation'];
$comment = $_POST['comment'];
$dateToPublish = $_POST['dateToPublish'];
$resourcesused = $_POST['resourcesused'];
$PrevURL = $_POST['PrevURL'];
//echo '<BR>'.$_POST['prefix'].' '.$_POST['suffix'].'<BR>';
if (isset($_POST['xLocation'])) {
	$xLocation = $_POST['xLocation'];
} else {
	$xLocation = 0;
}
if (isset($_POST['yLocation'])) {
	$yLocation = $_POST['yLocation'];
} else {
	$yLocation = 0;
}
if (isset($_POST['arrowc'])) {
	$annotationMarkup = $_POST['arrowc'];
} else {
	$annotationMarkup = "";
}

if (isset($_POST['annotationLabel'])) {
	$annotationLabel = $_POST['annotationLabel'];
} else {
	$annotationLabel = "";
}
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
		//Echo 'Update by selecting a previous taxon using annotationId='.$taxon;
		$typeDetAnnotation = $_POST['typeDetAnnotation'];

		$link = Adminlogin();
		if (isSpecimen($taxon)) {
			$query = "select * from Specimen where id=" . $taxon;
			$results = mysqli_query($link, $query);
			$row = mysqli_fetch_array($results);
			$qtsn = $row['tsnId'];
			$TSNData = GetTSNdata($qtsn);
			$Rank_Id = $TSNData['rank_id'];
			$Kingdom_Id = $TSNData['kingdomId'];
			$Rank_Name = $TSNData['rankName'];
		} else {
			$query = "select * from DeterminationAnnotation where annotationId=" . $taxon;
			$results = mysqli_query($link, $query);
			$row = mysqli_fetch_array($results);
			$qtsn = $row['tsnId'];
			$Rank_Id = $row['rankId'];
			$Kingdom_Id = $row['kingdomId'];
			$Rank_Name = $row['rankName'];
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

	if ($typeDetAnnotation = 'agreewq' || $typeDetAnnotation = "newdet") {
		if (isset($_POST['prefix']))
		$prefix = $_POST['prefix'];
		if (isset($_POST['suffix']))
		$suffix = $_POST['suffix'];
	}
	$sourceOfId = $_POST['sourceOfId'];

	if ($typeDetAnnotation == 'agreewq' || $typeDetAnnotation == 'newdet') {
		$typeDetAnnotation = 'agree';
	}

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


$link = Adminlogin();

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

$query = "update Annotation SET ";
$query .= 'typeAnnotation="' . $typeAnnotation . '",';
$query .= 'xLocation="' . $xLocation . '",';
$query .= 'yLocation="' . $yLocation . '",';
$query .= 'annotationMarkup="' . $annotationMarkup . '",';
$query .= 'title="' . $title . '",';
$query .= 'comment="' . $comment . '",';
$query .= 'XMLData="' . $XMLData . '", ';
$query .= 'annotationLabel="' . $annotationLabel . '" where id=' . $annotationId;

$results = mysqli_query($link, $query);
if ($results) {
	echo '<span style="color:#17256B"><b>Annotation Record with id=[' . $annotationId . '] updated successfuly.</b></span><br/>';
	UpdateToPublishDate($annotationId, $dateToPublish);
} else {
	echo mysqli_error($link);
	echo '<BR>' . $query . '<BR>';
}

if ($typeAnnotation == "Determination") {
	$queryd = "Update DeterminationAnnotation set ";
	$queryd .= ' tsnId="' . $qtsn . '",';
	$queryd .= ' rankId="' . $Rank_Id . '",';

	// don't update kingdome or rank they are empty
	if (!empty($Kingdom_Id)) {
		$queryd .= ' kingdomId="' . $Kingdom_Id . '",';
	}
	if (!empty($Rank_Name)) {
		$queryd .= ' rankName="' . $Rank_Name . '",';
	}

	$queryd .= ' typeDetAnnotation="' . $typeDetAnnotation . '",';
	$queryd .= ' sourceOfId="' . $sourceOfId . '",';
	$queryd .= ' materialsUsedInId="' . $materialsUsedInId . '",';
	$queryd .= ' prefix="' . $prefix . '",';
	$queryd .= ' suffix="' . $suffix . '",';
	$queryd .= ' resourcesused="' . $resourcesused . '" ';
	$queryd .= 'where annotationId=';
	$queryd .= $annotationId;
	//echo '<BR>'.$queryd;
	$results = mysqli_query($link, $queryd);
	if ($results) {
		//echo '<br/><span style="color:#17256B"><b>Determination for Annotation record:['.$annotationId.'] updated successfuly</b></span><br/>';
	} else {
		echo mysqli_error($link);
		echo '<BR>' . $queryd . '<BR>';
		echo '<BR>' . mysqli_error($link);
	}
}

function isSpecimen($id)
{
	global $link;
	$sql = "select objectTypeId from BaseObject where id=" . $id;
	$results = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($results);
	if ($row['objectTypeId'] == "Specimen") {
		return true;
	} else {
		return false;
	}
}

function UpdateToPublishDate($id, $dateToPublish)
{
	$link = Adminlogin();
	$sql = 'update BaseObject set dateToPublish="' . $dateToPublish . '", dateLastModified=NOW()  where id=' . $id;
	$results = mysqli_query($link, $sql);
	if ($results) {
		//echo "Update baseObject, set Date to publish date to ".$dateToPublish." ".$id;
	} else {
		echo mysqli_error($link);
		echo "<BR>" . $sql . "<BR>";
	}
}
function GetTSNData($tsn)
{
	$link = Adminlogin();
	$sql = "select * from TaxonomicUnits where tsn=" . $tsn;
	$results = mysqli_query($link, $sql);
	if (!$results) return;
	$row = mysqli_fetch_array($results);
	$data['rank_id'] = $row['rank_id'];
	$data['kingdom_id'] = $row['kingdom_id'];
	$sql = "select rank_name from TaxonUnitTypes where rank_id = " . $row['rank_id'] . " and kingdom_id = " . $row['kingdom_id'];
	$results = mysqli_query($link, $sql);
	$row2 = mysqli_fetch_array($results);

	$data['rank_name'] = $row2['rank_name'];
	return $data;
}

function GoBackOne()
{
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
?>
