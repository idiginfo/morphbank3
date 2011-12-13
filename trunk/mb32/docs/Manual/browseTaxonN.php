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
		<h1>Browse - Taxon Names</h1>
<div id=footerRibbon></div>
<p>
In this option, users can avoid possible spelling or name recollection difficulties
by using the <strong>Browse - Taxon Names</strong> option. All taxon names are listed in
alphabetic order. The default screen will list all alphabetized taxon names
starting with the letter <strong>A</strong>. To jump to another letter in the list, select that letter from the letter strip.
</p>			
<img src="ManualImages/browse_taxon_names_sample.png" />
<p>
Users may select the camera <img src="../../style/webImages/camera-min16x12.gif" /> beside a taxon name to display a
list of associated images in <a href="<?php echo $config->domain; ?>About/Manual/browseImages.php">
<strong>Browse - Images</strong></a>. Clicking the annotate <img src="../../style/webImages/annotate-trans.png" height="16" width="16" alt="annotate icon"/>
 icon gives users the ability to comment about a taxon name in the database if they have user privileges for that taxon.
Selecting the tree of life symbol <img src="../../style/webImages/hierarchryIcon.png" /> will list
the <a href="<?php echo $config->domain; ?>About/Manual/browseTaxonH.php">Taxonomic hierarchy</a> of the Taxon name.
The <img src="../../style/webImages/infoIcon.png" /> icon, where present, links to the ITIS database entry for that taxon name.
</p>
<div class="specialtext3">Notes:
<ul>
<li>
This taxonomic classification is based on the Integrated
Taxonomic Information System (ITIS) database maintained
by the United States Department of Agriculture.
</li>
<li>When a taxonomic Id has a value greater than [999000000]
it is considered a temporary id. Temporary Ids are assigned
to taxon names that have not been officially entered into
the ITIS database.
</li>
<li>The number of images shown beside the taxon names may
not be the actual count. Image counts are updated
periodically. Values that remain constant over several
hours can be assumed to be accurate. Images just
submitted may take time to publish so image(s) may not be
immediately viewable but may be listed in the count.
</li>
</ul>
</div>
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?php echo $config->domain; ?>About/Manual/taxonNameSearch.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
			</table>
			</div>
		<?php
//Finish with end of HTML	
finishHtml();
?>	
	
