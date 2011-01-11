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

// check for database availability

include_once($securityDirectory.'admin.inc.php');

$xml_pattern = array('/\"/');
$xml_replace = array('\\\"');
$text_pattern = array('/\s\s+/');
$text_replace = array(' ');

// keyword attributes for each table
$baseObjectAttrs = "B.id, B.name";
//."B.userId, B.groupId, B.objectTypeId, "
//."B.submittedBy AS submitterId ";
$annotationAttrs = ", A.objectId, A.typeAnnotation, A.title, A.comment, A.xmlData, A.annotationLabel"
.", DA.rankName, DA.typeDetAnnotation, DA.sourceOfId, DA.materialsUsedInId, DA.prefix"
.", DA.suffix, DA.resourcesused, DA.altTaxonName";
$collectionAttrs = "";
$groupsAttrs = ", G.groupName";
$imageAttrs = ", I.originalFileName, I.resolution, I.magnification, I.imageType";
$localityAttrs = ", L.country, L.continentOcean, L.locality, L.latitude, L.longitude"
.", L.minimumElevation, L.maximumElevation";
$specimenAttrs = ", S.sex, S.form, S.developmentalStage"
.", S.preparationType, S.typeStatus, S.name, S.comment, S.institutionCode, S.collectionCode"
.", S.catalogNumber, S.previousCatalogNumber, S.relatedCatalogItem, S.collectionNumber"
.", S.collectorName, S.dateCollected, S.notes";
$publicationAttrs = ", P.doi, P.publicationType, P.author, P.publicationTitle, P.month"
.", P.publisher, P.school, P.series, P.note, P.organization, P.institution, P.title, P.volume"
.", P.year, P.isbn, P.issn";
$taxonconceptAttrs = ", TC.nameSpace, TC.status";
$taxonnameAttrs = ", title, comment, xmlData, annotationLabel";
$viewAttrs = ", V.viewName, V.imagingTechnique, V.imagingPreparationTechnique, V.specimenPart"
.", V.viewAngle, V.developmentalStage, V.sex, V.form";
$userAttrs = ", U.name as ownername, U.uin, U.affiliation, US.name as submitter, US.uin as submitterId";

// Fields to use in type-specific keyword harvesting query
$baseObjectFields = $baseObjectAttrs . $userAttrs . $groupsAttrs;
$annotationFields = $baseObjectFields . $annotationAttrs . $specimenAttrs ;
$collectionFields = $baseObjectFields . $collectionAttrs;
$groupsFields = $baseObjectAttrs . $groupsAttrs;
$imageFields = $baseObjectFields . $imageAttrs . $specimenAttrs . $localityAttrs . $viewAttrs;
$localityFields = $baseObjectFields . $localityAttrs;
$publicationFields = $baseObjectFields . $publicationAttrs;
$specimenFields = $baseObjectFields . $specimenAttrs . $localityAttrs;
$taxonconceptFields = $baseObjectFields . $taxonconceptAttrs;
$taxonnameFields = $baseObjectFields . $taxonnameAttrs;
$viewFields = $baseObjectFields . $viewAttrs;
$userFields = $baseObjectFields;

// Joins to use in a type-specific keyword harvesting query
$baseObjectJoin = " BaseObject B"
." left join User U ON B.userId = U.id"
." left join User US ON B.submittedBy = US.id"
." left join Groups G ON G.id = B.groupId";
$annotationJoin = $baseObjectJoin
." left join Annotation A ON A.id = B.id"
." left join DeterminationAnnotation DA ON A.id = DA.annotationId"
." left join Specimen S ON DA.specimenId = S.id";
$collectionJoin = $baseObjectJoin
." left join Collection C ON C.id = B.id";
$groupJoin = " BaseObject B"
." left join  Groups G ON G.id = B.id";
$imageJoin = $baseObjectJoin
." left join Image I ON I.id = B.id"
." left join View V ON I.viewId = V.id"
." left join Specimen S ON I.specimenId = S.id"
." left join Locality L ON S.localityId = L.id";
$localityJoin = $baseObjectJoin
." left join Locality L ON L.id = B.id";
$publicationJoin = $baseObjectJoin
." left join Publication P ON P.id = B.id";
$specimenJoin = $baseObjectJoin
." left join Specimen S ON S.id = B.id"
." left join Locality L ON S.localityId = L.id";
$taxonconceptJoin = $baseObjectJoin
." left join TaxonConcept TC ON TC.id = B.id";
$viewJoin = $baseObjectJoin
." left join View V ON V.id = B.id";
$userJoin = " BaseObject B"
." left join  User U ON U.id = B.id";

