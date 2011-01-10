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
	
		<div class="mainGenericContainer">
		<!--change the header below -->
		<h1>Preparing to Submit</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
<br />
Before submitting data into Morphbank, users must have available each of the following:
<ul>
	<li>Valid Morphbank username and password (obtained through the <a href="<?echo $config->domain;?>About/Manual/login.php">login
	screen</a> or by contacting <strong>mbadmin <font color="blue">at</font> scs <font color="blue">dot</font> fsu <font color="blue">dot</font>  edu</strong>).
	</li>
	
    <li>Have available information about the locality where the specimen was
	collected to include the place/country/province/region and the contributor's
	name.
	</li>
	
    <li>Information about the specimen and the parties involved in the collection
	of it to include basis of record, sex, form, developmental stage, type
	status, determination, collector(s) name, date collected, locality and the
	contributor's name.</li>
	
    <li>Information about the view of the specimen to include imaging technique,
	preparation technique, specimen part, sex, form, developmental stage,
	view angle, highest taxon to which this view is applicable and the
	contributor's name.</li>
	
    <li>Image file to be uploaded (bitmap [.bmp], joint photographic experts group
	[.jpeg, .jpg], tag information file format [.tiff]), Specimen to which the
	image belongs, its Morphbank View and the contributor's name.</li>
	
    <li>Have ready any URL information so that any object contributed to Morphbank can be linked to a page/s outside Morphbank. <a href="<?echo $config->domain;?>				About/Manual/externalLink.php" >External Links</a> can be added to any Morphbank object (Locality, Specimen, View, Image, Publication).
	</li>
</ul>			

<div class="specialtext3">Note: The fields with <font color="red">*</font> next to them are required, however, It is with great
emphasis that we suggest all applicable data fields be completed. This will
improve the reliability and accuracy of data searches. The above listed
information is only the minimum needed to successfully submit data into
Morphbank.</div>

<p>In addition to specimens, images, views, and localities, Morphbank users are encouraged to submit:</p>

<ul>
	<li><a href="<?echo $config->domain;?>About/Manual/uploadSubmitPublication.php" >Publications</a> that reference Morphbank objects.</li>
	<li><a href="<?echo $config->domain;?>About/Manual/addTaxonName.php" >Taxon Names</a> needed before submitting specimens and their associated images.
  		<ul>
    		<li>Two methods to check for presence or absence of taxon names in Morphbank:</li>
      			<ol>
        			<li><a href="<?echo $config->domain;?>/Help/nameMatch/">Name Query</a>: use this to check long lists of names.</li>
        			<li> or login to use <a href="<?echo $config->domain;?>Admin/TaxonSearch/">Taxon Search</a> for short lists.</li>
        		</ol>
    		<li>If <strong>determinations</strong> for a Contributor's specimens are at rank higher than Genus, the Morphbank Excel Workbook (option 2 below) cannot be used for 				             upload.</li>  
    	</ul>
    
	<li>A <a href="<?echo $config->domain;?>About/Manual/updateAccount.php" >Contributor Logo</a> and any associated URL for that Logo to be displayed with every object submitted by 		    this Morpbbank user.</li>
</ul>
	

<h2>Submit Options</h2>
<p>At this time, users have several options for submitting images &amp; associated data to Morphbank. Other options are being developed. Users may contribute to Morphbank via:
<ol>
<li><strong>Morphbank Web-Interface:</strong> This option suits users wishing to periodically contribute a relatively small number of images. In addition, this approach is useful for those where the associated data <em>is not</em> in a database already. This online user manual describes how to do this.</li>
<br />

<li><strong>Morphbank Excel Workbook:</strong> Users wishing to contribute 100 - 250 images at a time, may find this method useful. A multi-page Excel Workbook is populated by the Contributor. Morphbank staff uploads the completed workbook. If users need assistance to complete the workbook, Morphbank staff steps in to assist. This workbook and an accompanying manual are found at:
	<ul>
    <li><a href="<?echo $config->domain;?>docs/mb3p.xls">Morphbank Excel Workbook</a> - tailored with views for <strong>Plantae</strong> &amp; 
    <a href="<?echo $config->domain;?>docs/mb3wbmanual.pdf">Manual</a>    </li>
    <li><a href="<?echo $config->domain;?>docs/mb3a.xls">Morphbank Excel Workbook</a> - tailored with views for <strong>Animalia</strong> &amp; <a href="<?echo $config->domain;?>docs/mb3wbmanual.pdf">Manual</a>	</li>
    </ul>
 </li>
