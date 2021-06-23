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

function mainFeedback()
{

    global $link, $objInfo, $codeArray, $config;

    $email = '';

    if ($objInfo->getUserId() != null) {
        //echo 'test';
        $sql = 'SELECT email FROM User WHERE id = '.$objInfo->getUserId().' ';
        $result = mysqli_query($link, $sql) or die(mysqli_error($link));

        if ($result) {
            $row = mysqli_fetch_array($result);
            $email = $row['email'];
        }
    }

    echo '
		<div class="mainGenericContainer" style="width:650px;">
			<h1>Feedback Form</h1><br />
			
			<form name="emailForm" action="feedbackAction.php" method="post">
				<table class="blueBorder" width="100%">';
    if ($_GET['id'] == 1) {
        echo '	
					<tr>
						<td>&nbsp;</td>
						<td align="left"><div class="searchError">Message sent successfully</div></br /></td>
					</tr>';
    } elseif ($_GET['id'] == 2) {
        echo '	
					<tr>
						<td>&nbsp;</td>
						<td align="left"><div class="searchError">Message not sent.  Contact MorphBank Administrators.</div></br /></td>
					</tr>';
    } elseif ($_GET['id'] == 3) {
        echo '	
					<tr>
						<td>&nbsp;</td>
						<td align="left"><div class="searchError">Message not sent.  Captcha validation failed.</div></br /></td>
					</tr>';
        } elseif($_GET['id'] == 4) {
		echo '	
					<tr>
						<td>&nbsp;</td>
						<td align="left"><div class="searchError">Message not sent. All fields required.</div></br /></td>
					</tr>';
    } elseif($_GET['id'] == 5) {
		echo '	
					<tr>
						<td>&nbsp;</td>
						<td align="left"><div class="searchError">Message not sent. Email is invalid.</div></br /></td>
					</tr>';
	} elseif ($_GET['id'] >= 6) {
        echo '	
					<tr>
						<td>&nbsp;</td>
						<td align="left"><div class="searchError">Message not sent.  Contact MorphBank Administrators.</div></br /></td>
					</tr>';
    }
    echo '	
					<tr>
						<th align="right">To: </th>
						<td>Morphbank Administrators</td>
					</tr>
					
					<tr>
						<th align="right">From: </th>
						<td><input type="text" name="from" size="30" value="'.$email.'" /> <em>(valid email address)</em></td>
					</tr>
					
					<tr>
						<th align="right">Subject: </th>
						<td><input type="text" name="subject" size="30" /></td>
					</tr>
					
					<tr>
						<th valign="top">Message:</th>
						<td><textarea cols="60" rows="15" name="message"></textarea></td>
					</tr>
				</table>
				<table border="0" width="100%">	
					<tr>
                        <td>
                            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                            <div class="g-recaptcha" data-sitekey="'.$config->siteKey.'"></div>
                        </td>
                    </tr>
					<tr>
						<td align="right"><input type="submit" value="Submit" class="button smallButton"></td>
					</tr>
				</table>						
			</form>		
		
		
		</div>  ';
    if ($objInfo->getUserId() != null) {
        echoFocus("subject");
    } else {
        echoFocus("from");
    }
}

function echoFocus($field)
{
    echo '
	<script type="text/javascript" language = "Javascript">
		document.emailForm.'.$field.'.focus();
	</script>';
}

?>

