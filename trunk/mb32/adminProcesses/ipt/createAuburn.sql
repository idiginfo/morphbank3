Use MB32;
drop table IPT_DB.AuburnId;
		
create table IPT_DB.AuburnId (
	id int primary key
);
	
drop table IPT_DB.AuburnOcc ;


CREATE TABLE IPT_DB.AuburnOcc  (
id int primary key, # morphbank id
occurrenceID varchar(100), # included in IPT

#record level fields
type varchar(100), # included in IPT
modified datetime, # included in IPT
language varchar(20), # included in IPT
#rights varchar(100),
#rightsHolder varchar(100), 
#accessRights varchar(100),
#bibliographicCitation varchar(512),
`references` varchar(512),
#institutionId varchar(100),
#collectionId varchar(100),
#datasetId varchar(100),
institutionCode varchar(100), # included in IPT
collectionCode varchar(100), # included in IPT
#datasetName varchar(100),
#ownerInstitutionCode varchar(100),
basisOfRecord varchar(100), # included in IPT
informationWithheld varchar(512), # included in IPT
#dataGeneralizations varchar(100),
#dynamicProperties varchar(100),
#source varchar(100),

# occurrence fields
catalogNumber varchar(100), # included in IPT
occurrenceRemarks varchar(512), # included in IPT
recordNumber varchar(100), # included in IPT
recordedBy varchar(1024), # included in IPT
#individualId varchar(100),
individualCount int, # included in IPT
sex varchar(100), # included in IPT
lifeStage varchar(100), # included in IPT
#reproductiveCondition varchar(100),
#behavior varchar(512),
#establishmentMeans varchar(100),
#occurrenceStatus varchar(100),
preparations varchar(100), # included in IPT
otherCatalogNumbers varchar(100), # included in IPT
#disposition varchar(100),
#previousIdentifcations varchar(512),
#associatedMedia varchar(100),
#associatedReferences varchar(100),
#associatedOccurrences varchar(100),
#associatedSequences varchar(100),
#associatedTaxa varchar(100),
#occurrenceDetails varchar(100),

#Event fields

#eventID varchar(100),
#samplingProtocol varchar(100),
#samplingEffort varchar(100),
eventDate date, # included in IPT
#eventTime varchar(100),
#startDayOfYear int,
#endDayOfYear int,
#year int,
#month varchar(12),
#day int,
verbatimEventDate varchar(100), # included in IPT
#habitat varchar(512),
#fieldNumber int,
fieldNotes varchar(512), # included in IPT
#eventRemark varchar(100),

# location fields
#locationId varchar(100),
#higherGeographyID varchar(100),
#higherGeography varchar(100),
continent varchar(100), # included in IPT
waterbody varchar(300), # included in IPT
#islandGroup varchar(100),
#island varchar(100),
country varchar(100), # included in IPT
countryCode varchar(10), # included in IPT
stateProvince varchar(100), # included in IPT
county varchar(512), # included in IPT
#municipality varchar(100),
locality varchar(512), # included in IPT
#verbatimLocality varchar(512),
#verbatimElevation varchar(100),
minimumelevationInMeters varchar(45), # included in IPT
maximumElevationInMeters varchar(45), # included in IPT
#verbatimDepth varchar(100),
minimumDepthInMeters varchar(45), # included in IPT
maximumDepthInMeters varchar(45), # included in IPT
#minimumDistanceAboveSurfaceInMeters varchar(45),
#maximumDistanceAboveSurfaceInMeters varchar(45),
#locationAccordingTo varchar(100),
#locationRemarks varchar(512),
#verbatimCoordinates varchar(100),
#verbatimLatitude varchar(100),
#verbatimLongitude varchar(100),
#verbatimCoordinateSystem varchar(100),
#verbatimSRS varchar(100),
decimalLatitude varchar(45), # included in IPT
decimalLongitude varchar(45), # included in IPT
#geodeticDatum varchar(100),
#coordinateUncertaintyInMeters varchar(45),
coordinatePrecision varchar(100), # included in IPT
#pointRadiusSpatialFit varchar(100),
#footprintWKT varchar(100),
#footprintSRS varchar(100),
#footprintSpatialFit varchar(100),
#georeferencedBy varchar(100),
#georeferencedDate date,
#georeferenceProtocol varchar(100),
#georeferenceSources varchar(100),
#georeferenceVerificationStatus varchar(100),
#georeferenceRemarks varchar(100),

#Geological Context fields

#geologicalContextID varchar(100),
#earliestEonOrLowestEonothem varchar(100),
#latestEonOrHighestEonothem varchar(100),
#earliestEraOrLowestErathem varchar(100),
#latestEraOrHighestErathem varchar(100),
#earliestPeriodOrLowestSystem varchar(100),
#latestPeriodOrHighestSystem varchar(100),
#earliestEpochOrLowestSeries varchar(100),
#latestEpochOrHighestSeries varchar(100),
#earliestAgeOrLowestStage varchar(100),
#latestAgeOrHighestStage varchar(100),
#lowestBiostratigraphicZone varchar(100),
#highestBiostratigraphicZone varchar(100),
#lithostratigraphicTerms varchar(100),
`group` varchar(100), # included in IPT
formation varchar(100), # included in IPT
member varchar(100), # included in IPT
bed varchar(100), # included in IPT

#identification fields

#identificationID varchar(100),
identifiedBy varchar(100), # included in IPT
dateIdentified varchar(100), # included in IPT
#identificationReferences varchar(100),
identificationRemarks varchar(1024), # included in IPT
#identificationQualifier varchar(100),
#identificationVerificationStatus varchar(100),
typeStatus varchar(100), # included in IPT

# taxon fields

#taxonID varchar(100),
#scientificNameID varchar(100),
#acceptedNameUsageID varchar(100),
#parentNameUsageID varchar(100),
#originalNameUsageID varchar(100),
#nameAccordingToID varchar(100),
#namePublishedInID varchar(100),
#taxonConceptID varchar(100),
scientificName varchar(100), # included in IPT
#acceptedNameUsage varchar(100),
#parentNameUsage varchar(100),
#originalNameUsage varchar(100),
#nameAccordingTo varchar(100),
#namePublishedIn varchar(100),
#namePublishedInYear varchar(45),
higherClassification varchar(512), # included in IPT
kingdom varchar(100), # included in IPT
phylum varchar(100), # included in IPT
class varchar(100), # included in IPT
`order` varchar(100), # included in IPT
family varchar(100), # included in IPT
genus varchar(100), # included in IPT
subgenus varchar(100), # included in IPT
#specificEpithet varchar(100),
#infraspecificEpithet varchar(100),
taxonRank varchar(100), # included in IPT
#verbatimTaxonRank varchar(100),
scientificNameAuthorship varchar(100), # included in IPT
#vernacularName varchar(100),
#nomenclaturalCode varchar(100),
#taxonomicStatus varchar(100),
nomenclaturalStatus varchar(100) # included in IPT
#taxonRemarks varchar(100)
);


