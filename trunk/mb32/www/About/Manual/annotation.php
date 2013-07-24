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
		<h1>What is an Annotation?</h1>
<div id=footerRibbon></div>
<p>
Annotation allows users to add additional information to objects in the
Morphbank relational database. An annotation is a comment about an object
(could be an image, collection, or taxon name) that is stored separately from the object itself.
Annotations are identified in Morphbank by a unique internal id.
</p>
Two Icons are used for Annotations in Morphbank. The 
<img src="../../style/webImages/annotate-trans.png" height="16" width="16" alt="annotate icon" /> indicates
the user may Annotate this object. The <img src="../../style/webImages/annotateIcon.png" alt="alternate annotate icon" />
lets the user know Annotations exist for this object. Click the <img src="../../style/webImages/annotateIcon.png" alt="alternate annotate icon" />
to view the Annotations. When using <strong>My Manager</strong>, each tab displays the objects in that Morphbank Module and
any Annotations present are also noted there by a link as -- <strong>No. Annotations: (some number)</strong>.

<p>The created annotations are published (viewable to the world) when released by
the creator (default 6 months if not otherwise notified).</p>
<div class="specialtext3">Note: Currently, only images, specimens and taxon names have annotation options but in
future versions, users will be able to annotate any Morphbank object ( i.e.
image, specimen, locality, view, publication, annotation, character, etc).</div>
<h2>Guidelines for working with annotations</h2>:
<p>A user may have multiple annotations that will be identified by a title on the
screen. Since the annotation will have a unique internal identifier, the name may
be duplicated but is not recommended. (When making mass annotations all will
have the same initial title in <a href="<?php echo $config->domain; ?>About/Manual/myManagerAnnotations.php" target="_blank"><strong>My Manager > Annotations tab</strong></a>).</p>

<p>Any logged-in user can annotate any image, any set of images in a collection or taxon name that is released. Any
logged-in user can annotate any image or collection (of images) that has not been released
provided they belong to the group who owns the image or image collection. With the release of the latest (Beta) version of 
Morphbank 2.7, Annotations are managed from the Header Menu > Tools > My Manager > Annotations tab interface.</p>
<p>
<strong>Unpublished owned annotations</strong>
<br />
A user may:
<ul>
<li>edit the makeup of their own unpublished annotations.
</li>
<li>delete an unpublished, owned annotation.
</li>
</ul>
</p>

<p>
<strong>Unpublished annotations owned by other users</strong>
<br />
A user may:
<ul>
<li>browse unpublished annotations of other users within groups
to which he/she belongs.
</li>
<li>view unpublished annotations of other users within groups to
which he/she belongs.
</li>
</ul>
</p>
<p>
<strong>Published annotations</strong>:
<ul>
<li>cannot be edited.
</li>
<li>are viewable to the world.
</li>
</ul>
</p>

<p>
<strong>The user's group/user's annotation relationship</strong>:
<ul>
<li>The user's annotation will be shared with a group in Morphbank. The user
must declare which group they belong before they create the annotation
(declared through Select Group in the login process) and that annotation
is shared with the declared group.
</li>
<li>The annotation will be immediately viewable to all users in that group (The
annotation cannot be accessed by the world until it is published).
</li>
<li>Although the owner may edit their own unpublished annotation, other
members of the group may not.
</li>
</ul></p>
		<h1>Types of Annotations</h1>
<div id=footerRibbon></div>
<ul>
<li><h3>Determination</h3>:  This is the most complex of the annotation types and is
designed to offer biologists the ability to remotely collaborate on the
determination (assignment of a taxonomic name); and to offer the ability to
supply additional details concerning the taxonomic name associated with a
specimen. When <strong>Determination</strong> is selected as the annotation type, additional
field options will be available:
<ol>
<li>Determination annotation will give users the ability to view and respond to
a list of determination annotations that are related to the current object.</li>
<li>Users can choose to comment on the previous determinations, select a
new taxonomic name from the ITIS database, or add a new taxon.</li>
<li>Users are required to provide Morphbank with the source of the
identification (defaults to the name of the logged-in user) and resources
used in making this determination annotation.</li>
</ol>
An annotation title, comments and date to publish are the remaining required
fields in this option. (Details for this annotation type are located in the <a href="<?php echo $config->domain; ?>About/Manual/annotationAdd.php">Add
Annotations</a> documentation).
</li>
<div class="specialtext2">Note: Even though the image was selected for annotation, it is really
the associated specimen that is linked to the determination
annotation. For example, if two users create a determination
annotation using two different images from the same specimen, when
the determination annotations are viewed for that specimen, both will
be seen as related annotations. If a determination annotation is
written for a collection of images there will be an identical
determination annotation record written for each specimen in the
collection.</div>
<li><h3>General</h3>: This annotation type is used to add general comments about an
image or collection of images. The required fields in this option include an
annotation title, general comments and date to publish (The publish date
defaults to 6 months from the date the collection was established). (Details
for this annotation type are located in the <a href="<?php echo $config->domain; ?>About/Manual/annotationAdd.php">Add Annotations</a> 
documentation).</li>
<li><h3>Legacy</h3>: General and legacy annotations differ only in the source of the
annotation. Data in a legacy annotation was previously generated and
stored elsewhere prior to the inclusion in Morphbank. As in a general
annotation, a legacy annotation is used to add general comments about an
image or collection of images. The required fields in this option include an
annotation title, general comments and date to publish (The publish date
defaults to 6 months from the date the collection was established). (Details
for this annotation type are located in the <a href="<?php echo $config->domain; ?>About/Manual/annotationAdd.php">Add Annotations</a> 
documentation).</li>
<li><h3>XML</h3>: This option allows the user to upload an XML document into the
Morphbank database and use it as a general annotation. All other fields
match the general and legacy annotations. The required fields include an
annotation title, general comments and date to publish (The publish date
defaults to 6 months from the date the collection was established). The
XML document is limited in size to 64K. (Details for this annotation type are
located in the <a href="<?php echo $config->domain; ?>About/Manual/annotationAdd.php">Add Annotations</a> documentation).</li>
<li><h3>Taxon Name</h3>: This option gives the Morphbank user the ability to leave comments about
any published Taxon Name in Morphbank. The comments will be visible to anyone looking at these names. This type
of annotation is <em>published immediately</em> upon clicking "Submit." Details for this annotation type can be found by jumping to
<a href="<?php echo $config->domain; ?>About/Manual/annotate_taxon_name.php">Annotate Taxon Names</a>.
</li>
<li>Mass Annotations</li>
</ul>
			
			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?php echo $config->domain; ?>About/Manual/annotationAdd.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	
