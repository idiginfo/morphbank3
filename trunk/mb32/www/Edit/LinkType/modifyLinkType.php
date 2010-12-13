<?php
/**
 File name: modifyLinkType.php
 @author Neelima Jammigumpula <jammigum@scs.fsu.edu>
 @package Morphbank2
 @subpackage Submit
 @subpackage Edit
 @subpackage LinkType
 This script updates ExternalLinktype reference table with
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

 Table:	Sex

 Record:		id
 Field Name:	Name
 Old Value:	FemalE
 New Value:	Female

 Field Name:	Description
 Old Value:	FemalE Description
 New Value:	Female Description

 #of References:
 Specimen:	count
 View:		Count
 Image:		Count
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


$rows =  	  	trim($_POST['rows']);

$message = "Table:	ExternalLinkType <br /><br />";

//Get all the post variables for all the records as array element.
for($i = 0; $i < $rows; $i++){
	$name[$i] =  	  	mysqli_real_escape_string($link, trim($_POST[LinkType. $i]));
	$description[$i] =  	mysqli_real_escape_string($link, trim($_POST[Description .$i]));
	$id[$i] =  		mysqli_real_escape_string($link, trim($_POST[id .$i]));

	$curr = mysqli_fetch_array(runQuery('SELECT * FROM ExternalLinkType WHERE linkTypeId =' .$id[$i]));

	if($curr['name'] != $name[$i]){

		$modifiedFrom[$i] = 'ExternalLinkType: ' .$curr['name']. ' ';
		$modifiedTo[$i] = 'ExternalLinkType: ' .$name[$i] . ' ';
		$updates = 'name = \'' .$name[$i] .'\'';
		$message .= 'Record:	' .$id[$i]. '<br />Field Name:	ExternalLinkType <br />Old Value:	' .$curr['name']. '<br />New Value:	' .$name[$i];
	}

	if($curr['description'] != $description[$i]){
		$modifiedFrom[$i] .= 'Description: ' .$curr['description']. ' ';
		$modifiedTo[$i] .= 'Description: ' .$description[$i]. ' ';

		if($updates != ''){
			$updates = ', description = \'' .$description[$i] .'\'';
			$message .= '<br />Field Name:	Description<br />Old Value:	' .$curr['description']. '<br />New Value:	' .$description[$i];
		}else{
			$updates = ' description = \'' .$description[$i] .'\'';
			$message .= 'Record:	' .$id[$i]. '<br />Field Name:	Description<br />Old Value:	' .$curr['description']. '<br />New Value:	' .$description[$i];
		}
	}
		
	if($updates !=''){

		$query[$i] = "CALL ExternalLinkTypeUpdate(@oMsg, '" .$id[$i]. "', \"" .$updates. "\", " .$objInfo->getUserGroupId() . ", " .$objInfo->getUserId(). ", \"" .$modifiedFrom[$i] . "\", \"" .$modifiedTo[$i]. "\", \"Sex\");";

		$imgCountSql ='SELECT COUNT(*) AS imgCount FROM ExternalLinkObject WHERE extLinkTypeId = \'' .$id[$i]. '\';';
		$imgCount = mysqli_fetch_array(runQuery($imgCountSql));


		$message .= '<br />No. of Specimens,Images,Views,Localities using this value: ' .$imgCount['imgCount'] . '<br />';
		$flag = false;
	}
}

/*echo 'message: '. $message. '<br /><br />Modified From: ' .$modifiedFrom. '<br />Modified To: ' .$modifiedTo;
 for($i = 0; $i < $rows; $i++)
 echo 'Query:	' .$query[$i]. '<br />';
 */

if(checkAuthorization('LinkType', $objInfo->getUserId(), $objInfo->getUserGroupId(), 'Modify')){
	for($i = 0; $i < $rows; $i++){

		if($query[$i]){
			//echo $query[$i];
			$results = mysqli_query($link, $query[$i]);

			if($results) {
				History($id[$i], $objInfo->getUserId(), $objInfo->getUserGroupId(), $modifiedFrom[$i], $modifiedTo[$i], "Linktype");
			} else {
				echo $query. '<BR>';
				echo mysqli_error($link);
			}
		}
	}
	$url = 'index.php';
	//$url = 'index.php?code=1&id='.$curr['id'];
	header ("location: ".$url);
	exit;
}else{

	$url = '../mailpage.php?message='.$message;
	header ("location: ".$url);
	exit;
} // end of else part of checkAuthorization
?>
