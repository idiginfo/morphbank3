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

include_once('../public_html/includes/tsnFunctions.php');

// Username and password
$db_user = 'webuser';
$db_passwd = 'namaste';
$host = 'localhost';
$port = '3306';
//$database = 'MB30';
$database = 'MB30';

//$startTime = time();

// Connect to MySQL server
$link = mysqli_connect($host, $db_user, $db_passwd, $database, $port);

if (!$link)
        echo "Error connecting to $database";

updateTaxonomicNamesOnSpecimen();

function updateTaxonomicNamesOnSpecimen( $specimenId = NULL) {

	global $link;
	
	if ($specimenId) { // For one specimen
		$sql = "SELECT tsnId From Specimen WHERE id='".$specimenId."';";
		//echo $sql."\n";
		$result = mysqli_fetch_array( mysqli_query($link, $sql));
		if (!$result) 
			die('Invalid query: ' . mysqli_error($link));
		$arrayOfParents = getArrayParents($result['tsnId']);
		
		$arraySize = count($arrayOfParents);
		$taxonomicNames = "";
		for($i=1; $i < $arraySize; $i++) { // skip life node
			 $taxonomicNames .= " ".$arrayOfParents[$i]['name']; 
		}
		//echo $taxonomicNames."\n";
		
		$sql = "UPDATE Specimen SET taxonomicNames='".$taxonomicNames."' ";
		$sql .= "WHERE id='".$specimenId."';";
		//echo $sql."\n";
		if (mysqli_query($link, $sql)) 
			echo "done\n";
		else {
			echo "not done\n";
			die('Invalid query: ' . mysqli_error($link));
		}
	}
	else { // For all specimen table
		$sql = "SELECT tsnId From Specimen GROUP BY tsnId;";
		//echo $sql."\n";
		$result = mysqli_query($link, $sql);
		if (!$result) 
			die('Invalid query: ' . mysqli_error($link));
		
		$j=0;
		while ($rowTsn = mysqli_fetch_array( $result)) {
			$arrayOfParents = getArrayParents($rowTsn['tsnId']);
		
			$arraySize = count($arrayOfParents);
			$taxonomicNames = "";
			for($i=1; $i < $arraySize; $i++) { // skip life node
				 $taxonomicNames .= " ".$arrayOfParents[$i]['name']; 
			}
			//echo $taxonomicNames."\n";
			$sql = "UPDATE Specimen SET taxonomicNames='".$taxonomicNames."' ";
			$sql .= "WHERE tsnId='".$rowTsn['tsnId']."';";
			//echo $sql."\n";
			if (mysqli_query($link, $sql)) 
				echo $j++." done\n";
			else {
				echo "not done\n";
				die('Invalid query: ' . mysqli_error($link));
			}
		}
	}
}
?>
