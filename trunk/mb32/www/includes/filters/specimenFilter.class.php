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

include_once ( "filter.class.php");

class specimenFilter extends filter {
	//fields
	var $whatSearch;
	var $specimenId;
	var $specimenIdLabel;
	var $searchString;
	var $searchStringLabel;

	var $actionUrl;
	var $buttonSrc;

	// Contructor
	function specimenFilter( $myDomain = "http://morphbank2.scs.fsu.edu/", $module="browse") {		
		$this->setDomainName( $myDomain);			
		$this->resetData();
		$this->setModule($module);
	}

	// Class methods...
	function resetData() {
		$this->setTitle("Specimen: ");
		$this->whatSearch = 'keywords';
		$this->specimenId = '';
		$this->specimenIdLabel = 'Id ';
		$this->searchString = '';
		$this->searchStringLabel = 'Keywords ';
		$this->setSize();
		$this->forItseft = FALSE;
		$this->isTheFirst = FALSE;

		$this->actionUrl = $this->domainName.'Browse/BySpecimen/index.php?pop=YES&amp;referer=';
		$this->buttonSrc = $this->domainName.'style/webImages/';
	}

	function printValues() {

		echo "Class name: ".$this->getName()."\n";
		echo "Title = ".$this->getTitle()."\n";
		echo $this->getSpecimenIdLabel()."'".$this->getSpecimenId()."'\n";
		echo $this->getSearchStringLabel()."'".$this->getSearchString()."'\n";
		echo "actionUrl = '$this->actionUrl'\n";
		echo "buttonSrc = '$this->buttonSrc'\n";
	}

	function display() {
		$bigTitle = 'Specimen keywords field queries:<br />'
		.'- Specimen id<br />'
		.'- Sex<br />'
		.'- Form<br />'
		.'- Basis of record<br />'
		.'- Type status<br />'
		.'- Collector name<br />'
		.'- Institution code <br />'
		.'- Collection code <br />'
		.'- Catalog number<br />'
		.'- Taxonomic names';
		$postItContent = htmlentities($bigTitle, ENT_QUOTES, "UTF-8");

		if ($this->module == "browse")
		parent::echoJSFuntions();

		echo '<h3>';
		if (!$this->forItSeft)
		echo $this->getTitle();
		else
		echo $this->getSearchStringLabel();
		echo '</h3>
			<table border="0" width="100%">';
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
					echo'				
				<tr>
					<td '.$tdWidth.'><input type="text" name="spKeywords" value="'.$htmlValue.'" size="'.$this->size.'" 
						onkeypress="return checkEnter(event);" onmouseover="startPostIt( event, \''.$postItContent.'\')" 
						onmouseout="stopPostIt()"/>';
		if ($this->forItSeft) {
			$referer = isset($_GET['referer'])?$_GET['referer']:"browse";
			echo'<input type="hidden" name="referer" value="'.$referer.'" />';
		}
					echo'
					</td>';

		if (!$this->forItSeft) {
			echo '
					<td align="'.$rightOrLeft.'" valign="middle" style="padding-right:10px;">&nbsp;&nbsp;
						<a id="selectSpImg" href="javascript:openPopup(\''.$this->actionUrl.$checkmarkReferer.'\',\'890\',\'600\')" title="Click here to select a Specimen">
							<img src="'.$this->buttonSrc.'selectIcon.png" border="0" alt="Browse" /></a>';
			/*
			 <input type="image" src="'.$this->buttonSrc.'/buttons/reset-trans.png"
			 onclick="javascript:this.form.spKeywords.value=\''.$this->searchString.'\'; return false;" />
			 */
			echo '
					</td>';
		}
				echo '
				</tr>
			</table><br />';
	}

	function getSqlJoinContribution() {

		if (($this->whatSearch == 'keywords') && ($this->searchString != '')) {
			$sql = 'LEFT JOIN BasisOfRecord ON Specimen.basisOfRecordId = BasisOfRecord.name
					LEFT JOIN TypeStatus ON Specimen.typeStatusId = TypeStatus.id ';
			return $sql;
		}
		return '';
	}

