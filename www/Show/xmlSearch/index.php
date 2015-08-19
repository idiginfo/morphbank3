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
			  
		for ($i = 0; $i < $numberOfWords; $i++)	{
			if ($i == 0){
				$sql .= 'WHERE ';		
			}
			else {
				$sql .= 'AND ';
			}
		
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
