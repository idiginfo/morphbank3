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

class viewFilter extends filter {
	//fields 
	var $whatSearch;
	var $viewId; 
	var $viewIdLabel;
	var $searchString;
	var $searchStringLabel;
	
	var $actionUrl;
	var $buttonSrc;
	
	// Contructor
	function viewFilter( $myDomain = "http://morphbank2.scs.fsu.edu/", $module="browse") {		
		$this->setDomainName( $myDomain);			
		$this->resetData();
		$this->setModule($module);	
	}
	
	// Class methods...	
	function resetData() {
		$this->setTitle('View: ');
		$this->viewId = '';
		$this->whatSearch = 'keywords';
		$this->viewIdLabel = 'Id ';
		$this->searchString = '';
		$this->searchStringLabel = 'Keywords';
		$this->setSize();
		$this->forItseft = FALSE;
		$this->isTheFirst = FALSE;
		
		$this->actionUrl = $this->domainName.'Browse/ByView/index.php?pop=YES&amp;referer=';
		$this->buttonSrc = $this->domainName.'style/webImages/';	
	}
	
	function printValues() {
		echo "Class name: ".$this->getName()."\n";
		echo "Title = ".$this->getTitle()."\n";
		echo $this->getViewIdLabel()."'".$this->getViewId()."'\n";
		echo $this->getSearchStringLabel()."'".$this->getSearchString()."'\n";
		echo "actionUrl = '$this->actionUrl'\n";
		echo "buttonSrc = '$this->buttonSrc'\n";
	}
	
	function display() {	
		$bigTitle = 'View keywords field queries:<br />'
					.'- View id<br />'
					.'- Imaging technique<br />'
					.'- Imaging preparation technique<br />'
					.'- Part<br />'
					.'- Angle<br />'
					.'- Developmental Stage<br />'
					.'- Sex<br />'
					.'- Form<br />';
		$postItContent = htmlentities($bigTitle);		
		
		if ($this->module == "browse")
			parent::echoJSFuntions();
			
		echo '<h3>';
		if (!$this->forItSeft)
			echo $this->getTitle();
		else
			echo $this->getSearchStringLabel();
		echo '</h3>
			<table border="0" width="100%">';
			/*
				<tr>
					<td>';
		if (!$this->forItSeft)
			echo '<input type="radio" name="viewId_Kw" onclick="kwFilterOnClick(\'selectViewImg\')"
					value="keywords" '.(($this->whatSearch=="keywords")?'checked="checked"':'').'/>';
		echo $this->searchStringLabel;
		if (!$this->forItSeft) // echo out Id and selection 
		 	echo '	<input type="radio" name="viewId_Kw"'
						.($this->forItSeft?' ':' onclick="idFilterOnClick(\'selectViewImg\')"')
						.'value="id" '.(($this->whatSearch=="id")?'checked="checked"':'').'/>' 
					.$this->viewIdLabel.'
					</td>
					<td align="left">
						 <a id="selectViewImg" href="javascript:openPopup(\''.$this->actionUrl.'\')" 
							title="Click here to select a  View" style="display:'.(($this->whatSearch=="id")?'inline':'none').'">
						<img src="'.$this->buttonSrc.'selectIcon.png" border="0" alt="Browse" /></a>
					</td>';
		echo '		</td>
				</tr>*/
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
				echo '
				<tr>
					<td '.$tdWidth.'><input type="text" name="viewKeywords" value="'.$htmlValue.'" size="'.$this->size.'" 
						onkeypress="checkEnter(event)" onmouseover="startPostIt( event, \''.$postItContent.'\')" 
						onmouseout="stopPostIt()"/>';
						if ($this->forItSeft) {
							$referer = isset($_GET['referer'])?$_GET['referer']:"browse";
							echo'<input type="hidden" name="referer" value="'.$referer.'" />';
						}
					echo'	
					</td>';
				if (!$this->forItSeft) {
					echo'
					<td align="'.$rightOrLeft.'" valign="middle" style="padding-right:10px;">&nbsp;&nbsp;
						 <a id="selectViewImg" href="javascript:openPopup(\''.$this->actionUrl.$checkmarkReferer.'\',\'890\',\'600\')" title="Click here to select a  View">
							<img src="'.$this->buttonSrc.'selectIcon.png" border="0" alt="Browse" /></a>';
					/*
						<input type="image" src="'.$this->buttonSrc.'/buttons/reset-trans.png" 
							onclick="javascript:this.form.viewKeywords.value=\''.$this->searchString.'\'; return false;" />
					*/
					echo '
					</td>';
				}
				echo '
				</tr>
			</table><br />';
	}
	
	function getSqlJoinContribution() {
		
		if ($this->searchString != '') {
			$sql = 'LEFT JOIN View ON image.viewId = View.id ';
			return $sql;
		}
		
		return '';	
	}
	
	function getSqlWhereContribution() {
		
		$sql = '';
		/*
		$arrayOfWords = explode(" ", addcslashes($this->searchString,"'\"")); //explode(" ", $this->searchString);			
			$numberOfWords = count($arrayOfWords);
			for ($i = 0; $i < $numberOfWords; $i++)	{
				$sql .= $this->isTheFirst?'WHERE ':'AND ';
				$sql .= '(View.viewName LIKE \'%'.$arrayOfWords[$i].'%\' OR '
						.'View.id = "'.$arrayOfWords[$i].'") ';
				//$sql .= ($i < ($numberOfWords-1))?' AND ':' ';
				$this->isTheFirst = FALSE;
			}
			return $sql;
		*/
		if ( is_numeric($this->searchString) ) {
			$sql .= $this->isTheFirst?'WHERE ':'AND ';
			$sql .= '(View.id ='.$this->searchString.') ';
			$this->isTheFirst = FALSE;
			return $sql;
		}
		
		elseif ($this->searchString != '') {
			$arrayOfWords = parent::returnArrayOfWords($this->searchString);
			$num = count($arrayOfWords);
			
			for ($i = 0; $i < $num; $i++) {
				$sql .= $this->isTheFirst?'WHERE ':'AND ';
				$sql .= '(View.viewName LIKE \'%'.$arrayOfWords[$i].'%\' ) ';
				$this->isTheFirst = FALSE;		
			}
			return $sql;			
		}
			
		return '';
	}
	
	function retrieveDataFromGET() {
		if (isset($_GET['submit2']) || ($_GET['activeSubmit'] == 2) ) {
			//$this->whatSearch = $_GET['viewId_Kw']!=NULL?$_GET['viewId_Kw']:$this->whatSearch;
			$this->setSearchString(trim($_GET['viewKeywords']));
		}
	}
		
	function getViewId() {
		return $this->viewId;
	}
	function setViewId( $newViewId) {
		$this->viewId = $newViewId;
	}
	
	function getViewIdLabel() {
		return $this->viewIdLabel;
	}
	function setViewIdLabel( $newViewIdLabel) {
		$this->viewIdLabel = $newViewIdLabel;
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

}// end class viewFilter

//testing 
/*
$myViewFilter = new viewFilter();
$myViewFilter->setSearchString('Female Fredrik');
$myViewFilter->printValues();
$myViewFilter->display();
*/
?>
