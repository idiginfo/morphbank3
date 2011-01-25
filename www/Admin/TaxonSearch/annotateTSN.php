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

// This file is used to add Correction
// on existing tsn name (Taxon Concept). This is
// basicaly a new type of Annotation
// Created by : Karolina Maneva-Jakimoska
// Created on: Jan 29th 2007


if ($_REQUEST['pop']) {
	include_once('pop.inc.php');
} else {
	include_once('head.inc.php');
}
include_once('gettables.inc.php');
include_once('tsnFunctions.php');
include_once('postItFunctions.inc.php');
include_once('updateObjectKeywords.php');

global $objInfo;

$link = AdminLogin();
// The beginnig of HTML
$title = 'Add Taxon Name Annotation';
initHtml($title, null, null);
echoHead(false, $title);

if (isset($_POST['returnUrl']))
$returnUrl = $_POST['returnUrl'];
else
$returnUrl = $_SERVER['HTTP_REFERER'];

$groupTSN = $objInfo->getGroupTSN();

if (isset($_GET['browse']))
$browse = $_GET['browse'];

if (isset($_GET['view']))
$view = $_GET['view'];

if ((isset($_GET['POP']) && $_GET['POP'] == "YES") || (isset($_POST['pop']) && $_POST['pop'] == "YES"))
echo '<div class="popContainer" style="width:760px">';
else
echo '<div class="mainGenericContainer" style="width:760px">';
if (isset($_GET['tsn']) && $_GET['tsn'] != "")
$tsn = $_GET['tsn'];
if (isset($_GET['tsn']) && $_GET['tsn'] == "") {
	echo '<span style="color:#17256B"><b>You can not make TaxonName annotation through massAnnotation.</b></span><br/>
             <a href="' . $returnUrl . '" class="button smallButton right" title="Click to return to CollectionManager"><div>Return</div></a>
             </div>';
	finishHtml();
	exit();
}
if (!isset($_POST['tsn'])) {
	if(!checkAuthorization(null, $objInfo->getUserId(), $objInfo->getUserGroupId(), 'add')){
		echo getNonAuthMessage(getNonAuthCode()) . "<br /><br />";
		echo '<a href="javascript: window.close();" class="button smallButton right" title="Click to close window"><div>Cancel</div></a>';
		finishHtml();
		exit();
	}
}

echoJavaScript();

echo '<form method="post" action="annotateTSN.php">';
if (isset($_GET['pop']) || isset($_POST['pop']))
echo '<input type="hidden" name="pop" value="yes" />';

