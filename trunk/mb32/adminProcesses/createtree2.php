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


/*
if (!session_is_registered('admin'))
{
	header("Location: ../");
	exit;
}
*/
$startTime = time();

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

$sql = "DROP TABLE IF EXISTS `".$treeTableName."`;";
mysqli_query($link, $sql);
echo "1".mysqli_error($link);
echo $sql; echo "\n";

$sql = "CREATE TABLE `".$treeTableName."` (
	`tsn` bigint(20) NOT NULL default '0',
	`unit_ind1` char(1) default '',
	`unit_name1` varchar(35) default '',
	`unit_ind2` char(1) default '',
	`unit_name2` varchar(35) default '',
	`unit_ind3` varchar(7) default '',
	`unit_name3` varchar(35) default '',
	`unit_ind4` varchar(7) default '',
	`unit_name4` varchar(35) default '',
	`tsnName` varchar(64) default '',
	`letter` char(1) default '',
	`usage` varchar(12) NOT NULL default '',
	`unaccept_reason` varchar(50) default NULL,
	`credibility_rtng` varchar(40) NOT NULL default '',
	`completeness_rtng` varchar(10) default NULL,
	`currency_rating` varchar(7) default NULL,
	`parent_tsn` bigint(20) default NULL,
	`kingdom_id` smallint(6) default NULL,
	`rank_id` smallint(6) default NULL,
	`lft` int(11) default NULL,
	`rgt` int(11) default NULL,
	`imagesCount` bigint(20) default '0',
	PRIMARY KEY  (`tsn`),
	KEY `treeparttsntsnfk` (`parent_tsn`),
	KEY `letter` (`letter`),
	KEY `lft` (`lft`),
	KEY `rgt` (`rgt`))"; 
mysqli_query($link, $sql);
echo "\n2".mysqli_error($link);
echo $sql."\n";

$sql = "INSERT INTO `".$treeTableName."` VALUES ( 0,'','Life','','','','','','','Life','L','','','','','',NULL,NULL,0,0,0,0);";
mysqli_query($link, $sql);
echo "\n3".mysqli_error($link);
echo $sql;echo "\n";

// Cleaning TaxonomicaUnits
//==========================

//Names
//=====
//Update TaxonomicUnits set unit_name1="" Where unit_name1 is null;
//Update TaxonomicUnits set unit_name2="" Where unit_name2 is null;
//Update TaxonomicUnits set unit_name3="" Where unit_name3 is null;
//Update TaxonomicUnits set unit_name4="" Where unit_name4 is null;

//Indicators
//==========
//Update TaxonomicUnits set unit_ind1="" Where unit_ind1 is null;
//Update TaxonomicUnits set unit_ind2="" Where unit_ind2 is null;
//Update TaxonomicUnits set unit_ind3="" Where unit_ind3 is null;
//Update TaxonomicUnits set unit_ind4="" Where unit_ind4 is null;


$sql = "INSERT INTO `".$treeTableName."` 
		SELECT `tsn`, `unit_ind1`,`unit_name1`, 
					 `unit_ind2`,`unit_name2`, 
					 `unit_ind3`,`unit_name3`, 
					 `unit_ind4`,`unit_name4`,
					concat( unit_ind1,' ', unit_name1,' ',
							unit_ind2,' ', unit_name2,' ', 
							unit_ind3,' ',unit_name3,' ',
							unit_ind4,' ',unit_name4),
					SUBSTRING( unit_name1, 1, 1),
					`usage`, `unaccept_reason`, `credibility_rtng`,`completeness_rtng`,`currency_rating`,
					`parent_tsn`, `kingdom_id`, `rank_id`, 0, 0, 0 
		FROM TaxonomicUnits";
mysqli_query($link, $sql);
echo "\n4".mysqli_error($link);
echo $sql; echo "\n\n";

// Update parent_tsn for invalid and not accepted tsn based on synonym_ref table

// Get the "valid" synomys which have a valid or accept
$sql ="SELECT synonym_links.*, `usage`, rank_id, parent_tsn, count(*) as repeated
	FROM synonym_links 
	LEFT JOIN TaxonomicUnits ON synonym_links.tsn_accepted = TaxonomicUnits.tsn 
	WHERE parent_tsn IS NOT NULL AND parent_tsn <> 0 AND rank_id >= 140
	GROUP BY synonym_links.tsn 
	HAVING repeated = 1";
	
$listOfSynonyms = mysqli_query($link, $sql);
$i=0;
while ($row=mysqli_fetch_array($listOfSynonyms)) {
	$updateSql = "UPDATE $treeTableName SET parent_tsn=".$row['parent_tsn']." WHERE tsn = ".$row['tsn']; 
	$i++;
	echo $updateSql;
	if ( mysqli_query($link, $updateSql)) 
		echo mysqli_error($link);	
	echo "=>affected_rows ".mysqli_affected_rows($link)."\n";
}


// Check parent_tsn on Tree <> 0 
//==============================

$sql= "DELETE FROM $treeTableName WHERE parent_tsn=0 and (rank_id>10 or tsn=50)";
mysqli_query($link, $sql);

echo $i." updated records\n";  
//echo "Creating the tree\n";
//include ('./rebuild_tree.php');
echo "End\n";
?>
