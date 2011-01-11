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

function get_arecord($TableName,$KeyName,$id)
{
	global $link;

	if (!$link)
	$link = Adminlogin();

	$Query = "SELECT * FROM ";
	$Query .= $TableName;
	$Query .= " where ".$KeyName;
	$Query .= ' = "'.$id.'"';
	$results = mysqli_query($link, $Query);
	if($results)
	$row = mysqli_fetch_array($results);
	else
	$row=' ' ;
	return $row;
}

function getFamily($tsnId)
{
	// return getTsnNameByRankId($tsnId, 140);
	global $link;

	$results = mysqli_query($link, "select scientificName, parent_tsn, rank_id from Tree where tsn=".$tsnId);
	$row = mysqli_fetch_array($results);
	$parent_tsn = $row['parent_tsn'];
	$rank_id= $row['rank_id'];
	while($rank_id > 139)
	{
		if($rank_id==140)
		return $row['scientificName'];
		$results = mysqli_query($link, "select scientificName, parent_tsn, rank_id from Tree where tsn=".$parent_tsn);
		$row = mysqli_fetch_array($results);
		$parent_tsn = $row['parent_tsn'];
		$rank_id = $row['rank_id'];
	}
	return "";
}

function getallimagedata($id) {
	global $link;
	$sql = 'SELECT Image.id AS imageId, Image.specimenId AS specimenId, View.form AS formName, View.sex AS sex, View.viewName AS viewName, '
	.'View.specimenPart AS specimenPart '
	.'FROM Image INNER JOIN View ON Image.viewId = View.id '
	.'WHERE Image.id = \''.$id.'\' ';

	$result = mysqli_query($link, $sql);

	if (result)
	$imageArray = mysqli_fetch_array($result);

	$sql = 'SELECT Specimen.id AS specimenId, DATE_FORMAT(Specimen.dateCollected, "%Y-%m-%d") AS dateCollected, Specimen.collectorName AS collectorName, '
	.'Specimen.comment AS comment, Specimen.tsnId AS tsnId, Specimen.collectionNumber AS collectionNumber, Specimen.institutionCode AS institutionCode, '
	.'Locality.locality AS locality, ContinentOcean.description AS continentOcean, Country.description AS country '
	.'FROM Specimen LEFT JOIN Locality ON Specimen.localityId = Locality.id '
	.'LEFT JOIN Country ON Locality.country = Country.name '
	.'LEFT JOIN ContinentOcean ON Locality.continentOcean = ContinentOcean.name '
	.'WHERE Specimen.id = '.$imageArray['specimenId'].' ';

	$result = mysqli_query($link, $sql);

	if ($result)
	$specimenArray = mysqli_fetch_array($result);


	$TaxonomicUnits = get_arecord("Tree","tsn",$specimenArray['tsnId']);

	$imagedescription = "<div>ImageId:[".$id."]<br /></div>";
	$imagedescription .= "<div>SpecimenId:[".$imageArray['specimenId']."]<br /></div>";
	$imagedescription .= "<div>Family: ".getFamily($specimenArray['tsnId'])."<br /></div>";

	$imagedescription .= "<div>Taxon Name: ".$TaxonomicUnits['scientificName']."<br /></div>";

	$imagedescription .= "<div>Form: ".$imageArray['formName']."<br /></div>";
	$imagedescription .= "<div>Sex: ".$imageArray['sex']."<br /></div>";

	$locality = $specimenArray['continentOcean']." ".$specimenArray['country']." ".$specimenArray['locality'];
	$len = strlen($locality);
	if ($len > 35)
	$locality = wordwrap($locality, 35, "<br />&nbsp;&nbsp;");

	$imagedescription .= "<div>Locality: ".$locality."<br /></div>";

	$view = str_replace("/", " ", $imageArray['viewName']);

	$len = strlen($view);
	if ($len > 35)
	$view = wordwrap($view, 35, "<br />&nbsp;&nbsp;&nbsp;");

	$imagedescription .= "<div>View: ".$view."<br /></div>";
	$imagedescription .= "<div>Specimen Part: ".$imageArray['specimenPart']."<br /></div>";
	$imagedescription .= "<div>Date Collected: ".$specimenArray['dateCollected']."<br /></div>";
	$imagedescription .= "<div>Collector: ".$specimenArray['collectorName']."<br /></div>";
	$imagedescription .= "<div>Collector Identifier: ".$specimenArray['collectionNumber']."<br /></div>";
	$imagedescription .= "<div>Institution: ".$specimenArray['institutionCode']."<br /></div>";

	$comment = $specimenArray['comment'];

	$len = strlen($comment);

	if ($len > 35)
	$comment = wordwrap($comment, 35, "<br />&nbsp;&nbsp;&nbsp;");

	$imagedescription .= "Comments: ".$comment."<br />";

	return $imagedescription;
}

