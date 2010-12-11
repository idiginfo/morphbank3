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
include_once ( "filter.class.php");

class tsnFilter extends filter {
	//fields 
	var $whatSearch;
	var $tsnId;
	var $tsnIdLabel;
	var $searchString;
	var $searchStringLabel;
	
	var $leftAndRight; // private
	var $actionUrl;
	var $buttonSrc;
	
	
	// Contructor
	function tsnFilter( $myDomain = "http://morphbank2.scs.fsu.edu/", $module="browse") {		
		$this->setDomainName( $myDomain);	
		$this->setModule($module);		
		$this->resetData();
	}
	
	// Class methods...	
	function resetData() {
		$this->setTitle("Taxon: ");
		$this->tsnId = '';
		$this->whatSearch = 'keywords';
		$this->tsnIdLabel = 'Id ';
		$this->searchString = '';
		$this->searchStringLabel = 'Keywords ';
		$this->setSize();
		
		$this->leftAndRight = NULL;
		$this->actionUrl = $this->domainName.'Admin/TaxonSearch/index.php?pop=yes&amp;searchonly=0&amp;browse=1';
		$this->buttonSrc = $this->domainName.'style/webImages/';	
	}
	
	function printValues() {
	
		echo "Class name: ".$this->getName()."\n";
		echo "Title = ".$this->getTitle()."\n";
		echo "Search for :".$this->getWhatSearch()."\n";
		echo $this->getSearchStringLabel()."'".$this->getSearchString()."'\n";
		echo "actionUrl = '$this->actionUrl'\n";
		echo "buttonSrc = '$this->buttonSrc'\n";
	}
	
	function display() {	
		$bigTitle = 'Taxon keywords field queries:<br/>'
					.'- Taxonomic names<br />'
					.'- Taxonomic Serial Number (Tsn) ';
		$postItContent = htmlentities($bigTitle, ENT_QUOTES, "UTF-8");	
		echo '<h3>'.$this->getTitle().'</h3><br />
				<table border="0" width="100%">';
				$htmlValue = htmlentities($this->searchString, ENT_QUOTES, "UTF-8");
				if ($this->fromSearch($_SERVER['PHP_SELF'])) {
					$rightOrLeft = "left";
					$tdWidth = ' width="400" ';
				
				} else {
					$rightOrLeft = "right";
					$tdWidth = ' ';				
				}
				echo '
				<tr>
					<td valign="middle" '.$tdWidth.'><input type="text" name="tsnKeywords" value="'.$htmlValue.'" size="'.$this->size.'" 
						onkeypress="checkEnter(event)" onmouseover="startPostIt( event, \''.$postItContent.'\')" 
						onmouseout="stopPostIt()" /></td>';
				if (!$this->forItSeft) {
					echo '
					<td align="'.$rightOrLeft.'" valign="middle" style="padding-right:10px;">&nbsp;&nbsp;				
						<a id="selectTsnImg" href="javascript:openPopup(\''.$this->actionUrl.'\')" title="Click here to select a Taxon">
							<img src="'.$this->buttonSrc.'selectIcon.png" border="0" alt="Browse" /></a>'; // copy of code above from wilfredo's version
					/*
						<input type="image" src="'.$this->buttonSrc.'/buttons/reset-trans.png" 
							onclick="javascript:this.form.tsnKeywords.value=\''.$this->searchString.'\'; return false;" />
					*/
					echo '
					</td>';
				}
				echo '
				</tr>
			</table><br />';
	}
	
	function getSqlJoinContribution() {
		/*
		if (($this->whatSearch == 'id') && ($this->searchString != '')) {
			return 'LEFT JOIN Tree ON Specimen.tsnId = Tree.tsn ';
		}
		*/
		if ($this->searchString != '') {
			return 'LEFT JOIN Tree ON Specimen.tsnId = Tree.tsn ';
		}
		 
		return "";		
	}
	
	function getSqlWhereContribution() {
		global $link;
		
		$sql = '';
		if ( is_numeric($this->searchString) ) {
			$sqlTsn = 'SELECT lft,rgt FROM `Tree` WHERE tsn = '.$this->searchString;
			$result = mysqli_query($link, $sqlTsn);
			if (mysqli_num_rows($result) == 0) return NULL;
			$this->leftAndRight = mysqli_fetch_array($result);
			$sql .= 'AND lft >='.$this->leftAndRight['lft'].' ';
			$sql .= 'AND rgt <='.$this->leftAndRight['rgt'].' ';
			return $sql;
		}
		elseif ($this->searchString != '') {
			$arrayOfWords = parent::returnArrayOfWords($this->searchString);
			$num = count($arrayOfWords);
			
			for ($i = 0; $i < $num; $i++) 
				$sql .= 'AND (Specimen.taxonomicNames LIKE \'%'.$arrayOfWords[$i].'%\') ';
			
			return $sql;
		}
		
		return $sql;
	}	
	
	
	function retrieveDataFromGET() {
		if (isset($_GET['submit2']) || ($_GET['activeSubmit'] == 2) ) {
			$this->whatSearch = $_GET['tsnId_Kw'];
			$this->setSearchString(trim($_GET['tsnKeywords']));
		}
	}
	
	function getTsnId() {
		return $this->tsnId;
	}
	function setTsnId( $newTsnId) {
		$this->tsnId = $newTsnId;
	}
	
	function getWhatSearch() {
		return $this->whatSearch;
	}
	function setWhatSearch( $newWhatSearch) {
		$this->whatSearch = $newWhatSearch;
	}
	
	function getTsnIdLabel() {
		return $this->tsnIdLabel;
	}
	function setTsnIdLabel( $newTsnIdLabel) {
		$this->tsnIdLabel = $newTsnIdLabel;
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

}// end class tsnFilter

//testing 
/*
$myTsnFilter = new tsnFilter();
$myTsnFilter->setSearchString('Female Fredrik');
$myTsnFilter->printValues();
$myTsnFilter->display();
*/
?>
