<?php
echo "drop table " . $iptACSTable . ";


CREATE TABLE " . $iptACSTable . " (
id int primary key, # morphbank id
occurrenceID varchar(100), # included in IPT

# Management Vocabulary
identifier varchar(100), # included in IPT
type varchar(100), 
subTypeLiteral varchar(100),
title varchar(1000),
Xmodified datetime, # included in IPT
metadataLanguageLiteral varchar(100), # included in IPT
rating varchar(100),
# comments varchar(100),
reviewerLiteral varchar(100),
XreviewerComments varchar(100),
available varchar(100), # included in IPT

# Attribution Vocabulary
rights varchar(100), # included in IPT
owner varchar(100), # included in IPT
usageTerms varchar(100),
webStatement varchar(1000), # included in IPT
licenseLogoURL varchar(100), # included in IPT
credit varchar(100), # included in IPT
attributionLogoURL varchar(100), # included in IPT
attributionLinkURL varchar(100),
source varchar(100),

# Agents Vocabulary
creator varchar(100), # included in IPT
providerLiteral varchar(100), # included in IPT
metadataProviderLiteral varchar(100), # included in IPT
# metadataCreator varchar(100), # included in IPT

# Content Coverage Vocabulary
description varchar(512), # included in IPT
# caption varchar(512),
# language varchar(100),

#Geography Vocabulary
LocationShown varchar(100),
# WorldRegion varchar(100),
# CountryCode varchar(100),
# CountryName varchar(100),
# ProvinceState varchar(100),
# City varchar(100),
# Sublocation varchar(100),

#Temporal Coverage Vocabulary
# temporal varchar(100),
# CreateDate date,
# timeOfDay varchar(100),

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
taxonID varchar(100),
scientificNameSynonym varchar(100),
identifiedBy varchar(100),
dateIdentified date,
taxonCount int,
subjectPart varchar(100), # included in IPT
sex varchar(100),
lifeStage varchar(100),
subjectOrientation varchar(100), # included in IPT

#Resource Creation Vocabulary
LocationCreated varchar(512),
digitizationDate date,
captureDevice varchar(100), # included in IPT
resourceCreationTechnique varchar(100), # included in IPT

#Service access points
accessURI varchar(100), # included in IPT
format varchar(100), # included in IPT
variantLiteral varchar(100),
furtherInformationURL varchar(100)
);
"
?>