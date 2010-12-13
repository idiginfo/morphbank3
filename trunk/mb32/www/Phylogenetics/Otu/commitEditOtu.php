<?php

//File to commit the Otu information changes to the
//database
//
//@author Karolina Maneva-Jakimoska
//@date created September 26th 2007
//

include_once('head.inc.php');
include_once('collectionFunctions.inc.php');
include_once('Phylogenetics/phylo.inc.php');

global $config;

$groupId = $objInfo->getUserGroupId();
$userId = $objInfo->getUserId();

if(isset($_POST['id'])){
	$url = $config->domain . 'Phylogenetics/Otu/editOtu.php?id='.$_POST['id'];
	$link = Adminlogin();
	$id=$_POST['id'];
	$flagb=0; $flagt=0; $flago=0; $flag_error=0;

	$query_updateBO = "Update BaseObject set ";
	$queryT = "Update Taxa set";
	$query_updateO = "Update Otu set ";
	$queryh = "INSERT INTO History (id,userId,groupId,dateModified,modifiedFrom,modifiedTo,tableName) VALUES(";
	$queryT = "Update Taxa set";
	$modifiedFrom = "";
	$modifiedTo = "";

	//update base object for character
	if($_POST['description_old']!=$_POST['description']){
		$query_updateBO .= " description = '".$_POST['description']."',";
		$modifiedFrom .= " description: ".trim($_POST['description_old']);
		$modifiedTo .= " description: ".trim($_POST['description']);
		$flagb=1;
	}
	if($_POST['dateToPublish_old']!=$_POST['dateToPublish']){
		$query_updateBO .= " dateToPublish = '".$_POST['dateToPublish']."',";
		$queryT .= " dateToPublish = '".$_POST['dateToPublish']."',";
		$modifiedFrom .= " dateToPublish: ".trim($_POST['dateToPublish_old']);
		$modifiedTo .= " dateToPublish: ".trim($_POST['dateToPublish']);
		$flagb=1;
		$flagt=1;
	}
	if($_POST['title_old']!=$_POST['title']){
		$query_updateBO .= " name = '".$_POST['title']."',";
		$queryT .= " scientificName = '".$_POST['title']."',";
		$modifiedFrom .= " name: ".trim($_POST['title_old']);
		$modifiedTo .= " name: ".trim($_POST['title']);
		$flagb=1;
		$flagt=1;
	}
	if($_POST['label_old']!=$_POST['label']){
		$query_updateO .= " label = '".$_POST['label']."',";
		$modifiedFrom .= " label: ".trim($_POST['label_old']);
		$modifiedTo .= " label: ".trim($_POST['label']);
		$flago=1;
	}


	if($flagb==1){
		if(strrpos($query_updateBO,",")==(strlen($query_updateBO)-1))
		$query_updateBO = substr($query_updateBO,0,strlen($query_updateBO)-1);
		$query_updateBO .= " where id = ".$_POST['id'].";";

		$result = mysqli_query($link,$query_updateBO);
		if(!$result)
		$flag_error=1;
		else{
			$queryH = $queryh.$_POST['id'].",".$userId.",".$groupId.",NOW(),'".$modifiedFrom."','".$modifiedTo."','BaseObject')";
			$result = mysqli_query($link,$queryH);
			if(!$result)
			$flag_error=1;
			$modifiedFrom = "";
			$modifiedTo = "";
		}

	}
	if($flago==1){
		if(strrpos($query_updateO,",")==(strlen($query_updateO)-1))
		$query_updateO = substr($query_updateO,0,strlen($query_updateO)-1);
		$query_updateO .= " where id = ".$_POST['id'].";";
		$result = mysqli_query($link,$query_updateO);
		if(!$result)
		$flag_error=1;
		else {
			$queryH = $queryh.$_POST['id'].",".$userId.",".$groupId.",NOW(),'".$modifiedFrom."','".$modifiedTo."','Otu')";
			$result = mysqli_query($link,$queryH);
			if(!$result)
			$flag_error=1;
			$modifiedFrom = "";
			$modifiedTo = "";
		}
	}
	if($flagt==1){
		if(strrpos($queryT,",")==(strlen($queryT)-1))
		$queryT = substr($queryT,0,strlen($queryT)-1);
		$queryT .= " where boId = ".$_POST['id'].";";
		$result = mysqli_query($link,$queryT);
		if(!$result)
		$flag_error=1;
	}

	if($flag_error!=1){
		//update the Taxa keywords table
		TaxaKeywords($link,NULL,$_POST['id']);
	}
}
$url .= "&fe=".$flag_error;
header("location:".$url);
exit();
?>