//if there was a submit action the record is created before the form is displayed
if (isset($_POST['tsn'])) {
	$tsn = $_POST['tsn'];
	$title = $_POST['title'];
	$newName = $_POST['new_name'];
	$newReferenceId = $_POST['reference_id'];
	$newReferenceName = $_POST['reference_name'];
	$newTaxonAuthor = $_POST['taxon_author'];
	$newSynonymTsn = $_POST['synonym_tsn'];
	$newSynonymName = $_POST['synonym_name'];
	$newStatus = $_POST['not_synonym'];
	$newParent = $_POST['parent_tsn'];
	$newParentName = $_POST['parent_name'];
	$newReasons = $_POST['reasons'];
	$notes = $_POST['notes'];
	$unacceptReasons = $_POST['old_status'];
	$comment = "";

	if ($newName != "")
	$comment .= "<b>Name should be: </b>" . $newName . "<br/>";
	if ($newReferenceName != "")
	$comment .= "<b>Reference should be: </b>[" . $newReferenceId . "]-" . $newReferenceName . "<br/>";
	if ($newTaxonAuthor != "")
	$comment .= "<b>Taxon Author should be: </b>" . $newTaxonAuthor . "<br/>";
	if ($newSynonymName != "")
	$comment .= "<b>Should be made synonym of: </b>[" . $newSynonymTsn . "]-" . $newSynonymName . "<br/>";
	if ($newStatus == "on")
	$comment .= "<b>Should be removed from synonymy! Reasons: </b>" . $newReasons . "<br/>";
	if ($newParentName != "")
	$comment .= "<b>Should be child of: </b>[" . $newParent . "]-" . $newParentName . "<br/>";
	if ($unacceptReasons != "")
	$comment .= "<b>Unaccepted reasons are: </b>" . $unacceptReasons . "<br/>";
	if ($notes != "")
	$comment .= "<b>Additional comments: </b>" . $notes . "<br/>";


	//echo $comment;
	$query = "SELECT id FROM TaxonConcept WHERE tsn=" . $_POST['tsn'];
	//echo $query;
	$result = mysqli_query($link, $query);
	if (!$result) {
		echo '<b><span style="color:red">Problems querying the database</b></span><hr/><br/>';
	} else {

		$numRows = mysqli_num_rows($result);
		if ($numRows < 1) {
			//the record does not exist in TaxonConcept
			//check what is the status of that tsn in the moment
			$query = "SELECT * FROM Tree WHERE tsn=" . $_POST['tsn'];
			$result = mysqli_query($link, $query);
			if (!$result) {
				echo '<b><span style="color:red">Problems querying the database</b></span><hr/><br/>';
			} else {

				$row = mysqli_fetch_array($result);

				$query = "CALL `TaxonConceptInsert`(" . $_POST['tsn'] . ",'" . $row['nameSource'] . "','" . $row['usage'] . "'," . $objInfo->getUserId() . ",";
				$query .= $objInfo->getUserGroupId() . "," . $objInfo->getUserId() . ",NULL,NOW(),'TaxonConcept',NULL,NULL,NULL,NULL,'";
				$query .= $row['scientificName'] . "', @oId)";
				$result = mysqli_multi_query($link, $query);

				if ($result) {
					if (mysqli_multi_query($link, "select @oId")) {
						if ($idResult = mysqli_store_result($link)) {
							$row = mysqli_fetch_row($idResult);
							$taxon_id = $row[0];
						}
					}
				}
			}
		} else {

			//echo "Taxon concept exists";
			$row = mysqli_fetch_array($result);
			$taxon_id = $row['id'];
		}
		$query = "CALL `AnnotationInsert`(" . $taxon_id . ",'TaxonConcept','TaxonName',NULL,NULL,NULL,NULL,NULL,NULL,'";
		$query .= $title . "',NULL,'" . $comment . "',NULL,NULL,NULL,NULL,'" . $title . "','Annotation',NULL," . $objInfo->getUserId() . ",";
		$query .= $objInfo->getUserGroupId() . "," . $objInfo->getUserId() . ",NOW(), NULL, NULL, NULL, NULL, @oId)";
		$result = mysqli_multi_query($link, $query);
		if ($result) {
			if (mysqli_multi_query($link, "select @oId")) {
				if ($idResult = mysqli_store_result($link)) {
					$row = mysqli_fetch_row($idResult);
					$annotationId = $row[0];
				}
			}
		}

		updateKeywordsTable($annotationId, 'insert');

		echo '<b><span style="color:#17256B">New Taxon Name Annotation record was successfully added to the data base</span></b><hr/><br/>';
	}
}
//matches (if isset POST['tsn'])


//display title on the form
DisplayTitleName($link, $tsn);

