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

class sort {
	var $listOfFields;
	var $numListBox;
	var $title;
	var $domainName;
	var $actionUrl;
	var $buttonSrc;
	var $idSelectTag;
	var $module;
	
	// Contructor
	function sort( $myDomain = "http://morphbank2.scs.fsu.edu/", $listOfFields = NULL, $module="browse") {		
		$this->setDomainName( $myDomain);			
		$this->resetData( $listOfFields);
		$this->module = $module;	
		//$this->echoTsnJSFuntions();
	}
	
	function resetData( $listOfFields) {
		$this->setTitle('Sort by:');
		$this->numListBox = 3;
		$this->actionUrl = $this->domainName.'Browse/ByImage/index.php';
		$this->buttonSrc = $this->domainName.'style/webImages/buttons/';
		$this->idSelectTag = 'listField';
		
		global $sortByFields;
		
		if ($listOfFields != NULL)
			$this->listOfFields = $listOfFields;
		else
			$this->listOfFields = array ( 	array(	'field' => 'imageId',
							  	'label' => 'Image id',
								'width' => 40,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'DESC'),
						array(	'field' => 'Specimen.tsnId',
							  	'label' => 'Taxon id',
								'width' => 40,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'ASC'),
						array(	'field' => 'TaxonName',
							  	'label' => 'Taxon name',
								'width' => 40,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'ASC'),
						array(	'field' => 'View.specimenPart',
							  	'label' => 'Specimen Part',
								'width' => 60,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'ASC'),
						array(	'field' => 'View.viewAngle',
							  	'label' => 'Angle',
								'width' => 50,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'ASC'),
						array(	'field' => 'Specimen.sex',
							  	'label' => 'Sex',
								'width' => 30,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'ASC'),
						array(	'field' => 'View.developmentalStage',
							  	'label' => 'Stage',
								'width' => 30,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'ASC'),
					    array(	'field' => 'View.form',
							  	'label' => 'Form',
								'width' => 30,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'ASC'),
					    array(	'field' => 'View.imagingTechnique',
							  	'label' => 'Imaging Technique',
								'width' => 30,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'ASC'),
						array(	'field' => 'View.imagingPreparationTechnique',
							  	'label' => 'Imaging Preparation',
								'width' => 30,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'ASC')
						);
	}
	
	function display() {
	
		echo '<h3>'.$this->title.'</h3><br/>';
		for ($i = 1; $i <= $this->numListBox; $i++) {
			$order = 'ASC';
			echo '<select name="'.$this->idSelectTag.$i.'" style="width:200px">';
			echo '<option value ="">Select Sort Criteria</option>';
			echo '<option value ="">--------------</option>';
			foreach($this->listOfFields as $f) {
				if ( $f['toSort']) {
					echo '<option value="'.$f['field'].'" ';
					if ($f['inGet'] == $i) {
						echo 'selected="selected"';
						$order = $f['order'];
					}
					echo '>'.$f['label'].'</option>';
				}
			}
			echo '</select><br/>';
			echo '<input type="radio" name="orderAsc'.$i.'" value="ASC"';
			if ($order == 'ASC') echo ' checked="checked"';
			echo ' title="Ascendent" /><strong title="Ascendent">Asc.</strong>';
			echo '<input type="radio" name="orderAsc'.$i.'" value="DESC"';
			if ($order == 'DESC') echo ' checked="checked"';
			echo ' title="Descendent" /><strong title="Descendent">Desc.</strong>';
			
			if ($i != $this->numListBox) echo '<br/><br/><strong>then by:</strong><br/>';
		}
		
		$sortFunction = ($this->module == "browse")? "sortSubmit();" : "manager_submitForm();";
		echo '<table border="0" width="100%">
				<tr>
					<td align="right">';
						//<input type="image" onclick="javascript:this.form.resetOffset.value=\'on\';return true;" src="'.$this->buttonSrc.'sortButton.png" name="sort" alt="Sort" />
						echo'<a href="javascript: '.$sortFunction.' " class="button smallButton"><div>Sort</div></a>
					</td>
				</tr>
			</table>';
			
		/*	Limit By for MyManager.... relocated to MyManager/filterContent.php
		if ($this->module != "browse") {
			/*
			$selected = (isset($_GET['limitBy']))? $_GET['limitBy'] : "all";
			echo'<hr />
			<h3>Limit Search by:</h3><br />
			
			<input type="radio" name="limitBy" value="all" ';
				if($selected == "all") echo ' checked="checked" ';
			echo 'onclick="manager_submitForm();" />All<br />
			
			<input type="radio" name="limitBy" value="user" ';
				if($selected == "user") echo ' checked="checked" ';
			echo' onclick="manager_submitForm();" />User<br />
			
			<input type="radio" name="limitBy" value="userAndGroup" ';
				if($selected == "userAndGroup") echo ' checked="checked" ';
			echo' onclick="manager_submitForm();" />User and Current Group<br />
			
			<input type="radio" name="limitBy" value="userAndAnyGroup" ';
				if($selected == "userAndAnyGroup") echo ' checked="checked" ';
			echo 'onclick="manager_submitForm();" />User and Any Group<br />
			
			<input type="radio" name="limitBy" value="group" ';
				if($selected == "group") echo ' checked="checked" ';
			echo' onclick="manager_submitForm();" />Current Group<br />
			
			<input type="radio" name="limitBy" value="anyGroup" ';
				if($selected == "anyGroup") echo ' checked="checked" ';
			echo 'onclick="manager_submitForm();" />Any Group<br />';
			*/
			
		/*	
		}
		*/
			//echo 'module'.$this->module;
	}
	
