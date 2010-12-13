<?php

// Script will loop through the objectArray and get all the id's from the BaseObject table that do not have
// a corresponding record in the appropriate table (i.e. An Image record in the B.O. with no record in the Image table
require_once('../configuration/app.server.php');

$link = Adminlogin();

mysqli_select_db($link, "MB30");

$objectArray = array('Annotation', 'Collection', 'Groups', 'Image', 'Locality', 'News', 'Publication', 'Specimen', 'User', 'View');

$deleteArray = array('DevelopmentalStage', 'Form', 'ImagingPreparationTechnique', 'ImagingTechnique', 'Sex', 'SpecimenPart', 'ViewAngle');

foreach($deleteArray as $array) {
	$sql = 'DELETE FROM BaseObject WHERE objectTypeId = "'.$array.'"';
	
	//echo $sql."\n";
	
	mysqli_query($link, $sql);

}

$sql = 'UPDATE BaseObject SET objectTypeId = "Groups" WHERE objectTypeId="Group" ';
mysqli_query($link, $sql);

$sql = 'UPDATE BaseObject SET objectTypeId = "Collection" WHERE objectTypeId="myCollection" ';
mysqli_query($link, $sql);

$sql = 'UPDATE BaseObject SET objectTypeId = "Locality" WHERE objectTypeId="Location" ';
mysqli_query($link, $sql);


foreach($objectArray as $array) {
	$sql = 'SELECT BaseObject.id AS BO_id, '.$array.'.id AS id FROM BaseObject LEFT JOIN '.$array.' ON BaseObject.id = '.$array.'.id WHERE '.$array.'.id IS NULL AND BaseObject.objectTypeId = "'.$array.'" ';
	//echo $sql."\n\n";
	
	$results = mysqli_query($link, $sql);
	
	//echo $array;
	//echo "\n\n";
			
	$count = 0;
	if ($results) {
		while ($row = mysqli_fetch_array($results)) {
			
			/* output to the screen a list of the ids which are junk
			echo $row['BO_id']." ".$row['id'];
			echo "\n";
			$count++;
			*/
			
			$deleteSql = 'DELETE FROM BaseObject WHERE id = '.$row['BO_id'];
			
			//echo $deleteSql."\n";
			
			$r = mysqli_query($link, $deleteSql);
			
			//echo mysqli_affected_rows($link);
			/*
			if (mysqli_warning_count($link))
				echo mysqli_error($link);
			*/
			
		}
		
	}
	
	//echo $count."\n\n";
	
}
s
?>
