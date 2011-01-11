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

//
/**
 *	Functions that are used for manipulating, and creating collections
 *
 *
 *	If the query has no results and error message is outputted.
 *	File: collectionFunctions.inc.php
 *   @package Morphbank2
 *   @subpackage includes
 *
 */

/**
 *	Include config file for needed variables
 */
include_once('Phylogenetics/phylo.inc.php');
include_once('imageFunctions.php');
include_once('updateObjectKeywords.php');

$link = Adminlogin();


function createCollection($idArray, $userId, $groupId, $title="New Collection Name", $otu = FALSE) {
	$db = connect();
	
	$numObjects = count($idArray);

	$publishDate = date('Y-m-d', strtotime('+6 months'));
	
	// Insert Object and Locality returning id
	$params = array($db->quote($title), $userId, $groupId, $userId, $db->quote($publishDate), $db->quote("Collection Description"));
	$result = $db->executeStoredProc('CollectionInsert', $params);
	if(isMdb2Error($result, 'Create Collection procedure', false)) {
		return false;
	}
	$id = $result->fetchOne();
	clear_multi_query($result);
	
	if ($id) {
		if (!is_null($idArray)) {
			insertObjects($idArray, NULL, $id, $numObjects);
		}
		if (!$otu) {
			updateKeywordsTable($id, 'insert');
		}
		createCollectionThumb($id);
	}
	return $id;
}

