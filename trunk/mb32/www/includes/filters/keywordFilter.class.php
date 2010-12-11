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

class keywordFilter extends filter {
	var $searchString;
	var $searchStringLabel;

	var $actionUrl;
	var $buttonSrc;

	// Contructor
	function keywordFilter(  $myDomain = "http://morphbank4.scs.fsu.edu/", $module="browse") {
		$this->setDomainName( $myDomain);
		$this->resetData();
		$this->setModule($module);
	}

	// Class methods...
	function resetData() {
		$this->setTitle("Keywords:");
		$this->searchString = "";
		$this->actionUrl = $this->domainName.'Browse/ByImage/index.php';
		$this->buttonSrc = $this->domainName.'style/webImages/buttons/';
	}

	function display() {
		global $config, $objInfo;

		$bigTitle = 'General keywords field queries:<br />'
		.'- User name<br />'
		.'- Taxonomic names<br />'
		.'- Catalog number<br />'
		.'- Form<br />'
		.'- Sex<br />'
		.'- Developmental stage<br />'
		.'- Type status<br />'
		.'- Imaging technique<br />'
		.'- Imaging preparation technique<br />'
		.'- Part<br />'
		.'- Image id<br />'
		.'- Locality<br />'
		.'- Continent/Ocean<br />'
		.'- Country<br />';
		$postItContent = htmlentities($bigTitle, ENT_QUOTES, "UTF-8");

		if ($objInfo->getKeywords() == "" || $objInfo->getKeywords() == NULL || $this->module == "browse" || isset($_GET['tab'])) {
			$htmlValue = htmlentities($this->searchString, ENT_QUOTES, "UTF-8");
		} else {
			$htmlValue = htmlentities($objInfo->getKeywords(), ENT_QUOTES, "UTF-8");
		}
		$checkEnterFunction = ($this->module == "browse")? "checkEnterKeyword" : "manager_checkEnter";
		$submitFormFunction = ($this->module == "browse")? "submitForm(1)" : "manager_submitForm()";
		$resetAction = ($this->module == "browse")? "index.php" : "javascript: manager_resetForm()";

		echo '<h3>'.$this->title.'</h3><br />
			<table border="0" cellspacing="2px" width="100%">
				<tr>
					<td align="right">						
						<a href="javascript: '.$submitFormFunction.';" class="button smallButton"><div>Search</div></a>
						<a href="'.$resetAction .'" class="button smallButton"><div>Reset</div></a>
					</td>
				</tr>
			
				<tr>
					<td><textarea id="keywordFilterId" name="keywords" rows="2" cols="22" onkeypress="return '.$checkEnterFunction.'(event);"
						onmouseover="startPostIt( event, \''.$postItContent.'\')" 
						onmouseout="stopPostIt()">'.$htmlValue.'</textarea></td>
				</tr>';
		$this->limitBy();
		echo '</table>';	
	}


	function printValues() {
		echo "Class name:".$this->getName()."\n";
		echo "Title = ".$this->getTitle()."\n";
		echo "searchString = '".$this->getSearchString()."'\n";
		echo "actionUrl = '$this->actionUrl'\n";
		echo "buttonSrc = '$this->buttonSrc'\n";
	}

	function retrieveDataFromGET() {
		if ( (isset($_GET['submit1_x'])) || (isset($_GET['submit1'])) || ($_GET['activeSubmit'] == 1) ) // if keywords search is actived
		$this->setSearchString(trim($_GET['keywords']));
		//$this->setSearchString($_GET['keywords']);
	}


	function getSqlJoinContribution() {

		$sql = 'LEFT JOIN User ON image.userId = User.id '
		.'LEFT JOIN Specimen ON image.specimenId = Specimen.id '
		.'LEFT JOIN Form ON Specimen.formId = Form.id '
		.'LEFT JOIN Sex ON Specimen.sexId = Sex.id '
		.'LEFT JOIN DevelopmentalStage ON Specimen.developmentalStageId = DevelopmentalStage.id '
		.'LEFT JOIN TypeStatus ON Specimen.typeStatusId = TypeStatus.id '
		.'LEFT JOIN View ON image.viewId = View.id ';

		//.'LEFT JOIN Location ON specimen.locationId = Location.id '
		//.'LEFT JOIN Country ON Location.countryId = Country.id '
		// .'LEFT JOIN ContinentOcean ON Location.continentOceanId = ContinentOcean.id '
		//.'LEFT JOIN TaxonomicUnits ON TaxonomicUnits.tsn = specimen.tsnId '
		//.'LEFT JOIN Vernacular ON Vernacular.tsn = specimen.tsnId '

		return $sql;
	}

