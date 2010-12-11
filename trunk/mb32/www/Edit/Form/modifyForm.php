#-------------------------------------------------------------------------------
# Copyright (c) 2010 Greg Riccardi, Fredrik Ronquist.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Public License v2.0
# which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# 
# Contributors:
#   Fredrik Ronquist - conceptual modeling and interaction design
#   Austin Mast - conceptual modeling and interaction design
#   Greg Riccardi - initial API and implementation
#   Wilfredo Blanco - initial API and implementation
#   Robert Bruhn - initial API and implementation
#   Christopher Cprek - initial API and implementation
#   David Gaitros - initial API and implementation
#   Neelima Jammigumpula - initial API and implementation
#   Karolina Maneva-Jakimoska - initial API and implementation
#   Katja Seltmann - initial API and implementation
#   Stephen Winner - initial API and implementation
#-------------------------------------------------------------------------------
<?php
/**
 File name: modifyForm.php
 @author Neelima Jammigumpula <jammigum@scs.fsu.edu>
 @package Morphbank2
 @subpackage Submit
 @subpackage Edit
 @subpackage Form
 This script updates Form reference table with
 appropriate update queries..
  
 Included Files:

   
 Functions:
  
 checkIfLogged : checks if the user is logged and directs to Submit page if not logged.
 groups: checks if the user selected the group he would use. Redirects to groups page
 under Submit for group selection.
 Adminlogin(): Connects to the database for writing.
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

$message = "Table:  Form <br /><br />";


//Get all the post variables for all the records as array element.
for ($i = 0; $i < $rows; $i++) {
	$name[$i] = mysqli_real_escape_string($link, trim($_POST[Form . $i]));
	$description[$i] = mysqli_real_escape_string($link, trim($_POST[Description . $i]));
	$id[$i] = mysqli_real_escape_string($link, trim($_POST[id . $i]));

	$curr = mysqli_fetch_array(runQuery('SELECT * FROM Form WHERE name =\'' . $id[$i] . '\''));

	if ($curr['name'] != $name[$i]) {
		$modifiedFrom[$i] = 'Form: ' . $curr['name'] . ' ';
		$modifiedTo[$i] = 'Form: ' . $name[$i] . ' ';
		$updates = 'name = \'' . $name[$i] . '\'';

		$message .= 'Record:  ' . $id[$i] . '<br />Field Name:  Form <br />Old Value:  ' . $curr['name'] . '<br />New Value:  ' . $name[$i];
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
		$query[$i] = "CALL FormUpdate(@oMsg, '" . $id[$i] . "', \"" . $updates . "\", " . $objInfo->getUserGroupId() . ", " . $objInfo->getUserId() . ", \"" . $modifiedFrom[$i] . "\", \"" . $modifiedTo[$i] . "\", \"Form\");";

		$imgCount = mysqli_fetch_array(runQuery('SELECT COUNT(*) AS imgCount FROM Image, Specimen, View
        WHERE Image.specimenId = Specimen.id AND Image.viewId = View.id AND 
        (Specimen.form = \'' . $id[$i] . '\' OR  View.form = \'' . $id[$i] . '\');'));

		$specCount = mysqli_fetch_array(runQuery('SELECT COUNT(*) AS specCount FROM Specimen WHERE form = \'' . $id[$i] . '\';'));
		$viewCount = mysqli_fetch_array(runQuery('SELECT COUNT(*) AS viewCount FROM View WHERE form = \'' . $id[$i] . '\';'));

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
 *  / if (checkAuthorization('Form', $objInfo->getUserId(), $objInfo->getUserGroupId(), 'Modify'))
 {
 for ($i = 0; $i < $rows; $i++) {
 //echo $query[$i];
 if ($query[$i] != '') {
 $results = mysqli_query($link, $query[$i]) or die('Could not run query: <br />' . $query[$i] . '<br />' . $mysqli_error($link));
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
