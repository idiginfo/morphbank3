<?php

//This file automatically connects to the database when called.

// Username and password
$db_user = 'ODBC';
$db_passwd = 'odbcpassword';

// Connect to MySQL server and Morphbank database.
if (!$GLOBALS['connection'] = odbc_connect("MySQL", "$db_user", "$db_passwd")){
    echo "Error, ODBC has mucked up.";}

// Select the contents of the updates table
function getUpdatesArray(){

	$sql  = "SELECT news.date, news.title, news.body, news.img, news.img_text, user.name, user.email ";
	$sql .= "FROM user, news ";
	$sql .= "WHERE news.user_id = user.user_id;";

	$result = odbc_exec($GLOBALS['connection'],$sql);
	
	// For each row, assign the row array to another array.
	$i = 0;
	while ($row = odbc_fetch_array($result)){
		  $rowArray[$i] = $row;
		  $i++;}
	
	// Probably no need to call the following with most queries, but not bad
	// practive to.
	odbc_free_result($result);
	
	return $rowArray; 
}
?>
