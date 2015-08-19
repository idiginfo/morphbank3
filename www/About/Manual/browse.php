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
		<h1>Browse</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
			<td width="100%">
			
<p>
Select <strong>Browse</strong> when there is a need to scan a hierarchical tree or list. <strong>Browse</strong>
is directly accessible through the opening screen or from the <strong>Browse</strong> area on
any Morphbank page header.
</p>
<img src="ManualImages/browse_intro.png" />
<p>
The Browse option does not require login, however, logged-in users have varied
tools accessible within Browse based on user privileges (e.g. Collections, Edit,
Annotate). The browse options include:
<ul>
<li><strong><a href="<?php echo $config->domain; ?>About/Manual/browseImages.php">Images</a></strong> (View a sortable list of all images in
the morphbank database. The list of images can be restricted by use of
keyword or id searches. If the user is logged-in, other options such as
edit, annotate, and collections might be available).
</li>
<li><strong><a href="<?php echo $config->domain; ?>About/Manual/browseTaxonH.php">Taxon hierarchy</a></strong> (View all kingdoms from
the top level of the taxonomic hierarchy or users can traverse the taxon
hierarchy using only major categories).
</li>
<li><strong><a href="<?php echo $config->domain; ?>About/Manual/browseTaxonN.php">Alphabetical taxon name</a></strong> (All taxon names
beginning with letter A are displayed. Use the alphabet bar at the top of the screen to choose taxon names starting with a different
letter).
</li>
<li><strong><a href="<?php echo $config->domain; ?>About/Manual/browseV.php">View</a></strong> (Search for views based
on a keyword or a sortable list of all the registered views within the
database).
</li>
<li><strong><a href="<?php echo $config->domain; ?>About/Manual/browseS.php">Specimen</a></strong> (Search for
specimens based on a keyword or a sortable list of all the specimens
currently located on the Morphbank database).
</li>
<li><strong><a href="<?php echo $config->domain; ?>About/Manual/browseL.php">Locality</a></strong> (Search for localities
based on a keyword or a sortable list of all the available localities within
the database).
</li>
<li><strong><a href="<?php echo $config->domain; ?>About/Manual/browseC.php">Collections</a></strong> (Search for
existing collections based on a keyword or a sortable list of all the
available collections within the database).
</li>
<li><strong><a href="<?php echo $config->domain; ?>About/Manual/browseP.php">Publication</a></strong> (Search for
existing publications based on a keyword or a sortable list of all the
available publications within the database).
</li>
<!--<li><strong><a href="<?php echo $config->domain; ?>About/Manual/browseM.php" target="_blank">Mirrors</a></strong> (Search current morphbank mirrors).
</li>-->
</ul>
</p>
			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?php echo $config->domain; ?>About/Manual/browseImages.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
	</div>
		
			<?php
//Finish with end of HTML	
finishHtml();
?>	

	
