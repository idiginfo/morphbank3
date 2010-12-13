<?php
//This file creates a mirroring site information record in the database
// Created by: Karolina Maneva-Jakimoska
// Date created: Jan 19 2007

include_once('head.inc.php');
include_once('Admin/admin.functions.php');

checkIfLogged();
$userid = $objInfo->getUserId();
$SelectTSN = "/style/webImages/selectIcon.png";

$title = 'Add Mirror Server Information';
initHtml($title, null, null);
echoHead(false, $title);

echo '<div class="mainGenericContainer" style="width:680px">';
echo '<h1>Add Mirror Server Information</h1>';

include_once('js/pop-update.js');

$link = Adminlogin();

echoJavaScript();


//if there was submit action, create record
if (isset($_POST['url'])) {
	//echo "submit action";
	//extract the post variables
	$url = $_POST['url'];
	$contact = $_POST['contact'];
	$mgroup_id = $_POST['mgroup_id'];
	$mgroup = $_POST['mgroup'];
	$basePath = $_POST['basePath'];
	$login = $_POST['login'];
	$password = $_POST['password'];
	$city = $_POST['city'];
	$country = $_POST['country'];
	$state = $_POST['state'];
	$imageURL = $_POST['imageURL'];
	$port = $_POST['port'];
	$locality = $country . "," . $state . "," . $city;
	$flag_logo = 0;

	//selecting the mirroring group coordinator to be the admin of the group
	$query = "SELECT User.id FROM User,UserGroup WHERE User.id=UserGroup.user AND UserGroup.groups=" . $mgroup;
	$query .= " AND UserGroup.userGroupRole='coordinator'";
	//echo $query;
	$result = mysqli_query($link, $query);
	if ($result) {
		$row = mysqli_fetch_array($result);
		$admin = $row['id'];
	}
	//geting the next available serverId
	$query = "SELECT MAX(serverId) AS id FROM ServerInfo";
	$result = mysqli_query($link, $query);
	if (!$result)
	echo '<span style="color:red">Problems querying the database</span>';
	else {

		$row = mysqli_fetch_array($result);
		$next_id = $row['id'];
	}
	if (isset($_FILES['image']) && ($_FILES['image']['name']) > "") {
		$imagefile = $_FILES['image']['name'];
		$simple_name = substr($imagefile, 0, strpos($imagefile, "."));
		$simple_name .= $next_id;
		$imagefile = $simple_name . substr($imagefile, strpos($imagefile, "."), strlen($imagefile) - 1);
		//  if($image=="")
		$logo = '/images/mirrorLogos/' . $imagefile;
		$imagetype = $_FILES['image']['type'];
		$imagetype = substr($imagetype, strpos($imagetype, "/") + 1, strlen($imagetype));
		//echo "image type is ".$imagetype;
		if ($imagetype != "gif" && $imagetype != "png") {
			echo '<br/><br/><span style="color:red"><b>The logo image is not of required type</b></span><br/>';
			$imagefile = null;
			$flag_logo = 1;
		} else {
			//TODO revise to eliminate directory name, use server parameter
			move_uploaded_file($_FILES['image']['tmp_name'], $config->mirrorLogos . $imagefile);
			exec("chmod 777 " . $config->mirrorLogos . $imagefile);
			$imagesize = getSafeImageSize($config->mirrorLogos . $imagefile);
			$image_height = $imagesize[1];
			$image_width = $imagesize[0];
			//echo "image width is ".$image_width;
			//echo "image hight is ".$image_hight;
			if ($image_height < ($image_width / 2)) {
				echo '<br/><br/><span style="color:red"><b>The logo image is not of required size</b></span><br/>';
				$imagefile = null;
				$flag_logo = 1;
			}
		}
	}

	//check if the record already exists
	$query = "SELECT serverId FROM ServerInfo WHERE url='" . $url . "' AND login='" . $login . "' AND basePath='" . $basePath . "'";
	//echo $query;
	$result = mysqli_query($link, $query);
	if ($result) {
		$numrows = mysqli_num_rows($result);
		if ($numrows > 0)
		echo '<br/><br/><span style="color:red"><b>Record already exists in the database</b></span><br/>';
		else {

			//insert new record
			$query = "INSERT INTO ServerInfo(url,logo,contact,admin,mirrorGroup,dateCreated,updatedDate,basePath,login,";
			$query .= " passwd,locality,imageURL,port) ";
			$query .= "VALUES ('" . $url . "','" . $imagefile . "','" . $contact . "'," . $admin . "," . $mgroup . ",NOW(),NOW(),'" . $basePath;
			$query .= "','" . $login . "',password('" . $password . "'),'" . $locality . "','" . $imageURL . "'," . $port . ")";
			//echo $query;
			$result = mysqli_query($link, $query);
			if (!$result)
			echo '<br/><br/><span style="color:red"><b>Problems inserting new record in the database.</b></span><br/>';
			else {

				if ($flag_logo == 0)
				echo '<br/><br/><span style="color:#17256B"><b>New mirroring server info record was successfully added to the database</b></span><br/>';
				if ($flag_logo == 1)
				echo '<br/><br/><span style="color:#17256B"><b>New mirroring server info record was added to the database without logo image</b></span><br/>';
			}
		}
	} else {
		echo '<span style="color:red"><b>Problems querying the database</b></span>';
	}
}
echo '<br/><br/>

    <form action="addMirror.php" method="post" enctype="multipart/form-data">
  
        <table border="0">
     <tr>
              <td><b>Server address: <span style="color:red">*</span></b></td>
              <td><input type="text" name="url" size="45" maxlength="128" title="Enter mirroring site URL"/></td>
           </tr>
           <tr>
              <td><b>Server connection port: <span style="color:red">*</span></b></td>
              <td><input type="text" name="port" value="21" size="5" maxlength="5" title="Enter the mirroring server connection port" /></td>
           </tr>';

