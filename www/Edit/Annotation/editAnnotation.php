<?php
include_once('thumbs.inc.php');

include_once('gettables.inc.php');


//***********************************************************************************
//* @author:  David A. Gaitros                                                       *
//* Date: June 22, 2006                                                              *
//* @package: Edit Annotation                                                        *
//* Description:  This is the MorphBank Annotation Tool.  The basic                  *
//*     idea is to allow authorized users to view images and then enter comments     *
//*     about those images.  There is not restriction as to the number of comments   *
//*     that can be made. Associated with the comments is a title, keywords, type    *
//*     annotation, and a d 256 block of characters for comments.                    *
//***********************************************************************************/
//***********************************************************************************
//* Display the standard MorphBank Header with the Annotation title.                 *
//***********************************************************************************/
function editAnnotation($AnnotationId, $PrevURL)
{
	
	
	
	
	global $annotationMenuOptions;
	global $objInfo;
	global $annotationMenu;
	global $annotationid;
	global $imageId;
	global $config;
	global $popUrl;

	$userId = $objInfo->getUserId();
	$groupId = $objInfo->getUserGroupId();
	$_SESSION['userid'] = $userId;
	$_SESSION['groupid'] = $groupId;
	$PublishDate = date('Y/m/d', (mktime(0, 0, 0, date("m") + 6, date("d") - 1, date("Y"))));
	$link = Adminlogin();
	$results = mysqli_query($link, "select name from User where id=" . $userId);
	$row = mysqli_fetch_array($results);
	$userName = $row['name'];

	include_once('js/pop-update.js');

	echo '<style type="text/css">';
	echo '.scroll{  width:740px; height: 175px; overflow: scroll;}';
	echo '</style>';


	echo '<div class="mainGenericContainer" style="width:750px">';
	echo '<form name="form1" method="post" action="modifyAnnotation.php" enctype="multipart/form-data">';
	echo '<br/><input type="hidden" name="PrevURL" value="' . $PrevURL . '">';


	echo '<input type="hidden" name="pop" value="' . $popUrl . '">';

	$AnnotationData = getAnnotationData($AnnotationId);
	$baseObjectData = getBaseObjectData($AnnotationId);



	$year = substr($baseObjectData['dateToPublish'], 0, 4);
	$month = substr($baseObjectData['dateToPublish'], 5, 2);
	$day = substr($baseObjectData['dateToPublish'], 8, 2);
	$date = mktime(0, 0, 0, $month, $day, $year);
	$today = date('Y-m-d H:j:s');


	if ($baseObjectData['dateToPublish'] <= $today || $baseObjectData['userId'] != $userId) {
		echo '<table width="720"> <td align = "right"> <h3>This annotation cannot be edited because it is published or was created by another user.</h3><br /><br />
         <a href = "javascript: window.close();"class="button smallButton"><div>Cancel</div> </a></td></table>';
		die;
	}
	displayTitle($AnnotationData['objectId']);
	echo '<table class="topBlueBorder" style="z-index=1;"><tr><td><b>Type of Annotation</b></tr><td>';
	displayTypeofAnnotations($AnnotationData['typeAnnotation']);
	echo '</TD></TR></TABLE width="740px">';
	// ************************************************************************************************
	// **  PART OF SECTION THAT CONTAINS THE METHODS THAT APPEAR OR DISAPPEAR DEPENDING UPON THE      *
	// **  TYPE OF ANNOTATION SELECTED.                                                               *
	// ************************************************************************************************
	if ($AnnotationData['typeAnnotation'] == "Determination")
	include_once('determination.inc.php');



	// **************************************************************************************************
	// **   FIELDS COMMON TO ALL ANNOTATIONS AND WILL ALWAYS APPEAR.                                    *
	// **************************************************************************************************
	$comments = "";

	echo '<h3>Common Annotation Fields</h3>';
	echo '<table class="topBlueBorder" width="740">';
	echo '<TR><TD><B>Title <span class="req">*</span></B></TD><TD><Input type="text" tabindex="10" name="title" size="40"
                  value="' . $AnnotationData['title'] . '" title="Enter a title for the Annotation up to 40 characters"></TD></TR>';
	echo '<TR><TD><B>Comments <span class="req">*</span></B></TD><TD><TEXTAREA NAME="comment" rows="5" cols="64"
                 tabindex="11" title="Enter comments associated with this annotation">' . $AnnotationData['comment'] . '</TEXTAREA></TD></TR>';


	echo '<TR><TD><B>Image Label:</B></TD><TD>';
	echo '      <Input type="text" name="annotationLabel" tabindex="12" size=35 title="The label can be entered here or during arrow placement"';
	echo '      value ="' . $AnnotationData['annotationLabel'] . '">';
	echo '&nbsp;<a href="javascript:openPopup(\'' . $config->domain . 'Annotation/ckhadd.php?id=' . $AnnotationData['objectId'] . '\')"><img src="/style/webImages/selectIcon.png"></TD></TR>';
	echo '<TR colspan=3><TD><B>X-Coord </B></TD><TD>';
	echo '      <Input type="text" name="xLocation" size=10 tabindex="13" title="X axis coordinate location of the arrow point in percentage of pixels"';
	echo '      value="' . $AnnotationData['xLocation'] . '"></TD></TR>';
	echo '<TR colspan=3><TD><B>Y-Coord </B></TD><TD>';
	echo '      <Input type="text" name="yLocation" size="10" tabindex="14" title="Y axis coordinate location of the arrow point in percentage of pixels"';
	echo '      value="' . $AnnotationData['yLocation'] . '"></TD></TR>';
	echo '<TR colspan=3><TD><B>Type of markup </B></TD><TD>';
	echo '      <Input type="text" name="arrowc" size="20" tabindex="14" title="The color and shape used for the annotation"';
	echo '      value="' . $AnnotationData['annotationMarkup'] . '"></TD></TR>';
	echo '</table>';
	echo '</table>';
	if ($AnnotationData['typeAnnotation'] == "XML")
	$XMLDisplay = "block";
	else
	$XMLDisplay = "none";
	echo '<div id="XML" style="display:' . $XMLDisplay . '">';
	echo '<Table class="topBlueBorder" width="740px">';
	echo '   <TR><TD><B>XML Data</TD><TD><textarea name="XMLText" rows="15" cols="59" title="XML data cannot be altered directly, you must upload a new file">' . $AnnotationData['XMLData'] . '</textarea></TD></TR>';
	echo '   <TR><TD><B>XML FILE Upload</B></TD><TD Align="left"><Input type="file" name="XMLData" size="40"';
	echo '      tabindex="15"  title="Browse for an NEW REPLACEMENT XML file to include with the Annotation" >';
	echo '</TD></TR>';
	echo '</TABLE></DIV>';
	echo '<Table class="topBlueBorder"  width="740">';
	echo '<TR ><td width="45%" ><b>Date To Publish (YYYY/MM/DD) <span class="req">*</span></b></td>';
	echo ' <td align="left"><input type="text" name="dateToPublish" size="10" tabindex="15" maxlength="10" ';
	echo ' value="' . date('Y/m/d', $date) . '" ';
	//echo ' onfocus="javascript:vDateType=\'2\'"';
	//echo ' onblur="DateFormat(this,this.value,event,true,2);"';
	//echo ' onkeyup="DateFormat(this,this.value,event,true,2);"';
	echo '     title="Enter the date to make this annotation public." />';
	echo '  </td><TD>&nbsp;</td>';
	echo '</tr>';

	echo '</table>';
	echo '</br><b><span class="req">* -Required</span></b>
   <table align="right"><tr>';
	echo '<TD><a href="javascript: checkAnnotationForm();" class="button smallButton"><div>Submit</div></a>';
	echo '<a href="javascript: window.close();" class="button smallButton"><div>Cancel</div></a>';
	echo '<INPUT TYPE=Hidden name="annotationId" value=' . $AnnotationId . '>';
	echo '<INPUT TYPE=Hidden name="typeAnnotation" value=' . $AnnotationData['typeAnnotation'] . '></TD>';
	echo '</TR></TABLE>';

	echo '</form>';


	echo '</div>';
	echo '<script type="text/javascript" src="' . $config->domain . 'js/datescript.js"></script>';
	echo '<script type="text/javascript" src="' . $config->domain . 'js/datescript.js"></script>';
	echo '<script type="text/javascript" src="global.js"></script>';
	//   echoJavaScript();
}

