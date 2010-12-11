#-------------------------------------------------------------------------------
# Copyright (c) 2010 Greg Riccardi, Fredrik Ronquist.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Public License v2.0
# which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# 
# Contributors:
#   Fredrik Ronquist - conceptual modeling and interaction design
#   Austin Mast - conceptual modeling and interaction design
#   Greg Riccardi - initial API and implementation
#   Wilfredo Blanco - initial API and implementation
#   Robert Bruhn - initial API and implementation
#   Christopher Cprek - initial API and implementation
#   David Gaitros - initial API and implementation
#   Neelima Jammigumpula - initial API and implementation
#   Karolina Maneva-Jakimoska - initial API and implementation
#   Katja Seltmann - initial API and implementation
#   Stephen Winner - initial API and implementation
#-------------------------------------------------------------------------------
<?php
include_once('head.inc.php');

// The beginnig of HTML
$title =  'About - How to contribute';
initHtml( $title, NULL, NULL);

// Add the standard head section to all the HTML output.
echoHead( false, $title);

$contributeHtml = file_get_contents('content/contribute.html', true);
// Output the content of the main frame
echo '<div class="mainGenericContainer" style="width:700px">'.$contributeHtml.'</div>';

// Finish with end of HTML
finishHtml();
?>
