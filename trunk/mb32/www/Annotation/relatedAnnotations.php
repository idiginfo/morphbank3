<?php


include_once('thumbs.inc.php');

include_once('gettables.inc.php');
include_once('libjs/annotateMenu.php');

/**
 * Display the standard MorphBank Header with the Annotation title.
 * @param $imageId
 * @param $PrevURL
 * @return unknown_type
 */
function relatedAnnotations($imageId, $PrevURL)
{
	
	
	
	
	global $annotationMenuOptions;
	global $annotationMenu;
	global $objInfo;
	global $imageId;
	global $config;


	echo '<form method="post" action="relatedAnnotationsindex.php">';
	//***********************************************************************************
	//*  Surround the display of the page with the MorphBank containers for style and    *
	//*  check to see if the userid and Image id have been passed into the page.  If	*
	//*  not, we cannot proceed and we exit the page.  In the future, we will want	  *
	//*  to transfer to the Login page and have the user login before proceeding.  We    *
	//*  also want to make sure that the person has permissions to access and annotate   *
	//*  this range of taxon.												*
	//*  Likewise we like the sorting of the annotations to be persistant.  If a new	*
	//*  request is sent to the web page via a $_GET then we reset the session variable  *
	//*  to the new value. The variable $sortby is always given the correct value and    *
	//*  the session variable set to that.									  *
	//***********************************************************************************

	if (isset($_GET['sortby'])) {
		$sortby = $_GET['sortby'];
	} elseif (isset($_SESSION['sortby'])) {
		$sortby = $_SESSION['sortby'];
	} else {
		$sortby = 1;
	}
	$_SESSION['sortby'] = $sortby;

	$useId = $objInfo->getUserId();
	$groupId = $objInfo->getUserGroupId();

	if (isset($_GET['page'])) {
		$page = $_GET['page'];
	} else {
		$page = 1;
	}

	$title = " ";
	$keywords = " ";
	$annoId = $_GET['annotationid'];
	$sort = $_GET['sortby'];


	$typeannotation = " ";
	$comment = " ";
	$link = Adminlogin();
	$firstannotation = getfirstannotation($imageId);
	echo '<div id="main">';

	if (isset($_SESSION['annotationid'])) {
		$annotationid = $_SESSION['annotationid'];
	} elseif (isset($_GET['annotationid'])) {
		$annotationid = $_GET['annotationid'];
	} else {
		$annotationid = $firstannotation;
	}

	$typeCollection = "specimen=1";

	if (isset($_GET['view'])) {
		$idArray = getView($imageId);
		$typeCollection = "view=1";
	} elseif (isset($_GET['species'])) {
		$idArray = getSpecies($imageId);
		$typeCollection = "species=1";
	} elseif (isset($_GET['collection'])) {
		$collectionId = $imageId;
		$typeCollection = "collection=" . $collectionId;
		$idArray = getCollection($collectionId);
	} else {
		$idArray = getSpecimen($imageId);
	}

	$annoId = $annotationid;
	if (isset($_GET['sortby'])) {
		$sort = $_GET['sortby'];
	} else {
		$sort = "";
	}

	//***********************************************************************************
	//*  This code checks to see if an annotation id has been sent to the page via the   *
	//*  html URL.  If so, we are to display this annotation instead of blank fields	*
	//*  in the lower part of the page.										*
	//***********************************************************************************
	$ANNOTATION = 0;
	if (isset($_GET['annotationid']) || $firstannotation != null) {
		if (isset($_GET['annotationid'])) {
			$annotationid = $_GET['annotationid'];
		} else {
			$annotationid = $firstannotation;
		}
		$row = get_arecord("Annotation", "id", $annotationid);
		$title = $row['title'];
		$keywords = $row['keywords'];
		$typeannotation = $row['typeAnnotation'];
		$comment = $row['comment'];
		$xLocation = $row['xLocation'];
		$yLocation = $row['yLocation'];
		$annotationlabel = $row['annotationLabel'];
		if ($xLocation > 0) $ANNOTATION = 1;
	}

	//***********************************************************************************
	//*  Get the Image from the file and resize it to 1/2 of it's original for display   *
	//*  purposes.														 *
	//*  At this point I need to normalize the Image to a certain size. Either shrink    *
	//*  or grow.														  *
	//***********************************************************************************
	$imageloc = getImageUrl($imageId, 'jpg');
	list($fileSize, $width, $height, $imgTypeNumber) = getRemoteImageSize($imageId, 'jpg');
	//TODO get rid of size calculation
	$rwidth = $width;
	$rheight = $height;
	
	//************************************************************************************
	//*  Normalize the width to 680 pixels. Adjust the height to maintain the correct	*
	//*  ration.														    *
	//************************************************************************************
	$FPWidth = $width;
	$FPHeight = $height;
	if ($FPWidth!=0){
		$factor = 680.0 / $FPWidth;
	} else {
		$factor = 1;
	}

	$height = $height * $factor;
	$width = $width * $factor;
	$ImageData = getallimagedata($imageId);
	$postItContents = str_replace("ImageId", "Image Id", $ImageData);
	makeTestMenu($imageId, $width, $height);
	echo "<br/>";
	$X_ArrowOffset = 0;
	$Y_ArrowOffset = 0;
	$tdHeight = $height + 20;

	echo '<div class="mainGenericContainer" style="width:740px">';

	displayTitle($imageId);

	//***********************************************************************************
	//*  Display the table and form that allows us to read in and display the annotation *
	//*  data. Note there is a table within a table that allows us to display related    *
	//*  annotation data in a scrolled area.									*
	//*  One more note,  The order of index has been changed so that when a user	    *
	//*  tabs through the fields they do not encroach into the scolled area but instead  *
	//*  go through the title, keywords, type annotation, and comment fields first.	 *
	//************************************************************************************
	echo '<table border="1" bordercolor="#000000" cellspacing=0 cellpadding=4 width=100% height=100%>';
	echo '<tr><td width="740px" height="' . $tdHeight . 'px">';
	echo '<div id="oDiv" style="position:absolute; top:105px; height:' . $height . 'px; width:' . $width . 'px; overflow:auto;">';
	echo getImageViewerPostitTag($id, $imageloc, $width, $height, $postItContents);
	if ($ANNOTATION == 1)
	displayArrow($row);
	// End the div id="oDiv"
	echo '</div>';
	echo '&nbsp;</td></tr>';
	echo '</table>';

	echo '</div>';
	echo '<div class="mainGenericContainer" style="width:740px">';
	echo '<form action="addannotation.php" method=post>';
	echo '<table border="1" width="750px">';
	echo '<tr width=' . $max . '>';
	displayAnnotationData($annotationid);

	DisplayAnnotations($imageId, $sortby, $annotationid, $page, $typeCollection);
	echo '</tr>';
	echo '</table><table>';
	echo '<td><input type="hidden" name="userId" value=' . $userId . '></td>';
	echo '<td><input type="hidden" name="groupId" value=' . $groupId . '></td>';
	echo '<td><input type="hidden" name="imageId" value =' . $imageId . '></td>';
	echo '</tr>';
	echo '</table>';
	if (isset($_GET['annotationid'])) {
		printButtons(0, $imageId, $userId, $PrevURL);
	} else {
		printButtons(1, $imageId, $userId, $PrevURL);
	}

	echo '</form>';


	echo '<div class="scroll" style="width:740px;">';


	if ($idArray == 0) {
		echo 'No Images to display';
	} else {
		$size = sizeof($idArray);
		//**********************************************************************************
		// Display Navigation Bar for the thumbnails and thumbnails.
		//**********************************************************************************
		DisplayNavBar($idArray, $imageId, $annotationid, $page, $typeCollection);
	}
	echo '</div></html></div>';
	include_once($config->domain . 'includes/footer.inc.php');
	echo '</div>';
}

