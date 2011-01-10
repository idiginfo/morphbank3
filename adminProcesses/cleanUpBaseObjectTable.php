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
