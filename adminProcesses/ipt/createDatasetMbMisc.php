<?php

// constants that are used for this dataset 
// Use these constants, along with the population of the ids table, below,
// to tailor the script for the specific dataset

$dataSetName = "MbMisc";

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

$iptGroupsTable = $iptTablePrefix . "Groups";

echo "
#populate specimen ids

# list of groups
create table " . $iptGroupsTable . " 
select o.groupid as groupid, count(*) as quantity
from Specimen s 
join BaseObject o on
 o.id=s.id 
where basisofrecordid = 's' and institutionCode is not null and institutionCode !=' '
and institutionCode !='private'
group by groupid
having count(*)>20 and count(*)<2000 order by count(*);
		
truncate Table " . $iptIdTable ."; 

#ids of specimens
insert into " . $iptIdTable ."
select s.id from Specimen s join BaseObject o on o.id=s.id 
where basisofrecordid = 's' and institutionCode is not null and institutionCode !=' '
and institutionCode !='private'
and groupid in (select groupid from " . $iptGroupsTable . ");
 
drop table " . $iptGroupsTable . ";
		
";

include ("PopulateOccurrence.php");
include ("PopulateAudubonCore.php");
include ("PopulateRelatedResource.php");

?>