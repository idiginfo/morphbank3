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

// Username and password
$db_user = 'webuser';
$db_passwd = 'namaste';
$host = 'localhost';
$port = '3306';
$database = 'MB30';

//$startTime = time();

// Connect to MySQL server
if (!mysqli_connect("$host:$port" , "$db_user", "$db_passwd")){
    echo "Error, no database server.";
    exit;} 
else{
    $link = mysqli_connect("$host:$port" , "$db_user", "$db_passwd");

    if (!mysqli_select_db($link, $database)){
        echo "Error, no database";
        exit;    } 
    else
        mysqli_select_db($link, $database);} 

$treeTableName = 'Tree';
$tsn = 999000188;
$parent_tsn = 152742;
$rank_id = 130;

addListOfNewTSNonTree();

//addNewTSNonTree( 999000573, 'Acantheucoela', '','','',154028,180);

// Function for testing 
function addListOfNewTSNonTree() {
	
	$sql = "SELECT * FROM TaxonomicUnits WHERE tsn>999000622 ORDER BY tsn;";
	$result = mysqli_query($link, $sql);
	
	echo "Result = ".$result;
	if ($result == NULL) exit;
	
	while ($array = mysqli_fetch_array($result)) {
		echo $array['tsn']."-".$array['parent_tsn']."-". $array['rank_id']."\n";
		addNewTSNonTree( $array['tsn'], $array['unit_name1'], $array['unit_name2'],$array['unit_name3'],$array['unit_name4'], 
						$array['parent_tsn'], $array['rank_id'], $array['kingdom_id']);
	}
}

// This function only update the necesary fields
// I'm not checking (validation) any parameter
function addNewTSNonTree( $tsn, $unit_name1 = '', $unit_name2 = '', $unit_name3 = '', $unit_name4 = '', $parent_tsn,  $rank_id, $kingdom_id) {
   
	global $treeTableName;
	
	$sql = "SELECT lft, rgt FROM ".$treeTableName." WHERE tsn=".$parent_tsn.";";
	//echo $sql."\n";
	$lft_right = mysqli_fetch_array( mysqli_query($link, $sql));
	
	$sql = "UPDATE ".$treeTableName." SET rgt=rgt+2 WHERE rgt>".($lft_right['rgt']-1).";";
	//echo $sql."\n";
	mysqli_query($link, $sql);
	
	$sql = "UPDATE ".$treeTableName." SET lft=lft+2 WHERE lft>".($lft_right['rgt']-1).";";
	//echo $sql."\n";
	mysqli_query($link, $sql);
	
	$letter = $unit_name1[0];
	$tsnName = $unit_name1.' '.$unit_name2.' '.$unit_name3.' '.$unit_name4;
	
	$sql = "INSERT INTO `".$treeTableName."` (tsn, unit_name1, unit_name2, unit_name3, unit_name4, tsnName, letter, parent_tsn, kingdom_id, rank_id, lft, rgt, imagesCount) 
			VALUES (".$tsn.",'".$unit_name1."','".$unit_name2."','".$unit_name3."','".$unit_name4."','".$tsnName."','".$letter."',".$parent_tsn.",".$kingdom_id.",".$rank_id.", ".$lft_right['rgt'].", ".($lft_right['rgt']+1).", 0);";
	//echo $sql."\n\n";
	mysqli_query($link, $sql);
	
	return 1;
}

?>

