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
  // Menu links from mbMenu_data.php
  $browseThumbList = $config->domain . 'Browse/ThumbList/';
  $browseByImage = $config->domain . 'Browse/ByImage/';
  $annotationHref = $config->domain . 'Admin/TaxonSearch/annotateTSN.php';
  
  // simply echos the following contents to the web browser.
  // This helps keep the main scripts simpler to read.
  
  $returnUrl = $_SERVER['HTTP_REFERER'];
  
  if (!isset($_GET['majorCategories']))
      $_GET['majorCategories'] = '0';
  
  if (!isset($_GET['noSynonyms']))
      $_GET['noSynonyms'] = '0';
  
  if (!isset($_GET['images']))
      $_GET['images'] = '0';
  
  
  function microtime_float()
  {
      list($usec, $sec) = explode(" ", microtime());
      return((float)$usec + (float)$sec);
  }
  
  function echoJSFuntionShow()
  {
      echo '
    <script language="JavaScript" type="text/javascript">
      function showCategories( form) {
        form.submit();
        //location.reload(true);
      }
    </script>';
  }
  
  function printEmptySet()
  {
      echo '<div class="innerContainer7">
      <h2>Empty set.</h2>
        <ul>
           <li>This tsn id doesn\'t exist</li>
        </ul>
      </div>';
  }
  
  function mainBrowseByTaxonTree($title)
  {
      
      include('treeview.inc.php');
      // taken from PHPLib
      include('lib/template.inc.php');
      include('lib/layersmenu.inc.php');
      include('webServices.inc.php');
      
      global $browseThumbList, $browseByImage;
      
      // code to make the file
      //$startTime = microtime_float();
      $treeView = new TreeView();
      $treeView->setExtraLinkHref($browseByImage);
      $treeView->setExtraLinkHref1($itisHref);
      $treeView->setExtraLinkHref2('../../Admin/TaxonSearch/annotateTSN.php');
      
      // Find the required file, if not present, then make it
      $tsn = 0;
      if (isset($_GET['tsn'])) {
          $tsn = $_GET['tsn'];
          // Set the TSN to the one sent (via GET)    
      }
      
      $treeString = $treeView->createTree($tsn, $_GET['majorCategories'], $_GET['noSynonyms'], $_GET['images']);
      
      //$TreeViewTime = microtime_float() - $startTime;
      
      //$mid = new LayersMenu(340, 20, 20);
      
      
      $mid = new LayersMenu();
      $mid->setPrependedUrl("?tsn=");
      $mid->setShowMajorCategories($_GET['majorCategories']);
      $mid->setShowSynonyms($_GET['noSynonyms']);
      $mid->setShowWithImages($_GET['images']);
      $mid->setLibjsdir('/js/');
      $mid->setLibdir('/includes/lib/');
      $mid->setImgdir('/style/webImages/');
      $mid->setImgwww('/style/webImages/');
      
      $mid->setDownArrowImg('down-nautilus.png');
      $mid->setForwardArrowImg('forward-nautilus.png');
      if ($treeString != "") {
          $mid->setMenuStructureString($treeString);
          $mid->parseStructureForMenu("treemenu1");
      }
      
      echo '<div class="mainGenericContainer" style="width:770px">
      <h1 align="center">' . $title . '</h1><br/><br/>';
      /*
       <tr>
       <td> TreeView took:'.$TreeViewTime.' seconds <br>
       Java process took:'.$MakeTree.' seconds <br>
       </td>
       </tr>
       */
      echoJSFuntionShow();
      
      echo '<form action="index.php" method="get">
      <input name="tsn" value="' . $tsn . '" type="hidden" />
                        <input type="checkbox" name="majorCategories" onclick="showCategories(this.form)" value="1" title="Check if you want to see only the major categories"' . (($_GET['majorCategories'] == '1') ? 'checked="checked" ' : '') . ' /> Only major categories &nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="noSynonyms" onclick="showCategories(this.form)" value="1" title=" Check if you want to see only valid/accepted taxa by ITIS"' . (($_GET['noSynonyms'] == '1') ? 'checked="checked" ' : '') . ' /> Only valid/accepted taxa &nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="images" onclick="showCategories(this.form)" value="1" title="Check if you want to see only taxa associated with images"' . (($_GET['images'] == '1') ? 'checked="checked" ' : '') . ' /> Only taxa with images &nbsp;&nbsp;&nbsp;
      
            </form>';
      
      
      echo '<table width="100%" cellspacing="0" border="1">
      <tr>
        <td height="500px" valign="top">';
      //echo '<div class="browseTreeContainer">';
      if ($treeString != "")
          print $mid->newTreeMenu("treemenu1");
      else {
          printEmptySet();
      }
      //echo $treeString;
      echo '    </td>
      </tr>
    </table>';
      /*
       echo '  <br><br>
       <strong>Notes:</strong><br><br>
       - This taxonomic classification is based on the <b>I</b>ntegrated <b>T</b>axonomic <b>I</b>nformation <b>S</b>ystem
       (<a href="http://www.itis.usda.gov" target="new">ITIS</a>) database maintained by the United States Department of Agriculture.<br>
       - When a taxonomic Id has a value greater then [999000000] it is cosidered a temporaly id. Temporary Ids are
       assigned to Taxon names that have not been officially entered into the ITIS database.<br>
       - The number of images shown beside the taxon names may not be the actual count. Image counts are updated periodically.
       Values that remain constant over several hours can be assumed to be accurate.<br>
       ';*/
      //mainGenericContainer
      echo '</div>  ';
  }
?>
