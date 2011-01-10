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

require_once('filters/keywordFilter.class.php');
require_once('filters/tsnFilter.class.php');
require_once('filters/specimenFilter.class.php');
require_once('filters/viewFilter.class.php');
require_once('filters/localityFilter.class.php');
require_once('filters/sort.class.php');

class MyManager
{
	private $title;
	public $filterArray = array();
	private $filterCount;
	private $link;
	private $userId;
	private $groupId;
	private $domainName;
	private $imgDirectory;

	function __construct($title = "")
	{
		global $config, $link, $objInfo;

		$this->title = $title;
		$this->link = $link;
		$this->domainName = $config->domain;
		$this->imgDirectory = $this->domainName . 'style/webImages/';

		if ($objInfo) {
			$this->userId = $objInfo->getUserId();
			$this->groupId = $objInfo->getUserGroupId();
		}

		$module = "manager";

		$this->filterArray[0] = new keywordFilter($config->domain, $module);

		//$this->filterArray[1] = new tsnFilter($config->domain, $module);
		//$this->filterArray[2] = new specimenFilter( $domainName, $module);
		//$this->filterArray[3] = new viewFilter($config->domain, $module);
		//$this->filterArray[4] = new localityFilter($config->domain, $module);
		//$this->filterArray[5] = new sort($config->domain, $this->getSortByFields(), $module); // always sort should be the last

		$this->filterCount = count($this->filterArray);
	}

	public function display()
	{
		global $objInfo;
		echo '<div class="mainGenericContainer" style="width:950px">
    <table width = "950px">
      <tr>
        <td align ="left">
      <h1>' . $this->title . '</h1><h2> (search, browse, edit & annotate)</h2>
        </td>
        <td align = "right">
      <a style="color:red; font-weight:bold;" href="javascript: openPopup(\'' . $this->domainName . 'About/Manual/myManager.php\');">(Help)</a>&nbsp;<a href="' . $this->domainName . 'Help/feedback/">(Feedback)</a> <p></p>
      </td>
       </tr>
       </table>';
		//var_dump($objInfo);

		echo '
      <table class="manageBrowseTable" width="100%" cellspacing="0">
        <tr>
          <td id="rightBorder" width="210px" valign="top">
            <div class="browseFieldsContainer">';
		$this->makeFilterForm();
		echo '</div>
          </td>
          <td valign="top">';
		$this->showPageControls();
		$this->showListOfObjects();
		//mainGenericContainer
		echo '    </td>
        </tr>
      </table>
    </div>  ';
	}

	protected function showPageControls()
	{
		global $objInfo;

		$goToValue = ($objInfo->getCurrentPage() != "" || $objInfo->getCurrentPage() != null) ? $objInfo->getCurrentPage() : "";
		$numPerPageValue = ($objInfo->getNumPerPage() != "" || $objInfo->getNumPerPage() != null) ? $objInfo->getNumPerPage() : 20;

		echo '
    <div class="imagethumbspageHeader">
      <form name="operationForm" action="#">
      <table border="0" cellpadding="0" width="95%"><tr>

      <td colspan="4" valign="top">
        
        Show: 
        <select name="numPerPage" onchange="manager_submitForm();return false;" >';

		for ($i = 10; $i <= 100; $i += 10) {
			$selected = ($i == $numPerPageValue) ? ' selected="selected" ' : "";
			echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
		}
		echo '
          
        </select>
        hits per page &nbsp;&nbsp; Page:
        <input align="top" name="goTo" size="5" type="text" value="' . $goToValue . '" onKeyPress="return manager_checkEnterGoToPage(event);" />

      
        <a href="javascript: manager_goToPage();" class="button smallButton"><div>Go</div></a>
            
      </td>
    </tr>
    <tr>
      <td colspan="4"><img src="' . $this->domainName . 'style/webImages/blueHR-trans.png" width="685" height="4" class="blueHR" style="margin-bottom:5px;" alt="" /></td>
    </tr><!--hr/ width="95%" align="left"-->
    
    
      <tr>
        
        <td width="70px">

        <a href="javascript: checkAllImages();" class="button smallButton" title="Check all images in the current page" ><div>Check All</div></a>
        </td>
        <td width="70px">
        <a href="javascript: unCheckAllImages();" class="button smallButton" title="Uncheck all images in the current page" ><div>UnCheck</div></a>
        </td>
        <td align="center">  
        <span id="massActionOptions">';

		// Mass Action Options populated by ajax calls

		echo '
        </span>
        </td>
        <td align="right">
          <a href="javascript: massCheckboxOperation();" class="button smallButton"><div>Submit</div></a>
        </td>
        
      </tr>
      <tr><td colspan="4"><img src="' . $this->domainName . 'style/webImages/blueHR-trans.png" width="685" height="4" class="blueHR" style="margin-bottom:5px;" alt="" /></td></tr>
    
    </table>
      <input id="dontNeed" type="hidden" value="" name="dontNeed"  />
      </form>
      </div>
    
    ';
	}

	protected function makeFilterForm()
	{
		echo '<form name="managerForm" action="#" method="post">';
		$this->filterArray[0]->display();
		echo '<div id="imageFilters">';
		//$this->filterArray[5]->display();
		echo '</div>';
		echo '</form>';
	}

