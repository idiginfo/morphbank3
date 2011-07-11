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

include_once('gettables.inc.php');
include_once('XML.inc.php');

$annotationArray = getAnnotationData($id);
$baseObjectArray = getBaseObjectData($id);

$popUrl = (isset($_GET['pop'])) ? "/Show/?pop=Yes&amp;id=" : "/?id=";

// Made modifications to this point.
// Be sure to include with the anntation data all of the detemination data and other
// types of annotation data to include this with specimenArray data.
$imgId= $annotationArray['objectId'];
$imageArray = getImageData($imgId);
$specimenArray = getSpecimenData($imageArray['specimenId']);

$imgUrl = getObjectImageUrl($annotationArray['objectId'], 'jpeg');

list($width, $height, $imgType) = getSafeImageSize($imgPath);//TODO get rid of size calculation
if ($width>=$height) {
	$sizeParam = 'width="680px"';
} else {
	$sizeParam = 'height="680px"';
}

// Output the content of the main frame
include_once( 'tsnFunctions.php');
if (isset($_GET['pop'])) {
	echo '<div class="popContainer" style="width:700px">';
} else {
	echo '<div class="mainGenericContainer" style="width:700px">';
}
printTitle($id);
echo '
		<div class="annBlueCont">
			<img src="'.$imgUrl.'" '.$sizeParam.' id="image" style="z-index=1;" border="0"  alt="image" />';
DisplayArrow($annotationArray);
echo '
		</div>
			<br />
			<div class="annotationRightFloat"><a href="'.$config->domain.'Annotation/index.php?id='.$imgId.'">Add Annotation</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$config->domain.'Annotation/mailTo.php?objectId='.$id.'">Send Link</a></div>
			<a id="expandContainerLink" href="javascript: expandAnnCont(\'annotationContent\');" >
				<img name="blueTriangle" src="/style/webImages/tree_folder_closed.png" alt="arrow" /> (Display Information...)</a>
			<br />
			<br />
		
		<div class="annBlueFloat" id="annotationContent" style="display:none;">
			<div class="annHalf">
				<h3>Base Obj Info</h3><br/><br/>';
showBaseObjectData($baseObjectArray);
echo'
			</div>
			<div class="annHalf leftBorder">
				<h3>Object Info</h3><br/><br/> ';
displayObjectInfo();
echo '
			</div>
			<div class="annBlueFloat topBoderOnly">
				<h3>Comments</h3><br/><br/>
                '.stripslashes($annotationArray['comment']);
echo '
			</div>';
$xmlDataArray = getXmlData($id);
if (!empty($xmlDataArray['xmlData'])) {
	echo'
				<div class="annBlueFloat topBoderOnly">
					<h3>XML Data</h3><br/><br/>';
	$string = htmlentities($xmlDataArray['xmlData']);
	echo '<pre>'.$string.'</pre>';
	echo'
				</div>';
}
echo'
			<div class="annBlueFloat topBoderOnly">';
showDeterminationAnnotation($specimenArray['specimenId'], TRUE);
echo '
			</div>
			<div class="annBlueFloat topBoderOnly">';
showImageAnnotations($imgId);
echo '
			</div>
		</div>'; // annBlueFloat
echo '</div>';// popContainer or mainGenericContainer
?>
<script language="JavaScript" type="text/javascript">
	
	function expandAnnCont(targetId) {
		var imgN = "blueTriangle";
		if (document.getElementById){
			target = document.getElementById( targetId );
			if (target.style.display == "none"){
				if(document.images)document.images[imgN].src="/style/webImages/tree_folder_open.png";
				target.style.display = "block";
			} 
			else {
				if(document.images)document.images[imgN].src="/style/webImages/tree_folder_closed.png";
				target.style.display = "none";
			}
		}	
	}

</script>
<?php
//*****************************************************************************************************
//*  Modified to display Annotation Record Title.  Display record Id and the title of the             *
//*  annotation.                                                                                      *
//*****************************************************************************************************

function getXmlData($id) {
	global $link;

	$sql = 'SELECT xmlData FROM Annotation where id='.$id;

	$result = mysqli_query($link, $sql);

	if ($result)
	return mysqli_fetch_array($result);
	else
	return FALSE;
}

