<?php


include_once ('collectionFunctions.inc.php');


if ($objInfo->getUserId() == NULL)
	header('Location:'.$config->domain.'Submit/');
elseif ($objInfo->getUserGroupId() == NULL)
	header('Location:'.$config->domain.'Submit/groups.php');
else {
	
	//$taxonIdArray = getTsnArrayFromPost();
	//var_dump($taxonIdArray);
	//exit(0);
	//========================
	
	//$newCollection = createCollection($taxonIdArray, $objInfo->getUserId(), $objInfo->getUserGroupId());
	
	echo '
	<html>
	<body onLoad="document.autoForm.submit();">
	<form name="autoForm" action="'.$config->domain.'Phylogenetics/Otu/addOtu.php" method="post">';
	
	foreach ($_POST as $k => $v) {
		//$_POST['object'.$counter] = $v;
		if (strpos ($k, "object") !== FALSE ) {
			echo '<input name="'.$k.'" value="'.$v.'" type="hidden" />';
		}
		
	}
	echo'</form>
	
	</body>
	</html>';
	
	//header('Location:'.$config->domain.'myCollection/manageCollections.php?newCollectionId='.$newCollection);
	
	
}


?>