function displayAnnotationData($id) {
	global $config;
	echo '<td><table class="topBlueBorder" width="360px" cellspacing="0" cellpadding="2">';
	if ($id == null) {
		echo '<tr><td align="center"><H3>No Annotations For This Image</h3></td></tr></table>';
		return;
	}
	$annotationArray = getAnnotationData($id);
	$baseObjectArray = getBaseObjectData($id);
	$imageArray = getImageData($annotationArray['objectId']);
	$specimenArray = getSpecimenData($imageArray['specimenId']);

	displayDataLine("Contributed by:", $baseObjectArray['userName'], null);
	displayDataLine("Date Contributed:", $baseObjectArray['dateCreated'], null);
	displayDataLine("Publish Date:", $baseObjectArray['dateToPublish'], null);
	displayDataLine("Title:", $annotationArray['title'], null);
	displayDataLine("Species Name:", getTsnSpecies($specimenArray['tsnId']), null);
	displayDataLine("Related Specimen:", $specimenArray['id'], "/Show/?pop=Yes&amp;id=" . $specimenArray['id']);
	displayDataLine("Type Annotation:", $annotationArray['typeAnnotation'], null);
	if ($annotationArray['typeAnnotation'] == "Determination")
	displayDataLine("Determination Record:", $annotationArray['id'], "/Show/?pop=Yes&amp;id=" . $annotationArray['id']);
	else
	displayDataLine("Single Show Annotation Record:", $annotationArray['id'], "/Show/?pop=Yes&amp;id=" . $annotationArray['id']);
	echo '<div class="scoll" style="width:320px;">';
	echo '<tr><td align="center" colspan="3">' . $annotationArray['comment'] . '</td></tr>';
	echo '</div>';
	echo '</table></td>';
}



