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
<h1>Annotate Taxon Names</h1>
<div id=footerRibbon></div>
<p>
For Taxon Names found in Morphbank, you may wish to comment, add/correct publication data or note an error. For names that you cannot directly edit you may <strong>Annotate Taxon Names</strong>.
 <div class="specialtext2">
<a href="<?php echo $config->domain; ?>About/Manual/Movies/AddTaxonNamenew.avi" target='_blank'><img src="ManualImages/movieicon.jpg" /></a>  How to <strong>Annotate </strong>a taxon name: <a href="<?php echo $config->domain; ?>About/Manual/Movies/AddTaxonNamenew.avi" target='_blank'>video</a>
</div>

<div class="specialtext2">
Access this feature of Morphbank directly from:
<br />
<em>header menu </em><strong>> Browse > Taxon ABC > find desired name > click <img src="../../style/webImages/annotate-trans.png" /> Annotate icon</strong>
<br />
<em>header menu </em><strong>> Browse > Taxon hierarchy > find desired name > click <img src="../../style/webImages/annotate-trans.png" /> icon </strong>
<br />
or <em>header menu </em><strong>> Tools > Submit > Taxon Name > Taxon Name Search > find desired name > click <img src="ManualImages/annotate_taxon_name_button.jpg" /> button </strong><br />
In addition, the <strong>Add Taxon Name Annotation</strong> screen may also be accessed from any other 
<img src="../../style/webImages/annotate-trans.png" /> icon. See 
<a href="<?php echo $config->domain; ?>About/Manual/annotationAdd.php#pathToAnnotation">Paths to Annotation</a>
</div>
Example of an <strong>Add Taxon Name Annotation</strong> screen.
<p>
<img src="ManualImages/add_taxon_name_annotation.jpg" />
<br />
<br />
<strong>Taxon Name Annotation Fields</strong>
<ul>
<li><strong>Annotation title</strong>: (Required)</li> 
<li><strong>Name should be</strong>: Put the desired name here</li>
<li><strong>Publication should be</strong>: If the publication is missing or 
incorrect, use the <img src="../../style/webImages/selectIcon.png" /> to access the Publication Table. Choose
the correct publication from this table or use the <img src="ManualImages/add_new_button.jpg" /> feature 
found in the same table to add the necessary publication.</li>
<li><strong>Taxon Author, Year should be</strong>: Add/correct this data here in the suggested format.</li>
<li><strong>Should be synonym of</strong>: Choose name by using the <img src="../../style/webImages/selectIcon.png" /> to
access the Taxon Name Search/Select table. With certain log-in permissions, new names (from Family downward)
 can be added to this table if necessary.</li>
<li><strong>Should be removed from synonymy</strong>: Click in the check box and enter a reason or reasons. </li>
<li><strong>Should be a child of</strong>: Choose parent by using the <img src="../../style/webImages/selectIcon.png" /> to
access the Taxon Name Search/Select table.</li>
<li><strong>Additional information</strong>: Enter more text here if needed to explain the annotation.</li>
</ul>
Click "Submit" to finish the Annotation. This annotation is published (viewable by all users of Morphbank) immediately
after clicking "Submit."


<div class="specialtext3">
<b>How to synonymize Manuscript Names with Regular Scientific Names</b>
<br />When a published Manuscript Name is given a Regular Scientific Name you cannot change the original manuscript
 name in Morphbank.  However, you can synonymize these using Annotate Taxon Names.  To do this add the new name to 
 Morphbank with Type of Name being 'regular scientific name,' then add an annotation to the manuscript name.
</p>
</div>
			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?php echo $config->domain; ?>About/Manual/myManagerPublications.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
				</div>
		
			<?php
// Finish with end of HTML
finishHtml();
?>
	
