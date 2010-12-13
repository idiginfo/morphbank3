<?php
include_once ('collectionFunctions.inc.php');
//include_once ('http_build_query.php');
//echo http_build_query($_POST);

//exit;
if ($objInfo->getUserId() == NULL)
	header('Location:'.$config->domain.'Submit/');
elseif ($objInfo->getUserGroupId() == NULL)
	header('Location:'.$config->domain.'Submit/groups.php');
else {
	
	$collectionIdArray = getIdArrayFromPost();
	//var_dump($collectionIdArray);
	//========================
	
	$title = (isset($_GET['title'])) ? $_GET['title'] : "New Collection Name";
	//Adminlogin();
	$newCollection = createCollection($collectionIdArray, $objInfo->getUserId(), $objInfo->getUserGroupId(), $title);
	//exit(0);
	if ($newCollection) {
		//insertObjects( NULL, $newCollection, $numObjs);
		//header('Location:'.$config->domain.'myCollection/manageCollections.php?newCollectionId='.$newCollection);
		//header('Location:'.$config->domain.'MyManager/index.php?tab=collectionContent&newCollectionId='.$newCollection);
		
		echo '	<html>
					<head>					
					</head>
					<body onload="document.collectionForm.submit();">
				
						<form name="collectionForm" action="'.$config->domain.'MyManager/index.php" method="get">
							<input type="hidden" name="tab" value="collectionTab" />
							<input type="hidden" name="newCollectionId" value="'.$newCollection.'" />
						</form>
					</body>
			  	</html>
		';
		
	}
	
}
?>