function displayDataLine($name, $value, $url)
{
	if ($url == null)
	echo '<tr><td><th align="right">' . $name . '</th></td><td align="left">' . $value . '</td></tr>';
	else
	echo '<tr><td><th align="right">' . $name . '</th></td><td align="left"><a href="' . $url . '">[' . $value . ']</a></td></tr>';
}


function displayArrow($aArray)
{
	

	if ($aArray['xLocation'] == null || $aArray == 0)
	return;
	$x = $aArray['xLocation'];
	$y = $aArray['yLocation'];
	$textX = 0;
	$textY = 0;
	$Dist = 10;
	if ($x <= 50 && $y <= 50) {
		$img = "/style/webImages/UpperLeft-trans.png";
		$width = 60;
		$height = 60;
		$textX = $x + $Dist;
		$textY = $y + $Dist;
	} elseif ($x <= 50 && $y > 50) {
		$img = "/style/webImages/LowerLeft-trans.png";
		$width = 60;
		$height = 60;
		$y = $y - 13;
		$x = $x - 1;
		$textX = $x + $Dist;
		$textY = $y - $Dist;
	} elseif ($x > 50 && $y > 50) {
		$img = "/style/webImages/LowerRight-trans.png";
		$width = 60;
		$height = 60;
		$y = $y - 13;
		$x = $x - 12;
		$textX = $x - $Dist - 25;
		$textY = $y - $Dist;
	} else {
		$img = "/style/webImages/UpperRight-trans.png";
		$width = 60;
		$height = 60;
		$x = $x - 8;
		$y = $y - 1;
		$textX = $x - $Dist;
		$textY = $y + $Dist;
	}


	$title = $aArray['annotationLabel'];


	echo ' <div id="arrow1" class="overlay" style="z-index=3; position:absolute; top:' . $y . '%; left:' . $x . '%">
									   <img src="' . $img . '" height="' . $height . '" width="' . $width . '"> ';
	echo '</div>';
	if ($title != "") {
		echo ' <div id="titlefield" border="3" class="overlay" style="z-index=3; position:absolute; top:' . $textY . '%; left:' . $textX . '%">';
		echo '<table border="2" bgcolor=#ffffff><tr><td>';
		echo $title;
		echo '</td></tr></table>';
		echo '</div>';
	}
}

function getAnnotationData($id)
{
	global $link;
	$sql = 'SELECT Annotation.* FROM Annotation ';
	$sql .= 'WHERE Annotation.id = ' . $id;

	$result = mysqli_query($link, $sql);

	if ($result != false) {
		$numRows = mysqli_num_rows($result);

		if ($numRows = 1) {
			$array = mysqli_fetch_array($result);
			return $array;
		}
	}
}



function getSpecimenData($id)
{
	global $link;
	$sql = 'SELECT  Specimen.*, Specimen.id AS specimenId,Specimen.tsnId as tsnId, Image.id AS imageid, ' . 'Sex.name AS sex, Form.name AS form ' . 'FROM Specimen ' . 'LEFT JOIN Image ON Specimen.id = Image.specimenId ' . 'LEFT JOIN Sex ON Specimen.sexId = Sex.id ' . 'LEFT JOIN Form ON Specimen.formId = Form.id ';

	$sql .= 'WHERE Specimen.id = ' . $id . '';



	$result = mysqli_query($link, $sql);

	if ($result != false) {
		$numRows = mysqli_num_rows($result);

		if ($numRows = 1) {
			$array = mysqli_fetch_array($result);
			return $array;
		}
	}
}

function getImageData($id)
{
	global $link;
	$sql = 'SELECT * FROM Image where id=' . $id;
	$result = mysqli_query($link, $sql);
	if ($result == false)
	return;
	$array = mysqli_fetch_array($result);
	return $array;
}