function getAllAnnotationData($annotationId) {
	global $link;

	$sql1 = "select Annotation.* from Annotation where id=".$annotationId;
	$results1 = mysqli_query($link, $sql1);
	if(!$results1) return "";
	$row1 = mysqli_fetch_array($results1);

	$sql2 = "select BaseObject.*, User.name as name from BaseObject join User on BaseObject.userId=User.id and  BaseObject.id=".$annotationId;
	$results2 = mysqli_query($link, $sql2);
	$row2 = mysqli_fetch_array($results2);

	$imagedescription = "<div>ANNOTATION DATA<BR /></div>";
	$imagedescription .= "<div>Annotation id: [".$row1['id']."]<BR /></div>";
	$imagedescription .= "<div>".$row1['objectTypeId']." id:[".$row1['objectId']."]<BR /></div>";
	$imagedescription .= "<div>Type Annotation: ".$row1['typeAnnotation']."<BR /></div>";
	$imagedescription .= "<div>Title: ".$row1['title']."<BR /></div>";
	$imagedescription .= "<div>Annotation Entered under user: ".$row2['name']."<BR /></div>";

	$sql = "select * from DeterminationAnnotation where annotationId=".$annotationId;
	$results = mysqli_query($link, $sql);
	if(!$results) return $imagedescription;
	$row = mysqli_fetch_array($results);
	$TaxonName = getTsnSpecies($row['tsnId']);


	$imagedescription .= "<div>DETERMINATION DATA<BR /></div>";
	$imagedescription .= "<div>Specimen Id: [".$row['specimenId']."]<BR /></div>";
	$imagedescription .= "<div>Species: ".$TaxonName."<BR /></div>";
	$imagedescription .= "<div>Prefix: ".$row['prefix']."<BR /></div>";
	$imagedescription .= "<div>Suffix: ".$row['suffix']."<BR /></div>";
	$imagedescription .= "<div>Opinion on Id: ".$row['typeDetAnnotation']."<BR /></div>";
	$imagedescription .= "<div>Source of Id: ".$row['sourceOfId']."<BR /></div>";
	$imagedescription .= "<div>Related Collection: [".$row['myCollectionId']."]<BR /></div>";

	return $imagedescription;
}

function getTsnSpecies ($tsn) {
	global $link;

	if($tsn =="") return "Unidentified Taxon Name";

	$sql = "select Tree.*, TaxonUnitTypes.rank_name as rank_name from Tree left join TaxonUnitTypes on ";
	$sql .= 'Tree.rank_id = TaxonUnitTypes.rank_id where tsn='.$tsn;

	$results = mysqli_query($link, $sql);
	if(!results) return;
	$row = mysqli_fetch_array($results);
	$name = $row['scientificName'].' ';
	return $name;
}


