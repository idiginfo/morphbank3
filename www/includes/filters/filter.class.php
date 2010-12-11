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
	var $module;
	var $newSql;
	var $title;
	var $domainName;
	var $actionUrl;
	var $buttonSrc;
	var $joinTables; // wait until php5 where you can declare static attributes
	var $size;
	var $isTheFirst; // if the filter is the first one, for the Where part of the sql => default is FALSE
	var $forItSeft; //if this filter will be applied for collection search => TRUE 
					// if TRUE don't show browse icon

	
	function display() { }
	
	function saveData() { }
	
	function resetData() { }
	
	function sqlContribution() { }
	
	function setTitle( $newTitle = "") {
		$this->title = $newTitle; 
	}
	
	function getDomainName() {
		return $this->domainName;
	}
	
	function setModule($module) {
		$this->module = (strtolower($module) == "browse") ? "browse" : "manager";	
	}
	
	function isTableJoined() { }
	
	function setDomainName ( $myDomain = "http://morphban4.scs.fsu.edu") {
		$this->domainName = $myDomain;
		$this->actionUrl = $this->domainName.'Browse/ByCollection/index.php?pop=YES';
		$this->buttonSrc = $this->domainName.'style/webImages/';
	}
	
	function getTitle() {
		return $this->title; 
	}
	
	function getName() {
		return get_class($this);
	}
	
	function echoJSFuntions() {
		echo '
			<script language="JavaScript" type="text/javascript">
			<!--
				function kwFilterOnClick( id) {
					if (document.getElementById){
  						target = document.getElementById(id);
					    	target.style.display = "none";
					}
				}
				function idFilterOnClick (id) {
					if (document.getElementById){
  						target = document.getElementById(id);
						target.style.display = "inline";
					}
				}
				
				function setValueTextField( id, newValue) {
					if (document.getElementById){
						target = document.getElementById(id);
						target.value = newValue;
					}	
				}
				
				function submitForm(activeSubmitValue) {
					document.resultControlForm.activeSubmit.value = activeSubmitValue;
					document.resultControlForm.submit();				
				}
				
				function sortSubmit() {
					document.resultControlForm.resetOffset.value=\'on\';
					document.resultControlForm.submit();				
				}
				
				function gotoPageOnClick() {
					var form = document.operationForm;
					if (document.getElementById){
						numPerPage = document.getElementById("numPerPage");
						numPerPage.value = form.numPerPage.value;
						
						goTo = document.getElementById("goTo");
						goTo.value = form.goTo.value;
						
						document.resultControlForm.submit();
					}
				}
				
				
			//-->
			</script>';
	}
	
	function setSize( $newSize = 21 ) { $this->size = $newSize;}
	
	function printValues() {	}
	
	function fromSearch( $string ) {
		return (strpos($string, "Search")===FALSE) ? FALSE : TRUE;	
	}
	
	function fromBrowse( $string ) {
		return (strpos($string, "Browse")===FALSE) ? FALSE : TRUE;	
	}
	
	function setIsTheFirst( $isTheFirst = FALSE) {
		$this->isTheFirst = $isTheFirst;
	}
	
	function setForItSeft( $forItSeft = FALSE) {
		$this->forItSeft = $forItSeft;
	}
	
	function returnArrayOfWords( $inputString = "" ) {
		if ($inputString != '') { // if searchString isn't blank
			//$inputString = trim(ereg_replace('[^A-Za-z0-9"[:space:]]', '', $inputString));
			$inputString = trim(ereg_replace('\]|[\[/\^\@\#!\$\+\?`=\|\{\}%&\*\(\)<>~\]', '', $inputString));
			$inputString = str_replace("'", "\'", $inputString);
			if (strpos($inputString, '"') === FALSE) { // if there are no double quotes in search string
				//$arrayOfWords = explode(" ", addcslashes( $inputString, "'\""));
				$arrayOfWords = explode(" ", $inputString);
				
				return $arrayOfWords;			
			} else { // if there are double quotes in the search string
				if((substr_count($inputString, '"') % 2) != 0) { // if there are an odd number of double quotes, then that is wrong.  Strip all quotes and process like above.
					$inputString = str_replace('"', '', $inputString);
					//$arrayOfWords = explode(" ", addcslashes( $inputString, "'\""));
					$arrayOfWords = explode(" ", $inputString);
					
					return $arrayOfWords;				
				} else { // if there are an even amount of double quotes
					//$inputString = str_replace('\\', '', $inputString);
					$first = strpos($inputString, '"');
					$last = strrpos($inputString, '"');
					$length = ($last) - ($first + 1);
					
					$quotedText = substr($inputString, ($first + 1), $length);
					
					$newText = substr_replace($inputString, '', $first, ($length+2)); // takes out the quoted text					
					$newText = trim(str_replace('"', '', str_replace('  ', ' ', $newText))); // should remove any double quotes, double spaces and trim the string
					
					if (strlen($inputString) == ($length + 2) ) { // if the entire search string is in quotes
						$arrayOfWords = array(0=>$quotedText);					
						return $arrayOfWords;
					} else { // if there are other words than the quoted text					
						$arrayOfWords = explode(" ", $newText);
						$numberOfWords = count($arrayOfWords);
						$arrayOfWords[$numberOfWords] = $quotedText;
						
						return $arrayOfWords;
						//return "ss: ".$inputString." qt: ".$quotedText." nt: ".$newText." ".strlen($inputString)." ".$length;
					}
				}
			
			}		
			
		} // end if ($inputString != '')
		
		return array();
	
	} //end function 	
} // end class

?>