// harvest SQL for related objects
$collectionObjectKeywordsSql = "select K.keywords from Keywords K join CollectionObjects C on K.id=C.objectId "
."where C.collectionId = ?";
$externalKeywordsSql = "select label, externalId, description, urlData from ExternalLinkObject where mbId = ?";
$annotationKeywordsSql = "select K.keywords from Keywords K join Annotation A on K.id=A.id "
."where A.objectId = ?";

prepareKeywordQueries();

function getObjectKeywordQuery($objectTypeId){
	global $updateStmt, $taxaUpdateStmt;
	global $SqlUserQuery, $SqlGroupsQuery, $SqlViewQuery, $SqlLocalityQuery,
	$SqlSpecimenQuery, $SqlImageQuery, $SqlCollectionQuery , $SqlAnnotationQuery ,
	$SqlPublicationQuery, $SqlTaxonConceptQuery, $SqlTaxonNameQuery;
	global $baseObjectFields, $baseObjectJoin, $viewFields, $viewJoin, $localityFields, $localityJoin,
	$specimenFields, $specimenJoin, $collectionFields, $collectionJoin, $annotationFields, $annotationJoin,
	$publicationFields, $publicationJoin, $taxonconceptFields, $taxonconceptJoin, $taxonnameFields,
	$imageFields, $imageJoin;

	//$db = connect();

	switch ($objectTypeId) {
		case "User":
			global $SqlUserQuery;
			if ($SqlUserQuery == null) {
				$SqlUserQuery = getKeywordsQuery($baseObjectFields, $baseObjectJoin);
			}
			return $SqlUserQuery;
		case "Groups":
			if ($SqlGroupsQuery == null) {
				$SqlGroupsQuery = getKeywordsQuery($baseObjectFields, $baseObjectJoin);
			}
			return $SqlGroupsQuery;
		case "View":
			if ($SqlViewQuery == null) {
				$SqlViewQuery = getKeywordsQuery($viewFields, $viewJoin);
			}
			return $SqlViewQuery;
		case "Locality":
			if ($SqlLocalityQuery == null) {
				$SqlLocalityQuery = getKeywordsQuery($localityFields, $localityJoin);
			}
			return $SqlLocalityQuery;
		case "Specimen":
			if ($SqlSpecimenQuery == null) {
				$SqlSpecimenQuery = getKeywordsQuery($specimenFields, $specimenJoin);
			}
			return $SqlSpecimenQuery;
		case "Image":
			if ($SqlImageQuery == null) {
				$SqlImageQuery = getKeywordsQuery($imageFields, $imageJoin);
			}
			return $SqlImageQuery;
		case "Collection":
			if ($SqlCollectionQuery == null) {
				$SqlCollectionQuery = getKeywordsQuery($collectionFields, $collectionJoin);
			}
			return $SqlCollectionQuery;
		case "Annotation":
			if ($SqlAnnotationQuery == null) {
				$SqlAnnotationQuery = getKeywordsQuery($annotationFields, $annotationJoin);
			}
			return $SqlAnnotationQuery;
		case "Publication":
			if ($SqlPublicationQuery == null) {
				$SqlPublicationQuery = getKeywordsQuery($publicationFields, $publicationJoin);
			}
			return $SqlPublicationQuery;
		case "TaxonConcept":
			if ($SqlTaxonConceptQuery == null) {
				//TODO will not work because of dependence on $row GR
				$SqlTaxonConceptQuery = getKeywordsQuery($taxonconceptFields, $taxonconceptJoin);
			}
			return $SqlTaxonConceptQuery;
		case "Taxon Name":
			if ($SqlTaxonNameQuery == null) {
				$SqlTaxonNameQuery = "SELECT $taxonnameFields FROM TaxonomicUnits WHERE id = ?";
				break;
			}
			return $SqlTaxonNameQuery;
			//TODO fix these cases
	}
	return null;
}