function createCollectionThumb($id, $objectId = FALSE) {
	global $link;

	// if there is no object Id given (only a collectionId), then pick the first object in the collection for the thumb
	if (!$objectId) {
		$updateSql = 'SELECT CollectionObjects.objectId FROM CollectionObjects WHERE CollectionObjects.collectionId='.$id.' ORDER BY objectOrder LIMIT 1 ';
		//echo $updateSql."\n";
		$result = mysqli_query($link, $updateSql);

		if ($result) {
			$idArray = mysqli_fetch_array($result);
			$thumbId = $idArray['objectId'];
		}
	} else { // other wise, use the objectId given for the thumbURL in B.O.
		$thumbId = $objectId;
	}

	// find the thumbURL for the object (could be a specimen or view etc) defined in imageFunctions.
	$thumbURL = getObjectImageId($thumbId);

	// Last... set the thumbURL for the collection, to the thumbURL of the object chosen to represent the collection
	if (setThumb($id, $thumbURL)) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function createCharacterThumb($id) {
	global $link;

	$imageId = PhyloCharacter::getThumb($id);
	if (!empty($imageId)) {
		$updateSql = 'UPDATE BaseObject SET thumbURL = '.$imageId.' WHERE id = '.$id;
		//echo $updateSql."\n";
		mysqli_query($link, $updateSql);
	}
}

function updateCollectionKeywords($id) {
	//TODO get rid of this keywords mess
	updateKeywordsTable($id);
}

// Make a copy of one collection. Create a new collection for a especific user
function copyCollection ($collectionId, $userId, $groupId) {
	global $link;
	$newCollectionId = NULL;
	$newCollectionId = createCollection(NULL, $userId, $groupId);

	$insertSql = "INSERT INTO CollectionObjects (collectionId, objectId, objectOrder, objectTypeId, objectRole, objectTitle, startSubCollection, parentId) "
	."SELECT $newCollectionId, objectId, objectOrder, objectTypeId, objectRole, objectTitle, startSubCollection, parentId FROM CollectionObjects "
	."WHERE collectionId = '$collectionId' ORDER BY objectOrder";
	$result = mysqli_query($link, $insertSql) or die(mysqli_error($link).$sql);

	updateKeywordsTable($newCollectionId, 'update');
	createCollectionThumb($newCollectionId);

	if ($result) {
		return $newCollectionId;
	} else {
		return NULL;
	}
}

// needs to be modified to accept an array of object id's instead of using the post variables
function insertObjects($idArray, $collectionId, $targetCollectionId, $objectCount) {
	global $link;

	//var_dump($idArray);
	//echo $collectionId.' '.$targetCollectionId.' '.$objectCount;

	$targetCollectionCount = getCollectionCount($targetCollectionId);
	$objectOrder = $targetCollectionCount;
	$targetCollectionArray = getArrayOfObjects($targetCollectionId);

	//var_dump($idArray);
	//echo '<br />'.$objectCount;
	// Loops through the list of objects that could have been submitted, and checks to see if it is there,
	// If the object was present and the target collection amount is not 0, it will loop through the target collection to
	// check if that object is in that collection already.  If it is not, it will insert it.
	// If the target collection is empty ($targetCollectionCount == 0) then no need to check.  Just insert.
	for ($i = 0; $i < $objectCount; $i++) {
		if ($idArray[$i]['id']) {
			$counter = 0;
			if ($targetCollectionCount) {
				$isIn = False;
				for ($j = 0; $j < $targetCollectionCount; $j++) {
					//echo $counter.'&nbsp;&nbsp;'.$targetCollectionCount.'&nbsp;&nbsp;'.$j.'&nbsp;&nbsp;'.$_POST['object'.$i.''].'&nbsp;&nbsp;'.$targetCollectionArray[$j]['objectId'].'<br />';
					if ($idArray[$i]['id'] == $targetCollectionArray[$j]['objectId']) {
						$isIn = True;
						break;
					}
				}
				if (!$isIn) {
					$title =  str_replace("'", "''",getObjectTitle($idArray[$i], $collectionId));
						
					$sql = 'INSERT INTO CollectionObjects '
					.'(collectionId, '
					.' objectId, '
					.' objectOrder, '
					.' objectTypeId, '
					.' objectTitle, '
					.' startSubCollection) '
					.'VALUES '
					.'(\''.$targetCollectionId.'\', '
					.' \''.$idArray[$i]['id'].'\', '
					.' '.(++$objectOrder).', '
					.' \''.$idArray[$i]['objectTypeId'].'\', '
					.' \''.$title.'\', '
					.' 0) ';
					//echo $counter.'&nbsp;&nbsp;'.$targetCollectionCount.'<br />'.$sql.'<br />';
					mysqli_query($link, $sql) or die(mysqli_error($link));
					//echo mysqli_error($link);
				}
			} else {
				$title = str_replace("'", "''",getObjectTitle($idArray[$i], $collectionId));
					
				$sql = 'INSERT INTO CollectionObjects '
				.'(collectionId, '
				.' objectId, '
				.' objectOrder, '
				.' objectTypeId, '
				.' objectTitle, '
				.' startSubCollection) '
				.'VALUES '
				.'(\''.$targetCollectionId.'\', '
				.' \''.$idArray[$i]['id'].'\', '
				.' '.(++$objectOrder).', '
				.' \''.$idArray[$i]['objectTypeId'].'\', '
				.' \''.$title.'\', '
				.' 0) ';
				//echo $counter.'&nbsp;&nbsp;'.$targetCollectionCount.'<br />'.$sql.'<br />';
				mysqli_query($link, $sql) or die(mysqli_error($link));
				//echo mysqli_error($link);
			}
		} // if $_POST
	} //for $i
} // end function

function getUserCollectionArray($userId, $groupId) {
	global $link;

	// Get User's collections for the group they are logged into
	//$sql ='SELECT BaseObject.*, BaseObject.id AS id, DATE_FORMAT(BaseObject.dateToPublish, \'%Y-%m-%d\') AS dateToPublish, DATE_FORMAT(NOW(), \'%Y-%m-%d\') AS now '
	//	 .'FROM  BaseObject  WHERE BaseObject.userId = \''.$userId.'\' AND BaseObject.groupId = \''.$groupId.'\' AND (BaseObject.objectTypeId = \'Collection\' OR BaseObject.objectTypeId = \'myCollection\') ORDER BY id DESC ';

	// Get all User's collections, regardless of group
	$sql ='SELECT BaseObject.*, BaseObject.id AS id, DATE_FORMAT(BaseObject.dateToPublish, \'%Y-%m-%d\') AS dateToPublish, DATE_FORMAT(NOW(), \'%Y-%m-%d\') AS now '
	.'FROM  BaseObject  WHERE BaseObject.userId = '.$userId.' AND (BaseObject.objectTypeId = \'Collection\' OR BaseObject.objectTypeId = \'myCollection\') ORDER BY id DESC ';

	$results = mysqli_query($link, $sql) or die(mysqli_error($link));
	if ($results) {
		$numRows = mysqli_num_rows($results);
		for ($i = 0; $i < $numRows; $i++)
		$collectionArray[$i] = mysqli_fetch_array($results);
		return $collectionArray;
	} else {
		return FALSE;
	}
}

function getUserCharacterArray($userId, $groupId) {
	global $link;

	// Get User's characters for the group they are logged into
	//$sql ='SELECT MbCharacter.*, BaseObject.*, MbCharacter.id AS id, DATE_FORMAT(BaseObject.dateToPublish, \'%Y-%m-%d\') AS dateToPublish, DATE_FORMAT(NOW(), \'%Y-%m-%d\') AS now '
	//	 .'FROM MbCharacter INNER JOIN BaseObject ON MbCharacter.id = BaseObject.id WHERE BaseObject.userId = \''.$userId.'\' AND BaseObject.groupId = \''.$groupId.'\' ORDER BY MbCharacter.id DESC ';

	// Get all User's characters, regardless of group
	$sql ='SELECT MbCharacter.*, BaseObject.*, MbCharacter.id AS id, DATE_FORMAT(BaseObject.dateToPublish, \'%Y-%m-%d\') AS dateToPublish, DATE_FORMAT(NOW(), \'%Y-%m-%d\') AS now '
	.'FROM MbCharacter INNER JOIN BaseObject ON MbCharacter.id = BaseObject.id WHERE BaseObject.userId = '.$userId.' ORDER BY MbCharacter.id DESC ';

	$results = mysqli_query($link, $sql) or die(mysqli_error($link));
	if ($results) {
		$numRows = mysqli_num_rows($results);
		for ($i = 0; $i < $numRows; $i++)
		$collectionArray[$i] = mysqli_fetch_array($results);
		return $collectionArray;
	} else {
		return FALSE;
	}
}

function getUserOtuArray($userId, $groupId) {
	global $link;

	// Get User's OTU's for the group they are logged into
	//$sql ='SELECT BaseObject.*, BaseObject.id AS id, DATE_FORMAT(BaseObject.dateToPublish, \'%Y-%m-%d\') AS dateToPublish, DATE_FORMAT(NOW(), \'%Y-%m-%d\') AS now '
	//	 .'FROM  BaseObject  WHERE BaseObject.userId = \''.$userId.'\' AND BaseObject.groupId = \''.$groupId.'\' AND BaseObject.objectTypeId = \'Otu\' ORDER BY id DESC ';

	// Get all User's OTU's, regardless of group
	$sql ='SELECT BaseObject.*, BaseObject.id AS id, DATE_FORMAT(BaseObject.dateToPublish, \'%Y-%m-%d\') AS dateToPublish, DATE_FORMAT(NOW(), \'%Y-%m-%d\') AS now '
	.'FROM  BaseObject  WHERE BaseObject.userId = '.$userId.' AND BaseObject.objectTypeId = \'Otu\' ORDER BY id DESC ';

	$results = mysqli_query($link, $sql) or die(mysqli_error($link));
	if ($results) {
		$numRows = mysqli_num_rows($results);
		for ($i = 0; $i < $numRows; $i++)
		$otuArray[$i] = mysqli_fetch_array($results);
		return $otuArray;
	} else {
		return FALSE;
	}
}


function getArrayOfObjects ($collectionId) {
	global $link;
	//TODO only get accessible objects!
	$sql = 'SELECT * FROM CollectionObjects WHERE collectionId = '.$collectionId.' ORDER BY objectOrder ';
	$results = mysqli_query($link, $sql) or die(mysqli_error($link));
	if ($results) {
		$numRows = mysqli_num_rows($results);
		for ($i = 0; $i < $numRows; $i++)
		$collectionArray[$i] = mysqli_fetch_array($results);
		return $collectionArray;
	} else {
		return FALSE;
	}
}

function getCollectionCount($collectionId) {
	global $link;

	$sql = 'SELECT * FROM CollectionObjects WHERE collectionId = '.$collectionId.' ';
	$results = mysqli_query($link, $sql) or die(mysqli_error($link));
	if ($results) {
		return mysqli_num_rows($results);
	}  else  {
		return FALSE;
	}
}

function getObjectTitle($idArray, $collectionId) {
	global $link;

	if ($collectionId == NULL ) {
		if ($idArray['objectTypeId'] == "Image") {
			$sql ='SELECT Tree.scientificName AS objectTitle, Image.id FROM Image LEFT JOIN Specimen ON Image.specimenId = Specimen.id '
				.'LEFT JOIN Tree ON Specimen.tsnId = Tree.tsn WHERE Image.id =  '.$idArray['id'].' ';
		} else {
			return $idArray['objectTypeId'].' '.$idArray['id'];
		}
	} else {
		$sql = 'SELECT objectTitle FROM CollectionObjects WHERE collectionId = '.$collectionId.' AND objectId = '.$idArray['id'].' ';
	}

	$results = mysqli_query($link, $sql) or die(mysqli_error($link));

	if ($results) {
		$array =  mysqli_fetch_array($results);
		return $array['objectTitle'];
	} else {
		return FALSE;
	}
}

function getIdArrayFromPost() {
	global $link;

	$counter=1;
	foreach ($_POST as $k => $v) {
		//$_POST['object'.$counter] = $v;
		if (strpos ($k, "object") !== FALSE ) {
			$idArray[$counter-1] = $v;
			$counter++;
		}
	}

	#allows for the array to be empty and a character created without images
	if (!empty($idArray)){
		$count = 0;
		foreach ($idArray as $array) {
			$sql = 'SELECT objectTypeId FROM BaseObject WHERE id='.$array;
			//	echo $sql.'<br />';

			$result = mysqli_query($link, $sql);

			if ($result) {
				$typeArray = mysqli_fetch_array($result);
				//var_dump($typeArray);
				$collectionIdArray[$count]['id'] = $array;
				$collectionIdArray[$count]['objectTypeId'] = $typeArray['objectTypeId'];
			}
			$count++;
		}
		return $collectionIdArray;
	} else {
		//var_dump( $collectionIdArray);
		//exit(0);
		return $collectionIdArray;
	}
}

function getTsnArrayFromPost() {

	$counter=0;
	foreach ($_POST as $k => $v) {
		//$_POST['object'.$counter] = $v;
		if (strpos ($k, "object") !== FALSE ) {
			$array = explode("=", $v);
				
			$idArray[$counter]['id'] = $array[1];
			$idArray[$counter]['objectTypeId'] = $array[0];
			$counter++;
		}
	}

	return $idArray;
}


function deleteCollection($collectionId) {
	deleteFromMyCollectionObjects($collectionId);
	deleteFromMyCollection($collectionId);
	deleteFromBaseObject($collectionId);
	deleteFromKeywords($collectionId);
}

function deleteFromKeywords($id) {
	global $link;
	$sql = 'DELETE FROM Keywords WHERE id = '.$id;
	mysqli_query($link, $sql) or die (mysqli_error($link));

}

function deleteFromMyCollection($collectionId) {
	global $link;
	$sql = 'DELETE FROM Collection WHERE id = \''.$collectionId.'\'';
	mysqli_query($link, $sql) or die (mysqli_error($link));

}

function deleteFromMyCollectionObjects($collectionId) {
	global $link;
	$sql = 'DELETE FROM CollectionObjects WHERE collectionId = \''.$collectionId.'\'';
	mysqli_query($link, $sql) or die (mysqli_error($link));
}

function deleteFromBaseObject($id) {
	global $link;
	$sql = 'DELETE FROM BaseObject WHERE id = '.$id;
	mysqli_query($link, $sql) or die (mysqli_error($link));
}
?>