function displayRelatedAnnotations($imageArray, $currTaxonName, $currPrefix, $currSuffix)
{
	echo '<h3>Related Annotations</h3>';
	$OldResults = GetRelated($imageArray);
	if (sizeof($OldResults) < 1) {
		echo '<table class="topBlueBorder" width="660px"><TR><TD><h3>No related Annotations</h3></TD></TD></Table>';
		return;
	}
	// ADDED THIS CODE SO THAT THE SPECIMEN RECORD SHOWS UP AS ONE OF THE DETERMINATIONS RECORDS
	$OldResults = AddSpecimenDetermination($OldResults, $imageArray);
	$DetResults = RemoveDuplicates($OldResults);
	$DetResults = CountSpecimens($DetResults, $OldResults);
	$sized = sizeof($DetResults['id']);
	echo '<Table width="730px" class="topBlueBorder" ><TR><TD>';
	echo '<Table class="topBlueBorder" border="0" width="730px"><TR><TD>&nbsp;</TD><TD><B>Taxonomic Name</TD><TD><B>Taxon Author</TD>';
	echo ' <TD><B>Prefix</B></TD><TD><B>Suffix</B></TD>';
	echo '<TD title="Number who agree with the Determination"><B>A</B></TD>';
	echo '<TD title="Number who disagreed with the Determination"><B>D</TD>';
	echo ' <TD title="Number of specimens associated with this determination and collection of images"><B>S</TD></TR>';


	for ($i = 0; $i < $sized; $i++) {
		if (trim($currTaxonName) == trim($DetResults['TaxonName'][$i]) && $currPrefix == $DetResults['prefix'][$i] && $currSuffix == $DetResults['suffix'][$i]) {
			$checked = 'checked="checked"';
		}

		else
		$checked = "";

		echo '<TR><TD><Input type="radio" name="Taxon" ' . $checked . '  value="' . $DetResults['id'][$i] . '" title="Check here to select this determination"></TD><TD>' . $DetResults['TaxonName'][$i] . '</TD>';
		echo '<TD>' . $DetResults['TaxonAuthor'][$i] . '</TD>';
		echo '<TD>' . $DetResults['prefix'][$i] . '</TD>';
		echo '<TD>' . $DetResults['suffix'][$i] . '</TD>';
		echo '<TD>' . $DetResults['numAgree'][$i] . '</TD>';
		echo '<TD>' . $DetResults['numDisagree'][$i] . '</TD>';
		echo '<TD>' . $DetResults['numSpecimens'][$i] . '</TD></TR>';
	}

	echo '</table></TD></TR></TABLE>';
}

