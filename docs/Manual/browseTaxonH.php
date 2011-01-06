<?php 
	//global $includeDirectory, $dataDirectory, $imgDirectory;
	global $domainName;
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>

		<div class="mainGenericContainer" width="100%">
		<!--change the header below -->
		<h1>Browse - Taxon Hierarchy</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
			
<br />
<img src="ManualImages/browse_taxon_hierarcy_window.png" />
<br />
<br />
In this option, users are placed at the top level of the taxonomic hierarchy to view all
Kingdoms. By default the user will browse the taxon hierarchy through all
available categories containing all available taxa. By selecting Only Major Categories
the user will have the option to browse using only major hierarchy categories (kingdom,
phylum, class, order, family, genus, and species). The Only valid/accepted taxa option
will limit the browse to taxa that are ITIS accepted. By selecting the Only taxa with
images, the resulting list will contain only the categories that are currently populated
with images.
<br />
<img align="left" src="ManualImages/reveal_taxon_hierarchy.png" />
<br />
<br />
By left-clicking on the name of the Kingdom, the tree is expanded to reveal all levels
below that Kingdom. To the right of each name is a number which indicates the
approximate number of images in that category along with several icons.
If images are available, the camera icon <img src="../../style/webImages/camera-min16x12.gif" />appears. To view information about the
particular name, users can select the information icon <img src="../../style/webImages/infoIcon.png" />which will display additional
information. The <img src="../../style/webImages/annotate-trans.png" /> icon allows users with certain log-in permissions to enter an
annotation concerning a taxon name in the database.
<br />
<br />
<img src="ManualImages/browse_taxon_hierarchy_levels.png" />
<br />
<br />
Continuing to left-click on subsequent scientific names will expose progressively
lower levels of the hierarchy tree. Each level will contain the same
screen options as listed above. Selecting the right arrow <img src="ManualImages/tree_open.png" /> on the Taxon Hierarchy screen advances the
tree to the next taxon level. The open, right facing arrow <img src="ManualImages/tree_open_hollow.png" /> signifies that the tree
can be expanded further by selecting the desired scientific name. If the downward arrow <img src="ManualImages/tree_closed.png" /> that has lower
hierarchy levels exposed is selected, the list is collapsed to that arrow which becomes a
right facing arrow <img src="ManualImages/tree_open.png" />. Click the arrow again to return to the previous state.
<div class="specialtext3">Note: The number of images shown beside the taxon names may not be
the actual count. Image counts are updated periodically. Values that remain
constant over several hours can be assumed to be accurate. Images just
submitted may take time to publish so image(s) may not be immediately
viewable but may be listed in the count.
</div>
<h3>Example: Browse - Images Page</h3>:  From the above <strong>Browse - Taxon hierarchy</strong> page, a list of images is displayed when the camera icon <img src="../../style/webImages/camera-min16x12.gif"> 
next to <em>Epinephelus tauvina</em> is selected.<br />
<br />
<img src="ManualImages/browse_taxon_to_browse_images.png" hspace="30"/>
<br />
<br />
Tag descriptions.
<ol>
<li>Taxonomic serial number assigned to <em>Epinephelus tauvina</em>
</li>
<li>Select <img src="../../style/webImages/hierarchryIcon.png" /> to advance to hierarchy tree or to the alphabetical list of all
taxonomic names in morphbank
</li>
<li>The list of images for the species. In this case there are 10 images. If > 10, they will be displayed on multiple pages.
</li>
<li>Annotation <img src="../../style/webImages/annotate-trans.png" />, edit <img src="../../style/webImages/edit-trans.png" />and information <img src="../../style/webImages/infoIcon.png" /> icons
</li>
</ol>
<div class="specialtext3">Screen Use Tips:
Use the check boxes (located left of the image id) to select images to add to a collection.
<br /><br />
The number of hits displayed on each page can be designated and a user can advance to a specific page
number by listing that page and selecting the <img src="ManualImages/go.gif" alt="go" /> button). Keep in mind that the quantity of information requested to display
per page will affect the speed at which that screen loads (i.e. requesting 100 records per page will take longer to load than the screen that has only
10 records to load.)
<br />
<br />
Selecting the information icon <img src="../../style/webImages/infoIcon.png" /> will display detailed information about the specimen. 
Other options such as annotate <img src="../../style/webImages/annotate-trans.png" /> and edit <img src="../../style/webImages/edit-trans.png" /> will be available only 
for those authorized through login permissions. Any thumbnail image can be reproduced in its original format by selecting the [jpg] or [tif] option (images with
other formats will list that option). Selecting the tree of life symbol <img src="../../style/webImages/hierarchryIcon.png" /> will list the taxonomic hierarchy of the Taxon name.
</div>
<h3>Example: Browse - Itis Report</h3>
<br />
<br />
Displayed from Information Icon <img src="../../style/webImages/infoIcon.png" />, <strong>Browse - Taxon Hierarchy page</strong>.
<br />
<br />
This taxonomic classification page is provided by the <a href="http://www.itis.gov/" target="_blank">Integrated Taxonomic Information System (ITIS)</a> database maintained by the United States Department of Agriculture
(USDA). ITIS was selected as the taxonomic name server for morphbank in 2004
because it represented the most complete comprehensive taxonomic name service
available at the time. Also, the entire database could be downloaded locally making
access to the data quick and efficient.
<br />
<br />ITIS is a consistent service. It has a high level of stability and a rigid review system.
Since ITIS is maintained by the USDA, the probability that the service will be persistent
for several years is high. Taxonomic names are entered into the system and panel of
experts periodically review the names for quality assurance.
<br />
<br />When a taxonomic Id has a value greater than [999000000] it is considered a temporary
id. Temporary Ids are assigned to taxon names that have not been officially entered into
the ITIS database.
<br />
<br />
<img src="ManualImages/sampleItisReport%20copy.gif" />
<br />
<br />
<h3>Example: Single Show - Image Record</h3>
<br />
<br />
Displayed from the information icon <img src="../../style/webImages/infoIcon.png" /> on the 
<strong>Browse Images</strong> page. This page displays information unique to this image. 
Morphbank Single Show is an efficient way to display large amounts of information. For complete
documentation on single show refer to <a href="<?echo $domainName;?>About/Manual/show.php">Morphbank Show</a> in the Information
Linking section of this manual.
<br />
<br />
<img src="ManualImages/image_record_single_show.png" />
			<br />
			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $domainName;?>About/Manual/browseTaxonN.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $domainName;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</div>
			<?php
//Finish with end of HTML	
finishHtml();
?>	
	