function bigboxotext($output)
{

	$output = str_replace(chr(10), "<br />", $output);
	$output = str_replace(chr(146), "&#8217;", $output);
	$output = str_replace(chr(130), "&#8218;", $output);
	$output = str_replace(chr(133), "&#8230;", $output);
	$output = str_replace(chr(150), "&ndash;", $output);
	$output = str_replace(chr(151), "&ndash;", $output);
	$output = str_replace(chr(152), "&ndash;", $output);

	$output = str_replace(chr(146), "&#39;", $output); // error 146
	$output = str_replace("'", "&#39;", $output); // error 146
	$output = str_replace(chr(145), "&#39;;", $output); // error 145
	$output = str_replace(chr(147), '"', $output);
	$output = str_replace(chr(148), '"', $output);
	$output = str_replace(chr(151), "&#8212", $output);

	return $output;

}

function displayObjectInfo() {
	global $popUrl, $baseObjectArray, $annotationArray, $specimenArray;


	echo '
	<table border="0" >							
		<tr>
		   <th align="right" width="120px">Specimen Id:</th>
		   <td align="left">
			 <a href="'.$popUrl.$specimenArray['specimenId'].'">['.$specimenArray['specimenId'].']</A></td>
		</tr>
		<tr>
			<th align="right">Sex:</th><td align="left">'.$specimenArray['sex'].'</td>
		</tr>
		<tr>
			<th align="right">Collector:</th><td align="left">'.$specimenArray['collectorName'].'</td>
		</tr>';
	DisplayTsnSpecies($specimenArray['tsnId']);
	echo '
		<tr>
		  <th align="right">Object Id:</th><td align="left">
			<a href="'.$popUrl.$annotationArray['objectId'].'">['.$annotationArray['objectId'].']</A></td>
		</tr>
		<tr>
			<th align="right">Object Type:</th><td align="left">'.$annotationArray['objectTypeId'].'</td>
		</tr>
		<tr>
			<th align="right">Annotation Type: </th><td align="left">'.$annotationArray['typeAnnotation'].'</td>
		</tr>
		
	</table>';

}

function printTitle ($id) {
	global $config;
	$title = stripslashes(getAnnotationTitle($id));
	if (isset($_GET['fromAnn']))
	echo '<h2 class="red">*Annotation for Image '.$id.' Successfully Created!&nbsp;&nbsp;<a href="javascript: window.close();" class="button smallButton right" title="Click here to close this window."><div>Close</div></a></h2>
<br /><br />';
	echo "<h2>Annotation Record: [".$id."] ".$title."</h2>";
}

// ******************************************************************************************************
// *  Displays the arrow on the image if one exists.  The picture is divided into four parts:           *
// *  Upper left, Upper right, Lower left, and Lower Right.  Each has a different arrow and             *
// *  label location so that the arrow and label appear inside the picture and away from the            *
// *  designated point referenced by the arrow.                                                         *
// ******************************************************************************************************

