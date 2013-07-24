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
<h1>Edit Taxon Names</h1>
<div id=footerRibbon></div>

<!--<div class="specialtext2">
<a href="<?php echo $config->domain; ?>About/Manual/Movies/Edit_Specimen_Determination.avi" target='_blank'><img src="ManualImages/movieicon.jpg" /></a>  How to <strong>Edit Specimen Determination</strong> in Morphbank:<a href="<?php echo $config->domain; ?>About/Manual/Movies/Edit_Specimen_Determination.avi" target='_blank'>video</a>
</div>
-->
<div class="specialtext2">
There are restrictions to editing names in Morphbank. If you personally have added names to morphbank and they are
not published and have not yet been sent to ITIS, you may change them.  
<ul>
<li>you may only edit taxon names that you added to morphbank</li> 
<li>no one else has used that name since you added it to the system</li>
<li>you cannot change the parent if someone else added children</li>
<li>published manuscript names cannot be edited</li>
<li>once a name is published you cannot unpublish it</li>
<li>regular scientific names cannot be edited if they are under review by ITIS</li>
<li>to change a parent all of the children must be changed first</li>
<li>to change the rank all of the children must be changed first</li>
</ul>
</div>

<h3>Find and Edit a taxon name</h3>
<p>A user may <strong>Edit</strong> their contributed <strong>taxon names</strong> by:</p>
	<ol>
	<li>First, selecting the <strong>Group</strong> the user was logged into when the name was uploaded.
	<br />Select the <strong>Group</strong> from the <strong>Header Menu > Tools > Select Group > groups list</strong> or hover over <strong>Group</strong>
	at top left of Header to choose <strong>Group</strong> from a list.</li>

	<li>Going to <strong>Header Menu > Tools > My Manager > Taxa tab</strong>
	</li>

	<li>In the <strong>Keywords</strong> field, enter the name (or at least part of the name) to be edited. Click <img src="ManualImages/search.gif"
	alt="Search button"/>.
	</li>
	
    <li>If there are many hits, use <strong>Limit by</strong> in the left sidebar. Click <strong>Contributor,</strong>
	then <strong><img src="ManualImages/go.gif" alt="Go button">.</strong>
	<br /> 
	The new search results will be limited to names the logged-in user has uploaded to Morphbank.</li>

	<li>Find the name of interest in this new smaller set. Click the <img src="../../style/webImages/edit-trans.png" height="16" width="16" alt="edit icon"/> edit icon to
	open the <strong>Edit Taxon Name</strong> screen (see below). Screen opens in a new tab (default).
	</li>

	<li>Correct the fields that need updating, click 
	<img src="ManualImages/update_button.png" alt="Update button">.</li>
	</ol>

<img src="ManualImages/edit_taxon_name.jpg" hspace="20" vspace="20" />

<br />
<strong>Edit Taxon Name</strong> fields:
<ul>
<li><strong>Type of Name</strong>: you may not change this. A published Manuscript Name, however, may be <a href="#syn">synonymized</a> with a regular scientific name.</li>
<li><strong>Taxon Name</strong>: if the "Name status" field = <strong>do not publish yet</strong>, you can change the Taxon Name.
If you chose originally chose to publish this name, it may only be changed if no one has used it and it's not under ITIS review.</li>
<li><strong>Rank Identification</strong>: If the name is not published you can change the rank but, change all the children first. 
<li><strong>Parent Taxon Id/Name</strong>: If you are changing the parents in a lineage, change the children first and work upwards.
Click the <img src="../../style/webImages/selectIcon.png" alt="select check icon"/> check mark to choose the new parent from the
<strong>Taxon Name Search</strong> window.</li>
<li><strong>Name Source</strong>: This field tracks the name of the organization that holds the publication data for the name and/or
is responsible for contributing the name to the Morphbank database. Examples of values this field might contain include:
uBio, TROPICOS, IPNI, APNI, or a group like SBMNH, AMNH, World Spider Catalog, etc.</li>
<li><strong>Publication Id / Name</strong>: (Required by ITIS) If the publication data is already in Morphbank or needs to be
added to Morphbank, click the <img src="../../style/webImages/selectIcon.png" alt="select check"/> to open
a Publication window. 
	<ul>
	<li>The user can <img src="ManualImages/select.gif" alt="select check button" /> a publication already 
	there, or click "Add New" to add a Publication.
	</li>
	<li>The Publication Id will then auto-fill as well as the Taxon Author field.
	</li>
	<li>If the user knows the Morphbank Publication Id, they may enter it and the Taxon Author field will not auto-fill.
    </li>
	</ul>
</li>
<li><strong>Taxon Author(s), Year</strong>: Fix this as needed. Enter Authors separated by commas as in <strong>Smythe JL, Strong ES</strong></li>
<li><strong>Page(s)</strong>: Fix this as needed</li>
<li><strong>Contributor</strong>: Change this as needed. If a user is submitting publications on behalf of another Morphbank user, the submitter
may choose the contributor's name from the drop-down.</li>
<li><strong>Name status</strong>: When ready, change "do not publish yet" to "publish now." Unpublished names can be changed by 
the contributor and submitter but no other morphbank user can see or use these names. Once published, regular
scientific names are sent to ITIS for review and these names are public in the morphbank system. These names are
not editable. Published manuscript names are also visible, usuable by all and not editable.</li>
<li><strong>Vernacular names</strong>: You may add as many common names as you would like by clicking the <img src="../../style/webImages/plusIcon.png" />.</li>
</ul>


<a name="syn"></a>
<div class="specialtext3">
<b>How to synonymize Manuscript Names with Regular Scientific Names</b>
<br />When a published Manuscript Name is given a Regular Scientific Name you cannot change the original manuscript 
name in Morphbank.  However, you can synonymize these using Annotate Taxon Names.  To do this add the new name to 
Morphbank with Type of Name being 'regular scientific name' Then add an annotation to the manuscript name.
</div>

<p>Jump to 
<a href="<?php echo $config->domain; ?>About/Manual/addTaxonName.php">Add New Taxon Name</a> for more information about the fields for Add/Edit
 Taxon Name.  However, if you find other Taxon Name errors or wish to comment on 
names you cannot directly edit you may <a href="<?php echo $config->domain; ?>About/Manual/annotate_taxon_name.php">Annotate Taxon Names</a>.
</p>
			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?php echo $config->domain; ?>About/Manual/edit_publications.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
				</div>
		
			<?php
// Finish with end of HTML
finishHtml();
?>
	
