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
		<h1>Browse - Localities</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
<p>A locality includes detailed information about where a specimen was collected.
By selecting the <strong>Browse - Locality</strong> option, the user will be presented with a list of
all localities registered on the morphbank database.
</p>
<img src="ManualImages/browse_locality.png" />
<br />
<br />
<h3>Browse - Localities by Keywords</h3>
<br />
<br />
As explained in <a href="<?echo $config->domain;?>About/Manual/browseImages.php">Browse - Images</a>,
use the <strong>Keywords</strong> field to display a list of localities based on its locality field,
continent/ocean and/or country. To display a list of localities based on a
keyword(s) search, type the keyword(s) in the box and select Search. For
example, to browse for all localities pertaining to the <strong>Africa</strong> (continent) <strong>Zimbabwe</strong>
(country) and <strong>Harare</strong> (locality); type in <strong>africa zimbabwe harare</strong> and select<strong> Search</strong>.
<br />
<br />
<img src="ManualImages/browse_locality_details.png" />
<br />
<br />
<h3>After a Keyword Search of Localities - Sort the Results, View Images, See Locality Details</h3>
<br />
<br />
Tag descriptions
<ol>
<li>Note the keyword search in this example results in 2 Localities.</li> 
<li>After a Keyword Search  of the Localities, one can sort the resulting list of localities. 
Select the Sort By criteria from the drop down list(s). The more criteria selected, (up to 3 levels) 
the more refined the browse will be. The resulting page will display the locality list with the initial Sort By option grouped together 
first, followed by groups of any of the other sort criteria that was selected. 
The Sort feature of morphbank is explained in detail in <a href="<?echo $config->domain;?>About/Manual/browseSort.php">Browse - Sort Search Results</a>
<br />
<br />
Sort criteria options include:
<ul>
<li><strong>Location id</strong>: Unique morphbank-issued identifier for a locality
</li>
<li><strong>Continent ocean</strong>: name of continent or ocean where the specimen was collected
</li>
<li><strong>Country</strong>: name of the country where the specimen was collected
</li>
<li><strong>Locality</strong>: detailed description of where the specimen was collected
</li>
<li><strong>Number of images</strong>: pertaining to one specimen
</li>
</ul>
<br />
<li><h3>Browse - Locality: Single Show</h3> is displayed from any information 
Icon <img src="../../style/webImages/infoIcon.png" /> on any <strong>Browse - Locality</strong> page.
This page displays Information unique to a locality record. For complete
documentation on single show refer to <a href="<?echo $config->domain;?>About/Manual/show.php">Morphbank Show</a> in the Information
Linking section of this manual.</li>
<li><h3>Viewing Images for a Given Locality:</h3> Any given Locality may have 1 or more
 images associated with it; for the example above, locality id 111689 has 5 images. To see the images for a particular
locality, click on the <img src="../../style/webImages/camera-min16x12.gif"> icon for that Locality.
A <strong>Browse - Images</strong> page will open displaying these images. In <strong>Browse - Images</strong> use the <img src="../../style/webImages/hierarchryIcon.png" /> icon
to see the hierarchy tree or go to the alphabetical list of all taxonomic names in morphbank. 
For any image in this <strong>Browse - Images</strong> page, each image is associated with a specimen. 
Left click on any thumbnail to display the Single Show details for any image.</li>
<li>Morphbank-issued Locality id number and Selection <img src="../../style/webImages/selectIcon.png" />: The locality
id number can be used to search in the <strong>Browse - Images</strong> or <strong>Browse - Locality</strong> modules. To search for
and select a Locality use the <img src="../../style/webImages/selectIcon.png" /> feature further explained in the 
<a href="<?echo $config->domain;?>About/Manual/browseImages.php">Browse - Images</a>
section of this manual.</li>
</ol>
<br />
<br />
<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/browseC.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			<!--</tr>-->
			<!--</table>-->
			</div>
		<?php
//Finish with end of HTML	
finishHtml();
?>
	
