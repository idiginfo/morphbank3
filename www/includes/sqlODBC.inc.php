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
