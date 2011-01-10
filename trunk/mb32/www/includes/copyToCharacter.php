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

// Copy objects from the MyManager to an existing Character
include_once('collectionFunctions.inc.php');
require_once('Phylogenetics/Classes/BaseObject.php');
require_once('Phylogenetics/Classes/Collection.php');
require_once('Phylogenetics/Classes/CollectionObject.php');
require_once('Phylogenetics/Classes/CharacterState.php');
require_once('Phylogenetics/Classes/PhyloCharacter.php');


if ($objInfo->getUserId() == NULL)
	header('Location:'.$config->domain.'Submit/');
elseif ($objInfo->getUserGroupId() == NULL)
	header('Location:'.$config->domain.'Submit/groups.php');
else {
	$link = Adminlogin();
	$imageIdArray = getIdArrayFromPost();
	
	$characterId = $_GET['characterId'];
	
	//echo $characterId.'<br>';
	//var_dump($imageIdArray);
	//exit(0);
	$phyloCharacter = new PhyloCharacter($link, $characterId );	
	$phyloCharacter->addImageToCharacter($imageIdArray);
	
	
	header('Location:'.$config->domain.'myCollection/index.php?collectionId='.$characterId);
}
?>
