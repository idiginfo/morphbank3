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
		<h1>Browse - Views</h1>
<div id=footerRibbon></div>
			
<p>A view specifies the criteria (the type of taxa, view angle, preparation technique,
etc.) under which a photograph was taken. 
<br />By selecting the <strong>Browse - View</strong> option, the
user is presented with a list of all the registered views within the database.
</p>
<img src="ManualImages/browse_views.png" hspace="20" />
<br />
<br />
<p><h3>What's in a View?</h3>
<br />
<img src="ManualImages/view_sample.png" />
</p>


<br />
<br />
<h3>Browse - Views by Keywords</h3>
<br />
<br />
As explained in <a href="<?php echo $config->domain; ?>About/Manual/browseImages.php">Browse - Images</a>,
use <strong>Keywords</strong> to display a list of views based on imaging technique, imaging
preparation technique, part, angle, developmental stage, sex and/or form. To
display a list of views based on a keyword(s) search, type the keyword(s) in the
box and select <strong>Search</strong>. For example, to browse for all views pertaining to
wings (specimen part), reflected light (imaging technique), female (sex) 
type keywords <strong>wings reflected light female</strong> and select <strong>Search </strong>.
<br />
<br />
<img src="ManualImages/browse_view_keyword_search.png" hspace="20" />
<br />
<br />
<h3>Sort the Results</h3>
<br />
<br />
Outlined in detail in <a href="<?php echo $config->domain; ?>About/Manual/browseSort.php">Browse - Sort Search Results</a>, 
to sort the list of views, select the <strong>Sort By</strong> criteria from the drop down list(s).
The more criteria selected, (up to 3 levels) the more refined the browse will be. 
The resulting page will display the view list with the
initial Sort By option grouped together first, followed by groups of any of the
other sort criteria that was selected.
<br />
<br />
Sort criteria options for <strong>Browse - Views</strong> include:
<ul>
<li><strong>View id</strong>: Unique MorphBank-issued identifier for a view
</li>
<li><strong>Specimen part</strong>: pertains to a view that contains a portion of a specimen
</li>
<li><strong>Angle</strong>: location of the camera with respect to the specimen for
photographing
</li>
<li><strong>Sex</strong> : present for specimens when known or applicable
</li>
<li><strong>Stage</strong>: developmental growth phase of specimen
</li>
<li><strong>Form</strong>: specimens may have a form of parthenogenetic, indeterminate,
unknown, etc.
</li>
<li><strong>Imaging</strong>: technique used to capture photo such as auto-montage,
transmitted light; bright field, etc.
</li>
<li><strong>Imaging Preparation Technique</strong>: technique used to prepare the specimen
for photographing such as dissected, air dried and gold coated etc.
</li>
<li><strong>Taxon</strong>: scientific name of the specimen
</li>
<li><strong>Number of images</strong>:pertaining to one specimen
</li>
</ul>
Use the <strong>Reset </strong>button to
clear the Search and Sort
By boxes of all criteria.
<br />
<br />
<h3>Display Images associated with a View</h3>
<br />
<br />
From <strong>Browse - Views</strong>, the images for any given View can be seen by clicking on the camera icon <img src="../../style/webImages/camera-min16x12.gif" /> to
go to <strong>Browse - Images</strong>.
<br />
<img src="ManualImages/browse_view_to_browse_images.png" />
<br />
Tag descriptions
<ol>
<li>Morphbank-issued unique identifier number for the view.
</li>
<li>List of images associated with the view 63978
</li>
<li>Select to advance to hierarchy tree
</li>
</ol>
<h3>Browse Views - Single Show</h3>
<br />
<br />
Click the information Icon <img src="../../style/webImages/infoIcon.png" /> on the
<strong>Browse - Views</strong> page to open the View Record - Single Show.
<br />
<img src="ManualImages/browse_view_to_single_show.png" />
<br /><br />
Morphbank Single Show is an efficient way to display large amounts of
information. For complete documentation on single show refer to <a href="<?php echo $config->domain; ?>About/Manual/show.php">Morphbank
Show</a> in the Information Linking section of this manual.
			<br />
			<br />
						<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?php echo $config->domain; ?>About/Manual/browseS.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
			</table>
			</div>
			<?php
//Finish with end of HTML	
finishHtml();
?>	
	

	
