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

include_once('head.inc.php');
include_once('Admin/admin.functions.php');
include_once('showFunctions.inc.php');

$includeJavaScript = array('jquery.1-4-2.min.js', 'jquery-ui-1.8.min.js', 'jquery.validate.min.js', 'formMethods.js');

// The beginnig of HTML
$title = 'Username/password';
initHtml($title, null, $includeJavaScript);

// Add the standard head section to all the HTML output.
echoHead(false, $title);
echo '<div class="mainGenericContainer" style="width:680px">';

$db = connect();

if ($_POST['email'] != null) {
	$email = trim($_POST['email']);
	$sql = "Select id, uin, name from User where email = ?";
	$row = $db->getRow($sql, null, array($email), null, MDB2_FETCHMODE_ASSOC);
	isMdb2Error($row, "Error selecting user information from database");
	if (empty($row)) {
		$message = '<div class="searchError">Unable to locate user information from provided email</div><br /><br />';
	} else {
		$new_password = trim(generateString(12));
		$id = $row['id'];
		$uin = $row['uin'];
		$name = $row['name'];
		$affectedRows = $db->query("update User set pin = password('$new_password') where id = $id");
		isMdb2Error($affectedRows, "Error updating user password");
		if (!$affectedRows) {
			$message = '<div class="searchError">Problems updating database. Please contact the administration.</div><br /><br />';
		} else {
			$text = "Below is your ".$config->appName." username and new password.\nYou may use the password by copying and pasting it into the password field on the login form.\n";
			$text .= "This new password was created for your first login. Please reset it to a more convenient one after logging in.\n\nusername: " . $uin . " \n\npassword: " . $new_password . " \n\n\n".$config->appName." Admin team";
			
			$html = "Below is your ".$config->appName." username and new password.<br />You may use the password by copying and pasting it into the password field on the login form.<br />";
			$html .= "This new password was created for your first login. Please reset it to a more convenient one after logging in.<br /><br />username: " . $uin . "<br /><br />password: " . $new_password . "<br /><br /><br />".$config->appName." Admin team";
			
			$subject  = 'Your ' . $config->appName . ' account information';
			try {
				$mail = new Zend_Mail();
				$mail->setBodyText($text);
				$mail->setBodyHtml($html);
				$mail->setFrom($config->email, $config->appName);
				$mail->addTo($email, $name);
				$mail->setSubject($subject);
				$mail->send();
			} catch (Zend_Exception $e) {
				errorLog("Error sending email for reset password.", $e->getMessage());
			}			
			$message = "<h3>You have successfully reset your password. Your account information has been mailed to $email</h3><br /><br />";
		}
	}
}

echo '<br /><br />' . $message;
echo '<form method="post" action="userpassword.php" id="frmResetPasword">';
echo '<p>Enter the e-mail address associated with your '.$config->appName.' account, 
		and we will e-mail you the information you need to access your account.</p>
      <p><span style="color:#FF0000">*</span> <b>Email: </b><input type="text" name="email" id="email" size="30" /></p>';
echo frmSubmitButton('Submit');
echo '</form>';

echo '</div>';
finishHtml();

/**
 * Generate random string
 * @param integer $length Length of generated string
 */
function generateString($length = 20) {
	$string="";
   $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
   srand((double)microtime()*1000000);
   for ($i=0; $i<$length; $i++) {
      $string .= substr ($chars, rand() % strlen($chars), 1);
   }
   return $string;
}
