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
		<h1>Collections - Defined</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
<p>This section of the manual explains what a user can do with a collection. For instructions on
 how to create a collection in Morphbank go to
 <br />
<a href="<?php echo $config->domain; ?>About/Manual/browseImages.php#createCollection"><h3>Add Images to a Collection</h3></a></p>

<p>
A collection is a group of any objects in the Morphbank database assembled by members for the purpose of viewing 
and/or manipulating (e.g. rearranging the order, editing, and/or annotating, etc.) and
storing the collected objects for future use.
</p>

<div class="specialtext3">Note: In this newest (beta) version of Morphbank a collection can now include images and any
other objects such as: annotations, localities, publications, specimens, or views.</div>

<h3>Collections:</h3>
<ul>
<li>must have at least one image/object. Deleting the last image
will leave an empty collection. Do not delete it.</li>
<li>have an order based on the owner's criteria. The initial order will
correspond to the order the objects were initially selected.</li>
<li>are identified by a unique internal id.</li>
<li>are published (viewable to the world) when released by the
creator (default 6 months if not otherwise notified).</li>
</ul>
<p><h3>Guidelines for working with collections:</h3> A User may have
multiple collections that will be identified by a name on the screen. Since the
collection will have a unique internal identifier, the name may be duplicated
but is not recommended.
</p>
<p><h3>Unpublished owned collections:</h3>
<ul>
<li>A user may alter the makeup of their own unpublished collection by
adding or deleting images or other objects.
</li>
<li>An image or other Morphbank object can be added to a user's unpublished established collection.</li>
<div class="specialtext3">Note: There are no restrictions as to the number of objects in a
collection. However, due to speed considerations, the user should
exercise caution not to exceed 100 high resolution images per
collection.</div>
<li>A user may delete one or more images/objects (or an entire collection) from an
unpublished, owned collection.</li>
<li>A user may change the order of the images/objects in their own unpublished
collections.</li>
<li>A collection owner may move an image/object from one unpublished
collection to another owned, unpublished collection.</li>
<li>An owner of an unpublished collection may annotate that collection.</li>
</ul>
</p>
<p><h3>Unpublished collections owned by other users:</h3><br />
A user may...
<ul>
<li>browse and view unpublished collections of other users
within groups to which he/she belongs.</li>
<li>make a copy of an unpublished collection of another user
provided they belong to the same group.</li>
<li>copy an image from any collection to another unpublished,
owned collection</li>
<li>annotate an unpublished collection owned by another
member in the group.</li>
</ul>
</p>

<p><h3>Published collections:</h3>
<ul>
<li>A user may make a copy of any published collection. (This copy then
becomes an unpublished collection owned by the user and group who
created it.)</li>
<li>A published collection cannot be edited by anyone but may be
annotated.</li>
</ul>
</p>

<p><h3>The user's group/user's collection relationship:</h3>
<ul>
<li>The user's collection will be shared with a group in Morphbank. The
user must declare which group they belong to before they create the
collection and that collection is shared with the declared group.</li>
<li>The collection will be immediately viewable to all users in that group.
(The collection cannot be accessed by the world until it is published).</li>
<li>Although the owner may alter their own collection, other members of
the group may not (but they may annotate it)</li>
<li>Other members of the group may make a copy of another user's
collection and thus create their own personal copy.</li>
</ul>
</p>
<p><h3>Managing collections:</h3> The <strong>Collection Manager</strong> has been replaced
with the 
<a href="<?php echo $config->domain; ?>About/Manual/myManagerCollections.php" target="_blank">Collections tab</a>
in the new <strong>My Manager</strong> interface of Morphbank.
The Collections tab offers the user a list of all public collections and any they've created (public or private).
The user can view other collections there and in addition, modify any of their own collections.
</p><br>
<br />
<img src="ManualImages/tools_select_group.jpg" hspace="20" />
<p>There is no limit on the number of collections a user may have.
</p>
<p>
New collections are created or copied through any of the <strong>My Manager</strong> tabs, <strong>Browse</strong> pages, <strong>Browse -
Collections</strong> or through the results of a <strong>Search</strong>. Access all collections owned
by other users in Morphbank thru the <strong>My Manager - Collections tab</strong> or <strong>Browse - Collections</strong> screen. The
<strong>Collections tab</strong> of the <strong>My Manager</strong> interface allows users to keep track of their
personal collections and is directly accessed from the Header Menu > Tools > My Manager.
</p>


<p>
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
Offers the user a list of all the collections that have been created under the
current username and group. The current collection that is being displayed
is highlighted dark grey. The collections which are light grey are published
collections. The red number in brackets corresponds to the number of
images in the collection. Clicking on a collection will display the collection
contents and saved order. To access other collections under the same
username but created under another authorized group, return to the
<strong>Select Group</strong> screen and login under that group. There is no limit on the
number of collections a user may have.</li>
<li><strong>Check buttons</strong>:
These buttons are used to check or uncheck an entire collection of
images.</li>
<li><strong>Number of images</strong>:
This number reflects the current number of images in the collection.</li>
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
collection in the sorted order.</li>
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
<td><a href="<?php echo $config->domain; ?>About/Manual/characterCollection.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a>
</td>
</table>


		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	
