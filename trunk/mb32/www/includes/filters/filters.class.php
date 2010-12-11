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
//

class filter {

	var $newSql;
	
	function display() {
	
	
	}
	
	function saveData() {
	
	
	}
	
	function resetData() {
	
	
	}
	
	function createQuery() {
	
	
	}

}

class keywordFilter extends filter {
	var $flag; // bool value that flags whether or not the search filter is a simple search.  True = simple search, False = field search
	var $searchString;
	var $fieldArray = array();
	var $actionUrl;
	var $buttonSrc;
	
	// Contructor
	function keywordFilter() {		
		$this->resetData();				
	}
	
	// Class methods...	
	function resetData() {
		$this->flag = TRUE;
		$this->searchString = "";
		$this->fieldArray = array("table" => NULL, "operator" => NULL, "field" => NULL, "searchTerm" => NULL);
		$this->actionUrl = $config->domain . 'Browse/ByImage/index.php';
		$this->buttonSrc = $config->domain . 'style/webImages/buttons/';	
	}
	
	function display() {	
		echo '
		<form name="keyworkFilter" action="'.$this->actionUrl.'" method="post">
			<h2>Keyword Filter</h2><br /><br />			
			<table border="0">
				<tr>
					<td><input type="text" name="keywordFilterText" value="" size="21" /></td>
				</tr>
				<tr>
					<td align="right"><input type="image" name="submit" src="'.$this->buttonSrc.'filter-trans.png" value="submit" /></td>
				</tr>
			</table>'.$this->searchString.'
			<hr />	
		</form>		
		';	
		
	}
	
	
	function saveDataToPHP() {
		$_SESSION['keywordFilter'] = serialize($this);
		
		if ($_SESSION['keywordFilter'])
			return TRUE;
		else
			return FALSE;
	}
	
	function retrieveDataFromPHP() {
		if ($_SESSION['keywordFilter'])
			$this = unserialize($_SESSION['keywordFilter']);
	
	}
	
	
	function getFlag() {		
		return $this->flag;
	}
	
	function getSearchString() {
		return $this->searchString;
	}
	
	function getFieldArray() {
		return $this->fieldArray;
	}
	
	function setFlag($flag = TRUE) {
		$this->flag = $flag;	
	}		
	
	function setSearchString($searchText = "") {
		$this->searchString = $searchText;	
	}
	
	function setFieldArray($array) {
		if (is_array($array))
			$this->fieldArray = $array;	
		else
			echo 'Value Must be an array to call setFieldArray method of keywordFilter class';
	}
	
	


}// end class keywordFilter


?>