drop table IPT_DB.AuburnAC;


CREATE TABLE IPT_DB.AuburnAC (
id int primary key, # morphbank id
occurrenceID varchar(100), # included in IPT

# Management Vocabulary
identifier varchar(100), # included in IPT
type varchar(100), # included in IPT
# subtypeLiteral varchar(100)
subtype varchar(100),
title varchar(1000),
modified datetime, # included in IPT
MetadataDate date,
metadataLanguageLiteral varchar(100), # included in IPT
#metadataLanguage varchar(100),
providerManagedID varchar(100), # included in IPT
Rating varchar(100),
#commenterLiteral
#commenter
comments varchar(100),
#reviewerLiteral
reviewer varchar(100),
reviewerComments varchar(100),
available varchar(100), # included in IPT
#hasServiceAccessPoint varchar(10),

# Attribution Vocabulary
rights varchar(100), # included in IPT
Owner varchar(100), # included in IPT
UsageTerms varchar(100),
WebStatement varchar(300), # included in IPT
licenseLogoURL varchar(100), # included in IPT
Credit varchar(100), # included in IPT
attributionLogoURL varchar(100), # included in IPT
attributionLinkURL varchar(100),
#fundingAttribution
source varchar(100),

# Agents Vocabulary
creator varchar(100), # included in IPT
#providerLiteral
provider varchar(100), # included in IPT
#metadataCreatorLiteral,
metadataCreator varchar(100), # included in IPT
#metadataProviderLiteral
metadataProvider varchar(100), # included in IPT

#Content Coverage Vocabulary
description varchar(512), # included in IPT
caption varchar(512),
language varchar(100),
#physicalSetting
#CVterm
#subjectCategoryVocabulary
#tag		

#Geography Vocabulary
LocationShown varchar(100),
WorldRegion varchar(100),
CountryCode varchar(100),
CountryName varchar(100),
ProvinceState varchar(100),
City varchar(100),
Sublocation varchar(100),

#Temporal Coverage Vocabulary
temporal varchar(100),
CreateDate date,
timeOfDay varchar(100),

#Subject of Resource Vocabulary
physicalSetting varchar(100),
CVterm varchar(100),
subjectCategoryVocabulary varchar(100),
tag varchar(100),

#Taxonomic Coverage Vocabulary
taxonCoverage varchar(100),
scientificName varchar(100),
identificationQualifier varchar(100),
vernacularName varchar(100),
nameAccordingTo varchar(100),
scientificNameID varchar(100),
otherScientificName varchar(100),
identifiedBy varchar(100),
dateIdentified date,
taxonCount int,
subjectPart varchar(100), # included in IPT
sex varchar(100),
lifeStage varchar(100),
subjectOrientation varchar(100), # included in IPT
#preparations

#Resource Creation Vocabulary
LocationCreated varchar(512),
digitizationDate date,
captureDevice varchar(100), # included in IPT
resourceCreationTechnique varchar(100), # included in IPT

#Related Resources Vocabulary

#Service access points

accessURI varchar(100),
format varchar(100),
variantLiteral varchar(100),
#variant
PixelXDimension varchar(100),
PixelYDimension varchar(100),
furtherInformationURL varchar(100),
licensingException varchar(100),
serviceExpectation varchar(100),
variantDescription varchar(100)
);

