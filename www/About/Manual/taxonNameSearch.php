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
		<h1>Taxon Name Search</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
<p><strong>Taxon Name Search</strong> provides Morphbank Users an easy way to <strong>see</strong>, <strong>add</strong> or <strong>annotate </strong>taxonomic names in Morphbank. The parentage displays across the top of the page after a search. Other fields displayed with a given taxonomic name include: Taxon Number, Taxon Author, Common Name, Name Source, Usage, Reason (ITIS), Taxon Rank, Annotate Taxon Name.
</p>
<p>Users must be logged in to access <strong>Taxon Name Search</strong>.
</p>
<p>
Paths to <strong>Taxon Name Search</strong>
<ul>
<li>Browse > Taxon Search
</li>
<li>Tools > Submit > Taxon Name
</li>
<li>My Manager > Taxa tab > left side-bar <strong>Add new taxa</strong> button
</li>
</ul>
</p>
<p>
Enter a taxon name, or part of a taxon name and click <strong>Search</strong>.
</p>
<img src="ManualImages/taxon_name_search.png" alt="taxon name search screen" vspace="10" />
<div class="specialtext3">What does <strong>Taxon Name Search</strong> provide?
<ol>
<li>Use <strong>Taxon Name Search</strong> to browse the taxonomic names by clicking on any blue text to follow the taxonomic lineage.
</li>
<li>Enter a taxon name or part of a taxon name and click <strong>Search</strong> to see if the name or some variations of the name are in Morphbank.
</li>
<li>Once any Morphbank account holder reaches the taxonomic rank of <strong>sub-order</strong> or lower, they may <strong>Add a New Taxon Name</strong> to the database from this interface. Go to <a href="<?php echo $config->domain; ?>About/Manual/addTaxonName.php" >Add New Taxon Name</a> for instructions.
</li>
<li>At any taxomic rank, a Morphbank User may click the <img src="ManualImages/annotate_taxon_name_button.jpg" alt="annnotate" /> button to leave a comment about a particular Taxon Name.
</li>
</ol>
</div>

<br />

			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?php echo $config->domain; ?>About/Manual/submit.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	
