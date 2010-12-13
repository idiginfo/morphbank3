<?php

include_once('head.inc.php');
include_once('collectionFunctions.inc.php');
require_once('Phylogenetics/Classes/BaseObject.php');
require_once('Phylogenetics/Classes/Collection.php');
require_once('Phylogenetics/Classes/CollectionObject.php');
require_once('Phylogenetics/Classes/CharacterState.php');
require_once('Phylogenetics/Classes/PhyloCharacter.php');

$link = AdminLogin();

if (isset($_GET['mass'])) {
	$idArray = explode("_", $_GET['idString']);
	$userId = $_GET['userId'];
	$divArray = explode("_", $_GET['divString']);
	
	$idCount = count($idArray)-1;
	$returnVar = "";
	
	/*
	var_dump($idArray);
	echo "<br /><br />";
	var_dump($divArray);
	echo "<br /><br />";
	echo 'idCount: '.$idCount;
	*/
	
	for ($i=0; $i < $idCount; $i++) {
		$sql = 'SELECT *, NOW() as now FROM BaseObject WHERE id='.$idArray[$i];
		$result = mysqli_query($link, $sql) or die(mysqli_error($link));
		
		if ($result) {
			$array= mysqli_fetch_array($result);
			echo ' '.$array['dateToPublish'].' '.$array['now'];
			if ($array['userId'] == $userId && $array['dateToPublish'] > $array['now']) {
				
				if ($array['objectTypeId'] == "Annotation") {
					deleteAnnotation($idArray[$i]);
					$returnVar .= $divArray[$i].'_';
				} 
				elseif ($array['objectTypeId'] == "Collection") {
					deleteCollection($idArray[$i]);
					$returnVar .= $divArray[$i].'_';
				}
				
				// Should be phased out but left in so code won't break
				elseif ($array['objectTypeId'] == "PhyloCharacter") {
					$phyloChar = new PhyloCharacter($link, $idArray[$i]);
					$phyloChar->deleteCharacterCollectionFromDB();
					
					$returnVar .= $divArray[$i].'_';
				}
				elseif ($array['objectTypeId'] == "MbCharacter") {
					$phyloChar = new PhyloCharacter($link, $idArray[$i]);
					$phyloChar->deleteCharacterCollectionFromDB();
					
					$returnVar .= $divArray[$i].'_';
				}
				
			}
		}
	}
	
	$returnVar = substr_replace($returnVar, '', -1, 1);
	
	if ($returnVar)
		echo '<div id="deleteConfirm">'.$returnVar.'</div>';
	else
		echo '<div id="error">&nbsp;</div>';
	
} else {
	$id = $_GET['id'];
	$div = $_GET['div'];
	$userId = $_GET['userId'];

	$sql = 'SELECT *, NOW() as now FROM BaseObject WHERE id='.$id;
	$result = mysqli_query($link, $sql);
	
	if ($result) {
		$array= mysqli_fetch_array($result);
		
		if ($array['userId'] == $userId && $array['dateToPublish'] > $array['now']) {
			if ($array['objectTypeId'] == "Annotation") {
				deleteAnnotation($id);
				echo '<div id="deleteConfirm">'.$div.'</div>';
			} 
			elseif ($array['objectTypeId'] == "Collection") {
				deleteCollection($id);
				echo '<div id="deleteConfirm">'.$div.'</div>';	
			}
			
			// Should be phased out but left in so code won't break
			elseif ($array['objectTypeId'] == "PhyloCharacter") {
				$phyloChar = new PhyloCharacter($link, $id);
				$phyloChar->deleteCharacterCollectionFromDB();
				
				echo '<div id="deleteConfirm">'.$div.'</div>';	
			}
			elseif ($array['objectTypeId'] == "MbCharacter") {
				$phyloChar = new PhyloCharacter($link, $id);
				$phyloChar->deleteCharacterCollectionFromDB();
				
				echo '<div id="deleteConfirm">'.$div.'</div>';	
			}
			
			else
				echo '<div id="error">&nbsp;</div>';
		}
		else
			echo '<div id="error">&nbsp;</div>';
	}
	else
		echo '<div id="error">&nbsp;</div>';
}



function deleteAnnotation($id)  {
	deleteFromDetermination($id);
	deleteFromAnnotation($id);	
	deleteFromBaseObject($id);
	deleteFromKeywords($id);
}

function deleteFromAnnotation($id) {
	global $link;
	$sql = 'DELETE FROM Annotation WHERE id = \''.$id.'\'';
	//echo $sql;
	mysqli_query($link, $sql) or die (mysqli_error($link));

}

function deleteFromDetermination($id) {
	global $link;
	$sql = 'DELETE FROM DeterminationAnnotation WHERE annotationId = \''.$id.'\'';
	//echo $sql;
	mysqli_query($link, $sql) or die (mysqli_error($link));

}



?>
