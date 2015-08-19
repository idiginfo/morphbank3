SET foreign_key_checks = 0;

delete from Annotation;
delete from AnnotationType;
delete from BaseObject;
delete from BasisOfRecord;
delete from CharacterState;
delete from Collection;
delete from CollectionObjects;
delete from ContinentOcean;
delete from Country;
delete from DeterminationAnnotation;
delete from DevelopmentalStage;
delete from ExternalLinkObject;
delete from ExternalLinkType;
delete from Form;
delete from Geolocated;
delete from Groups;
delete from History;
delete from Image;
delete from ImagingPreparationTechnique;
delete from ImagingTechnique;
delete from ITISCounter;
delete from Keywords;
delete from Kingdoms;
delete from Locality;
delete from Matrix;
delete from MatrixCell;
delete from MatrixCellValue;
delete from MbCharacter;
delete from MBTeam;
delete from MirrorInfo;
#delete from MissingImages;
delete from News;
delete from Otu;
delete from Publication;
#delete from RecentlyModified;
#delete from RecentlyModifiedTemp;
delete from sequence;
delete from ServerInfo;
delete from Sex;
delete from Spam;
delete from Specimen;
delete from SpecimenPart;
delete from synonym_links;
delete from Taxa;
#delete from Taxon;
delete from TaxonAuthors;
#delete from TaxonBranch;
#delete from TaxonBranchNode;
delete from TaxonConcept;
delete from TaxonomicUnits;
delete from TaxonUnitTypes;
delete from Tree;
delete from TypeStatus;
delete from `User`;
delete from UserGroup;
delete from UserGroupKey;
delete from UserProperty;
delete from Vernacular;
delete from `View`;
delete from ViewAngle;

# value tables

insert into AnnotationType select * from  MB30.AnnotationType;
insert into BasisOfRecord select * from  MB30.BasisOfRecord;
insert into ContinentOcean select * from  MB30.ContinentOcean;
insert into Country select * from  MB30.Country;
insert into CurrentIds select * from  MB30.CurrentIds;
insert into DevelopmentalStage select * from  MB30.DevelopmentalStage;
insert into ExternalLinkType select * from  MB30.ExternalLinkType;
insert into Form select * from  MB30.Form;
insert into ImagingPreparationTechnique select * from  MB30.ImagingPreparationTechnique;
insert into ImagingTechnique select * from  MB30.ImagingTechnique;
insert into Kingdoms select * from  MB30.Kingdoms;
insert into ViewAngle select * from  MB30.ViewAngle;
insert into Sex select * from  MB30.Sex;
insert into SpecimenPart select * from  MB30.SpecimenPart;
insert into TypeStatus select * from  MB30.TypeStatus;

# public base objects

insert into BaseObject select *,null from  MB30.BaseObject where datetopublish<now() 
	or objecttypeid in ('User','Group');

# subclasses

insert into Annotation select * from  MB30.Annotation where id in (select id from BaseObject);
insert into Collection select * from  MB30.Collection where id in (select id from BaseObject);
insert into DeterminationAnnotation select * from  MB30.DeterminationAnnotation where id in (select id from BaseObject);
insert into Image select * from  MB30.Image where id in (select id from BaseObject);
insert into Locality select * from  MB30.Locality where id in (select id from BaseObject);
insert into News select * from  MB30.News where id in (select id from BaseObject);
insert into Otu select * from  MB30.Otu where id in (select id from BaseObject);
insert into Publication select * from  MB30.Publication where id in (select id from BaseObject);
insert into Specimen select * from  MB30.Specimen where id in (select id from BaseObject);
insert into TaxonConcept select * from  MB30.TaxonConcept where id in (select id from BaseObject);
insert into `View` select * from  MB30.`View` where id in (select id from BaseObject);

#dependent on base object

insert into CollectionObjects select * from  MB30.CollectionObjects c join BaseObject b1 on c.id;
insert into ExternalLinkObject select * from  MB30.ExternalLinkObject;
insert into UserProperty select * from  MB30.UserProperty;

# user and group info

insert into Groups select * from  MB30.Groups;
insert into `User` select * from  MB30.`User`;
insert into UserGroup select * from  MB30.UserGroup;
insert into UserGroupKey select * from  MB30.UserGroupKey;

insert into Keywords(id, userId, groupId, objecttypeid, datetopublish,datecreated,keywords,submittedby,xmlKeywords,geolocated)
 select id, userId, groupId, objecttypeid, datetopublish,datecreated,keywords,submittedby,xmlKeywords,geolocated
 from  BaseObject;

# unused tables

insert into MBTeam select * from  MB30.MBTeam;
insert into MirrorInfo select * from  MB30.MirrorInfo;
insert into sequence select * from  MB30.sequence;
insert into ServerInfo select * from  MB30.ServerInfo;
insert into Spam select * from  MB30.Spam;

# Taxonomic names tables

insert into ITISCounter select * from  MB30.ITISCounter;
insert into synonym_links select * from  MB30.synonym_links ;
insert into Taxa select * from  MB30.Taxa;
insert into TaxonAuthors select * from  MB30.TaxonAuthors;
insert into TaxonomicUnits select * from  MB30.TaxonomicUnits;
insert into TaxonUnitTypes select * from  MB30.TaxonUnitTypes;
insert into Tree select * from  MB30.Tree;
insert into Vernacular select * from  MB30.Vernacular;


# Matrix tables (mostly unused)

insert into CharacterState select * from  MB30.CharacterState;
insert into Matrix select * from  MB30.Matrix;
insert into MatrixCell select * from  MB30.MatrixCell;
insert into MatrixCellValue select * from  MB30.MatrixCellValue;
insert into MbCharacter select * from  MB30.MbCharacter;

# finish up

SET foreign_key_checks = 1;
repair table Keywords quick;
repair table Taxa quick;
