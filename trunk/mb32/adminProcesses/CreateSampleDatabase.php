#-------------------------------------------------------------------------------
# Copyright (c) 2010 Greg Riccardi, Fredrik Ronquist.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Public License v2.0
# which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# 
# Contributors:
#   Fredrik Ronquist - conceptual modeling and interaction design
#   Austin Mast - conceptual modeling and interaction design
#   Greg Riccardi - initial API and implementation
#   Wilfredo Blanco - initial API and implementation
#   Robert Bruhn - initial API and implementation
#   Christopher Cprek - initial API and implementation
#   David Gaitros - initial API and implementation
#   Neelima Jammigumpula - initial API and implementation
#   Karolina Maneva-Jakimoska - initial API and implementation
#   Katja Seltmann - initial API and implementation
#   Stephen Winner - initial API and implementation
#-------------------------------------------------------------------------------
<?php
/**
 * 
 */

require_once ('../configuration/app.server.php');

// PARAMS:
$SampleDB = "MB30Sample";
$ProdDB = "MB30";
$numImagesPerView = 15;
$minImagesPerView = 2;
$minImagesPerSpecimen = "?"; // not implemented

$db = connect();
//start transaction
$res = $db->beginTransaction();

// create tables
// fill auxilliary tables

//Turn off foreign keys
$keyOffSql = "SET foreign_key_checks = 0";
$res = execSql($keyOffSql);
truncateTable ("NewIds");
truncateTable ("BaseObject");

// Get ids
addViews();
addImages();
addSpecimens();
addLocalities();
addAnnotations(); //  (public of objects)
addDeterminationAnnotations(); //  (public of objects)
addCollections();
addCollectionObjects();//  (public)
addExternalLinks();
addTaxonConcepts();
addUserProperties();
addPublications();
addKeywords();
addGeolocated();
addUsers();
addGroups();
addUserGroups();

$keyOnSql = "SET foreign_key_checks = 1";
$res = execSql($keyOnSql);
$db->commit();

function addViews() {
	global $SampleDB, $ProdDB, $minImagesPerView;
	truncateTable("View");
	$viewIdSql = "insert into $SampleDB.NewIds select v.id "
	."from $ProdDB.View v join $ProdDB.Image i on v.id = i.viewid "
	."group by v.id having count(*)>$minImagesPerView";
	addIdObjects('View', $viewIdSql);
}

function addImages() {
	global $SampleDB,$ProdDB,$numImagesPerView;
	truncateTable("Image");
	$db = connect();
	// get the view identifiers
	$vIdSql = "select id from $SampleDB.BaseObject where objecttypeid='View'";
	$result = $db->query($vIdSql);
	if(PEAR::isError($result)){
		echo($result->getUserInfo()." $vIdSql\n");
	}
	while($row = $result->fetchRow()){
		// add [some] public images of the view
		$id = $row[0];
		$iSql = "insert into $SampleDB.NewIds select id from $ProdDB.Image i "
		."where dateToPublish<now() and viewId = $id limit $numImagesPerView";
		$numInserted = execSql($iSql);
		//echo "Added $numInserted images for view $id\n";
	}
	// fill BaseObject and Image tables
	addObjects('Image');
}

function addSpecimens(){//Specimen ids
	global $SampleDB, $ProdDB;
	truncateTable("Specimen");
	$specIdSql = "insert into $SampleDB.NewIds select distinct s.id from $ProdDB.Specimen s join $SampleDB.Image i on s.id = i.specimenId";
	addIdObjects('Specimen', $specIdSql);
}

function addLocalities() {
	global $SampleDB, $ProdDB;
	truncateTable("Locality");
	$locIdSql = "insert into $SampleDB.NewIds select distinct l.id from $ProdDB.Locality l join $SampleDB.Specimen s on l.id = s.localityId";
	addIdObjects('Locality', $locIdSql);
}

function addAnnotations(){
	// public annotions of objects already in BaseObject
	global $SampleDB, $ProdDB;
	truncateTable("Annotation");
	$annotIdSql = "insert into $SampleDB.NewIds select distinct a.id "
	."from $ProdDB.Annotation a join $SampleDB.BaseObject b on b.id = a.objectId "
	." join BaseObject b1 on a.id = b1.id "
	."where b1.datetopublish<now()";
	addIdObjects('Annotation', $annotIdSql);

} //  (public of objects)

function addDeterminationAnnotations(){
	//  determination annotations of annotations already in BaseObject
	truncateTable("DeterminationAnnotation");
	global $SampleDB, $ProdDB;
	$detAnnotIdSql = "insert into $SampleDB.DeterminationAnnotation "
	."select a.* from $ProdDB.DeterminationAnnotation a "
	."join $SampleDB.BaseObject b on b.id = a.annotationId ";
	$numInserted = execSql($detAnnotIdSql);
	echo "Number of determination objects $numInserted\n";
} //  (public of objects)

function addCollections(){
	global $SampleDB, $ProdDB;
	// add public collection with at least one reference to an object in the sample
	truncateTable("Collection");
	$collSql = "insert into $SampleDB.NewIds select distinct c.id "
	."from $ProdDB.Collection c join $ProdDB.BaseObject b on c.id = b.id "
	."join $ProdDB.CollectionObjects co on c.id = co.collectionId "
	."join $SampleDB.BaseObject sb on co.objectId = sb.id "
	."where b.datetopublish < now() ";
	addIdObjects('Collection', $collSql);
}

