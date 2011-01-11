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

include_once('head.inc.php');


include_once('tsnFunctions.php');
include_once('objOptions.inc.php');
include_once('imageFunctions.php');
include_once('thumbs.inc.php');
require_once ('baseObjectSearchClass.inc.php');
require_once ('classes/imageObjectClass.php');
require_once ('classes/specimenObjectClass.php');
require_once ('classes/viewObjectClass.php');
require_once ('classes/localityObjectClass.php');
require_once ('classes/collectionObjectClass.php');
require_once ('classes/annotationObjectClass.php');

mbObjectClass::setupGetVars($config->displayThumbsPerPage);

include_once('parseGETvars.php');

$regenerator = new baseObjectSearch();
$countAndSearch = $regenerator->countAndSearch($phrase, 'all', $querySort, $limitBy, $limitOffset);
$total = $countAndSearch['numMatches'];
$res = $countAndSearch['search'];
if (MDB2::isError($res)){
	var_dump($res);
	die($res->getUserInfo());
}
	
$imageObject = new imageObject($link, $config, $total);
$specimenObject = new specimenObject($link, $config, $total);
$viewObject = new viewObject($link, $config, $total);
$localityObject = new localityObject($link, $config, $total);
$collectionObject = new collectionObjectMM($link, $config, $total);
$annotationObject = new annotationObject($link, $config, $total);

$numRows = $imageObject->getNumRows();

if ($numRows > 0) {
	for ($i = 0; $i < $numRows; $i++)
		$resultArray[$i] = $res->fetchRow(MDB2_FETCHMODE_ASSOC);
}
//var_dump($resultArray);
//mysqli_free_result($result);

//echo $resultArray[1]['id'];
/*
echo"<br />
<br />";
var_dump($resultArray);
*/
$size = count($resultArray);
$callingPage = $config->domain . 'MyManager/content.php?id=allContent&';

echo '<br />';
		printLinksNew($imageObject->total, $imageObject->numPerPage, $imageObject->offset, $callingPage);
		echo ' <strong>('.$imageObject->total.' Objects)</strong><br /><br />';
echo '
	<span  id="HttpClientStatus" style="visibility:hidden">&nbsp;</span>
	<div class="TabbedPanelsContent">			
		<div class="imagethumbspage">';
		
$count = 0;
for ($i=0; $i < $size; $i++) {
	
	switch ($resultArray[$i]['objecttypeid']) {
		case "Image":
			$imageObject->displayResultRow($resultArray[$i], $count);
			$count++;
			break;
		
		case "Specimen":
			$specimenObject->displayResultRow($resultArray[$i], $count);
			$count++;
			break;
		
		case "View":
			$viewObject->displayResultRow($resultArray[$i], $count);
			$count++;
			break;
		
		case "Locality":
			$localityObject->displayResultRow($resultArray[$i], $count);
			$count++;
			break;
			
		case "Collection":
			$collectionObject->displayResultRow($resultArray[$i], $count);
			$count++;
			break;
		
		// should be phased out, but keep just incase
		case "PhyloCharacter":
			$collectionObject->displayResultRow($resultArray[$i], $count);
			$count++;
			break;
		
		case "MbCharacter":
			$collectionObject->displayResultRow($resultArray[$i], $count);
			$count++;
			break;
		
		case "Annotation":
			$annotationObject->displayResultRow($resultArray[$i], $count);
			$count++;
			break;	
	
	}
			
}
echo '			
		</div>			
	 </div>';
echo '<br />';
		printLinksNew($imageObject->total, $imageObject->numPerPage, $imageObject->offset, $callingPage);
		echo ' <strong>('.$imageObject->total.' Objects)</strong><br /><br />';


?>
