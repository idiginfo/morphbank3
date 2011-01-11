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
