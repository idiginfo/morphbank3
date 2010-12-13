<?php
include_once('head.inc.php');;
include_once('spam.php');
include_once('admin.functions.php');

$link = Adminlogin();

$codeArray = getSpamCode();

$js = '<script language="javascript" type="text/javascript" src="' . $config->domain . 'js/checkSpam.js"></script>';

$title = 'Morphbank application';
initHtml($title, $js);
echoHead(false, $title);

echo '<div class="mainGenericContainer" style="width:700px">';
if ($_POST['first_name'] != null)
echo '<h1>New Morphbank application</h1>';
else
echo '<h1>New User information</h1>';

echoJavaScript();

function echoJavaScript()
{
	echo '<script type="text/javascript">
 
  function openTSN(TSNtype,location){
       TSN = window.open(location,\'TSN\', \'directories=0,dependent=1,menubar=0,top=20,left=20,width=800,height=800,scrollbars=1,resizable=1\');
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
    
    if(document.forms[0].first_name.value.length==0){
      alert("You must fill in all the required fields (*)!. Fill in first name");
    }
          else if(document.forms[0].last_name.value.length==0){
      alert("You must fill in all the required fields (*)!. Fill in last name");
    }
    else if(document.forms[0].uin.value.length==0){
      alert("You must fill in all the required fields (*)!. Fill in username");
    }
          else if(document.forms[0].email.value.length==0){
      alert("You must fill in all the required fields (*)!. Fill in e-mail");

    }
    else if(document.forms[0].phone.value.length==0){
      alert("You must fill in all the required fields (*)!. Fill in phone number");
    }
          else if(document.forms[0].affiliation.value.length==0){
      alert("You must fill in all the required fields (*)!. Fill in affiliation");
    }
          else if(document.forms[0].privilegetsn.value.length==0){
      alert("You must fill in all the requiredfields (*)!. Privelege TSN is required");
    }
          else if(document.forms[0].primarytsn.value.length==0){
      alert("You must fill in all the required fields (*)!. Primary TSN is required");
    }

    else if(document.forms[0].userResume.value.length==0){
      alert("Your resume/CV is required for verification of your expertise. Please upload your resume");
    }
               
                //this part needs to be updated with better function to check the right format of e-mail
    //if((form.email.value.indexOf(".")<1)||(form.email.value.indexOf("@"))<0){
      //alert("Your e-mail address is not in a right format");
      //return false;
    //}

                else if(document.forms[0].email.value.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) == -1){
                        alert("Your e-mail address is not in a right format");

                }
               else  
                  document.forms[0].submit();
              }
            

</script>';
}
?>

