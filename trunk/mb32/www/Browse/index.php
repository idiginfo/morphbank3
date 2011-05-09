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
include_once('mainBrowse.php');

/**
 * Seting the title of the main window.
 */
$title = 'Browse';
/**
 * initHtml: echo out the beginnig and the header of the Html code.
 */
initHtml($title, null, null);

/**
 * echoHead: Add the standard head section for all the Html code.
 */
echoHead(false, $title);

/**
 * mainBrowse: displays the content of the main frame.
 */
mainBrowse();

/**
 * finishHtml: echo out the end of Html code.
 */
finishHtml();
?>