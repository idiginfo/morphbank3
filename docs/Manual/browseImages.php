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
		<h1>Browse - Images</h1>
<div id=footerRibbon></div>

<p>
By selecting the <strong>Browse - Images</strong> option, the user will be presented with a list of
all images registered in the Morphbank database. Data about the images is available here and these images 
can be searched, <a href="<?echo $config->domain;?>About/Manual/browseSort.php">sorted</a>, <a href="<?echo $config->domain;?>About/Manual/browseC.php">collected</a> and 
<a href="<?echo $config->domain;?>About/Manual/annotation.php">annotated</a>. If logged-in and using the 
<a href="<?echo $config->domain;?>About/Manual/MyManagerImagesTab.php"><strong>My Manager - Images tab</strong></a>, 
the The <strong>Select Mass Operation</strong> feature will allow
 users to change the date-to-publish on one or many images at once, as well as create a new character or collection.
</p>
<img src="ManualImages/browse_image_page.png" alt="Browse - Images" />
<p>
<h3>Browse Using Keywords</h3>
A <strong>general</strong> or <strong>specialized keyword search</strong> can be used in <strong>Browse - Images</strong> 
to limit the display to a desired set of images. General keyword searching is also available in
Browse - View/Specimen/Locality/Collection/Publication.
   <div class="specialtext2">In this latest (beta) version of Morphbank, the Keyword Search feature is being
   enhanced. Note there are some unexpected search results as we work out the items to be included in the database
   Keywords Table. Please give us <a href="<?echo $config->domain;?>/Help/feedback/">feedback</a> on this feature.</div>
<ul>
<li><strong>General keyword search</strong> - performs a search based on a username,
taxonomic name, catalog number, form, sex, developmental stage, type
status, imaging technique, imaging preparation technique, part, image
identifier, locality, continent/ocean, country.
<ul><li>Example: <strong>randall</strong> (contributor name) entered in the general <strong>Keywords</strong> field above.
Result is 9049 images.
</li></ul></li>
<li><a name="specialkeyword"></a><strong>Specialized keyword search</strong> - use this search to browse a specific group of images.
You may enter text or specific Morphbank ids into any or all of the 4 fields: Taxon, Specimen, View &amp; Locality. Using more
than one of these will result in an "AND" search. Or, using the <img src="../../style/webImages/selectIcon.png" alt="select icon" /> 
feature, you can search for and enter any Morphbank object id automatically into any of these four fields.
</li>
<ul>
<li>Taxon - enter the taxonomic name or taxonomic serial number of interest or use the <img src="../../style/webImages/selectIcon.png" alt="select icon" />.
</li>
<li>Specimen - search based on the categories of specimen id,
sex, form, basis of record, type status, collector name, institution code,
collection code, catalog number and taxonomic name. 
</li>
<li>View - search based on the view id, imaging technique, imaging
preparation technique, part, angle, developmental stage, sex or form. 
</li>
<li>Locality - search based on the image's locality id, locality,
continent/ocean or country. 
</li>
</ul>
</ul>
</p>
<div class="specialtext2">
<div class="specialtext3">
For example, to browse for all images pertaining to <em>Gelsemium sempervirens</em>
(taxonomic name), plant body (part) reflected light (imaging technique):
perform a <strong>specialized keyword search</strong> by typing as illustrated.
</div>
<img src="ManualImages/specialized_keyword_search.png"  alt="Keyword Search" hspace="20" />
<br />
<br />
The reset button will revert back to the keyword(s) that produced the list of
images currently on the screen.
<div class="specialtext3">
<strong>Keyword Search Tips</strong>:
<ul>
<li>Separate more than one keyword with a space.
</li>
<li>Search is not case sensitive.
</li>
<li>Proper spelling will assure the best search however, typing
a partial word, will result in a corresponding search containing those
letters. (e.g. if searching for taxon braconidae, typing the letters
<strong>braco</strong>, or <strong>conidae</strong> will result in a list of braconidae data, but entering
<strong>idae</strong> would return a list that contained more than just braconidae
data. Therefore, the more complete the search word(s) is, the more
accurate the search results will be).
</li>
<li>Double quotes can be used to narrow a search. Typing <strong>"south america"</strong> will return
records containing that string exactly whereas typing: <strong>south america</strong> would return all
records containing south and america.</li>
<li>Other characters allowed include: period, comma and dash slashes. These characters: ~!@#$%^ &amp; *()+}{|][
are not accepted.</li>
</ul>
</div>
</div>
<h3>Browse Using Ids</h3>
<br />
<img align="left" src="ManualImages/search_methods.png" alt="Search Methods" vspace="10" hspace="10" />
<br />
A <strong>specialized id search</strong> can be used in <strong>Browse - Images</strong> to reduce the display list
down to a more desirable list of images.
<br />
<br />
If the Morphbank image id is known, type it in the keyword box and select Search. If the desired image id
number is not known, click on the <img src="../../style/webImages/selectIcon.png" alt="select icon" /> 
next to the Taxon field and select the desired id from the taxonomic names selection screen (see below) by clicking on 
<img src="ManualImages/select.gif" alt="select" />.
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<strong>Taxon id</strong> -  Select taxonomic identifiers from the taxonomic names selection screen.
Traverse through the levels until the appropriate scientific name is found. Then click the Select
<img src="../../style/webImages/selectIcon.png" alt="select icon" /> icon; it will automatically direct the user back to the <strong>Browse - Images</strong>
screen and the appropriate tsn will be filled in and images with that scientific name are displayed.
<br />
<br />
<img src="ManualImages/select_taxon_next_level.png" alt="select next taxon" />
<br />
<br />
<img align="right" src="ManualImages/select_specimen.png" alt="select specimen" vspace="10" />
<br />
<br />
<strong>Specimen id</strong>, <strong>View id</strong>, and <strong>Locality id</strong> fields
work the same way as the <strong>Taxon id search</strong>. Click on the <img src="../../style/webImages/selectIcon.png" alt="select icon" />
next to the field of interest. Traverse through the resulting list. Sort the list if
needed (see <strong>browse - sort</strong> for instructions unique to this
process). When the desired item is found, click on the <img src="ManualImages/select.gif" alt="select button" /> and
the screen will redirect back to the <strong>Browse - Images</strong> page and the
appropriate identifier will be filled in. Click on Search to run query with selected id's.
<br />
<br />
The user may now <a href="<?echo $config->domain;?>About/Manual/browseSort.php">sort</a> the results or, if logged in, select various images to add to a collection.
<br />
<br />
<a name="createCollection"></a>
<h3>Browse - Add Images (or any other Morphbank Object) to a Collection</h3>
<strong><em>Once logged-in, users are taken out of Browse to the new (beta) version of
Morphbank, My Manager.</em></strong> Logged-in users have the option of adding a group of images
 (and/or Objects) to a collection. (If not logged-in, the My Manager section does not appear onscreen).
  A collection might be a group of specimen images that are assembled from the Morphbank database by
