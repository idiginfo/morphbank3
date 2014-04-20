<?php

$dataSetName = "MbMisc";

include ("CreateIptTables.php");

// populate id table
echo "
#populate specimen ids

# list of groups
create table Groups".$dataSetName." 
select o.groupid as groupid, count(*) as quantity
from Specimen s 
join BaseObject o on
 o.id=s.id 
where basisofrecordid = 's' and institutionCode is not null and institutionCode !=' '
and institutionCode !='private'
group by groupid
having count(*)>20 and count(*)<2000 order by count(*);
		
truncate Table IptIdsMbMisc; 

#ids of specimens
insert into IptIds".$dataSetName."
select s.id from Specimen s join BaseObject o on o.id=s.id 
where basisofrecordid = 's' and institutionCode is not null and institutionCode !=' '
and institutionCode !='private'
and groupid in (select groupid from Groups".$dataSetName.");
 
drop table Groups".$dataSetName.";
		
";

include ("PopulateOccurrence.php");
include ("PopulateAudubonCore.php");
include ("PopulateRelatedResource.php");

?>