function displayArrow( $aArray) {
	

	if($aArray['xLocation']==Null || $aArray==0) return;
	$x = $aArray['xLocation'];
	$y = $aArray['yLocation'];
	$markerType = $aArray['annotationMarkup'];
	$color = "";

	$arrowBool = (strpos($markerType, "Arrow")===FALSE) ? FALSE : TRUE;
	$squareBool = (strpos($markerType, "Square")===FALSE) ? FALSE : TRUE;

	if (substr_count($markerType, "red")!= 0)
	$color = "red";
	elseif (substr_count($markerType, "white")!= 0)
	$color = "white";
	elseif (substr_count($markerType, "black")!= 0)
	$color = "black";
	else
	$color = "red";

	if ($arrowBool) {
		if (substr_count($markerType, "1") != 0)
		$arrowDirection = "upperLeft";
		elseif (substr_count($markerType, "3") != 0)
		$arrowDirection = "upperRight";
		elseif (substr_count($markerType, "5") != 0)
		$arrowDirection = "lowerRight";
		elseif (substr_count($markerType, "7") != 0)
		$arrowDirection = "lowerLeft";
		else
		$arrowDirection = "upperLeft";

		if($arrowDirection == "upperLeft") {
			if ($color == "red") {
				$img = "/style/webImages/UpperLeftred-trans.png";
			} elseif ($color == "white") {
				$img = "/style/webImages/UpperLeftwhite-trans.png";
			} elseif ($color == "black"){
				$img = "/style/webImages/UpperLeftblack-trans.png";
			} else {
				$img = "/style/webImages/UpperLeftred-trans.png";
			}
			$textX= $x+ $Dist;
			$textY= $y+ $Dist;
		}
		elseif($arrowDirection == "lowerLeft") {
			if ($color == "red") {
				$img = "/style/webImages/LowerLeftred-trans.png";
			} elseif ($color == "white") {
				$img = "/style/webImages/LowerLeftwhite-trans.png";
			} elseif ($color == "black") {
				$img = "/style/webImages/LowerLeftblack-trans.png";
			} else {
				$img = "/style/webImages/LowerLeftred-trans.png";
			}
			$y -= 120;
			$textX = $x + $Dist;
			$textY = $y - $Dist;
		} elseif($arrowDirection == "lowerRight") {
			if ($color == "red") {
				$img = "/style/webImages/LowerRightred-trans.png";
			} elseif ($color == "white") {
				$img = "/style/webImages/LowerRightwhite-trans.png";
			} elseif ($color == "black") {
				$img = "/style/webImages/LowerRightblack-trans.png";
			} else {
				$img = "/style/webImages/LowerRightred-trans.png";
			}
			$x -= 120;
			$y -= 120;
			$textX = $x - $Dist-25;
			$textY = $y - $Dist;
		} elseif($arrowDirection == "upperRight") {
			if ($color == "red") {
				$img = "/style/webImages/UpperRightred-trans.png";
			} elseif ($color == "white"){
				$img = "/style/webImages/UpperRightwhite-trans.png";
			} elseif ($color == "black") {
				$img = "/style/webImages/UpperRightblack-trans.png";
			} else {
				$img = "/style/webImages/UpperRightred-trans.png";
			}
			$x -= 120;
			$textX = $x - $Dist-25;
			$textY = $y + $Dist;
		} else {
			if ($color == "red") {
				$img = "/style/webImages/UpperLeftred-trans.png";
			} elseif ($color == "white") {
				$img = "/style/webImages/UpperLeftwhite-trans.png";
			} elseif ($color == "black") {
				$img = "/style/webImages/UpperLeftblack-trans.png";
			} else {
				$img = "/style/webImages/UpperLeftred-trans.png";
			}
			$textX= $x+ $Dist;
			$textY= $y+ $Dist;
		}
	} elseif ($squareBool) {
		$y -= 120;
		if (substr_count($markerType, "Sm")!= 0){
			$squareSize = "small";
		} elseif (substr_count($markerType, "Md")!= 0) {
			$squareSize = "medium";
		} elseif (substr_count($markerType, "Bg")!= 0) {
			$squareSize = "large";
		} else {
			$squareSize = "small";
		}
		if ($color == "red") {
			if ($squareSize == "small") {
				$img = "/style/webImages/squareSmallred-trans.png";
			} elseif ($squareSize == "medium") {
				$img = "/style/webImages/squareMediumred-trans.png";
			} elseif ($squareSize == "large") {
				$img = "/style/webImages/squareBigred-trans.png";
			} else {
				$img = "/style/webImages/squareSmallred-trans.png";
			}
		}

		elseif ($color == "white") {
			if ($squareSize == "small") {
				$img = "/style/webImages/squareSmallwhite-trans.png";
			} elseif ($squareSize == "medium") {
				$img = "/style/webImages/squareMediumwhite-trans.png";
			} elseif ($squareSize == "large") {
				$img = "/style/webImages/squareBigwhite-trans.png";
			} else {
				$img = "/style/webImages/squareSmallwhite-trans.png";
			}
		} elseif ($color == "black") {
			if ($squareSize == "small"){
				$img = "/style/webImages/squareSmallblack-trans.png";
			} elseif ($squareSize == "medium"){
				$img = "/style/webImages/squareMediumblack-trans.png";
			} elseif ($squareSize == "large") {
				$img = "/style/webImages/squareBigblack-trans.png";
			} else {
				$img = "/style/webImages/squareSmallblack-trans.png";
			}
		} else {
			$img = "/style/webImages/squareMediumred-trans.png";
		}
	} else {
		if ($color == "red") {
			$img = "/style/webImages/UpperLeftred-trans.png";
		} elseif ($color == "white") {
			$img = "/style/webImages/UpperLeftwhite-trans.png";
		} elseif ($color == "black") {
			$img = "/style/webImages/UpperLeftblack-trans.png";
		} else {
			$img = "/style/webImages/UpperLeftred-trans.png";
		}
	}

	if($x==0 && $y==0) return;
	$textX=0;
	$textY=0;
	$Dist = 10;
	$title = $aArray['annotationLabel'];
	echo ' <div id="arrow1" class="overlay" style="z-index=3; position:absolute; top:'.$y.'px; left:'.$x.'px">
												<img src="'.$img.'" alt="'.$arrowDirection.'" title="'.$arrowBool.'" width="120" height="120" /> ';
	echo '</div>';
	if($title !="") {
		echo ' <div id="titlefield" border="3" class="overlay" style="z-index=3; position:absolute; top:'.$textY.'%; left:'.$textX.'%">';
		echo '<table border="2" bgcolor=#ffffff><tr><td>';
		echo $title;
		echo '</td></tr></table>';
		echo '</div>';
	}
}
// ******************************************************************************************************
// * Given a taxonomic $id, return the kingdom name.                                                    *
// ******************************************************************************************************

