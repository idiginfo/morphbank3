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

include_once ( 'filters/viewFilter.class.php');
include_once ( 'filters/sort.class.php');

// Array that store the fields of the browseView table from the DB
$sortByFields = array (
array(	'field' => 'id', 'label' => 'View id', 'width' => 40, 'toSort' => true,
		'inGet' => 0, 'order' => 'DESC'),
array(	'field' => 'viewName', 'label' => 'View Name', 'width' => 40, 'toSort' => false,
		'inGet' => 0, 'order' => 'ASC'),
array(	'field' => 'specimenPart', 'label' => 'Specimen Part', 'width' => 60, 'toSort' => true,
		'inGet' => 0, 'order' => 'ASC'),
array(	'field' => 'viewAngle', 'label' => 'Angle', 'width' => 50, 'toSort' => true,
		'inGet' => 0, 'order' => 'ASC'),
array(	'field' => 'sex', 'label' => 'Sex', 'width' => 30, 'toSort' => true,
		'inGet' => 0, 'order' => 'ASC'),
array(	'field' => 'developmentalStage', 'label' => 'Stage', 'width' => 30, 'toSort' => true,
		'inGet' => 0, 'order' => 'ASC'),
array(	'field' => 'form', 'label' => 'Form', 'width' => 30, 'toSort' => true,
		'inGet' => 0, 'order' => 'ASC'),
array(	'field' => 'imagingTechnique', 'label' => 'Imaging', 'width' => 30, 'toSort' => true,
		'inGet' => 0, 'order' => 'ASC'),
array(	'field' => 'imagingPreparationTechnique', 'label' => 'Preparation', 'width' => 30, 'toSort' => true,
		'inGet' => 0, 'order' => 'ASC'),
array(	'field' => 'viewTSN', 'label' => 'Taxon Id', 'width' => 40, 'toSort' => true,
		'inGet' => 0, 'order' => 'ASC'),		
array(	'field' => 'viewTSName', 'label' => 'Taxon name', 'width' => 130, 'toSort' => true,
		'inGet' => 0, 'order' => 'ASC'),
array(	'field' => 'standardImageId', 'label' => 'Image prototype', 'width' => 0, 'toSort' => false,
		'inGet' => 0, 'order' => 'ASC'),
array(	'field' => 'imagesCount', 'label' => 'No. Images', 'width' => 50, 'toSort' => true,
		'inGet' => 0, 'order' => 'ASC')
);

class resultControls {
	var $filterList = array();
	// Constructor
	function resultControls() {
		$this->setupFilters();
	}

	// Class Methods
	function setupFilters() {
		global $config;

		$viewFilter = new viewFilter($config->domain);
		$viewFilter->setIsTheFirst( TRUE);
		$viewFilter->setForItSeft( TRUE);
			
		global $sortByFields;
		$sortFilter = new sort($config->domain, $sortByFields);

		$this->filterList[0] = $viewFilter;
		$this->filterList[1] = $sortFilter;

		$len = count($this->filterList);
		for ($i=0; $i < $len; $i++) {
			$this->filterList[$i]->retrieveDataFromGET();
		}
	}

	function echoJSFunctionUpdate() {
		echo '<script language="JavaScript" type="text/javascript">
			<!--
				function checkEnter(e) { 
					var characterCode;
					if(e && e.which) { //if which property of event object is supported (NN4)
						e = e;
						characterCode = e.which; //character code is contained in NN4 which property
					} else {
						e = event;
						characterCode = e.keyCode; //character code is contained in IE keyCode property
					}
				
					if(characterCode == 13) {
						//if generated character code is equal to ascii 13 (if enter key)
						document.resultControlForm.submit(); //submit the form
						return false;
					} else {
						return true;
					}
				}
			//-->
			</script>';
	}

	function displayForm() {
		global $config;

		//$this->filterList[1]->echoJSFuntions(); // no good but ...should be static method in filter class ... until php 5
		$this->echoJSFunctionUpdate(); // java function to process the selection from Browse modules
		echo '<form name="resultControlForm" action="index.php" method="get">';
		$this->displayFilters();
		$resetPageUrl = isset($_GET['pop'])? 'index.php?pop=yes' : 'index.php';
		echo '<table border="0" width="100%">
				<tr>
					<td align="right">
					<br/>
					<a href="javascript: submitForm(\'2\');" class="button smallButton"><div>Search</div></a>
					<a href="'.$resetPageUrl.'" class="button smallButton"><div>Reset</div></a>
					</td>
				</tr>
			</table>	
			<hr/>';
		// sort
		$this->filterList[1]->display();
		echo '<hr/>';
		if (isset($_GET['pop'])) {
			echo'<table border="0" width="100%">
					<tr>
						<td align="center">
						<a href="'.$config->domain.'Submit/View/?pop=yes" class="button mediumButton" title="Add New View..." ><div>Add New...</div></a>
						</td>
					</tr>
				</table>	';
		}

		// know if popup window
		if ($_GET['pop']) {
			echo '<input name="pop" value="YES" type="hidden" />';
		}
		//know numPerPage
		echo '<input id="numPerPage" name="numPerPage" value="'.$_GET['numPerPage'].'" type="hidden" />
			<input id="goTo" name="goTo" value="'.$_GET['goTo'].'" type="hidden" />
			<input id="resetOffset" name="resetOffset" value="'.$_GET['resetOffset'].'" type="hidden" />
			<input name="submit2" value="'.$_GET['submit2'].'" type="hidden" />
			<input type="hidden" name="activeSubmit" value="" />
		</form>';
	}

