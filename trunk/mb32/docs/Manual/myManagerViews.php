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
		<h1>Views tab - My Manager</h1>
<div id=footerRibbon></div>
		<td width="100%">		
	
	<p>All View tab features and tools found here are used the same way in other tabs
	 of My Manager. All Views contributed/submitted to Morphbank will be seen in this tab. A view captures information
	 about the image, including the: specimen part featured in the image, view angle, imaging technique, imaging preparation technique, sex, form and developmental stage of the object in the image.</p>
     <div class="specialtext3">
     Taking images of specimens in a systematic way with regard to the concept of a <em>standard view</em> allows users to group images in new ways and paves the way for future use of these images to link meaningfully to illustrated ontologies as well as image recognition software of the future. Note here, a user can see all the images using a particular Morphbank View by simply clicking on the camera icon for a given View. Would-be contributors are encouraged to utilize this feature by using existing Views if Views are found that fit. If new Views are needed -- the user creates their own set of standard views.
     </div>
     
    <p>Paths to My Manager Views tab
    <ul>
    <li><strong>Header</strong> > hover over <strong>Browse</strong> to open drop-down > select <strong>View</strong> from drop-down OR
    </li>
    <li><strong>Header</strong> > click<strong> Browse</strong> to open My Manager interface > click <strong>View</strong> tab
    </li>
    </ul>
    </p>
    	
	<img src="ManualImages/my_manager_views.png" alt="Views Tab" hspace="20" />
	<br />
	<br />
	<h3>Features and Functions of the Views tab</h3>
	<p>Morpbhank users catch on quickly due to the modular nature of the tools and icons. Note the various features in the above screen shot are explained next.</p>
	
	<ul>
	
	<li><a href="<?echo $config->domain;?>About/Manual/manualHints.php" ><img src="ManualImages/feedback.png" alt="feeback" align="middle"></a>: please use this link to our automated feedback system. We appreciate your comments so that 
	we can continue to improve and enhance Morphbank. Clicking on <strong><font color="red">(Help)</font></strong> opens this Manual.</li>
	
	<li><a href="<?echo $config->domain;?>About/Manual/myManagerFeatures.php" ><strong>Keywords: </strong> </a>To use this powerful search feature, hold the mouse over the Keywords box to see a list of fields the Keywords queries. Enter a term or terms. Note that searches are <em>boolean <strong>and</strong></em>. Partial words can also be entered as all terms entered are <strong>wild-carded.</strong> See the yellow note above.</li>
	
	<li><a href="<?echo $config->domain;?>About/Manual/myManagerFeatures.php" ><strong>Limit Search by:</strong></a>A <strong>new feature</strong> of this (beta version) of Morphbank allows a user to view
	only the objects personally contributed/submitted AND/OR those from a particular group. Use the 
	<strong>Header Menu > Tools > Select Group > Choose group from sub-menu</strong> feature to change groups OR use the 
	<strong>Group</strong> link in the <strong>Header</strong> on the left side of the Main Screen.</li>
	
	<li><strong>Check box:</strong> Note the <img src="ManualImages/check_box.png" alt="check box"> to the left of each View Id and Title. Use this
	feature to <strong>Select</strong> one or more Views which will then be highlighted. A particular action can now
	 be applied to all the checked Views at one time using the <strong>Select Mass Operation</strong> feature. If a 
	 user wishes to collect all the Views on a page into a given collection, use the <strong>Check All</strong> button,
	 then <strong>Select Mass Operation > Create new collection > click Submit button.</strong></li>
	
	<li><a href="<?echo $config->domain;?>About/Manual/myManagerFeatures.php" ><strong>Select Mass Operation</strong></a>: After checking one or more Views with the <strong>check
	box</strong> feature, options in the drop-down of <strong>Select Mass Operation</strong> can be applied to all
	the Views checked. An example would be: gathering all the checked Views into a new or existing collecion.</li>
	
	<li><strong>Submit</strong>: Click this button to carry out the <strong>Mass Operation</strong> selected.</li>
	
	<li><strong>Icons</strong>: The <strong>icons</strong> and their functions are explained fully in Morphbank 
	<a href="<?echo $config->domain;?>About/Manual/graphicGuide.php" >Guide to Graphic Buttons</a>. An abbreviated 
	explanation can be found on the <a href="<?echo $config->domain;?>About/Manual/myManagerAll.php" >All</a> page
	for My Manager.
	
		<ul>
		<li>The <img src="<?echo $config->domain;?>/style/webImages/infoIcon-trans.png" /> icon
		shows metadata for any given object in Morphbank, in this case, the details of a given view.</li>
		<li><img src="<?echo $config->domain;?>/style/webImages/edit-trans.png" />, the <strong>Edit</strong> icon gives users access to 
		change/update metadata for any <strong>Views</strong> they've contributed/submitted.</li>
		<li>Note the <img src="<?echo $config->domain;?>style/webImages/camera-min.gif" width="16" height="16"> is a link to all 
		images in Morphbank using a particular view.
		</li>
		
		</ul>
				
	
		<li><strong>Creating Collections with My Manager</strong>: To group objects (in this case, views) together creating a collection, follow 
		the instructions in the box. 
	 
	 <div class="specialtext2">
     <h3>Short-cut instructions to creating a collection</h3>
	<ul>
	<li>To group objects together <em>creating a collection</em>, use the 
	<img src="ManualImages/check_box.png" alt="check box" /> <strong>check box</strong> next to each Object Id and Title.
	<br />
	<em>Each item checked will be highlighted in green</em>.
	</li> 
	<li>After checking all desired items, go up to the <img src="ManualImages/select_mass_operation.png" align="middle"  alt="select mass operation"> drop-down
	and choose the desired action (like <strong>Create New Collection</strong>, or <strong>Copy to existing collection...</strong>) 
	and then click <strong>Submit</strong>.
	</li>
	<li>Other objects from other tabs in My Manager can be added to an existing Collection in the same manner.
	</li>
	<li>Any <strong>Collections</strong> created can be seen in the 
	<a href="<?echo $config->domain;?>About/Manual/myManagerCollections.php" >Collections</a> tab.
	</li>
	</ul>
	<br />
	</div>
	 
     </li>
     </ul>
	 
<a href="<?echo $config->domain;?>About/Manual/uploadSubmitView.php" ><h3>Add a View to Morphbank</h3></a>
<br /><br />
<a href="<?echo $config->domain;?>About/Manual/edit.php" ><h3>Edit a View in Morphbank</h3></a>
<br /><br />	
		<a href="<?echo $config->domain;?>About/Manual/myManager.php" ><h3>Known Version Issues</h3></a>
		<br />
		<br />
		<a href="<?echo $config->domain;?>About/Manual/myManager.php" ><h3>Introduction to My Manager</h3></a>
		<br />
			<br />
				
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/myManagerLocalities.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</tr>
				</div>
		
			<?php
// Finish with end of HTML
finishHtml();
?>
