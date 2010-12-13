<?php
/************************************************************************************************
 *  Author: David A. Gaitros                                                                     *
 *  Date:  9/16/2005                                                                             *
 *  Description:  PhP script to display the User add screen. The form will automatically call    *
 *                the adduserscript.php upon pressing of the submit button.                      *
 *                                                                                               *
 *  Modified: 1/24/2006 to include .php header files and static path names.                      *
 ************************************************************************************************/


include_once('head.inc.php');
include_once('imageFunctions.php');
include_once('Admin/admin.functions.php');

checkIfLogged();

$title = 'User';
initHtml($title, null, null);
echoHead(false, $title);

echo '<div class="mainGenericContainer" style="width:700px">';
echo '<h1>Add User</h1>';

echoJavaScript();

function echoJavaScript()
{
	echo '<script type="text/javascript">
 
  function openTSN(TSNtype){
    var location = "../TaxonSearch/index.php?TSNtype=1&pop=yes&parenttsn=0";
       if(TSNtype==2) 
      location = "../TaxonSearch/index.php?TSNtype=2&pop=yes&parenttsn=0";
       else if(TSNtype==3) 
      location = "../TaxonSearch/index.php?TSNtype=3&pop=yes&parenttsn=0";
         else if(TSNtype==4) 
      location = "../TaxonSearch/index.php?TSNtype=4&pop=yes&parenttsn=0";
       TSN = window.open(location,\'TSN\', \'location = 1, directories=0,dependent=1,menubar=0,top=20,left=20,width=800,height=800,scrollbars=1,resizable=1\');
           if (window.focus) 
            TSN.focus();
        }

  function updateprivilegeTSN(value,value2){
                 document.forms[0].privilegetsn.value=value;                
    document.forms[0].privilegeTSN.value=value2;
        }

  function updatealtprivTSN(value,value2){
                document.forms[0].altprivtsn.value=value;
    //document.forms[0].altprivtsn.value=value2;
        }

  function updateprimaryTSN(value,value2){
                document.forms[0].primarytsn.value=value;                
    document.forms[0].primaryTSN.value=value2;
        }

  function updatesecondaryTSN(value, value2){
                document.forms[0].secondarytsn.value=value;
    document.forms[0].secondaryTSN.value=value2;                
        }

  function CheckValues(){
                var form = document.forms[0];

    if(form.first_name.value.length==0){
      alert("You must fill in all required fields (*)!. Fill in first name");
    }
          else if(form.last_name.value.length==0){
      alert("You must fill in all required fields (*)!. Fill in last name");
    }
    else if(form.uin.value.length==0){
      alert("You must fill in all required fields (*)!. Fill in username");
    }
                else if(form.pin.value.length==0){
      alert("You must fill in all required fields (*)!. Fill in password");
    }
          else if(form.email.value.length==0){
      alert("You must fill in all required fields (*)!. Fill in e-mail");
    }
          else if(form.affiliation.value.length==0){
      alert("You must fill in all required fields (*)!. Fill in affiliation");
    }
          else if(form.privilegetsn.value.length==0){
      alert("You must fill in all requiredfields (*)!. Privelege TSN is required");
    }
          else if(form.primarytsn.value.length==0){
      alert("You must fill in all required fields (*)!. Primary TSN is required");
    }
                else if(form.email.value.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) == -1){
                        alert("The e-mail address is not in a right format");
                }
               else    
                  form.submit();  
  }

</script>';
}

