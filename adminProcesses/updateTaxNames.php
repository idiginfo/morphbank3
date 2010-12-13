<?php
set_include_path(".;C:\Program Files (x86)\PHP\pear");
echo "include: ". get_include_path();

require_once('../configuration/app.server.php');

// Define functions that perform keyword update on a single object
$numUpdated = setMissingTaxNames();
echo "Number of rows updated: $numUpdated\n";

function setMissingTaxNames(){
	$whereClause = "where tsnId is not null and taxonomicNames is null";
	$numUpdated = setAllTaxNames($whereClause);
	return $numUpdated;
}

function setAllTaxNames($whereClause = ""){
	$sql = "select id, tsnId from Specimen " . $whereClause;
	$db = connect();
	$numUpdated = 0;
	$result = $db -> query($sql);
	while (	$row = $result -> fetchRow()){
		list ($id, $tsn) = $row;
		$taxNames = getTaxNames($tsn);
		setTaxNames($id, $taxNames);
		$numUpdated ++;
	}
	return $numUpdated;
}

function getTaxNames ($tsnId){
	$branchNames = getTaxonBranchNames($tsnId);

	$taxNames = "";
	foreach ($branchNames as $name){
		$taxNames .= ' ' . $name;
	}
	$db = connect();
	$taxNames = $db->quote($taxNames,'text',true);
	return $taxNames;
}

function setTaxNames($id, $taxNames){
	$sql = "update Specimen set taxonomicNames = $taxNames where id = $id";
	$db = connect();
	$result = $db->exec($sql);
	// check result
	$sql = "update BaseObject set dateModified=now() where id = $id";
	$result = $db->exec($sql);
	//TODO update keywords!
}

function getTaxonBranchNames($tsnId){
	$sql = "select scientificName from TaxonBranchNode where child = $tsnId ";
	$db = connect();
	$branchNames = array();
	$branch = $db->query($sql);
		while ($node = $branch->fetchRow()) {
		$branchNames[] = $node[0];
	}
	
	return $branchNames;
}
?>