function getBaseObjectData($id)
{
	global $link;
	$sql = 'SELECT DATE_FORMAT(BaseObject.dateCreated, \'%m-%d-%Y\') AS dateCreated, '
	. 'DATE_FORMAT(dateLastModified, \'%m-%d-%Y\') AS dateLastModified, '
	. 'DATE_FORMAT(dateToPublish, \'%m-%d-%Y\') AS dateToPublish, ' . 'User.name AS userName, '
	. 'User.id AS userId ,' . 'User.email AS userEmail '
	. 'FROM BaseObject LEFT JOIN User ON BaseObject.userId = User.id '
	. 'WHERE BaseObject.id = ' . $id . ' ';

	$result = mysqli_query($link, $sql);

	if ($result != false) {
		$total = mysqli_num_rows($result);

		if ($total = 1) {
			$array = mysqli_fetch_array($result);
			return $array;
		} else {
			$error = 'Error';
			return $error;
		}
	} else {
		$error = 'Query Error';
		return $error;
	}
}



function printButtons($new, $id, $user, $prevURL)
{
	global $config;
	echo '<table  align="right"><tr>';
	echo '<td><a href="' . $config->domain . 'Annotation/annotationManager.php"><IMG SRC="/style/webImages/buttons/return.png" border="0"></a></td>';
	echo '</tr></table><br/><br/>';
}

/****************************************************************************************
 *																	  *
 *  Routine/Module Name: DisplayAnnotations									 *
 *																	  *
 *  Parameter Description:$id: Image Id, $sortyby: how the list is sorted,			*
 *    $annotationId: Which Annotation is being displayed, $page:  which page		   *
 *    to display, and $typeCollection:  which group of images to display.			 *
 *																	  *
 *																	  *
 *  Description: Displays all related annotations. This includes all annotations	    *
 *    associated with the current image and all images of the specimen to which that	*
 *    image belongs.													    *
 *																	  *
 *  Author: David A. Gaitos, MorphBank Project Director						    *
 *  Date: Dec 13, 2005													  *
 ***********************************************s****************************************/
function DisplayAnnotations($id, $sortby, $annotationid, $page, $typeCollection)
{
	global $link;
	$imageId = $id;
	$query = "select specimenId from Image where id=" . $id;
	$results = mysqli_query($link, $query);
	$row = mysqli_fetch_array($results);
	$query = "select * from Image where specimenId=" . $row['specimenId'];
	$results = mysqli_query($link, $query);
	$numrows = mysqli_num_rows($results);
	$row = mysqli_fetch_array($results);
	$id = $row['id'];

	$query = "select Annotation.id, Annotation.objectId, User.name, BaseObject.dateCreated, Annotation.title ";
	$query .= "from Annotation join baseObject join User where Annotation.id = BaseObject.id and ";
	$query .= "BaseObject.userId = User.id  and( ";
	$query .= "Annotation.objectId=" . $id . " ";

	for ($i = 1; $i < $numrows; $i++) {
		$row = mysqli_fetch_array($results);
		$id = $row['id'];
		$query .= "or Annotation.objectId=" . $id . " ";
	}
	$query .= ")";

	if ($sortby == 3)
	$query .= " order by baseObject.dateCreated";
	elseif ($sortby == 2)
	$query .= " order by User.last_Name";
	else
	$query .= " order by Annotation.title ";

	$results = mysqli_query($link, $query);
	if ($results)
	echo ' <div id="arrow1" class="overlay" style="z-index=3; position:absolute; top:' . $y . '%; left:' . $x . '%">
									   <img src="' . $img . '" height="' . $height . '" width="' . $width . '"> ';
	{
		echo '<td rowspan="10">';
		$numrows = mysqli_num_rows($results);

		echo '<table border="1" ALIGN="CENTER" WIDTH="360PX"><tr><td align="center">' . $numrows . ' Related Annotations for this Specimen</td></tr></table>';
		echo '<div class="annotationContainer" STYLE="WIDTH:360PX;">';
		echo '<table WIDTH="370PX">';
		for ($index = 0; $index < $numrows; $index++) {
			$row = mysqli_fetch_array($results);
			if ($annotationid == $row['id'])
			$bgcolor = 'bgcolor=#CCCCCC border="1"';
			else
			$bgcolor = "";

			$title = $row['title'];
			$Annotationid = $row['id'];
			$PostIt = getAllAnnotationData($Annotationid);
			$dateCreated = $row['dateCreated'];
			$name = $row['name'];
			// **************************************************************************************************
			//* Need to make sure that the correct image Id is set for the annotation.  This problem occurs	*
			//* whenever a specimen has more then one image.										  *
			//***************************************************************************************************

			$realImageId = $row['objectId'];
			echo '<tr ' . $bgcolor . '><td border="2"><a
			href="relatedAnnotationsIndex.php?id=' . $realImageId . '&annotationid=' . $Annotationid . '&page=' . $page . '&' . $typeCollection . '"';
			echo 'onMouseOver="javascript:startPostIt(event,\'' . $PostIt . '\');" onMouseOut="javascript:stopPostIt();"\>';
			echo '<b>TITLE:</b> ' . $title;
			echo '<br/><b>BY:</b>' . $name . '<br/><b>DATE CREATED:</b> ' . $dateCreated . '<br/><b>Annotation ID:</b>' . $Annotationid;
			echo '</a></td></tr>';
		}
		echo '</table></div>';
		echo '</tr>';
	}
}



