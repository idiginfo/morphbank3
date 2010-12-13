<?php
include_once ( "filter.class.php");

class collectionFilter extends filter {
	//fields
	var $whatSearch;
	var $collectionId;
	var $collectionIdLabel;
	var $searchString;
	var $searchStringLabel;

	//var $actionUrl;
	//var $buttonSrc;

	// Contructor
	function collectionFilter( $myDomain = "http://morphbank2.scs.fsu.edu/") {
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

		$this->actionUrl = $this->domainName.'Browse/ByCollection/index.php?pop=YES';
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
		$bigTitle = 'Collection search field queries the following fields:<br />'
		.'- Collection id<br />'
		.'- Collection name<br />'
		.'- User name<br />'
		.'- Group name<br />';
		$postItContent = htmlentities($bigTitle, ENT_QUOTES, "UTF-8");
		parent::echoJSFuntions();
		echo '<h3>'.$this->getTitle().'</h3><table border="0">';
		$htmlValue = htmlentities($this->searchString, ENT_QUOTES, "UTF-8");
		echo '<tr><td><input id="keywords" type="text" name="collectionKeywords" value="'.$htmlValue.'" size="'.$this->size.'"
				onkeypress="checkEnter(event)" onmouseover="startPostIt( event, \''.$postItContent.'\')" 
				onmouseout="stopPostIt()"/>
			</td></tr></table>';
	}

	function getSqlWhereContribution() {

		$sql = '';
		if ( is_numeric($this->searchString) ) {
			$sql .= $this->isTheFirst?'WHERE ':'AND ';
			$sql .= '(BaseObject.id =\''.$this->searchString.'\') ';
			$this->isTheFirst = FALSE;
			return $sql;
		}

		elseif ($this->searchString != '') {
			$arrayOfWords = parent::returnArrayOfWords($this->searchString);
			$num = count($arrayOfWords);
				
			for ($i = 0; $i < $num; $i++) {
				$sql .= $this->isTheFirst?'WHERE ':'AND ';
				$sql .= '(User.name LIKE \'%'.$arrayOfWords[$i].'%\' OR '
				.'BaseObject.name LIKE \'%'.$arrayOfWords[$i].'%\' OR '
				.'Groups.groupName LIKE \'%'.$arrayOfWords[$i].'%\') ';
				$this->isTheFirst = FALSE;
			}
			return $sql;
		}
			
		return ' ';
	}

	function retrieveDataFromGET() {
		if (isset($_GET['submit2'])) {
			$this->whatSearch = $_GET['collectionId_Kw']!=NULL?$_GET['collectionId_Kw']:$this->whatSearch;
			$this->setSearchString($_GET['collectionKeywords']);
		}
	}

	function getCollectionId() {
		return $this->collectionId;
	}
	function setCollectionId( $newcollectionId) {
		$this->collectionId = $newcollectionId;
	}

	function getCollectionIdLabel() {
		return $this->collectionIdLabel;
	}
	function setCollectionIdLabel( $newcollectionIdLabel) {
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
