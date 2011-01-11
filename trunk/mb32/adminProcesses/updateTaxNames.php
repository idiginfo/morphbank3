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
