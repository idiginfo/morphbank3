<?php 
	//global $includeDirectory, $dataDirectory, $imgDirectory;
	
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>
	
		<div class="mainGenericContainer">
		<!--change the header below -->
		<h1>Annotation Record Show</h1>
<div id=footerRibbon></div>
	
<p>This is an example of an annotation record page displayed from the <strong>
<a href="<?echo $config->domain;?>About/Manual/myManager.php" target="_blank" >My Manager</a> > 
<a href="<?echo $config->domain;?>About/Manual/myManagerAnnotations.php" target="blank" >Annotation Tab</a>
> <img src="../../style/webImages/infoIcon.png" alt="information icon" /> Info Icon</strong>. Morphbank <strong>Single Show</strong> is an
efficient way to display large amounts of information. For complete documentation on single
show refer to <a href="<?echo $config->domain;?>About/Manual/show.php" target="_blank" >Morphbank Show</a> in the Information Linking section of this manual.</p>
<!--<img src="ManualImages/annotation_show_from_id.png" />-->
<br />
Sample <strong>Annotation Show</strong> from click on <strong>
<img src="../../style/webImages/infoIcon.png" alt="information icon" /> Info Icon</strong> in the 
<a href="<?echo $config->domain;?>About/Manual/myManager.php" target="_blank" >My Manager</a> > 
<a href="<?echo $config->domain;?>About/Manual/myManagerAnnotations.php" target="_blank" >Annotation Tab.</a>
<br />
<br />
<img src="ManualImages/annotation_show.png" />
<p>In <strong>Annotation Record Show</strong>, the user is presented with a list of all annotations
associated with the object to include all annotations related to the image and
specimen. The user may click on Add Annotation also, if desired, to add another annotation to this same
Morphbank object.</p>

<!--<p>Clicking on this section will bring up the <strong>Related Annotations</strong> page which
contains all the tools needed for a user to research annotations associated with
the current annotation record.</p>
<h2>Use the Related Annotation page to:</h2>
<ul>
<li>View a scrollable list of all images related to the annotation of the same
specimen, images with the same taxonomic name, images with the same
view, and images in collections to which the current image belongs.</li>
<li>Email an annotation to another party for viewing.</li>
<li>View related image, specimen or view data. This option utilizes the
Morphbank Show option to display a full set of information on the image
data, the specimen data, or data about the view associated with the
image.</li>
<li>Add a new annotation to the current image by calling the single add
annotation screen or sort the current list of related annotations.</li>
</ul>

<h2>Related Annotations</h2>
<p>Related Annotations contains all the tools needed for a user to research
annotations associated with the current annotation record.
</p>
INSERT Image of Related Annotations Screen with TAGS
<p>The <strong>Related Annotation</strong> page is designed to display all
of the information associated with a particular annotation and display links to
detailed data on the specimen, image, view , locality, and determination.
Additionally, Related Annotations permits the user to view the image in more detail
using a commercial image viewer product called the FSI Viewer from Neptune Labs.
<p/>
<p>Since any single object within morphbank may have several related objects, this screen
displays some of those relationships. The user can, by selecting the related image drop-down
menu, display other images related to the current image, specimen of the image, species,
images with the same view, or all of the images in collections where the current image is also
contained.</p>
Tag descriptions
<ol>
<li>Clicking on <strong>Related Images</strong> will display a drop-down list for the user to
select which category of <strong>Related Annotations</strong> to display. The related images
will display in the list at the bottom of the page.</li>
INSERT Related Images Dropdown Menu
<li>The <strong>Mail</strong> option allows users to email the
annotation URL to any valid email address for viewing
with an accompanying user supplied message. A sent
email contains the text
along with a morphbank URL that will allow the
recipient to view the image using the
<strong>Morphbank Show</strong> feature.</li>
INSERT Mail
<li>The View drop-down box
displays a selection choice of record data types that can
be displayed on the screen.</li>
INSERT View
<li>This
option brings up the commercial image viewer
product called the FSI Viewer from Neptune Labs.
This viewer gives the user many more
viewing options. Complete instructions for this viewer
can be found at <a href="<?echo $config->domain;?>About/Manual/FSI.php">FSI Viewer</a> located in this
manual,</li>
INSERT Image>View Image
<li>Use this option to add an annotation or sort the
onscreen list of related annotations by title, author, or
date. The previous order of related annotations and
collection images are maintained</li>
INSERT Annotation Drop Down
<li>List of related annotations. This list is also a hot-link that allows the user
to display that annotation data on the current web page. Select to reveal that
annotation</li>
<li>List of related images. This is where the images from tag 1 above are
deposited. Additionally, clicking on the thumbnail images of the related images
will display related annotations associated with that image.</li>
<li>The image is shown in a larger format than normally seen in the rest of
morphbank. The image displays any designated annotation marker and label,
(overlaid arrow and label). Clicking on the image brings up the image viewer
which allows the image to be viewed in more detail using a commercial image
viewer product called the FSI Viewer from Neptune Labs.</li>
</ol>
-->
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/annotate_taxon_name.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	