//retreive the information from the database available for this tsn
$query = "SELECT scientificName,`usage`,Tree.rank_id,rank_name,publicationId,taxon_author_id,parent_tsn,nameType,pages";
$query .= "  FROM Tree, TaxonUnitTypes WHERE tsn=" . $tsn . " AND Tree.rank_id=TaxonUnitTypes.rank_id";
$result = mysqli_query($link, $query);
if (!$result)
echo '<b><span style="color:red">Problems querying the database</b></span><hr/><br/>';
else {

	$row = mysqli_fetch_array($result);
	$taxonName = $row['scientificName'];
	$status = $row['usage'];
	$rank_id = $row['rank_id'];
	$rank = $row['rank_name'];
	$author_id = $row['taxon_author_id'];
	$publication_id = $row['publicationId'];
	$parent_tsn = $row['parent_tsn'];
	$pages = $row['pages'];
	$nameType = $row['nameType'];
	$query = "SELECT scientificName FROM Tree WHERE tsn=" . $parent_tsn;
	$result = mysqli_query($link, $query);
	if (!$result)
	echo '<b><span style="color:red">Problems querying the database</b></span><hr/><br/>';
	else {

		$row = mysqli_fetch_array($result);
		$parent = $row['scientificName'];
		if ($author_id != "" && $author_id != null && $author_id != 0) {
			$query = "SELECT taxon_author FROM TaxonAuthors WHERE taxon_author_id=" . $author_id;
			//      echo $query;
			$result = mysqli_query($link, $query);
			if (!$result)
			echo '<b><span style="color:red">Problems querying the database</b></span><hr/><br/>';
			else {

				$row = mysqli_fetch_array($result);
				$author_name = $row['taxon_author'];
			}
		}
		if ($publication_id != "" && $publication_id != null) {
			$query = "SELECT author, year, title, publicationTitle FROM Publication WHERE id=" . $publication_id;
			//      echo $query;
			$result = mysqli_query($link, $query);
			if (!results)
			echo '<b><span style="color:red">Problems querying the database</b></span><hr/><br/>';
			else {

				$row = mysqli_fetch_array($result);
				$p_year = $row['year'];
				$p_author = $row['author'];
				$p_title = $row['title'];
				$p_Title = $row['publicationTitle'];
				$publication = $p_author . ". " . $p_year;
				if ($p_title != "" && $p_title != null)
				$publication .= ". " . $p_title;
				if ($p_Title != "" && $p_Title != null)
				$publication .= ". <i>" . $p_Title . "</i>";
			}
		}
	}
}

echo '<h3><b>Taxon Name Information:</b></h3>
         <table class="topBlueBorder" width="760px">
         <tr></tr><tr>
           <td width="30%px"><b>Type of Name: </b></td><td>' . $nameType . '</td>
         </tr><tr></tr>
          <tr>
           <td width="30%px"><b>Taxon Name: </b></td><td>' . $taxonName . '</td>
          </tr><tr></tr>
          <tr>
           <td width="30%px"><b>Rank Identification: </b></td><td>' . $rank . '</td>
           <input type="hidden" name="rank_id" value="' . $rank_id . '" />
          </tr><tr></tr>
          <tr>
           <td width="30%px"><b>Parent Taxon Id/Name: </b></td><td>' . $parent_tsn . ' / ' . $parent . '</td>
          </tr><tr></tr>
          <tr>
           <td width="30%px"><b>Publication: </b></td><td>' . $publication . '</td>
          </tr><tr></tr>
          <tr>
           <td width="30%px"><b>Taxon Author,Year: </b></td><td>' . $author_name . '</td>
          </tr><tr></tr>
          <tr>
           <td width="30%px"><b>Page(s): </b></td><td>' . $pages . '</td>
          </tr>
          <tr>
           <td width="30%px"><b>Name status: </b></td><td>' . $status . '</td>
          </tr>
        </table><br/>';
DisplayExistingCorrections($link, $tsn);

echo '<br/><h3><b>Create New Annotation: </b></h3>';


//form display for new annotation
//check if there was make similar action and if that is the case
//populate the fields

if (isset($_GET['annotationId']) && $_GET['annotationId'] != "" && $_GET['annotationId'] != null) {
	$query = "SELECT comment FROM Annotation WHERE id=" . $_GET['annotationId'];
	$result = mysqli_query($link, $query);
	if (!$result)
	echo '<span style="color:red"><b>Problems querying the database</b></span><br/><hr/><br/>';
	else {

		$row = mysqli_fetch_array($result);
		$comment = $row['comment'];
		$array = explode("<br/>", $comment);
		for ($i = 0; $i < count($array); $i++) {
			$array[$i] = trim($array[$i]);
			$part1 = substr($array[$i], 2, strpos($array[$i], ":"));
			$part2 = substr($array[$i], strpos($array[$i], ":") + 6, strlen($array[$i]));

			if ($part1 == ">Name should be: ") {
				$new_name_value = $part2;
			}
			if ($part1 == ">Reference should be: ") {
				$part3 = substr($part2, 1, strpos($part2, "-") - 2);
				$part4 = substr($part2, strpos($part2, "-") + 1, strlen($part2));
				$new_referenceId_value = $part3;
				$new_reference_value = $part4;
			}
			if ($part1 == ">Taxon Author should be: ") {
				$new_taxonauthor_value = $part2;
			}
			if ($part1 == ">Should be made synonym of: ") {
				$part3 = substr($part2, 1, strpos($part2, "-") - 2);
				$part4 = substr($part2, strpos($part2, "-") + 1, strlen($part2));
				$new_synonymTsn_value = $part3;
				$new_synonym_value = $part4;
			}
			if ($part1 == ">Should be removed from synonymy! Reasons: ") {
				$new_reasons_value = $part2;
				$not_synonym_check = "on";
			}
			if ($part1 == ">Should be child of: ") {
				$part3 = substr($part2, 1, strpos($part2, "-") - 2);
				$part4 = substr($part2, strpos($part2, "-") + 1, strlen($part2));
				$new_parentTsn_value = $part3;
				$new_parent_value = $part4;
			}
			if ($part1 == ">Additional comments: ") {
				$new_comments_value = $part2;
			}
		}
	}
}

