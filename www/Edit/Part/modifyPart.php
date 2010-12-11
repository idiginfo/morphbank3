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
  /**
   File name: modifyPart.php
   @author Neelima Jammigumpula <jammigum@scs.fsu.edu>
   @package Morphbank2
   @subpackage Submit
   @subpackage Edit
   @subpackage Part
   This script updates SpecimenPart reference table with
   appropriate update queries..
   
   Included Files:
  
   
   
   Functions:
   
   checkIfLogged : checks if the user is logged and directs to Submit page if not logged.
   groups: checks if the user selected the group he would use. Redirects to groups page
   under Submit for group selection.
   Adminlogin(): Connects to the database for writing.
   
   Message:
   /*
   Message has the following format:
   
   Table:  Sex
   
   Record:    id
   Field Name:  Name
   Old Value:  FemalE
   New Value:  Female
   
   Field Name:  Description
   Old Value:  FemalE Description
   New Value:  Female Description
   
   #of References:
   Specimen:  count
   View:    Count
   Image:    Count
   The above is repeated for each record change
   **/

if ($_REQUEST['pop']) {
	include_once('pop.inc.php');
} else {
	include_once('head.inc.php');
}

  
  
  
  checkIfLogged();
  groups();
  $link = Adminlogin();
  
  
  $rows = trim($_POST['rows']);
  $message = "Table:  Specimen Part <br /><br />";
  
  //Get all the post variables for all the records as array element.
  for ($i = 0; $i < $rows; $i++) {
      $name[$i] = mysqli_real_escape_string($link, trim($_POST[SpecimenPart . $i]));
      $description[$i] = mysqli_real_escape_string($link, trim($_POST[Description . $i]));
      $id[$i] = trim($_POST[id . $i]);
      
      $curr = mysqli_fetch_array(runQuery('SELECT * FROM SpecimenPart WHERE name =\'' . $id[$i] . '\''));
      
      if ($curr['name'] != $name[$i]) {
          $modifiedFrom[$i] = $curr['name'];
          $modifiedTo[$i] = $name[$i];
          $flag = true;
      }
      
      
      if ($curr['description'] != $description[$i]) {
          $modifiedFrom[$i] .= $curr['description'];
          $modifiedTo[$i] .= $description[$i];
          $flag = true;
      }
      
      if (($curr['name'] != $name[$i]) && ($curr['description'] != $description[$i])) {
          $query[$i] = 'UPDATE SpecimenPart SET  name = \'' . $name[$i] . '\', description = \'' . $description[$i] . '\' where name = \'' . $id[$i] . '\' ;';
          $message .= 'Record:  ' . $id[$i] . '<br />Field Name:  Specimen Part <br />Old Value:  ' . $curr['name'] . '<br />New Value:  ' . $name[$i] . '<br />' . 'Field Name:  Description <br />Old Value:  ' . $curr['description'] . '<br />New Value:  ' . $description[$i] . '<br />';
      } elseif ($curr['name'] != $name[$i]) {
          $query[$i] = 'UPDATE SpecimenPart SET  name = \'' . $name[$i] . '\' where name = \'' . $id[$i] . '\' ;';
          $message .= 'Record:  ' . $id[$i] . '<br />Field Name:  Specimen Part <br />Old Value:  ' . $curr['name'] . '<br />New Value:  ' . $name[$i] . '<br />';
      } elseif ($curr['description'] != $description[$i]) {
          $query[$i] .= ' UPDATE SpecimenPart SET description = \'' . $description[$i] . '\'  where name = \'' . $id[$i] . '\' ;';
          $message .= '<br />Record:  ' . $id[$i] . '<br />Field Name:  Description<br />Old Value:  ' . $curr['description'] . '<br />New Value:  ' . $description[$i] . '<br />';
      }
      
      if ($flag) {
          $viewCount = mysqli_fetch_array(runQuery('SELECT COUNT(*) AS viewCount FROM View WHERE specimenPart = \'' . $id[$i] . '\';'));
          
          $message .= '<br />No. of Views using this value: ' . $viewCount['viewCount'] . '<br />';
          $flag = false;
      }
  }
  
  /*echo 'message: '. $message. '<br /><br />Modified From: ' .$modifiedFrom. '<br />Modified To: ' .$modifiedTo;
   for($i = 0; $i < $rows; $i++)
   echo 'Query:  ' .$query[$i]. '<br />';
   */
  
  if (checkAuthorization('SpecimenPart', $objInfo->getUserId(), $objInfo->getUserGroupId(), 'Modify')) {
      for ($i = 0; $i < $rows; $i++) {
          if ($query[$i]) {
              //echo $query[$i];
              $results = mysqli_query($link, $query[$i]);
              
              if ($results) {
                  History($id[$i], $objInfo->getUserId(), $objInfo->getUserGroupId(), $modifiedFrom[$i], $modifiedTo[$i], "SpecimenPart");
              } else {
                  echo $query . '<BR>';
                  echo mysql_error($link);
              }
          }
      }
      
      $url = 'index.php';
      //$url = 'index.php?code=1&id='.$curr['id']; 
      header("location: " . $url);
      exit;
  } else {
      
      
      $url = '../mailpage.php?message=' . $message;
      header("location: " . $url);
      exit;
  }
  // end of else part of checkAuthorization
?>
