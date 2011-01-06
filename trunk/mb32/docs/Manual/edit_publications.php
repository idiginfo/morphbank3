<?php 
	//global $includeDirectory, $dataDirectory, $imgDirectory;
	global $domainName;
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>
	
		<div class="mainGenericContainer">
		<!--change the header below -->
		<h1>Edit Publication</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
<p>Publications added to the Morphbank database can be edited. A user may edit any publication
they have entered into the system. Any group coordinator or lead scientist may also edit publications
entered by anyone in their group. Publications may be related to any object
in Morphbank including specimens, images, collections, annotations, views, and localities. 
</p>

<div class="specialtext3">
Paths to <strong>Edit Publication</strong>
<br />
<br />
After <em>login</em> <strong>Header Menu > Tools > My Manager > Publications</strong>
<br />This path gives the user access to all publications they have entered. Use the <strong>Keyword Search</strong> and/or the <strong>Limit Search by</strong> feature to find specific
publications.
<br />
After l<em>ogin</em> <strong>Header Menu > Browse > Publication > Find Publication</strong> and Click</strong> <img src="../../style/webImages/edit-trans.png" height="16" width="16" alt="edit icon">
<br />From this path, the user can see all the publications in the Morphbank database and will search to find the specific one to edit.
</div>
Sample <strong>Edit Publication</strong> Screen
<br /> 
After <em>login</em>, then <strong>Header Menu > Tools > My Manager > Publications </strong> path:
<br />
<br />
<img src="ManualImages/edit_publication.png" alt="Edit Publication Screen">

<p>
Data that must be entered on the <strong>Edit Publication</strong> screen varies 
with the type of publication selected. Note the required fields are indicated by a <font color="red">*</font>.
Fields that do not apply to a given publication type are grayed-out.</p>

<p>If a user is logged-in, they may <strong>Browse - Publications</strong> to find the publication they wish to edit. Once
found, click on the <img src="../../style/webImages/edit-trans.png" height="16" width="16" alt="edit icon"> to open
the Edit Publication screen for this publication only.</p>
Sample <strong>Edit Publication</strong> Screen
<br /> from <strong>If logged-in> Browse> Publication> Find Publication and Click</strong> <img src="../../style/webImages/edit-trans.png" height="16" width="16" alt="edit icon">
<br />
<img src="ManualImages/browse_publication_to_edit.png" alt="Browse Publication Edit">

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
instructions on providing links refer to <a href="<?echo $domainName;?>About/Manual/externalLink.php">External Linking</a>
 in the <strong>Information Linking</strong> section of this manual.
</p>
<p>
When the <strong>Edit Publication </strong>form has been completed, <strong>Update</strong> to complete
the edit publication process. A message will confirm that you have <em>successfully
edited a publication</em>. From this point the user can continue to edit additional
publications or return to the <strong>Edit Publication</strong> screen.
</p>

			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $domainName;?>About/Manual/customWorkbook.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $domainName;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	