function getSpecimenPostit($id) {
	global $link;

	$sql = 'SELECT Specimen.*, Locality.*, Groups.groupName '
	.' FROM Specimen INNER JOIN BaseObject ON Specimen.id = BaseObject.id INNER JOIN Locality ON Specimen.localityId = Locality.id '
	.' INNER JOIN Groups ON BaseObject.groupId = Groups.id '
	.' WHERE Specimen.id = '.$id;

	$spResult = mysqli_query($link, $sql);

	if ($spResult) {
		$specimenArray = mysqli_fetch_array($spResult);
	}

	$sql = 'SELECT count(*) as count FROM DeterminationAnnotation WHERE specimenId='.$id;

	$annResult = mysqli_query($link, $sql);

	if ($annResult) {
		$annotationArray = mysqli_fetch_array($annResult);
	}

	$taxonName = get_arecord("Tree","tsn",$specimenArray['tsnId']);

	$specimenDescription = '<div>SpecimenId:['.$id.']<br /></div>';
	$specimenDescription .= '<div>Sex:['.$specimenArray['sex'].']<br /></div>';
	$specimenDescription .= '<div>Taxon Name:['.$taxonName['scientificName'].']<br /></div>';
	$specimenDescription .= '<div>Family:['.getFamily($specimenArray['tsnId']).']<br /></div>';
	$specimenDescription .= '<div>Form:['.$specimenArray['form'].']<br /></div>';
	$specimenDescription .= '<div>Developmental Stage:['.$specimenArray['developmentalStage'].']<br /></div>';
	$specimenDescription .= '<div>Type:['.$specimenArray['typeStatus'].']<br /></div>';
	$specimenDescription .= '<div>Institution:['.$specimenArray['institution'].']<br /></div>';
	$specimenDescription .= '<div>Collection Code:['.$specimenArray['collectionCode'].']<br /></div>';
	$specimenDescription .= '<div>Date Collected:['.$specimenArray['dateCollected'].']<br /></div>';
	$specimenDescription .= '<div>Locality:['.$specimenArray['locality'].']<br /></div>';
	$specimenDescription .= '<div>Group:['.$specimenArray['groupName'].']<br /><br /></div>';

	$specimenDescription .= '<div>No Determinationo Annotations:['.$annotationArray['count'].']<br /></div>';

	return $specimenDescription;
}


function getViewPostit($id) {
	global $link;

	$sql = 'SELECT * FROM View '
	.' WHERE id = '.$id;

	$result = mysqli_query($link, $sql);

	if ($result) {
		$viewArray = mysqli_fetch_array($result);
	}


	$viewDescription = '<div>ViewId:['.$id.']<br /></div>';
	$viewDescription .= '<div>Sex:['.$viewArray['sex'].']<br /></div>';
	$viewDescription .= '<div>Specimen Part:['.$viewArray['specimenPart'].']<br /></div>';
	$viewDescription .= '<div>View Angle:['.$viewArray['viewAngle'].']<br /></div>';
	$viewDescription .= '<div>Form:['.$viewArray['form'].']<br /></div>';
	$viewDescription .= '<div>Imaging Technique:['.$viewArray['imagingTechnique'].']<br /></div>';
	$viewDescription .= '<div>Preparation Technique:['.$viewArray['imagingPreparationTechnique'].']<br /></div>';
	$viewDescription .= '<div>Images Using View:['.$viewArray['imagesCount'].']<br /></div>';

	return $viewDescription;
}


