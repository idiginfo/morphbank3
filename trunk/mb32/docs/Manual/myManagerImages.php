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
		<h1>Images tab - My Manager</h1>
<div id=footerRibbon></div>
		<td width="100%">		
	
	<p>The Images tab of My Manager is the default page. 
	<ul>
	<li>All the features and tools found here are used the same way in other tabs of My Manager.
	</li>
	<li>Any published Images and any Images contributed/submitted by a user will be seen in this tab.
	</li>
	<li>If a user belongs to mutliple groups, they will also see images contributed/submitted by other members
	for any of those groups.
	</li>
	</ul>
	Path to this tab after <em>login</em> is <strong>Header Menu > Browse > Images</strong>. Or, click <strong>Browse</strong> in the Header to jump to the Images tab.
	</p>
	<br />
	<br />
	<img src="ManualImages/my_manager_images_tab2.png" alt="Images Tab" hspace="20" />
	
	<br />
	<br />
	<h3>Features and Functions of the Images tab</h3>
	<p>Morpbhank users catch on quickly due to the modular nature of the tools and icons. Note the highlighted areas of the above image briefly
	explained next.</p>
	
	<ul>
	
	<li><a href="<?echo $config->domain;?>About/Manual/manualHints.php" ><img src="ManualImages/feedback.png" alt="feeback" align="middle"></a>: please use this link to our automated feedback system. We appreciate your comments so that 
	we can continue to improve and enhance Morphbank. The link labeled <strong><font color="red">(Help)</font></strong> opens a page in this online User Manual.</li>
	
	<li><a href="<?echo $config->domain;?>About/Manual/myManagerFeatures.php" ><strong>Keywords: </strong></a> To use this powerful search feature, hold the mouse over the Keywords box to see a list of fields the Keywords queries. Keyword search is <em>boolean <strong>AND</strong></em>. Complete words or partial terms can be entered as each term entered in the Keyword box is also <strong>wild-carded</strong>.</li>.
	
	<li><a href="<?echo $config->domain;?>About/Manual/myManagerFeatures.php" ><strong>Limit Search by:</strong></a> allows a user to easily limit their view to only the objects personally contributed/submitted AND/OR those from a particular group. Use the <strong>Header Menu > Tools > Select Group > Choose group from sub-menu</strong> feature to change groups OR hover over <strong>Group</strong> in the <strong>Header</strong> on the left side of the Main Screen under your User name.</li>
	
	<li><strong>Check box:</strong> Note the <img src="ManualImages/check_box.png" alt="check box"> to the left of each Image title. Use this
	feature to <strong>Select</strong> one or more Images which will then be highlighted in green. Now, a particular action can be applied to all the
	selected images at one time using the <strong>Select Mass Operation</strong> feature.</li>
	
	<li><a href="<?echo $config->domain;?>About/Manual/myManagerFeatures.php" ><strong>Select Mass Operation</strong></a>: After checking one or more Images with the <strong>check
	box</strong> feature, options in the drop-down of <strong>Select Mass Operation</strong> can be applied to all
	the Images checked. Examples include: gathering all the checked Images into a collecion, a character collection, or 
	changing the date-to-publish on all checked Images at once.</li>
	
	<li><strong>Submit</strong>: Click this button to carry out the <strong>Mass Operation</strong> selected.</li>
	
	<li><strong>Icons</strong>: The <strong>icons</strong> and their functions are explained fully in Morphbank 
	<a href="<?echo $config->domain;?>About/Manual/graphicGuide.php" target="_blank">Guide to Graphic Buttons</a>. An abbreviated 
	explanation can be found on the <a href="<?echo $config->domain;?>About/Manual/myManagerAll.php" target="_blank">All</a> page
	for My Manager.

		<ul>
		<li>The <img src="<?echo $config->domain;?>style/webImages/infoIcon-trans.png" /> icon	shows data for the image. 
		In general, the <img src="<?echo $config->domain;?>style/webImages/infoIcon-trans.png" /> opens a window that Morphbank 
		calls a <a href="<?echo $config->domain;?>About/Manual/show.php" ><strong>Show</strong></a> and it displays 
		the metadata for a given object in Morphbank.		</li>
		<li><img src="<?echo $config->domain;?>style/webImages/edit-trans.png" /> 
		, the <strong>Edit</strong> icon, allows a user to <strong>update/change/edit</strong> data associated with an <em>unpublished</em> 
		object they've contributed or submitted.If an Image is <em>not yet published</em> the user can replace that image.		</li>
		<li>With a click on the <img src="<?echo $config->domain;?>style/webImages/annotate-trans.png" /><a href="<?echo $config->domain;?>About/Manual/"><strong>Annotate</strong></a>
		icon, any user may leave a permanent comment about a given object.</li>
		<li>The <img src="../../style/webImages/magnifyShadow-trans.png" alt="zooming viewer icon"> magnifying glass icon will open an image in the
		open source <a href="<?echo $config->domain;?>About/Manual/zoomingViewer.php">Zooming Viewer</a> which allows zooming to help reveal/illuminate
		image features. Morphbank utilizes this	open source viewer to allow additional functionality to be added in order to increase the value
	of the photograph for the user. Click on any thumbnail, click on the resulting image in the <strong>Image Record Show</strong> to 
	open the image in the Zooming Viewer.</li>
		</ul>
	<li><strong>Annotations</strong>: If annotations exist for an Image, this will be a blue link (as
	in the above first image) to the annotations.</li>

	<li><strong>Creating Collections with My Manager</strong>: To group objects (in this case, images) follow the steps in the box.
	
	<div class="specialtext2">
    <h3>Short-cut instructions to creating a collection</h3>
	<ul>
	<li>To group objects together <em>creating a collection</em>, use the 
	<img src="ManualImages/check_box.png" alt="check box" /> <strong>check box</strong> next to each Object Id and Title.
	<br />
	<em>Each item checked will be highlighted in green</em>.
	</li> 
	<li>After checking all desired items, go up to the <img src="ManualImages/select_mass_operation.png" align="middle"  alt="select mass operation"> drop-down
	and choose the desired action (like <strong>Create New Collection</strong>, <strong>Create New Character</strong>
	collection, or <strong>Copy to existing collection...</strong> and then click <strong>Submit</strong>.
	</li>
	<li>Other objects from other tabs in My Manager can be added to an existing Collection in the same manner.
	</li>
	<li>Any <strong>Collections</strong> created can be seen in the 
	<a href="<?echo $config->domain;?>About/Manual/myManagerCollections.php" >Collections</a> tab.
	</li>
	</ul>
  	</div>
  
    <div class="specialtext3">
    Currently, there are <em>3 types of Morphbank Collections</em>:
    <ol>
    <li>a Collection of Images (and / or other objects), 
    </li>
    <li>a Character Collection created to illustrate Character States for a defined Character, and an
    </li>
    <li>OTU Collection consisting of Specimens and Taxon Names -- designed to help a user describe <em>operational taxonomic units (OTU)s.</em>
    </li>
    </ol>
   </div>

</li>
</ul>	

<a href="<?echo $config->domain;?>About/Manual/uploadSubmitImage.php" ><h3>Add an Image to Morphbank</h3></a>
<br />
<br />
<a href="<?echo $config->domain;?>About/Manual/edit.php" ><h3>Edit an Image in Morphbank</h3></a>
<br />
<br />
<a href="<?echo $config->domain;?>About/Manual/myManagerCollectionsCreate.php" ><h3>How to Create an Image Collection</h3></a>
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
<td><a href="<?echo $config->domain;?>About/Manual/myManagerSpecimens.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</tr>
				</div>
		
			<?php
// Finish with end of HTML
finishHtml();
?>
