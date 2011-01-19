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
		<h1>Collections tab - My Manager</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->

<p>From the <strong>Collections tab</strong> in My Manager, users can see any collection they've created
in Morphbank as well as any other user's published collections. Morpbhank users will catch on quickly due to the modular nature of the tools and icons.

<div class="specialtext2">
A collection is a group of any objects in the Morphbank database assembled by members for the purpose of viewing 
and/or manipulating (e.g. rearranging the order, editing, and/or annotating, etc.) and
storing the collected objects for future use.
</div>

</p>

<img src="ManualImages/my_manager_collections.png" alt="Collections Tab" hspace="55" />

<br />

<div class="specialtext3">  
Currently, there are 3 types of Morphbank Collections:
<ol>
<li> a Collection of Images (and / or other objects -- a mixed collection), 
</li>
<li>a Character Collection created to illustrate Character States for a defined Character, and an 
</li>
<li>OTU Collection consisting of Specimens and Taxon Names -- designed to help a user describe operational taxonomic units (OTU)s. OTU collections are in the Taxa tab.
</li>
</ol>
One can also create a collection of collections, which is very useful when putting links in publications to objects in Morphbank. 
</div>

<h3>Features and Functions of the Collections tab</h3>
	<p> Note the highlighted areas of the above image briefly explained next. Image / Mixed Collections &amp; Character Collections are found in this tab. OTU collections are found in the <a href="<?echo $config->domain;?>About/Manual/myManagerTaxa.php"><strong>Taxa</strong></a> tab.</p>
	
	<ul>
	
	<li><a href="<?echo $config->domain;?>About/Manual/manualHints.php" ><img src="ManualImages/feedback.png" align="middle"></a>: please use this link to our automated feedback system. We appreciate your comments so that 
	we can continue to improve and enhance Morphbank. Clicking on <strong><font color="red">(Help)</font></strong> opens this Manual.</li>
	
	<li><a href="<?echo $config->domain;?>About/Manual/myManagerFeatures.php" target="_blank"><strong>Keywords: </strong></a> Use the new <strong>enhanced</strong> search feature. Hold the mouse over the
	Keywords box to see an updated expanded list of fields the Keywords field searches.</li>
	
	<li><strong>Toggle Collection/Character:</strong> In this version of Morphbank, the My Manager
	interface has 1 new Collection type, a Characater Collection, and a new feature has been added to the regular collections
	allowing any object in Morphbank to be placed in a collection. Use the radio buttons of this feature to view either
	or both types of Collections.</li>
	
	<li><a href="<?echo $config->domain;?>About/Manual/myManagerFeatures.php" target="_blank"><strong>Limit Search by:</strong></a> 	allows a user to view only the objects personally contributed/submitted AND/OR those from a particular group. Use the 
	<strong>Header Menu > Tools > Select Group > Choose group from sub-menu</strong> feature to change groups OR use the 
	<strong>Group</strong> link in the <strong>Header</strong> on the left side of the Main Screen.</li>
	
	<li><strong>Check box:</strong> Note the 
	<img src="ManualImages/check_box.png" alt="Check box" /> 
	<strong>check box</strong> to the left of each Collection/Character ID
	and Title. Use this feature to <strong>Select</strong> one or more Collections which will then be <em>highlighted green</em>.
	A particular action (like changing a date-to-publish) can now be applied to all the Collections at one time using the <strong>Select Mass Operation</strong> feature.
	 
	 <ul>
	 <li>To see the contents of any Collection, click on the <strong>Collection Title</strong> or the 
	 <img src="../../style/webImages/infoIcon.png" alt="information icon" /> info icon.
	 </li>
	 <li>A user can <strong>Edit</strong> the title of any unpublished Collection they've created simply by clicking 
	 on the <strong>(edit...)</strong> link next to a modifiable title.
	 </li>
	  </ul>
	</li>
	<li><a href="<?echo $config->domain;?>About/Manual/myManagerFeatures.php" target="_blank"><strong>Select Mass Operation</strong></a>:
	 After checking one or more Collections with the <img src="ManualImages/check_box.png"> feature, options in the drop-down of
	  <strong>Select Mass Operation</strong> can be applied to all
	checked objects. Examples include: gathering all the checked objects into a new/existing collecion,
	or changing the date-to-publish on all checked objects at once. A user may also
	delete an unpublished Collection if they so desire. The 
	<img src="../../style/webImages/delete-trans.png" height="16" width="16" alt="delete icon" /> delete icon will
	be present if delete is allowed.</li>
	
	<li><img src="ManualImages/submit_button.gif" alt="Submit button">: Click this button to carry out the <strong>Mass Operation</strong> selected.</li>
	
	<li><strong>Icons</strong>: The <strong>icons</strong> and their functions are explained fully in Morphbank 
	<a href="<?echo $config->domain;?>About/Manual/graphicGuide.php" target="_blank">Guide to Graphic Buttons</a>. An abbreviated 
	explanation can be found on the <a href="<?echo $config->domain;?>About/Manual/myManagerAll.php" target="_blank">All</a> page
	for My Manager.
	
		<ul>
		<li>Briefly, the <img src="<?echo $config->domain;?>style/webImages/infoIcon-trans.png" /> icon
		opens the collection. 
		</li>
		<li>Clicking on the 
		<img src="../../style/webImages/camera-min16x12.gif" alt="camera icon" />
		<strong>camera</strong> icon will open the Collection. 
		</li>
		<li>The 
		<img src="../../style/webImages/calendar.gif" alt="calendar icon" />
		<strong>Calendar</strong> icon allows a user to change the date-to-publish for the Collection to be visible to all
		Morphbank users or extend the time it remains private.
		</li>
		<li>The <img src="../../style/webImages/copy-trans.png" alt="copy icon" />
	 	<strong>Copy</strong> icon allows a user to make two kinds of Collections, a regular or Characater
		Collection from any Collection (of either type).</li>
		</ul>
		</li>
	<li><strong>Annotations</strong>: If annotations exist for an object inside a collection, 
	this will be indicated by the
	<img src="../../style/webImages/annotateIcon.png" alt="alternate annotate icon" /> icon seen in the open collection
	itself. (See screen shot).
	<br />
	<img src="ManualImages/annotation_exists_icon.png" alt="A icon" vspace="10" />
	</li>
		
	
	
	<li><a href="<?echo $config->domain;?>About/Manual/zoomingViewer.php" target="_blank"><strong>Zooming Viewer</strong></a>: Morphbank utilizes
		 an open source viewer so that new funtionality may be added to increase the value
	of the photograph for the user. Clicking on any thumbnail in the <strong>Collections tab</strong> will
	open the Collection, double-click on any image in the Collection to 
	open the image in the Zooming Viewer. 
    </li>
  </ul>  
    