function setTab($instring, $fldlngth)
{
	$size = strlen($instring);
	$numspaces = $fldlngth - $size;
	if ($numspaces < 0)
	return;
	for ($i = 0; $i < $numspaces; $i++)
	echo '&nbsp;';
}


function CountSpecimens($DetResults, $OrigResults)
{
	global $link;
	$size = sizeof($DetResults['id']);
	$origsize = sizeof($OrigResults['id']);
	$querypt1 = "SELECT DISTINCT specimenId,tsnId,prefix,suffix from DeterminationAnnotation where ";
	for ($i = 0; $i < $size; $i++) {
		$querypt2 = " tsnId= " . $DetResults['TsnId'][$i];
		$querypt2 .= ' and prefix="' . $DetResults['prefix'][$i] . '"';
		$querypt2 .= ' and suffix="' . $DetResults['suffix'][$i] . '"';
		$querypt2 .= ' and (specimenId = "' . $OrigResults['specimenId'][0] . '" ';
		for ($j = 1; $j < $origsize; $j++)
		$querypt2 .= ' or specimenId="' . $OrigResults['specimenId'][$j] . '"';
		$querypt2 .= ')';
		$query = $querypt1 . $querypt2;
		$results = mysqli_query($link, $query);
		$numrows = mysqli_num_rows($results);
		$DetResults['numSpecimens'][$i] = $numrows;
	}
	return $DetResults;
}