echo '<table class="topBlueBorder"  width="760px">
      <tr><td><b>Annotation title: <span style="color:red">*</span></b></td>
          <td><input type="text" name="title" size="40" maxlength="40" value="' . $taxonName . '"/>
      </tr>
      <tr><td><b>Existing name should become invalid/unaccepted, reasons being: <span style="color:red">*</span></b></td>';
NameStatus($link, $tsn);
echo '<tr><td><b>Name should be: </b></td>
          <td><input type="text" name="new_name" size="45" maxlength="128"';
if (isset($_GET['annotationId']) && $_GET['annotationId'] != "" && $_GET['annotationId'] != null)
echo 'value="' . $new_name_value . '"';
echo '/></td>
      </tr>
      <tr><td><b>Publication should be: <span style="color:red">*</span></b></td>
          <td><input type="text" name="reference_id" size="8"';
if (isset($_GET['annotationId']) && $_GET['annotationId'] != "" && $_GET['annotationId'] != null)
echo 'value="' . $new_referenceId_value . '"';

echo '/> / <input type="text" name="reference_name" size="45" maxlength="128"';
if (isset($_GET['annotationId']) && $_GET['annotationId'] != "" && $_GET['annotationId'] != null)
echo 'value="' . $new_reference_value . '"';
echo ' readonly="true" />';
?>
<a
	href="javascript:openPopup('<?php
  echo $config->domain
  ?>Browse/ByPublication/?pop=YES')"> <?php
  echo '<img src="/style/webImages/selectIcon.png" /></a>';
  ?>
</td>
</tr>
<tr>
	<td><b>Taxon Author,Year should be:</b></td>
	<td><input type="text" name="taxon_author" size="32"
	<?php
	if (isset($_GET['annotationId']) && $_GET['annotationId'] != "" && $_GET['annotationId'] != null)
	echo 'value="' . $new_taxonauthor_value . '"';
	?> /></td>
</tr>
<tr>
	<td><b>Should be synonym of: </b></td>
	<td><input type="text" name="synonym_tsn" size="8"
	<?php
	if (isset($_GET['annotationId']) && $_GET['annotationId'] != "" && $_GET['annotationId'] != null)
	echo 'value="' . $new_synonymTsn_value . '"';
	?> /> / <input type="text" name="synonym_name" size="32"
	<?php
	if (isset($_GET['annotationId']) && $_GET['annotationId'] != "" && $_GET['annotationId'] != null)
	echo 'value="' . $new_synonym_value . '"';
	?>
		readonly="true" /> <a
		href="javascript:openPopup('<?php
  echo $config->domain;
?>Admin/TaxonSearch/index.php?tsn=0&amp;pop=yes&amp;searchonly=0&amp;TSNtype=5&amp;view=<?php
  echo $view;