	function displayFilters() {
		$len = count($this->filterList);
		for ($i=0; $i < $len-1; $i++) { // no the last one (sort)
			$this->filterList[$i]->display();
			//$this->filterList[$i]->printValues();
		}
	}
	function createCountSql($objInfo = null) {
		$sqlSelect  = 'SELECT count(*) as total ';
		$this->filterList[0]->setIsTheFirst( TRUE);
		if ($this->filterList[0]->searchString != '') {
			return $sqlSelect . $this->createFromWhereSql($objInfo);
		}
		// no filters, count all specimens
		return 'SELECT count(*) as total from View';
	}
	function createSQL( $objInfo = NULL) {
		global $objInfo;
		$userId = $objInfo->getUserId() ? $objInfo->getUserId() : 0;
		$groupId = $objInfo->getUserGroupId() ? $objInfo->getUserGroupId() : 0;
		
		$sql  = 'SELECT View.id as id, View.viewName as viewName, View.standardImageId,
			View.imagesCount as imagesCount, 
			View.imagingTechnique as imagingTechnique,
			View.imagingPreparationTechnique as imagingPreparationTechnique, 
			View.specimenPart as specimenPart,
			View.viewAngle as viewAngle, 
			View.developmentalStage as developmentalStage,
			View.sex as sex, 
			View.form as form, 
			View.viewTSN,
			BaseObject.userId,
			BaseObject.groupId';
		return $sql . $this->createFromWhereSql($objInfo) . ' ' . $this->createOrderBySql($objInfo);
	}

	function createFromWhereSql($objInfo = NULL) {
		global $objInfo;
		$userId = $objInfo->getUserId() ? $objInfo->getUserId() : 0;
		$groupId = $objInfo->getUserGroupId() ? $objInfo->getUserGroupId() : 0;
		$sql = ' FROM View ';
		if ($objInfo) {
			if ($userId != 0 && $groupId != 0){
				$sql .= 'INNER JOIN BaseObject ON View.id = BaseObject.id ';
			} else {
				$sql .= 'INNER JOIN BaseObject ON View.standardImageId = BaseObject.id ';
			}
		} else {
			$sql .= 'INNER JOIN BaseObject ON View.standardImageId = BaseObject.id ';
		}
			
		if ($objInfo) {
			if ($userId != 0 && $groupId != 0){
				$groupArray = $objInfo->getGroupIdArray();
				$size = count($groupArray);
				$orSql .=' OR ( BaseObject.userId='.$userId;
				for ($i=0; $i < $size; $i++) {
					$orSql .= ' OR BaseObject.groupId= '.$groupArray[$i].' ';
				}
				$orSql .= ' ) ';
			}
		}

		if ($objInfo->getUserId() != NULL) {
			// Where
			if (isset($_GET['submit2']) && $this->filterList[0]->searchString != '') {
				$sql .= $this->createWhereContribSpecificKws();
				$sql .= ' AND ((BaseObject.dateToPublish < NOW())'.$orSql.'  ) ';
			} else {
				$sql .= ' WHERE ((BaseObject.dateToPublish < NOW())'.$orSql.'  ) ';
			}
		} else {
			if (isset($_GET['submit2']) && $this->filterList[0]->searchString != '') {
				$sql .= $this->createWhereContribSpecificKws();
				$sql .= ' AND ((BaseObject.dateToPublish < NOW() AND View.standardImageId <> 0 )'.$orSql.'  ) ';
			} else {
				$sql .= ' WHERE ((BaseObject.dateToPublish < NOW() AND View.standardImageId <> 0 )'.$orSql.'  ) ';
			}
		}
		return $sql;
	}

	function createOrderBySql($objInfo){
		// Order by
		// This part it is only with sort.class
		$len = count($this->filterList);
		for ($i=0; $i < $len; $i++) { // only sort
			if ($this->filterList[$i]->getName() == 'sort') {
				$sql .= $this->filterList[$i]->getSqlOrderContribution();
				break;
			}
		}
		return $sql;
	}

	function createWhereContribSpecificKws() {
		$sql = '';
		$len = count($this->filterList);
		// Get the contribution for each filter to the Where part of the SQL
		for ($i=0; $i < $len-1; $i++) { //less sort filter
			$where = $this->filterList[$i]->getSqlWhereContribution();
			if (is_null($where)) {
				return NULL;
			}
			$sql .= $where;
		}
		return $sql;
	}

} // End class resultControls

?>