function getLocalityPostit($id) {
	global $link;

	$sql = 'SELECT Locality.*, ContinentOcean.description as continent, Country.description as country FROM Locality INNER JOIN ContinentOcean ON Locality.continentOcean = ContinentOcean.name '
	.' INNER JOIN Country ON Locality.country = Country.name WHERE Locality.id = '.$id;

	$result = mysqli_query($link, $sql);

	if ($result) {
		$localityArray = mysqli_fetch_array($result);
	}


	$localityDescription = '<div>LocalityId:['.$id.']<br /></div>';
	$localityDescription .= '<div>Locality:['.$localityArray['locality'].']<br /></div>';
	$localityDescription .= '<div>Continent:['.$localityArray['continent'].']<br /></div>';
	$localityDescription .= '<div>Country:['.$localityArray['country'].']<br /></div>';
	$localityDescription .= '<div>Latitude:['.$localityArray['latitude'].']<br /></div>';
	$localityDescription .= '<div>Longitude:['.$localityArray['longitude'].']<br /></div>';
	$localityDescription .= '<div>Min. Elevation(m):['.$localityArray['minimumElevation'].']<br /></div>';
	$localityDescription .= '<div>Max. Elevation(m):['.$localityArray['maximumElevation'].']<br /></div>';
	$localityDescription .= '<div>Min. Depth(m):['.$localityArray['minimumDepth'].']<br /></div>';
	$localityDescription .= '<div>Max. Depth(m):['.$localityArray['maximumDepth'].']<br /><br /></div>';

	$localityDescription .= '<div>Images Using Locality:['.$localityArray['imagesCount'].']<br /></div>';

	return $localityDescription;
}


function getCollectionPostit($id) {
	global $link;

	$sql = 'SELECT BaseObject.*, Groups.groupName FROM BaseObject INNER JOIN Groups ON BaseObject.groupId = Groups.id WHERE BaseObject.id = '.$id.' AND BaseObject.objectTypeId = "Collection" ';

	$result = mysqli_query($link, $sql);

	if ($result) {
		$collectionArray = mysqli_fetch_array($result);
	}


	$collectionDescription = '<div>CollectionId:['.$id.']<br /></div>';
	$collectionDescription .= '<div>Collection Title:['.$collectionArray['name'].']<br /></div>';
	$collectionDescription .= '<div>Date To Publish:['.$collectionArray['dateToPublish'].']<br /></div>';
	$collectionDescription .= '<div>Group:['.$collectionArray['groupName'].']<br /><br /></div>';

	$collectionDescription .= '<div>Object Count:['.getCollectionCount($id).']<br /></div>';

	return $collectionDescription;
}

function getCharacterPostit($id) {
	global $link;

	$sql = 'SELECT MbCharacter.*, BaseObject.*, Groups.groupName FROM MbCharacter INNER JOIN BaseObject ON MbCharacter.id = BaseObject.id INNER JOIN Groups ON BaseObject.groupId = Groups.id '
	.' WHERE MbCharacter.id ='.$id;

	$result = mysqli_query($link, $sql);

	if ($result) {
		$characterArray = mysqli_fetch_array($result);
	}


	$characterDescription = '<div>CharacterId:['.$id.']<br /></div>';
	$characterDescription .= '<div>Character Title:['.$characterArray['name'].']<br /></div>';
	$characterDescription .= '<div>Label:['.$characterArray['label'].']<br /></div>';
	$characterDescription .= '<div>Description:['.$characterArray['description'].']<br /></div>';
	$characterDescription .= '<div>Date To Publish:['.$characterArray['dateToPublish'].']<br /></div>';
	$characterDescription .= '<div>Group:['.$characterArray['groupName'].']<br /><br /></div>';

	$characterDescription .= '<div>State Count:['.getCollectionCount($id).']<br /></div>';

	return $characterDescription;
}

