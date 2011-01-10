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
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

include_once ('collectionFunctions.inc.php');
include_once('Phylogenetics/phylo.inc.php');

//include_once ('http_build_query.php');
//echo http_build_query($_POST);

$link = Adminlogin();

$userId = $objInfo->getUserId();
$groupId = $objInfo->getUserGroupId();

if (!isset($_GET['id'])) // calling this module with no id
	header('Location:'.$config->domain);
else {	
	
	// source means the collection to copy.  Tests if the source is a character or collection.
	if ($_GET['source'] == "MbCharacter") {
		if (isset($_GET['character'])) {
			$phylo = new PhyloCharacter($link, $_GET['id'] );
			$phylo->setUserId($userId);
			$phylo->setGroupId($groupId);
			
			
			
			for ($i = 0; $i < count($phylo->collectionObjects); $i++) {
				//var_dump($phylo->collectionObjects);
				$stateId = $phylo->collectionObjects[$i]->getObjectId();
				$state = new CharacterState($link, $stateId );
				$state->setUserId($userId);
				$state->setGroupId($groupId);
				$id = $state->saveCharacterState();
				$phylo->collectionObjects[$i]->setObjectId($id);		
			}
			
			$phylo->savePhyloCharacter();
			
			$sql = 'SELECT max(id) as id FROM BaseObject WHERE objectTypeId="MbCharacter" ';
			$result = mysqli_query($link, $sql);
			if ($result) {
				$array = mysqli_fetch_array($result);
				//CharacterKeywords($link, $_GET['id'] , "Insert");
				CharacterKeywords($link, $array['id'] , "Insert");
			}
			
			
			header('Location:'.$config->domain.'MyManager/?tab=collectionTab');
		} else {
			$phylo = new PhyloCharacter($link, $_GET['id'] );
			$array = $phylo->getIdsFromCharacter();
			
			
			$newArray = array();
			$newIndex = 0;
			for ($i=0; $i < count($array); $i++) {
				if ($array[$i]['objectTypeId'] != "CharacterState") {
					$newArray[$newIndex] = $array[$i];
					$newArray[$newIndex]['id'] = $array[$i]['objectId'];
					$newIndex++;				
				}
					
			}
			
			createCollection($newArray, $userId, $groupId, "Collection of Character objects");
			header('Location:'.$config->domain.'MyManager/?tab=collectionTab');

		}
	
	} else {
	
		if (isset($_GET['character'])) {
			$collectionId = $_GET['id'];
			$newCollection = copyCollection( $collectionId, $objInfo->getUserId(), $objInfo->getUserGroupId());
			
			
			if ($newCollection) {
				$sql = 'SELECT objectId FROM CollectionObjects where collectionId ='.$newCollection;
				
				$results = mysqli_query($link, $sql);
				
				if ($results) {
					$numRows = mysqli_num_rows($results);
					
					for ($i=0; $i < $numRows; $i++) {
						$array[$i] = mysqli_fetch_array($results);
					}
					
					echo '
					<html>
					<body onLoad="document.autoForm.submit();">
					<form name="autoForm" action="'.$config->domain.'Phylogenetics/Character/addCharacter.php" method="post">';
					//var_dump($array);
					
					foreach ($array as $k => $v) {						
						echo '<input name="object'.($k + 1).'" value="'.$v['objectId'].'" type="hidden" />';
					}
					
					echo '<input name="id" value="'.$newCollection.'" type="hidden" />';
					echo'</form>
					
					</body>
					</html>';
					
				}
			}
				//header('Location:'.$config->domain.'MyManager/index.php?tab=collectionTab');
			/*
			if ($newCollection)
				header('Location:'.$config->domain.'myCollection/manageCollections.php?newCollectionId='.$newCollection);
			*/
			
		} else {
			$collectionId = $_GET['id'];
			if ($objInfo->getUserId() == NULL)
				header('Location:'.$config->domain.'Submit/');
			elseif ($objInfo->getUserGroupId() == NULL)
				header('Location:'.$config->domain.'Submit/groups.php');
			else {
				
				$newCollection = copyCollection( $collectionId, $objInfo->getUserId(), $objInfo->getUserGroupId());
				if ($newCollection)
					header('Location:'.$config->domain.'MyManager/index.php?tab=collectionTab');
			}
		}
	}
}
?>
