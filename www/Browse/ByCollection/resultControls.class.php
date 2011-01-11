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

  include_once('filters/collectionFilter.class.php');
  include_once('filters/sort.class.php');
  
  $sortByFields = array(array('field' => 'id', 'label' => 'Collection Id', 'width' => 40, 'toSort' => true, 'inGet' => 0, 'order' => 'DESC'), array('field' => 'userName', 'label' => 'User name', 'width' => 40, 'toSort' => true, 'inGet' => 0, 'order' => 'ASC'), array('field' => 'groupName', 'label' => 'Group name', 'width' => 60, 'toSort' => true, 'inGet' => 0, 'order' => 'ASC'), array('field' => 'publicationId', 'label' => 'Publication', 'width' => 50, 'toSort' => false, 'inGet' => 0, 'order' => 'ASC'), array('field' => 'publicationInfo', 'label' => 'publication Info', 'width' => 30, 'toSort' => false, 'inGet' => 0, 'order' => 'ASC'), array('field' => 'collectionName', 'label' => 'Collection name', 'width' => 30, 'toSort' => true, 'inGet' => 0, 'order' => 'ASC'), array('field' => 'imagesCount', 'label' => 'No. Images', 'width' => 50, 'toSort' => true, 'inGet' => 0, 'order' => 'ASC'));
  
  class resultControls
  {
      var $filterList = array();
      
      // Constructor
      function resultControls()
      {
          $this->setupFilters();
      }
      
      // Class Methods
      function setupFilters()
      {
          global $config;
          
          $collectionFilter = new collectionFilter($config->domain);
          $collectionFilter->setIsTheFirst(true);
          $collectionFilter->setForItSeft(true);
          
          global $sortByFields;
          $sortFilter = new sort($config->domain, $sortByFields);
          
          $this->filterList[0] = $collectionFilter;
          $this->filterList[1] = $sortFilter;
          
          $len = count($this->filterList);
          for ($i = 0; $i < $len; $i++) {
              $this->filterList[$i]->retrieveDataFromGET();
          }
      }
      
      function echoJSFunctionUpdate()
      {
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
      //-->
      </script>';
      }
      
      
      function displayForm()
      {
          global $config;
          
          //$this->filterList[1]->echoJSFuntions(); // no good but ...should be static method in filter class ... until php 5
          // java function to process the selection from Browse modules 
          $this->echoJSFunctionUpdate();
          echo '<form name="resultControlForm" action="index.php" method="get">';
          $this->displayFilters();
          $resetPageUrl = isset($_GET['pop']) ? 'index.php?pop=yes' : 'index.php';
          echo '<table border="0" width="100%">
        <tr>
          <td align="right">
          <a href="javascript: submitForm(\'2\');" class="button smallButton"><div>Search</div></a>
          <a href="' . $resetPageUrl . '" class="button smallButton"><div>Reset</div></a>
          </td>
        </tr>
      </table>  
      <hr/>';
          // sort 
          $this->filterList[1]->display();
          echo '<hr/>
      <table border="0" width="100%">
        <tr>
          <td align="right">
            <a href="index.php" class="button smallButton"><div>Clear</div></a>
          </td>
        </tr>
      </table>  ';
          
          // know if popup window
          if ($_GET['pop'])
              echo '<input name="pop" value="YES" type="hidden" />';
          //know numPerPage 
          echo '<input id="numPerPage" name="numPerPage" value="' . $_GET['numPerPage'] . '" type="hidden" />
      <input id="goTo" name="goTo" value="' . $_GET['goTo'] . '" type="hidden" />
      <input id="resetOffset" name="resetOffset" value="' . $_GET['resetOffset'] . '" type="hidden" />
      <input name="submit2" value="' . $_GET['submit2'] . '" type="hidden" />
      <input type="hidden" name="activeSubmit" value="" />
    </form>';
      }
      
      function displayFilters()
      {
          $len = count($this->filterList);
          for ($i = 0; $i < $len - 1; $i++) {
              // no the last one (sort)
              $this->filterList[$i]->display();
              //$this->filterList[$i]->printValues();    
          }
      }
      
      function createSQL($objInfo = null)
      {
          $sql = 'SELECT BaseObject.id as id, 
        User.name as userName, 
        Groups.groupName as groupName,
        BaseObject.name as collectionName,
        count(*) as imagesCount,
        BaseObject.dateToPublish as dateToPublish
        FROM CollectionObjects
        LEFT JOIN BaseObject ON CollectionObjects.collectionId = BaseObject.id
        LEFT JOIN User ON BaseObject.userId = User.id 
        LEFT JOIN Groups ON BaseObject.groupId = Groups.id ';
          
          
          // below was commented out.  I uncommented and modified to display only published collections, or 
          // collections that you own, or belong to the group
          
          
          
          $sql .= 'WHERE (BaseObject.dateToPublish <= NOW()';
          if ($objInfo) {
              if ($objInfo->getUserId() != null)
                  $sql .= ' OR BaseObject.userId=\'' . $objInfo->getUserId() . '\'';
              if ($objInfo->getUserGroupId() != null)
                  $sql .= ' OR BaseObject.groupId=\'' . $objInfo->getUserGroupId() . '\'';
          }
          $sql .= ') ';
          
          // Where 
          $this->filterList[0]->setIsTheFirst(false);
          $sql .= $this->createWhereContribSpecificKws();
          
          $sql .= 'GROUP BY id ';
          // Order by
          // This part it is only with sort.class
          $len = count($this->filterList);
          for ($i = 0; $i < $len; $i++) {
              // only sort
              if ($this->filterList[$i]->getName() == 'sort') {
                  $sql .= $this->filterList[$i]->getSqlOrderContribution();
                  break;
              }
          }
          
          return $sql;
      }
      
      function createWhereContribSpecificKws()
      {
          $sql = '';
          $len = count($this->filterList);
          // Get the contribution for each filter to the Where part of the SQL
          for ($i = 0; $i < $len - 1; $i++) {
              //less sort filter
              $where = $this->filterList[$i]->getSqlWhereContribution();
              if (is_null($where)) {
                  return null;
              }
              $sql .= $where;
          }
          return $sql;
      }
  }
  // End class resultControls
?>
