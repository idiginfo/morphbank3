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
		<h1>Edit Publication</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
<ul>
<li>Publications added to the Morphbank database can be edited.</li>
<li>A user may edit data for any publication they have entered into the system or submitted for some other contributor.</li>
<li>Any group coordinator or lead scientist may also edit publications entered by anyone in their group.</li>
<li>Publications may be related to any object in Morphbank including specimens, images, collections, annotations, views, and localities.</li>
</ul>

<div class="specialtext3">
Paths to <strong>Edit Publication</strong>
<ul>
<li>After <em>login</em> <strong>Header Menu > Tools > My Manager > Publications</strong>
<br />This path gives the user access to all publications they have entered. Use the <strong>Keyword Search</strong> and/or the <strong>Limit Search by</strong> feature to find specific
publications.
</li>
<li>
After l<em>ogin</em> <strong>Header Menu > Browse > Publication > keyword search for publication</strong> and Click</strong> <img src="../../style/webImages/edit-trans.png" height="16" width="16" alt="edit icon">
<br />From this path, the user can see all the publications in the Morphbank database and will search to find the specific one to edit.
</li>
</ul>
</div>

Sample <strong>Edit Publication</strong> Screen
<br /> 
After <em>login</em>, then <strong>Header Menu > Tools > My Manager > Publications > keyword search > click Edit Icon</strong> path:
<br />
<br />
<img src="ManualImages/edit_publication.png" alt="Edit Publication Screen" hspace="20">

<p>
Data that must be entered on the <strong>Edit Publication</strong> screen varies 
with the type of publication selected. Note the required fields are indicated by a <font color="red">*</font>.</p>

<p>If a user is logged-in, they may <strong>Browse - Publications</strong> to find the publication they wish to edit. Once
found, click on the <img src="../../style/webImages/edit-trans.png" height="16" width="16" alt="edit icon"> to open
the Edit Publication screen for this publication only.</p>
Sample <strong>Edit Publication</strong> Screen
<br /> from <strong>If logged-in> Browse> Publication> Find Publication and Click</strong> <img src="../../style/webImages/edit-trans.png" height="16" width="16" alt="edit icon">
<br />
<img src="ManualImages/browse_publication_to_edit.png" alt="Browse Publication Edit" hspace="20">

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
<p>Morphbank provides an option to add <strong>External Links</strong> and / or External Unique Identifiers to this record. For complete
instructions on providing links refer to <a href="<?echo $config->domain;?>About/Manual/externalLink.php">External Linking</a>
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
<td><a href="<?echo $config->domain;?>About/Manual/customWorkbook.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	
