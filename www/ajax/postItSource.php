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

include_once( 'gettables.inc.php');
include_once( 'collectionFunctions.inc.php');

$link = AdminLogin();

$id = $_GET['id'];
$objectTypeId = $_GET['objectTypeId'];

if ($objectTypeId == "Image"){
	$postIt=getallimagedata($id);
} elseif ($objectTypeId == "Specimen"){
	$postIt=getSpecimenPostit($id);
} elseif ($objectTypeId == "View") {
	$postIt=getViewPostit($id);
} elseif ($objectTypeId == "Locality"){
	$postIt=getLocalityPostit($id);
} elseif ($objectTypeId == "Collection") {
	$postIt=getCollectionPostit($id);
} elseif ($objectTypeId == "MbCharacter") {
	$postIt=getCharacterPostit($id);
} elseif ($objectTypeId == "Annotation") {
	$postIt=getAnnotationPostit($id);
} elseif ($objectTypeId == "Publication") {
	$postIt=getPublicationPostit($id);
} elseif ($objectTypeId == "TaxonConcept") {
	$postIt=getTaxonConceptPostit($id);
} else {
	$postIt = $objectTypeId;
}

echo $postIt;