if (isset($_POST['first_name'])) {
	if ($_PUT['returntouser'] != null)
	exit;


	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$middle_init = $_POST['middle_init'];
	$suffix = $_POST['suffix'];
	$uin = $_POST['uin'];
	$pin = $_POST['pin'];
	$email = $_POST['email'];
	$affiliation = $_POST['affiliation'];
	$privilegetsn = $_POST['privilegetsn'];
	$primarytsn = $_POST['primarytsn'];
	$secondarytsn = $_POST['secondarytsn'];
	$street1 = $_POST['street1'];
	$street2 = $_POST['street2'];
	$city = $_POST['city'];
	$country = $_POST['country'];
	$state = $_POST['state'];
	$zipcode = $_POST['zipcode'];
	$logoURL = $_POST['link'];
	$name = trim($first_name) . " " . trim($last_name);
	$address = trim($street1) . " " . trim($street2) . " " . trim($city) . " " . trim($state) . " " . trim($country) . " " . trim($zipcode);

	if (isset($_FILES['new_image']) && ($_FILES['new_image']['name'] > "")) {
		$new_image = $_FILES['new_image']['name'];

		$simple_name = substr($new_image, 0, strpos($new_image, "."));
		$simple_name .= $_POST['id'];
		$image_new = $simple_name . substr($new_image, strpos($new_image, "."), strlen($new_image) - 1);

		$logo = $image_new;
		$imagesize = $_FILES['new_image']['size'];
		$imagetype = $_FILES['new_image']['type'];
		$tmpFile = $_FILES['new_image']['tmp_name'];

		move_uploaded_file($tmpFile, $config->userLogoPath . $image_new);
		exec("chmod 755 " . $config->userLogoPath . $image_new);

		$userLogo = trim($image_new);
	}

	$UserId = $objInfo->getUserId();
	$link = Adminlogin();

	/***********************************************************************************************
	 *  1. Get the current id from the id table.                                               *
	 *  2. Add one to the id field.                                                                *
	 *  3. Update the id field in the id table.                                                  *
	 *  4. Insert the new user record using the new id .                                         *
	 *  5. Add a new record to the "baseobject" table referenceing the new user.                   *
	 **********************************************************************************************/
	while (true) {
		$query = "Select uin from User where uin ='" . $uin . "' and first_Name='" . $first_name . "' and last_Name='" . $last_name . "' and affiliation='" . $affiliation . "' and country='" . $country . "' and privilegeTSN='" . $privilegetsn . "';";
		$results = mysqli_query($link, $query);
		$num_results = mysqli_num_rows($results);
		if ($num_results != 0) {
			echo '<br/><br/>';
			echo '<span style="color:red"><b>User already exists in the Data Base</b></span>';
			break;
		}

		$query = "Select uin from User where uin ='" . $uin . "';";
		$results = mysqli_query($link, $query);
		$num_results = mysqli_num_rows($results);
		if ($num_results != 0) {
			echo '<br/><br/>';
			echo '<span style="color:red"><b>Username already exists. Choose another username</b></span>';
			break;
		}

		$query = "START TRANSACTION";
		$result = mysqli_query($link, $query);
		if (!$result) {
			mysqli_error($link);
			echo '<span style="color:red">Problems starting transaction</span>';
			break;
		} else {

			$curr_id = getNewId();
			$user_Id = $curr_id;
			$status = AddBaseObject($curr_id, $objInfo->getUserId(), $objInfo->getUserGroupId(), "User", date('Y m d'), "Added new\
 user to database", $link);
			if (!$status) {
				echo '<br/><br/>';
				echo '<span style="color:red"><b>Base Object for user not created</b></span>';
				$query = "RELEASE";
				$result = mysqli_query($link, $query);
				if (!$result) {
					mysqli_error($link);
					echo '<span style="color:red">Problems releasing transaction1</span>';
					break;
				} else
				break;
			} else {

				$query = "Insert into User (id, last_Name, first_Name, middle_init, suffix, uin,pin,email,";
				$query .= "status, affiliation,privilegeTSN,primaryTSN,secondaryTSN, street1, street2, city,country,";
				$query .= " state, zipcode,name,address,logoURL,userLogo) values(";
				$query .= $curr_id . ",'" . $last_name . "','" . $first_name . "','" . $middle_init . "','" . $suffix . "','" . $uin . "',";
				$query .= "password('$pin'),'" . $email . "',true,'" . $affiliation . "'," . $privilegetsn . "," . $primarytsn;
				if (isset($_POST['secondarytsn']) && trim($_POST['secondarytsn']) != "") {
					$query .= "," . $secondarytsn . ",";
				} else {

					$query .= ",null,";
				}
				$query .= "'" . $street1 . "','" . $street2 . "','" . $city . "','" . $country . "','" . $state . "','" . $zipcode;
				$query .= "','" . $name . "','" . $address . "','" . $logoURL . "','" . $userLogo . "')";
				//echo $query;
				$results = mysqli_query($link, $query);
				if (!$results) {
					mysqli_error($link);
					echo '<br/><br/>';
					echo '<span style="color:red"><b>User record for user not created</b></span>';
					$query = "RELEASE";
					$result = mysqli_query($link, $query);
					if (!$result) {
						echo '<span style="color:red">Problems releasing transaction2</span>';
						break;
					} else
					break;
				} else {

					//create a group with the same name as the username just entered
					$currid = getNewId();
					$status_g = AddBaseObject($currid, $objInfo->getUserid(), $objInfo->getUserGroupId(), "Groups", date('Y m d'), "Addednew group to database", $link);
					if (!$status_g) {
						mysqli_error($link);
						echo '<br/><br/><span style="color:red"><b>Base Object for group not created</b></span>';
						$query = "RELEASE";
						$result = mysqli_query($link, $query);
						if (!$result) {
							echo '<span style="color:red">Problems releasing the transaction3</span>';
							break;
						} else
						break;
					} else {

						$uig = "'s group";
						$uinn = $uin . $uig;
						$query_group = "INSERT INTO Groups (id,groupName,tsn,groupManagerId,status) VALUES (";
						$query_group .= "\"" . $currid . "\",\"" . $uinn . "\",\"" . $privilegetsn . "\",\"" . $user_Id . "\",1)";
						$results_group = mysqli_query($link, $query_group);
						if ($results_group) {
							$query = "insert into UserGroup (user,groups,userId,dateLastModified,dateCreated,dateToPublish,";
							$query .= "userGroupRole) values (";
							$query .= $user_Id . ",";
							$query .= $currid . ",";
							$query .= $UserId . ",";
							$query .= "NOW(), NOW(), NOW(),";
							$query .= "'coordinator')";
							//echo $query;
							$results = mysqli_query($link, $query);
							if (!$results) {
								mysqli_error($link);
								echo '<br/><br/><span style="color:red"><b>Record into UserGroup not added</b></span>';
								$query = "RELEASE";
								$result = mysqli_query($link, $query);
								if (!$result) {
									echo '<span style="color:red">Problems releasing the transaction4</span>';
									break;
								}
								break;
							} else {

								$query = "COMMIT";
								$result = mysqli_query($link, $query);
								if (!$result) {
									mysqli_error($link);
									echo '<span style="color:red">Problems commiting the transaction</span>';
									break;
								} else
								echo '<br/><br/><span style="color:navy"><b>Record added successfuly</b></span>';
							}
						} else {

							echo '<br/><br/><span style="color:red"><b>Record into Groups not added</b></span>';
							$query = "RELEASE";
							$result = mysqli_query($link, $query);
							if (!$result) {
								mysqli_error($link);
								echo '<span style="color:red">Problems releasing the transaction5</span>';
								break;
							} else
							break;
						}
					}
				}

				if (isset($_POST['email_option'])) {
					//echo "email will be sent";
					$recipient = $_POST['email'];
					$subject = "Your MorphBank account information";
					$message = $first_name . " " . $last_name . ", welcome to MorphBank,\n Please find bellow your MorphBank username and password.\n This password was created for your first login.\n You can reset it to a more convenient one after the login.\n\t username: " . $uin . " \n\t password: " . $pin . " \n\n\n MorhBank admin team";

					$headers = "From: mbadmin@scs.fsu.edu";
					mail($recipient, $subject, $message, $headers);
				}
				break;
			}
			//else2
		}
		//else1
	}
	//while
}
//post
?>

