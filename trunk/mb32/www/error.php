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

include_once('head.inc.php');


/* Include javascript files */
$includeJavaScript = array('jquery.1-4-2.min.js', 'jquery.validate.min.js', 'formMethods.js');

/* Begin HTML */
$title = 'Error';
initHtml( $title, NULL, $includeJavaScript);

/* Add the standard head section to all the HTML output. */
echoHead(false, $title);
echo '<div class = "mainGenericContainer" style="width:Auto">';

// Put container content here
echo '<div class="searchError">An Error has occurred</div><br /><br />';
echo '<div class="searchError">';
echo $_SESSION['errMsg'] . "  Please contact the administration.";
echo '</div><br /><br />';

unset($_SESSION['errMsg']);

// Finish HTML
echo '</div>';
finishHtml();