function makeTestMenu($imageId, $imagew, $imageh)
{
	global $annotationid;
	$width = 125;
	//annNavMenuCont
	echo '
    <div class="annNavMenuCont">
	<div class="annNavMenuLink" style="left:0px;">
    <a href="#" onMouseOver="hideAllMenusAnn();expandMenu(\'collection\');stopTimeAnn();" onMouseOut="startTimeAnn();">Related Images</a>
	  </div>		  
    <div class="annNavMenuLink" style="left:' . ($width + 50) . 'px;">
    <a href="#" onMouseOver="hideAllMenusAnn();expandMenu(\'mail\');stopTimeAnn();" onMouseOut="startTimeAnn();">Mail</a>
		   </div>  
    <div class="annNavMenuLink" style="left:' . ($width * 2) . 'px;">
    <a href="#" onMouseOver="hideAllMenusAnn();expandMenu(\'view\');stopTimeAnn();" onMouseOut="startTimeAnn();">View</a>
	  </div>  
    <div class="annNavMenuLink" style="left:' . ($width * 3) . 'px;">
    <a href="#" onMouseOver="hideAllMenusAnn();expandMenu(\'image\');stopTimeAnn();" onMouseOut="startTimeAnn();">Image</a>
	  </div>  
    <div class="annNavMenuLink" style="left:' . ($width * 4) . 'px;">
    <a href="#" onMouseOver="hideAllMenusAnn();expandMenu(\'annotation\');stopTimeAnn();" onMouseOut="startTimeAnn();">Annotation</a>
	  </div>
		</div>';

	makeTestLinks($imageId, $imagew, $imageh);
}