<a href="<?echo $config->domain;?>About/Manual/myManagerCollectionsCreate.php" target="_blank"><h3>Create a Collection</h3></a>
<br />
<br />
<a href="<?echo $config->domain;?>About/Manual/myManagerCollectionsSample.php" target="_blank"><h3>A Sample Collection</h3></a>
<br />
<br />
<h3>Edit a Collection</h3>: A collection must be <em>unpublished</em> or a user will not be able to <strong>Edit</strong> the contents. To Edit 
an <em>unpublished</em> Collection, click the <img src="../../style/webImages/infoIcon.png" alt="information icon" /> to open the Collection.
Alter the contents as desired. A user might delete some objects, change the title for some of the objects, or rearrange the objects in the
collection and save the order.
<br />
<br />
<a href="<?echo $config->domain;?>About/Manual/myManagerCollectionsDefined.php" target="_blank"><h3>What exactly is a Collection?</h3></a>
<br />
<br />
<a href="<?echo $config->domain;?>About/Manual/characterCollections.php" target="_blank"><h3>Character Collections</h3></a>
		<br />
		<br />
		<a href="<?echo $config->domain;?>About/Manual/myManager.php" ><h3>Known Version Issues</h3></a>
		<br />
		<br />
		<a href="<?echo $config->domain;?>About/Manual/myManager.php" ><h3>Introduction to My Manager</h3></a>
<br />
<br />
				
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/myManagerCollectionsDefined.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</tr>
				</div>
		
			<?php
// Finish with end of HTML
finishHtml();
?>
