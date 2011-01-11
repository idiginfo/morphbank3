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

// Check if get variable set
if (!isset($_GET['cv']) || empty($_GET['cv'])) exit;

// Validate admin user and logged in
include_once 'validateUser.inc.php';
$groupId = $objInfo->getUserGroupId();
if ($groupId !== $config->adminGroup) {
	echo "Permission denied";
	exit;
}

// retrieve file and download contents
$file = $config->cvFolder . $_GET['cv'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimetype = finfo_file($finfo, $file);
finfo_close($finfo);
header('Content-Type: '.$mimetype );
echo readfile($file);

