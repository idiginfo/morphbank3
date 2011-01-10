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
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

if (isset($_POST['spamId'])) {
	$sql = 'SELECT * FROM Spam WHERE id = '.$_POST['spamId'];
	
	$result = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if ($result) {
		$spamArray = mysqli_fetch_array($result);	
		
		if (strtolower($spamArray['code']) == strtolower($_POST['spamCode'])) {
			if (mail('mbadmin@scs.fsu.edu', 'AUTOMATED MORPHBANK FEEDBACK EMAIL::  '.$_POST['subject'] , $_POST['message'], 'From: '.$_POST['from'].''))
				header('Location: index.php?id=1');
				
			else
				header('Location: index.php?id=2'); //mail send error
		} 
		else
			header('Location: index.php?id=3');	// wrong spam code
	}
	else 
		header('Location: index.php?id=4');	// Query messed up
}
else 
	header('Location: index.php?id=5');	// No $_POST['spamId'] set


?>