	function retrieveDataFromGET () {
	
		$arraySize = count($this->listOfFields);
		$someOneInGet = false;
		$goodToGo = true;
		
		// for the numbers of select
		for($j=1; $j <= $this->numListBox; $j++) {
			// for all fields
			for($i=0; $i < $arraySize; $i++) {
				$idSelect = $this->idSelectTag.$j;
				$idOrder = 'orderAsc'.$j;
				if ($this->listOfFields[$i]['toSort'] && ($_GET[$idSelect] == $this->listOfFields[$i]['field'])) {
					$someOneInGet = true;
					if ($this->listOfFields[$i]['inGet'] > 0) 
						$goodToGo = false;
					else { 
						$this->listOfFields[$i]['inGet'] = $j; 
						if ($_GET[$idOrder] != NULL)
							$this->listOfFields[$i]['order'] = $_GET[$idOrder];
					}
				}
			}
		}
		
		//echo $sortByFields[0]['inGet'];
		if (!$someOneInGet) $this->listOfFields[0]['inGet'] = 1; // id field to Sort at the first select
		return $goodToGo;
	}
	
	function getSqlOrderContribution() {
		
		$sql .= 'ORDER BY ';
		$mumSelected = 0;
		$orderSeq = array_fill(0, $this->numListBox, '');
		$orderSeq2 = array_fill(0, $this->numListBox, 'ASC');
	
		foreach( $this->listOfFields as $field) {
			if ($field['inGet']>0)  {
				$orderSeq[$field['inGet']] = $field['field'];
				$orderSeq2[$field['inGet']] = $field['order'];
			}
		}
	
		$arraySize = count($orderSeq);
		for($i=0; $i<$arraySize; $i++) {
			if ( $orderSeq[$i] != '') 
				$sql .= $orderSeq[$i].' '.$orderSeq2[$i].', ';
		}
		
		// removing the last coma
		$sql = substr( $sql, 0, strlen($sql)-2);
		
		return $sql;
	}
	
	function sqlOrderByContribution() { }
	
	function setTitle( $newTitle = "") {
		$this->title = $newTitle; 
	}
	
	function setListOfFields ( $listOfFields='') {
		$this->listOfFields = $listOfFields;
	}
	
	function isTableJoined() { }
	
	function setDomainName ( $myDomain = "http://morphban2.scs.fsu.edu") {
		$this->domainName = $myDomain;
	}
	
	function getTitle() {
		return $this->title; 
	}
	
	function getName() {
		return get_class($this);
	}
	
	function printValues() {
	
	}
}

?>
