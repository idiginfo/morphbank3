<?php 
	//global $includeDirectory, $dataDirectory, $imgDirectory;
	
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>
	
		<div class="mainGenericContainer">
		<!--change the header below -->
		<h1>Submit: Add Publication</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
<p>Publications added to the Morphbank database may relate to any object in Morphbank including specimens, images, collections, annotations, views, localities, and taxon names. If adding a <strong>regular scientific name</strong> to the database, adding a corresponding publication is required. External Links may be added to a virtual copy of the paper.
</p>
<p>Path to <strong>Add Publication</strong>: <em>header menu</em><strong> > Tools > Submit > Publication</strong>
</p>

<img src="ManualImages/add_publication.png" alt="Add Publication Screen" hspace="20"  >

<p>
Data to be entered for the <strong>Add Publication</strong> screen varies 
with the type of publication selected. Note the required fields are indicated by a <font color="red">*</font>.
Fields that do not apply to a given publication type are grayed-out.</p>

<div class="specialtext3">Note: The person logged-in will be the name of the person that displays
in the "Contributor" field above. If a Submitter is entering data on behalf of a Contributor, select the 
Contributor's name from the drop-down.
</div>
<ul>
<li><strong>Publication Type</strong>: Choose the type of publication from the drop-down.
</li>
<li><strong>DOI<sup>&reg;</sup></strong>: a <em>digital identifier</em> for any object. If the 
publication being submitted has a <strong>DOI<sup>&reg;</sup></strong>, enter it here. For more 
information on <strong>DOI<sup>&reg;</sup></strong>'s go to <a href="http://www.doi.org/index.html" target="_blank">http://www.doi.org/index.html</a> </li>
<li><strong>Publication Title</strong>: Enter the name of the publication here. This field is case sensitive.</li>
<li><strong>Article Title</strong>: Enter the title of the article here as you wish it to appear in the database.</li>
<li><strong>Authors</strong>: Add Author names here, use the following [last name][first name initial][comma] format: Smythe J, Braun K </li>
<li><strong>Published in year</strong>: Enter year here</li>
<li><strong>Published in month</strong>:If applicable, enter month.</li>
<li><strong>Published on day</strong>: If applicable, enter day. </li>
<li><strong>Journal/Techreport number</strong>:</li>
<li><strong>Series</strong>:</li>
<li><strong>Organization</strong>:</li>
<li><strong>School</strong>:</li>
<li><strong>Pages</strong>:</li>
<li><strong>Chapter</strong>:</li>
<li><strong>Volume</strong>:</li>
<li><strong>Edition</strong>:</li>
<li><strong>Editor(s)</strong>:</li>
<li><strong>How published</strong>:</li>
<li><strong>Institution</strong>:</li>
<li><strong>Publisher</strong>:</li>
<li><strong>Publisher address</strong>:</li>
<li><strong>ISBN</strong>:</li>
<li><strong>ISSN</strong>:</li>
<li><strong>Comments</strong>:</li>
<li><strong>Contributor</strong>: Select the name of the contributor (person having the authorization to
release data and images to the public) from the dropdown list. The contributor can be
different from the submitter (person entering the data). If you need to add
new entries to this list, please contact <strong>mbadmin <font color="blue">at</font>
scs <font color="blue">dot</font> fsu <font color="blue">dot</font> edu</strong>.
</li>
</ul>
<p>Morphbank provides an option to add <strong>External Links</strong> to this record. For complete
instructions on providing links refer to <a href="<?echo $config->domain;?>About/Manual/externalLink.php">External Linking</a>
 in the <strong>Information Linking</strong> section of this manual.
</p>
<p>
When the <strong>Add Publication </strong>form has been completed, <strong>Submit</strong> to complete
the add publication process. A message will confirm that you have <em>successfully
added a publication</em>. From this point the user can continue to add additional
publications or click return (goes to the front page of Morphbank) or click on the desired destination using drop-downs in the <strong>Header Menu</strong>.
</p>

			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/edit.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	