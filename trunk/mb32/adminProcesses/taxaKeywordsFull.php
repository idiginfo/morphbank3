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

// Pear class for handling database connection
include_once("keywordLogin.php");

require_once('../configuration/app.server.php');

// Separate file for the keywords fields.  Separate so other modules can use one copy.
include_once("updateTaxaKeywords.php");
// OPTIONAL fields
$SELECT_LIMIT = " order by tsn limit 2000 ";
$SLEEP_AFTER_MKDIR = 1;
while (true){
	echo date("H:i:s\n");
	// get the id of the last image processed
	$STATUS_FILE = "/data/scratch/taxaStatus.txt";
	$file = fopen($STATUS_FILE,'r');
	$lastTsn = fread($file, 20);
	fclose($file);
	if (empty($lastTsn)) $lastTsn = -1;

	$tsnSql = "select  tsn from Tree where tsn > $lastTsn $SELECT_LIMIT ";

	echo "SQL: $tsnSql\n";
	$db = connect();
	$result = $db->query($tsnSql);
	if(PEAR::isError($result)){
		echo("Error in Missing SQL query".$result->getUserInfo()." $sql\n");
		die();
	}

	$rows= 0;
	while($row = $result->fetchRow()){
		$tsn = $row[0];
		$rows++;
		$message = TaxaKeywords($tsn);
		echo $message."\n";
		if ($rows % 1000 == 0){
			print " (progress $tsn ".date("H:i:s").") \n";
		}
	}
	// refresh the status file with the last id
	$file = fopen($STATUS_FILE,'w');
	fwrite($file, $tsn);
	fclose($file);
	echo date("H:i:s\n");
	if ($rows==0) break;
}


function getMaxTsn($db){
	$SQL_maxId = 'SELECT max(tsn) from Taxa';
	$maxIdResult = $db->query($SQL_maxId);
	if(PEAR::isError($maxIdResult)){
		die("\n" . $maxIdResult->getMessage() . "\n");
	}
	$maxId = $maxIdResult->fetchOne();
	return $maxId;
}

?>
