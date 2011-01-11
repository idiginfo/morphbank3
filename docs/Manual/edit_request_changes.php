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
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);

	?>
		
<div class="mainGenericContainer" width="100%">
<!--change the header below -->
<h1>Edit: View and Request Changes</h1>
<div id=footerRibbon></div>
<br />
Some information in morphbank is used by a large number of users.  Only requests can be made to edit information in these tables:
<ul>
    		<li>Imaging Technique</li>
            <li>Imaging Preparation Technique</li>
            <li>Specimen Part</li>
            <li>Sex</li>
            <li>Form</li>
            <li>Developmental Stage</li>
            <li>View Angle</li>
            <li>Link Type</li>
</ul>

You can view the information from <b>Tools> Edit</b>
<br />
<br />
<img src="ManualImages/edit_view_request_changes.jpg" hspace="20" />
<br />
<br />
To request a change, modify the desired fields above and when you hit the 
<img src="../../style/webImages/buttons/updateButton.jpg" /> button an email will be generated for 
morphbank administration.
<br />
<br />
Example email next shows a request to change the Description field of the Imaging Technique ESEM from "not provided"
to "Environmental Scanning Electron Microscope."
<br />
<img src="ManualImages/edit_request_mail.jpg" vspace="5" />
<br />
A user may add comments for the morphbank team and click the send button to email the request.
			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/edit_taxon_name.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
				</div>
		
			<?php
// Finish with end of HTML
finishHtml();
?>
	
