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

include_once ( 'filters/publicationFilter.class.php');
include_once ( 'filters/sort.class.php');
  
	$sortByFields = array (	0 => array(	'field' => 'id',
										'label' => 'Publication Id',
										'width' => 40,
										'toSort' => true,
										'inGet' => 0,
										'order' => 'DESC'),
							1 => array(	'field' => 'publicationTitle',
										'label' => 'Publication Title',
										'width' => 40,
										'toSort' => true,
										'inGet' => 0,
										'order' => 'ASC'),
							2 => array(	'field' => 'author',
										'label' => 'Author',
										'width' => 60,
										'toSort' => true,
										'inGet' => 0,
										'order' => 'ASC'),
							3 => array(	'field' => 'institution',
										'label' => 'Institution',
										'width' => 50,
										'toSort' => true,
										'inGet' => 0,
										'order' => 'ASC'),
							4 => array(	'field' => 'publicationType',
										'label' => 'Publication Type',
										'width' => 30,
										'toSort' => false,
										'inGet' => 0,
										'order' => 'ASC'),
							5 => array(	'field' => 'volume',
										'label' => 'Volume',
										'width' => 30,
										'toSort' => false,
										'inGet' => 0,
										'order' => 'ASC'),
							6 => array(	'field' => 'year',
										'label' => 'Year',
										'width' => 30,
										'toSort' => true,
										'inGet' => 0,
										'order' => 'ASC'),
							7 => array(	'field' => 'title',
										'label' => 'Article Title',
										'width' => 30,
										'toSort' => true,
										'inGet' => 0,
										'order' => 'ASC'),
							8 => array(	'field' => 'year',
										'label' => 'Year',
										'width' => 30,
										'toSort' => true,
										'inGet' => 0,
										'order' => 'ASC')
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
      
		$publicationFilter = new publicationFilter($config->domain);
		$publicationFilter->setIsTheFirst( FALSE );
		$publicationFilter->setForItSeft( TRUE);
      
		global $sortByFields;
		$sortFilter = new sort($config->domain, $sortByFields);
      
		$this->filterList[0] = $publicationFilter;
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
      
    // -->
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
						<a href="'.$config->domain.'Admin/Publication/addPublication.php?pop=yes" class="button mediumButton" title="Add New Publication..." ><div>Add New...</div></a>
						</td>
					</tr>
				</table>	';
      }
		// know if popup window
		if ($_GET['pop']) 
			echo '<input name="pop" value="YES" type="hidden" />';
		//know numPerPage 
		echo '<input id="numPerPage" name="numPerPage" value="'.$_GET['numPerPage'].'" type="hidden" />
			<input id="goTo" name="goTo" value="'.$_GET['goTo'].'" type="hidden" />
			<input id="resetOffset" name="resetOffset" value="'.$_GET['resetOffset'].'" type="hidden" />
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
  
	function createSQL( $objInfo = NULL) {
  
		$sql  = 'SELECT Publication.* '
			   .'FROM Publication ' 
			   .'LEFT JOIN BaseObject ON BaseObject.id = Publication.id ';
      
		$sql .= 'WHERE (BaseObject.dateToPublish <= NOW()';
		if ($objInfo) {
			if ($objInfo->getUserId() != NULL)
				$sql .=' OR BaseObject.userId=\''.$objInfo->getUserId().'\''; 
			if ($objInfo-> getUserGroupId() != NULL)
				$sql .= ' OR BaseObject.groupId=\''.$objInfo->getUserGroupId().'\'';
      }
		$sql .=') ';
  
		// Where 
		//$this->filterList[0]->setIsTheFirst( FALSE );
		$sql .= $this->createWhereContribSpecificKws();
  
		$sql .= 'GROUP BY id ';
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
