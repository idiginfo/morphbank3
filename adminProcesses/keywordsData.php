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
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

$xml_pattern = array('/\"/');
$xml_replace = array('\\\"');
$text_pattern = array('/\s\s+/');
$text_replace = array(' ');

$baseObjectFields = "BaseObject.id, BaseObject.userId, BaseObject.groupId, BaseObject.objectTypeId, BaseObject.submittedBy AS submitterUin, User.name, User.uin, Groups.groupName ";



$userFields = "User.affiliation, User.last_Name, User.first_Name ";
$groupsFields = " Groups.tsn ";
$viewFields = "View.viewName, View.imagingTechnique, View.imagingPreparationTechnique, View.specimenPart, View.viewAngle, View.developmentalStage, View.sex, View.form, View.viewTSN, View.isStandardView, View.standardImageId, User.name AS submittedBy ";
$localityFields = "Locality.country, Locality.continentOcean, Locality.locality, Locality.latitude, Locality.longitude, Locality.minimumElevation, Locality.maximumElevation, User.name AS submittedBy ";
$specimenFields = "Locality.country, Locality.continentOcean, Locality.locality, Locality.latitude, Locality.longitude, Locality.minimumElevation, Locality.maximumElevation, Specimen.id, Specimen.sex, Specimen.form, Specimen.developmentalStage, Specimen.preparationType, Specimen.typeStatus, Specimen.name, Specimen.comment, Specimen.institutionCode, Specimen.collectionCode, Specimen.catalogNumber, Specimen.previousCatalogNumber, Specimen.relatedCatalogItem, Specimen.collectionNumber, Specimen.collectorName, Specimen.dateCollected, Specimen.notes, Specimen.taxonomicNames, Specimen.tsnId, ExternalLinkObject.label, User.name AS submittedBy";
$imageFields = "Image.originalFileName, Image.resolution, Image.magnification, Image.imageType, Image.copyrightText, Image.viewId, Image.copyrightText, Image.specimenId, View.viewName, View.imagingTechnique, View.imagingPreparationTechnique, View.specimenPart, View.viewAngle, View.developmentalStage, View.sex, View.form, View.viewTSN, View.isStandardView, Specimen.sex, Specimen.form, Specimen.developmentalStage, Specimen.preparationType, Specimen.collectionCode, Specimen.typeStatus, Specimen.name, Specimen.comment, Specimen.institutionCode, Specimen.collectionCode, Specimen.catalogNumber, Specimen.previousCatalogNumber, Specimen.relatedCatalogItem, Specimen.collectionNumber, Specimen.collectorName, Specimen.dateCollected, Specimen.notes, Specimen.taxonomicNames, Specimen.tsnId,  Locality.country, Locality.continentOcean, Locality.locality, Locality.latitude, Locality.longitude, Locality.minimumElevation, Locality.maximumElevation, ExternalLinkObject.label, User.name AS submittedBy ";
$collectionFields = "BaseObject.name, CollectionObjects.collectionId, CollectionObjects.objectId, CollectionObjects.objectTypeId, CollectionObjects.objectRole, CollectionObjects.objectTitle, User.name AS submittedBy ";

$annotationFields = "Annotation.objectId, Annotation.objectTypeId, Annotation.id, Annotation.typeAnnotation, Annotation.title, Annotation.comment, Annotation.xmlData, Annotation.annotationLabel, DeterminationAnnotation.annotationId, DeterminationAnnotation.specimenId, DeterminationAnnotation.tsnId, DeterminationAnnotation.rankName, DeterminationAnnotation.typeDetAnnotation, DeterminationAnnotation.sourceOfId, DeterminationAnnotation.collectionId, DeterminationAnnotation.materialsUsedInId, DeterminationAnnotation.prefix, DeterminationAnnotation.suffix, DeterminationAnnotation.resourcesused, DeterminationAnnotation.altTaxonName, Specimen.taxonomicNames, User.name AS submittedBy ";
$publicationFields = "Publication.doi, Publication.publicationType, Publication.author, Publication.publicationTitle, Publication.month, Publication.publisher, Publication.school, Publication.series, Publication.note, Publication.organization, Publication.institution, Publication.title, Publication.volume, Publication.year, Publication.isbn, Publication.issn, User.name AS submittedBy ";
$taxonconceptFields = "TaxonConcept.tsn, TaxonConcept.nameSpace, TaxonConcept.status, Vernacular.vernacular_name, Vernacular.language, Tree.scientificName, Tree.usage, Tree.taxon_author_id, Tree.publicationId, Specimen.tsnId, Specimen.taxonomicNames, User.name AS submittedBy ";
$taxonnameFields = "objectTypeId, typeAnnotation, title, comment, xmlData, annotationLabel";

$viewJoin = ' View LEFT JOIN BaseObject ON View.id = BaseObject.id LEFT JOIN User ON BaseObject.submittedBy = User.id ';
$localityJoin = ' Locality LEFT JOIN BaseObject ON Locality.id = BaseObject.id LEFT JOIN User ON BaseObject.submittedBy = User.id ';
$specimenJoin = ' Specimen LEFT JOIN Locality ON Specimen.localityId = Locality.id LEFT JOIN BaseObject ON Specimen.id = BaseObject.id LEFT JOIN User ON BaseObject.submittedBy = User.id LEFT JOIN ExternalLinkObject ON BaseObject.id = ExternalLinkObject.mbId ';
$imageJoin = ' Image LEFT JOIN View ON Image.viewId = View.id LEFT JOIN Specimen ON Image.specimenId = Specimen.id LEFT JOIN Locality ON Specimen.localityId = Locality.id LEFT JOIN BaseObject ON Image.id = BaseObject.id LEFT JOIN User ON BaseObject.submittedBy = User.id LEFT JOIN ExternalLinkObject ON BaseObject.id = ExternalLinkObject.mbId ';
$collectionJoin = ' Collection LEFT JOIN CollectionObjects ON Collection.id = CollectionObjects.collectionId LEFT JOIN BaseObject ON Collection.id = BaseObject.id LEFT JOIN User ON BaseObject.submittedBy = User.id ';
$annotationJoin = ' Annotation LEFT JOIN DeterminationAnnotation ON Annotation.id = DeterminationAnnotation.annotationId LEFT JOIN Specimen ON DeterminationAnnotation.specimenId = Specimen.id LEFT JOIN BaseObject ON Annotation.id = BaseObject.id LEFT JOIN User ON BaseObject.submittedBy = User.id ';
$publicationJoin = ' Publication LEFT JOIN BaseObject ON Publication.id = BaseObject.id LEFT JOIN User ON BaseObject.submittedBy = User.id ';
$taxonconceptJoin = ' TaxonConcept LEFT JOIN Tree ON TaxonConcept.tsn = Tree.tsn LEFT JOIN Specimen ON Tree.tsn = Specimen.tsnId LEFT JOIN Vernacular ON Tree.tsn = Vernacular.tsn LEFT JOIN BaseObject ON TaxonConcept.id = BaseObject.id LEFT JOIN User ON BaseObject.submittedBy = User.id ';

$defaultCreativeCommons = '<a href="http://creativecommons.org/licenses/by-nc-sa/3.0/us/" rel="license">
<img src="http://i.creativecommons.org/l/by-nc-sa/3.0/us/88x31.png" style="border-width: 0pt;" alt="Creative Commons License"/>
</a>';
?>