Morphbank members for the purpose of viewing and/or manipulating (e.g.
rearranging the order, editing, and/or annotating, etc.) and storing the images for
future use. 
<div class="specialtext3">
Note: In this (beta) version of Morphbank a collection can now include images and other
objects such as: annotations, localities, publications, specimens, or views.
 The collections are created the same way as described here from any page 
in the <strong><a href="<?echo $config->domain;?>About/Manual/myManager.php" >My Manager</a></strong> interface.
</div>
<ul>
<li> Collections must have at least one image/object. Deleting the last image will
leave an empty collection. Do not delete it.
</li>
<li>Collections have an order based the owner's criteria. The initial order will
correspond to the order the objects were initially selected.
</li>
<li>Collections are identified by a unique internal id.
</li>
<li>Collections are published (viewable to the world) when released by the
creator (default 6 months if not otherwise notified).
</li>
</ul>
Once the desired group of images are collected and listed on the screen, the
images can be tagged for inclusion in a collection. Select the desired images
for a collection by clicking on the check box to the left of the image id on each
image to be included.

<div class="specialtext3">
Note: There are no restrictions as to the number of objects in a collection.
However, due to speed considerations, the user should exercise caution
not to exceed 100 high resolution images per collection.
</div>
If all the images listed on the screen are desired in the collection, select the
button. To undo all the checked boxes at one time click on the (<strong>uncheck</strong>)
button.
<br />
<br />
<img src="ManualImages/add_to_collection.png" alt="add image to collection" />
<br />
<br />
From the drop down list, choose the desired operation. If adding an image to an already existing Collection, the
<strong>Collection Manager</strong> will display a confirmation message before performing the
operation.
<strong>Collections</strong> can be viewed, edited, copied and annotated. See the <a href="<?echo $config->domain;?>About/Manual/collections.php"><strong>Collections</strong></a> section of
this manual for detailed instructions and permission guidelines for using these
features.
<br />
<br />
<h3>Browse - Information Icon and Taxon Hierarchy</h3>
Additional data about the image can be viewed using the <img src="../../style/webImages/infoIcon-trans.png" alt="i icon" />
 icon. Additional data about the taxonomic name can be viewed using the <a href="<?echo $config->domain;?>About/Manual/browseTaxonH.php">Taxon Hierarchy </a><img src="../../style/webImages/hierarchryIcon.png" alt="hierarchy button"/> icon<br />
<br />
Example: <strong>Single Show - Image Record Page</strong>
<div class="specialtext2">
This is an example of an image record page displayed from the <img src="../../style/webImages/infoIcon-trans.png" alt="i icon" /> information
Icon. Morphbank Single Show is an efficient way to display large amounts of
information. For complete documentation on single show refer to <a href="<?echo $config->domain;?>About/Manual/show.php">Morphbank Show</a> in the <a href="<?echo $config->domain;?>About/Manual/InfoLinking.php">Information Linking</a> section of this manual.
</div>
<img src="ManualImages/image_record_single_show.png" alt="image single show" hspace="20" />
<br />
<br />
Example: <strong>Taxon Hierarchy Page</strong>
<div class="specialtext2">
This is an example of an image record page displayed from the taxon hierarchy <img src="../../style/webImages/hierarchryIcon.png" alt="hierarchy button" />
icon.
</div>
<img src="ManualImages/taxon_hierarchy_sample.png" hspace="20" />
			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/browseTaxonH.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</div>
			<?php
//Finish with end of HTML	
finishHtml();
?>	
		

	
