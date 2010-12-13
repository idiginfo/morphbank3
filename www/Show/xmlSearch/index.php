<?php

include_once('head.inc.php');


include_once('Show/xmlSearch/xmlFunctions.inc.php');
echo'
<html>
	<head>
		<title>Xml Show</title>
	</head>
<body>';
		$searchString = explode(" ", $_GET['search']);
		
		$numberOfWords = count($searchString);	

		//echo $numberOfWords.'<br />';	
		
	//**************************************************************************//
	//	Sql statement that is built to join the tables necessary to do desired	//
	//	search.  Currently, users can search for contributor names, taxonomic	//
	//	names, developmental stage, sex, viewAngle, and imagingTechnique.		//
	//**************************************************************************//
		
		$sql = 'SELECT DISTINCT Image.id AS imageId '
			  .'FROM Image '
			  .'LEFT JOIN View ON Image.viewId = View.id '
			  .'LEFT JOIN ViewAngle ON ViewAngle.id = View.viewAngleId '
			  .'LEFT JOIN Specimen ON Image.specimenId = Specimen.id '
			  .'LEFT JOIN Sex ON Sex.id = Specimen.sexId '
			  .'LEFT JOIN BaseObject ON BaseObject.id = Image.id '
			  .'LEFT JOIN User ON User.id = BaseObject.userId '
			  .'LEFT JOIN DevelopmentalStage ON (DevelopmentalStage.id = View.developmentalStageId OR DevelopmentalStage.id = Specimen.developmentalStageId) '
			  .'LEFT JOIN ImagingTechnique ON View.imagingTechniqueId = ImagingTechnique.id '
			  .'LEFT JOIN SpecimenPart ON View.specimenPartId = SpecimenPart.id '
			  .'LEFT JOIN Form ON Specimen.formId = Form.id '
			  .'LEFT JOIN TypeStatus ON Specimen.typeStatusId = TypeStatus.id ';
			  //.'LEFT JOIN Location ON specimen.locationId = Location.id '
			  //.'LEFT JOIN Country ON Location.countryId = Country.id '
			 // .'LEFT JOIN ContinentOcean ON Location.continentOceanId = ContinentOcean.id '
			  //.'LEFT JOIN TaxonomicUnits ON TaxonomicUnits.tsn = specimen.tsnId '
			  //.'LEFT JOIN Vernacular ON Vernacular.tsn = specimen.tsnId '
			 
			  
		for ($i = 0; $i < $numberOfWords; $i++)	{
			if ($i == 0){
				$sql .= 'WHERE ';		
			}
			else {
				$sql .= 'AND ';
			}
		/*'(Vernacular_name LIKE \'%'.$searchString[$i].'%\' OR '
			  .'(unit_name1 LIKE \'%'.$searchString[$i].'%\' OR unit_name2 LIKE \'%'.$searchString[$i].'%\' '
			  .' OR unit_name3 LIKE \'%'.$searchString[$i].'%\' OR unit_name4 LIKE \'%'.$searchString[$i].'%\') OR '		  
			  .'Country.name LIKE \'%'.$searchString[$i].'%\' OR '
			  .'ContinentOcean.name LIKE \'%'.$searchString[$i].'%\' OR '	  
			  .'TaxonomicUnits.tsn = \''.$searchString[$i].'\' OR '
			  .'image.id = \''.$searchString[$i].'\' OR '
			  .'Vernacular.vernacular_name LIKE \'%'.$searchString[$i].'%\' OR '*/
			  $sql .= '(ViewAngle.name LIKE \'%'.$searchString[$i].'%\' OR '
					  .'DevelopmentalStage.name LIKE \'%'.$searchString[$i].'%\' OR '
					  .'Sex.name LIKE \''.$searchString[$i].'\' OR '
					  .'Specimen.taxonomicNames LIKE \'%'.$searchString[$i].'%\' OR '
					  .'User.name LIKE \'%'.$searchString[$i].'%\' OR '
					  .'ImagingTechnique.name LIKE \'%'.$searchString[$i].'%\' OR '
					  .'Form.name LIKE \'%'.$searchString[$i].'%\' OR '
					  .'Image.id LIKE \'%'.$searchString[$i].'%\' OR '
					  .'TypeStatus.name LIKE \'%'.$searchString[$i].'%\' OR '
					  .'SpecimenPart.name LIKE \'%'.$searchString[$i].'%\') ';
					  
		}		  	
		
		$sql .= ' AND ( BaseObject.dateToPublish < now() ';
		global $objInfo;
		
		if ($objInfo->getUserId() != NULL)
			$sql .= ' OR BaseObject.userId = '.$objInfo->getUserId().' ';
		if ($objInfo->getUserGroupId() != NULL)
			$sql .= ' OR BaseObject.groupId = '.$objInfo->getUserGroupId().' ';
		
		$sql .= ')';
			
		$result = mysqli_query($link,$sql);
		//echo $sql.'<br /><br />';
	
		
	//**************************************************************************//
	//	If the query produced a valid record set, then seek to the offset of the//
	//	record set and output thumbnails										//											
	//**************************************************************************//	
	//**************************************************************************//
	//	Convert the result set to an array which can be used by thumbs.inc		//
	//	and call the outPutArrayImages() function to output the thumbnails		//											
	//**************************************************************************//	
		//echo '<br />'.$sql.'<br />';
		if ($result) {		
			$totalThumbs = mysql_num_rows($result);
			//echo $totalThumbs;	
			
			if ($totalThumbs >0) {
				for ($i = 0; $i < $totalThumbs; $i++) {
					$imageArray[$i] = mysql_fetch_array($result);
				}
				
				// output xml here
				//echo $totalThumbs.'<br />'.$sql.'<br />';
				for ($i = 0; $i < $totalThumbs; $i++) {
					xmlCheckIfArray ( $imageArray[$i]['imageId'] );
				}
				
			}
			else {
				echo '<strong>No images for specified criteria.</strong>';
				
			}
		}
		else {
			echo '<strong>No images for specified criteria.</strong>';
			
		}

echo '</body></html>';

// Finish with end of HTML


?>
