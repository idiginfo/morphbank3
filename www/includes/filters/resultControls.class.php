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

include_once ( 'filters/keywordFilter.class.php');
include_once ( 'filters/tsnFilter.class.php');
include_once ( 'filters/specimenFilter.class.php');
include_once ( 'filters/viewFilter.class.php');
include_once ( 'filters/localityFilter.class.php');
include_once ( 'filters/sort.class.php');

class resultControls {

	var $filterList = array();
	var $b;
	var $c;
		
	// Constructor
	function resultControls() {
		$this->setupFilters();
	}
	
	// Class Methods
	function setupFilters() {
	    global $config;
		
		$keywordFilter = new keywordFilter($config->domain);
		$tsnFilter = new tsnFilter($config->domain);
		$specimenFilter = new specimenFilter( $config->domain);
		$viewFilter = new viewFilter($config->domain);
		$localityFilter = new localityFilter($config->domain);
		$sortFilter = new sort($config->domain);
				
		$this->filterList[0] = $keywordFilter;
		$this->filterList[1] = $tsnFilter;
		$this->filterList[2] = $specimenFilter;
		$this->filterList[3] = $viewFilter;
		$this->filterList[4] = $localityFilter;
		$this->filterList[5] = $sortFilter;
		
		$len = count($this->filterList);
		for ($i=0; $i < $len; $i++) {
			//$this->filterList[$i]->printValues();
			$this->filterList[$i]->retrieveDataFromGET();
		}		
	}
	
	function displayForm() {
		global $config;
		
		$this->filterList[1]->echoJSFuntions(); // no good but ...should be static method in filter class ... until php 5
		echo '<form action="index.php" method="get">';
		$this->displayFilters();
		echo '<table border="0" width="100%">
				<tr>
					<td align="right">
					<input type="image" name="submit2" src="/style/webImages/buttons/searchButton.png" value="2" />
					</td>
				</tr>
			</table>	
			<hr/>';
		// sort 
		$this->filterList[5]->display();
		echo '<hr/>
			<table border="0" width="100%">
				<tr>
					<td align="right">
					<a  href="index.php">
						<img align="right" src="/style/webImages/buttons/clearButton.png" border="0" alt="clear" /></a>
					</td>
				</tr>
			</table>	';
				
		// know if popup window
		if ($_GET['pop']) 
			echo '<input name="pop" value="YES" type="hidden" />';
		echo '	</form>';
	}
		
	function displayFilters() {
		$len = count($this->filterList);
		for ($i=0; $i < $len-1; $i++) { // no the last one (sort)
			$this->filterList[$i]->display();
			//$this->filterList[$i]->printValues();		
		}
	
	}
	
	function displaySort() {
	
	
	}
	
	function displayPageOptions() {
	
	
	}
	
	function createSQLGeneralKws( $objInfo) {
		
		$sql = 'SELECT convert(image.id, unsigned) AS imageId FROM image ';
				
		// Get contribution for the filter to the Join part of the SQL
		// $sql .= $this->filterList[0]->getSqlJoinContribution();
		
		// Checking user dateToPublish and user info
		$sql .= 'WHERE ';
		$sql .= ' (image.dateToPublish <= CURDATE()';
		if ($objInfo) {
			if ($objInfo->getUserId() != NULL)
				$sql .=' OR image.userId=\''.$objInfo->getUserId().'\''; 
			if ($objInfo-> getUserGroupId() != NULL)
				$sql .= ' OR image.groupId=\''.$objInfo->getUserGroupId().'\'';
		}
		$sql .=') ';
		// Get the contribution for the filter to the Where part of the SQL
		$where = $this->filterList[0]->getSqlWhereContribution();
		if (is_null($where)) {
				return NULL;
		}
		$sql .= $where;
				
		echo $sql;
		return $sql;
	}
	