function getKingdomName($id) {
	global $link;
	$sql = "Select kingdom_name from Kingdoms k where t.Kingdom_Id=$id ";
	$results = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($results);
	return $row[0];
}


// ******************************************************************************************************
// *  Similar to getTsnSpecies routine in the gettables.inc.php include file, this routine              *
// *  returns the complete name for a given TSN id.                                                     *
// ******************************************************************************************************

// TODO remove yet another function to fetch scientific name from tsn
function GetTaxonomyName($id) {
	$results=mysqli_query($link, 'select * from Tree where tsn='.$id);
	if(!results) return;
	$row = mysqli_fetch_array($results);
	$Name = $row['unit_name1'].' '.$row['unit_name2'].' '.$row['unit_name3'].' '.$row['unit_name4'];
	return $Name;
}

// ******************************************************************************************************
// *  Checks a character string to for the <?xml characters.  This is a simple check to see if the      *
// *  string might be an XML document.  A more complex check needs to be developed.                     *
// ******************************************************************************************************

function IsXML($data) 	{
	if(strstr($data,'<?xml')) return trUE;
	else return FALSE;
}

function DisplayAnnotationData($annotationArray) {
	
	include_once('XML.inc.php');
	echo '<tr><td><h3>'.$annotationArray['typeAnnotation'].' Data </h3></td></tr>';
	$XMLData = StripSlashes($annotationArray['XMLData']);
	if(IsXML($XMLData)) {
		GetXMLTable($XMLData);
	} else {
		echo '<tr><td>'.$annotationArray['XMLData'].'</td></tr>';
	}
}

// ******************************************************************************************************
// *  In the Show function, this displays the determination data if the current image and annotation    *
// *  record is of type Determination.                                                                  *
// *  Othersize, No Additional Data is displayed.                                                       *
// ******************************************************************************************************

function displayDeterminationData ($id) {
	global $link;
	$sql = "SELECT * from DeterminationAnnotation where annotationId=".$id;
	$result = mysqli_query($link, $sql);
	if(!$result) return;
	$numrows = mysqli_num_rows($result);
	if($numrows<=0) {
		echo "<tr><td><h3>No Additional Data</h3></td></tr>";
		return;
	}

	$row = mysqli_fetch_array($result);
	$Kingdom_Name = getKingdomName($row['kingdomId']);
	$TSN_Name = GetTaxonomyName($row['tsnId']);
	$Rank_Name = GetRankName($row['kingdomId'],$row['rankId']);
	$Prefix = $row['prefix'];
	$Suffix = $row['suffix'];
	$typeDetAnnotatoin = $row['typeDetAnnotation'];

	echo '
            <tr><td><h3>Determination Data</h3></td></tr>
          <tr>
             <th align="right">Specimen Id:</th><td align="left">['.$row['specimenId'].']</td>
          </tr>
          <tr>
             <th align="right">Taxonomic Serial Number:</th><td align="left">['.$row['tsnId'].']</td>
          </tr>
          <tr>
             <th align="right">Taxonomic Name:</th><td align="left">['.$TSN_Name.']</td>
         </tr>
         <tr>
            <th align="right">Prefix:</th><td align="left">['.$row['prefix'].']</td>
         </tr>
         <tr>
            <th align="right">Suffix:</th><td align="left">['.$row['suffix'].']</td>
         </tr>
         <tr>
             <th align="right">Type Determination:</th><td align="left">['.$row['typeDetAnnotation'].']</td>
         </tr>
         <tr>
            <th align="right">Source of Id:</th><td align="left">['.$row['sourceOfId'].']</td>
         </tr>
         <tr>
            <th align="right">Resources used in Id:</th><td align="left">['.$row['resourcesused'].']</td>
         </tr>
         <tr>
            <th align="right">Materials used in Id:</TH><td algin="left">['.$row['materialsUsedInId'].']</td>
         </tr>          
          ';

}
// ******************************************************************************************************
// * Routine to simply extract the Anntation title.  I would recommend that in the future the we perform*
// * one query at the start of the routine to extract all Annotation and DeterminationAnnotation data   *
// * to improve the efficincies.                                                                        *
// ******************************************************************************************************


