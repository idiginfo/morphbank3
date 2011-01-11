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

/**
* Included files:
* config.inc - required to get global variables 
* admin.inc  - Database connectivity with Write privileges otherwise can use sql.inc
* thumbs.inc - Get the path of the image from Accession Number.

* This script updates 'viewName' field of the View table with names from tables as 
* SpecimenPart/ViewAngle/ImagingTechnique/ImagingPreparationTechnique/DevelopmentalStage/
* Sex/Form that have Null or '' values 
*/

  require_once('../configuration/app.server.php');

  $link = AdminLogin();

  $result = mysqli_query($link, "SELECT DISTINCT(id) AS viewId FROM View WHERE viewName = '' ORDER BY id");

  if (!$result) {
     echo "Could not successfully run query ($sql) from DB: " . mysqli_error($link);
        exit;
  }

  if (mysqli_num_rows($result) == 0) {
     echo "No rows found, nothing to print so am exiting";
     exit;
  }

  while($viewarray = mysqli_fetch_array($result)){

  	$sql = 'SELECT View.id, SpecimenPart.name as part, ViewAngle.name as angle, 
		 ImagingTechnique.name as imgtech, ImagingPreparationTechnique.name as imgprep,
		 DevelopmentalStage.name as stage, Sex.name as sex, Form.name as form
		 FROM View, SpecimenPart, ViewAngle, ImagingTechnique, 
		 ImagingPreparationTechnique, DevelopmentalStage, Sex, Form
		 WHERE View.specimenPartId = SpecimenPart.id 
		 AND View.viewAngleId  = ViewAngle.id
		 AND View.imagingTechniqueId = ImagingTechnique.id
		 AND View.imagingPreparationTechniqueId = ImagingPreparationTechnique.id
		 AND View.developmentalStageId  = DevelopmentalStage.id
		 AND View.sexId  = Sex.id
		 AND View.formId  = Form.id
		 AND View.id =' .$viewarray['viewId']. ';';
	
	//echo $sql;

	 $resultset = mysqli_query($link, $sql) or die('Could not run query' .mysqli_error($link));

  	 while($array = mysqli_fetch_array($resultset)){

	 	$viewname = 'UPDATE View SET viewName = "'.$array['part']. '/' .$array['angle']. '/' 
				.$array['imgtech']. '/' .$array['imgprep']. '/' .$array['stage']. '/'
				.$array['sex']. '/' .$array['form']. '" WHERE id = ' .$array['id']. ';';

	 	echo  $viewarray['viewId'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $viewname. '<br>';

	 	$result1 = mysqli_query($link, $viewname);
	}

  }

/*
Update View, SpecimenPart, ViewAngle, ImagingTechnique,
ImagingPreparationTechnique, DevelopmentalStage, Sex, Form 
SET View.viewName = Concat( SpecimenPart.name,'/',
	ViewAngle.name, '/',
	ImagingTechnique.name, '/',
	ImagingPreparationTechnique.name, '/',
	DevelopmentalStage.name, '/',
	Sex.name, '/',
	Form.name)
WHERE View.specimenPartId = SpecimenPart.id 
AND View.viewAngleId  = ViewAngle.id
AND View.imagingTechniqueId = ImagingTechnique.id
AND View.imagingPreparationTechniqueId = ImagingPreparationTechnique.id
AND View.developmentalStageId  = DevelopmentalStage.id
AND View.sexId  = Sex.id
AND View.formId  = Form.id;
*/
?>