<form action="adduser.php" method="post" enctype="multipart/form-data">

<table border="0" width="100%">
	<tr>
		<td><b> First Name: <span style="color: red">*</span></b></td>
		<td align="left"><input type="text" name="first_name" size="35"
			maxlength="35" /></td>
	</tr>
	<tr>
		<td><b>Middle Initial: </b></td>
		<td align="left"><input type="text" name="middle_init" size="1"
			maxlength="1" /></td>

	</tr>
	<tr>
		<td><b>Last Name: <span style="color: red">*</span></b></td>
		<td align="left"><input type="text" name="last_name" size="35"
			maxlength="35" /></td>
	</tr>
	<tr>
		<td><b>Suffix: </b></td>
		<td align="left"><input type="text" name="suffix" size="10"
			maxlength="10" /></td>
	</tr>
	<tr>
		<td><b>User Name: <span style="color: red">*</span></b></td>
		<td align="left"><input type="text" name="uin" size="41"
			maxlength="41" /></td>
	</tr>
	<tr>
		<td><b>Password: <span style="color: red">*</span></b></td>
		<td align="left"><input type="password" name="pin" size="15" /></td>
	</tr>
	<tr>
		<td><b>E-Mail: <span style="color: red">*</span></b></td>
		<td align="left"><input type="text" name="email" size="41"
			maxlength="128" /></td>
	</tr>
	<tr>
		<td><b>Affiliation: <span style="color: red">*</span></b></td>
		<td align="left"><input type="text" name="affiliation" size="41"
			maxlength="128" /></td>
	</tr>
	<tr>
		<td><b>Street1: </b></td>
		<td align="left"><input type="text" name="street1" size="35"
			maxlength="35" /></td>
	</tr>
	<tr>
		<td><b>Street2: </b></td>
		<td align="left"><input type="text" name="street2" size="35"
			maxlength="35" /></td>
	</tr>
	<tr>
		<td><b>City: </b></td>
		<td align="left"><input type="text" name="city" size="25"
			maxlength="25" /></td>
	</tr>
	<?php
	printstate(" ")
	?>
	<?php
	printcountry(" ")
	?>
	<tr>
		<td><b>Zip Code: </b></td>
		<td align="left"><input type="text" name="zipcode" size="10"
			maxlength="10" /></td>
	</tr>
	<tr>
		<td><b>Privilege Taxon: <span style="color: red">*</span></b></td>
		<td><input type="hidden" name="privilegetsn" size="20" /> <input
			type="text" name="privilegeTSN" value="" size="42" /> <a
			href="javascript:formname='user';openTSN(1);"> <img
			src="/style/webImages/selectIcon.png" border="0"
			title="Select Privilege TSN" alt="Select Privilege TSN" /></a></td>
	</tr>

	<tr>
		<td><b>Primary Taxon: <span style="color: red">*</span></b></td>
		<td><input type="hidden" name="primarytsn" size="20" /> <input
			type="text" name="primaryTSN" size="42" /> <a
			href="javascript:formname='user';openTSN(2);"> <img
			src="/style/webImages/selectIcon.png"
			title="Select Primary TSN" alt="Primary TSN" /></a></td>
	</tr>
	<tr>
		<td><b>Secondary Taxon: </b></td>
		<td><input type="hidden" name="secondarytsn" value=" " size="20" /> <input
			type="text" name="secondaryTSN" size="42" /> <a
			href="javascript:formname='user';openTSN(3);"> <img
			src="/style/webImages/selectIcon.png"
			title="Select Secondary TSN" alt="Secondary TSN" /></a></td>
	</tr>
	<tr>
		<td><b>User logo: </b></td>
		<td><input type="file" name="new_image" size="42"
			title="Add  personal/organizational logo" /></td>
	</tr>
	<tr>
		<td><b>User link: </b></td>
		<td><input type="text" name="link"
			value="<?php
  echo $row['logoURL'];
?>" size="42" maxlength="128"
			title="Enter url of the personal/organizational page to be displayed when the logo is clicked."></td>
	</tr>
</table>
<table>
	<tr>
		<td><b>Send automatic e-mail to the user <input type="checkbox"
			name="email_option"
			title="If checked an automatic email is sent to the new user with his user name and password"
			checked="checked"></b></td>
	</tr>
</table>
<br></br>
<span style='font-size: 12; color: red'><b>* - Required</b></span>
<table align="right">
	<tr>
		<td><a href="javascript:CheckValues();" class="button smallButton "
			title="Click to add the user to the database">
		<div>Add</div>
		</a> <a href="<?php  echo $config->domain; ?>Admin/User/"
			class="button smallButton" title="Click to return to User">
		<div>Return</div>
		</a></td>
	</tr>
</table>
</form>


<?php
echo '</div>';
finishHtml();
?>