	function createSQL( $objInfo = NULL) {
		/*, imageHeight, imageWidth, imageType, accessNum, 
			ImagingTechnique.name as imagingTechnique, 
			ImagingPreparationTechnique.name as imagingPreparationTechnique,
		  	SpecimenPart.name as specimenPart, 
		  	Specimen.tsnId as taxonId, 
		  	Sex.name as sex, 
			DevelopmentalStage.name as developmentalStage, 
		  	Form.name as form, 
			ViewAngle.name as viewAngle */

		$sql = 'SELECT image.id as imageId, CONCAT(unit_name1, \' \', unit_name2 ,\' \', unit_name3, \' \', unit_name4) as TaxonName  
		  FROM image 
			LEFT JOIN User ON image.userId = User.id 
		  LEFT JOIN Specimen ON image.specimenId = Specimen.id 
			LEFT JOIN Tree ON Specimen.tsnId = Tree.tsn 
			LEFT JOIN Form ON Specimen.formId = Form.id 
		  LEFT JOIN Sex ON Specimen.sexId = Sex.id 
			LEFT JOIN DevelopmentalStage ON Specimen.developmentalStageId = DevelopmentalStage.id 
			LEFT JOIN BasisOfRecord ON Specimen.basisOfRecordId = BasisOfRecord.id
			LEFT JOIN Location ON Specimen.locationId = Location.id 
			LEFT JOIN TypeStatus ON Specimen.typeStatusId = TypeStatus.id 
		  LEFT JOIN View ON image.viewId = View.id 
		  LEFT JOIN ImagingTechnique ON ImagingTechnique.id = View.imagingTechniqueId 
		  LEFT JOIN ImagingPreparationTechnique ON ImagingPreparationTechnique.id = View.imagingPreparationTechniqueId 
		  LEFT JOIN SpecimenPart ON View.specimenPartId = SpecimenPart.id 
		  LEFT JOIN ViewAngle ON View.viewAngleId = ViewAngle.id ';
		  	
		$sql .= 'WHERE (image.dateToPublish <= CURDATE()';
		if ($objInfo) {
			if ($objInfo->getUserId() != NULL)
				$sql .=' OR image.userId=\''.$objInfo->getUserId().'\''; 
			if ($objInfo-> getUserGroupId() != NULL)
				$sql .= ' OR image.groupId=\''.$objInfo->getUserGroupId().'\'';
		}
		$sql .=') ';

		// Where 
		if ((isset($_GET['submit1'])) || ($_GET['activeSubmit'] == 1))
			$sql .= $this->createWhereContribGeneralKws();
		if ((isset($_GET['submit2'])) || ($_GET['activeSubmit'] == 2)) 
			$sql .= $this->createWhereContribSpecificKws();
	
		// Order by
		// This part it is only with sort.class
		$len = count($this->filterList);
		for ($i=0; $i < $len; $i++) { // no the last one (sort)
			if ($this->filterList[5]->getName() == 'sort') {
				$sql .= $this->filterList[5]->getSqlOrderContribution();
				break;
			}
		}
			
		return $sql;	
	}
	
	function createWhereContribSpecificKws() {
	
		$sql = '';
		$len = count($this->filterList);
		// Get the contribution for each filter to the Where part of the SQL
		for ($i=1; $i < $len-1; $i++) {
			$where = $this->filterList[$i]->getSqlWhereContribution();
			if (is_null($where)) {
				return NULL;
			}
			$sql .= $where;				
		}
		return $sql;
	}
	
	function createWhereContribGeneralKws( ) {
	
		$sql = '';
		$len = count($this->filterList);
		// Get the contribution for the filter to the Where part of the SQL
		$where = $this->filterList[0]->getSqlWhereContribution();
		if (is_null($where)) {
				return NULL;
		}
		$sql .= $where;
				
		return $sql;
	
	}
	
	function createSQLspecificKws( $objInfo) {
				
		//$sql = 'SELECT convert(image.id, unsigned) as imageId FROM image ';
		//$sql .= 'LEFT JOIN Specimen ON image.specimenId = Specimen.id ';
		
		// Get contribution for each filter to the Join part of the SQL
		//$len = count($this->filterList);
		//for ($i=1; $i < $len-1; $i++) {
		//	$sql .= $this->filterList[$i]->getSqlJoinContribution();
		//}
		
		// WHERE
		// Checking user dateToPublish and user info
		$sql = 'WHERE (image.dateToPublish <= CURDATE()';
		if ($objInfo) {
			if ($objInfo->getUserId() != NULL)
				$sql .=' OR image.userId=\''.$objInfo->getUserId().'\''; 
			if ($objInfo-> getUserGroupId() != NULL)
				$sql .= ' OR image.groupId=\''.$objInfo->getUserGroupId().'\'';
		}
		$sql .=') ';
		// Get the contribution for each filter to the Where part of the SQL
		for ($i=1; $i < $len-1; $i++) {
			$where = $this->filterList[$i]->getSqlWhereContribution();
			if (is_null($where)) {
				return NULL;
			}
			$sql .= $where;				
		}
		
		// ORDER
			
				
		return $sql;
	}
	
	
	function createSQL_old( $objInfo = NULL) {
		
		if ((isset($_GET['submit1'])) || ($_GET['activeSubmit'] == 1))
			$sql = $this->createSQLGeneralKws( $objInfo);
		if ((isset($_GET['submit2'])) || ($_GET['activeSubmit'] == 2))
			$sql = $this->createSQLspecificKws( $objInfo);
		
		return $sql;			
	}
	
} // End class resultControls

?>
