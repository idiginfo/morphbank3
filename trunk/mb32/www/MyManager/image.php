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


imageObject::setupGetVars($config->displayThumbsPerPage);

include_once('parseGETvars.php');

//TODO modify so that the empty keyword query runs fast!
// may make the search method return a pair of values: number of matches, results of match
$regenerator = new baseObjectSearch();
// NOTE: search has no paramters??!!
$countAndSearch = $regenerator->countAndSearch($phrase, 'Image', $querySort, $limitBy, $limitOffset);
$total = $countAndSearch['numMatches'];
$res = $countAndSearch['search'];
if (MDB2::isError($res)){
	var_dump($res);
	die($res->getUserInfo());
}

$imageObject = new imageObject($link, $config, $total);

$resultArray = array();
while ($row= $res->fetchRow(MDB2_FETCHMODE_ASSOC)){
	$resultArray[] = $row;
}
$imageObject->displayResults($resultArray);

//var_dump($resultArray);
//mysqli_free_result($result);

//echo $resultArray[1]['id'];




?>