<?php
//This file allows to the coordinator of the mirroring
//group to change any of the mirror settings recorded in
//morhbank including username and password
//Created By: Karolina Maneva-Jakimoska
//Date created: Jan 25th 2007



include_once('head.inc.php');
include_once('Admin/admin.functions.php');

checkIfLogged();

// The beginnig of HTML
$title = 'Mirror Server Information';
initHtml($title, null, null);

// Add the standard head section to all the HTML output.
echoHead(false, $title);

global  $objInfo, $config;

echo '<div class="mainGenericContainer" style="width:680px">';
echo '<h1>Mirror Server Information</h1>';

echo '<form method="post" action="mirror.php" enctype="multipart/form-data">';
$SelectTSN = "/style/webImages/selectIcon.png";

echoJavaScript();

$link = Adminlogin();
$userId = $objInfo->getUserId();
$groupId = $objInfo->getUserGroupId();

if (!isset($_POST['returnUrl'])) {
	$returnUrl = $_SERVER['HTTP_REFERER'];
} else {
	$returnUrl = $_POST['returnUrl'];
}


//This code executes if the user hit the submit button
if (isset($_POST['serverId']) && $_POST['serverId'] != null) {
	if (!isset($_FILES['new_image']) || ($_FILES['new_image']['name'] == "")) {
		$logo = $_POST['logo_old'];
	} else {
		$imagefile = $_FILES['new_image']['name'];
		$simple_name = substr($imagefile, 0, strpos($imagefile, "."));
		$simple_name .= $_POST['serverId'];
		$image_new = $simple_name . substr($imagefile, strpos($imagefile, "."), strlen($imagefile) - 1);
		$logo = $image_new;
		$imagesize = $_FILES['new_image']['size'];
		$imagetype = $_FILES['new_image']['type'];
		$imagetype = substr($imagetype, strpos($imagetype, "/") + 1, strlen($imagetype));
		//echo "image type is ".$imagetype;
		if ($imagetype != "gif" && $imagetype != "png") {
			echo '<br/><br/><span style="color:red"><b>The logo image is not of required type,logo image not updated</b></span><br/>';
			$logo = $_POST['logo_old'];
			$flag_logo = 1;
		} else {
			move_uploaded_file($_FILES['new_image']['tmp_name'], $config->mirrorLogos . $imagefile);
			exec("chmod 777 " . $config->mirrorLogos . $imagefile);
			$imagesize = getimagesize($config->mirrorLogos . $imagefile);
			$image_hight = $imagesize[1];
			$image_width = $imagesize[0];
			//echo "image width is ".$image_width;
			//echo "image hight is ".$image_hight;
			if ($image_hight < ($image_width / 2)) {
				echo '<br/><br/><span style="color:red"><b>The logo image is not of required size, logo image not updated</b></span><br/>';
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
	$flag1 = 0;
	$flag = 0;
	if (trim($_POST['url']) != trim($_POST['url_old'])) {
		$query .= " url ='" . trim($_POST['url']) . "',";
		$modifiedFrom = "url: " . trim($_POST['url_old']);
		$modifiedTo = "url: " . trim($_POST['url']);
		$flag1 = 1;
	}
	if (trim($_POST['country']) != trim($_POST['country_old']) || trim($_POST['state']) != trim($_POST['state_old']) || trim($_POST['city']) != trim($_POST['city_old'])) {
		$query .= " locality ='" . trim($_POST['country']) . "," . trim($_POST['state']) . "," . trim($_POST['city']) . "',";
		$modifiedFrom = "locality: " . trim($_POST['country_old']) . "," . trim($_POST['state_old']) . "," . trim($_POST['city_old']);
		$modifiedTo = "locality: " . trim($_POST['country']) . "," . trim($_POST['state']) . "," . trim($_POST['city']);
		$flag1 = 1;
	}

	if (trim($_POST['login']) != trim($_POST['login_old'])) {
		$query .= " login ='" . trim($_POST['login']) . "',";
		$modifiedFrom .= "login: " . trim($_POST['login_old']);
		$modifiedTo .= " login: " . trim($_POST['login']);
		$flag1 = 1;
	}

	if (trim($_POST['contact']) != trim($_POST['contact_old'])) {
		$query .= " contact ='" . trim($_POST['contact']) . "',";
		$modifiedFrom .= " contact: " . trim($_POST['contact_old']);
		$modifiedTo .= " contact: " . trim($_POST['contact']);
		$flag1 = 1;
	}

	if (trim($_POST['basePath']) != trim($_POST['basePath_old'])) {
		$query .= " basePath ='" . trim($_POST['basePath']) . "',";
		$modifiedFrom .= " basePath: " . trim($_POST['basePath_old']);
		$modifiedTo .= " basePath: " . trim($_POST['basePath']);
		$flag1 = 1;
	}

	if (trim($_POST['imageURL']) != trim($_POST['imageURL_old'])) {
		$query .= " imageURL ='" . trim($_POST['imageURL']) . "',";
		$modifiedFrom .= " imageURL: " . trim($_POST['imageURL_old']);
		$modifiedTo .= " imageURL: " . trim($_POST['imageURL']);
		$flag1 = 1;
	}


	if ($_POST['passwd'] != "") {
		$pwresults = mysqli_query($link, "select serverId from ServerInfo where passwd=password('" . $_POST['passwd'] . "')");
		if ($pwresults) {
			$pwrow = mysqli_fetch_array($pwresults);
			$server = $pwrow['serverId'];
			if ($server != $_POST['serverId'])
			$flag = 1;
			else {

				if (strlen($_POST['new_passwd']) < 6)
				$flag = 4;
				else {

					if ($_POST['new_passwd'] == $_POST['reentered_passwd']) {
						$query .= "passwd = password(\"" . $_POST['new_passwd'] . "\"),";
					} else {
						$flag = 2;
					}
				}
			}
		} else {
			$flag = 3;
		}
		if ($flag == 0) {
			$modifiedFrom .= " passwd: ";
			$modifiedTo .= " passwd:";
			$flag1 = 1;
		}
	}

	if (trim($logo) != trim($_POST['logo_old']) && $flag_logo != 1) {
		$query .= " logo = '" . trim($logo) . "',";
		$modifiedFrom .= " logo: " . trim($_POST['logo_old']);
		$modifiedTo .= " logo: " . trim($logo);
		$flag1 = 1;
	}
	if (strrpos($query, ",") == (strlen($query) - 1)) {
		$query = substr($query, 0, strlen($query) - 1);
	}

	$query .= " where serverId = " . $_POST['serverId'];
	//echo $query;
	if ($flag1 == 1) {
		$results = mysqli_query($link, $query);
		if (!results) {
			echo mysqli_error($link) . "<br/>";
		} else {
			$queryh .= $modifiedFrom . "','" . $modifiedTo . "','ServerInfo')";
			//echo $queryh;
			$result = mysqli_query($link, $queryh);
			if (!$result) {
				mysqli_error($link);
			} else {
				if ($flag_logo != 1) {
					echo '<br/><span style="color:#17256B"><b>Mirror server information updated successfully</b></span><br/>';
				} else {
					echo '<br/><span style="color:#17256B"><b>Mirror server contact information updated successfully</b></span><br/>';
				}
			}
		}
	} elseif ($flag_logo != 1) {
		echo '<br/><span style="color:#17256B"><b>No information needs to be updated at this time</b></span><br/>';
	}
}

//select all the Mirroring sites currently in morphbank

$query = "Select * from ServerInfo where mirrorGroup=" . $groupId;
$result = mysqli_query($link, $query);
if (!result) {
	echo '<span style="color:red"><b>Error querying the data base</b></span>';
} else {

	// fetch the row information...
	$mirror = mysqli_fetch_array($result);
	$locality = $mirror['locality'];
	if (strpos($mirror['locality'], ",") > 0) {
		$mirror['country'] = substr($mirror['locality'], 0, strpos($mirror['locality'], ","));
		$mirror['locality'] = substr($mirror['locality'], strpos($mirror['locality'], ",") + 1, strlen($mirror['locality']));
		$mirror['state'] = substr($mirror['locality'], 0, strpos($mirror['locality'], ","));
		$mirror['city'] = substr($mirror['locality'], strpos($mirror['locality'], ",") + 1, strlen($mirror['locality']));
	}
}
if ($mirror['logo'] != null && $mirror['logo'] != "") {
	$imageloc = '/images/mirrorLogos/' . $mirror['logo'];
	$size = getimagesize($imageloc);
	$height = 50;
} else {
	$size = 0;
}

echo '<br/><table frame="border" width="600" ><tr><td>';
if ($size != 0) {
	echo '<img src="' . $imageloc . '" name="image" height="' . $height . '" width="' . $width . '" alt="IMAGE NOT AVAILABLE" /></a></td></tr>';
}
echo '<tr>
             <td><b>Server address: </b></td><td><input type="text" name="url" value="' . $mirror['url'] . '" size="45" maxlength="128" title="Mirroring server address" readonly="true"/>
                 <input type="hidden" name="url_old" value="' . $mirror['url'] . '" />
                 <input type="hidden" name="serverId" value="' . $mirror['serverId'] . '">
                 <span style="color:#17256B">*</span>
             </td>
          </tr>
          <tr>
             <td><b>Server connection port: </b></td>
             <td><input type="text" name="port" value="' . $mirror['port'] . '" size="5" maxlength="5" title="Mirroring server connection port" readonly="true"/>
                 <input type="hidden" name="port_old" value="' . $mirror['port'] . '" />
                 <span style="color:#17256B">*</span>
             </td>
          </tr>';
//printcountry($mirror['country']);
echo '<tr>
             <td><b>Country: </b></td>
             <td><input type="text" name="country" value="' . $mirror['country'] . '" size="26" maxlength="32" title="Country where the mirroring server is located" readonly="true"/>
                 <input type="hidden" name="country_old" value="' . $mirror['country'] . '">
                 <span style="color:#17256B">*</span>
             </td>
          </tr>
          <tr>
             <td><b>State/Province: </b></td>
             <td><input type="text" name="state" value="' . $mirror['state'] . '" size="26" maxlength="32" title="State/Province where the mirroring server is located" readonly="true"/>
                 <input type="hidden" name="state_old" value="' . $mirror['state'] . '" />
                 <span style="color:#17256B">*</span>
             </td>
          </tr>
          <tr>
             <td><b>City: </b></td>
             <td><input type="text" name="city" value="' . $mirror['city'] . '" size="26" maxlength="32" title="City where the mirroring server is located" readonly="true"/>
                 <input type="hidden" name="city_old" value="' . $mirror['city'] . '" />
                 <span style="color:#17256B">*</span>
             </td>
          </tr>';


MGroup($link, $mirror['mirrorGroup']);

Administrator($link, $mirror['admin']);

echo '<tr>
              <td><b>FTP login name: </b></td>
              <td><input type="text" name="login" size="26" value="' . $mirror['login'] . '" maxlength="41" readonly="true"/>
                  <input type="hidden" name="login_old" value="' . $mirror['login'] . '" />
                  <span style="color:#17256B">*</span>
              </td>
          </tr>
          <tr><td><b>Contact e-mail: <span style="color:red">*</span></b></td>
              <td><input type="text" name="contact" value="' . $mirror['contact'] . '" size="45" maxlength="128" title="Contact e-mail for the Mirroring site" />
                  <input type="hidden" name="contact_old" value="' . $mirror['contact'] . '">
              </td>
          </tr>
          <tr>
              <td><b>Directory path: </b></td>
              <td><input type="text" name="basePath" value="' . $mirror['basePath'] . '" size="45" maxlength="128" title="Directory path where the images reside on the mirroring server" readonly="true"/>
                  <input type="hidden" name="basePath_old" value="' . $mirror['basePath'] . '" />
                  <span style="color:#17256B">*</span>
              </td>
          </tr>
          <tr>
              <td><b>Images URL: </b></td>
              <td><input type="text" name="imageURL" value="' . $mirror['imageURL'] . '" size="45" maxlength="128" title="URL where the images reside on the mirroring server" readonly="true" />
                  <input type="hidden" name="imageURL_old" value="' . $mirror['imageURL'] . '" />
                  <span style="color:#17256B">*</span>
              </td>
          </tr>


          <tr>
              <td><input type="hidden" name="logo_old" value="' . $mirror['logo'] . '" /><b>Update logo image file: </b></td>
              <td><input type="file" name="new_image" size="45" maxlength="128" title="Click to upload new logo image file of type gif or png and Width<=2xHeight" />
                  <input type="hidden" name="returnUrl" value="' . $returnUrl . '"></td>
          </tr>
        </table>
        <span style="color:#17256B"><b>* - Can only be changed through email request to morphbank admin team.</b></span>';
	echo '<a href="javascript:pop(\'Mail\',\''.$config->domain.'Admin/mail.php\')"><img src="/style/webImages/envelope.gif" border="0" title="Click to send an e-mail to mbadmin" /></a>
<br />
<br />';
echo '<h1>Change FTP Password </h1>';
if ($flag == 1) {
	echo '<br/><b><span style="color:red">Wrong password , please try again.</span></b>';
}
if ($flag == 2) {
	echo '<br/><b><span style="color:red">Your re-entered password did not match. Please try again.</span></b>';
}
if ($flag == 3) {
	echo '<br/><b><span style="color:red">Please retype your old password again.</span></b>';
}
if ($flag == 4) {
	echo '<br/><b><span style="color:red">Blank passwords and passwords with less than 6 characters are not allowed. Please choose one with at least 6 alphanumeric characters</span></b>';
}
echo '<br/><table width="400px" frame="border">
        <tr>
        <td><b>Enter password</b></td>
        <td><input type="password" name="passwd"/></td>
        </tr>
        <tr>
        <td><b>Enter new password</b></td>
        <td><input type="password" name="new_passwd"/></td>
        </tr>
        <tr>
        <td><b>Re-enter new password</b></td>
        <td><input type="password" name="reentered_passwd"/>
        </td></tr></table>';

printButtons($id, $MODIFY_BUTTON, $currentgroupid, $returnUrl);

// cleanup
mysqli_free_result($result);
echo '</form>';
echo '</div>';

//function to create user list for selection
function Administrator($link, $admin) {
	$query = "SELECT name FROM User where id=" . $admin;
	//echo $query;
	$result = mysqli_query($link, $query);
	if ($result) {
		$row = mysqli_fetch_array($result);
		$name = $row['name'];
		echo '<tr><td><b>Administrator: </b></td>
          <td><input type="text" name="admin" value="' . $name . '" size="26" maxlength="128" readonly="true" />
              <span style="color:#17256B">*</span></td></tr>';
	}
}

//function to create group list for selection
function MGroup($link, $mgroup) {
	$query = "SELECT groupName FROM Groups where id=" . $mgroup;
	//echo $query;
	$result = mysqli_query($link, $query);
	if ($result) {
		$row = mysqli_fetch_array($result);
		echo '<tr><td><b>Mirroring group: </b></td>
          <td><input type="text" name="mgroup" value="' . $row['groupName'] . '" size="26" readonly="true">
              <span style="color:#17256B">*</span></td></tr>';
	}
}

function printButtons($id, $BUTTON, $groupID, $returnUrl) {
	

	echo '<table align="right">
        <tr>
          <td><a href="javascript: CheckValues();" class="button smallButton" title="Click to save the changes made to the mirror">
                <div>Update</div></a></td>
          <td><a href="' . $returnUrl . '" class="button smallButton" title="Click to return to the previous page"><div>Return</div></a>
          </td>
        </tr>
       </table>';
}

function echoJavaScript() {
	echo '<script language = "JavaScript" type ="text/javascript">

  function CheckValues(){
    var form = document.forms[0];
  
    if(notEmpty(form.url) &&
       notEmpty(form.port) &&
       notEmpty(form.login) &&
       notEmpty(form.city) &&
       notEmpty(form.contact)){

      document.forms[0].submit();
}
  }

 function notEmpty(elem){
   var str = elem.value;
   if(str.length == 0){
     alert("You must fill in all the required fields (*)!");
     return false;
   }
   else
     return true;
 }
</script>';
}
include_once('js/pop-update.js');

// Finish with end of HTML
finishHtml();
?>