function getAnnotationTitle($id) {
	global $link;
	$sql = "Select * from Annotation where id=".$id;
	$result = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($result);
	return  $row['title'];
}

function getAnnotationData ($id) {
	global $link;
	$sql = 'SELECT Annotation.* FROM Annotation ';
	$sql .= 'WHERE Annotation.id = '.$id;
	$result = mysqli_query($link, $sql);
	if ($result != FALSE) {
		$numRows = mysqli_num_rows($result);
		if ($numRows = 1) {
			$array = mysqli_fetch_array($result);
			return $array;
		}
	}
}

function getSpecimenData ($id) {
	global $link;
  
  if (empty($id)) return;
  
	$sql = 'SELECT Specimen.*, Specimen.id AS specimenId, Locality.*, Image.id AS imageid, '
	.'Specimen.sex AS sex, Specimen.form AS form, Specimen.DevelopmentalStage AS developmentalStage '
	.'FROM Specimen '
	.'LEFT JOIN Image ON Specimen.id = Image.specimenId '
	.'LEFT JOIN Locality ON Locality.id = Specimen.localityId ';
	$sql .= 'WHERE Specimen.id = '.$id.'';
	$result = mysqli_query($link, $sql) or die(mysqli_error($link));
	if ($result) {
		$numRows = mysqli_num_rows($result);
		if ($numRows = 1) {
			$array = mysqli_fetch_array($result);
			return $array;
		}
	}
}

function getImageData($id) {
	global $link;
	$sql = 'SELECT * FROM Image where id='.$id;
	$result = mysqli_query($link, $sql);
	if($result == FALSE) return;
	$array = mysqli_fetch_array($result);
	return $array;
}

function DisplayTsnSpecies ($tsnId) {
	
	include_once ('tsnFunctions.php');
	global  $config, $browseByTaxonHref, $browseByNameHref;
	global $link;
	$sql='SELECT scientificName, rank_name from Tree left join TaxonUnitTypes on';
	$sql .= ' Tree.rank_id = TaxonUnitTypes.rank_id where tsn='.$tsnId;
	$results = mysqli_query($link, $sql);
	if(!$results) return;
	$row = mysqli_fetch_array($results);
	$name = $row['scientificName'];
	echo
        '<tr>
		 <th align="right" valign="top">'.$row['rank_name'].' Name:</th><td Title="Describes the lowest level identification">'.$name.'</td>
	</tr>';

}
/****************************************************************************************
 *                                                                                       *
 *  Routine/Module Name: DisplayAnnotations                                               *
 *                                                                                       *
 *  Parameter Description:$id: Image Id                                                  *
 *                                                                                       *
 *  Description: Temporary routine to test the possibilty of displaying annotations      *
 *    in a table area.                                                                   *
 *                                                                                       *
 *  Author: David A. Gaitos, MorphBank Project Director                                  *
 *  Date: Dec 13, 2005                                                                   *
 ***********************************************s*****************************************/
function DisplayAnnotations($id) {
	global $config;
	global $link;
	$query = "select Annotation.id as id , Annotation.objectId as objectId, Annotation.typeAnnotation as typeAnnotation, User.name as name ,BaseObject.dateCreated dateCreated ,Annotation.title as title ";
	$query .= "from Annotation join BaseObject join User where Annotation.id = BaseObject.id and ";
	$query .= "BaseObject.userId = User.id  and ";
	$query .= "Annotation.objectId=".$id." ";
	$query .= " order by BaseObject.dateCreated";
	$results = mysqli_query($link, $query);
	if($results){
		$numrows = mysqli_num_rows($results);
		echo '<div class="annotationContainer" style="width:95%">';
		echo '<Table >';
		for($index=0; $index<$numrows; $index++) {
			$row = mysqli_fetch_array($results);
			$title = $row['title'];
			$Annotationid    = $row['id'];
			$dateCreated = $row['dateCreated'];
			$name = $row['name'];
			echo '<tr><td>';
			echo '<BR><b>TITLE:</B> '.$title;
			echo '<BR><b>TYPE ANNOTATION: </b>'.$row['typeAnnotation'];
			echo '<BR><b>BY:</b>'.$name;
			echo '<BR><b>DATE CREATED:</b> '.$dateCreated.'</td></tr>';
			echo '<tr><td title="Select to view all related Annotations" Border="2"><A HREF="'.$config->domain.'Annotation/relatedAnnotationsIndex.php?id='.$row['objectId'].'&annotationid='.$Annotationid.'">';
			echo '<b>RELATED ANNOTATIONS OF ID:</b>['.$Annotationid.']</a></td></tr>';
			echo '<tr><td title="Select to view Show data of this Annotation" Border="2"><A HREF="'.$config->domain.'Show/?id='.$Annotationid.'"><b>SINGLE SHOW OF ANNOTATION ID:</b>['.$Annotationid;
			echo ']</a></td></tr>';
		}
		echo '</TABLE>';
		echo '</div>';
	} else {
		echo "No related Annotations to this Image or Specimen";
	}
}


