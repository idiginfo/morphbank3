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

include_once('pop.inc.php');


global $objInfo, $link;


checkIfLogged();
echoJavaScript();
if (isset($_GET['pop']))
$pop = $_GET['pop'];
if (isset($_POST['pop']))
$pop = $_POST['pop'];

if (!isset($_POST['returnUrl']))
$returnUrl = $_SERVER['HTTP_REFERER'];
else
$returnUrl = $_POST['returnUrl'];


$userId = $objInfo->getUserId();
$query = "SELECT contact FROM ServerInfo WHERE admin=" . $userId;
$result = mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
$from = $row['contact'];

// The beginning of HTML
$title = 'Send mail';
initHtml($title, null, null);

// Output the content of the main frame
echoHead(false, $title);

echo '<div class="popContainer" style="width:500px">';

//check for post action if yes create and send an e-mail
if (isset($_POST['letter'])) {
	$recipient = $config->email;
	$subject = $_POST['subject'];
	$message = $_POST['letter'];
	$headers = "From: " . $_POST['user'];
	mail($recipient, $subject, $message, $headers);

	echo '<span style="color:#17256B;"><br/>
            <b>Your email was just sent to the mbadmin team.</b></span><br/>
            <table align="right"><tr>
            <td><a href="javascript:window.close();" class="button smallButton" title="Click to close the window."><div>OK</div>
            </a></td></tr></table>';
} else {


	echo '<h3><b>Send mail to: </b></h3>mbadmin<br/>
        <h3><b>From: </b></h3>' . $from . '<br/>';
	?>
<form action="mail.php" method="post">

<table id="mytable" border="0" width="500px">
	<tr>
		<h3><b>Subject: </b><input type="text" name="subject" size="55"
			maxlength="64" /></h3>
	
	
	<tr>
		<td><textarea name="letter" cols="50" rows="7"></textarea></td>
		<input type="hidden" name="user" value="<?php
      echo $from;
?>" />
	</tr>
</table>
<table align="right">
	<tr>
		<td><a href="javascript: CheckValues();" class="button smallButton"
			title="Click to send e-mail to mbadmin">
		<div>Send</div>
		</a> <a href="javascript: window.close();" class="button smallButton"
			title="Click to cancel your action">
		<div>Cancel</div>
		</a></td>
	</tr>
</table>

<?php
}
echo '</div>';

finishHtml();

//javascript functions
function echoJavaScript()
{
	echo '<script type="text/javascript">

//function to check if the required fields
//has been provided by the user before submition

function CheckValues(){

              if(document.forms[0].letter.value.length==0){
                 alert("You can not send an empty letter!");
              }
              else
                 document.forms[0].submit();
}//end of function CheckValues

</script>';
}
?>
