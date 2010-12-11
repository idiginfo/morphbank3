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

class publicationFilter extends filter {
	//fields 
	var $whatSearch;
	var $publicationId; 
	var $publicationIdLabel;
	var $searchString;
	var $searchStringLabel;
				
	//var $actionUrl;
	//var $buttonSrc;
	
	// Contructor
	function publicationFilter( $myDomain = "http://morphbank2.scs.fsu.edu/") {		
		$this->setDomainName( $myDomain);			
		$this->resetData();
	}
	
	// Class methods...	
	function resetData() {
		$this->setTitle('Keywords: ');
		$this->whatSearch = 'keywords';
		$this->collectionIdLabel = 'Id ';
		$this->searchString = '';
		$this->searchStringLabel = '';
		$this->setSize();
		$this->forItseft = FALSE;
		$this->isTheFirst = FALSE;
				
		$this->actionUrl = $this->domainName.'Browse/ByPublication/index.php?pop=YES';
		$this->buttonSrc = $this->domainName.'style/webImages/';	
	}
	
	function printValues() {
		echo "Class name: ".$this->getName()."\n";
		echo "Title = ".$this->getTitle()."\n";
		echo $this->getcollectionIdLabel()."'".$this->getcollectionId()."'\n";
		echo $this->getSearchStringLabel()."'".$this->getSearchString()."'\n";
		echo "actionUrl = '$this->actionUrl'\n";
		echo "buttonSrc = '$this->buttonSrc'\n";
	}
	
	function display() {	
		$bigTitle = 'Publication search field queries the following fields:<br /><br />'
					.'- Publication id, Author, Editor, Institution, <br />'
					.'- Title, Publication Type, Organization, Publisher<br />'
					.'- Volume, Year, Series<br />';
		$postItContent = htmlentities($bigTitle, ENT_QUOTES, "UTF-8");
		parent::echoJSFuntions();	
		echo '<h3>'.$this->getTitle().'</h3>
			<table border="0">
				';
		//echo $this->searchStringLabel;
					
		$htmlValue = htmlentities($this->searchString, ENT_QUOTES, "UTF-8");
		
		echo '	<tr>
					<td><input id="keywords" type="text" name="publicationKeywords" value="'.$htmlValue.'" size="'.$this->size.'" 
						onkeypress="checkEnter(event)" onmouseover="startPostIt( event, \''.$postItContent.'\')" 
						onmouseout="stopPostIt()"/>';
					/* Reset value if you change it and don't submit the page.  Button will change it back.
						<a href="javascript:setValueTextField(\'keywords\',\''.$htmlValue.'\')">
							<img src="'.$this->buttonSrc.'buttons/reset-trans.png" alt="reset" /></a> */
					echo '
					</td>
				</tr>
			</table>';
	}
	
	
	
	function getSqlWhereContribution() {
				
		$sql = '';
		if ( is_numeric($this->searchString) ) {
			$sql .= $this->isTheFirst?'WHERE ':'AND ';
			$sql .= '(Publication.id ='.$this->searchString.' OR Publication.year = '.$this->searchString.') ';
			$this->isTheFirst = FALSE;
			return $sql;
		}
		
		elseif ($this->searchString != '') {
			$arrayOfWords = parent::returnArrayOfWords($this->searchString);
			$num = count($arrayOfWords);
			
			for ($i = 0; $i < $num; $i++) {
				$sql .= $this->isTheFirst?'WHERE ':'AND ';
				$sql .= '(Publication.publicationType LIKE \'%'.$arrayOfWords[$i].'%\' OR '
					.'Publication.author LIKE \'%'.$arrayOfWords[$i].'%\' OR '
					.'Publication.publicationTitle LIKE \'%'.$arrayOfWords[$i].'%\' OR '
					.'Publication.editor LIKE \'%'.$arrayOfWords[$i].'%\' OR '
					.'Publication.howPublished LIKE \'%'.$arrayOfWords[$i].'%\' OR '
					.'Publication.institution LIKE \'%'.$arrayOfWords[$i].'%\' OR '
					.'Publication.note LIKE \'%'.$arrayOfWords[$i].'%\' OR '
					.'Publication.organization LIKE \'%'.$arrayOfWords[$i].'%\' OR '
					.'Publication.publisher LIKE \'%'.$arrayOfWords[$i].'%\' OR '
					.'Publication.title LIKE \'%'.$arrayOfWords[$i].'%\' OR '
					.'Publication.volume LIKE \'%'.$arrayOfWords[$i].'%\' OR '
					.'Publication.series LIKE \'%'.$arrayOfWords[$i].'%\') ';
				$this->isTheFirst = FALSE;		
			}
			return $sql;			
		}
			
		return ' ';
	}
	
	function retrieveDataFromGET() {
		
		$this->setSearchString($_GET['publicationKeywords']);
		
	}
	
	function getPublicationId() {
		return $this->collectionId;
	}
	function setPublicationId( $newcollectionId) {
		$this->collectionId = $newcollectionId;
	}
	
	function getPublicationIdLabel() {
		return $this->collectionIdLabel;
	}
	function setPublicationIdLabel( $newcollectionIdLabel) {
		$this->collectionIdLabel = $newcollectionIdLabel;
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

}// end class collectionFilter

//testing 
/*
$mycollectionFilter = new collectionFilter();
$mycollectionFilter->setSearchString('Female Fredrik');
$mycollectionFilter->printValues();
$mycollectionFilter->display();
*/
?>