function makeTestLinks($imageId, $imagew, $imageh)
{
	
	global $annotationid;
	global $imageId;
	global $config;
	$width = 125;
	$initPosition = 100;


	$query = "select * from Image where id=" . $imageId;
	$result = mysql_query($query);
	$numrows = mysql_num_rows($result);
	$row = mysql_fetch_array($result);
	$specimenId = $row['specimenId'];
	$viewId = $row['viewId'];

	echo '<div class="annNavMenuLinks" id="collection" style="display:none; width:125px; left:' . $initPosition . 'px;">
	<a href="relatedAnnotationsIndex.php?species=1&id=' . $imageId 
	. '&page=1" onMouseOver="stopTimeAnn();" onMouseOut="startTimeAnn();" '
	. 'title="Show related Species Images">Species</a><br/>'
	. '<a href="relatedAnnotationsIndex.php?specimen=1&id=' . $imageId
	. '&page=1" onMouseOver="stopTimeAnn();" onMouseOut="startTimeAnn();"'
	. ' title=" Display all Images related to this specimen">Specimen</a><br/>'
	. '<a href="relatedAnnotationsIndex.php?view=1&id=' . $imageId . '&page=1" '
	. 'onMouseOver="stopTimeAnn();" onMouseOut="startTimeAnn();"'
	. 'title=" Display all Images that have this view">View</a><br/>'
	. '<a href="relatedAnnotationsIndex.php?collection=1&id=' . $imageId
	. '&page=1" onMouseOver="stopTimeAnn();" onMouseOut="startTimeAnn();"'
	. ' title="Select a collection of Images from my shopping list">Collection</a>'
	. '<br/></div>'
	. '<div class="annNavMenuLinks" id="mail" style="display:none; width:125px; left:'
	. ($initPosition + $width) . 'px;"><a href="mailTo.php?imageid=' . $imageId
	. '" onMouseOver="stopTimeAnn();" onMouseOut="startTimeAnn();"'
	. ' title="Mail this link to a person">Send Link</a><br/></div> '
	. '<div class="annNavMenuLinks" id="view" style="display:none; width:125px; left:'
	. ($initPosition + ($width * 2)) . 'px;"><a href="javascript:openPopup(\''
	. $config->domain . 'Show/?id=' . $imageId . '\')" '
	. 'onMouseOver="stopTimeAnn();" onMouseOut="startTimeAnn();"'
	. ' title="Show related Image data ">Image Data</a><br/>'
	. '<a href="javascript:openPopup(\'' . $config->domain . 'Show/?id=' . $specimenId
	. '\')" '.$config->domain.'"images/jpeg/";'
	. '	onMouseOver="stopTimeAnn();" onMouseOut="startTimeAnn();"'
	. ' title="Show related Specimen data ">Specimen Data</a><br/>'
	. '<a href="javascript:openPopup(\'' . $config->domain . 'Show/?id=' . $viewId
	. '\')"	onMouseOut="startTimeAnn();"'
	. 'title="Show the data about this particular view">View Data</a><br/>'
	. '</div>'
	. '<div class="annNavMenuLinks" id="image" style="display:none; width:125px; left:'
	. ($initPosition + ($width * 3)) . 'px;">'
	. showViewerTag($imageId, 'onMouseOver="stopTimeAnn();" onMouseOut="startTimeAnn();"')
	. 'View Image</a><br/></div>'
	. '<div class="annNavMenuLinks" id="annotation" style="display:none; width:125px; left:'
	. ($initPosition + ($width * 4)) . 'px;">'
	. '<a href="index.php?id=' . $imageId . '" onMouseOver="stopTimeAnn();" onMouseOut="startTimeAnn();"'
	. ' title="Add an annotation to this Image.">Add Annotation</a><br/>'
	. '<a href="relatedAnnotationsIndex.php?id=' . $imageId . '&annotationid=' . $annotationid
	. '&sortby=1" onMouseOver="stopTimeAnn();" onMouseOut="startTimeAnn();" '
	. ' title="Sort displayed annotations alphabetically by their title">Sort by Title</a><br/>'
	. '<a href="relatedAnnotationsIndex.php?id=' . $imageId . '&annotationid=' . $annotationid
	. '&sortby=2" onMouseOver="stopTimeAnn();" onMouseOut="startTimeAnn();" '
	. 'title="Sort displayed annotations alphabetically by their author">Sort by Author</a><br/>'
	. '<a href="relatedAnnotationsIndex.php?id=' . $imageId . '&annotationid=' . $annotationid
	. '&sortby=3" onMouseOver="stopTimeAnn();" onMouseOut="startTimeAnn();"'
	. ' title="Sort displayed annotations chronologically by date created" >Sort by Date</a><br/>'
	. '</div>';
}

function annotateJS() {
	
	include_once("libjs/annotateMenu.php");
}


function displayTitle($imageId) {
	echo "<h1>Image Record[" . getImageTSN($imageId) . "]<h1>";
}

/**
 * TODO use scientific name instead of unit_name1 etc
 * duplicate function in mainAnnotation.php
 * @param unknown_type $imageId
 * @return unknown_type
 */
function getImageTSN($imageId) {
	$results = mysql_query("select * from Image join Specimen where Image.specimenId = Specimen.id and Image.id=" . $imageId);
	$row = mysql_fetch_array($results);
	$tsn = $row['tsnId'];
	$results = mysql_query("select * from Tree where tsn=" . $tsn);
	$row = mysql_fetch_array($results);
	return $row['unit_name1'] . ' ' . $row['unit_name2'] . ' ' . $row['unit_name3'] . ' ' . $row['unit_name4'];
}


function addJavaScript($xcor, $ycor, $height, $width, $xoffset, $yoffset) {
	echo '<script type="text/javascript" ssrc="global.js"></script>';
	echo '<script language="javascript" type="text/javascript">
  function layArrow(){
  var curleft = 0;
  var curtop = 0;
  obj= document.getElementById("animage");
	 while (obj.offsetParent) {
	 curleft += obj.offsetLeft;
	 obj = obj.offsetParent;
	}
    obj= document.getElementById("animage");
    while (obj.offsetParent) {
	 curtop += obj.offsetTop;
	 obj = obj.offsetParent;
	 } 

    var xpos = ((' . $width . ' * (' . $xcor . '/100)) + curleft);
    var ypos = (((' . $height . ' * (' . $ycor . '/100)) + curtop) - 65);
   document.getElementById("label").style.top = (ypos + 8 ) + "px";
  document.getElementById("label").style.left = (xpos + 8) + "px";
  document.getElementById("arrow").style.visibility="visible";
  document.getElementById("arrow").style.top= (ypos + ' . $yoffset . ') + 5 +"px";
  document.getElementById("arrow").style.left= (xpos + ' . $xoffset . ') + 5 +"px";
  }
  </script>';
}
  "<br/></div>";

