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
