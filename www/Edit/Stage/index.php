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
   File name: index.php
   @author Neelima Jammigumpula <jammigum@scs.fsu.edu>
   @package Morphbank2
   @subpackage Submit
   @subpackage Edit
   @subpackage Stage
   This is the standard script that calls editStage function which displays the form
   for editing DevelopmentalStage reference table.
   
   Included Files:
   head.inc.php - Used for the standard head section.
   
   
   Functions:
   
   checkIfLogged : checks if the user is logged and directs to Submit page if not logged.
   groups: checks if the user selected the group he would use. Redirects to groups page
   under Submit for group selection.
   initHtml: Sets the tilte of the page.
   echoHead: Puts the standard head section of MorphBank and primary html tags.
   
   editStage: The function displays the form with all the GUI elements and their javascript
   funtions.
   
   finishHtml: Finishes the html by appropriately.
   **/
  

if ($_REQUEST['pop']) {
	include_once('pop.inc.php');
} else {
	include_once('head.inc.php');
}

  checkIfLogged();
  groups();
  
  include_once('editStage.php');
  
  // The beginnig of HTML
  $title = 'Edit Developmental Stage';
  initHtml($title, null, null);
  
  // Add the standard head section to all the HTML output.
  echoHead(false, $title);
  echo '<div class="mainGenericContainer" style="width: Auto;">';
  
  if ($_GET['code'] == '1') {
      echo '<h3><b>Update successfull.</b></h3><br /><br />';
  }
  
  editStage();
  
  echo '</div>';
  
  
  // Finish with end of HTML
  finishHtml();
?>
