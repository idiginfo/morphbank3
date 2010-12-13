<?php
set_include_path(".;C:\Program Files (x86)\PHP\pear");
echo "include: ". get_include_path();

require_once('../configuration/app.server.php');
include_once('tsnFunctions.php');
include_once('updateTaxKeywords.php');

// Define functions that perform keyword update on a single object
$numUpdated = setMissingTaxNames();
echo "Number of rows updated: $numUpdated\n";

function setMissingTaxNames(){
	$whereClause = "where taxonomicNames is null";
	$numUpdated = setAllTaxNames($whereClause);
	return $numUpdated;
}

function setAllTaxNames($whereClause = ""){
	$sql = "select tsn from Taxa " . $whereClause;
	$db = connect();
	$numUpdated = 0;
	$result = $db -> query($sql);
	while (	$row = $result -> fetchRow()){
		list ($tsn) = $row;
		$taxNames = getTaxonomicNamesFromBranch ($tsn);
		echo "Setting tax names for $tsn to $taxNames\n";
		setTaxNames($tsn, $taxNames);
		$numUpdated ++;
	}
	return $numUpdated;
}

function setTaxNames($tsn, $taxNames){
	$sql = "update Taxa set taxonomicNames = $taxNames where tsn = $tsn";
	$db = connect();
	$result = $db->exec($sql);
	TaxaKeywords($tsn);
}

