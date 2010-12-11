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
  //This file was created for generating reports for ITIS
  //Two types of reports can be generated for new names
  //and for corrections on the existing ones
  //
  //Created by: Karolina Maneva-Jakimoska
  //Cretaed on: February 20th 2007
  
  
  
  function itisReports()
  {
      global  $objInfo, $config;
      
      $link = AdminLogin();
      
      if (!isset($_POST['returnUrl']))
          $returnUrl = $_SERVER['HTTP_REFERER'];
      else
          $returnUrl = $_POST['returnUrl'];
      
      echoJavaScript();
      echo '<form name="itis" action="index.php" method="post" >
 
        <table >
          <tr>
            <td><input type="radio" name="selection" value="1" checked="checked"/><b>New names report</b></td>
          </tr>
          <tr><td><input type="radio" name="selection" value="0" /><b>Name corrections report</b></td>
          </tr>
          <tr>&nbsp;</tr>
        </table><br/><br/>
        <table width="600px">';
      
      DisplayKingdoms($link);
      
      DisplayRanks($link);
      echo '<tr>
               <td><b>Taxon Id/ Name: </b></td>
               <td><input type="text" name="tsn" size="8" title="Enter the tsn of interest" />
               <b>/</b><input type="text" name="scientific_name" readonly="true" size="45" maxlength="128" title="Enter taxon name of your interest. All taxa having this name as a parent will also be included in the report"/>';
?>
               <a href="javascript:openPopup('<?php
      echo $config->domain;
?>Admin/TaxonSearch/index.php?pop=YES&searchonly=0&tsn=0&TSNtype=6')" >
             <?php
      echo '<img src="/style/webImages/selectIcon.png" /></a>
             </td>
             </tr>
             <tr>
               <td><b>Updates from date: </b></td><td><input type="text" name="date_from" size="11" maxlength="11" title="Enter the first date in yyyy-mm-dd format"/>
               <b>to date: </b><input type="text" name="date_to" size="11" maxlength="11" title="Enter the last date in yyyy-mm-dd format"/></td>
         </table>';
      
      //table to hold the buttons
      echo '<table align="right">
              <tr>
                <td><a href="javascript:document.forms[0].submit();" class="button smallButton"><div>Submit</div></a></td>
                <td><a href="' . $returnUrl . '" class="button smallButton"><div>Cancel</div></a></td>
              </tr>
             </table>
           </form>';
      
      echo '</div>';
  }
  //end of function itisReports
  
  //function to create Kingdoms selection box
  function DisplayKingdoms($link)
  {
      $query = "SELECT kingdom_id, kingdom_name FROM Kingdoms";
      $result = mysqli_query($link, $query);
      $numRows = mysqli_num_rows($result);
      echo '<tr><td><b>Kingdom: </b></td><td>
        <select name="kingdom">
        <option value="0">-Select Kingdom-</option>';
      for ($i = 0; $i < $numRows; $i++) {
          $row = mysqli_fetch_array($result);
          echo '<option value="' . $row['kingdom_id'] . '">' . $row['kingdom_name'] . '</option>';
      }
      echo '<option value="7">All kingdoms(default)</option>
     </select>
   </td></tr>';
  }
  // end of DisplayKingdoms
  
  //function that creates Rank selection boxes
  function DisplayRanks($link)
  {
      $query = "SELECT distinct rank_id, rank_name FROM TaxonUnitTypes";
      $result = mysqli_query($link, $query);
      $result1 = mysqli_query($link, $query);
      $numRows = mysqli_num_rows($result);
      echo '<tr><td><b>Highest rank: </b></td><td>
           <select name="highest_rank">
              <option value="0">-Select Highest Rank-</option>';
      for ($i = 0; $i < $numRows; $i++) {
          $row = mysqli_fetch_array($result);
          echo '<option value="' . $row['rank_id'] . '">' . $row['rank_name'] . '</option>';
      }
      echo '<option value="7">All ranks(default)</option>
      </select>

      </td></tr><tr><td><b>Lowest rank: </b></td><td>
         <select name="lowest_rank">
            <option value="0">-Select Lowest Rank-</option>';
      for ($j = 0; $j < $numRows; $j++) {
          $row = mysqli_fetch_array($result1);
          echo '<option value="' . $row['rank_id'] . '">' . $row['rank_name'] . '</option>';
      }
      echo '<option value="7">All ranks(default)</option>
       </select>
    </td>
  </tr>';
  }
  //end of  function DisplayRanks
  
  function echoJavaScript()
  {
      echo '<script type="text/javascript">

 function updateParentTSN(tsn,name){
        document.forms[0].tsn.value=tsn;
        document.forms[0].scientific_name.value=name;
 }


</script>';
  }
?>
