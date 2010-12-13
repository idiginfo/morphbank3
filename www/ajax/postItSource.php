<?php

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

