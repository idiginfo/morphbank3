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

  //This file allows for searching and updateing existing
  //records about mirroring sites
  //Created By: Karolina Maneva-Jakimoska
  //Date created: Jan 16 2007
  
  
  
  function mainMirror()
  {
      global  $objInfo, $config;
      
      
      echo '<div class="mainGenericContainer" style="width:680px">';
      echo '<h1>Mirror Server Information</h1>';
      
      echo '<form method="post" action="index.php" name="Mirror" enctype="multipart/form-data">';
      
      $SelectTSN = "/style/webImages/selectIcon.png";
      echoJavaScript();
      
      $link = Adminlogin();
      $userId = $objInfo->getUserId();
      $groupId = $objInfo->getUserGroupId();
      
      if (isset($_GET['row']))
          $rowNum = $_GET['row'];
      elseif ($rowNum == null)
          if ($_POST['row'] != null)
              $rowNum = $_POST['row'];
          else
              $rowNum = 0;
      
      //This code executes if the user hit the submit button
      if (isset($_POST['serverId']) && $_POST['serverId'] != null) {
          $flag_logo = 0;
          if (!isset($_FILES['new_image']) || ($_FILES['new_image']['name'] == ""))
              $logo = $_POST['logo_old'];
          else {
              
              $imagefile = $_FILES['new_image']['name'];
              $simple_name = substr($imagefile, 0, strpos($imagefile, "."));
              $simple_name .= $_POST['serverId'];
              $imagefile = $simple_name . substr($imagefile, strpos($imagefile, "."), strlen($imagefile) - 1);
              $logo = $imagefile;
              $imagesize = $_FILES['new_image']['size'];
              $imagetype = $_FILES['new_image']['type'];
              $imagetype = substr($imagetype, strpos($imagetype, "/") + 1, strlen($imagetype));
              //echo "image type is ".$imagetype;
              if ($imagetype != "gif" && $imagetype != "png") {
                  echo '<br/><br/><span style="color:red"><b>The logo image is not of required type,logo image was not updated</b></span><br/>';
                  $logo = $_POST['logo_old'];
                  $flag_logo = 1;
              } else {
                  
                  move_uploaded_file($_FILES['new_image']['tmp_name'], $config->mirrorLogos . $imagefile);
                  exec("chmod 777 " . $config->mirrorLogos . $imagefile);
                  $imagesize = getSafeImageSize($config->mirrorLogos . $imagefile);
                  $image_hight = $imagesize[1];
                  $image_width = $imagesize[0];
                  //echo "image width is ".$image_width;
                  //echo "image hight is ".$image_hight;
                  if ($image_hight < ($image_width / 2)) {
                      echo '<br/><br/><span style="color:red"><b>The logo image is not of required size, logo image was not updated</b></span><br/>';
                      $logo = $_POST['logo_old'];
                      $flag_logo = 1;
                  }
              }
          }
          
          $query = "Update ServerInfo set ";
          $queryh = "INSERT INTO History (id,userId,groupId,dateModified,modifiedFrom,modifiedTo,tableName) VALUES(";
          $queryh .= $_POST['serverId'] . "," . $objInfo->getUserId() . "," . $objInfo->getUserGroupId() . ",NOW(),'";
          $modifiedFrom = "";
          $modifiedTo = "";
          $flag = 0;
          if (trim($_POST['url']) != trim($_POST['url_old'])) {
              $query .= " url ='" . trim($_POST['url']) . "',";
              $modifiedFrom = "url: " . trim($_POST['url_old']);
              $modifiedTo = "url: " . trim($_POST['url']);
              $flag = 1;
          }
          
          if (trim($_POST['port']) != trim($_POST['port_old'])) {
              $query .= " port ='" . trim($_POST['port']) . "',";
              $modifiedFrom = "port: " . trim($_POST['port_old']);
              $modifiedTo = "port: " . trim($_POST['port']);
              $flag = 1;
          }
          if (trim($_POST['country']) != trim($_POST['country_old']) || trim($_POST['state']) != trim($_POST['state_old']) || trim($_POST['city']) != trim($_POST['city_old'])) {
              $query .= " locality ='" . trim($_POST['country']) . "," . trim($_POST['state']) . "," . trim($_POST['city']) . "',";
              $modifiedFrom = "locality: " . trim($_POST['country_old']) . "," . trim($_POST['state_old']) . "," . trim($_POST['city_old']);
              $modifiedTo = "locality: " . trim($_POST['country']) . "," . trim($_POST['state']) . "," . trim($_POST['city']);
              $flag = 1;
          }
          
          
          if (trim($_POST['login']) != trim($_POST['login_old'])) {
              $query .= " login ='" . trim($_POST['login']) . "',";
              $modifiedFrom .= "login: " . trim($_POST['login_old']);
              $modifiedTo .= " login: " . trim($_POST['login']);
              $flag = 1;
          }
          if (trim($_POST['mgroup']) != trim($_POST['mgroup_old'])) {
              $query .= " mirrorGroup =" . trim($_POST['mgroup']) . ",";
              $modifiedFrom .= " mirrorGroup: " . trim($_POST['mgroup_old']);
              $modifiedTo .= " mirrorGroup: " . trim($_POST['mgroup']);
              
              $query_db = "SELECT groupManagerId FROM Groups WHERE id=" . trim($_POST['mgroup']);
              $result = mysqli_query($link, $query_db);
              $row = mysqli_fetch_array($result);
              $query .= " admin =" . $row['groupManagerId'] . ",";
              $modifiedFrom .= " admin: " . trim($_POST['admin']);
              $modifiedTo .= " admin: " . $row['groupManagerId'];
              $flag = 1;
          }
          
          if (trim($_POST['contact']) != trim($_POST['contact_old'])) {
              $query .= " contact ='" . trim($_POST['contact']) . "',";
              $modifiedFrom .= " contact: " . trim($_POST['contact_old']);
              $modifiedTo .= " contact: " . trim($_POST['contact']);
              $flag = 1;
          }
          
          if (trim($_POST['path']) != trim($_POST['path_old'])) {
              $query .= " basePath ='" . trim($_POST['path']) . "',";
              $modifiedFrom .= " basePath: " . trim($_POST['path_old']);
              $modifiedTo .= " basePath: " . trim($_POST['path']);
              $flag = 1;
          }
          
          if (trim($_POST['imageURL']) != trim($_POST['imageURL_old'])) {
              $query .= " imageURL ='" . trim($_POST['imageURL']) . "',";
              $modifiedFrom .= " imageURL: " . trim($_POST['imageURL_old']);
              $modifiedTo .= " imageURL: " . trim($_POST['imageURL']);
              $flag = 1;
          }
          
          if (trim($_POST['password']) != trim($_POST['password_old'])) {
              $query .= " password = password('" . trim($_POST['password']) . "'),";
              $modifiedFrom .= " password: ";
              $modifiedTo .= " password: ";
              $flag = 1;
          }
          
          if (trim($logo) != trim($_POST['logo_old']) && $flag_logo != 1) {
              $query .= " logo = '" . trim($logo) . "',";
              $modifiedFrom .= " logo: " . trim($_POST['logo_old']);
              $modifiedTo .= " logo: " . trim($logo);
              $flag = 1;
          }
          if (strrpos($query, ",") == (strlen($query) - 1))
              $query = substr($query, 0, strlen($query) - 1);
          
          $query .= " where serverId = " . $_POST['serverId'];
          //echo $query;
          if ($flag == 1) {
              $results = mysqli_query($link, $query);
              if (!results)
                  echo mysqli_error($link) . "<br/>";
              else {
                  
                  $queryh .= $modifiedFrom . "','" . $modifiedTo . "','ServerInfo')";
                  //echo $queryh;
                  $result = mysqli_query($link, $queryh);
                  if (!$result)
                      mysqli_error($link);
                  else {
                      
                      if ($flag_logo != 1)
                          echo '<br/><span style="color:#17256B"><b>Mirror server information updated successfully</b></span><br/>';
                      else
                          echo '<br/><span style="color:#17256B"><b>Mirror server information updated without updating the logo</b></span><br/>';
                  }
              }
          } else {
              
              echo '<br/><span style="color:#17256B"><b>No information needs to be updated at this time</b></span><br/>';
          }
      }
      
      
      //select all the Mirroring sites currently in morphbank
      
      $query = "Select * from ServerInfo;";
      $result = mysqli_query($link, $query);
      if (!result) {
          echo '<span style="color:red"><b>There are no mirroring sites currently registered in the database</b></span>';
      } else {
          
          $numRows = mysqli_num_rows($result);
          
          if ($_GET['new'] == "1" || $_GET['last'] == "1") {
              $rowNum = $numRows - 1;
          }
          
          // fetch the row information...
          for ($index = 0; $index < $numRows; $index++) {
              $row = mysqli_fetch_array($result);
              $mirror['serverId'][$index] = $row['serverId'];
              $mirror['url'][$index] = $row['url'];
              $mirror['logo'][$index] = $row['logo'];
              $mirror['admin'][$index] = $row['admin'];
              $mirror['contact'][$index] = $row['contact'];
              $mirror['basePath'][$index] = $row['basePath'];
              $mirror['mgroup'][$index] = $row['mirrorGroup'];
              $mirror['tsn'][$index] = $row['tsn'];
              $mirror['login'][$index] = $row['login'];
              $mirror['passwd'][$index] = $row['passwd'];
              $mirror['port'][$index] = $row['port'];
              $mirror['locality'][$index] = $row['locality'];
              if (strpos($mirror['locality'][$index], ",") > 0) {
                  $mirror['country'][$index] = substr($mirror['locality'][$index], 0, strpos($mirror['locality'][$index], ","));
                  $mirror['locality'][$index] = substr($mirror['locality'][$index], strpos($mirror['locality'][$index], ",") + 1, strlen($row['locality']));
                  $mirror['state'][$index] = substr($mirror['locality'][$index], 0, strpos($mirror['locality'][$index], ","));
                  $mirror['city'][$index] = substr($mirror['locality'][$index], strpos($mirror['locality'][$index], ",") + 1, strlen($mirror['locality'][$index]));
              }
              $mirror['imageURL'][$index] = $row['imageURL'];
          }
          $size = 0;
          if ($mirror['logo'][$rowNum] != null && $mirror['logo'][$rowNum] != "") {
              $imageloc = '/images/mirrorLogos/' . $mirror['logo'][$rowNum];
              $size = getSafeImageSize($imageloc);
              $height = 50;
          }
          echo '<br/><table border="0"><tr><td>';
          if ($size != 0) {
              echo '<img src="' . $imageloc . '" name="image" height="' . $height . '" width="' . $width . '" alt="IMAGE NOT AVAILABLE" /></a></td></tr>';
          }
          echo '<tr>
             <td><b>Server address: <span style="color:red">*</span></b></td>
             <td><input type="text" name="url" value="' . $mirror['url'][$rowNum] . '" size="45" maxlength="128" title="Mirroring site URL"/><input type="hidden" name="url_old" value="' . $mirror['url'][$rowNum] . '" />
                 <input type="hidden" name="serverId" value="' . $mirror['serverId'][$rowNum] . '">
             </td>
         </tr>
         <tr><td><b>Server connection port: <span style="color:red">*</span></b></td>
             <td><input type="text" name="port" value="' . $mirror['port'][$rowNum] . '" size="5" maxlength="5" title="Server port used fro connection" />
                 <input type="hidden" name="port_old" value="' . $mirror['port'][$rowNum] . '">
            </td>
         </tr>';
          printcountry($mirror['country'][$rowNum]);
          echo '<tr>
            <td><b>State/Province: </b></td>
            <td><input type="text" name="state" value="' . $mirror['state'][$rowNum] . '" title="State/Province where the mirroring site is located"/>
            <td><input type="hidden" name="state_old" value="' . $mirror['state'][$rowNum] . '" />
            </td>
         </tr>
         <tr>
            <td><b>City: <span style="color:red">*</span></b></td>
            <td><b><input type="text" name="city" value="' . $mirror['city'][$rowNum] . '" title="City where the mirroring site is located" />
                   <input type="hidden" name="city_old" value="' . $mirror['city'][$rowNum] . '">                   
                   <input type="hidden" name="country_old" value="' . $mirror['country'][$rowNum] . '">
            </td>
         </tr>';
          
          AdminPerson($link, $mirror['admin'][$rowNum]);
          MirrorGroup($link, $mirror['mgroup'][$rowNum]);
          
          echo '<tr>
                 <td><b>FTP login name: <span style="color:red">*</span></b></td>
                 <td><input type="text" name="login" size="26" value="' . $mirror['login'][$rowNum] . '" maxlength="41"/>
                     <input type="hidden" name="login_old" value="' . $mirror['login'][$rowNum] . '" />
                 </td>
              </tr>
              <tr>
                 <td><b>FTP login password: <span style="color:red">*</span></b></td>
                 <td><input type="password" name="password" size="26" value="' . $mirror['passwd'][$rowNum] . '" maxlength="41"/>
                     <input type="hidden" name="password_old" value="' . $mirror['passwd'][$rowNum] . '" />
                 </td>
             </tr>
             <tr>
                 <td><b>Contact e-mail: <span style="color:red">*</span></b></td>
                 <td><input type="text" name="contact" value="' . $mirror['contact'][$rowNum] . '" size="45" maxlength="128" title="Contact e-mail for the Mirroring site" />
                     <input type="hidden" name="contact_old" value="' . $mirror['contact'][$rowNum] . '">
                </td>
            </tr>
            <tr>
               <td><b>Directory path: <span style="color:red">*</span></b></td>
               <td><input type="text" name="path" value="' . $mirror['basePath'][$rowNum] . '" size="45" maxlength="128" title="Enter the path where images will reside on the mirroring server" />
                   <input type="hidden" name="path_old" value="' . $mirror['basePath'][$rowNum] . '" />
              </td>
           </tr>
             <tr>
               <td><b>Images URL: </b></td>
               <td><input type="text" name="imageURL" value="' . $mirror['imageURL'][$rowNum] . '" size="45" maxlength="128" title="Enter the url (if applicable) where images will reside on the mirroring server" />
                   <input type="hidden" name="imageURL_old" value="' . $mirror['imageURL'][$rowNum] . '" />
              </td>
           </tr>

           <tr>
              <td><b>Logo image filename: </b></td>
              <td><input type="text" name="logo" "size="45"  value="' . $mirror['logo'][$rowNum] . '" maxlength="128" title="Current logo image file name" />
                 <input type="hidden" name="logo_old" value="' . $mirror['logo'][$rowNum] . '" />
              </td>
          </tr>
          <tr><td><b>Update logo image file name: </b></td>
              <td><input type="file" name="new_image" "size="45" maxlength="128" title="Click to upload new logo image file" />
                  <input type="hidden" name="row" value="' . $rowNum . '" />
              </td>
          </tr>
        </table>
      <br/>';
          
          
          // print out the navigation (next, prev, first, last)
          printNav($rowNum, $numRows);
          // print out the command buttons
      }
      printButtons($id, $MODIFY_BUTTON, $currentgroupid);
      
      // cleanup
      mysqli_free_result($result);
      echo '</form>';
      
      echo '</div>';
  }
  
  //function to create user list for selection
  function AdminPerson($link, $admin)
  {
      $query = "SELECT name FROM User where id=" . $admin;
      //echo $query;
      $result = mysqli_query($link, $query);
      if ($result) {
          $row = mysqli_fetch_array($result);
          $name = $row['name'];
          echo '<tr><td><b>Administrator: <span style="color:red">*</span></b></td>
          <td><input type="text" name="admin" value="' . $name . '" size="26" maxlength="128" readonly="true" /></td></tr>';
      }
  }
  
  //function to create group list for selection
  function MirrorGroup($link, $mgroup)
  {
      $query = "SELECT id, groupName FROM Groups ORDER BY groupName";
      $result = mysqli_query($link, $query);
      if ($result) {
          echo '<tr><td><b>Mirroring group: <span style="color:red">*</span></b><input type="hidden" name="mgroup_old" value="' . $mgroup . '"></td>
                <td><select name="mgroup" title="Select group responsable for the mirroring activities">
                <option value =\'0\'>--- Select mirroring group from the list ---</option>';
          while ($name = mysqli_fetch_array($result)) {
              if ($mgroup == $name['id'])
                  echo '<option value="' . $name['id'] . '" selected="selected">' . $name['groupName'] . '</option>';
              else
                  echo '<option value="' . $name['id'] . '">' . $name['groupName'] . ' </option>';
          }
          echo '</select></td></tr>';
      }
  }
  
  
  function printButtons($id, $BUTTON, $groupID)
  {
      global  $config;
      
      echo '<table align="right">
       <tr>
          <td>
             <a href="javascript: CheckValues();" class="button smallButton" title="Click to save the changes made to the mirror">
              <div>Update</div></a>
          </td>
          <td><a href="' . $config->domain . 'Admin/MirrorServer/addMirror.php" class="button largeButton" title="Click to add new mirror server">
              <div>Add mirror</div></a>
          </td>
          <td><a href="' . $config->domain . 'Admin/" class="button smallButton" title="Click to return to main Admin page">
              <div>Return</div></a>
          </td>
      </tr>
</table>';
  }
  
  function echoJavaScript()
  {
      echo '<script language = "JavaScript" type ="text/javascript">

  function CheckValues(){


    var form = document.forms[0];

    if(notEmpty(form.url) &&
       notEmpty(form.mgroup) &&
       notEmpty(form.login) &&
       notEmpty(form.password) &&
       notEmpty(form.port) &&
       notEmpty(form.city) &&
       notEmpty(form.path) &&
       notEmpty(form.contact))

          form.submit();
  }

 function notEmpty(elem){
   var str = elem.value;
   if(str.length == 0){
     alert("You must fill in all the required fields (*)");
     return false;
   }
   else
     return true;
 }



</script>';
  }
?>
