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

	<div class="mainGenericContainer" width="100%">
		<!--change the header below -->
		<h1>My Manager</h1>
<div id=footerRibbon></div>
		<td width="100%">		
			<br />
			
	The My Manager interface seen next is Morphbank's tool to help the scientist with a Morphbank account manage their objects in Morphbank. From <a href="<?echo $config->domain;?>" target="blank">www.morphbank.net</a> simply click on <strong>Browse</strong> in the Header and then any of the options listed in the drop-down. If a Morphbank account holder logs in at <a href="<?echo $config->domain;?>" target="blank" >www.morphbank.net</a> with User Name and Password, the system opens at the <strong>My Manager Images</strong> tab with features, functions and icons reserved for account holders activated. New options are now available under <strong>Browse</strong>, <strong>Tools</strong> and <strong>Help</strong> in the header. 

<div class="specialtext2">
Everyone uses this My Manager interface -- the public and those with Morphbank accounts. The general public uses My Manager tabs to browse all the objects in Morphbank that are <em>visible to the public</em>. In Morphbank terms, the public sees only images that are <strong><em>published</em></strong>.
</div>


<img src="ManualImages/my_manager2.png" hspace="20" />

<p>
The logged-in user can limit search results to show any objects in Morphbank or some subset of objects they've personally contributed to the database. Images that are not published can only be seen by those who are 1) logged into Morphbank, and 2) in the Group with which the image is associated. Logged in users can submit objects, edit data, annotate objects, create character states &amp; collections and see the annotations and collections of others belonging to the same Morphbank Group(s).
</p>

<div class="specialtext3">Go to <a href="<?echo $config->domain;?>About/Manual/screenTips.php">Screen Use Tips</a> for a general introduction to all the icons and special features found on Morphbank pages.
</div>
	<h2>Basic Anatomy of a My Manager Page</h2>
	<br />
	<br />
    <img src="ManualImages/anatomy_browse_images.png" hspace="20"/>
    <br />
    <p>The Morphbank My Manager interface is modular. Features found in one tab work the same way in each tab. This screen shot serves as a general introduction to this user-interface.</p>