drop table IPT_DB.AuburnRR;


Create Table IPT_DB.AuburnRR (
occurrenceID varchar(100),
#resourceRelationshipID varchar(100),
#resourceID varchar(100),
relatedResourceID varchar(100),
relationshipOfResource varchar(100)
#relationshipAccordingTo varchar(100),
#relationshipEstablishedDate varchar(100),
#relationshipRemarks varchar(100)
);


#populate specimen ids

truncate Table IPT_DB.AuburnId; 

#ids of specimens
insert into IPT_DB.AuburnId
select s.id from Specimen s join BaseObject b on b.id=s.id 
where basisofrecordid = 's' 
and b.groupId=692592;
 
drop table ;
		
truncate Table IPT_DB.AuburnOcc; 

insert into IPT_DB.AuburnOcc 
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


from IPT_DB.AuburnId i join 
Specimen s on i.id=s.id join Locality l on s.localityid=l.id
join BaseObject b on s.id=b.id 
join Taxa t on s.tsnId=t.tsn
join TaxonUnitTypes u on (t.rank_id=u.rank_Id and t.kingdom_id=u.kingdom_id)
left join ExternalLinkObject eo 
		on (eo.mbid=s.id and eo.description = 'dcterms:identifier')
left join Country c on l.country=c.description
where b.dateToPublish <= now() and s.basisOfRecordId = 'S'
;

update IPT_DB.AuburnOcc o join Specimen s on s.id=o.id
		join TaxonBranches t on s.tsnid = t.tsn
		set o.kingdom = t.kingdom,
		o.phylum = t.phylum,
		o.class = t.class,
		o.order = t.order,
		o.family = t.family,
		o.genus = t.genus,
		o.subgenus = t.subgenus;		
		
truncate table IPT_DB.AuburnAC;

 insert into IPT_DB.AuburnAC (
id, 
occurrenceID,
identifier,
type,
title,
modified,
metadataLanguageLiteral,
providerManagedID,
available,
rights,
owner,
webStatement,
licenseLogoURL,
credit,
attributionLogoURL,
creator,
provider,
metadataProvider,
metadataCreator,
description,
tag,
subjectPart,
subjectOrientation,
captureDevice,
resourceCreationTechnique,

accessURI,
format,
variantLiteral,
PixelXDimension,
PixelYDimension,
furtherInformationURL)

select
i.id,
s.occurrenceID,
eo.externalId AS identifier, 
'StillImage' AS type,
concat ('image of ',s.scientificname) AS title,
b.dateLastModified AS modified,
'en' AS metadataLanguageLiteral,
concat('http://www.morphbank.net/',i.id) AS providerManagedID,
i.dateToPublish AS available,
cc.rights,
i.copyrightText AS Owner,
i.creativeCommons as webStatement,
cc.licenseLogoURL,
i.copyrightText AS credit,
u.userLogo AS attributionLogoURL,
i.photographer AS creator,
concat('http://www.morphbank.net/',b.userId) AS provider,
'morphbank.net' AS metadataProvider,
'morphbank.net' AS metadataCreator,
b.description AS description,
v.specimenPart AS tag, # tag with more info from view
v.specimenPart AS subjectPart,
v.viewAngle AS subjectOrientation,
v.imagingTechnique AS captureDevice,
v.imagingPreparationTechnique AS resourceCreationTechnique,


# original image as best quality access point
concat('http://www.morphbank.net?id=',i.id,'&imgType=jpeg') AS accessURI,
'image/jpeg' AS format,
'best quality' as variantLiteral,
i.imageWidth as PixelXDimension,
i.imageHeight as PixelYDimension,
concat('http://www.morphbank.net/',i.id) AS furtherInformationURL

from Image i 
join IPT_DB.AuburnOcc s on (i.specimenid=s.id)
join BaseObject b on(i.id = b.id) 
left join View v on(i.viewId = v.id) 
join User u on(b.userId = u.id) 
join ExternalLinkObject eo on (i.id = eo.mbid and eo.description =  'dcterms:identifier')
left join CreativeCommons cc on  cc.idCreativeCommons = 32;

truncate IPT_DB.AuburnRR;

insert into IPT_DB.AuburnRR
(occurrenceId, relationshipOfResource, relatedResourceId)
select occurrenceId, 'representedIn',concat ('http://www.morphbank.net/',id)
from IPT_DB.AuburnOcc;

