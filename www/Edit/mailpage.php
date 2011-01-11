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
   File name: mailpage.php
   @author Neelima Jammigumpula <jammigum@scs.fsu.edu>
   @package Morphbank2
   @subpackage Submit
   @subpackage Edit
   This is the standard script that calls sends  a mail to mbadmin for correction of tables that are updated by admin people only.
   
   Included Files:
   head.inc.php - Used for the standard head section.
   
   
   Functions:
   
   checkIfLogged : checks if the user is logged and directs to Submit page if not logged.
   groups: checks if the user selected the group he would use. Redirects to groups page
   under Submit for group selection.
   initHtml: Sets the tilte of the page.
   echoHead: Puts the standard head section of MorphBank and primary html tags.
   finishHtml: Finishes the html by appropriately.
   **/
  

if (isset($_GET['pop'])){
	include_once('pop.inc.php');
} else {
	include_once('head.inc.php');
} 
 
  
  checkIfLogged();
  groups();
  
  // The beginnig of HTML
  $title = 'Email';
  initHtml($title, null, null);
  
  // Add the standard head section to all the HTML output.
  echoHead(false, $title);
  
  if ($_GET['pop']) {
      echo '<div class="popContainer" style="width: 600px;">
    <form name = "mailinput" method = "post" action = "mailto.php?pop=yes">';
  } else {
      
      echo '<div class="mainGenericContainer" style="width: 600px;">
       <form name = "mailinput" method = "post" action = "mailto.php">';
  }
?>
    <h3> You do not have permission to edit records in the table <?php
  echo $_GET['object'];
?>. Would you like to request a change by sending a message to morphbank team?</h3>
    <br /> <br />
      <table>
         <tr>
      <td><textarea name = "message" cols = "60" rows = "10" wrap = "physical" readonly = "readonly"><?php
  if ($_GET['message'])
      //  echo trim($_GET['message']); 
      echo str_replace('<br />', "\n", trim($_GET['message']));
  else
      echo str_replace('<br />', "\n", trim($_POST['message']));
?></textarea></td>
         </tr>
         <tr> <td> <br /> </td> </tr>
         <tr>
      <td><textarea name = "comments" cols = "60" rows = "8" wrap = "physical">User Comments: </textarea>
      <td>
         <tr>
         <tr>
            <td align = "right"> <a href = "javascript: document.mailinput.submit()" 
          class="button smallButton"><div>Send</div> </a>
        <?php
  if ($_GET['pop'])
      echo ' <a href = "javascript: window.close()" class="button smallButton"><div>Cancel</div> </a>';
  else
      echo ' <a href = "javascript: top.location =\'' . $config->domain . 'MyManager/\'" class="button smallButton"><div>Cancel</div> </a>';
?>
      </td>
         </tr>
    </table>
     </form>
     </div>
<?php
  // Finish with end of HTML
  finishHtml();
?>
