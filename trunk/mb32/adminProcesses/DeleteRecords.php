<?php

/**
* File name: DeleteRecords.php 
* @package MB30
* @subpackage: AdminProcesses 
* @author Neelima Jammigumpula  <jammigum@csit.fsu.edu>
* This script can be used to delete records by passing the arguments as described in the next few lines.

Argument 1: Objects to delete. Example: "Specimen, Image, ..". Locality, Specimen, View, Image are the only objects supported at this stage. External links created for all these will be deleted automatically.
Argument 2: id list/ id from. Example: "1, 2, 3, 4, 5, ..." or 123 (lowest no.) 
Argument 3: id to. Example: 345 (higher no.) 

Usage: From command line

//The following will delete Localities, Specimens with ids between 99 and 112. Have to include them in quotes if there is more than one object type.
php DeleteRecords.php "Locality, Specimen" 100 111

//This is a variation of the above and deletes Localities, Specimens with ids 100, 101, 102, 103
php DeleteRecords.php "Locality, Specimen" "100,101,102,103"

**/

require_once ('../configuration/app.server.php');
include_once('imageFunctions.php');


$link = Adminlogin();
$numargs = $argc;
//echo "Number of arguments: $numargs<br />\n";

$Objects =  explode(',', $argv[1]);
//print_r($Objects);

if($numargs == 4){
	$idFrom =  $argv[2];
	$idTo =  $argv[3];
	//echo "\n idFrom  " .$idFrom. "      idTo: " .$idTo;
}else if($numargs == 3){
	$ids = explode(',', $argv[2]);
	//print_r($ids);
}

if (!$link)
        echo "Error connecting to $database";

mysqli_select_db($link,'MB30');

$sql = "SELECT id, objectTypeId FROM BaseObject WHERE (";

for ( $i=0; $i < count($Objects) ; $i++ ) 
	$sql .= ' objectTypeId = \'' .trim($Objects[$i]). '\' or ';
$sql = preg_replace('/or $/', '', $sql);
$sql .= ")";

if($numargs == 4)
	$sql .= " AND id >= $idFrom AND id <= $idTo";
else if($numargs == 3){

	$sql .= " AND id in ( ";
	for ( $i=0; $i < count($ids) ; $i++ ) 
		$sql .=  "$ids[$i], ";
	$sql = preg_replace('/, $/', '', $sql);
	$sql .=  ") ";
}

$sql.= " ORDER BY id desc;";

echo $sql. "\n";

$result = mysqli_query($link, $sql);

if (!$result) {
	echo "Could not successfully run query ($sql) from DB: " . mysqli_error($link);
        exit;
}

if (mysqli_num_rows($result) == 0) {
	echo "No rows found, nothing to print so am exiting";
	exit;
}
while($array = mysqli_fetch_array($result)){

	if($array['objectTypeId'] == 'Locality'){
		$delete = "CALL LocalityDelete(@oMsg, " .$array['id']. ");";
		$objSql = "SELECT id FROM Specimen WHERE localityId = " .$array['id']. ";";
	}else if($array['objectTypeId'] == 'Specimen'){
		$delete = "CALL SpecimenDelete(@oMsg, " .$array['id']. ");";
		$objSql = "SELECT id FROM Image WHERE specimenId = " .$array['id']. ";";
	}else if($array['objectTypeId'] == 'View'){
		$delete = "CALL ViewDelete(@oMsg, " .$array['id']. ");";
		$objSql = "SELECT id FROM Image WHERE viewId = " .$array['id']. ";";
	}else if($array['objectTypeId'] == 'Image'){
		$objSql = "SELECT id FROM Annotation WHERE objectId = " .$array['id']. ";";
		$imgType = mysqli_fetch_array(mysqli_query($link, 'SELECT imageType FROM Image WHERE id = ' .$array['id']));
		$delete = "CALL ImageDelete(@oMsg, " .$array['id']. ");";

		$imagePath = getImagePathOld($array['id']);

                $imgsFileName[0] = 'jpeg/'.$imagePath;
                $imgsFileName[1] = 'tiff/'.$imagePath;
                $imgsFileName[2] = 'jpg/'.$imagePath;
                $imgsFileName[3] = 'thumbs/'.$imagePath;

                $imgsFileExt[0] = "jpeg";

		if(strtolower($imgType['imageType']) == 'jpg') 
			$imgsFileExt[1] = 'tif';
		else
                	$imgsFileExt[1] = strtolower($imgType['imageType']);

                $imgsFileExt[2] = "jpg";
                $imgsFileExt[3] = "jpg";

                for ($i=0; $i < count( $imgsFileName); $i++) {
                        echo "deleting $imgsFileName[$i].$imgsFileExt[$i]\n";
                        $command = "rm $imgsFileName[$i].$imgsFileExt[$i]";
			echo $command. "\n";
//uncomment the following line to test when the new developer machine is up. This will not work for BMPs as they are converted to tiffs and uploaded where as imageType is BMP in the database
                        //exec($command);
                }
	}
echo $delete. "\n";
exit;
	$res = mysqli_query($link, $delete) or die ('Could not run query' .$delete. "\n" . mysqli_error($link));
	$msgRes = mysqli_multi_query($link, "SELECT @oMsg;");
	$msg = mysqli_store_result($link);
	$message = mysqli_fetch_array($msg);

	echo "Operation: " .$message[0]. "\n";
	if(eregi("Failed", $message[0]) > 0 ){

		$str = $array['objectTypeId'] . "              " .$array['id']. "\n Ids: ";
		$objs = mysqli_query($link, $objSql);
	
		while($objIds = mysqli_fetch_array($objs))
			$str .= $objIds['id']. ", ";
	}

	$str = preg_replace('/, $/', '', $str);
	echo $str ."\n";
}
?>
