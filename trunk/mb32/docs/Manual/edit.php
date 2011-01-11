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
		<h1>Edit</h1>
<div id=footerRibbon></div>
		<td width="100%">
			
<!--	<div class="specialtext2">
<p><a href="<?echo $config->domain;?>About/Manual/Movies/EditImage.avi" target='_blank'><img src="ManualImages/movieicon.jpg" /></a>  How to <strong>Edit images</strong> replacing one image with another: <a href="<?echo $config->domain;?>About/Manual/Movies/EditImage.avi" target='_blank'> video</a>
<br /><a href="<?echo $config->domain;?>About/Manual/Movies/Changepublishdate.avi" target='_blank'> <img src="ManualImages/movieicon.jpg" /></a>  How to <strong>Edit</strong> date to publish for <strong>lots of images: </strong><a href="<?echo $config->domain;?>About/Manual/Movies/Changepublishdate.avi" target='_blank'>video</a> 
<br /><a href="<?echo $config->domain;?>About/Manual/Movies/Edit_Specimen_Determination.avi" target='_blank'><img src="ManualImages/movieicon.jpg" /></a>  How to <strong>Edit Specimen Determination</strong> in Morphbank:<a href="<?echo $config->domain;?>About/Manual/Movies/Edit_Specimen_Determination.avi" target='_blank'>video</a></p>
</div>
-->

<h3>General Rules:</h3> 
<ul>
<li>You must be <strong>logged in</strong> to edit your records and you must have <strong>permission</strong> to edit those records.</li>
<li>Only contributors (aka Users), submitters and group coordinators have permission to edit their objects in Morphbank.</li>
<li>The presence of an <img src="../../style/webImages/delete-trans.png" alt="delete icon" /> means you have permission to delete the object.</li>
<!--<li>Only contributors, submitters of a record, group coordinators and lead scientists have permission to edit.</li>
-->
<li>Published records may not be edited, although there are exceptions. If a contributor or submitter finds a typo, the record may be edited.</li>
<li>If a contributor finds a problem with a published record, they may try to edit as well as contact <b>mbadmin <font color="blue">at</font> scs <font color="blue">dot</font> fsu <font color="blue">dot</font> edu</b> for help.</li>
<li>The <img src="../../style/webImages/edit-trans.png" alt="edit icon" /> (Edit icon) appears in each My Manager Tab, next to each object's thumbnail.</li>
<li>Click the <img src="../../style/webImages/edit-trans.png" /> icon to open the <strong>Edit screen</strong> for a particular object <em>in a new tab</em>.</li>

<!--<li>With the exception of the standard image field, the instructions for completing the other fields on this page can be found in 
<a href="<?echo $config->domain;?>About/Manual/uploadSubmitSpecimen.php">Add Specimen</a></li>.-->
</ul>

<p>This Edit manual page contains general instructions for how to edit most Morphbank Objects including:</p>
<ul>
<li>Image</li>
<li>Specimen</li>
<li>View</li>
<li>Locality</li>
<li>Taxa</li>
<li>Annotation</li>
<li>Publication</li>
<li>Collection</li>
</ul>

<h3>Knowing where to edit:</h3>
<ul>
<li>With the release of the My Manager user-interface, a user (a Morphbank Contributor) can edit their objects from any of the 
Tabs in My Manager. </li>
<li>The Submitter may also edit the objects they have submitted for a Morphbank Contributor.</li>
<li>Every object is associated with a Morphbank Group. Anyone in a particular group, with at least a lead scientist role, may also Edit objects
associated with that group.</li>
<li>If a Morphbank Contributor opens a single show using the <img src="../../style/webImages/infoIcon-trans.png" alt="info icon" /> for any of their objects (published or not), the <img src="../../style/webImages/edit-trans.png" /> icon is available inside the single show.</li>
	<ul>
	<li>For an <em>unpublished</em> Collection a user has created, they may <strong>Edit</strong> this Collection by finding and opening it in My Manager
	Collections from the <img src="<?echo $config->domain;?>/style/webImages/infoIcon-trans.png" /> icon.	</li>
	<li>From My Manager > Annotations, a user finds their <em>unpublished</em> Annotations and clicks on the 
	<img src="../../style/webImages/edit-trans.png" /> icon to open the Edit Annotation Screen. Go to 
	<a href="<?echo $config->domain;?>About/Manual/edit_annotations.php" target="_blank"><h3>Edit Annotations</h3></a> for details.	</li>
	</ul>
</ul>

<p><strong>Icons commonly found in Edit:</strong>
<br /><img src="../../style/webImages/selectIcon.png" /> Browse: To modify the record you must select from already existing records
<br /><img src="../../style/webImages/plusIcon.png" /> Add a record: You can add a new record by clicking on the plus</p>

<h3>Edit using My Manager:</h3>

<div class="specialtext2"> Every object uploaded by a given user can be edited from the appropriate tab in My Manager. Once in 
My Manager, a user selects the tab (Images, Views, Localities, Specimens, Publications, Collections, Annotations) of choice and then searches (and/or Limits) to find the object/s to be edited. Then, use the <img src="../../style/webImages/edit-trans.png" /> icon to open the Edit screen <em>in a new tab.</em></div>