function RemoveDuplicates($DetResults)
{
	$size = sizeof($DetResults['id']);
	if ($size < 2)
	return $DetResults;
	$counter = 1;
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
			if ($DetResults['TsnId'][$i] == $NewDetResults['TsnId'][$j] && $DetResults['prefix'][$i] == $NewDetResults['prefix'][$j] && $DetResults['suffix'][$i] == $NewDetResults['suffix'][$j]) {
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

function GetAnnotationData($AnnotationId)
{
	global $link;
	$sql = "select * from Annotation where id=" . $AnnotationId;
	$results = mysqli_query($link, $sql);
	echo mysqli_error($link);
	if (!$results)
	return null;
	$row = mysqli_fetch_array($results);
	return $row;
}

function getBaseObjectData($AnnotationId)
{
	global $link;
	$sql = "select * from BaseObject where id=" . $AnnotationId;
	$results = mysqli_query($link, $sql);
	echo mysqli_error($link);
	if (!$results)
	return;
	$row = mysqli_fetch_array($results);
	return $row;
}


function getDeterminationData($AnnotationId)
{
	global $link;
	$sql = "select * from DeterminationAnnotation where annotationId=" . $AnnotationId;
	//echo "query is ".$sql;
	$results = mysqli_query($link, $sql);
	echo mysqli_error($link);
	if (!$results)
	return;
	$row = mysqli_fetch_array($results);
	return $row;
}

function GetRelated($imageId)
{
	$link = AdminLogin();

	$counter = 0;


	$query = "select Annotation.id as id,";
	$query .= " Annotation.objectId as objectId, ";
	$query .= " DeterminationAnnotation.specimenId as specimenId, ";
	$query .= " DeterminationAnnotation.tsnId as TsnId, ";
	$query .= " DeterminationAnnotation.typeDetAnnotation as typeDetAnnotation, ";
	$query .= " DeterminationAnnotation.prefix as prefix, ";
	$query .= " DeterminationAnnotation.suffix as suffix ";
	$query .= " from Annotation ";
	$query .= " join DeterminationAnnotation where Annotation.id = DeterminationAnnotation.annotationId ";
	$query .= " and Annotation.objectId=";
	$query .= $imageId;
	$results = mysqli_query($link, $query);

	$numrows = mysqli_num_rows($results);

	for ($j = 0; $j < $numrows; $j++) {
		$row = mysqli_fetch_array($results);
		$DetResults['id'][$counter] = $row['id'];
		$DetResults['objectId'][$counter] = $row['objectId'];
		$DetResults['specimenId'][$counter] = $row['specimenId'];
		$DetResults['TsnId'][$counter] = $row['TsnId'];
		$DetResults['TaxonName'][$counter] = getTaxonName($row['TsnId']);
		if ($row['TsnId'] > "999000000")
		$DetResults['TaxonAuthor'][$counter] = "Temporary TSN Name";
		else
		$DetResults['TaxonAuthor'][$counter] = getTaxonAuthor($row['TsnId']);
		$DetResults['typeDetAnnotation'][$counter] = $row['typeDetAnnotation'];
		$DetResults['prefix'][$counter] = $row['prefix'];
		$DetResults['suffix'][$counter] = $row['suffix'];
		if ($row['typeDetAnnotation'] == 'disagree') {
			$DetResults['numAgree'][$counter] = 0;
			$DetResults['numDisagree'][$counter] = 1;
		} else {
			$DetResults['numAgree'][$counter] = 1;
			$DetResults['numDisagree'][$counter] = 0;
		}
		$counter++;
	}

	return $DetResults;
}

// ****************************************************************************************************
// Added the following functions to include specimen data with the determination data.                *
// ****************************************************************************************************

function AddSpecimenDetermination($OldResults, $imageArray)
{
	$size = sizeof($OldResults['id']);


	$specimenData = getSpecimenDeterminationData(getSpecimenId($imageArray));
	$OldResults['id'][$size] = $specimenData['specimenId'];
	$OldResults['objectId'][$size] = $imageArray[$i];
	$OldResults['specimenId'][$size] = $specimenData['specimenId'];
	$OldResults['TsnId'][$size] = $specimenData['tsnId'];
	$OldResults['TaxonName'][$size] = $specimenData['taxonName'];
	if ($specimenData['tsnId'] > "999000000")
	$OldResults['TaxonAuthor'][$size] = "Temporary TSN Name";
	else
	$OldResults['TaxonAuthor'][$size] = $specimenData['taxonAuthor'];
	$OldResults['typeDetAnnotation'][$size] = 'agree';
	$OldResults['prefix'][$size] = "none";
	$OldResults['suffix'][$size] = "none";
	$OldResults['numAgree'][$size] = 1;
	$OldResults['numDisagree'][$size] = 0;



	return $OldResults;
}
function getSpecimenId($imageId)
{
	global $link;
	$sql = "select specimenId from Image where id=" . $imageId;
	$results = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($results);
	return $row['specimenId'];
}


function getSpecimenDeterminationData($specimenId)
{
	global $link;
	$sql = "select * from Specimen where id=" . $specimenId;
	$results = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($results);
	$data['specimenId'] = $specimenId;
	$data['tsnId'] = $row['tsnId'];
	$data['taxonName'] = getTaxonName($row['tsnId']);
	$data['taxonAuthor'] = getTaxonAuthor($row['tsnId']);
	return $data;
}

// ****************************************************************************************
// * End of new routines.                                                                 *
//*****************************************************************************************
function displayTypeofAnnotations($currentType)
{
	global $link;
	$sql = "select annotationType as name from AnnotationType";
	$results = mysqli_query($link, $sql);
	$numrows = mysqli_num_rows($results);

	echo '<select disabled id="dropDown" size="1">';
	for ($i = 0; $i < $numrows; $i++) {
		$row = mysqli_fetch_array($results);
		if ($row['name'] == $currentType)
		$Selected = 'selected="selected"';
		else
		$Selected = "";
		echo '<option value=' . $row['name'] . ' ' . $Selected . '>' . $row['name'] . '</option>';
	}
	echo '</selected>';
}


/** 
 * duplicate functions in several places!
 * TODO get rid of these function
 * @param $tsnId
 * @return unknown_type
 */
function getTaxonName($tsnId)
{
	global $link;
	$query = "select scientificName from Tree where tsn=" . $tsnId;
	//echo $query;
	$results = mysqli_query($link, $query);
	$row = mysqli_fetch_array($results);
	return trim($row[0]);
}

function getTaxonAuthor($tsnId)
{
	global $link;
	$query = "select TaxonAuthors.taxon_author from Tree join TaxonAuthors where Tree.taxon_author_id ";
	$query .= " = TaxonAuthors.taxon_author_id and Tree.tsn = " . $tsnId;
	$results = mysqli_query($link, $query);
	$row = mysqli_fetch_array($results);
	return $row['taxon_author'];
}


function getMaterialsExamined()
{
	$MEArray[0] = "Image";
	$MEArray[1] = "Specimen";
	$MEArray[2] = "DNA Sequence";
	$MEArray[3] = "DNA Fingerprinting";
	$CurrIndex = 4;
	$link = Adminlogin();
	$results = mysqli_query($link, "select distinct materialsUsedInId from DeterminationAnnotation");
	$numrows = mysqli_num_rows($results);
	if (numrows < 1) {
		return $MEArray;
	}

	for ($i = 0; $i < $numrows; $i++) {
		$row = mysqli_fetch_array($results);
		if ($row['methodOfId'] != "Image" && $row['methodOfId'] != "Specimen" && $row['MethodOfId'] != "DNA Sequence" && $row['MethodOfId'] != "DNA Fingerprinting") {
			$MEArray[$CurrIndex++] = $row['MethodOfId'];
		}
	}
	return $MEArray;
}

function displayImageList($imageList, $collectionId)
{
	global $config, $link;
	$size = sizeof($imageList);
	$query = "select * from myCollection where id=" . $collectionId;
	$results = mysqli_query($link, $query);
	$row = mysqli_fetch_array($results);
	$name = $row['name'];
	$query = "select * from myCollectionObjects where myCollectionId=" . $collectionId;
	$results = mysqli_query($link, $query);
	$numrows = mysqli_num_rows($results);
	echo "<H2>";
	echo $size . " Images of " . $numrows . " in Collection ";
	echo '<A HREF="' . $config->domain . 'Show/index.php?pop=yes&id=' . $collectionId . '">' . $collectionId . '</A> [' . $name . ']';
	echo "</h2><BR><HR>";
}

function displayTitle($imageId)
{
	global $config, $link;
	echo "<h2>Annotation Record of Image [";
	echo showViewerTag($imageId) . $imageId . '</a>';
	echo "-" . getImageTSN($imageId) . "]</h2><br/><hr/>";
}


function getImageTSN($imageId)
{
	global $link;
	$results = mysqli_query($link, "select tsnId from Image join Specimen where Image.specimenId = Specimen.id and Image.id=" . $imageId);
	//echo mysqli_error($link);
	$row = mysqli_fetch_array($results);
	$tsn = $row['tsnId'];
	$results = mysqli_query($link, "select scientificName from Tree where tsn=" . $tsn);
	$row = mysqli_fetch_array($results);
	return $row['scientificName'];
}

function DisplayThumbs($idArray)
{
	global $config;
	$mbpath = "http://morphbank.scs.fsu.edu/images/jpeg/";
	$numImages = sizeof($idArray);
	echo '<div class="scroll"><TABLE border="1" bordercolor=#000000 cellspacing=0 cellpadding=4 width="770px">';
	echo '<tr>';
	for ($i = 0; $i < $numImages; $i++) {
		$imageData = getallimagedata($idArray[$i]);
		echo '<table height="100px">';
		echo 'Image Record: [' . $idArray[$i] . '] ';
		echo '<br>';
          "<br/></div>";
		$imageloc = getImageUrl($idArray[$i]);
		echo '<a href ="' . $config->domain . 'Show/?id=' . trim($idArray[$i]) . '"><img src = '
		. $imageloc . ' width=100 onMouseOver="javascript:startPostIt(event,\'' . $imageData
		. '\');" onMouseOut="javascript:stopPostIt();"</a>';
		echo '</td>';
	}
	echo '</tr>';
	echo '</table></div>';
}
?>
