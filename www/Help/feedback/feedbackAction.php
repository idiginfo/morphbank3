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

// check post values
if (empty($_POST['from']) ||
	empty($_POST['subject']) ||
	empty($_POST['message']) ||
	empty($_POST["g-recaptcha-response"])) {
	header('Location: index.php?id=4');
} elseif ( ! filter_var($_POST['from'], FILTER_VALIDATE_EMAIL)) {
	header('Location: index.php?id=5');
}elseif ($_POST["g-recaptcha-response"]) {
	require_once 'Classes/ReCaptchaResponse.php';

	$reCaptcha = new ReCaptcha($config->secretKey);
	$response = $reCaptcha->verifyResponse($_SERVER["REMOTE_ADDR"],	$_POST["g-recaptcha-response"]);

    if ($response != null && $response->success) {
		$mailed = mail($config->email, 'AUTOMATED MORPHBANK FEEDBACK EMAIL::  '.$_POST['subject'] , $_POST['message'], 'From: '.$_POST['from'].'');
		header('Location: index.php?id=1');
    } else {
		header('Location: index.php?id=3');	// wrong spam code
	}
}else {
	header('Location: index.php?id=6');	// No $_POST['spamId'] set
}
