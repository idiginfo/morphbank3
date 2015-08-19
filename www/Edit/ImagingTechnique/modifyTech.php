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
   File name: modifyImgTech.php
   @author Neelima Jammigumpula <jammigum@scs.fsu.edu>
   @package Morphbank2
   @subpackage Submit
   @subpackage Edit
   @subpackage ImagingTechnique
   This script updates ImagingTechnique reference table with
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
  $message = "Table:  Imaging Technique <br /><br />";
  
  //Get all the post variables for all the records as array element.
  for ($i = 0; $i < $rows; $i++) {
      $name[$i] = mysqli_real_escape_string($link, trim($_POST[ImagingTechnique . $i]));
      $description[$i] = mysqli_real_escape_string($link, trim($_POST[Description . $i]));
      $id[$i] = mysqli_real_escape_string($link, trim($_POST[id . $i]));
      
      $curr = mysqli_fetch_array(runQuery('SELECT * FROM ImagingTechnique WHERE name =\'' . $id[$i] . '\''));
      
      
      if ($curr['name'] != $name[$i]) {
          $modifiedFrom[$i] = 'Imaging Technique: ' . $curr['name'] . ' ';
          $modifiedTo[$i] = 'Imaging Technique: ' . $name[$i] . ' ';
          $updates = 'name = \'' . $name[$i] . '\'';
          
          $message .= 'Record:  ' . $id[$i] . '<br />Field Name:  Imaging Technique:  <br />Old Value:  ' . $curr['name'] . '<br />New Value:  ' . $name[$i];
      }
      
      if ($curr['description'] != $description[$i]) {
          $modifiedFrom[$i] .= 'Description: ' . $curr['description'] . ' ';
          $modifiedTo[$i] .= 'Description: ' . $description[$i] . ' ';
          
          if ($updates != '') {
              $updates = ', description = \'' . $description[$i] . '\'';
              $message .= '<br />Field Name:  Description<br />Old Value:  ' . $curr['description'] . '<br />New Value:  ' . $description[$i];
          } else {
              $updates = ' description = \'' . $description[$i] . '\'';
              $message .= 'Record:  ' . $id[$i] . '<br />Field Name:  Description<br />Old Value:  ' . $curr['description'] . '<br />New Value:  ' . $description[$i];
          }
      }
      
      if ($updates != '') {
          $query[$i] = "CALL ImagingTechniqueUpdate(@oMsg, '" . $id[$i] . "', \"" . $updates . "\", " . $objInfo->getUserGroupId() . ", " . $objInfo->getUserId() . ", \"" . $modifiedFrom[$i] . "\", \"" . $modifiedTo[$i] . "\", \"Sex\");";
          $viewCount = mysqli_fetch_array(runQuery('SELECT COUNT(*) AS viewCount FROM View WHERE imagingTechnique = \'' . $id[$i] . '\';'));
          $message .= '<br />No. of Views using this value: ' . $viewCount['viewCount'] . '<br />';
      }
  }
  
  /*echo 'message: '. $message. '<br /><br />Modified From: ' .$modifiedFrom. '<br />Modified To: ' .$modifiedTo;
   for($i = 0; $i < $rows; $i++)
   echo 'Query:  ' .$query[$i]. '<br />';
   */
  
  if (checkAuthorization(null, $objInfo->getUserId(), $objInfo->getUserGroupId(), 'Modify')) {
      for ($i = 0; $i < $rows; $i++) {
          if ($query[$i] != '') {
              //echo $query[$i];
              $results = mysqli_query($link, $query[$i]);
              
              if ($results) {
                  History($id[$i], $objInfo->getUserId(), $objInfo->getUserGroupId(), $modifiedFrom[$i], $modifiedTo[$i], "ImagingTechnique");
              } else {
                  echo $query . '<BR>';
                  echo mysqli_error($link);
              }
              //echo $query[$i];
          }
      }
      
      $url = 'index.php';
      header("location: " . $url);
      exit;
  } else {
      $url = '../mailpage.php?message=' . $message;
      header("location: " . $url);
      exit;
  }
  // end of else part of checkAuthorization
?>