function getAnnotationPostit($id) {
	global $link;

	$sql = 'SELECT Annotation.*, DeterminationAnnotation.*, BaseObject.*, Groups.groupName FROM Annotation LEFT JOIN DeterminationAnnotation ON Annotation.id = DeterminationAnnotation.annotationId '
	.' LEFT JOIN BaseObject ON Annotation.id = BaseObject.id LEFT JOIN Groups ON BaseObject.groupId = Groups.id '
	.' WHERE Annotation.id ='.$id;

	$result = mysqli_query($link, $sql);

	if ($result) {
		$annotationArray = mysqli_fetch_array($result);
	}


	$annotationDescription = '<div>AnnotationId:['.$id.']<br /></div>';
	$annotationDescription .= '<div>Title:['.$annotationArray['title'].']<br /></div>';
	$annotationDescription .= '<div>Annotation Type:['.$annotationArray['typeAnnotation'].']<br /></div>';
	$annotationDescription .= '<div>Object Type:['.$annotationArray['objectTypeId'].']<br /></div>';
	$annotationDescription .= '<div>Comments:['.$annotationArray['comment'].']<br /></div>';
	$annotationDescription .= '<div>Alternative Taxon Name:['.$annotationArray['altTaxonName'].']<br /></div>';
	$annotationDescription .= '<div>Date To Publish:['.$annotationArray['dateToPublish'].']<br /></div>';
	$annotationDescription .= '<div>Group:['.$annotationArray['groupName'].']<br /><br /></div>';

	return $annotationDescription;
}


function getPublicationPostit($id) {
	global $link;

	$sql = 'SELECT * FROM Publication '
	.' WHERE Publication.id ='.$id;

	$result = mysqli_query($link, $sql);

	if ($result) {
		$publicationArray = mysqli_fetch_array($result);
	}


	$publicationDescription = '<div>PublicationId:['.$id.']<br /></div>';
	$publicationDescription .= '<div>Author:['.$publicationArray['author'].']<br /></div>';
	$publicationDescription .= '<div>Year:['.$publicationArray['year'].']<br /></div>';
	$publicationDescription .= '<div>Title:['.$publicationArray['title'].']<br /></div>';
	$publicationDescription .= '<div>Publication Title:['.$publicationArray['publicationTitle'].']<br /></div>';
	$publicationDescription .= '<div>Publication Type:['.$publicationArray['publicationType'].']<br /></div>';
	$publicationDescription .= '<div>Volume:['.$publicationArray['volume'].']<br /></div>';
	$publicationDescription .= '<div>Pages:['.$publicationArray['pages'].']<br /></div>';

	return $publicationDescription;
}

function getTaxonConceptPostit($id) {
	global $link;

	$sql = 'SELECT Taxa.*,  DATE_FORMAT(BaseObject.dateToPublish, \'%Y-%m-%d\') AS dateToPublish, NOW() as now FROM Taxa INNER JOIN BaseObject ON Taxa.boId = BaseObject.id '
	.' WHERE Taxa.boId ='.$id;

	$result = mysqli_query($link, $sql);

	if ($result) {
		$taxonArray = mysqli_fetch_array($result);
	}

	$published = ($taxonArray['dateToPublish'] > $taxonArray['now']) ? 'Not Published (Private)' : 'Public';


	$taxonDescription = '<div>TaxonConceptId:['.$id.']<br /></div>';
	$taxonDescription .= '<div>Tsn:['.$taxonArray['tsn'].']<br /></div>';
	$taxonDescription .= '<div>Scientific Name:['.$taxonArray['scientificName'].']<br /></div>';
	$taxonDescription .= '<div>Family:['.getFamily($taxonArray['tsn']).']<br /></div>';
	$taxonDescription .= '<div>Parent:['.$taxonArray['parent_name'].']<br /></div>';
	$taxonDescription .= '<div>Rank:['.$taxonArray['rank_name'].']<br /></div>';
	$taxonDescription .= '<div>Taxon Author:['.$taxonArray['taxon_author_name'].']<br /></div>';
	$taxonDescription .= '<div>Name Type:['.$taxonArray['nameType'].']<br /></div>';
	$taxonDescription .= '<div>Name Source:['.$taxonArray['nameSource'].']<br /></div>';
	$taxonDescription .= '<div>Name Status:['.$taxonArray['status'].']<br /></div>';
	$taxonDescription .= '<div>Pulished Status:['.$published.']<br /></div>';

	return $taxonDescription;
}

