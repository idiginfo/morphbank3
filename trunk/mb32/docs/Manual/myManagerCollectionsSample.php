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
	
		<div class="mainGenericContainer">
		<!--change the header below -->
		<h1>Collections Illustrated</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
<br />
<h3>Sample Collection</h3><br />
<img src="ManualImages/collectionSample.jpg" vspace="3" />
<br />
Tag descriptions
<ol>
<li><strong>Tools</strong>:
<ul><li><img align="left" src="ManualImages/collectionSelect.jpg" /> To use <strong>Tools</strong>, images in the collection need to be
tagged by using the check box in the lower left hand
corner of each image as shown.
When the image is selected, its screen is highlighted so selected images are visible
at a glance.</li>
<br />
<br />
<br />
<br />
<br />
<li>Note Well, many of the <strong>Tools</strong> work only for Collections of Images. If a collection is created from mixed objects (Images,
Specimens, Views, Publications, etc...) some of the <strong>Tools</strong> do not function as they are not meaningful anymore.</li>
<li><strong>Copy or move checked objects</strong>: Users have the option of copying or
moving objects to a new collection or to another owned, unpublished
collection. Check the images to include, then select the desired option
from the dropdown list.</li>
<li><strong>Label checked objects</strong>: Users have the option of labeling the checked
images by taxon name, specimen id, specimen part or view angle. Select
the desired label criteria from the dropdown list.</li>
<li><strong>Annotate checked objects</strong>: Users can annotate an image (single
annotation) or an entire collection of images at one time (mass
annotation). Complete instructions for this process can be found in Add
Annotations located in the Annotations chapter of this manual.</li>
<img src="ManualImages/sampleMassAnnotation.jpg" vspace="3" />
<li><strong>Delete checked objects</strong>: Users can permanently delete any of the
checked images from an unpublished collection by selecting this option
(checking all of the images in the collection deletes the entire collection). A
confirmation message will appear prior to completing the delete.</li>
</ul>
</li>
<li><strong>Collection display settings</strong>:
<ul>
<li>Collection images <strong>display size</strong>: The default, most manageable image size
is small due to load speed and space requirements. Use the<em> slider bar</em> to increase
the image display size (May take longer to load). The aspect ratio is retained
for all choices.</li>
<li><strong>Post It</strong>: Hovering over an image in a collection will display a post it note
containing additional information. This feature can be turned off by
selecting the appropriate radio button.</li>
<li><strong>Icons</strong>: Each image in a collection contains a row of icons (selection box,
edit, annotate, information) that may be turned on or off as needed.
<img src="ManualImages/collectionSelectHighlight.jpg" />
</li>
</ul>
</li>
<li><strong>Save the order</strong>:
The order of the images in the collection can be changed manually. Drag
and drop the image(s) into the new location. Retaining the
new order requires selecting the <strong>Save Order</strong> button. If this is not done, 
the order will revert back to the previous state when exiting out of the
collection.<br />
<img src="ManualImages/collectionMoveImage.jpg" vspace="3" />
</li>
<li><strong>Collection list</strong>:
Offers the user a list of all the collections that have been created <em><strong>under the
current username and group</strong></em>. The current collection that is being displayed
is highlighted dark grey. The collections which are light grey are published
collections. The red number in brackets corresponds to the number of
images in the collection. Clicking on a collection will display the collection
contents and saved order. To access other collections under the same
username but created under another authorized group, return to the
<strong>Select Group</strong> screen and login under that group. There is no limit on the
number of collections a user may have.</li>
<li><strong>Check buttons</strong>:
These buttons are used to check or uncheck an entire collection of
images or objects.</li>
<li><strong>Number of images</strong>:
This number reflects the current number of images/objects in the collection.</li>
<li><strong>Published collection</strong>:
Published collections show up as grayed items on the collection list. They
can be viewed, copied to another collection or annotated but not altered in
any other way.</li>
<li><img align="left" src="ManualImages/collectionSortBy.jpg" /><strong>Sort</strong>:
The sort option enables the user to
organize their collection in a more
desirable order. Select the sort criteria
from the dropdown box and click on the
sort button. This will save your
collection in the sorted order. Sort works for images (not mixed collections).</li>
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<li><img align="left" src="ManualImages/collectionPostItSample.jpg" /><strong>Images</strong>:
The image tiles contain the image and
options for annotating the image,
editing the title of the image or
displaying the single show feature
for the image. In addition, clicking on
the image will bring up the FSI Viewer
and hovering the mouse pointer over
the image tile will display an
informative Post it note pertaining to
that image.</li>
</ol>
</p>
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

	<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?php echo $config->domain; ?>About/Manual/characterCollections.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a>
</td>
</table>


		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	
