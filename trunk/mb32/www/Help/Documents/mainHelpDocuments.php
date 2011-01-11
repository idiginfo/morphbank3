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

$mainHelpDocuments = '<br/> 
<ul class="helpDocumentList">
	<li>User Manual [<a href="'.$config->domain.'/About/Manual/" target="blank">full online reference</a>]</li> 
	<li>UML database schema [<a href="'.$config->domain.'docs/mbUML.pdf">pdf</a>]</li>
	<li>Tables description [<a href="'.$config->domain.'docs/mbTablesDescription.pdf">pdf</a>]</li>
	<li type="0"> Bulk upload options - [<a href="'.$config->domain.'About/HowToContribute/">Considerations for Choosing an Option</a>]</li>
	<br />
		<ol>
		<li><strong>Morphbank Excel Workbook v3</strong></li>
		<ul>
		<li type="circle"><strong>Manual v3</strong> [<a href="'.$config->domain.'docs/mb3wbmanual.pdf">pdf</a>]</li>
			<ul>
			<li type="circle" > <strong>Data Entry workbook v3 Animalia</strong> [<a href="'.$config->domain.'docs/mb3a.xls">xls</a>]</li>
    		<li type="circle" > <strong>Data Entry workbook v3 Plantae</strong> [<a href="'.$config->domain.'docs/mb3p.xls">xls</a>]</li>
	     	</ul>
		 </ul>   
	<p>This Excel Workbook exists in two versions. The specimen parts and views for each version are tailored to the Animalia or Plantae kingdom. In all other respects the workbooks are the same. New features of this worksheet allow users to contribute new taxon names to Morphbank (at the rank of Genus or lower), associate publication data with taxon names, add Name Source information (Tropicos, IPNI, uBio, SBMNH, SCAMIT, World Spider Catalog, AMNH etc.), and add multiple blanket links to all specimens and images within the worksheet. Note that tailoring this workbook for a particular group is simple to do.</p>
	
	<li><strong>Morphbank Custom Workbook</strong> [<a href="'.$config->domain.'docs/customWorkbook.xls">xls</a>]
		<p> This newest upload method utilizes a customizable Excel file. It is meant to be a more efficient upload method (for everyone). The Worksheets are easily tailored to the those desiring to use only a few Morphbank fields or to those wishing to use many of the Morphbank Fields and add <em>user-defined fields</em> for their data / images. Users <strong>must</strong> have unique identifiers for their images and specimens to use this option.</p>

<p>Suggestion: download and open this Workbook to look at while reading this documentation in the Online User Manual at <a href="' .$config->domain.'About/Manual/customWorkbook.php" target="_blank">Custom Workbook Help</a></p>
</li>
	
	<li><strong> Morphbank XML Upload</strong>
	<p>
	Users with data <em>in a database</em> and many images (>500) are encouraged to use this option. This method is suitable when users plan to upload multiple large datasets and the accompanying images over time. Efforts are underway to automate this process. To use this option at-the-moment requires:
 	<ul>
    <li><strong>Mapping </strong>Contributor database fields to Morphbank fields.</li>
		<ul>
        <li>See Morphbank <a href="'.$config->domain.'docs/mbTablesDescription.pdf">Table descriptions pdf</a> and</li> 
        <li><a href="'.$config->domain.'schema/mbsvc3.xsd">Morphbank XML Schema</a> to map Contribuor database fields to Morphank fields.</li>
        </ul>
	<li><strong>Checking &amp; Adding Taxon Names</strong> where necessary, to Morphbank, before upload of data &amp; images.</li>
		<ul>
        <li><strong>Login and </strong>use <a href="'.$config->domain.'Admin/TaxonSearch/index.php?">Taxon Search</a> if checking, let\'s say, fewer than 20 names.</li>
        <li>Use <a href="'.$config->domain.'Help/nameMatch/">Name Query</a> to check a long list of taxon names and get a CSV file report of matches / non-matches.</li>
        <li>Names added to Morphbank via 1) web site, 2) <a href="'.$config->domain.'docs/mb3a.xls">Excel Workbook</a>, 3) Taxon Upload form (contact <strong>mbadmin <font color="blue">at</font> scs <font color="blue">dot</font> fsu <font color="blue">dot</font>  edu</strong>)</li>
        </ul>
	<li><strong>Contributor Data in Morphbank XML Schema format</strong>
    <li><strong><em>Perhaps</em> Modifying the Morphbank XML Schema</strong> to accomodate the Contributor dataset.</li>
    <li><strong>Upload of Data</strong></li>

    <li><strong>Images sent via FTP</strong> or hard drive</li>
    </ul>
   <strong>XML Expertise:</strong> Those <em>would-be Contributors</em> facile with <strong>XML</strong> are encouraged to contact <strong>mbadmin <font color="blue">at</font> scs <font color="blue">dot</font> fsu <font color="blue">dot</font>  edu</strong>. Users must put their data into the Morphbank XML Schema format themselves to use this process.

	</p>
	</li>
	
	<li><strong>Morphbank via <a href="http://specifysoftware.org/">Specify6</a>:</strong> In progress, a software plug-in is being designed and written to enable those with data in <a href="http://specifysoftware.org/">Specify6</a> to upload directly to Morphbank with the click of a button. While this method inserts data into Morphbank, software is also being written to automate the Image upload process.
  </li>
	</ol>  
	</li>
	<li>User account application [<a href="'.$config->domain.'docs/mbUserAccountApp.pdf">pdf</a>]</li>
	<li>ENBI-Imaging Specimens [<a href="http://circa.gbif.net/Public/irc/enbi/comm/library?l=/enbi_reports/biological_specimens&vm=detailed&sb=Title" target="blank">Pdf Page</a>]
		<ul><li>
			Digital Imaging of Biological Type Specimens: A Manual of Best Practice. 2005.<br />
			Edited by C Hauser, A Steiner, J Holstein and Malcolm J Scoble.<br />
			Results from the study of the European Network for Biodiversity Information<br />
			(<a href="http://www.enbi.info/forums/enbi/index.php">ENBI</a>) full version available for download from the group collaboration<br />
			services of (<a href="http://circa.gbif.net/" target="blank">GBIF</a>).
			</li>
		</ul>
		<li>Posters and Presentations about morphbank for download.  [<a href="/docs/Presentations/">browse</a>]</li>
		</ul>';
?>