	function getSqlWhereContribution() {

		$sql = '';
		if ( is_numeric($this->searchString) ) {
			$sql .= 'AND (Image.id ='.$this->searchString.') ';
			return $sql;
		}

		elseif ($this->searchString != '') {
			$arrayOfWords = parent::returnArrayOfWords($this->searchString);
			$num = count($arrayOfWords);
				
			for ($i = 0; $i < $num; $i++)
			$sql .= 'AND (User.name LIKE \'%'.$arrayOfWords[$i].'%\' OR '
			.'Specimen.taxonomicNames LIKE \'%'.$arrayOfWords[$i].'%\' OR '
			.'Specimen.catalogNumber LIKE \'%'.$arrayOfWords[$i].'%\' OR '
			.'View.form LIKE \'%'.$arrayOfWords[$i].'%\' OR '
			.'Specimen.sex LIKE \''.$arrayOfWords[$i].'\' OR '
			.'View.developmentalStage LIKE \'%'.$arrayOfWords[$i].'%\' OR '
			.'Specimen.typeStatus LIKE \'%'.$arrayOfWords[$i].'%\' OR '
			.'View.specimenPart LIKE \'%'.$arrayOfWords[$i].'%\' OR '
			.'View.form LIKE \'%'.$arrayOfWords[$i].'%\' OR '
			.'View.imagingTechnique LIKE \'%'.$arrayOfWords[$i].'%\' OR '
			.'View.imagingPreparationTechnique LIKE \'%'.$arrayOfWords[$i].'%\' OR '
			.'View.viewAngle LIKE \'%'.$arrayOfWords[$i].'%\' OR '
			.'Locality.locality LIKE \'%'.$arrayOfWords[$i].'%\' OR '
			.'ContinentOcean.description LIKE \'%'.$arrayOfWords[$i].'%\' OR '
			.'Country.description LIKE \'%'.$arrayOfWords[$i].'%\' ) ';
			//.'View.viewName LIKE \'%'.$arrayOfWords[$i].'%\' OR '\

				
			return $sql;
		}

		return '';
	}


	function getSearchString() {
		return $this->searchString;
	}

	function setSearchString($searchText = "") {
		$this->searchString = $searchText;
	}

	function limitBy(){
		global $objInfo;
		$limitByArray = $objInfo->getLimitBy();

		$contributor = isset($_GET['limit_contributor']) ? 'checked="checked"' : ($limitByArray['contributor'] == "true" || $limitByArray['contributor'] == true) ? 'checked="checked"' : "";
		$current = isset($_GET['limit_current']) ? 'checked="checked"' : ($limitByArray['current'] == "true" || $limitByArray['current'] == true) ? 'checked="checked"' : "";
		$submitter = isset($_GET['limit_submitter']) ? 'checked="checked"' : ($limitByArray['submitter'] == "true" || $limitByArray['submitter'] == true) ? 'checked="checked"' : "";
		$any = isset($_GET['limit_any']) ? 'checked="checked"' : ($limitByArray['any'] == "true" || $limitByArray['any'] == true) ? 'checked="checked"' : "";

		/*
		 $current = isset($_GET['limit_current']) ? 'checked="checked"' : "";
		 $submitter = isset($_GET['limit_submitter']) ? 'checked="checked"' : "";
		 $any = isset($_GET['limit_any']) ? 'checked="checked"' : "";
		 */

		echo'<tr><td><h3>Limit Search by:</h3></td></tr><tr><tr><td><table>
	<tr><th>My Objects</th><th>Group</th></tr>
	<tr>
		<td title="Limit search by the objects you have contributed, and are the owner of in morphbank.">
		<input type="checkbox" name="limit_contributor" '.$contributor.' /> Contributor</td>
		<td title="Limit search to the objects that belong to the group you are currently logged into.">
		<input type="checkbox" name="limit_current" '.$current.'  /> Current</td>
	</tr>
	<tr>
		<td title="Limit search by the objects you have submitted, but do not own in morphbank.">
		<input type="checkbox" name="limit_submitter" '.$submitter.' /> Submitter</td>
		<td title="Limit search to the objects that belong to any of the groups that you are a member of.">
		<input type="checkbox" name="limit_any" '.$any.' /> Any Group</td>
	</tr></table></td></tr><tr><td><br />

<br/><br/><hr/></td></tr>';
	}

}// end class keywordFilter

//testing
/*
 $mykeywordFilter = new keywordFilter();
 $mykeywordFilter-> setSearchString('Anterior Head');
 $mykeywordFilter->printValues();
 */
?>