function getfirstannotation($id) {
	$results = mysql_query('select * from Annotation where objectId=' . $id);
	$numrows = mysql_num_rows($results);
	if ($numrows < 1)
	return null;
	$row = mysql_fetch_array($results);
	return $row['id'];
}

function getSpecies($id) {
	//******************************************************************************
	// Get the TSN of the Image id that was passed in.					    *
	//******************************************************************************
	$result = mysql_query('select Specimen.tsnId from Image join Specimen on Image.specimenId = Specimen.id where Image.id=' . $id);
	$row = mysql_fetch_array($result);
	$tsn = $row['tsnId'];
	//******************************************************************************
	// Get all of the Images that have the same genus-species tsn			   *
	//******************************************************************************

	$result1 = mysql_query('select Image.id from Image join Specimen on Image.specimenId = Specimen.id where Specimen.tsnId=' . $tsn);
	$numrows = mysql_num_rows($result1);

	for ($counter = 0; $counter < $numrows; $counter++) {
		$row = mysql_fetch_array($result1);
		$imageArray[$counter] = $row['id'];
	}
	return $imageArray;
}


function getView($id) {
	//********************************************************************************
	// Get all the images of the same view.								  *
	//********************************************************************************

	$result = mysql_query('select viewId from Image where Image.id=' . $id);
	$row = mysql_fetch_array($result);
	$viewId = $row['viewId'];
	$result1 = mysql_query('select id from Image where viewId=' . $viewId);
	$numrows = mysql_num_rows($result1);

	for ($counter = 0; $counter < $numrows; $counter++) {
		$row = mysql_fetch_array($result1);
		$imageArray[$counter] = $row['id'];
	}
	return $imageArray;
}

function getSpecimen($id) {
	//********************************************************************************
	// Get all the Images of the same specimen.							    *
	//********************************************************************************

	$result = mysql_query('select specimenId from Image where id=' . $id);
	$row = mysql_fetch_array($result);
	$specimenId = $row['specimenId'];
	$result1 = mysql_query('select * from Image where specimenId=' . $specimenId);
	$numrows = mysql_num_rows($result1);

	for ($counter = 0; $counter < $numrows; $counter++) {
		$row = mysql_fetch_array($result1);
		$imageArray[$counter] = $row['id'];
	}
	return $imageArray;
}

function getCollection($id) {
	$results = mysql_query('select myCollectionId from myCollectionObjects where objectId=' . $id);
	if (!$results)
	return null;
	$numrows = mysql_num_rows($results);
	if ($numrows < 1)
	return;
	for ($counter = 0; $counter < $numrows; $counter++) {
		$row = mysql_fetch_array($results);
		$collectionArray[$counter] = $row['myCollectionId'];
	}
	$sql = "select * from myCollectionObjects where myCollectionId=";
	$sql .= $collectionArray[0];
	for ($i = 1; $i < sizeof($collectionArray); $i++)
	$sql .= " or myCollectionId=" . $collectionArray[$i];

	$results = mysql_query($sql);
	$numrows = mysql_num_rows($results);
	$index = 0;
	for ($i = 0; $i < $numrows; $i++) {
		$row = mysql_fetch_array($results);
		if (NotInArray($imageArray, $row['objectId']))
		$imageArray[$index++] = $row['objectId'];
	}

	return $imageArray;
}

function NotInArray($InArray, $objectId) {
	$size = sizeof($InArray);
	for ($i = 0; $i < $size; $i++)
	if ($InArray[$i] == $objectId)
	return false;
	return true;
}

