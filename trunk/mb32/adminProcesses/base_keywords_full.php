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

require_once('../configuration/app.server.php');

// Separate file for the keywords fields.  Separate so other modules can use one copy.
include_once('newKeywordsData.php'); // Located in /adminProcesses
$link = Adminlogin();
/* Initialize Variables */

include_once("updateObjectKeywords.php");

$STATUS_FILE = "/data/scratch/keywordStatus.txt";
$file = fopen($STATUS_FILE,'r');
$lastId = fread($file, 20);
fclose($file);
if (empty($lastId)) $lastId = 0;

$minId =  $lastId; //1;
$maxId = getMaxId($db);
print ("Harvesting keywords for ids: $minId to $maxId at ".date("h:i:s")."\n");
//echo ("Missing object ids follow: ");
for ($id = $minId; $id<=$maxId; $id++){
	//print "updating id: $id\n";flush();
	updateBaseKeywords( $id);
	if ($id % 1000 == 0){
		print " (progress $id ".date("H:i:s").") \n";
		$file = fopen($STATUS_FILE,'w');
		fwrite($file, $id);
		fclose($file);
	}
}

$updateSql = "replace  Keywords(id, userId, groupId, dateToPublish, objectTypeId,
        keywords, submittedBy, geolocated, xmlKeywords)
    select B.id, userId, groupId, dateToPublish, B.objectTypeId,
         keywords, submittedBy, geolocated, xmlKeywords
       from BaseObject B ";

$deleteSql = "delete from Keywords";
$insertSql = "insert into  Keywords(id, userId, groupId, dateToPublish, objectTypeId,
        keywords, submittedBy, geolocated, xmlKeywords)
    select B.id, userId, groupId, dateToPublish, B.objectTypeId,
         keywords, submittedBy, geolocated, xmlKeywords
       from BaseObject B ";
$db = connect();
echo "Deleting full Keywords table ".date("H:i:s").") \n";
$deleteResult = $db->exec($deleteSql);
if(PEAR::isError($deleteResult)){
	die("\n" . $deleteResult->getMessage() . "\n");
}
echo "Inserting full Keywords table ".date("H:i:s").") \n";
$insertResult = $db->exec($insertSql);
if(PEAR::isError($insertResult)){
	die("\n" . $insertResult->getMessage() . "\n");
}

echo "Repairing keyword index (".date("H:i:s").") \n";
$repairResult = $db->exec("repair table Keywords quick");
if(PEAR::isError($repairResult)){
	die("\n" . $repairResult->getMessage() . "\n");
}
echo "Done! (".date("H:i:s").") \n";

$db->disconnect();

function getMaxId($db){
	$SQL_maxId = 'SELECT max(id) from BaseObject';
	$maxIdResult = $db->query($SQL_maxId);
	if(PEAR::isError($maxIdResult)){
		die("\n" . $maxIdResult->getMessage() . "\n");
	}
	$maxId = $maxIdResult->fetchOne();
	return $maxId;
}

?>
