<?php
include_once ( 'filters/specimenFilter.class.php');
include_once ( 'filters/sort.class.php');

// Array that store the fields of the browseView table from the DB
$sortByFields = array (
	array(	'field' => 'specimenId', 'label' => 'Specimen Id', 'width' => 40,
		'toSort' => true, 'inGet' => 0, 'order' => 'DESC'),
	array(	'field' => 'BasisOfRecord', 'label' => 'Basis Of Record', 'width' => 40,
		'toSort' => true, 'inGet' => 0, 'order' => 'ASC'),
	array(	'field' => 'Sex', 'label' => 'Sex', 'width' => 60,
		'toSort' => true, 'inGet' => 0, 'order' => 'ASC'),
	array(	'field' => 'Form', 'label' => 'Form', 'width' => 50,
		'toSort' => true, 'inGet' => 0, 'order' => 'ASC'),
	array(	'field' => 'Stage', 'label' => 'Developmental Stage', 'width' => 30,
		'toSort' => true, 'inGet' => 0, 'order' => 'ASC'),
	array(	'field' => 'typeStatus', 'label' => 'Type Status', 'width' => 30,
		'toSort' => true, 'inGet' => 0, 'order' => 'ASC'),
	array(	'field' => 'collectorName', 'label' => 'Collector Name', 'width' => 30,
		'toSort' => true, 'inGet' => 0, 'order' => 'ASC'),
	array(	'field' => 'dateCollected', 'label' => 'Date', 'width' => 30,
		'toSort' => true, 'inGet' => 0, 'order' => 'ASC'),
	array(	'field' => 'taxonomicNames', 'label' => 'Taxonomy', 'width' => 30,
		'toSort' => false, 'inGet' => 0, 'order' => 'ASC'),
	array(	'field' => 'tsnId', 'label' => 'TaxonomyId', 'width' => 30,
		'toSort' => false, 'inGet' => 0, 'order' => 'ASC'),
	array(	'field' => 'imagesCount', 'label' => 'No. Images', 'width' => 50,
		'toSort' => true, 'inGet' => 0, 'order' => 'ASC'),
	array(	'field' => 'country', 'label' => 'Country', 'width' => 30,
		'toSort' => true, 'inGet' => 0, 'order' => 'ASC'),		
	array(	'field' => 'locality', 'label' => 'Locality', 'width' => 30,
		'toSort' => false, 'inGet' => 0, 'order' => 'ASC'),
	array(	'field' => 'thumbUrl', 'label' => 'image', 'width' => 30,
		'toSort' => false, 'inGet' => 0, 'order' => 'ASC')	
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
		
		$specimenFilter = new specimenFilter($config->domain);
		$specimenFilter->setIsTheFirst(true);
		$specimenFilter->setForItSeft(true);
			
		global $sortByFields;
		$sortFilter = new sort($config->domain, $sortByFields);

		$this->filterList[0] = $specimenFilter;
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
						<a href="'.$config->domain.'Submit/Specimen/?pop=yes" class="button mediumButton" title="Add New Specimen..." ><div>Add New...</div></a>
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
		return 'SELECT count(*) as total from Specimen';
	}

	function createSQL( $objInfo = NULL) {
		global $objInfo;
		$userId = $objInfo->getUserId() ? $objInfo->getUserId() : 0;
		$groupId = $objInfo->getUserGroupId() ? $objInfo->getUserGroupId() : 0;

		$sqlSelect  = 'SELECT Specimen.id as specimenId,
				BasisOfRecord.description as BasisOfRecord, 
				Specimen.sex as Sex, 
				Specimen.form as Form, 
				Specimen.developmentalStage as Stage, 
				Specimen.typeStatus as typeStatus, 
				Specimen.collectorName, 
				DATE_FORMAT(Specimen.dateCollected, "%m-%d-%Y") AS dateCollected,
				Specimen.taxonomicNames, 
				Specimen.tsnId, 
				Specimen.imagesCount, 
				BaseObject.thumbUrl,
				Locality.locality as locality, Locality.country as country, 
				BaseObject.dateToPublish,
				BaseObject.userId,
				BaseObject.groupId';
		return $sqlSelect . $this->createFromWhereSql($objInfo) . ' ' . $this->createOrderBySql($objInfo);
	}

	function createFromWhereSql($objInfo = NULL) {
		global $objInfo;
		$userId = $objInfo->getUserId() ? $objInfo->getUserId() : 0;
		$groupId = $objInfo->getUserGroupId() ? $objInfo->getUserGroupId() : 0;

		$sql = ' FROM Specimen ';
		if ($objInfo) {
			if ($userId != 0 && $groupId != 0){
				$sql .= 'INNER JOIN BaseObject ON Specimen.id = BaseObject.id ';
			} else {
				$sql .= 'INNER JOIN BaseObject ON Specimen.standardImageId = BaseObject.id ';
			}
		} else {
			//$sql .= 'INNER JOIN BaseObject ON Specimen.standardImageId = BaseObject.id ';
		}
		$sql .= 'LEFT JOIN BasisOfRecord ON Specimen.basisOfRecordId = BasisOfRecord.name
			LEFT JOIN Locality ON Specimen.localityId = Locality.id ';
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
				$sql .= ' AND ((BaseObject.dateToPublish < NOW() )'.$orSql.'  ) ';
			} else {
				$sql .= ' WHERE ((BaseObject.dateToPublish < NOW()  )'.$orSql.'  ) ';
			}
		} else {
			if (isset($_GET['submit2']) && $this->filterList[0]->searchString != '') {
				$sql .= $this->createWhereContribSpecificKws();
				$sql .= ' AND ((BaseObject.dateToPublish < NOW() AND BaseObject.thumbUrl <> 0 )'.$orSql.'  ) ';
			} else {
				$sql .= ' WHERE ((BaseObject.dateToPublish < NOW() AND BaseObject.thumbUrl <> 0 )'.$orSql.'  ) ';
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
