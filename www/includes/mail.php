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

$link = adminLogin();

$peopleTable = 'User';

if (isset($_GET['team'])) {
	$peopleTable = 'MBTeam';
}

if(!isset($_GET['id'])) {
	$name='Who?';
} else {

	$sql = 'SELECT email FROM '.$peopleTable.' WHERE id='.$_GET['id'];
	//$sql = 'SELECT email FROM User WHERE convert(id,unsigned)='.$_GET['id'];
	$result = mysqli_query( $link, $sql) or die(mysqli_error($link));
	if (!$result) {
    		$name='Who?';
	}
    else {	
		$row = mysqli_fetch_array($result);
		if(!isset($row['email']) || ($row['email'] == ""))
			$name='---';
      	else
			$name=$row['email'];
	}	
}
//echo $name;
//$name ="ronquist@csit.fsu.edu";

header("Content-Type: image/png");
/* image height: text height is 16px, 2 is a trick for better centering with 
   the html text*/
$img = @imagecreate(strlen($name)*9,16+2) or die('messed up'); 

$background_color = imagecolorallocate($img, 255, 255,0);
imagecolortransparent($img, $background_color);
$textcolor = imagecolorallocate($img, 30,30,30);
imagestring($img, 5, 0, 0, $name, $textcolor);
imagepng($img);
imagedestroy($img);
/**/
?>