<form name="newUserForm" action="userapplication.php" method="post"
	enctype="multipart/form-data"><?php
	if ($_POST['first_name'] != null) {
		if ($_POST['subscription'] == 'on')
		$subscribe = "yes";
		else
		$subscribe = "no";
		$message = " A REQUEST FOR NEW USER ACCOUNT ON MORPHBANK FROM:
  First Name: " . $_POST['first_name'] . "
  Middle Initial: " . $_POST['middle_init'] . "
  Last  Name: " . $_POST['last_name'] . "
  Suffix: " . $_POST['suffix'] . "
  User Name: " . $_POST['uin'] . "
  E-mail: " . $_POST['email'] . "
  Phone: " . $_POST['phone'] . "
  Affiliation: " . $_POST['affiliation'] . "
  Street1: " . $_POST['street1'] . "
  Street2: " . $_POST['street2'] . "
  City: " . $_POST['city'] . "
  State: " . $_POST['state'] . "
  Country: " . $_POST['country'] . "  
  ZipCode: " . $_POST['zipcode'] . "
  PrivilegeTaxon: " . $_POST['privilegetsn'] . "-" . $_POST['privilegeTSN'] . "
  PrimaryTaxon: " . $_POST['primarytsn'] . "-" . $_POST['primaryTSN'] . "
  SecondaryTaxon: " . $_POST['secondarytsn'] . "-" . $_POST['secondaryTSN'] . "
         Subscription to the mail list: " . $subscribe;
		$headers = "From: " . $_POST['email'];
		$recipient = "mbadmin@scs.fsu.edu";
		$subject = "Request for New User Account";
		$fileatt = $_POST['fileatt'];
		//replace \n with <br>
		$message = str_replace("\n", "<br>", $message);

		// Obtain file upload variables
		$fileatt = $_FILES['userResume']['tmp_name'];
		$fileatt_type = $_FILES['userResume']['type'];
		$fileatt_name = $_FILES['userResume']['name'];

		if ($_FILES['userResume']['size'] > 0) {
			if (is_uploaded_file($fileatt)) {
				// Read the file to be attached ('rb' = read binary)
				// echo "file uploaded: ".$fileatt_name;
				$file = fopen($fileatt, 'rb');
				$data = fread($file, filesize($fileatt));
				fclose($file);
				// Generate a boundary string
				$semi_rand = md5(time());
				$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
				// Add the headers for a file attachment
				$headers .= "  MIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";
				// Add a multipart boundary above the  message
				$message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";

				// Base64 encode the file data
				$data = chunk_split(base64_encode($data));
				// Add file attachment to the message
				$message .= "--{$mime_boundary}\n" . "Content-Type: {$fileatt_type};\n" . " name=\"{$fileatt_name}\"\n" .
				//"Content-Disposition: attachment;\n" .
				//" filename=\"{$fileatt_name}\"\n" .
              "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n" . "--{$mime_boundary}--\n";
			} else
			echo "File error!  ";
		}

		//send the mail
		if (mail($recipient, $subject, $message, $headers)) {
			echo "<br/><b><font color=#17256B>Your request for a new user account was just sent to the Morphbank admin team.<br>
                  You will receive an email after your account is created.</b></font>";
			echo '<table align="right"><tr>
        <td><a href="../Submit" class="button smallButton" title="Click to return on the login page" ><div>OK</div>';
			echo '</a></td></tr></table>';
		} else
		echo "<b><font color=#FF0000>Error while processing the account request. Please fill in a new application!<b></font>";
	} else {

		?>
<table border="0" width="100%">
	<tr>
		<td><b> First Name<span style="color: red">*</span></b></td>
		<td align="left"><input type="text" name="first_name" size="35"
			maxlength="35" /></td>
	</tr>
	<tr>
		<td><b>Middle Initial</b></td>
		<td align="left"><input type="text" name="middle_init" size="1"
			maxlength="1" /></td>
	</tr>
	<tr>
		<td><b>Last Name<span style="color: red">*</span></b></td>
		<td align="left"><input type="text" name="last_name" size="35"
			maxlength="35" /></td>
	</tr>
	<tr>
		<td><b>Suffix</b></td>
		<td align="left"><input type="text" name="suffix" size="10"
			maxlength="10" /></td>
	</tr>
	<tr>
		<td><b>Requested User Name<span style="color: red">*</span></b></td>
		<td align="left"><input type="text" name="uin" size="41"
			maxlength="41" /></td>
	</tr>
	<!--<tr>
<td><b>Requested Password<span style="color:red">*</span></b></td>
<td align="left"><input type="password" name="pin" size="15" /></td>
</tr>-->
	<tr>
		<td><b>E-Mail<span style="color: red">*</span></b></td>
		<td align="left"><input type="text" name="email" size="41"
			maxlength="128" /></td>
	</tr>
	<tr>
		<td><b>Phone number<span style="color: red">*</span></b></td>
		<td align="left"><input type="text" name="phone" size="41"
			maxlength="20" /></td>
	</tr>
	<tr>
		<td><b>Institute of affiliation<span style="color: red">*</span></b></td>
		<td align="left"><input type="text" name="affiliation" size="41"
			maxlength="128" /></td>
	</tr>
	<tr>
		<td><b>Street1</b></td>
		<td align="left"><input type="text" name="street1" size="35"
			maxlength="35" /></td>
	</tr>
	<tr>
		<td><b>Street2</b></td>
		<td align="left"><input type="text" name="street2" size="35"
			maxlength="35" /></td>
	</tr>
	<tr>
		<td><b>City</b></td>
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
		<td><b>Zip Code</b></td>
		<td align="left"><input type="text" name="zipcode" size="10"
			maxlength="10" /></td>
	</tr>
	<tr>
		<td><b>Privilege Taxon<span style="color: red">*</span></b></td>
		<td><input type="hidden" name="privilegetsn" size="20" /> <input
			type="text" name="privilegeTSN" value="" size="42" /> <a
			href="javascript:formname='group';openTSN(1,'<?php
      echo "TaxonSearch/index.php?TSNtype=1&amp;pop=yes&amp;newuser=true"
?>')"> <img src="/style/webImages/selectIcon.png"
			border="0" title="Select Privilege TSN" alt="Select Privilege TSN" /></a></td>
	</tr>
	<tr>
		<td><b>Primary Taxon<span style="color: red">*</span></b></td>
		<td><input type="hidden" name="primarytsn" size="20" /> <input
			type="text" name="primaryTSN" size="42" /> <a
			href="javascript:formname='group';openTSN(2,'<?php
      echo "TaxonSearch/index.php?TSNtype=2&amp;pop=yes&amp;newuser=true"
?>');"> <img src="/style/webImages/selectIcon.png"
			title="Select Primary TSN" alt="Primary TSN" /></a></td>
	</tr>
	<tr>
		<td><b>Secondary Taxon</b></td>
		<td><input type="hidden" name="secondarytsn" size="20" /> <input
			type="text" name="secondaryTSN" size="42" /> <a
			href="javascript:formname='group';openTSN(3, '<?php
      echo "TaxonSearch/index.php?TSNtype=3&amp;pop=yes&amp;newuser=true"
?>');"> <img src="/style/webImages/selectIcon.png"
			title="Select Secondary TSN" alt="Secondary TSN" /></a></td>
	</tr>
	<tr>
		<td><b>Your Resume/CV<span style="color: red">*</span></b></td>
		<td><input type="file" name="userResume" size="42" /></td>
	
	
	<tr />

</table>
<table>
	<tr>
		<td><b>Subscribe to the Morphbank mailing list</b><input
			type="checkbox" name="subscription" checked="checked"></td>
	</tr>
</table>
<br />
<span style='color: #17256B'><b>Note: Your resume/CV will be used for
verification of your expertise</b></span> <br />
<br />
<span style='font-size: 12; color: red'><b>* - Required</b></span> <?php
echo '
<table border="0" width="100%">  
    <tr>
        <td>
            <br /><h3>Please type the letters in this picture into the box below. </h3><br /><em>(To prevent spam. Not case sensitive.)</em><br /><br />
        </td>
    </tr>
    <tr>
        <td>
            <img src="/style/webImages/codes/' . $codeArray['graphic'] . '" alt="graphic" />
        </td>
    </tr>
    <tr>
        <td>
            <input type="text" name="spamCode" value="" />
        </td>
    </tr>
</table>

<input type="hidden" name="spamId" value="' . $codeArray['id'] . '" />

<table align="right">
<tr>
<td><a href="javascript:checkSpam();" class="button smallButton" title="Click to submit the form to mbadmin team"><div>Submit</div></a>
<a href="' . $config->domain . 'Submit/" class="button smallButton" title="Click to cancel your application and return to the main page"><div>Cancel</div></a></td>
</tr>
</table>';
?></form>


<?php
	}
	echo '</div>';
	finishHtml();
	?>
