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

include_once ($config->webPath."/bischen/tileserver/TilepicParser.inc");
include_once ('imagepath.inc.php');

$tilepicParser = new TilepicParser();

function makeTilePic($id, $imgSrc = null){
	global $tilepicParser, $message;
	if (empty($imgSrc)) {
		$imgSrc = getImageFilePath($id, "jpeg");
	}
	$message .= "makeTilePic for $id path $imgSrc\n";
	if (! file_exists($imgSrc)) return false;

	$tpcPath = getImageFilePath($id, 'tpc');
	//$message .=  "calling encode for id $id and file $imgSrc putting result in  $tpcPath\n";
	$success = $tilepicParser -> encode($imgSrc, $tpcPath, $options);
	if (!$success){
		$message .= "parser error ".$tilepicParser->error."\n";
	}
	return $success;
}

?>
