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

	
	//include_once('/panfs/panasas1/users/dpaul/www/includes/head.inc.php');
	include_once('head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>
	
		<div class="mainGenericContainer">
		<!--change the header below -->
		<h1>Welcome to the Morphbank Users Manual</h1>
		<div id=footerRibbon></div>
<p>This online manual focuses on:</p> 
<ul>
<li>detailed directions for navigating and populating the Morphbank online database via the web.</li> 
<li>considerations and instructions for options to <em>bulk / mass upload data and images.</em></li>
	<ul>
    <li>There are links to instructions for uploading images via Excel, XML or Specify (in alpha-testing) at the  <a href="<?echo $config->domain;?>About/Manual/submit.php">Preparing to Submit</a> manual page.</li>
	</ul>
<li>morphbank general topics, like copyright, image philosophy and system requirements.</li>
</ul>

<p>Last update of this documentation was made 
  <!-- #BeginDate format:Am1 -->November 10, 2010<!-- #EndDate -->
reflecting the latest software release of Morphbank v 3.</p>
<!-- <div class="specialtext2">
		<b>Quick videos:</b>
		These videos (AVI) are being updated to reflect the latest changes to Morphbank software. New videos are added as they are made.
		<ul>
<li>How to <strong>Publish multiple collections</strong> from the MyManager: <a href="<?echo $config->domain;?>About/Manual/Movies/Publishcollection.avi" target='_blank'>video</a>
</li>
<li>How to <strong>Create a Collection</strong> from the MyManager: <a href="<?echo $config->domain;?>About/Manual/Movies/Makecollection.avi" target='_blank'>video</a>
</li>		
<li>How to <strong>Submit a Specimen: </strong><a href="<?echo $config->domain;?>About/Manual/Movies/SubmitSpecimen.avi" target='_blank'>video</a>
</li>
<li>How to <strong>Submit a View: </strong><a href="<?echo $config->domain;?>About/Manual/Movies/submitview.avi" target='_blank'>video</a>
</li>
<li>How to <strong>Submit a Locality: </strong><a href="<?echo $config->domain;?>About/Manual/Movies/submitlocality.avi" target='_blank'>video</a>
</li>
<li>How to <strong>Submit an Image: </strong><a href="<?echo $config->domain;?>About/Manual/Movies/submitimage.avi" target='_blank'>video</a>
</li>
<li>How to <strong>Edit</strong> date to publish for <strong>lots of images: </strong><a href="<?echo $config->domain;?>About/Manual/Movies/Changepublishdate.avi" target='_blank'>video</a>
</li>
<li>How to <strong>Annotate a taxon name</strong>: <a href="<?echo $config->domain;?>About/Manual/Movies/AnnotateTaxonName.avi" target='_blank'>video</a>
</li>
<li>How to <strong>Edit an image</strong>, replacing one image with another: <a href="<?echo $config->domain;?>About/Manual/Movies/EditImage.avi" target='_blank'>video</a>
</li>
<li>How to <strong>Add a Taxon Name</strong> to Morphbank: <a href="<?echo $config->domain;?>About/Manual/Movies/AddTaxonNamenew.avi" target='_blank'>video</a>
</li>
<li>How to <strong>Edit Specimen Determination</strong> in Morphbank: <a href="<?echo $config->domain;?>About/Manual/Movies/Edit_Specimen_Determination.avi" target='_blank'>video</a>
</li>
<li>How to <strong>Create a Collection of Images and Label by Specimen Part</strong>: <a href="<?echo $config->domain;?>About/Manual/Movies/collectionbyspecimenpart.avi" target='_blank'>video</a>
</li>
		</ul>
</div>
-->


<div class="specialtext3"><strong>Contributors to the Morphbank User Manual</strong>:
Wilfredo Blanco, Robert Bruhn, Christopher Cprek, Andy Deans, Chantelle Dorsey, Cynthia Gaitros, David Gaitros, Neelima Jammigumpula, Karolina Maneva-Jakimoska, Austin Mast, Debbie Paul,
Greg Riccardi, Fredrik Ronquist, Katja Seltmann, Steve Winner
</div>
<h2>Contents</h2>
<div id=footerRibbon></div>
<br />
<h3>General Topics</h3><br />
<a href="<?echo $config->domain;?>About/Manual/imagePhilosophy.php" title="Morphbank's Viewpoint regarding Public / Private Images">Image-Sharing Philosophy</a><br />
<a href="<?echo $config->domain;?>About/Manual/manualHints.php">User Manual Hints</a><br />
<!--<a href="<?echo $config->domain;?>About/Manual/manualIntro.php">INTRODUCING MORPHBANK</a><br />-->
<!--<a href="<?echo $config->domain;?>About/Manual/advantages.php">Advantages of Becoming a Morphbank Member</a><br />-->
<a href="<?echo $config->domain;?>About/Manual/systemRequire.php">System Requirements</a><br />
<!--<a href="<?echo $config->domain;?>About/Manual/howToSubmit.php">How to Submit Data to Morphbank</a><br />-->
<!--<a href="<?echo $config->domain;?>About/Manual/copyright.php">Morphbank Copyright Policy</a><br />-->
<a href="<?echo $config->domain;?>About/Manual/userPrivileges.php" title="Definitions of Morphbank User, Submitter, Group, Group Roles, ...">Users and Their Privileges</a><br />
<a href="<?echo $config->domain;?>About/Manual/graphicGuide.php" title="Icons in Morphbank" >Guide to Graphic Buttons</a><br />
<a href="<?echo $config->domain;?>About/Manual/terms.php" title="Just what does that word mean in Morphbank?">Morphbank Terms and Definitions</a><br />
<a href="<?echo $config->domain;?>About/Manual/screenTips.php" title="Anatomy of Morphbank Pages">Screen Use Tips</a><br />
<a href="<?echo $config->domain;?>About/Manual/ITIS.php">Integrated Taxonomic Information System (ITIS)</a><br />
<a href="<?echo $config->domain;?>About/Manual/addTaxonName.php">Add New Taxon Name</a><br />
<a href="<?echo $config->domain;?>About/Manual/InfoLinking.php">Information Linking</a><br />
<a href="<?echo $config->domain;?>About/Manual/externalLink.php" title="How-To guide for URLs to &amp; from Morphbank Objects">External Linking - Internal Linking</a><br />
<a href="<?echo $config->domain;?>About/Manual/show.php" title="How does Morphbank reveal metadata?">Morphbank Show</a><br />
<a href="<?echo $config->domain;?>About/Manual/zoomingViewer.php" title="Zooming Viewer - to zoom in on images">Zooming Viewer</a><br />
<a href="<?echo $config->domain;?>About/Manual/services.php" title="Services - What are they? How do they work?">Morphbank Web Services</a><br />
<a href="<?echo $config->domain;?>About/Manual/dwcabcdmb.php" title="Morphbank - Darwin Core - ABCD">Schema Map: Morphbank-Darwin Core-ABCD</a>
<br />
<br />
<h3>Specific Guide to: Login, Groups; Browse - Search - Collect - Annotate - Submit &amp; Edit via My Manager; Mirrors</h3><br />
<a href="<?echo $config->domain;?>About/Manual/gettingStarted.php">Getting Started</a><br />
<a href="<?echo $config->domain;?>About/Manual/login.php">Login - User Name &amp; Password</a><br />
&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/loginUsername.php">Login - Request a New Account</a><br />
&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/updateAccount.php">Login - Update User Account Information</a><br />
<a href="<?echo $config->domain;?>About/Manual/selectGroup.php" title="How to Select a Group and why it's important">Group - Select</a><br />
<a href="<?echo $config->domain;?>About/Manual/modifyGroup.php" title="Adding, Removing, Changing Roles of Morphbank Group Members">Group - Modify Members</a><br />
Browse via <a href="<?echo $config->domain;?>About/Manual/myManager.php">My Manager</a><br />
&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/myManagerFeatures.php">Important Features - Keyword Search, Limit Search by, Select Mass Operation &amp; Sort by </a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/myManagerAll.php">My Manager - All tab</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/myManagerImages.php">My Manager - Image tab</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/myManagerSpecimens.php">My Manager - Specimen tab</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/myManagerViews.php">My Manager - View tab</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/myManagerLocalities.php">My Manager - Locality tab</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/myManagerTaxa.php">My Manager - Taxa tab</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/myManagerCollections.php">My Manager - Collection tab</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/myManagerCollectionsDefined.php" title="Collections Guidelines - Attributes, Finding, Sharing, Types of, Deleting, Published, Unpublished ...">What is a Collection?</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/myManagerCollectionsCreate.php">How to make a Collection</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/myManagerCollectionsSample.php">A Sample Collection</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/characterCollections.php">Character Collections</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/myManagerAnnotations.php">My Manager - Annotation tab</a><br />

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/annotation.php">What is an Annotation? / Types of Annotations</a><br />

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/annotationAdd.php">Annotation - Add</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/annotationEdit.php">Annotation - Edit</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/annotationShow.php">Annotation  - Record Show</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/annotate_taxon_name.php">Annotation - Taxon Names</a><br />

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/myManagerPublications.php">My Manager - Publication tab</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/browseTaxonH.php">Browse - Taxon Hierarchy</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/browseTaxonN.php">Browse - Taxon ABC</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/taxonNameSearch.php">Browse - Taxon Search</a><br />

Preparing to <a href="<?echo $config->domain;?>About/Manual/submit.php">Submit</a> - Options and Considerations<br />
<a href="<?echo $config->domain;?>About/Manual/uploadSubmit.php">Submit - via Web</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/uploadSubmitLocality.php">Add Locality</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/uploadSubmitSpecimen.php">Add Specimen</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/uploadSubmitView.php">Add View</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/uploadSubmitImage.php">Add Image</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/uploadSubmitPublication.php">Add Publication</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/addTaxonName.php">Add Taxon Name</a><br />
<a href="<?echo $config->domain;?>About/Manual/edit.php">Edit - (Locality, Specimen, View, Image)</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/edit_annotations.php">Edit - Annotations</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/edit_collections.php">Edit - Collections</a><br />

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/edit_taxon_name.php">Edit - Taxon Name</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/edit_publications.php">Edit - Publications</a><br />
<!--<a href="<?echo $config->domain;?>About/Manual/mirrorInfo.php">Mirrors</a><br />
-->
<br />
<h3>Mass Upload Options</h3><br />
<a href="<?echo $config->domain;?>About/Manual/submit.php">Which Option Fits Best?</a> - Options and Considerations<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>docs/mb3wbmanual.pdf">Excel Workbook v3 Manual (pdf)</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>docs/mb3a.xls">Excel Workbook v3 - Animalia views</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>docs/mb3p.xls">Excel Workbook v3 - Plantae views</a><br />
<!-- TODO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>docs/mb3psample.xls">Sample Excel Workbook v3 - Plantae views</a><br />-->
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/Manual/customWorkbook.php">Custom Workbook Instructions</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>docs/customWorkbook.xls">Custom Workbook</a><br />
<!-- TODO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>docs/customWorkbookSample.xls">Sample Custon Workbook</a><br />-->
<!-- TODO <a href="<?echo $config->domain;?>About/Manual/xmlUpload.php>XML Upload</a><br />-->
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/HowToContribute/">XML Upload</a> - Contributors convert their data into Morphbank XML Schema format.<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?echo $config->domain;?>About/HowToContribute/">Specify plugin</a> - (in alpha-testing): Users with data in Specify database utilize a plug-in to upload directly to Morphbank.<br />
<!-- TODO <a href="<?echo $config->domain;?>About/Manual/specify.php">Specify (in alpha-testing)</a><br />-->
<br />
<br />
			</div>
			<?php
//Finish with end of HTML	
finishHtml();
?>	