function DisplayNavBar($idArray, $imageId, $annotationId, $page, $typeCollection) {
	
	$ImagesPerPage = 6;
	$NumPagesPer = 6;
	$numImages = sizeof($idArray);
	// windows 1-6, 7-12, etch
	$window = (integer)((($page - 1) / 6) + 1);
	// First page
	$firstPage = (integer)((($window - 1) * 6) + 1);
	$lastPage = (integer)($firstPage + 35);
	$lastPage = (integer)((($numImages - 1) / 6) + 1);
	$firstImage = (integer)(($page - 1) * 6);
	$lastImage = sizeof($idArray) - 1;
	$lastWindow = (integer)((($numImages - 1)) / 36 + 1);

	echo '<a href="relatedAnnotationsIndex.php?id=' . $imageId . '&page=1&annotationid=' . $annotationId . '&' . $typeCollection . '" title="Go to First Page">';
	echo '<img src="/style/webImages/goFirst.png" border="0" alt="FirstPage"/></a>&nbsp;&nbsp;';
	if ($page != 1) {
		$page = $page - 1;
		echo '<a href="relatedAnnotationsIndex.php?id=' . $imageId . '&page=' . $page . '&annotationid=' . $annotationId . '&' . $typeCollection . '" title="Go to First Page">';
		echo '<img src="/style/webImages/backward-gnome.png" border="0" alt="back"/></a>&nbsp;&nbsp;';
	}

	for ($i = $firstPage; $i <= ($firstPage + 5) && $i <= $lastPage; $i++) {
		echo '<a href="relatedAnnotationsIndex.php?id=' . $imageId . '&page=' . $i . '&annotationid=' . $annotationId . '&' . $typeCollection . '" title="Go to First Page">';
		echo $i . '</a>&nbsp;&nbsp;';
	}

	if (($firstpage + 7) < $lastPage) {
		$page = $firstPage + 6;
		echo '<a href="relatedAnnotationsIndex.php?id=' . $imageId . '&page=' . $page . '&annotationid=' . $annotationId . '&' . $typeCollection . '" title="Go to First Page">';
		echo '<img src="/style/webImages/forward-gnome.png" border="0" alt="back"/></a>&nbsp;&nbsp;';
	}
	echo '<a href="relatedAnnotationsIndex.php?id=' . $imageId . '&page=' . $lastPage . '&annotationid=' . $annotationId . '&' . $typeCollection . '" title="Go to First Page">';
	echo '<img src="/style/webImages/goLast.png" border="0" alt="FirstPage"/></a>&nbsp;&nbsp;';
	echo ' of ' . $lastPage . ' (' . $numImages . ' Images) <br/>';
	DisplayThumbs($firstImage, $idArray, $imageId, $annotationId, $page, $typeCollection);
}


function DisplayThumbs($firstImage, $idArray, $imageId, $annotationId, $page, $typeCollection) {

	$numImages = sizeof($idArray);
	$lastImage = $firstImage + 5;
	//  $lastImage = $firstImage+8;
	echo '<div id=scrolltd>';
	echo '<table border="1" bordercolor=#000000 cellspacing=0 cellpadding=4 width=100%>';
	echo '<tr>';
	for ($i = $firstImage; $i <= $lastImage && $i <= ($numImages - 1); $i++) {
		$imageData = getallimagedata($idArray[$i]);
		echo '<td>';
		echo 'Image Record: [' . $idArray[$i] . '] ';
		echo '<br/>';
		"<br/></div>";
		$imageloc = getImageUrl($idArray[$i]);
		echo '<a href = "relatedAnnotationsIndex.php?id=' . $idArray[$i] . '&page=' . $page . '&' . $typeCollection 
		. '"><img src = "' . $imageloc . '" width=100 onMouseOver="javascript:startPostIt(event,\'' 
		. $imageData . '\');" onMouseOut="javascript:stopPostIt();"</a>';
		echo '</td>';
	}
	echo '</tr>';
	echo '</table></div>';
}
//**********************************************************************************
echo '
    <script language="JavaScript1.2">

    //Image zoom in/out script- by javascriptkit.com   
    //Visit JavaScript Kit (http://www.javascriptkit.com) for script
    //Credit must stay intact for use

    var zoomfactor=0.10 //Enter factor (0.10=10%)
//**************************************************************************************
//*  Modified by Jason Simmons to limit the expansion and reduction of the Image to	*
//*  15000 and 100 pixels respectfully										*
//**************************************************************************************
    function zoomhelper()
	{  if((parseInt(whatcache.style.width)<15000 && 
		  parseInt(whatcache.style.height)<15000) && 
		 (parseInt(whatcache.style.width)>100 &&
		  parseInt(whatcache.style.width)>100))
		{
		  whatcache.style.width=parseInt(whatcache.style.width)+parseInt(whatcache.style.width)*zoomfactor*prefix
		  whatcache.style.height=parseInt(whatcache.style.height)+parseInt(whatcache.style.height)*zoomfactor*prefix
		}
	}

    function zoom(originalW, originalH, what, state)
	 {
	    if (!document.all&&!document.getElementById)
		 return
	    whatcache=eval("document.images."+what)
	    prefix=(state=="in")? 1 : -1
	    if (whatcache.style.width==""||state=="restore")
		{
		 whatcache.style.width=originalW
		 whatcache.style.height=originalH
		 if (state=="restore")
		   return
		}
	    else
	    {
		 zoomhelper()
	    }
		beginzoom=zoomhelper();
	  }

 function clearzoom()
   {
	if (window.beginzoom)
	   clearInterval(beginzoom)
    }

	  </script>
	  ';
?>
