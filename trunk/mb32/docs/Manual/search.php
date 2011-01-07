<?php 
	//global $includeDirectory, $dataDirectory, $imgDirectory;
	
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>
	
		<div class="mainGenericContainer" width="100%">
		<!--change the header below -->
		<h1>Search</h1>
<div id=footerRibbon></div>
<br />			
The <strong>Search</strong> option in morphank is designed for users who desire a fast and
efficient method of information discovery. <strong>Search</strong> does not
require user login, however, logged-in users have varied tools accessible
within Search based on user privileges (e.g. collections, edit, annotate).
To search, users either enter a keyword to scan a predefined set of fields in
the:
<ul><li><strong>Simple Search</strong> option or 
</li>
<li><strong>Search Images by Keywords/Ids</strong> when a more refined search is desired.
</li>
</ul>
<br />
<img src="ManualImages/simple_search.png" />
<br />
<br />
<h3>Simple Search</h3>
<p>With the <strong>Simple Search</strong> option, the user enters a keyword(s). When <strong>Search</strong>
is selected, a search of the Taxon, Sex, Developmental Stage,
Form, Type Status, Specimen Part, View Angle, Imaging Technique, Image Id, and 
Contributor fields is performed. The following is a result of entering Carex (taxon), leaf (Specimen Part)
 & Mast (Contributor) in the Simple Search field as <strong>Carex leaf Mast</strong>.
</p>
<img src="ManualImages/simple_search_sample.png" />
<br />
<br />
<h3>Search Images by Keywords/Ids</h3>
<p>By selecting the <strong>Search Images by Keywords/Ids</strong> option, the user will be
presented with a resulting list of qualified images registered in the Morphbank
database. The user may enter Keywords or Morphbank ids in the fields. If a Morphbank id search is
preferred, click the <img src="../../style/webImages/selectIcon.png" /> icon to the right of the
field to be searched. From the resulting pop-up, choose the desired Morphbank object by clicking on the
<img src="ManualImages/select.gif" />
</p>
<p>Use this option when searching is desired on a specific group of images.
See a detailed explanation of this search type at 
<a href="<?echo $config->domain;?>About/Manual/browseImages.php#specialkeyword">Browse - Images: Specialized Keyword Search</a>
</p>
<img src="ManualImages/simple_search_keyword_id.jpg" />
<ul>
<li><strong>Taxon</strong> performs a search based on the taxonomic name or taxonomic
serial number (tsn) of the specimen.
</li>
<li><strong>Specimen</strong> performs a search based on the categories of specimen id, sex,
form, basis of record, type status, collector name, institution code, collection
code, catalog number and taxonomic name.
</li>
<li><strong>View</strong> performs a search based on the view id, imaging technique, imaging
preparation technique, part, angle, developmental stage, sex or form.
</li>
<li><strong>Locality</strong> performs a search based on the image's locality id, locality,
continent/ocean or country.
</li>
</ul>
			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/submit.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</div>
		<?php
//Finish with end of HTML	
finishHtml();
?>