	protected function showListOfObjects()
	{
		$url = $this->domainName . 'MyManager/content.php?id=';
		echo '
  <form name="resultForm" action="#" method="post">
    <div class="TabbedPanels" id="tp1">
      <ul class="TabbedPanelsTabGroup">
        <li id="allTab" class="TabbedPanelsTab" tabindex="0" onclick="switchTab(\'' . $url . 'allTab\', \'allTab\');">All</li>
        <li id="imageTab" class="TabbedPanelsTabSelected" tabindex="0" onclick="switchTab(\'' . $url . 'imageTab\', \'imageTab\');" >Images</li>
        <li id="specimenTab" class="TabbedPanelsTab" tabindex="0" onclick="switchTab(\'' . $url . 'specimenTab\', \'specimenTab\');" >Specimens</li>
        <li id="viewTab" class="TabbedPanelsTab" tabindex="0" onclick="switchTab(\'' . $url . 'viewTab\', \'viewTab\');" >Views</li>
        <li id="localityTab" class="TabbedPanelsTab" tabindex="0" onclick="switchTab(\'' . $url . 'localityTab\', \'localityTab\');" >Localities</li>
        <li id="taxaTab" class="TabbedPanelsTab" tabindex="0" onclick="switchTab(\'' . $url . 'taxaTab\', \'taxaTab\');" >Taxa</li>
        <li id="collectionTab" class="TabbedPanelsTab" tabindex="0"  onclick="switchTab(\'' . $url . 'collectionTab\', \'collectionTab\');">Collections</li>
        <li id="annotationTab" class="TabbedPanelsTab" tabindex="0" onclick="switchTab(\'' . $url . 'annotationTab\', \'annotationTab\');" >Annotations</li>
        <!--li id="matrixTab" class="TabbedPanelsTab" tabindex="0" onclick="switchTab(\'' . $url . 'matrixTab\', \'matrixTab\');" >Matrices</li-->
        <li id="pubTab" class="TabbedPanelsTab" tabindex="0" onclick="switchTab(\'' . $url . 'pubTab\', \'pubTab\');" >Publications</li>
      </ul>
      
      <div class="TabbedPanelsContentGroup" id="contentId">';

		//Ajax puts the search content here
		echo '<div class="TabbedPanelsContent">
            <div id="imagethumbspage" class="imagethumbspage">
              <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
              <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
              <br /><br /><br /><br /><br /><br />
            </div></div>';
		echo '</div></div><input type="hidden" name="currentTab" value="" /></form>';
	}

	public function getCollectionArray()
	{
		if (!isset($this->userId)) {
			return null;
		}
		//TODO fix this so that list is shorter and more useful.suggest popup for selecting collection
		// Eg show most recent few with button to go to selection
		//TODO allow specification of "role" and other values and addition to objects other than collections

		$sql = 'SELECT BaseObject.* FROM BaseObject '
		. 'WHERE (userId='.$this->userId .' OR submittedBy='.$this->userId.')'
		. ' AND groupId = ' . $this->groupId
		. ' AND (objectTypeId = \'Collection\' OR objectTypeId = \'myCollection\') '
		//. 'AND dateToPublish > NOW() '
		. 'ORDER BY id ASC ';
		$results = mysqli_query($this->link, $sql);
		$numRows = mysqli_num_rows($results);
		if ($numRows) {
			for ($i = 0; $i < $numRows; $i++)
			$collectionArray[$i] = mysqli_fetch_array($results);
			return $collectionArray;
		} else
		return null;
	}

	public function getCharacterArray()
	{
		if (!isset($this->userId)) {
			return null;
		}
		$sql = 'SELECT MbCharacter.*, BaseObject.*, MbCharacter.id AS id ' . 'FROM MbCharacter INNER JOIN BaseObject ON MbCharacter.id = BaseObject.id  WHERE BaseObject.userId = \'' . $this->userId . '\' AND BaseObject.groupId = \'' . $this->groupId . '\'
         AND BaseObject.dateToPublish > NOW() ORDER BY MbCharacter.id DESC ';

		$results = mysqli_query($this->link, $sql) or die(mysqli_error($this->link));
		if ($results) {
			$numRows = mysqli_num_rows($results);
			for ($i = 0; $i < $numRows; $i++)
			$collectionArray[$i] = mysqli_fetch_array($results);
			return $collectionArray;
		} else
		return null;
	}

	public function getOtuArray()
	{
		if (!isset($this->userId)) {
			return null;
		}

		$sql = 'SELECT BaseObject.* FROM BaseObject ' . 'WHERE BaseObject.userId = ' . $this->userId . ' AND BaseObject.groupId = ' . $this->groupId . ' AND BaseObject.objectTypeId = \'Otu\'  ' . 'AND BaseObject.dateToPublish > NOW() ' . 'ORDER BY BaseObject.id ASC ';

		$results = mysqli_query($this->link, $sql) or die(mysqli_error($this->link));
		if ($results) {
			$numRows = mysqli_num_rows($results);
			for ($i = 0; $i < $numRows; $i++)
			$collectionArray[$i] = mysqli_fetch_array($results);
			return $collectionArray;
		} else
		return null;
	}

	protected function getSortByFields()
	{
		$sortByFields = array(array('field' => 'id', 'label' => 'Image id', 'width' => 40, 'toSort' => true, 'inGet' => 0, 'order' => 'DESC'), array('field' => 'dateToPublish', 'label' => 'Date To Publish', 'width' => 40, 'toSort' => true, 'inGet' => 0, 'order' => 'DESC'), array('field' => 'userId', 'label' => 'User Id', 'width' => 40, 'toSort' => true, 'inGet' => 0, 'order' => 'DESC'), array('field' => 'groupId', 'label' => 'Group Id', 'width' => 40, 'toSort' => true, 'inGet' => 0, 'order' => 'DESC'));

		return $sortByFields;
	}
}
// end class MyMangager
?>
