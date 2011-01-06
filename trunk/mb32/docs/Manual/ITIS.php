<?php 
	//global $includeDirectory, $dataDirectory, $imgDirectory;
	global $domainName;
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>

	
		<div class="mainGenericContainer" width="100%">
		<!--change the header below -->
		<h1>Integrated Taxonomic Information System (ITIS)</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
			<td width="100%">
		
<p>The <a href="http://www.itis.gov/" target="_blank">Integrated Taxonomic Information System (ITIS)</a> 
database maintained by the <a href="http://www.usda.gov/wps/portal/usdahome" target="_blank">United 
States Department of Agriculture (USDA)</a>. ITIS was selected as the taxonomic name
server for Morphbank in 2004 because it represented the most complete comprehensive
taxonomic name service available at the time. Also, the entire database could be
downloaded locally making access to the data quick and efficient.
ITIS is a consistent service. It has a high level of stability and a rigid review system.
Since ITIS is maintained by the USDA, the probability that the service will be persistent
for several years is high. Taxonomic names are entered into the system and panel of
experts periodically review the names for quality assurance.
</p>
<div class="specialtext2">
<img align="center" src="ManualImages/ITIS%20copy.jpg">
</div>
The Morphbank development team recognized early in the development of the
system, the need for a Taxonomic Name Server that would supply the scientific
names needed in determination of species. However, there were none available
that contained all of the recognized names. ITIS was chosen because it
<ol>
<li>contained the most complete set of names at the time,
</li>
<li>has a formal method for adding new names and
</li> 
<li>because the system is supported by the USDA ensuring the longevity of the system.
</li>
</ol>
Because the addition of new names to the ITIS system does take some time, the Morphbank team
established a method to <a href="<?echo $domainName;?>About/Manual/addTaxonName.php" target="_blank">add names</a> to Morphbank. 
This allows scientists use the taxonomic name of their choice to identify a specimen in Morphbank. Names added to Morphbank have 9-digit Morphbank <em>taxonomic serial numbers</em> beginning with 999. <br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $domainName;?>About/Manual/addTaxonName.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $domainName;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</div>
		
			<?php
//Finish with end of HTML	
finishHtml();
?>	