// **********************************************************************************************
// *  Code needed to display the list of related annotations.                                   *
// **********************************************************************************************
function displayRelatedAnnotations($imageArray) {
	$size = sizeof($imageArray);
	if($size < 1) return;
	echo '<h3>Related Annotations</h3>';
	$OldResults = GetRelated($imageArray);
	// ADDED THIS CODE SO THAT THE SPECIMEN RECORD SHOWS UP AS ONE OF THE DETERMINATION RECORDS.
	$OldResults = AddSpecimenDetermination($OldResults,$imageArray);
	if(sizeof($OldResults)<1) {
		echo '<table class="topBlueBorder" width="100%"><tr><td><h3>No related Annotations</h3></td></td></Table>'; return;
	}
	$DetResults = RemoveDuplicates($OldResults);
	$DetResults = CountSpecimens($DetResults,$OldResults);
	$sized = sizeof($DetResults['id']);
	echo '<Table width="100%" class="topBlueBorder" ><tr><td>';
	echo '<Table width="100%" class="topBlueBorder">';
	echo '<tr><td><B>Taxonomic Name</td><td><B>Taxon Author</td>';
	echo '<td><B>Prefix</B></td><td><B>Suffix</B></td>';
	echo '<td title="Number who Agreed with the Determination"><B>A</td>';
	echo '<td title="Number who disagreed with the Determination"><B>D</td>';
	echo ' <td title="Number of specimens associated with this determination and collection of images"><B>S</td></tr>';
	for ($i=0; $i<$sized; $i++) {
		echo '<tr><td>'.$DetResults['TaxonName'][$i].'</td>';
		echo '<td>'.$DetResults['TaxonAuthor'][$i].'</td>';
		echo '<td>'.$DetResults['prefix'][$i].'</td>';
		echo '<td>'.$DetResults['suffix'][$i].'</td>';
		echo '<td>'.$DetResults['numAgree'][$i].'</td>';
		echo '<td>'.$DetResults['numDisagree'][$i].'</td>';
		echo '<td>'.$DetResults['numSpecimens'][$i].'</td></tr>';
	}
	echo '</table></td></tr></table>';
}

function CountSpecimens($DetResults,$OrigResults) {
	global $link;
	$size = sizeof($DetResults['id']);
	$origsize = sizeof($OrigResults['id']);

	for($i=0; $i<$size; $i++) {
		$querypt1 = "SELECT DISTINCT specimenId,tsnId,prefix,suffix from DeterminationAnnotation where ";
		$querypt2 = " tsnId= ".$DetResults['TsnId'][$i];
		$querypt2.= ' and prefix="'.$DetResults['prefix'][$i].'"';
		$querypt2.= ' and suffix="'.$DetResults['suffix'][$i].'"';
		$querypt2.= ' and (specimenId = "'.$OrigResults['specimenId'][0].'" ';
		for ($j=1; $j<$origsize; $j++) {
			$querypt2.= ' or specimenId="'.$OrigResults['specimenId'][$j].'"';
		}
		$querypt2.= ')';
		$query=$querypt1.$querypt2;
		$results = mysqli_query($link, $query);
		$numrows = mysqli_num_rows($results);
		// ****************************************************************************************
		// **  If the number of rows is empty then that means that we have the siutation          *
		// **  where we have a specimen with no determination annotations.  So we count           *
		// **  the actual number of specimens and replace that number.                            *
		// ****************************************************************************************
		if($numrows==0) {
			$querypt2 ="";
			$querypt1 = 'select distinct id from Specimen where id = "'.$OrigResults['specimenId'][0].'"';
			for($j=1; $j<$origsize; $j++){
				$querypt2.= ' or id="'.$OrigResults['specimenId'][$j].'"';
			}
			$query=$querypt1.$querypt2;
			$results = mysqli_query($link, $query);
			echo mysqli_error($link);
			$numrows = mysqli_num_rows($results);
		}
		$DetResults['numSpecimens'][$i] = $numrows;
	}
	return $DetResults;
}