?>&amp;browse=<?php
  echo $browse;
  ?>')"> <?php
  echo '<img src="/style/webImages/selectIcon.png" /></a></td>
      </tr>
      <tr><td><b>Should be removed from synonymy: </b></td><td><input type="checkbox" name="not_synonym"';

  if (isset($_GET['annotationId']) && $_GET['annotationId'] != "" && $_GET['annotationId'] != null && $not_synonym_check == 'on')
  echo 'checked="checked"';
  echo '/>
        <b>Reasons: </b><input type="text" name="reasons" size="57" maxlength="128"';
  if (isset($_GET['annotationId']) && $_GET['annotationId'] != "" && $_GET['annotationId'] != null)
  echo 'value="' . $new_reasons_value . '"';
  echo ' />
        </td>
      </tr>
      <tr><td><b>Should be child of: </b></td>
          <td><input type="text" name="parent_tsn" size="8" ';
  if (isset($_GET['annotationId']) && $_GET['annotationId'] != "" && $_GET['annotationId'] != null)
  echo 'value="' . $new_parentTsn_value . '"';
  echo '/> / <input type="text" name="parent_name" size="32"';
  if (isset($_GET['annotationId']) && $_GET['annotationId'] != "" && $_GET['annotationId'] != null)
  echo 'value="' . $new_parent_value . '"';
  echo ' readonly="true"/>';
  ?> <a
		href="javascript:openPopup('<?php
  echo $config->domain;
?>Admin/TaxonSearch/index.php?tsn=0&amp;pop=yes&amp;searchonly=0&amp;TSNtype=6&amp;view=<?php
  echo $view;
