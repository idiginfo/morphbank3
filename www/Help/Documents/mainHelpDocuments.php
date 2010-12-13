<?php

$mainHelpDocuments = '<br/> 
<ul class="helpDocumentList">
	<li>User Manual [<a href="../../About/Manual/" target="blank">full online reference</a>] 
	<li>UML database schema [<a href="'.$config->domain.'docs/mbUML.pdf">pdf</a>]</li>
	<li>Tables description [<a href="'.$config->domain.'docs/mbTablesDescription.pdf">pdf</a>]</li>
	<li type="0"> Delivered uploading 
		<ul>
		<li type="circle" > Morphbank Customizable Workbook [<a href="'.$config->domain.'docs/customWorkbook.xls">xls</a>]
		<p> This newest upload method utilizes a customizable Excel file. It is meant to be a more efficient upload method (for everyone). The Worksheets are easily tailored to the those desiring to use only a few Morphbank fields or to those wishing to use many of the Morphbank Fields and add <em>user-defined fields</em> for their data / images.</p>

<p>Suggestion: download and open this Workbook to look at while reading this documentation in the Online User Manual at <a href="' .$config->domain.'About/Manual/customWorkbook.php" target="_blank">Custom Workbook Help</a></p>
</li>
	<li type="circle" > Data Entry worksheet v3 Animalia [<a href="'.$config->domain.'docs/mb3a.xls">xls</a>]</li>
    <li type="circle" > Data Entry worksheet v3 Plantae [<a href="'.$config->domain.'docs/mb3p.xls">xls</a>]</li>
	            <li type="circle">Manual v3 [<a href="'.$config->domain.'docs/mbDataEntryManual255.pdf">pdf</a>]</li>
				
		<p>This Excel Workbook exists in two versions. The specimen parts and views for each version are tailored to the Animalia or Plantae kingdom. In all other respects the workbooks are the same. New features of this worksheet allow users to contribute new taxon names	to Morphbank (below the rank of Genus), associate publication data with taxon names, add Name Source information (Tropicos, IPNI, uBio, SBMNH, SCAMIT, World Spider Catalog, AMNH etc.), and add multiple blanket links to all specimens and images within the worksheet.</p>
		
		
		<li type="circle" > Data Entry worksheet v2.2[<a href="'.$config->domain.'docs/mbDataEntryv22.xls">xls</a>]</li>
		<li type="circle">Manual v2.2 [<a href="'.$config->domain.'docs/mbDataEntryManual.pdf">pdf</a>]</li>
                <p>Original version of the Excel worksheet. It allows users to enter specimens with existing taxon names in Morphbank.
                   <br/>Note: If the taxon names needed for upload of specimen data are not yet in Morphbank, AND if those names are below the rank of Genus, use one of the Excel Workbook versions 2.5.5 (Animalia or Plantae) provided above.</p>
		</ul>  
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
		<li>Posters and Presentations about morphbank for download.  [<a href="../Presentations/">browse</a>]</li>
</ul>';

// simply echos the following contents to the web browser.
// This helps keep the main scripts simpler to read.
function mainHelpDocuments() {
	
	
	global $mainHelpDocuments;
	
	echo '
			<div class="mainGenericContainer" style="width:700px">
					<h1>Morphbank Documents</h1>
					<img src="/style/webImages/blueHR-trans.png" width="525" height="5" style="margin-top:5px;" />'
				.$mainHelpDocuments.'
			</div>';
}

?>