// Prepare queries and update
function prepareKeywordQueries(){
	global $updateStmt, $keywordsTempStmt, $numCleared, $taxaUpdateStmt;
	global $keywordsTempClearSql, $keywordsBaseObjectUpdateSql, $keywordsUpdateSql;
	global $keywordsMissingSql, $keywordsInsertSql, $keywordsTempParams;
	global $collectionObjectKeywordsSql, $collectionObjectKeywordsStmt;
	global $externalKeywordsSql, $externalKeywordsStmt;
	global $annotationKeywordsSql, $annotationKeywordsStmt;

	// Prepare update
	$db = connect();
	$updateSql = "UPDATE BaseObject SET keywords = ? , imageAltText = ?, xmlKeywords = ? WHERE id = ?";
	$param_types = array('text','text', 'text', 'integer');
	$updateStmt = $db->prepare($updateSql,$param_types);
	if(PEAR::isError($updateStmt)){
		die("prepareKeywordQueries for BaseObject\n".$updateStmt->getUserInfo()." $updateSql\n");
	}

	$keywordsTempClearSql = "delete from KeywordsTemp";

	$keywordsTempSql = "insert into KeywordsTemp(id,keywords,xmlKeywords,imageAltText) values(?,?,?,?)";
	$keywordsTempParams = array('integer','text','text','text');
	$keywordsTempStmt = $db->prepare($keywordsTempSql,$keywordsTempParams);
	isMdb2Error($keywordsTempStmt, $keywordsTempSql);

	$keywordsBaseObjectUpdateSql = "update BaseObject, KeywordsTemp set BaseObject.keywords = KeywordsTemp.keywords,"
	." BaseObject.xmlkeywords = KeywordsTemp.xmlkeywords, BaseObject.imageAltText = KeywordsTemp.imageAltText"
	." where BaseObject.id=KeywordsTemp.id";

	$keywordsUpdateSql = "update Keywords, KeywordsTemp set Keywords.keywords = KeywordsTemp.keywords,"
	." Keywords.xmlkeywords = KeywordsTemp.xmlkeywords "
	." where Keywords.id=KeywordsTemp.id";

	$keywordsMissingSql = "insert into KeywordsTemp (id) select b.id from BaseObject b left join Keywords k on b.id=k.id "
	."where k.id is null";

	$keywordsInsertSql = "INSERT INTO Keywords(id, userId, groupId, dateToPublish, objectTypeId,"
	." keywords, xmlKeywords, submittedBy, dateCreated)"
	." SELECT b.id, userId, groupId, dateToPublish, objectTypeId,"
	." b.keywords, xmlKeywords, submittedBy, dateCreated"
	." FROM BaseObject b join KeywordsTemp t on t.id=b.id";

	$updateTaxaSql = "UPDATE Taxa SET keywords = ? WHERE tsn = ?";
	$param_types = array('text','integer');
	$taxaUpdateStmt = $db->prepare($updateTaxaSql,$param_types);
	if(PEAR::isError($taxaUpdateStmt)){
		die("prepareKeywordQueries for Taxa\n".$taxaUpdateStmt->getUserInfo()." $updateTaxaSql\n");
	}

	$collectionObjectKeywordsStmt = $db->prepare($collectionObjectKeywordsSql);
	isMdb2Error($collectionObjectKeywordsStmt, $collectionObjectKeywordsSql);
	$externalKeywordsStmt = $db->prepare($externalKeywordsSql);
	isMdb2Error($externalKeywordsStmt, $externalKeywordsSql);
	$annotationKeywordsStmt = $db->prepare($annotationKeywordsSql);
	isMdb2Error($annotationKeywordsStmt, $annotationKeywordsSql);
}

// prepare a keyword query
function getKeywordsQuery($fields, $joins){
	$db = connect();
	$sql = "SELECT $fields FROM $joins where B.id = ?";
	$param_types = array('integer');
	$stmt = $db->prepare($sql, $param_types, MDB2_PREPARE_RESULT);
	if(PEAR::isError($stmt)){
		die("getKeywordsQuery for $joins\nError is:".$stmt->getUserInfo()."\n Sql is $sql\n");
	}
	return $stmt;
}


$defaultCreativeCommons = '<a href="http://creativecommons.org/licenses/by-nc-sa/3.0/us/" rel="license">
<img src="http://i.creativecommons.org/l/by-nc-sa/3.0/us/88x31.png" style="border-width: 0pt;" alt="Creative Commons License"/>
</a>';

