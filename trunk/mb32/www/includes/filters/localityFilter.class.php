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
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

include_once ( "filter.class.php");

class localityFilter extends filter {
	//fields
	var $whatSearch;
	var $localityId;
	var $localityIdLabel;
	var $searchString;
	var $searchStringLabel;

	var $actionUrl;
	var $buttonSrc;

	// Contructor
	function localityFilter( $myDomain = "http://morphbank2.scs.fsu.edu/", $module="browse") {
		$this->setDomainName( $myDomain);
		$this->resetData();
		$this->setModule($module);
	}

	// Class methods...
	function resetData() {
		$this->setTitle('Locality: ');
		$this->whatSearch = 'keywords';
		$this->localityIdLabel = 'Id ';
		$this->searchString = '';
		$this->searchStringLabel = 'Keywords ';
		$this->setSize();
		$this->forItseft = FALSE;
		$this->isTheFirst = FALSE;

		$this->actionUrl = $this->domainName.'Browse/ByLocation/index.php?pop=YES&amp;referer=';
		$this->buttonSrc = $this->domainName.'style/webImages/';
	}

	function printValues() {
		echo "Class name: ".$this->getName()."\n";
		echo "Title = ".$this->getTitle()."\n";
		echo $this->getLocalityIdLabel()."'".$this->getLocalityId()."'\n";
		echo $this->getSearchStringLabel()."'".$this->getSearchString()."'\n";
		echo "actionUrl = '$this->actionUrl'\n";
		echo "buttonSrc = '$this->buttonSrc'\n";
	}

	function display() {
		$bigTitle = 'Locality keywords field queries:<br />'
		.'- Locality id<br />'
		.'- Locality<br />'
		.'- Continent/Ocean<br />'
		.'- Country<br />';
		$postItContent = htmlentities($bigTitle, ENT_QUOTES, "UTF-8");

		if ($this->module == "browse")
		parent::echoJSFuntions();
			
		echo '<h3>';
		if (!$this->forItSeft)
		echo $this->getTitle();
		else
		echo $this->getSearchStringLabel();
		echo '</h3><table border="0" width="100%">';

		$htmlValue = htmlentities($this->searchString, ENT_QUOTES, "UTF-8");
		if ($this->fromSearch($_SERVER['PHP_SELF'])) {
			$rightOrLeft = "left";
			$tdWidth = ' width="400" ';
			$checkmarkReferer = "search";
				
		} elseif ($this->fromBrowse($_SERVER['PHP_SELF'])) {
			$rightOrLeft = "right";
			$tdWidth = ' ';
			$checkmarkReferer = "browse";
		} else {
			$rightOrLeft = "right";
			$tdWidth = ' ';
			$checkmarkReferer = "";
		}

		echo '<tr>
				<td '.$tdWidth.'><input type="text" name="localityKeywords" value="'.$htmlValue.'" size="'.$this->size.'" 
					onkeypress="checkEnter(event)" onmouseover="startPostIt( event, \''.$postItContent.'\')" 
					onmouseout="stopPostIt()" />';
		if ($this->forItSeft) {
			$referer = isset($_GET['referer'])?$_GET['referer']:"browse";
			echo'<input type="hidden" name="referer" value="'.$referer.'" />';
		}
		echo'</td> ';
		if (!$this->forItSeft) {
			echo '<td align="'.$rightOrLeft.'" valign="middle" style="padding-right:10px;">&nbsp;&nbsp;
					<a id="selectLocImg" href="javascript:openPopup(\''.$this->actionUrl.$checkmarkReferer.'\',\'890\',\'600\')" title="Click here to select a Locality">
						<img src="'.$this->buttonSrc.'selectIcon.png" border="0" alt="Browse" /></a></td>';
		}
		echo '</tr></table><br />';
	}

	function getSqlJoinContribution() {

		if (($this->whatSearch == 'id') && ($this->searchString != '')) {
			$sql = 'LEFT JOIN Location ON Specimen.localityId = Locality.id ';
			return $sql;
		}

		if (($this->whatSearch == 'keywords') && ($this->searchString != '')) {
			$sql = 'LEFT JOIN Location ON Specimen.localityId = Locality.id
					LEFT JOIN ContinentOcean ON Locality.continentOcean = ContinentOcean.name 
					LEFT JOIN Country ON Locality.country = Country.name ';
			return $sql;
		}
		return '';
	}

	function getKeywords(){
		return $this->searchString;
	}

	function getSqlWhereContribution() {

		$sql = '';
		if ( is_numeric($this->searchString) ) {
			$sql .= $this->isTheFirst?'WHERE ':'AND ';
			$sql .= ' (Locality.id ='.$this->searchString.') ';
			return $sql;
		}

		elseif ($this->searchString != '') { // if searchString isn't blank
			$arrayOfWords = parent::returnArrayOfWords($this->searchString);
			$num = count($arrayOfWords);
				
			for ($i = 0; $i < $num; $i++) {
				$sql .= $this->isTheFirst?'WHERE ':'AND ';
				$sql .= '(Locality.locality LIKE \'%'.$arrayOfWords[$i].'%\' OR '
				.'ContinentOcean.description LIKE \'%'.$arrayOfWords[$i].'%\' OR '
				.'Country.description LIKE \'%'.$arrayOfWords[$i].'%\' ) ';
				$this->isTheFirst = FALSE;
			}
			return $sql;
		}
			
		return '';
	}

	function retrieveDataFromGET() {
		if (isset($_GET['submit2']) || ($_GET['activeSubmit'] == 2) ) {
			//$this->whatSearch = $_GET['localityId_Kw']!=NULL?$_GET['localityId_Kw']:$this->whatSearch;
			$this->setSearchString(trim($_GET['localityKeywords']));
		}
	}

	function getLocalityId() {
		return $this->localityId;
	}
	function setLocalityId( $newLocalityId) {
		$this->localityId = $newLocalityId;
	}

	function getLocalityIdLabel() {
		return $this->localityIdLabel;
	}
	function setLocalityIdLabel( $newLocalityIdLabel) {
		$this->localityIdLabel = $newLocalityIdLabel;
	}

	function getSearchString() {
		return $this->searchString;
	}
	function setSearchString( $newSearchString = "") {
		$this->searchString = $newSearchString;
	}

	function getSearchStringLabel() {
		return $this->searchStringLabel;
	}
	function setSearchStringLabel( $newSearchStringLabel = "") {
		$this->searchStringLabel = $newSearchStringLabel;
	}

}// end class localityFilter

//testing
/*
 $myLocalityFilter = new localityFilter();
 $myLocalityFilter->setSearchString('Female Fredrik');
 $myLocalityFilter->printValues();
 $myLocalityFilter->display();
 */
?>
