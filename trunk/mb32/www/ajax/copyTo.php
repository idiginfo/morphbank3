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

// File is called by an AJAX call, to insert objects into a collection or a character.  Returns only an echo "TRUE" or echo "FALSE" 


include_once ('collectionFunctions.inc.php');
include_once('Phylogenetics/phylo.inc.php');

$idString = (isset($_GET['idString'])) ? $_GET['idString'] : FALSE;
$collectionType = (isset($_GET['type'])) ? $_GET['type'] : FALSE;

if ($idString) {
	// get rid of the trailing underscore
	$idString = substr_replace($idString, '', -1, 1);
	$array = explode("_", $idString);
	
	$index = 0;
	$collecionInsertArray = array();
	
	if ($collectionType == "Otu") {
		$objectTypeArray = explode("_", $_GET['objectType']);
		
		$objectType = $objectTypeArray[0];
		
		if ($objectType == "Specimen") {
			foreach($array as $idArray) {
				$sql = 'SELECT id, objectTypeId FROM BaseObject WHERE id='.$idArray;
				
				$result = mysqli_query($link, $sql);
				
				if ($result) {
					$collectionInsertArray[$index] = mysqli_fetch_assoc($result);
					$index++;
				} else
					echo mysqli_error($link);
			}
		} elseif ($objectType == "Tsn") {
			foreach($array as $idArray) {	
				$tempArray = explode("=", $idArray);			
				$collectionInsertArray[$index]['id'] = $tempArray[1];
				$collectionInsertArray[$index]['objectTypeId'] = $tempArray[0];
				$index++;				
			}
		} else {
			echo "FALSE";
			exit(0);	
		}
		
		$otu = new Otu($link, $_GET['id']);
		
		if($otu->addObjectToOtu($collectionInsertArray))
			echo '<div id="trueTest">TRUE</div>';
		else 
			echo "FALSE";
		
		//var_dump($collectionInsertArray);
	
	} else {
	
		foreach($array as $idArray) {
			$sql = 'SELECT id, objectTypeId FROM BaseObject WHERE id='.$idArray;
			
			$result = mysqli_query($link, $sql);
			
			if ($result) {
				$collectionInsertArray[$index] = mysqli_fetch_assoc($result);
				$index++;
			} else
				echo mysqli_error($link);
		}
		
		//var_dump($collectionInsertArray);
	
		if ($collectionType == "Collection") {
			$numObjs = count($collectionInsertArray);
			insertObjects( $collectionInsertArray, NULL, $_GET['id'], $numObjs);
			updateCollectionKeywords($_GET['id']);
			echo '<div id="trueTest">TRUE</div>';
			
		} elseif ($collectionType == "Character") {
			$characterId = $_GET['id'];
		
			//echo $characterId.'<br>';
			//var_dump($imageIdArray);
			//exit(0);
			$phyloCharacter = new PhyloCharacter($link, $characterId );	
			$phyloCharacter->addImageToCharacter($collectionInsertArray);
			echo '<div id="trueTest">TRUE</div>';
				
		} else
			echo "FALSE";
	}
	
} else
	echo "FALSE";

/*
	
	header('Location:'.$config->domain.'myCollection/index.php?collectionId='.$_GET['id']);
*/

?>
