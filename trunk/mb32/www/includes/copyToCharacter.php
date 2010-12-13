<?php
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
