<?php

// Script will loop through the objectArray and get all the id's from the BaseObject table that do not have
// a corresponding record in the appropriate table (i.e. An Image record in the B.O. with no record in the Image table
//modified to delete from both Keywords table and BaseObject

require_once('../configuration/app.server.php');

$link = Adminlogin();

$objectArray = array('Annotation', 'Collection', 'Groups', 'Image', 'Locality', 'News', 'Publication', 'Specimen', 'User', 'View');

// remove base objects that have been relegated to values
if (false){
	$deleteArray = array('DevelopmentalStage', 'Form', 'ImagingPreparationTechnique', 'ImagingTechnique', 'Sex', 'SpecimenPart', 'ViewAngle');
	foreach($deleteArray as $array) {
		$sql = 'DELETE FROM BaseObject WHERE objectTypeId = "'.$array.'"';
		mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	}
}

// Modify previous values for objectypeid
if (false){
	$sql = 'UPDATE BaseObject SET objectTypeId = "Groups" WHERE objectTypeId="Group" ';
	mysqli_query($link, $sql);

	$sql = 'UPDATE BaseObject SET objectTypeId = "Collection" WHERE objectTypeId="myCollection" ';
	mysqli_query($link, $sql);

	$sql = 'UPDATE BaseObject SET objectTypeId = "Locality" WHERE objectTypeId="Location" ';
	mysqli_query($link, $sql);

	$sql = 'UPDATE BaseObject SET objectTypeId = "TaxonConcept" WHERE objectTypeId="Taxon Concept" ';
	mysqli_query($link, $sql);

	$sql = 'UPDATE BaseObject SET objectTypeId = "MbCharacter" WHERE objectTypeId="PhyloCharacter" ';
	mysqli_query($link, $sql);

	$sql = 'UPDATE BaseObject SET objectTypeId = "CharacterState" WHERE objectTypeId="PhyloCharacterState" ';
	mysqli_query($link, $sql);
}

// delete objects from the baseobject table that have no corresponding object in the subclass table
// (ex.  a base object that looks like a specimen, but no record in specimen table).
foreach($objectArray as $array) {
	$sql = 'SELECT BaseObject.id AS BO_id FROM BaseObject LEFT JOIN '.$array.' ON BaseObject.id = '.$array.'.id WHERE '.$array.'.id IS NULL AND BaseObject.objectTypeId = "'.$array.'" ';
	$results = mysqli_query($link, $sql);
	$count = 0;
	if ($results) {
		while ($row = mysqli_fetch_array($results)) {
			$id = $row['BO_id'];
			echo "Deleting baseobject type $array id: $id\n";
			$deleteSql1 = "DELETE from Keywords  WHERE id=$id";
			$deleteSql2 = "DELETE from BaseObject  WHERE id=$id";

			//$r1 = mysqli_query($link, $deleteSql1);
			//$r2 = mysqli_query($link, $deleteSql2);
		}
	}
}
?>