<ul>
<li>The number of hits displayed on each page can be designated (A) and a user can advance to
a specific page number by listing that page (B) and selecting the go button. Keep in mind that the quantity of information requested to display per page will
affect the speed at which that screen loads (i.e. requesting 100 records per page will take
longer to load than the screen that has only 10 records to load).
</li>
<li>(C) Help and Feedback are available from any tab in Morphbank. The Help link is contextual to where the user is when they click on Help. Feedback opens a form the user may fill out to send an automated message to Morphbank. 
</li>
<li>At the top left is the Keywords search box (D). Metadata associated with each object in Morphbank is stored in a single table for quick searching. Any string entered here is wild-carded. Searches here are <em>boolean and</em> so that the user may narrow the result set. Click on the Search button, or press enter to perform the search.
</li>
<li>Use the tabs (E) to jump to different Morphbank objects (Images is the tab shown here). 
</li>
<li>At the left side-bar, note the <em>Limit Search by</em> feature (F). By checking one of the boxes, the Morphbank user can limit the resulting objects on display as desired. For example, the user may only wish to see objects they have contributed to Morphbank. Then, by using the check box (G) or the Check All button, the Morphbank user can group this set of objects into a Morphbank collection. First the user checks the objects to be collected and then goes to (H) Select Mass Operation. From the Select Mass Operation drop-down, the user chooses Create New Collection and then clicks on the Submit button.
</li>
<li>Clicking on the <strong>jpeg</strong> or <strong>Original</strong> (I) links will open images uploaded to Morphbank. If the original images have other formats, those will be found here.
</li>
<li>Selecting the information icon (J) will display detailed information about that object. 
</li>
<li>Other options such as edit (K) and annotate (L) will be available only for those authorized through login permissions. 
</li>
<li>The magnifying glass (M) will open the image using the FSI Viewer, allowing the user
to zoom, rotate and adjust color to enhance detailed features of the images. 
</li>
<li>Selecting the tree of life symbol (N) will list the taxonomic hierarchy of the Taxon name.
</li>
<li>In the Images tab of My Manager, clicking on the blue hot links (0) for Group, User, View, Specimen initiates a search for images related to the link clicked. For example, clicking on the Group link returns images associated with that Morphbank Group. Clicking on View returns images that use that same Morphbank View.
</li>
</ul>
</p>
<h2>Unique Features of My Manager and Morphbank</h2>
	
			<div class="specialtext2">
			<ul>
			<li>Change the date to publish for many of your objects at one time using the <a href="<?echo $config->domain;?>/About/Manual/myManagerFeatures.php" >
            <strong>"Select Mass Operation"</strong></a></li>
			<li><a href="<?echo $config->domain;?>/About/Manual/myManagerFeatures.php" ><strong>Easily limit your search</strong></a> by your group, all of the groups to which you
             belong, and those objects you've contributed</li>
			<li>The <strong>module tabs</strong> at the top of the <strong>My Manager</strong> interface allow easy movement between
			 lists of kinds of records (ie. Taxon Names, Publications, Specimens, Images etc.,).
			<li><strong>Create OTUs</strong> or operational taxonomic units.  These express a concept of a possible new classification, or grouping of 
			taxa. These new concepts can then be shared with other users.</li>
			<li><strong>Create <a href="<?echo $config->domain;?>About/Manual/characterCollections.php" target="_blank">Character Collections</a></strong>. 
			 Using the sorting capabilities you can create collections to define a character and character states.  These defined and illustrated characters
             can then be shared with other users.</li>
			<li>Now a user can <em>create collections that include any Morphbank object</em>.  So you can create a collection of publications, annotations, 
			specimens etc that can be shared with other users!</li>
            <li>Users can create <strong>rss feeds</strong> from any group record in Morphbank. Click on a contributor's name to open the Contributor's group record and look for the <img src="ManualImages/feed-icon-96x96.jpg" alt="rss feed icon" /> icon.
            </li>
            <li>Morphbank now features a <a href="http://services.morphbank.net/mb/" target="_blank"><em>services</em></a> page where users can query Morphbank and get feedback in the format of choice (ids, thumbnails, googlemaps, xml, etc.,). This service is great for retrieving ids to create urls for use in web pages and published papers.
            </li>
			</ul>
	</div>

<h2>Known Version Issues</h2>
		<div class="specialtext2">
		 My Manager is presently under the last stages of development.  Most of these issues are resolved and
		  additions made to the software.  It is very usable now so please try it out and send us 
		  <a href="<?echo $config->domain;?>/Help/feedback/" target="_blank">feedback</a> if you have suggestions!
		 <ul>
		 <li>The manual is being updated regularly.</li>
		 <li>Keyword search in My Manager may not return the expected results.</li>
		 <li>Return buttons will act strange when returning from pages back to My Manager. They should return to the tab in the My Manager
		  where you just were but will not retain your search results.</li>
		 <li>If user clicks on another Tab before the Images Tab loads, an SQL Exception Error may result. Refresh the page with F5 
		 (or what your Browser has for Refresh) to fix.</li>
		 <li>When you say 'Publish Now' it does it but the 'Publish Now' text does not go away until the next refresh.</li>
         <li>If Editing an External Link or GUID for any Morphbank Object (Image, Specimen, View, Locality), an error may result as we modify the software to allow users to 
         provide <a href="<?echo $config->domain;?>/About/Manual/terms.php" ><strong>unique external identifiers (GUID)s</strong></a> via the same interface.</li>
		 </ul>
		 </div>
		

		<br />
		<br />
	
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/myManagerFeatures.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</tr>
				</div>
		
			<?php
// Finish with end of HTML
finishHtml();
?>
