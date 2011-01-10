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
		<h1>Browse - Specimens</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
<br />
By selecting the <strong>Browse - Specimens</strong> option, the user will be presented with a list
of all specimens registered in the morphbank database.
<br />
<br />
<img src="ManualImages/browse_specimens.png" />
<br />
<br />
<h3>Browse - Specimens by Keywords</h3>
<br />
<br />
As outlined in <a href="<?echo $config->domain;?>About/Manual/browseImages.php" target="_blank">Browse - Images</a>,
use <strong>Keywords</strong> search to display a list of views based on sex, form, basis of
record, type status, collector name, institution code, collection code, catalog
number, and/or taxonomic names. To display a list of specimens based on a
keyword(s) search, type the keyword(s) in the box and select <strong>Search</strong>. For
example, to browse for all specimens pertaining to <strong>Alectis</strong> (taxonomic name), <strong>adult</strong>
(developmental stage): type in <strong>Alectis adult</strong> and select <strong>Search</strong>.
<br />
<br />
<img src="ManualImages/browse_specimen_keyword.png" />
<br />
<br />
<h3>Sort the Results</h3>
<br />
<br />
As explained in <a href="<?echo $config->domain;?>About/Manual/browseSort.php" target="_blank">Browse - Sort Search Results</a>, 
to sort the list of specimens, select the Sort By criteria from the drop down
list(s). The more criteria selected, (up to 3 levels) the more refined the browse
will be (Figure 32). The resulting page will display the specimen list with the
initial Sort By option grouped together first, followed by groups of any of the
other sort criteria that was selected.
<br />
<br />
Sort criteria options for <strong>Browse - Specimen</strong> include:
<ul>
<li><strong>Specimen id</strong>: Unique morphbank-issued identifier for a specimen
</li>
<li><strong>Basis of record</strong>: at the time of collection, the specimen was categorized
as an observation, a living organization, a specimen, a germ
plasm/seed
</li>
<li><strong>Sex</strong>: present for specimens when known or applicable
</li>
<li><strong>Form</strong>: Morphotype of the specimen. Specimens may have a form of
parthenogenetic, indeterminate, unknown, etc.
</li>
<li><strong>Developmental stage</strong>: developmental growth phase of specimen
</li>
<li><strong>Type status</strong>: specimen that is universally accepted as being a clear
example of its species
</li>
<li><strong>Collector name</strong>: records are grouped by person who collected the specimen
</li>
<li><strong>Date</strong>: when the specimen was collected
</li>
<li><strong>Number of images</strong>: pertaining to one specimen
</li>
<li><strong>Country</strong>: where the specimen was collected
</li>
</ul>
<h3>Browsing Images and Specimen Record data from <strong>Browse - Specimen</strong></h3>
<br />
<br />
The <img src="../../style/webImages/camera-min16x12.gif"/> icon in the <strong>Browse - Specimen</strong> page will
open the <strong>Browse - Images</strong> page displaying the images for a given specimen.
Clicking the information icon <img src="../../style/webImages/infoIcon.png" /> for any specimen on
a <strong>Browse - Specimens</strong> page will display information unique to this specimen (Specimen
Record - Single Show). Morphbank Single Show is an efficient way to display large amounts of information. For complete
documentation on single show refer to <a href="<?echo $config->domain;?>About/Manual/show.php">Morphbank Show</a> 
in the Information Linking section of this manual.
<br />
<img src="ManualImages/browse_specimen_to_browse_images.png" />
			<br />
			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/browseL.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</div>
		<?php
//Finish with end of HTML	
finishHtml();
?>	

	
