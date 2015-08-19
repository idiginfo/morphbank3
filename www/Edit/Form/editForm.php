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
   File name: editForm.php
   @author Neelima Jammigumpula <jammigum@scs.fsu.edu>
   @package Morphbank2
   @subpackage Submit
   @subpackage Edit
   @subpackage Form
   Included Files: editChanged.php
   This has a simple function editChanged($numRows) that creates the javascript function based on
   the number of rows returned from the query which is passed to it.
   
   This script has only one function editForm that displays the GUI and form data from the database.
   */
  
  function editForm()
  {
      //These are declared global so that they can be used anywhere in the function. These are defined in config.php
      
      
      
      if ($_GET['offset'])
          $offset = $_GET['offset'];
      else
          $offset = 0;
      
      // Gets all the records from the table 20 at a time from given offset.
      $totalNumRows = mysqli_num_rows(runQuery('SELECT name AS Form, description AS Description FROM Form ORDER BY name;'));
      
      $result = runQuery('SELECT name AS Form, description AS Description FROM Form ORDER BY name LIMIT ' . $offset . ', 20;');
      
      $numRows = mysqli_num_rows($result);
      
      echo '<form  name = "editForm" method = "post" action = "modifyForm.php" onsubmit = "return checkall()" >
    <h1><b>Edit Form</b></h1>
          <br /><br />';
      
      //*****************************************************************************
      // This section goes through each column and prints it out to the screen with *
      // that value as taken from the database.                *
      //*****************************************************************************
      
      /* Hidden inputs
       
       rows: Holds the number of rows or records value which is used in modifyAaa scripts for update queries.
       idXX: Keeps the id of each record which will be used in modifyAaa script.
       A javascript function changed() manipulates this form input as required.
       */
      
      if ($result) {
          echo '<input type="hidden" name="rows" value="' . $numRows . '" />
      <input type="hidden" name="flag" value = "false"/>
          <table width="600"> <tr><td><b>Form <span class="req">*</span></b></td>
        <td> <b> Description <span class="req">*</span></b></td></tr>';
          
          for ($i = 0; $i < $numRows; $i++) {
              mysqli_data_seek($result, $i) or die(mysqli_error($link));
              $row = mysqli_fetch_array($result, MYSQL_ASSOC);
              
              echo '<tr>';
              
              foreach ($row as $column => $val) {
                  $META = mysqli_fetch_field($result);
                  $SIZE = $META->max_length;
                  
                  if ($column == "Form") {
                      echo '<td><input type="hidden" name="id' . $i . '" value="' . $val . '" />
                                        <input type="text" size ="25" name="' . $column . $i . '" value="' . $val . '" onchange = "changed()" /></td>';
                  } else
                      echo '<td><input type="text" size ="30" name="' . $column . $i . '" value="' . $val . '" onchange = "changed()" /> </td>';
              }
              echo '</tr>';
          }
      } else {
          print "No records Found";
      }
      // cleanup
      freeResult($result);
?>
  </table>
 <br /> <br />  <strong><span class="req">* -Required</span></strong>
  <table width="500">
    <tr>
      <td align = "right"> 
        <a href = "javascript: checkall()" class="button smallButton"><div>Update</div> </a>
        <a href = "javascript: top.location = '../'" class="button smallButton"><div>Cancel</div> </a>
      </td>
    </tr> 
  </table>
        <?php
      if ($totalNumRows > 20)
          Navigation($totalNumRows, $offset);
?>
    </form>

<?php
      include_once('editjavascript.php');
      javascript($numRows);
  }

?>

