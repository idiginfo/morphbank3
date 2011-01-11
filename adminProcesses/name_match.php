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

//script that returns the wildcard matches from mb Tree based on scientific name
//to use change the name of the .csv file, make sure the script and .csv file are in the same folder
//run via the command line (php name_match.php > results.txt)
//csv file should just be a list of names in one column like:
// Animalia
//Plantae
// Hymentoptera
//the csv is best saved as DOS or UNIX (only a problem if coming from a Mac)


// Username and password
$db_user = 'webuser';
$db_passwd = 'namaste';
$host = 'localhost';
$port = '3306';
$database = 'MB30';

$rows = 1;
//$startTime = time();
// Connect to MySQL server

   $link = mysqli_connect($host , $db_user , $db_passwd , $database , $port);
   if (!$link){
    echo "Error, no database server.\n" .mysqli_connect_error($link);
    exit;
}else{
    if (!mysqli_select_db($link, $database)){
        echo "Error, no database";
        exit;
    }else
        mysqli_select_db($link, $database);
} 

//function for returning the parent name
	function parentName($parentTsn){
				global $link;
								$sql_parent="SELECT scientificName AS parent_name FROM Tree WHERE tsn=" .$parentTsn;
			  					$result = mysqli_query($link, $sql_parent);
			  							if (!$result) {
										return $sql."\n".mysqli_error($link);
												}//close if loop
								$parent = mysqli_fetch_array($result, MYSQL_ASSOC);
								$Parent_name=$parent['parent_name'];
								$Parent_name.=''.$parent[imageId].'';
			  					return $Parent_name;
									}//close function

//handler for opening text file
$handle = fopen("names2.csv", "r");//CHANGE the name of the .csv file
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$num = count($data);
    	//echo "$num fields in line $rows: \n\n";
	for ($c=0; $c < $num; $c++){

$sql= 'SELECT Kingdoms.kingdom_name, TaxonUnitTypes.rank_name, Tree.tsn, Tree.parent_tsn,Tree.scientificName, TaxonAuthors.taxon_author_id, Tree.nameSource, Tree.imagesCount';
$sql .=' FROM Tree LEFT JOIN Kingdoms ON Tree.kingdom_id = Kingdoms.kingdom_id';
$sql .=' LEFT JOIN TaxonAuthors ON Tree.taxon_author_id = TaxonAuthors.taxon_author_id';
$sql .=' LEFT JOIN TaxonUnitTypes ON Tree.rank_id = TaxonUnitTypes.rank_id AND Tree.kingdom_id = TaxonUnitTypes.kingdom_id';
$sql .=' WHERE Tree.scientificName LIKE \'%' .$data[$c] . '%\' order by Tree.kingdom_id, TaxonUnitTypes.rank_name, Tree.scientificName;';
  echo "New query for:" .$data[$c]. "\n";
    	  echo "kingdom | parent | rank | tsn | scientific name | taxon author | name source | image count". "\n";
    	    		}//end of for loop
    $result = mysqli_query($link, $sql);
			while ($row = mysqli_fetch_array($result)){
echo $row['kingdom_name'].", ".parentName($row['parent_tsn']).", ".$row['rank_name'].", ".$row['tsn'].", ".$row['scientificName'].", ".$row['taxon_author'].", ". $row['nameSource'].", ". $row['imagesCount']."\n"; 
									
													}//end of result while loop
							} //end of handle while loop 
fclose($handle);
?>
