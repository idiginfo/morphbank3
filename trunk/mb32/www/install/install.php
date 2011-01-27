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

require_once('install_functions.php');

if (!is_writable('../../log')) {
	$msg = "Application log directory must be writable";
	include('installForm.php');
} else if (!is_writable('../../configuration')) {
	$msg = "Application configuration directory must be writable";
	include('installForm.php');
} else if (file_exists('../../configuration/config.ini')) {
	die("/configuration/config.ini file already exists. Application is installed.");
	exit;
} else {
  	$msg = installApp();
  	include('install_form.php');
}
