<?php
/**
 File name: modifySex.php
 @author Neelima Jammigumpula <jammigum@scs.fsu.edu>
 @package Morphbank2
 @subpackage Submit
 @subpackage Edit
 @subpackage Sex
 This script updates Sex reference table with
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

$message = "Table:  Sex <br /><br />";


//Get all the post variables for all the records as array element.
for ($i = 0; $i < $rows; $i++) {
	$name[$i] = mysqli_real_escape_string($link, trim($_POST[Sex . $i]));
	$description[$i] = mysqli_real_escape_string($link, trim($_POST[Description . $i]));
	$id[$i] = mysqli_real_escape_string($link, trim($_POST[id . $i]));

	$curr = mysqli_fetch_array(runQuery('SELECT * FROM Sex WHERE name =\'' . $id[$i] . '\''));

	if ($curr['name'] != $name[$i]) {
		$modifiedFrom[$i] = 'Sex: ' . $curr['name'] . ' ';
		$modifiedTo[$i] = 'Sex: ' . $name[$i] . ' ';
		$updates = 'name = \'' . $name[$i] . '\'';

		$message .= 'Record:  ' . $id[$i] . '<br />Field Name:  Sex <br />Old Value:  ' . $curr['name'] . '<br />New Value:  ' . $name[$i];
	}

	if ($curr['description'] != $description[$i]) {
		$modifiedFrom[$i] .= 'Description: ' . $curr['description'] . ' ';
		$modifiedTo[$i] .= 'Description: ' . $description[$i] . ' ';

		if ($updates != '') {
			$updates = ', description = \'' . $description[$i] . '\'';
			$message .= '<br />Field Name:  Description<br />Old Value:  ' . $curr['description'] . '<br />New Value:  ' . $description[$i];
		} else {

			$updates = ' description = \'' . $description[$i] . '\'';
			$message .= 'Record:  ' . $id[$i] . '<br />Field Name:  Description<br />Old Value:  ' . $curr['description'] . '<br />New Value:  ' . $description[$i];
		}
	}

	if ($updates != '') {
		$query[$i] = "CALL SexUpdate(@oMsg, '" . $id[$i] . "', \"" . $updates . "\", " . $objInfo->getUserGroupId() . ", " . $objInfo->getUserId() . ", \"" . $modifiedFrom[$i] . "\", \"" . $modifiedTo[$i] . "\", \"Sex\");";

		$imgCount = mysqli_fetch_array(runQuery('SELECT COUNT(*) AS imgCount FROM Image, Specimen, View
        WHERE Image.specimenId = Specimen.id AND Image.viewId = View.id AND 
        (Specimen.sex = \'' . $id[$i] . '\' OR  View.sex = \'' . $id[$i] . '\');'));

		$specCount = mysqli_fetch_array(runQuery('SELECT COUNT(*) AS specCount FROM Specimen WHERE sex = \'' . $id[$i] . '\';'));
		$viewCount = mysqli_fetch_array(runQuery('SELECT COUNT(*) AS viewCount FROM View WHERE sex = \'' . $id[$i] . '\';'));

		$message .= '<br />No. of Specimens using this value: ' . $specCount['specCount'] . '<br />';
		$message .= 'No. of Views using this value: ' . $viewCount['viewCount'] . '<br />';
		$message .= 'No. of Images using this value: ' . $imgCount['imgCount'] . '<br /><br />';
		$updates = '';
	}
}

/*/ / echo 'message: ' . $message . '<br /><br />Modified From: ' . $modifiedFrom . '<br />Modified To: ' . $modifiedTo;
 for ($i = 0; $i < $rows; $i++)
 echo 'Query:  ' . $query[$i] . '<br />';
 exit;
 *  / if (checkAuthorization('Sex', $objInfo->getUserId(), $objInfo->getUserGroupId(), 'Modify'))
 {
 for ($i = 0; $i < $rows; $i++) {
 //echo $query[$i];
 if ($query[$i] != '') {
 // or die('Could not run query: <br />' .$query[$i]. '<br />' .$mysqli_error($link));
 $results = mysqli_query($link, $query[$i]);
 }
 }

 $url = 'index.php';
 header("location: " . $url);
 exit;
 } else {


 $url = '../mailpage.php?message=' . $message;
 header("location: " . $url);
 exit;
 }
 // end of else part of checkAuthorization
 ?>
