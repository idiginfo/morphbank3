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

include_once('filters/keywordFilter.class.php');
include_once('filters/tsnFilter.class.php');
include_once('filters/specimenFilter.class.php');
include_once('filters/viewFilter.class.php');
include_once('filters/localityFilter.class.php');
include_once('filters/sort.class.php');

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
		$specimenFilter = new specimenFilter($config->domain);
		$viewFilter = new viewFilter($config->domain);
		$localityFilter = new localityFilter($config->domain);
		// always sort should be the last
		$sortFilter = new sort($config->domain);

		$this->filterList[0] = $keywordFilter;
		$this->filterList[1] = $tsnFilter;
		$this->filterList[2] = $specimenFilter;
		$this->filterList[3] = $viewFilter;
		$this->filterList[4] = $localityFilter;
		$this->filterList[5] = $sortFilter;

		$len = count($this->filterList);
		for ($i = 0; $i < $len; $i++) {
			//$this->filterList[$i]->printValues();
			$this->filterList[$i]->retrieveDataFromGET();
		}
	}

	function echoJSFunctionUpdate() {
		echo '<script language="JavaScript" type="text/javascript">
	<!--
	  function update( child, value, name) {
		if(child == "TSN") {
		document.resultControlForm.tsnKeywords.value=value; 
		return;
		}
		if (child == "Specimen"){
		document.resultControlForm.spKeywords.value=value;
		return;
		}
		if (child == "View") {
		document.resultControlForm.viewKeywords.value=value;
		return;
		}
		if (child == "Location") {
		document.resultControlForm.localityKeywords.value=value;
		return;
		}
	  }
	  function updateTSN (tsn, tsnName) {
		document.resultControlForm.tsnKeywords.value= tsn;
		document.resultControlForm.submit();
	  }
	  
	  function checkEnter(e) { 
		var characterCode = returnKeyCode(e);
	  
		if(characterCode == 13) {
		//if generated character code is equal to ascii 13 (if enter key)
		submitForm(\'2\');
		return false;
		} else {
		return true;
		}
	  }
	  
	  function checkEnterKeyword (e) {
		var characterCode = returnKeyCode(e);
		
		if(characterCode == 13) {
		//if generated character code is equal to ascii 13 (if enter key)
		submitForm(\'1\');
		return false;
		} else {
		return true;
		}
	  }
	  
	  function returnKeyCode(e) {
		var keycode;
		if (window.event) keycode = window.event.keyCode;
		else if (e) keycode = e.which;
		else return false;
		
		return keycode;
	  }
	  
	  function submitForm(activeSubmitValue) {
		document.resultControlForm.activeSubmit.value = activeSubmitValue;
		document.resultControlForm.submit();	  
	  }
	  
	  function sortSubmit() {
		document.resultControlForm.resetOffset.value=\'on\';
		document.resultControlForm.submit();	  
	  }
	  
	  function resetFilters() {
		var myForm = document.resultControlForm; ';

		$tsnFilterVar = isset($_GET['tsnKeywords']) ? $_GET['tsnKeywords'] : "";
		$specimenFilterVar = isset($_GET['spKeywords']) ? $_GET['spKeywords'] : "";
		$viewFilterVar = isset($_GET['viewKeywords']) ? $_GET['viewKeywords'] : "";
		$localityFilterVar = isset($_GET['localityKeywords']) ? $_GET['localityKeywords'] : "";

		echo 'myForm.tsnKeywords.value = \'' . $tsnFilterVar . '\'; ' . 'myForm.spKeywords.value = \'' . $specimenFilterVar . '\'; ' . 'myForm.viewKeywords.value = \'' . $viewFilterVar . '\'; ' . 'myForm.localityKeywords.value = \'' . $localityFilterVar . '\';
	
	  }
	</script>';
	}


	function displayForm() {
		global $config;

		// no good but ...should be static method in filter class ... until php 5
		$this->filterList[1]->echoJSFuntions();
		// java function to process the selection from Browse modules
		$this->echoJSFunctionUpdate();
		echo '<form name="resultControlForm" action="index.php" method="get">';
		$this->displayFilters();
		echo '<table border="0" width="100%">
	  <tr>
		<td align="right">
		<br/>';
		echo '
		<a href="javascript: submitForm(\'2\');" class="button smallButton"><div>Search</div></a>
		<a href="index.php" class="button smallButton"><div>Reset</div></a>	  
		
		</td>
	  </tr>
	</table>  
	<hr/>';
		// sort
		//no good
		$this->filterList[5]->display();
		echo '<hr/>
	  <a href="index.php" class="button mediumButton right"><div>New Search</div></a>
		';
		// know if popup window
		if ($_GET['pop'])
		echo '<input name="pop" value="YES" type="hidden" />';
		//know numPerPage
		$activeSubmit = isset($_GET['activeSubmit']) ? $_GET['activeSubmit'] : 1;

		echo '<input id="numPerPage" name="numPerPage" value="' . $_GET['numPerPage'] . '" type="hidden" />
	<input id="goTo" name="goTo" value="' . $_GET['goTo'] . '" type="hidden" />
	<input id="resetOffset" name="resetOffset" value="' . $_GET['resetOffset'] . '" type="hidden" />
	<input type="hidden" name="activeSubmit" value="' . $activeSubmit . '" />
	
	</form>';
	}

	function displayFilters() {
		$len = count($this->filterList);
		for ($i = 0; $i < $len - 1; $i++) {
			// no the last one (sort)
			$this->filterList[$i]->display();
			//$this->filterList[$i]->printValues();
		}
	}

	function createCountSql($objInfo = null) {
		$sqlSelect  = 'SELECT count(*) as total ';
		$this->filterList[0]->setIsTheFirst( TRUE);
		return $sqlSelect . $this->createFromWhereSql($objInfo);
	}

	function createSQL( $objInfo = NULL) {
		global $objInfo;
		$userId = $objInfo->getUserId() ? $objInfo->getUserId() : 0;
		$groupId = $objInfo->getUserGroupId() ? $objInfo->getUserGroupId() : 0;

		$sqlSelect = 'SELECT Image.id as imageId,
			imageHeight, imageWidth, imageType, accessNum, 
			View.imagingTechnique as imagingTechniqueName,
			View.imagingPreparationTechnique as imagingPreparationTechniqueName,
			View.specimenPart as specimenPartName,
			View.viewName as viewName,
			Specimen.developmentalStage as specimenDevelStageName,
			View.viewAngle as viewAngleName,
			View.form as specimenFormName,
			Specimen.tsnId as tsn,
			Specimen.sex as sexName,
			Tree.scientificName as TaxonName ';		
		return $sqlSelect . $this->createFromWhereSql($objInfo) . ' ' . $this->createOrderBySql($objInfo);
	}

	function createFromWhereSql($objInfo = NULL) {
		global $objInfo, $config;
		$userId = $objInfo->getUserId() ? $objInfo->getUserId() : 0;
		$groupId = $objInfo->getUserGroupId() ? $objInfo->getUserGroupId() : 0;

		$sql = ' FROM Image
			LEFT JOIN Specimen ON Image.specimenId = Specimen.id
			LEFT JOIN View ON Image.viewId = View.id
			LEFT JOIN User ON Image.userId = User.id
			LEFT JOIN BasisOfRecord ON Specimen.basisOfRecordId = BasisOfRecord.name
			LEFT JOIN Locality ON Specimen.localityId = Locality.id
			LEFT JOIN Tree ON Specimen.tsnId = Tree.tsn ';
		
		if ($groupId != $config->adminGroup) $dateToPublish = 'Image.dateToPublish <= CURDATE() ';

		if ($objInfo && $groupId != $config->adminGroup) {
			if ($objInfo->getUserId() != null) {
			  $orSql .= 'Image.userId=\'' . $objInfo->getUserId() . '\' ';
			}
			if ($objInfo->getUserGroupId() != null) {
			  $orSql .= !empty($orSql) ? 'OR ' : '';
			  $orSql .= 'Image.groupId=\'' . $objInfo->getUserGroupId() . '\' ';
			}
		}
	    
		if ($dateToPublish != null && $orSql != null) {
		  $addWhere = '(' . $dateToPublish . ' OR ' . $orSql . ') ';
		} elseif ($dateToPublish == null && $orSql != null) {
		  $addWhere = $orSql;
		} elseif ($dateToPublish != null && $orSql == null) {
		  $addWhere = $dateToPublish;
		}
		
		// Where
		if ((isset($_GET['submit1'])) || ($_GET['activeSubmit'] == 1)) {
		  $genContrib = $this->createWhereContribGeneralKws();
		  $genContrib = empty($addWhere) ? ltrim($genContrib, 'AND') : $genContrib;
		}
		if ((isset($_GET['submit2'])) || ($_GET['activeSubmit'] == 2)) {
		  $genSpec = $this->createWhereContribSpecificKws();
		  $genSpec = (empty($genContrib) && empty($addWhere)) ? ltrim($genSpec, 'AND') : $genSpec;
		}
		$combined = $addWhere . $genContrib . $genSpec;
		$sql .= empty($combined) ? $combined : 'WHERE ' . $combined;

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

	function createWhereContribSpecificKws()
	{
		$sql = '';
		$len = count($this->filterList);
		// Get the contribution for each filter to the Where part of the SQL
		// no general keywords
		for ($i = 1; $i < $len - 1; $i++) {
			$where = $this->filterList[$i]->getSqlWhereContribution();
			if (is_null($where)) {
				return null;
			}
			$sql .= $where;
		}
		return $sql;
	}

	function createWhereContribGeneralKws()
	{
		$sql = '';
		$len = count($this->filterList);
		// Get the contribution for the filter to the Where part of the SQL
		$where = $this->filterList[0]->getSqlWhereContribution();
		if (is_null($where)) {
			return null;
		}
		$sql .= $where;

		return $sql;
	}
}
// End class resultControls
?>
