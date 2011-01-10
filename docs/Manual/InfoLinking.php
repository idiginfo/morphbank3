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

	
		<div class="mainGenericContainer" width="100%">
		<!--change the header below -->
		<h1>Information Linking</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
			<td width="100%">

<br />
<h3>URLs from Morphbank - External Links</h3>
<p>Add Uniform Resource Locators (URLs) easily to any Morphbank Object (image, specimen, view, locality, publication, taxon name). When a user
uploads data and images to Morphbank, each record uploaded can have one or more urls linking the Morphbank Object to a site outside Morphbank.
These are in the http:// format and can be edited by the Morphbank Contributor as needed by clicking the 
<img src="<?echo $config->domain;?>style/webImages/edit-trans.png" alt="Edit icon" /> Edit icon for a given object.
</p>          

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<h3>External Link Samples</h3>
<div class="specialtext2">
<table cellpadding="2" cellspacing="2" border="1" hspace="30" >
<tr><th align="left">Label Morphbank User Clicks</th><th align="left">Label Points to this user-supplied URL</th><th align="left">See these URLs in Morphbank</th></tr>
<tr><td>Teleost Anatomy Ontology TAO:0001250</td><td>http://bioportal.bioontology.org/virtual/1110/TAO:0001250</td><td><a href="<?echo $config->domain;?>?id=478760">http://www.morphbank.net/?id=478760</a></td></tr>
<tr><td>Handbook of Nearctic Chalcidoidea</td><td>http://codex.begoniasociety.org/chalcidkey/</td><td><a href="<?echo $config->domain;?>?id=228786">http://www.morphbank.net/?id=228786</a></td></tr>
<tr><td>28S</td><td>http://www.ncbi.nlm.nih.gov/entrez/viewer.fcgi?db=nucleotide&amp;val=AY675671</td><td><a href="<?echo $config->domain;?>?id=80200">http://www.morphbank.net/?id=80200</a></td></tr>
</table>
</div>
           
<p>These URLs can be added at the time the object is created or added later during an edit session. Example: Users may add an
image to the database and point to a museum web site where the original specimen is located. The museum URL is added as an external link to the image
record. 
</p>

<p> The Add Image (Specimen, View, Locality, Publication and Taxon Name) forms all have this option that appears as:
</p>
<img src="<?echo $config->domain;?>About/Manual/ManualImages/add_externallinksref_sample.png" hspace="20"/>
<br />
<br />
<h3>URLs to Morphbank - Internal Links</h3>
<p>All Morphbank Objects (image, specimen, view, locality, publication, taxon name) have unique identifiers - numbers that are unique within Morphbank.
From anywhere outside Morphbank (from a journal article, a web site, a conference or workshop paper), a Morphbank Contributor creates a URL back <em>into</em> Morphbank using
the base URL (http://morphbank.net/) + the unique identifier for a specific object.</p>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<h3>Internal Link Samples</h3>
<div class="specialtext2">
<table cellpadding="2" cellspacing="2" border="1" hspace="30">
<tr><th align="left">Base URL</th><th align="left">Morphbank Object Id</th><th align="left">Actual Internal Link to Morphbank</th><th align="left">What the Link Returns</th></tr>
<tr><td>http://morphbank.net</td><td align="center">478760</td><td><a href="<?echo $config->domain;?>?id=478760">http://morphbank.net/?id=478760</a></td><td>Morphbank Image Metadata + 400px image</td></tr>
<tr><td>http://morphbank.net</td><td align="center">228716</td><td><a href="<?echo $config->domain;?>?id=228716">http://morphbank.net/?id=228716</a></td><td>Morphbank Specimen Metadata + 400px image</td></tr>
<tr><td>http://morphbank.net</td><td align="center">575098</td><td><a href="<?echo $config->domain;?>?id=575098">http://morphbank.net/?id=575098</a></td><td>Morphbank Collection</td></tr>
<tr><td>http://morphbank.net</td><td align="center">464413</td><td><a href="<?echo $config->domain;?>?id=464413">http://morphbank.net/?id=464413</a></td><td>Morphbank Annotation</td></tr>
<tr><td>http://morphbank.net</td><td align="center">579435</td><td><a href="<?echo $config->domain;?>?id=579435">http://morphbank.net/?id=579435</a></td><td>Morphbank Image Metadata + 400px image</td></tr>
<tr><td>http://morphbank.net</td><td align="center">579435</td><td><a href="<?echo $config->domain;?>?id=579435&amp;imgType=jpeg">http://morphbank.net/?id=579435&amp;imgType=jpeg</a></td><td>Morphbank Image jpeg</td></tr>
<tr><td>http://morphbank.net</td><td align="center">579435</td><td><a href="<?echo $config->domain;?>?id=579435&amp;imgType=thumb">http://morphbank.net/?id=579435&amp;imgType=thumb</a></td><td>Morphbank thumbnail</td></tr>
<tr><td>http://morphbank.net</td><td align="center">579435</td><td><a href="<?echo $config->domain;?>?id=579435&amp;imgType=jpeg&amp;imgSize=500">http://morphbank.net/?id=579435&amp;imgType=jpeg&amp;imgSize=500</a></td><td>Morphbank Image with user-specified width</td></tr>
</table>
</div>

<p>Contributors may link back to (reference) the <strong>metadata</strong> for any object in Morphbank, using the Morphbank unique id for that object.
 -- The Morphbank <a href="<?echo $config->domain;?>About/Manual/show.php" target="_blank">Show</a> feature was created to gather and present the associated metadata for 
 a given Morphbank record in one window. The Show pages provide easy, direct access via Uniform Resource Locators (URLs) to data objects in Morphbank that
may be used by other data repositories. Example: scientists may cite the Morphbank web site and display images and data in journal,
 conference, and workshop research papers. A person outside of Morphbank may then use that referenced URL as a direct link back to the
  data or image located in the Morphbank database. In other words, the Morphbank web address plus the Morphbank id create this link.</p>

<p>In addition to creating an http:// address to link to a Morphbank Show page, other types of http:// links to specific images are possible. For example, a user may wish to point someone to the 
image directly, not the metadata. For more about the types of Internal Links (links back into Morphbank from outside), ...

<div class="specialtext3">
See <a href="<?echo $config->domain;?>About/Manual/externalLink.php" target="_blank"><b>External Linking - Internal Linking</b>
</a>for details on how to create Internal or External Morphbank links.
</div>

<h3>Getting Morphbank Ids to Build URLs</h3>
<p>Via <a href="http://services.morphbank.net/mb3/">Services</a>, users retrieve Morphbank Ids for various objects. Using these ids, Contributors create urls and may also build other websites that utilize web services, pulling images via their ids. See <a href="<?echo $config->domain;?>About/Manual/services.php">Morphbank Web Services</a> in this online user manual for more details.</p>
<br />
<br />		
			<div id=footerRibbon></div>
			<table align="right">
<td>
<td><a href="<?echo $config->domain;?>About/Manual/externalLink.php" class="button smallButton"><div>Next</DIV></a>
<a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a>
		</td>
			</table>
			</div>
		
			<?php
//Finish with end of HTML	
finishHtml();
?>	
	
