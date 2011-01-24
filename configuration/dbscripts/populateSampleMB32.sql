# This file copies the data not copied by the CreateSampleDatabase.php script

SET foreign_key_checks = 0;

insert into AnnotationType select * from  MB32.AnnotationType;
insert into BasisOfRecord select * from  MB32.BasisOfRecord;
insert into ContinentOcean select * from  MB32.ContinentOcean;
insert into Country select * from  MB32.Country;
insert into DevelopmentalStage select * from  MB32.DevelopmentalStage;
insert into ExternalLinkType select * from  MB32.ExternalLinkType;
insert into Form select * from  MB32.Form;
insert into ImagingPreparationTechnique select * from  MB32.ImagingPreparationTechnique;
insert into ImagingTechnique select * from  MB32.ImagingTechnique;
insert into ITISCounter select * from  MB32.ITISCounter;
insert into Kingdoms select * from  MB32.Kingdoms;
insert into MBTeam select * from  MB32.MBTeam;
insert into sequence select * from  MB32.sequence;
insert into Sex select * from  MB32.Sex;
insert into Spam select * from  MB32.Spam;
insert into SpecimenPart select * from  MB32.SpecimenPart;
insert into synonym_links select * from  MB32.synonym_links ;
insert into Taxa select * from  MB32.Taxa;
insert into TaxonAuthors select * from  MB32.TaxonAuthors;
insert into TaxonomicUnits select * from  MB32.TaxonomicUnits;
insert into TaxonUnitTypes select * from  MB32.TaxonUnitTypes;
insert into Tree select * from  MB32.Tree;
insert into TypeStatus select * from  MB32.TypeStatus;
insert into Vernacular select * from  MB32.Vernacular;
insert into ViewAngle select * from  MB32.ViewAngle;

# finish up

SET foreign_key_checks = 1;
repair table Keywords quick;
repair table Taxa quick;