	function getSqlWhereContribution() {
		global $objInfo;
		$sql = '';
		/*
		$arrayOfWords = explode(" ", $this->searchString);
			$numberOfWords = count($arrayOfWords);
			for ($i = 0; $i < $numberOfWords; $i++)	{
				$sql .= $this->isTheFirst?'WHERE ':'AND ';
				$sql .= '(DevelopmentalStage.name LIKE \'%'.$arrayOfWords[$i].'%\' OR '
		 		  .'Sex.name LIKE \''.$arrayOfWords[$i].'%\' OR '
		 		  .'Form.name LIKE \'%'.$arrayOfWords[$i].'%\' OR '
				  .'BasisOfRecord.name LIKE \'%'.$arrayOfWords[$i].'%\' OR '
				  .'TypeStatus.name LIKE \'%'.$arrayOfWords[$i].'%\' OR '
				  .'collectorName LIKE \'%'.$arrayOfWords[$i].'%\' OR '
				  .'institutionCode LIKE \'%'.$arrayOfWords[$i].'%\' OR '
				  .'collectionCode LIKE \'%'.$arrayOfWords[$i].'%\' OR '
				  .'taxonomicNames LIKE \'%'.$arrayOfWords[$i].'%\' OR '
				  .'catalogNumber LIKE \'%'.$arrayOfWords[$i].'%\'	OR '
				  .'Specimen.id = "'.$arrayOfWords[$i].'") ';
				$this->isTheFirst = FALSE;
			}
			return $sql;
		*/

		if ( is_numeric($this->searchString) ) {
			$sql .= $this->isTheFirst?'WHERE ':'AND ';
			$sql .= ' (Specimen.id ='.$this->searchString.') ';
			return $sql;
		}
		if ($this->limitCurrent){
			if (is_object($objInfo)){
				$sql .= $this->isTheFirst?'WHERE ':'AND ';
				$sql .= "groupid=".$objInfo->getUserGroupId();
				$this->isTheFirst = false;
			}
		}

		if ($this->searchString != '') {
			$arrayOfWords = parent::returnArrayOfWords($this->searchString);
			$num = count($arrayOfWords);
			for ($i = 0; $i < $num; $i++) {
				$sql .= $this->isTheFirst?'WHERE ':'AND ';
				$sql .= '(Specimen.developmentalStage LIKE \'%'.$arrayOfWords[$i].'%\' OR '
				.'Specimen.sex LIKE \''.$arrayOfWords[$i].'%\' OR '
				.'Specimen.form LIKE \'%'.$arrayOfWords[$i].'%\' OR '
				.'BasisOfRecord.description LIKE \'%'.$arrayOfWords[$i].'%\' OR '
				.'Specimen.typeStatus LIKE \'%'.$arrayOfWords[$i].'%\' OR '
				.'Specimen.collectorName LIKE \'%'.$arrayOfWords[$i].'%\' OR '
				.'Specimen.institutionCode LIKE \'%'.$arrayOfWords[$i].'%\' OR '
				.'Specimen.collectionCode LIKE \'%'.$arrayOfWords[$i].'%\' OR '
				.'Specimen.taxonomicNames LIKE \'%'.$arrayOfWords[$i].'%\' OR '
				.'Specimen.catalogNumber LIKE \'%'.$arrayOfWords[$i].'%\'	) ';
					
				$this->isTheFirst = FALSE;
			}
			return $sql;
		} // end if searchSting != ''
			
		return '';
	}

	function retrieveDataFromGET() {
		if (isset($_GET['submit2']) || ($_GET['activeSubmit'] == 2) ) {
			//$this->whatSearch = $_GET['spId_Kw']!=NULL?$_GET['spId_Kw']:$this->whatSearch;
			$this->setSearchString(trim($_GET['spKeywords']));
		}
	}

	function getSpecimenId() {
		return $this->specimenId;
	}
	function setSpecimenId( $newSpecimenId) {
		$this->specimenId = $newSpecimenId;
	}

	function getSpecimenIdLabel() {
		return $this->specimenIdLabel;
	}
	function setSpecimenIdLabel( $newSpecimenIdLabel) {
		$this->specimenIdLabel = $newSpecimenIdLabel;
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

	/*
	 function sqlContribution () {
		$sql = "";
		if ( $this->specimenId != 'All specimens') {
		$sql .= 'AND specimenId =\''.$specimenId.'\' ';
		//$and ='AND';
		}
		return $sql;
		}
		*/

}// end class specimenFilter

//testing
/*
 $mySpecimenFilter = new specimenFilter();
 $mySpecimenFilter->setSpecimenId('77845');
 $mySpecimenFilter->setSearchString('Female Fredrik');
 $mySpecimenFilter->printValues();
 $mySpecimenFilter->display();
 */
?>
