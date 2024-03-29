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

  /**
   * File name: mainBrowseByCollection.php
   *
   *
   * @author Wilfredo Blanco <blanco@scs.fsu.edu>
   * @package Morphbank2
   * @subpackage Browse
   *
   */
  
  
  include_once('thumbs.inc.php');
  include_once('resultControls.class.php');
  
  // Menu link from mbMenu_data.php
  $browseByImageHref = $config->domain . 'Browse/ByImage/';
  $browseByPublication = $config->domain . 'Browse/ByPublication/';
  
  $publicationHref = $config->domain . 'Show/';
  $publicationSql = null;
  
  function mainBrowseByPublication($title)
  {
      initGetVariables();
      global $publicationSql;
      
      if (isset($_GET['pop']))
          echo '<div class="popContainer" style="width:760px">';
      else
          echo '<div class="mainGenericContainer" style="width:760px">';
      
      echo '
      <h1 align="center">' . $title . '</h1> <p></p>
      
      <table class="manageBrowseTable" width="100%" cellspacing="0">
      <tr>
        <td id="rightBorder" width="150px" valign="top">
        <div class="browseFieldsContainer">';
      makeFilterForm();
      echo '    </div> 
        </td>
        <td valign="top">';
      //lecho $publicationSql;
      showListOfPublications();
      echo '    </td>
      </tr>
      </table>
    </div>';
  }
  
  function initGetVariables()
  {
      global $config;
      
      
      if ($_GET['offset'] == null)
          $_GET['offset'] = 0;
      
      if ($_GET['resetOffset'] == 'on') {
          $_GET['offset'] = 0;
          $_GET['goTo'] = "";
          $_GET['resetOffset'] = 'off';
      }
      if ($_GET['publicationKeywords'])
          $_GET['publicationKeywords'] = trim(ereg_replace('\]|[\[/\^\@\#!\$\+\?`=\|\{\}%&\*\(\)<>~\]', '', $_GET['publicationKeywords']));
      
      if (!isset($_GET['goTo']) || !is_numeric($_GET['goTo'])) $_GET['goTo'] = "";
	  if (is_numeric($_GET['goTo'])) $_GET['goTo'] = round((int)$_GET['goTo']);
      
      if ($_GET['numPerPage'] == null)
          $_GET['numPerPage'] = $config->displayThumbsPerPage;
  }
  
  function makeFilterForm()
  {
      $resultControls = new resultControls;
      $resultControls->displayForm();
      
      global $objInfo, $publicationSql;
      $publicationSql = $resultControls->createSQL($objInfo);
  }
  
  function echoJSCollection()
  {
      echo '<script language="JavaScript" type="text/javascript">
    <!--
      function changeNumPerPage(form) {
        if (document.getElementById){
            numPerPage = document.getElementById("numPerPage");
          numPerPage.value = form.numPerPage.value;
          
          goTo = document.getElementById("goTo");
          goTo.value = "";
          
          resetOffset = document.getElementById("resetOffset");
          resetOffset.value = "on";
          
          document.resultControlForm.submit();
        }
        
      }
      
    // -->
    </script>';
  }
  
  function showListOfPublications()
  {
      global $link, $publicationSql;
      
      if (is_null($publicationSql)) {
          printEmptySet();
          return;
      }
      //echo $publicationSql;
      $result = mysqli_query($link, $publicationSql);
      if ($result) {
          $total = mysqli_num_rows($result);
          if ($total == 0) {
              printEmptySet();
              return;
          }
          
          if ($_GET['goTo'] != "") {
              //$newOffset =(int)(($_GET['goTo']-1)/$_GET['numPerPage']);
              $newOffset = (int)($_GET['goTo']) ? $_GET['goTo'] - 1 : $_GET['offset'] / $_GET['numPerPage'];
              if ($newOffset * $_GET['numPerPage'] < $total)
                  $_GET['offset'] = $newOffset * $_GET['numPerPage'];
          }
          $numRows = ($total - $_GET['offset']) >= $_GET['numPerPage'] ? $_GET['numPerPage'] : $total - $_GET['offset'];
          
          if ($numRows > 0) {
              mysqli_data_seek($result, $_GET['offset']);
              for ($i = 0; $i < $numRows; $i++)
                  $array[$i] = mysqli_fetch_array($result);
              global $browseByPublication;
              outputArrayCollections($array, $total, $browseByPublication);
          }
          mysqli_free_result($result);
      } else {
          echo '<div class="error"><br/>Error, please contact the administration group<br/><br/>' . $publicationSql . "<br/><br/>" . mysqli_error($link) . '</div> ';
      }
  }
  
  
  function printPagesTool()
  {
      global $config;
      
      // How many to show
      echoJSCollection();
      echo '<form action="#" name="operationForm"><p>Show: 
      <select name="numPerPage" onchange="changeNumPerPage(this.form);return false;" >';
      for ($i = 1; $i <= $config->displayThumbsPerPageSelect; $i++) {
          $value = $i * 10;
          echo '<option value="' . $value . '" ';
          if ($value == $_GET['numPerPage'])
              echo 'selected="selected"';
          echo '>' . $value . '</option>';
      }
      echo '</select>
      hits per page';
      // Page    
      echo ' &nbsp;&nbsp; Page:
      <input align="top" name="goTo" size="5" type="text" value="' . $_GET['goTo'] . '" />
      <a href="javascript: gotoPageOnClick();" class="button smallButton"><div>Go</div></a>
  </p></form >';
  }
  
  
  function outputArrayCollections($array, $total, $browseByPublication)
  {
      global $config;
      global $sortByFields, $browseThumbList, $browseByImageHref, $publicationHref;
      
      include_once('objOptions.inc.php');
      
      
      //Number of collections to display
      $sizeOfArray = count($array);
      
      // Set the value of offset to that passed in the URL, else 0.
      if (isset($_GET['offset']))
          $offset = $_GET['offset'];
      else
          $offset = 0;
      
      if (isset($_GET['numPerPage']))
          $numParPage = $_GET['numPerPage'];
      else
          $numParPage = $numPerPage;
      
      echo '<div class="imagethumbspageHeader">';
      printPagesTool();
      printLinks($total, $numParPage, $offset, $browseByPublication);
      echo ' &nbsp; (' . $total . ' items)</div><br />';
      
      // Update myObjOptions with the right values
      $myObjOptions = $objOptions;
      global $objInfo;
      if ($_GET['pop']) {
          $myObjOptions['Info'] = true;
          $myObjOptions['Select'] = true;
          $myObjOptions['Edit'] = false;
          $myObjOptions['Annotate'] = false;
          $myObjOptions['Copy'] = false;
      } else {
          $myObjOptions['Annotate'] = false;
          $myObjOptions['Copy'] = false;
      }
      
      // Loop
      //===========================
      $color[0] = $config->lightListingColor;
      $color[1] = $config->darkListingColor;
      echo '<div class="imagethumbspage">';
      for ($i = 0; $i < $sizeOfArray; $i++) {
          $colorIndex = $i % 2;
          $showCameraHtml = "";
          $title = !empty($array[$i][$sortByFields[7]['field']]) ? $array[$i][$sortByFields[7]['field']] : $array[$i][$sortByFields[1]['field']];
          $publicationTitle = $array[$i][$sortByFields[2]['field']] . '; ' . $array[$i][$sortByFields[6]['field']] . '; ' . $title;
          
          if (strlen($publicationTitle) > 55)
              $publicationTitle = substr($publicationTitle, 0, 55) . '...';
          
          /*if (!$_GET['pop'] && $array[$i][$sortByFields[6]['field']]) // no pop and imagesCount > 0
           $showCameraHtml = '<a href="'.$publicationHref.'?id='.$array[$i][$sortByFields[0]['field']].'">
           <img border="0" src="/style/webImages/camera-min16x12.gif" title="List of images" alt="link"/></a>';*/
          
          echo '
    <div id="row' . ($i + 1) . '" class="imagethumbnail" style="background-color:' . $color[$colorIndex] . ';">
        <table>
          <tr>
            <td class="greenBottomBorder">
              <span  title="Location Id">Publication [' . $array[$i][$sortByFields[0]['field']] . ']</span></td>
            <td class="browseRight greenBottomBorder">' . printOptions($myObjOptions, $array[$i][$sortByFields[0]['field']], 'Publication', $publicationTitle) . '</td>
          </tr>
          <tr>
            <td>' . $sortByFields[2]['label'] . ': ' . $array[$i][$sortByFields[2]['field']] . '</td>
            <td class="browseRight">' . $sortByFields[5]['label'] . ' / ' . $sortByFields[6]['label'] . ': ' . $array[$i][$sortByFields[5]['field']] . ' / ' . $array[$i][$sortByFields[6]['field']] . '</td>
          </tr>';
          
          if (!empty($array[$i][$sortByFields[7]['field']])) {
              echo '
            <tr>
              <td>' . $sortByFields[7]['label'] . ': ' . $array[$i][$sortByFields[7]['field']] . '</td>
              <td class="browseRight">&nbsp;</td>
            </tr>';
          }
          
          echo '
          <tr>
            <td>' . $sortByFields[1]['label'] . ': ' . $array[$i][$sortByFields[1]['field']] . '</td>
            <td class="browseRight">&nbsp;</td>
          </tr>
          <tr>
            <td>' . $sortByFields[4]['label'] . ': ' . $array[$i][$sortByFields[4]['field']] . '</td>
            <td class="browseRight">&nbsp;</td>
          </tr>
        </table>
      </div>';
          
          /*
           echo '
           <div id="row'.($i+1).'" class="imagethumbnail" style="background-color:'.$color[$colorIndex].';">
           <table>
           <tr>
           <td class="greenBottomBorder">
           <span  title="Location Id">Publication ['.$array[$i][$sortByFields[0]['field']].']</span>&nbsp;'.$publicationTitle.'</td>
           <td class="browseRight greenBottomBorder">'.printOptions($myObjOptions, $array[$i][$sortByFields[0]['field']],'Publication', $publicationTitle).'</td>
           </tr>
           <tr>
           <td>'.$sortByFields[2]['label'].': '.$array[$i][$sortByFields[2]['field']].'</td>
           <td class="browseRight">'.$sortByFields[5]['label'].' / '.$sortByFields[6]['label'].': '.$array[$i][$sortByFields[5]['field']].' / '.$array[$i][$sortByFields[6]['field']].'</td>
           </tr>
           <tr>
           <td>'.$sortByFields[3]['label'].': '.$array[$i][$sortByFields[3]['field']].'</td>
           <td class="browseRight">&nbsp;</td>
           </tr>
           <tr>
           <td>'.$sortByFields[4]['label'].': '.$array[$i][$sortByFields[4]['field']].'</td>
           <td class="browseRight">&nbsp;</td>
           </tr>
           
           </table>
           </div>';
           */
      }
      echo '</div>';
      echo '<div class="imagethumbspageHeader"><br/>';
      printLinks($total, $numParPage, $offset, $browseByPublication);
      echo ' &nbsp; (' . $total . ' items)</div><br />';
  }
?>
