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
<h1>Edit Annotation</h1>
<div id=footerRibbon></div>
<br />
Edit Annotation contains the previously entered annotation data that can be edited by the owner
 (<em>only available if the annotation is not yet published</em>.)  
<div class="specialtext2">

Edit Annotation is now accessed through the Annotations tab in the <em><strong>new My Manager interface</strong></em>. Select 
from the Header Menu <b>Tools > My Manager </b> then click the Annotations tab. 
A user may click the <img src="../../style/webImages/edit-trans.png" /> <strong>edit icon</strong> of
any of their own<em> unpublished</em> annotations to make necessary changes.
</div>

The information included on the Edit Annotation screen reflects all the previous data that was included on the original annotation.  


To edit the information on this page, click on the appropriate area to highlight the data 
and type in or select the corrected information.  The type of annotation cannot be changed.
If, however, the annotation has not been published, it can be deleted entirely and re-entered under the proper type.  
Help in filling out the data fields on this page can be obtained 
in <a href="<?echo $config->domain;?>About/Manual/annotationAdd.php">Add Annotations</a> located in this manual. After the
changes are made, click the <img src="ManualImages/submit_button.gif" /> button to save the changes.
<br />
<img src="ManualImages/edit_annotation_screen.jpg" vspace="10" hspace="20"/>
			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/edit_collections.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
				</div>
		
			<?php
// Finish with end of HTML
finishHtml();
?>
	
