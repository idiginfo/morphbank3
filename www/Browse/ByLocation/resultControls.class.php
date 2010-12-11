#-------------------------------------------------------------------------------
# Copyright (c) 2010 Greg Riccardi, Fredrik Ronquist.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Public License v2.0
# which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# 
# Contributors:
#   Fredrik Ronquist - conceptual modeling and interaction design
#   Austin Mast - conceptual modeling and interaction design
#   Greg Riccardi - initial API and implementation
#   Wilfredo Blanco - initial API and implementation
#   Robert Bruhn - initial API and implementation
#   Christopher Cprek - initial API and implementation
#   David Gaitros - initial API and implementation
#   Neelima Jammigumpula - initial API and implementation
#   Karolina Maneva-Jakimoska - initial API and implementation
#   Katja Seltmann - initial API and implementation
#   Stephen Winner - initial API and implementation
#-------------------------------------------------------------------------------
<?php
include_once('filters/localityFilter.class.php');
include_once('filters/sort.class.php');

// Array that store the fields of the browseView table from the DB
$sortByFields = array(array('field' => 'id', 'label' => 'Location Id', 'width' => 40, 'toSort' => true, 'inGet' => 0, 'order' => 'DESC'), array('field' => 'ContinentOcean', 'label' => 'Continent Ocean', 'width' => 40, 'toSort' => true, 'inGet' => 0, 'order' => 'ASC'), array('field' => 'Country', 'label' => 'Country', 'width' => 60, 'toSort' => true, 'inGet' => 0, 'order' => 'ASC'), array('field' => 'locality', 'label' => 'Locality', 'width' => 50, 'toSort' => true, 'inGet' => 0, 'order' => 'ASC'), array('field' => 'latitude', 'label' => 'Latitude', 'width' => 30, 'toSort' => false, 'inGet' => 0, 'order' => 'ASC'), array('field' => 'longitude', 'label' => 'Longitude', 'width' => 30, 'toSort' => false, 'inGet' => 0, 'order' => 'ASC'), array('field' => 'coordinatePrecision', 'label' => 'Coordinate Precision', 'width' => 30, 'toSort' => false, 'inGet' => 0, 'order' => 'ASC'), array('field' => 'minimumElevation', 'label' => 'Minimum Elevation', 'width' => 30, 'toSort' => false, 'inGet' => 0, 'order' => 'ASC'), array('field' => 'maximumElevation', 'label' => 'Maximum Elevation', 'width' => 30, 'toSort' => false, 'inGet' => 0, 'order' => 'ASC'), array('field' => 'minimumDepth', 'label' => 'Minimum Depth', 'width' => 30, 'toSort' => false, 'inGet' => 0, 'order' => 'ASC'), array('field' => 'maximumDepth', 'label' => 'Maximum Depth', 'width' => 30, 'toSort' => false, 'inGet' => 0, 'order' => 'ASC'), array('field' => 'imagesCount', 'label' => 'No. Images', 'width' => 50, 'toSort' => true, 'inGet' => 0, 'order' => 'ASC'));

class resultControls {
	var $filterList = array();

	// Constructor
	function resultControls()
	{
		$this->setupFilters();
	}

	// Class Methods
	function setupFilters() {
		global $config;

		$localityFilter = new localityFilter($config->domain);
		$localityFilter->setIsTheFirst(true);
		$localityFilter->setForItSeft(true);

		global $sortByFields;
		$sortFilter = new sort($config->domain, $sortByFields);

		$this->filterList[0] = $localityFilter;
		$this->filterList[1] = $sortFilter;

		$len = count($this->filterList);
		for ($i = 0; $i < $len; $i++) {
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
            return true;
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
		// java function to process the selection from Browse modules
		$this->echoJSFunctionUpdate();
		echo '<form name="resultControlForm" action="index.php" method="get">';
		$this->displayFilters();
		$resetPageUrl = isset($_GET['pop']) ? 'index.php?pop=yes' : 'index.php';
		echo '<table border="0" width="100%">
        <tr>
          <td align="right">
          <br/>
          <a href="javascript: submitForm(\'2\');" class="button smallButton"><div>Search</div></a>
          <a href="' . $resetPageUrl . '" class="button smallButton"><div>Reset</div></a>
          </td>
        </tr>
      </table>  
      <hr/>';
		// sort
		$this->filterList[1]->display();
		echo '<hr/>';
		if (isset($_GET['pop'])) {
			echo '<table border="0" width="100%">
          <tr>
            <td align="center">
            <a href="' . $config->domain . 'Submit/Location/?pop=yes" class="button mediumButton" title="Add New Locality..." ><div>Add New...</div></a>
          </tr>
        </table>  ';
		}

		// know if popup window
		if ($_GET['pop']) {
			echo '<input name="pop" value="YES" type="hidden" />';
		}
		//know numPerPage
		echo '<input id="numPerPage" name="numPerPage" value="' . $_GET['numPerPage'] . '" type="hidden" />
      <input id="goTo" name="goTo" value="' . $_GET['goTo'] . '" type="hidden" />
      <input id="resetOffset" name="resetOffset" value="' . $_GET['resetOffset'] . '" type="hidden" />
      <input name="submit2" value="' . $_GET['submit2'] . '" type="hidden" />
      <input type="hidden" name="activeSubmit" value="" />
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

	function createSQL($objInfo = null) {
		$sql = 'SELECT K.id as id, continentOcean as ContinentOcean, country as Country,
        locality, longitude, latitude, coordinatePrecision, minimumElevation, maximumElevation, 
        minimumDepth, maximumDepth, imagesCount 
			FROM Keywords K join Locality L on K.id = L.id ';
		//LEFT JOIN ContinentOcean CO ON L.continentOcean = CO.name ';
		//LEFT JOIN Country C ON L.country = C.name ';

		// Where
		if (isset($_GET['submit2'])) {
			//TODO use baseObjectSearch for searching
			//$sql .= " where match(K.keywords, ";
			$where = $this->createWhereContribSpecificKws();
			if ($where != ''){
				$sql .= 'where '.$where;
			}
		}

		// Order by
		// This part it is only with sort.class
		$len = count($this->filterList);
		for ($i = 0; $i < $len; $i++) {
			// only sort
			if ($this->filterList[$i]->getName() == 'sort') {
				$sql .= $this->filterList[$i]->getSqlOrderContribution();
				break;
			}
		}
		return $sql;
	}

	function createWhereContribSpecificKws() {
		$len = count($this->filterList);
		// Get the contribution for each filter to the Where part of the SQL
		$phrase = $this->filterList[0]->getKeywords();
		if ($phrase == null || $phrase == "") return "";
		$array = explode(" ", $phrase);
		$numWords = count($array);
		$phrase = "";
		for ($i = 0; $i <$numWords; $i++) {
			$phrase .= "+".$array[$i]."* ";
		}
		$sql .= " (MATCH (K.keywords) AGAINST ('$phrase' in boolean mode)) ";
		return $sql;
	}
}
// End class resultControls
?>
