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

  // Menu links from mbMenu_data.php
  //$browseThumbList = $mainMenu[1]['href'].'ThumbList/';
  //$browseByImageHref = $mainMenu[1]['href'].'ByImage/';
  //$browseByTaxonHref = $mainMenuOptions[6]['href'];
  //$browseByNameHref = $mainMenuOptions[7]['href'];
  $browseByViewHref = $mainMenuOptions[8]['href'];
  $browseByTaxonHref = $config->domain . 'Browse/ByTaxon';
  $browseByNameHref = $config->domain . 'Browse/ByName';
  $browseByImageHref = $config->domain . 'Browse/ByImage/';
  $annotateNameHref = $config->domain . 'Admin/TaxonSearch/annotateTSN.php';
  $itisHref = "http://www.itis.gov/servlet/SingleRpt/SingleRpt?search_topic=TSN&search_value=";
  
  if (!isset($_GET['letter']))
      $_GET['letter'] = 'A';
  
  if (!isset($_GET['showLetter']))
      $_GET['showLetter'] = 'all';
  
  // simply echos the following contents to the web browser.
  // This helps keep the main scripts simpler to read.
  function mainBrowseByName($title)
  {
      global $browseByImageHref, $browseByNameHref, $browseByTaxonHref, $annotateNameHref, $itisHref;
      
      echo '<a name="Top"></a>
        <div class="mainGenericContainer" style="width:700px">
      <h1 align="center">' . $title . '</h1>
      <p></p>
    ';
      
      echoJSFuntionShow();
      
      // Get the A to Z letters to show as links at the top of the page.
      $results = runSqlQuery($_GET['letter']);
      getAToZLetters();
      /*
       echo '&nbsp;&nbsp;&nbsp;(<a href="#Notes">Notes</a>)
       <p></p>';
       */
      
      $tableWidth = 690;
      $colums = 2;
      $col = 1;
      $l = "";
      if (!mysqli_data_seek($results, 0)) {
          // move the pointer of the result to the first row
          echo '<div class="error"> No records in Morphbank</div>';
          exit;
      }
      
      echo '<table class="browseByName" border="0">';
      $numRows = mysqli_num_rows($results);
      
      for ($i = 0; $i < $numRows; $i++) {
          $array = mysqli_fetch_array($results);
          if ($_GET['letter'] != $array['letter'])
              continue;
          
          if ($l != $array['letter']) {
              // New row
              if ($col > 1)
                  echo '</tr>';
              echo '<tr><td colspan="' . $colums . '"><hr/></td></tr>
          <tr> 
            <td colspan="' . ($colums - 1) . '" valign="middle">
              <a name="letter' . $array['letter'] . '"></a><b>' . $array['letter'] . '</b></td>
          </tr>';
              $col = 1;
          }
          $l = $array['letter'];
          if ($col == 1)
              echo '<tr>';
          echo '<td width="' . (int)($tableWidth / $colums) . 'px" valign="middle">' . $array['scientificName'] . ' (' . $array['imagesCount'] . ') 
          <a href="' . $browseByImageHref . '?tsn=' . $array['tsn'] . '"><img border="0" alt="images" src="/style/webImages/camera-min.gif" title="List of images"/></a>
                                          <a href="javascript: openPopup(\'' . $annotateNameHref . '?tsn=' . $array['tsn'] . '&amp;pop=yes\')"><img border="0" alt="images" src="/style/webImages/annotate-trans.png" title="Annotate Taxon name"/></a>
          <a href="' . $browseByTaxonHref . '?tsn=' . $array['tsn'] . '"> <img border="0" alt="tree" src="/style/webImages/hierarchryIcon.png" title="See hierarchy tree" /></a>';
          if ($array['tsn'] < 999000000 && $array['tsn'])
              //$array['tsn'] == 0 => life node is not in ITIS
              echo '<a href="' . $itisHref . $array['tsn'] . '" target="itis"> <img border="0" alt="ITIS info" src="/style/webImages/itisLogo-trans.png" 
            title="See ITIS classification for ' . $array['tsn'] . '" /></a>';
          echo '</td>';
          if ($col == $colums)
              echo '</tr>';
          $col += 1;
          if ($col > $colums)
              $col = 1;
          continue;
      }
      
      if ($col > 1)
          echo '</tr>';
      echo "</table>";
      
      mysqli_data_seek($results, 0);
      echo "<p></p>";
      getAToZLetters();
      /*
       echo '<a name="Notes"></a><hr/>
       <br/><br/>
       <strong>Notes:</strong><br/><br/>
       - This taxonomic classification is based on the <b>I</b>ntegrated <b>T</b>axonomic <b>I</b>nformation <b>S</b>ystem
       (<a href="http://www.itis.usda.gov" target="new">ITIS</a>) database maintained by the United States Department of Agriculture.<br/>
       - When a taxonomic Id has a value greater then [999000000] it is cosidered a temporaly id. Temporary Ids are
       assigned to Taxon names that have not been officially entered into the ITIS database.<br/>
       - The number of images shown beside the taxon names may not be the actual count. Image counts are updated periodically.
       Values that remain constant over several hours can be assumed to be accurate.<br/>
       ';
       */
      //mainGenericContainer 
      echo '</div>';
  }
  
  
  function echoJSFuntionShow()
  {
      echo '
    <script language="javascript" type="text/javascript">
      function showLetters(form) {
        form.submit();
      }
    </script>';
  }
  
  function runSqlQuery($letter)
  {
      global $link;

      $sql = "SELECT tsn, scientificName, letter, imagesCount FROM `Tree` WHERE `imagesCount`>0 AND `usage`!='not public'";
      $sql .= " AND letter='" . mysql_escape_string($letter) . "'";
      $sql .= " ORDER BY `letter`, `scientificName`";

      $result = mysqli_query($link, $sql) or die("Could not run query\n" . $sql . "\n" . mysqli_error($link));
      
      return $result;
  }
  
  function getAToZLetters()
  {
      global $browseByNameHref;
      static $letter = array();
      //echo count($letter).'<br>';
      $href = $browseByNameHref . '?showLetter=one&letter=';
      
      for ($i = 0; $i < 26; $i++) {
          if ($i > 0) {
              echo '|';
          }
          echo '<a href="' . $href . chr(65 + $i) . '"> <b>' . chr(65 + $i) . '</b> </a>';
      }
  }
?>