function addCollectionObjects(){
	global $SampleDB, $ProdDB;
	// every CO that connects objects in the sample BaseObject
	truncateTable("CollectionObjects");
	$collObjSql = "insert into $SampleDB.CollectionObjects "
	."select co.* from $ProdDB.CollectionObjects co "
	."join $SampleDB.BaseObject cb on co.collectionId = cb.id "
	."join $SampleDB.BaseObject sb on co.objectId = sb.id";
	$numInserted = execSql($collObjSql);
	echo "Number of collection objects $numInserted\n";
}//  (public)

function addExternalLinks(){
	global $SampleDB, $ProdDB;
	truncateTable("ExternalLinkObject");
	// all referenced
	$sql = "insert into $SampleDB.ExternalLinkObject select e.* from $ProdDB.ExternalLinkObject e "
	." join $SampleDB.BaseObject b on e.mbId = b.id";
	$numInserted = execSql($sql);
	echo "Number of external links $numInserted\n";
}

function addTaxonConcepts(){
	global $SampleDB, $ProdDB;
	truncateTable("TaxonConcept");
	$idSql = "insert into $SampleDB.NewIds select id from $ProdDB.TaxonConcept";
	addIdObjects('TaxonConcept', $idSql);
}

function addUserProperties(){
	global $SampleDB, $ProdDB;
	truncateTable("UserProperty");
	// all referenced
	$sql = "insert into $SampleDB.UserProperty select u.* from $ProdDB.UserProperty u "
	." join $SampleDB.BaseObject b on u.objectId = b.id";
	$numInserted = execSql($sql);
	echo "Number of user properties $numInserted\n";
}

function addPublications(){
	global $SampleDB, $ProdDB;
	truncateTable("Publication");
	// all
	$idSql = "insert into $SampleDB.NewIds select id from $ProdDB.Publication";
	addIdObjects('Publication', $idSql);
}

function addKeywords(){
	global $SampleDB, $ProdDB;
	truncateTable("Keywords");
	// all referenced
	$sql = "insert into $SampleDB.Keywords select k.* from $ProdDB.Keywords k "
	." join $SampleDB.BaseObject b on k.id = b.id";
	$numInserted = execSql($sql);
	echo "Number of keywords $numInserted\n";
	$cleanSql = "repair table Keywords quick";
	execSql($cleanSql);
	echo "Keyword table indexed";
}

function addGeolocated(){
	global $SampleDB, $ProdDB;
	truncateTable("Geolocated");
	// all referenced
	$sql = "insert into $SampleDB.Geolocated select g.* from $ProdDB.Geolocated g "
	." join $SampleDB.BaseObject b on g.id = b.id";
	$numInserted = execSql($sql);
	echo "Number of geolocated $numInserted\n";
}

function addUsers(){
	global $SampleDB, $ProdDB;
	truncateTable("User");
	// every owner of an object in the database
	$userSql = "insert into $SampleDB.NewIds "
	."select distinct u.id from $ProdDB.User u "
	//." left join $SampleDB.BaseObject sb "
	//."on sb.userid = u.id where not isnull(sb.id) or u.uin='griccardi'"
	;
	addIdObjects('User', $userSql);
}

function addGroups() {
	global $SampleDB, $ProdDB;
	truncateTable("Groups");
	$groupSql = "insert into $SampleDB.NewIds "
	."select id from $ProdDB.Groups ";
	//."select distinct groupId from $SampleDB.BaseObject ";
	addIdObjects('Groups', $groupSql);
}

function addUserGroups(){
	global $SampleDB, $ProdDB;
	truncateTable("UserGroup");
	$userGroupSql = "insert into $SampleDB.UserGroup "
	."select ug.* from $ProdDB.UserGroup ug "
	;//."join $SampleDB.BaseObject bu on ug.user = bu.id "
	//."join $SampleDB.BaseObject bg on ug.groups = bg.id ";
	$numInserted = execSql($userGroupSql);
	echo "Number of UserGroups $numInserted\n";
}

// utilities

function addIdObjects($objectType, $insertIdSql){
	$numInserted =  execSql($insertIdSql);
	echo "Number of $objectType ids $numInserted";
	addObjects($objectType);
}

function addObjects($objectType){
	$numBaseObjects =  addBaseObjects();
	echo " Number of base objects $numBaseObjects";
	$numSubTypes = addSubTypeObjects($objectType);
	echo " $objectType objects $numSubTypes\n";
	$numDeleted = truncateTable("NewIds");
	echo "Deleted $numDeleted ids\n";
}

function addSubTypeObjects($subTypeTable){
	global $SampleDB,$ProdDB;
	$insertSql = "insert into $SampleDB.$subTypeTable "
	."select s.* from $ProdDB.$subTypeTable s join $SampleDB.BaseObject b on s.id = b.id";
	$numInserted = execSql($insertSql);
	return $numInserted;
}

function addBaseObjects(){
	global $SampleDB,$ProdDB;
	$insertSql = "insert into $SampleDB.BaseObject select b.* from $ProdDB.BaseObject b "
	."join $SampleDB.NewIds i on b.id=i.id";
	$db = connect();
	$numInserted = execSql($insertSql);
	return $numInserted;
}

function execSql($insertSql){
	global $SampleDB,$ProdDB;
	$db = connect();
	$numInserted = $db->exec($insertSql);
	if(PEAR::isError($numInserted)){
		echo(" error for $insertSql\n".$numInserted->getUserInfo()." \n");
		die();
	}
	return $numInserted;
}

function truncateTable ($table){
	global $SampleDB,$ProdDB;

	$sql = "truncate $SampleDB.$table";
	$numDeleted = execSql($sql);
	return $numDeleted;
}
?>
