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
		<h1>Localities tab - My Manager</h1>
<div id=footerRibbon></div>
		<td width="100%">		
	<p>All the features and tools found here are used the same way in other tabs of My Manager. All Localities contributed/submitted to Morphbank will be seen in this tab. A Locality record contain details for a Specimen's given locale when it was observed and/or collected. More than one Specimen may be associated with a given locality. Clicking on the camera icon for a given Locality jumps to all images associated with that locality.
</p>
	
    <p>Paths to My Manager Localities tab:
    <ul>
    <li>Header:<strong> hover over Browse</strong> to open drop-down > select <strong>Locality</strong> from drop-down OR
    </li>
    <li>Header: <strong>click Browse</strong> to open My Manager interface > click <strong>Localities tab</strong>
    </li>
    </ul>
    </p>
       
	<img src="ManualImages/my_manager_localities.png" alt="Localities tab" hspace="20"/>
	<br />
	<br />
	<h3>Features and Functions of the Localities tab</h3>
	<p>Morpbhank users catch on quickly due to the modular nature of the tools and icons. Note the various features in the above screen shot are explained next.</p>
	
	<ul>
	
	<li><a href="<?echo $config->domain;?>About/Manual/manualHints.php" ><img src="ManualImages/feedback.png" alt="feedback link" align="middle" /></a>: please use this link to our automated feedback system. 
	We appreciate your comments so that we can continue to improve and enhance Morphbank. Clicking on <strong><font color="red">(Help)</font></strong> opens this Manual.</li>
	
	<li><a href="<?echo $config->domain;?>/About/Manual/myManagerFeatures.php" ><strong>Keywords: </strong></a> To use this powerful search feature, hold the mouse over the Keywords box to see a list of fields the Keywords queries. Enter a term or terms. Note that searches are <em>boolean <strong>and</strong></em>. Partial words can also be entered as all terms entered are <strong>wild-carded.</strong> Hover over to see a list of
	fields the Keywords accesses. See the yellow note above.</li>
	
	<li><a href="<?echo $config->domain;?>/About/Manual/myManagerFeatures.php" ><strong>Limit Search by:</strong></a> allows a user to view	only the objects personally contributed/submitted AND/OR those from a particular group. Use the 
	Header Menu <strong>Tools > Select Group > Choose group from sub-menu</strong> feature to change groups OR use the 
	<strong>Group</strong> link in the Header on the left side of the Main Screen under User name.</li>
	
	<li><strong>Check box:</strong> Note the <img src="ManualImages/check_box.png"> to the left of each Locality Id and Title. Use this
	feature to <strong>Select</strong> one or more Localities which will then be highlighted. A particular action can now
	 be applied to all the checked Localities at one time using the <strong>Select Mass Operation</strong> feature. If a 
	 user wishes to collect all the Localities on a given page into a collection, use the <strong>Check All</strong> button,
	 then <strong>Select Mass Operation > Create new collection > click Submit button.</strong></li>
	
	<li><a href="<?echo $config->domain;?>About/Manual/myManagerFeatures.php" ><strong>Select Mass Operation:</strong></a> 
	After checking one or more Localities with the <strong>check
	box</strong> feature, options in the drop-down of <strong>Select Mass Operation</strong> can be applied to all
	the Localities checked. An example would be: gathering all the checked Localities into a new or existing collecion.</li>
	
	<li><strong>Submit</strong>: Click this button to carry out the <strong>Mass Operation</strong> selected.</li>
	
	<li><strong>Icons</strong>: The <strong>icons</strong> and their functions are explained fully in Morphbank 
	<a href="<?echo $config->domain;?>About/Manual/graphicGuide.php" >Guide to Graphic Buttons</a>. An abbreviated 
	explanation can be found on the <a href="<?echo $config->domain;?>About/Manual/myManagerAll.php" >All</a> page
	for My Manager. 
	
		<ul>
		<li>The <img src="<?echo $config->domain;?>/style/webImages/infoIcon-trans.png" /> icon	shows metadata for the object, 
		in this case the Locality in a format that Morphbank refers to as the <a href="<?echo $config->domain;?>About/Manual/show.php" >Show.</a>
		</li>
		<li>Using the <img src="<?echo $config->domain;?>/style/webImages/edit-trans.png" /> <strong>Edit</strong> icon gives users access to 
		change/update data for any <strong>Localities</strong> they've contributed/submitted.
		</li>
		<li>Note the <img src="<?echo $config->domain;?>style/webImages/camera-min.gif" width="16" height="16"> is a link to all 
		images in Morphbank linked to this particular Locality.
		</li>
		</ul>
	
		
<li><strong>Creating Collections with My Manager</strong>: To group objects (in this case, Localities) 
together creating a collection, follow the steps in the box next:		
	<div class="specialtext2">
     <h3>Short-cut instructions to creating a collection</h3>
	<ul>
	<li>use the <img src="ManualImages/check_box.png" alt="check box" /> <strong>check box</strong> next to each desired locality.
	 <em>Each locality checked will	be highlighted in green.</em>
	<li>After checking all desired items, go up to the <strong>Select Mass Operation</strong> drop-down
	and choose the desired action (like <strong>Create New Collection</strong> or <strong>Copy to Existing Collection</strong>).
	</li>
	<li>Click the <img src="ManualImages/submit_button.gif"> to perform the <strong>Mass Operation</strong> selected.
	</li>
    <li>Any <strong>Collections</strong> created can be seen in the 
	 <a href="<?echo $config->domain;?>About/Manual/myManagerCollections.php" >Collections</a> tab.</li>
	</ul>
	
	</div>
</li>		
</ul>

<a href="<?echo $config->domain;?>About/Manual/uploadSubmitLocality.php" ><h3>Add a Locality to Morphbank</h3></a>
<br />
<br />
<a href="<?echo $config->domain;?>About/Manual/edit.php" ><h3>Edit a Locality in Morphbank</h3></a>
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
<td><a href="<?echo $config->domain;?>About/Manual/myManagerTaxa.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</tr>
				</div>
		
			<?php
// Finish with end of HTML
finishHtml();
?>
