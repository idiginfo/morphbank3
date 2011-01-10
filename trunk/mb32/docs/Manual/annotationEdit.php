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
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>
	
		<div class="mainGenericContainer">
		<!--change the header below -->
		<h1>Edit Annotations</h1>
<div id=footerRibbon></div>
<p>Edit annotations contains the previously entered annotation data that can be
edited by the owner (only available if the annotation is not yet published.) Take
note that the <strong>type of annotation</strong> can not be altered.</p>
<p>Collection information and Annotation information are edited from their respective
tabs in the new My Manager interface. (The old Collection and Annotation Managers have been removed).
If logged-in, you can access Edit from the My Manager tabs, using the <img src="../../style/webImages/edit-trans.png" /> icon found on each annotation record.</p>
<img src="ManualImages/tools_edit_annotation.jpg" vspace="5" />
<br />
<br />
This example is from clicking on the 
<img src="../../style/webImages/edit-trans.png" height="16" width="16" alt="edit icon"/> icon from
the above <strong>General Annotation [196276] Gonobase absent</strong> in the 
<strong>My Manager</strong> Annotations tab.
<img src="ManualImages/edit_annotation_sample.png" vspace="5" />

<p>Change or add to the fields desired. Click on Submit to apply the changes.</p>
			<br />
			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/annotationShow.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	
