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


if ($objInfo->getUserId() == NULL)
	header('Location:'.$config->domain.'Submit/');
elseif ($objInfo->getUserGroupId() == NULL)
	header('Location:'.$config->domain.'Submit/groups.php');
else {
	
	$collectionIdArray = getIdArrayFromPost();
	//var_dump($collectionIdArray);
	//========================
	
	$newCollection = createCollection($collectionIdArray, $objInfo->getUserId(), $objInfo->getUserGroupId());
	if ($newCollection) {
	
		echo '
		<html>
		<body onLoad="document.autoForm.submit();">
		<form name="autoForm" action="'.$config->domain.'Phylogenetics/Character/addCharacter.php" method="post">';
		
		foreach ($_POST as $k => $v) {
			//$_POST['object'.$counter] = $v;
			if (strpos ($k, "object") !== FALSE ) {
				echo '<input name="'.$k.'" value="'.$v.'" type="hidden" />';
			}
			
		}
		echo '<input name="id" value="'.$newCollection.'" type="hidden" />';
		echo'</form>
		
		</body>
		</html>';
		
		//header('Location:'.$config->domain.'myCollection/manageCollections.php?newCollectionId='.$newCollection);
	}
	
}


?>
