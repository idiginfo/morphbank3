<?php

// constants that are used for this dataset 
// Use these constants, along with the population of the ids table, below,
// to tailor the script for the specific dataset

$dataSetName = "Troy";
$groupId = "634593";

// constants with values representing the databases
$iptDb = "IPT_DB";
$mbDB = "MB32";

// Constants derived from the constants above

$iptTablePrefix = $iptDb . "." . $dataSetName;
$iptIdTable = $iptTablePrefix . "Id";
$iptOccTable = $iptTablePrefix . "Occ";
$iptACTable = $iptTablePrefix . "AC";
$iptRRTable = $iptTablePrefix . "RR";

echo "Use " . $mbDB . ";";

include ("CreateIptTables.php");

// populate id table
// this section should be changed to fit the needs of the dataset.



echo "
#populate specimen ids

truncate Table " . $iptIdTable ."; 

#ids of specimens
insert into " . $iptIdTable ."
select s.id from Specimen s join BaseObject b on b.id=s.id 
where basisofrecordid = 's' 
and b.groupId=" . $groupId . ";
 
		
";

include ("PopulateOccurrence.php");
include ("PopulateAudubonCore.php");
include ("PopulateRelatedResource.php");

?>