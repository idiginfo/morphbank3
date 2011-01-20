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

//This program will update the RecentlyModified table to reflect the modification
// status of objects with respect to creating the keywords entries.
// TODO extend the model to include modifications that affect the XML metadata document
// GR 3/27/06

//get all recently modified
$numDays = 5;
echo("Finding objects changed in last $numDays days \n");
$SQL_drop = "delete from RecentlyModifiedTemp";
$numDrop = $db->exec($SQL_drop);
		echo ("Number dropped: $numDrop \n");
$SQL_initInsert = "INSERT INTO RecentlyModifiedTemp(id, dateLastModified, objectTypeId, dependentTypeId) "
	." select id, dateLastModified, objectTypeId, 'self' from"
	." BaseObject where dateLastModified > adddate(now(), -$numDays)";
		//echo("initInsert: $SQL_initInsert \n");
$initResult = $db->exec($SQL_initInsert);
		echo ("Modified objects: $initResult \n");
$userResult = $db->exec(getBaseInsert("userId"));
		echo ("Objects with modified user: $userResult \n");
$groupResult = $db->exec(getBaseInsert("groupId"));
		echo ("Objects with modified group: $groupResult \n");
$specimenResult = $db->exec(getInsert("Specimen", "localityId"));
		echo ("Specimens with modified locality: $userResult \n");
$imageSpecimenResult = $db->exec(getInsert("Image","specimenId"));
		echo ("Images with modified specimen: $imageSpecimenResult \n");
$imageViewResult = $db->exec(getInsert("Image","viewId"));
		echo ("Images with modified view: $imageViewResult \n");
$annotationDetResult = $db->exec(getPairInsert("Annotation","DeterminationAnnotation","annotationId"));
		echo ("Annotations with modified determination annotation: $annotationDetResult \n");
//TODO taxon concept has info from all determined specimens: join on tsn 
//$taxonConceptSpecimenResult = $db->exec(getInsert("TaxonConcept", "specimenId"));
//		echo ("Specimens with modified locality: $userResult \n");
	
$deleteRecent = "DELETE FROM RecentlyModified";
$numDeleted = $db->exec($deleteRecent);
echo("Number deleted from RecentlyModified: $numDeleted \n");
$finalInsert = "INSERT INTO RecentlyModified(id, dateLastModified) "
."SELECT id, min(dateLastModified) FROM RecentlyModifiedTemp GROUP BY id ";
$numModified = $db->exec($finalInsert);
echo("Number modified objects: $numModified \n");

echo ("Done with modifications!\n");
	
function getInsert($table, $field){
	// join for adding derived dependencies, like image -> specimen
	// with a many-one relationship (specimen has many images)
	$sql = "INSERT INTO RecentlyModifiedTemp(id, dateLastModified, objectTypeId, dependentTypeId)"
		." select B.id, T.dateLastModified, B.objectTypeId, T.objectTypeId from BaseObject B join"
		." $table S on B.id = S.id join RecentlyModifiedTemp T on S.$field = T.id";
	//echo("insert: $sql \n");
	return $sql;
}
function getPairInsert($leftTable, $rightTable, $field){
	// join for adding derived dependencies, like annotation -> determinationAnnotation
	// with a one-many relationship
	$sql = "INSERT INTO RecentlyModifiedTemp(id, dateLastModified, objectTypeId, dependentTypeId)"
		." select B.id, T.dateLastModified, B.objectTypeId, T.objectTypeId from BaseObject B join"
		." $leftTable L on B.id = L.id join $rightTable R on L.id = R.$field "
		." join RecentlyModifiedTemp T on R.$field = T.id";
	//echo("insert: $sql \n");
	return $sql;
}

function getBaseInsert($field){
	// join for adding base dependencies, such as group and user
	$sql = "INSERT INTO RecentlyModifiedTemp(id, dateLastModified, objectTypeId, dependentTypeId)"
		." select B.id, T.dateLastModified, B.objectTypeId, T.objectTypeId from BaseObject B join"
		." RecentlyModifiedTemp T on B.$field = T.id";
	//echo("insert: $sql \n");
	return $sql;
}

?>