function RemoveDuplicates($DetResults)
{
	$size = sizeof($DetResults['id']);
	if($size <2) return $DetResults;
	$counter = 1;
	$NewDetResults['id'][0] = $DetResults['id'][0];
	$NewDetResults['objectId'][0] = $DetResults['objectId'][0];
	$NewDetResults['specimenId'][0] = $DetResults['specimenId'][0];
	$NewDetResults['TsnId'][0] = $DetResults['TsnId'][0];
	$NewDetResults['TaxonName'][0] =  $DetResults['TaxonName'][0];
	$NewDetResults['TaxonAuthor'][0] = $DetResults['TaxonAuthor'][0];
	$NewDetResults['typeDetAnnotation'][0] = $DetResults['typeDetAnnotation'][0];
	$NewDetResults['prefix'][0] = $DetResults['prefix'][0];
	$NewDetResults['suffix'][0] = $DetResults['suffix'][0];
	$NewDetResults['numAgree'][0] = 0;
	$NewDetResults['numDisagree'][0] = 0;
	for ($i=0; $i<$size; $i++) {
		$insert=1;
		for ($j=0; $j<$counter; $j++) {
			if($DetResults['TsnId'][$i]  == $NewDetResults['TsnId'][$j] &&
			$DetResults['prefix'][$i] == $NewDetResults['prefix'][$j] &&
			$DetResults['suffix'][$i] == $NewDetResults['suffix'][$j])
			$insert=0;
			$NewDetResults['numAgree'][$j] += $DetResults['numAgree'][$i];
			$NewDetResults['numDisagree'][$j] += $DetResults['numDisagree'][$i];
		}
		if($insert==1) {
			$NewDetResults['id'][$counter] = $DetResults['id'][$i];
			$NewDetResults['objectId'][$counter] = $DetResults['objectId'][$i];
			$NewDetResults['specimenId'][$counter] = $DetResults['specimenId'][$i];
			$NewDetResults['TsnId'][$counter] = $DetResults['TsnId'][$i];
			$NewDetResults['TaxonName'][$counter] =  $DetResults['TaxonName'][$i];
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

function GetRelated($imageArray)
{
	//  AdminLogin();
	$size=sizeof($imageArray);
	$counter = 0;
	global $link;
	for($i=0; $i<$size; $i++) {
		$query = "select Annotation.id as id,";
		$query .=" Annotation.objectId as objectId, ";
		$query .=" DeterminationAnnotation.specimenId as specimenId, ";
		$query .=" DeterminationAnnotation.TsnId as TsnId, ";
		$query .=" DeterminationAnnotation.typeDetAnnotation as typeDetAnnotation, ";
		$query .=" DeterminationAnnotation.prefix as prefix, ";
		$query .=" DeterminationAnnotation.suffix as suffix ";
		$query .=" from Annotation ";
		$query .=" join DeterminationAnnotation where Annotation.id = DeterminationAnnotation.annotationId ";
		$query .=" and Annotation.objectId=";
		$query .=$imageArray[$i];
		$results = mysqli_query($link, $query);

		$numrows = mysqli_num_rows($results);

		for ($j=0; $j<$numrows; $j++) {
			$row = mysqli_fetch_array($results);
			$DetResults['id'][$counter] = $row['id'];
			$DetResults['objectId'][$counter] = $row['objectId'];
			$DetResults['specimenId'][$counter] = $row['specimenId'];
			$DetResults['TsnId'][$counter] = $row['TsnId'];
			$DetResults['TaxonName'][$counter] = getTaxonName($row['TsnId']);
			if($row['TsnId'] >"999000000"){
				$DetResults['TaxonAuthor'][$counter] = "Temporary TSN Name";
			} else {
				$DetResults['TaxonAuthor'][$counter] = getTaxonAuthor($row['TsnId']);
			}
			$DetResults['typeDetAnnotation'][$counter] = $row['typeDetAnnotation'];
			$DetResults['prefix'][$counter] = $row['prefix'];
			$DetResults['suffix'][$counter] = $row['suffix'];
			if($row['typeDetAnnotation']=='disagree') {
				$DetResults['numAgree'][$counter]=0;
				$DetResults['numDisagree'][$counter]=1;
			} else {
				$DetResults['numAgree'][$counter]=1;
				$DetResults['numDisagree'][$counter]=0;
			}
			$counter++;
		}
	}
	return $DetResults;
}
// ****************************************************************************************************
// Added the following functions to include specimen data with the determination data.                *
// ****************************************************************************************************

function AddSpecimenDetermination($OldResults, $imageArray){
	global $link;
	$size=0;
	$specimenData = getSpecimenDeterminationData(getSpecimenId($imageArray));
	$OldResults['id'][$size] = $specimenData['specimenId'];
	$OldResults['objectId'][$size] = $imageArray;
	$OldResults['specimenId'][$size]=$specimenData['specimenId'];
	$OldResults['TsnId'][$size] = $specimenData['tsnId'];
	$OldResults['TaxonName'][$size]= $specimenData['taxonName'];
	if($specimenData['tsnId']>"999000000") {
		$OldResults['TaxonAuthor'][$size]="Temporary TSN Name";
	} else {
		$OldResults['TaxonAuthor'][$size]=$specimenData['taxonAuthor'];
	}
	$OldResults['typeDetAnnotation'][$size]='agree';
	$OldResults['prefix'][$size]="none";
	$OldResults['suffix'][$size]="none";
	$OldResults['numAgree'][$size]=1;
	$OldResults['numDisagree'][$size++]=0;
	// Right here add code to add the rest of the determination annotation data.
	// remember this is for a single image which only has one specimen.
	$sql = "select Annotation.*, DeterminationAnnotation.* from Annotation join DeterminationAnnotation where Annotation.id = DeterminationAnnotation.annotationId and ";
	$sql .= 'Annotation.objectId ='.$imageArray;
	$results = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($results);
	$numrows = mysqli_num_rows($results);
	echo $numrows;
	for ($i=0; $i<$numrows; $i++) {
		$OldResults['id'][$size]          = $row['specimenId'];
		$OldResults['objectId'][$size]    = $row['objectId'];
		$OldResults['TsnId'][$size]       = $row['tsnId'];
		$OldResults['TaxonName'][$size]   = getTsnSpecies($row['tsnId']);
		if($row['tsnId']>"999000000") $OldResults['TaxonAuthor'][$size]="Temporary TSN Name";
		else $OldResults['TaxonAuthor'][$size]= getTaxonAuthor($row['tsnId']);
		$OldResults['typeDetAnnotation'][$size] = $row['typeDetAnnotation'];
		$OldResults['prefix'][$size]      = $row['prefix'];
		$OldResults['suffix'][$size]      = $row['suffix'];
		if($row['typeDetAnnotation'] =='agree') {
			$OldResults['numAgree'][$size]=1;
			$OldResults['numDisagree'][$size]=0;
		} else {
			$OldResults['numAgree'][$size]=0;
			$OldResults['numDisagree'][$size]=1;
		}
		$size++;
		$row = mysqli_fetch_array($results);
	}
	return $OldResults;
}

function getSpecimenId($imageId) {
	global $link;
	$sql = "select specimenId from Image where id=".$imageId;
	$results = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($results);
	return $row['specimenId'];
}
function getSpecimenDeterminationData($specimenId) {
	global $link;
	$sql = "select * from Specimen where id=".$specimenId;
	$results = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($results);
	$data['specimenId'] = $specimenId;
	$data['tsnId'] = $row['tsnId'];
	$data['taxonName'] = getTaxonName($row['tsnId']);
	$data['taxonAuthor'] = getTaxonAuthor($row['tsnId']);
	return $data;
}

//TODO get rid of scientific name and author functions
function getTaxonName($tsnId) {
	global $link;
	$query ="select * from Tree where tsn=".$tsnId;
	$results = mysqli_query($link, $query);
	$row = mysqli_fetch_array($results);
	return $row['unit_name1'];' '.$row['unit_name2'].' '.$row['unit_name3'].' ' .$row['unit_name4'];
}

function getTaxonAuthor($tsnId) {
	global $link;
	$query = "select TaxonAuthors.taxon_author from Tree join TaxonAuthors where Tree.taxon_author_id ";
	$query .= "= TaxonAuthors.taxon_author_id and Tree.tsn = ".$tsnId;
	$results = mysqli_query($link, $query);
	mysqli_error($link);
	$row = mysqli_fetch_array($results);
	return $row['taxon_author'];
}
// ****************************************************************************************
// * End of new routines.                                                                 *
//*****************************************************************************************
?>
