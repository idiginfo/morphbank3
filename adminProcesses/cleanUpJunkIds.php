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
