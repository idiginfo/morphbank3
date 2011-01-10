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

	
		<div class="mainGenericContainer" width="100%">
		<!--change the header below -->
		<h1>Getting Started</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
			<td width="100%">
<p>		
Opening page at the Morphbank website at <a href="<?echo $config->domain;?>" target="_blank">http://www.morphbank.net</a>. All users of Morphbank see essentially the same web-interface. Enter a search term or click <strong>Browse</strong> in the header and choose from the drop-down menu (see below) to browse Morphbank via the My Manager interface. Those with Morphbank accounts login to enable features not available to the public such as: submit, edit, account &amp; group settings, collection and annotation modules. The general public sees only those Morphbank objects that are published. 
</p>
<img src="ManualImages/intro_morphbank2.png" hspace="20" />
<p>Under each term in the <em>header menu</em>, drop-down sub-menus appear (see screen shot next).
<ul>
<li><strong>About</strong>: reveals links to information about the history of Morphbank, how to cite Morphbank, our sponsors and collaborators, and the Morphbank Team.
</li>
<li><strong>Browse</strong>: opens links to My Manager -- Morphbank's web user-interface. Users can access Image, View, Specimen, Locality, Publication, Collection, Annotation &amp; Taxonomic information here.
</li>
<li><strong>Tools</strong>: Those logged into Morphbank use options found under <strong>Tools</strong> to submit data / images to Morphbank and access their account / group settings.
</li>
<li><strong>Help</strong>: provides access to an online user manual, downloadable forms for submitting data to Morphbank, an automated taxonomic name query script and an automated feedback form.
</li>
</ul>
</p>
<img src="ManualImages/dropdownmenus.png" hspace="20" />

<br />
<br />		
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/login.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
	</div>
		
			<?php
//Finish with end of HTML	
finishHtml();
?>	