<p>
At the moment, you may edit your Locality, Specimen, View, Image, Publication, Annotation, &amp; Collection records in several different ways,</p>
<ol>
<li>via the <img src="../../style/webImages/edit-trans.png" /> icon found at<strong> header menu > browse > choose tab</strong></li>
<li>via the <img src="../../style/webImages/edit-trans.png" /> icon found at <strong>header menu > tools > my manager > choose tab</strong> </li>
<li>or via the <img src="../../style/webImages/edit-trans.png" /> icon in any <strong>single show</strong>.</li>
</ol> 

<p>
For example: if you know a specific location record is incorrect, go to Browse > Locality, search for that record and click the edit icon. This icon will take you to the <strong>Edit Locality</strong> screen (<em>in a new tab</em>) where you can modify information about that specific location record.
<br />
<img src="ManualImages/edit_locality.jpg" vspace="5" /><br />
Also you can add or edit <a href="<?echo $config->domain;?>About/Manual/externalLink.php#addLink">External Links</a> from this window.  Once you have finished changing the form click the
<img src="ManualImages/update_button.png" /> button.  A small window will popup that requires a response.  
This window tells you how many records will be affected by the change you are about to make.  <em>Not to 
worry, you will not be able to change records for which you do not have permission</em>.
Selecting yes will send the user back to the <strong>Edit Locality</strong> screen where a
 confirmation message will explain that the update has been made. Selecting no or cancel will send you 
 back to the Edit Locality screen without making any changes.
 <br />
 <br />
<img src="ManualImages/edit_confirmation_message.jpg" />
<br />
<!--<h3>Edit using Edit Module:</h3>
<br />The Edit Module is best used when you have multiple records to edit in a row.  
<br /> You can access the Edit Module from the <b>Header Menu> Tools> Edit</b>
<br />
<br />
<img src="ManualImages/edit_from_tools.jpg" />

<br>As an example, to edit a View click View to open a page with access to all of your View records. 
The page numbers reflect the number of records that are available for edit.  
To find previously contributed records you can advance through 
the data using the arrows or go to a particular page number by typing it in and selecting the "Go" button. 
<br />
<br />
<img src="ManualImages/tool_edit_view.jpg" hspace="20" />
<br />
<br />-->
</p>
<h3>Edit Hints and Common Questions:</h3>
<p><b>Set a standard image for a Specimen or View:</b>
<br />From the Specimen Edit and View Edit you can set a standard image.
The standard image is the image that best represents your concept of the View or Specimen.
The default image is the first image you entered and if another image is desired as the standard display image, 
click on the <img src="../../style/webImages/selectIcon.png" /> and choose it from the list of images.</p>

<img src="ManualImages/choose_standard_image.jpg" vspace="20" hspace="20" />

<strong>Delete:</strong>
<ul>
<li>An entire image record cannot be deleted, but the image can be replaced if an incorrect image was uploaded.</li>
<li>You can replace an image if an incorrect image is uploaded.</li>
<li>Records <em>created after upload</em> can be deleted. In other words, Annoations and Collections made in Morphbank can be deleted (if they are not published).</li>
<li>Please contact <b>mbadmin <font color="blue">at</font> scs <font color="blue">dot</font> fsu <font color="blue">dot</font> edu</b>
 if you have any questions about deleting objects. You will see the 
 <img src="../../style/webImages/delete-trans.png" height="16" width="16" alt="delete icon" /> if you have delete privileges for a given object.</li>
</ul>

<strong>Adding and Editing Links:</strong>
<p>Links can be added to Specimen, Location and Image records. External Links and External Identifiers can be added to any object contributed. Click on the
 blue text <a href="<?echo $config->domain;?>About/Manual/externalLink.php#addLink">Add Links</a>
to open this feature. These links will appear on the single show for that record.  
Links also can be added to other objects in Morphbank!</p>

<img src="ManualImages/edit_add_links.png" vspace="10" />

<br />
<h3>Edit Images:</h3>
<br />From <strong>Edit Image</strong> users can:
<ul>
<li>change to a different Specimen Id or View Id</li>
<li>add a completely new Specimen record or View to associate with the selected image.</li>
<li>and if the image is <em>unpublished</em>, a user may replace the image.</li>
<li>Users cannot currently edit the existing Specimen record, View, or Locality data from the <strong>Edit Image screen</strong> page.
  <ul>
    <li>Go directly to My Manager or Browse, find the Specimen or View to be edited, click the <img src="../../style/webImages/edit-trans.png" /> icon next to the Specimen or View</strong> to change details of the data for those objects.</li>
  </ul>
</li>
</ul>


<br />
<a href="<?echo $config->domain;?>About/Manual/edit_taxon_name.php" target="_blank"><h3>Edit Taxon Names</h3></a>
<br />
<br />
<a href="<?echo $config->domain;?>About/Manual/edit_annotations.php" target="_blank"><h3>Edit Annotations</h3></a>
<br />
<br />
<a href="<?echo $config->domain;?>About/Manual/edit_collections.php" target="_blank"><h3>Edit Collections</h3></a>

			
			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/edit_annotations.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
</tr>				</div>
		
			<?php
// Finish with end of HTML
finishHtml();
?>
	
