<?php
echo "truncate Table " . $iptOccTable . "; 

insert into " . $iptOccTable . " 
( 
id,
occurrenceID,
type,
modified,
language,
`references`,
institutionCode,
collectionCode,
basisOfRecord,
informationWithheld,
catalogNumber,
occurrenceRemarks,
recordNumber,
recordedBy,
individualCount,
sex,
lifeStage,
preparations,
otherCatalogNumbers,
eventDate,
#verbatimEventDate,
#fieldNotes,
#locationId,
continent,
waterbody,
country,
countryCode,
stateProvince,
county,
locality,
minimumelevationInMeters,
maximumElevationInMeters,
minimumDepthInMeters,
maximumDepthInMeters,
decimalLatitude,
decimalLongitude,
coordinatePrecision,
`group`,
formation,
member,
bed,
identifiedBy,
dateIdentified,
identificationRemarks,
typeStatus,
scientificname,
higherClassification,
taxonRank,
scientificNameAuthorship,
nomenclaturalStatus
)
 
select
s.id,
eo.externalId AS occurrenceID,
'PreservedSpecimen' as type,
b.dateLastModified AS modified,
'en' AS language,
concat('http://www.morphbank.net/',s.id) as `references`,
s.institutionCode,
s.collectionCode,
'PreservedSpecimen' AS basisOfRecord,
l.informationWithheld,
s.catalogNumber,
s.notes AS occurrenceRemarks,
s.collectionNumber AS recordNumber,
s.collectorName AS recordedBy,
s.individualCount,
s.sex,
s.developmentalStage AS lifeStage,
s.preparationType AS preparations,
s.previousCatalogNumber AS otherCatalogNumbers,
s.dateCollected AS eventDate,
#uVED.value AS verbatimEventDate,
#uFN.value AS fieldNotes,
#locationId,
l.continent,
l.ocean as waterbody,
l.country,
c.name as countryCode,
l.state as stateProvince,
l.county,
l.locality,
l.minimumelevation as minimumelevationInMeters,
l.maximumElevation as maximumElevationInMeters,
l.minimumDepth as minimumDepthInMeters,
l.maximumDepth as maximumDepthInMeters,
l.latitude as decimalLatitude,
l.longitude as decimalLongitude,
l.coordinatePrecision,
l.paleoGroup as `group`,
l.paleoFormation as formation,
l.paleoMember as member,
l.paleoBed as bed,
s.name as identifiedBy,
s.dateIdentified,
s.comment as identificationRemarks,
s.typeStatus,

t.scientificname,
s.taxonomicNames AS higherClassification,
u.rank_name as taxonRank,
t.taxon_author_name as scientificNameAuthorship,
t.status as nomenclaturalStatus


from " . $iptIdTable . " i join 
Specimen s on i.id=s.id join Locality l on s.localityid=l.id
join BaseObject b on s.id=b.id 
join Taxa t on s.tsnId=t.tsn
join TaxonUnitTypes u on (t.rank_id=u.rank_Id and t.kingdom_id=u.kingdom_id)
left join ExternalLinkObject eo 
		on (eo.mbid=s.id and eo.description = 'dcterms:identifier')
left join Country c on l.country=c.description
where b.dateToPublish <= now() and s.basisOfRecordId = 'S'
;

update " . $iptOccTable . " o join Specimen s on s.id=o.id
		join TaxonBranches t on s.tsnid = t.tsn
		set o.kingdom = t.kingdom,
		o.phylum = t.phylum,
		o.class = t.class,
		o.order = t.order,
		o.family = t.family,
		o.genus = t.genus,
		o.subgenus = t.subgenus;		
		
";
?>