printcountry($mirror['country'][$rowNum]);

echo '<tr>
         <td><b>State/Province: </b></td>
               <td><input type="text" name="state" title="Enter the State/Province where the mirroring site is located"/>
         </tr>
         <tr>
         <td><b>City: <span style="color:red">*</span></b></td>
               <td><b><input type="text" name="city" title="Enter the city where the mirroring site is located" /></td>
         </tr>';

MirrorGroup($link);

echo '<tr>
            <td><b>FTP login name: <span style="color:red">*</span></b></td>
            <td><input type="text" name="login" size="26" maxlength="41" title="Enter the login name for the mirroring server"/></td>
        </tr>
        <tr>
            <td><b>FTP login password: <span style="color:red">*</span></b></td>
            <td><input type="password" name="password" size="26" maxlength="41" title="Enter the password for the mirroring server" />
            </td>
        </tr>
  <tr>
            <td><b>Contact e-mail: <span style="color:red">*</span></b></td>
            <td><input type="text" name="contact" size="45" maxlength="128" title="Enter contact e-mail for the mirroring site" /></td>
        </tr>
  <tr>
            <td><b>Directory path: <span style="color:red">*</span></b></td>
            <td><input type="text" name="basePath" size="45" maxlength="128" title="Enter the directory path where the images will reside on the mirroring site" /></td>
        </tr>
   <tr>
            <td><b>Images URL: </span></b></td>
            <td><input type="text" name="imageURL" size="45" maxlength="128" title="Enter the url (if applicable) where the images will reside onthe mirroring site" /></td>
        </tr>
        <tr>
            <td><b>Logo image file: </b></td>
            <td><input type="file" name="image" size="45"  title="Upload mirroring site logo image file" /></td>
        </tr>
      </table>
    <br/>

  <span style="font-size:12;color:red"><b>* - Required</b></span><br/><br/>
        <span style="color:#17256B"><b>Note: required type for the image logo is gif or png, and the width should not exceed twice the height.</b></span><br/>
  <table align="right">
     <tr>
         <td><a href="javascript: CheckValues();" class="button smallButton" title="Click to submit mirror server information">
                  <div>Submit</div></a>
              </td>
        <td><a href="' . $config->domain . 'Admin/MirrorServer/" class="button smallButton" title="Click to return to main Mirror Server page">
                  <div>Return</div></a>
              </td>
    </tr>
        </table>
  </form>
</div>';

function AdminPerson($link) {
	$query = "SELECT id, name FROM User WHERE id IN (SELECT distinct(User.id) FROM User, ";
	$query .= "UserGroup WHERE User.id=user and UserGroupRole!='reviewer') ORDER BY name";
	$result = mysqli_query($link, $query);
	if ($result) {
		echo '<tr><td><b>Administrator: <span style="color:red">*</span></b></td>
                <td><select name="admin" title = "Select mirroring site administrator ">
                <option value =\'0\'>--- Select administrator from the list ---</option>';
		while ($name = mysqli_fetch_array($result)) {
			echo '<option value="' . $name['id'] . '">' . $name['name'] . ' </option>';
		}
		echo '</select></td></tr>';
	}
}

function MirrorGroup($link) {
	$query = "SELECT id, groupName FROM Groups ORDER BY groupName";
	$result = mysqli_query($link, $query);
	if ($result) {
		echo '<tr><td><b>Mirroring group: <span style="color:red">*</span></b></td>
                <td><select name="mgroup" title="Select group responsable for the mirroring activities"/>
                <option value =\'0\'>--- Select mirroring group from the list ---</option>';
		while ($name = mysqli_fetch_array($result)) {
			echo '<option value="' . $name['id'] . '">' . $name['groupName'] . ' </option>';
		}
		echo '</select></td></tr>';
	}
}

function echoJavaScript() {
	echo '<script language = "JavaScript" type ="text/javascript">

function UpdateMirrorGroup(id,name){

      document.forms[0].mgroup.value = name;
      document.forms[0].mgroup_id.value = id;

}//end of function UpdateMirrorGroup

  function CheckValues(){

    var form = document.forms[0];

       if(notEmpty(form.url) && 
           notEmpty(form.port)&&
           notEmpty(form.login) &&
           notEmpty(form.password) &&
           notEmpty(form.city) &&
           notEmpty(form.basePath) &&
           notEmpty(form.port) &&
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
finishHtml();
?>