<br />

<li><strong>Morphbank Custom Workbook</strong> [<a href="/About/customWorkbook.xls">xls</a>]
		<p> This newest upload method utilizes a modifiable Excel file. It is meant to be a more efficient upload strategy (for everyone). The Worksheets are easily tailored to the those desiring to use only a few Morphbank fields or to those wishing to use many of the Morphbank Fields and add <em>user-defined fields</em> for their data / images. This option <strong>requires unique identifiers</strong> for the contributor's images and specimens. It is most suitable for those whose data is already <em>in a database.</em> If a contributor's data is not in a database, they must create these unique identifiers in order to use this upload option.</p>

<p>Suggestion: download and open this Workbook to look at while reading this documentation in the Online User Manual at <a href="/About/Manual/customWorkbook.php" target="_blank">Custom Workbook Help</a></p>
</li>
<br />

 <li><strong>Morphbank XML Upload:</strong> Users with data <em>in a database</em> and many images (&gt;500) are encouraged to use this option. This method is suitable when users plan to upload multiple large datasets and the accompanying images over time. Efforts are underway to automate this process. To use this option at-the-moment requires:
 	<ul>
    <li>Contributor-provided <strong>unique identifiers</strong>
    for images and specimens in the dataset.<li><strong>Mapping </strong>Contributor's database fields to Morphbank fields.</li>
    	<ul>
        <li>See Morphbank <a href="/About/mbTablesDescription.pdf">Table descriptions pdf</a> and</li> 
        <li><a href="/schema/mbsvc3.xsd">Morphbank XML Schema</a> to map Contribuor database fields to Morphank fields.</li>
        </ul>
    <li><strong>Checking &amp; Adding Taxon Names</strong> where necessary, to Morphbank, before upload of data &amp; images.</li>
    
    	<ul>
        <li><strong>Login and </strong>use <a href="/Admin/TaxonSearch/index.php?">Taxon Search</a> if checking, let's say, fewer than 20 names.</li>
        <li>Use <a href="/Help/nameMatch/">Name Query</a> to check a long list of taxon names and get a CSV file report of matches / non-matches.</li>
        <li>Names added to Morphbank via 1) web site, 2) <a href="/About/mb3a.xls">Excel Workbook</a>, 3) Taxon Upload form (contact <strong>mbadmin <font color="blue">at</font> scs 						         <font color="blue">dot</font> fsu <font color="blue">dot</font>  edu</strong>)</li>
        </ul>
    
    
    <li><strong>Contributor's Data in Morphbank XML Schema format</strong>
    <li><strong><em>Perhaps </em>Modifying the Morphbank XML Schema</strong> to accomodate the Contributor's dataset.</li>
    <li><strong>Upload of Data</strong></li>
    <li><strong>Images sent via FTP</strong> or hard drive</li>
    </ul>
  <strong>XML Expertise:</strong></li> 
 Those <em>would-be Contributors</em> facile with <strong>XML</strong> are encouraged to contact <strong>mbadmin <font color="blue">at</font> scs <font color="blue">dot</font> fsu <font color="blue">dot</font>  edu</strong>.
  Users must put their data into the Morphbank XML Schema format to use t this upload option.</li>
  <br />
  <br />
<li><strong>Morphbank via <a href="http://specifysoftware.org/">Specify6</a>:</strong> In progress, software is being designed and written to enable those with data in <a href="http://specifysoftware.org/">Specify6</a> to upload directly to Morphbank with the click of a button. While this method inserts data into Morphbank, software is also being written to automate the Image upload process.  </li>
  </ol>
	    <br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/uploadSubmit.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	
