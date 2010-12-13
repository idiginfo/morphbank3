<?php
/**
 File name: modifyStage.php
 @author Neelima Jammigumpula <jammigum@scs.fsu.edu>
 @package Morphbank2
 @subpackage Submit
 @subpackage Edit
 @subpackage Stage
 This script updates DevelopmentalStage reference table with
 appropriate update queries..
  
 Included Files:

 

  
 Functions:
  
 checkIfLogged : checks if the user is logged and directs to Submit page if not logged.
 groups: checks if the user selected the group he would use. Redirects to groups page
 under Submit for group selection.
 Adminlogin(): Connects to the database for writing.
  
 Message:
 /*
 Message has the following format:
  
 Table:  Sex
  
 Record:    id
 Field Name:  Name
 Old Value:  FemalE
 New Value:  Female
  
 Field Name:  Description
 Old Value:  FemalE Description
 New Value:  Female Description
  
 #of References:
 Specimen:  count
 View:    Count
 Image:    Count
 The above is repeated for each record change
 **/


if ($_REQUEST['pop']) {
	include_once('pop.inc.php');
} else {
	include_once('head.inc.php');
}


checkIfLogged();
groups();
$link = Adminlogin();


$rows = trim($_POST['rows']);

$message = "Table:  Developmental Stage <br /><br />";

//Get all the post variables for all the records as array element.
for ($i = 0; $i < $rows; $i++) {
	$name[$i] = mysqli_real_escape_string($link, trim($_POST[Stage . $i]));
	$description[$i] = mysqli_real_escape_string($link, trim($_POST[Description . $i]));
	$id[$i] = trim($_POST[id . $i]);

	$curr = mysqli_fetch_array(runQuery('SELECT * FROM DevelopmentalStage WHERE name =\'' . $id[$i] . '\''));


	if ($curr['name'] != $name[$i]) {
		$modifiedFrom[$i] = 'Developmental Stage: ' . $curr['name'] . ' ';
		$modifiedTo[$i] = 'Developmental Stage: ' . $name[$i] . ' ';
		$flag = true;
	}


	if ($curr['description'] != $description[$i]) {
		$modifiedFrom[$i] .= 'Description: ' . $curr['description'] . ' ';
		$modifiedTo[$i] .= 'Description: ' . $description[$i] . ' ';
		$flag = true;
	}

	if (($curr['name'] != $name[$i]) && ($curr['description'] != $description[$i])) {
		$query[$i] = 'UPDATE DevelopmentalStage SET  name = \'' . $name[$i] . '\', description = \'' . $description[$i] . '\' where name = \'' . $id[$i] . '\' ;';
		$message .= 'Record:  ' . $id[$i] . '<br />Field Name:  Developmental Stage <br />Old Value:  ' . $curr['name'] . '<br />New Value:  ' . $name[$i] . '<br />' . 'Field Name:  Description <br />Old Value:  ' . $curr['description'] . '<br />New Value:  ' . $description[$i] . '<br />';
	} elseif ($curr['name'] != $name[$i]) {
		$query[$i] = 'UPDATE DevelopmentalStage SET  name = \'' . $name[$i] . '\' where name = \'' . $id[$i] . '\' ;';
		$message .= 'Record:  ' . $id[$i] . '<br />Field Name:  Developmental Stage <br />Old Value:  ' . $curr['name'] . '<br />New Value:  ' . $name[$i] . '<br />';
	} elseif ($curr['description'] != $description[$i]) {
		$query[$i] .= ' UPDATE DevelopmentalStage SET description = \'' . $description[$i] . '\'  where name = \'' . $id[$i] . '\' ;';
		$message .= '<br />Record:  ' . $id[$i] . '<br />Field Name:  Description<br />Old Value:  ' . $curr['description'] . '<br />New Value:  ' . $description[$i] . '<br />';
	}

	if ($flag) {
		$imgCount = mysqli_fetch_array(runQuery('SELECT COUNT(*) AS imgCount FROM Image, Specimen, View
                                WHERE Image.specimenId = Specimen.id AND Image.viewId = View.id AND
                                (Specimen.developmentalStage = \'' . $id[$i] . '\' OR  View.developmentalStage = \'' . $id[$i] . '\');'));

		$specCount = mysqli_fetch_array(runQuery('SELECT COUNT(*) AS specCount FROM Specimen WHERE developmentalStage = \'' . $id[$i] . '\';'));
		$viewCount = mysqli_fetch_array(runQuery('SELECT COUNT(*) AS viewCount FROM View WHERE developmentalStage = \'' . $id[$i] . '\';'));


		$message .= '<br />No. of Specimens using this value: ' . $specCount['specCount'] . '<br />';
		$message .= 'No. of Views using this value: ' . $viewCount['viewCount'] . '<br />';
		$message .= 'No. of Images using this value: ' . $imgCount['imgCount'] . '<br />';
		$flag = false;
	}
}

/*echo 'message: '. $message. '<br /><br />Modified From: ' .$modifiedFrom. '<br />Modified To: ' .$modifiedTo;
 for($i = 0; $i < $rows; $i++)
 echo 'Query:  ' .$query[$i]. '<br />';
 */

if (checkAuthorization('Stage', $objInfo->getUserId(), $objInfo->getUserGroupId(), 'Modify')) {
	for ($i = 0; $i < $rows; $i++) {
		if ($query[$i]) {
			//echo $query[$i];
			$results = mysqli_query($link, $query[$i]);

			if ($results) {
				History($id[$i], $objInfo->getUserId(), $objInfo->getUserGroupId(), $modifiedFrom[$i], $modifiedTo[$i], "DevelopmentalStage");
			} else {
				echo $query . '<BR>';
				echo mysqli_error($link);
			}
		}
	}

	$url = 'index.php';
	//$url = 'index.php?code=1&id='.$curr['id'];
	header("location: " . $url);
	exit;
} else {


	$url = '../mailpage.php?message=' . $message;
	header("location: " . $url);
	exit;
}
// end of else part of checkAuthorization
?>
