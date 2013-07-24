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
		<h1>What is a Collection?</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
<p>This section of the manual explains what a collection is and what user can do with a collection. A collection is a group of any objects in the Morphbank database assembled by members for the purpose of viewing 
and/or manipulating (e.g. rearranging the order, editing, and/or annotating, etc.) and
storing the collected objects for future use.
</p>
<div class="specialtext2">For instructions on
 how to create a collection in Morphbank go to: <a href="<?php echo $config->domain; ?>About/Manual/myManagerCollectionsCreate.php"><h3> How to Make a Collection</h3></a></p>
</div>

<div class="specialtext3">Currently, there are 3 types of Morphbank Collections: 
<ul>
<li>
a Collection of Images (and / or other objects - a mixed collection), 
</li>
<li>
a Character Collection (composed of images & character states) created to illustrate Character States for a defined Character , 
</li>
<li>
and an OTU Collection (consisting of Specimens and Taxon Names) -- designed to help a user describe operational taxonomic units (OTU)s.
</li>
</ul>
One can also create a collection of collections, which is very useful when putting links in publications to objects in Morphbank. 
</div>

<h3>Collections:</h3>
<ul>
<li>must have at least one image/object. Deleting the last image
will leave an empty collection. Do not delete it.</li>
<li>have an order based on the owner's criteria. The initial order will
correspond to the order the objects were initially selected.</li>
<li>are identified by a unique Morphbank id which contributors can use to link to the collection from a published paper or web site.</li>
<li>are published (viewable to the world) when released by the
creator (default 6 months if not otherwise notified).</li>
<li>may be deleted by the owner, if desired, as long as it is not past the <strong>date-to-publish</strong> for the collection.
</li>
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
<h3>Managing collections:</h3> 
<ul>
<li>Image Collections &amp; Character Collections are found in the
 <a href="<?php echo $config->domain; ?>About/Manual/myManagerCollections.php" >Collections tab</a>
of the <strong>My Manager</strong> interface of Morphbank.
</li>
<li>The Collections tab offers the user a list of all public collections and any they've created (public or private). The user can view other collections there and in addition, modify any of their own unpublished collections. There is no limit on the number of collections a user may have. 
</li>
<li>Any <strong>OTU Collection</strong> created is found in the <a href="<?php echo $config->domain; ?>About/Manual/myManagerTaxa.php"><strong>My Manager Taxa tab</strong></a>.
</li>
<li>New collections are created or copied through any of the <strong>My Manager</strong> tabs.
</li>
<li>Access all collections owned
by other users in Morphbank by clicking the <strong>My Manager - Collections tab</strong> or from the path: <em>header menu > Browse > Collections</em> which opens the <strong>My Manager - Collections tab</strong>.
</li>
<li>The
<strong>Collections tab</strong> of the <strong>My Manager</strong> interface allows users to keep track of their
personal collections and is directly accessed from the Header Menu > Browse (drop-down to My Manager choices) > click Collections.
</li>
</ul>

<br />		
	<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?php echo $config->domain; ?>About/Manual/myManagerCollectionsCreate.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a>
</td>
</table>


		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	