?>&amp;browse=<?php
  echo $browse;
  ?>')" /> <?php
  echo '<img src="/style/webImages/selectIcon.png" /></a>
          </td>
      </tr>
      <tr>
         <td><b>Additional information: </b></td>
         <td><textarea name="notes" cols="54" rows="4" title="Provide additional information to support the sugested change"';
  if (isset($_GET['annotationId']) && $_GET['annotationId'] != "" && $_GET['annotationId'] != null)
  echo 'value="' . $new_comments_value . '"';
  echo '></textarea></td>
      </tr>
   </table>';

  echo '<br/><b><span style="color:red">* - Required</span></b><br/>';

  echo '<br/><table align="right"><tr>
         <td><a href="javascript: CheckValues(document.forms[0]);" class="button smallButton" title="Click to Submit the correction">
             <div>Submit</div></a></td>
         <td><a href="javascript: window.close();" class="button smallButton" title="Click to close window">
         <div>Cancel</div></a>
         <input type="hidden" name="tsn" value="' . $tsn . '" />
         <input type="hidden" name="returnUrl" value="' . $returnUrl . '" />
       </td>
       </tr>
      </table>
     </form><br/>';

  echo '</div>';
  //included on the end on purpose
  include_once('js/pop-update.js');
  // Finish with end of HTML
  finishHtml();

  //function to create the title
  function DisplayTitleName($link, $tsn)
  {
  	$query = "SELECT tsn, scientificName,taxon_author_id FROM Tree where Tree.tsn=" . $tsn;
  	$result = mysqli_query($link, $query);
  	$row = mysqli_fetch_array($result);
  	if ($row['taxon_author_id'] != "" && $row['taxon_author_id'] != null && $row['taxon_author_id'] != 0) {
  		$query = "SELECT taxon_author FROM TaxonAuthors WHERE taxon_author_id=" . $row['taxon_author_id'];
  		$result1 = mysqli_query($link, $query);
  		$row1 = mysqli_fetch_array($result1);
  		$count = strlen("[" . $row['tsn'] . "]-" . $row['scientificName'] . "-" . $row1['taxon_author']);
  		if ($count > 46)
  		$heder = "Add Annotation For Taxon Name: <br/>[" . $row['tsn'] . "]-" . $row['scientificName'] . "-" . $row1['taxon_author'];
  		else
  		$heder = "Add Annotation For Taxon Name: [" . $row['tsn'] . "]-" . $row['scientificName'] . "-" . $row1['taxon_author'];
  	} else {

  		$count = strlen("[" . $row['tsn'] . "]-" . $row['scientificName']);
  		if ($count > 46)
  		$heder = "Add Annotation For Taxon Name: <br/>[" . $row['tsn'] . "]-" . $row['scientificName'];
  		else
  		$heder = "Add Annotation For Taxon Name: [" . $row['tsn'] . "]-" . $row['scientificName'];
  	}

  	echo '<h2><b>' . $heder . '</b></h2><br/><hr/><br/>';
  }
  //display title

  //function to display existing corrections
  function DisplayExistingCorrections($link, $tsn)
  {
  	global $config;

  	$color[0] = $config->lightListingColor;
  	$color[1] = $config->darkListingColor;

  	/*
  	 $query = "SELECT BaseObject.dateCreated, BaseObject.id, Annotation.comment, User.name ";
  	 $query .= " FROM Annotation, TaxonConcept, BaseObject WHERE TaxonConcept.tsn=".$tsn;
  	 //$query .= " AND TaxonConcept.id=Annotation.objectId AND User.id=BaseObject.userId AND Annotation.objectTypeId='TaxonConcept'";
  	 $query .= " AND TaxonConcept.id=Annotation.objectId ";
  	 $query .= " AND BaseObject.id=Annotation.id";
  	 */

  	$query = "SELECT BaseObject.dateCreated, BaseObject.id, Annotation.comment, User.name ";
  	$query .= " FROM Annotation LEFT JOIN TaxonConcept ON TaxonConcept.id=Annotation.objectId LEFT JOIN BaseObject ON Annotation.id=BaseObject.id ";
  	$query .= "LEFT JOIN User ON BaseObject.userId = User.id WHERE TaxonConcept.tsn=" . $tsn;
  	//$query .= " AND  AND User.id=BaseObject.userId AND Annotation.objectTypeId='TaxonConcept'";
  	$query .= " AND TaxonConcept.id=Annotation.objectId ";
  	$query .= " AND BaseObject.id=Annotation.id";
  	//echo $query;

  	$result = mysqli_query($link, $query);
  	if (!$result)
  	echo '<b><span style="color:red">Problems querying the database</span></b><hr/><br/>';
  	else {

  		$numRows = mysqli_num_rows($result);
  		if ($numRows < 1)
  		echo '<h3><b>Existing Annotation of the Taxon Name: </b></h3>
              <table class="topBlueBorder"  width="760px"><tr><td><b>There are no existing annotations for this taxonomic name</b></td></tr></table>';
  		else {

  			echo '<h3><b>Existing Annotation of the Taxon Name: </b></h3>
         <table class="topBlueBorder"  width="760px">
           <tr><td width="15%px"><b>Date: </b></td>
               <td width="15%px"><b>Created By: </b></td>
               <td width="50%px"><b>Annotation Record: </b></td>
               <td width="20%px"><b>&nbsp;Make: </b></td>
           </tr>';
  			for ($index = 0; $index < $numRows; $index++) {
  				$colorIndex = $index % 2;
  				$row = mysqli_fetch_array($result);
  				$dateCreated = substr(trim($row['dateCreated']), 0, strpos(trim($row['dateCreated']), " "));

  				$pop = "";
  				if (isset($_GET['pop']) || isset($_POST['pop']))
  				$pop = "&amp;pop=yes";

  				echo '<tr style="background-color:' . $color[$colorIndex] . '">
                  <td width="10%px">' . $dateCreated . '</td>
                  <td width="20%px">' . $row['name'] . '</td>
                  <td width="60%px">' . $row['comment'] . '</td>
                  <td width="10%px"><a href="' . $config->domain . 'Admin/TaxonSearch/annotateTSN.php?tsn=' . $tsn . '&annotationId=' . $row['id'] . $pop . '" class="button smallButton"><div>similar</div></a></td>
                </tr>';
  			}
  			echo '</table>';
  		}
  	}
  }

  //function that checks if the person is in the right
  //group to annotate that taxon name
  function CheckGroup($tsn, $groupTSN)
  {
  	$parents_array = getTaxonBranchArray($tsn);
  	$i = 0;
  	$count = count($parents_array);
  	while ($i < $count) {
  		//echo "tsn".$parrents_array[$i]['tsn'];
  		//echo "groupTSN".$groupTSN;
  		if ($parents_array[$i]['tsn'] == $groupTSN)
  		return true;
  		$i++;
  		continue;
  	}
  	return false;
  }
  //end of group CheckGroup

  //function to display selection for new name status
  function NameStatus($link, $tsn)
  {
  	$query = "SELECT kingdom_id FROM Tree WHERE tsn=" . $tsn;
  	$result = mysqli_query($link, $query);
  	$row = mysqli_fetch_array($result);
  	$kingdom = $row['kingdom_id'];
  	echo '<td><select name="old_status" id="old_status" >
            <option value="1">-- Select unacceptibility reason --</option>';
  	if ($kingdom == 5 || $kingdom == 1 || $kingdom == 2) {
  		echo '<option value="homonym & junior synonym">homonym & junior synonym</option>
        <option value="junior homonym">junior homonym</option>
        <option value="junior synonym">junior synonym</option>
    <option value="nomen dubium">nomen dubium</option>
    <option value="nomen oblitum">nomen oblitum</option>
    <option value="original name/combination">original name/combination</option>
    <option value="subsequent name/combination">subsequent name/combination</option>
    <option value="unavailable, database artifact">unavailable, database artifact</option>
    <option value="unavailable, incorrect orig.spelling">unavailable, incorrect orig.spelling</option>
    <option value="unavailable, literature misspeling">unavailable, literature misspeling</option>
    <option value="unavailable, nomen nudum">unavailable, nomen nudum</option>
    <option value="unavailable, other">unavailable, other</option>
    <option value="unavailable, suppressed by ruling">unavailable, suppresed by rulling</option>
    <option value="unjustified emendation">unjustified emendation</option>
    <option value="unneeded replacement">unneeded replacement</option>';
  	}
  	if ($kingdom == 3 || $kingdom == 4 || $kingdom == 6) {
  		echo '<option value="database artifact">database artifact</option>
        <option value="homonym (illegitimate)">homonym (illegitimate)</option>
        <option value="horticultural">horticultural</option>
        <option value="invalidly published, nomen nudum">invalidly published, nomen nudum</option>
        <option value="invalidly published, other">invalidly published, other</option>
        <option value="orthographic variant(misspelling)">orthographic variant (misspeling)</option>
        <option value="rejected name">rejected name</option>
        <option value="superfluous renaiming (illegitimate)">superfluous renaiming (illegitimate)</option>
        <option value="synonym">synonym</option>';
  	}
  	echo '<option value="misapplied">misapplied</option>
          <option value="other,see comments">other, see comments</option>
      <option value="pro parte">pro parte</option>
      </select></td></tr><tr></tr>';
  }

  function echoJavaScript()
  {
  	echo '<script type="text/javascript">

      function updatePublication(id,title){
          document.forms[0].reference_name.value = title;
          document.forms[0].reference_id.value = id;
      }

      function updateSynonymTSN(id,name){
          document.forms[0].synonym_tsn.value = id;
          document.forms[0].synonym_name.value = name;
     }

      function updateParentTSN(id,name){
          document.forms[0].parent_tsn.value = id;
          document.forms[0].parent_name.value = name;
     }

     function CheckValues(form){
//alert("check mark is " + form.not_synonym.checked);
          if(form.title.value.length==0){
             alert("You have to fill title, that is a required field!");
          }
          else if(form.old_status.value==1){
            alert("You have to select unacceptibility reason for the existing name to submit valid annotation!")
          }
          else if(form.reference_id.value.length==0){
            alert("You have to provide publication to support your annotation");
          }
          else if(form.new_name.value.length==0 &&
             form.taxon_author.value.length==0 &&
             form.synonym_tsn.value.length==0 &&
             form.parent_tsn.value.length==0 &&
             form.notes.value.length==0 &&
             form.not_synonym.checked!="false"){
             alert("You have to suggest at least one change to submit valid annotation!");
          }
          else if(form.not_synonym.checked=="true" && form.reasons.value.length==0){
              alert("You have to provide reasons to support your annotation");
          }
          else if(form.parent_tsn.value.length!=0 && form.new_name.value==0 && form.rank_id.value > 180){
              alert("You have to provide new name if you suggest parent change for name bellow genus");
          }
          else
              form.submit();
     }

</script>';
  }
  //end of javascript functions
  ?>
	</p>
	</blockquote>
