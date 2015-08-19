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
		<h1>Specimens tab - My Manager</h1>
<div id=footerRibbon></div>
		<td width="100%">		

	<p>All the features and tools found here are used the same way in other tabs of My Manager. All Specimens contributed/submitted to Morphbank will be seen in this tab. </p>
    
    <div class="specialtext3">
    All provider-contributed Specimen data is displayed in the Specimen tab. A casual browser sees how many images &amp; annotations are present for each specimen. Unique to this tab and the Taxa tab, a user may create an <em><strong>Operational Taxonomic Unit (OTU) Collection.</strong></em> By collecting a specimen record or records and adding a taxon name / names to that Morphbank collection, a user can create / illustrate a description of a <em>new taxonomic grouping</em> or possible <em>new classification</em>, i.e. an <strong>OTU</strong>.
    </div>
    
    
<p>Path to the Specimens tab <em>after login </em>is <strong>Header Menu > Browse > Specimen</strong>. Or, click <strong>Browse</strong> in the Header, then click on the <strong>Specimen tab</strong>.</p>
	
	<img src="ManualImages/my_manager_specimens_tab.png" alt="Specimens Tab" hspace="20" />
	
	<br />
	<br />
	<h3>Features and Functions of the Specimens tab</h3>
	<p>Morpbhank users catch on quickly due to the modular nature of the tools and icons. Note the various features in the above screen shot are explained next.</p>
	
    <ul>
	<li><a href="<?php echo $config->domain; ?>About/Manual/manualHints.php" ><img src="ManualImages/feedback.png" alt="feeback" align="middle"></a>: please use this link to our automated feedback system. We appreciate your comments so that 
	we can continue to improve and enhance Morphbank. Clicking on <strong><font color="red">(Help)</font></strong> opens this Manual.</li>
		
	<li><a href="<?php echo $config->domain; ?>About/Manual/myManagerFeatures.php" ><strong>Keywords: </strong> </a>To use this powerful search feature, hold the mouse over the Keywords box to see a list of fields the Keywords queries. Enter a term or terms. Note that searches are <em>boolean <strong>and</strong></em>. Partial words can also be entered as all terms entered are <strong>wild-carded.</strong> </li>
	
	<li><a href="<?php echo $config->domain; ?>About/Manual/myManagerFeatures.php" ><strong>Limit Search by:</strong></a>allows a user to easily limit their view to only the objects personally contributed/submitted AND/OR those from a particular group. Use the <strong>Header Menu > Tools > Select Group > Choose group from sub-menu</strong> feature to change groups OR use the <strong>Group</strong> link in the <strong>Header</strong> on the left side of the Main Screen.</li>
	
	<li><strong>Check box:</strong> Note the <img src="ManualImages/check_box.png" alt="check box"> to the left of each Specimen Id and Title. 
    <p>Use this feature to <strong>Select</strong> one or more Specimens which will be highlighted. <em><strong>Ruizantheda</strong></em> and 
	<em><strong>Crabronidae</strong></em> are selected in the above example to illustrate this feature.
	 Then, a particular action can be applied to all the <strong>selected</strong> specimens at one time using the 
	 <strong>Select Mass Operation</strong> feature explained next.</p>
     </li>
	
	<li><a href="<?php echo $config->domain; ?>About/Manual/myManagerFeatures.php" ><strong>Select Mass Operation</strong></a>: After checking one or more Specimens with the <strong>check
	box</strong> feature, options in the drop-down of <strong>Select Mass Operation</strong> can be applied to all
	the Specimens checked. Examples shown above include: gathering all the checked Specimens 
	into a new or existing collecion, creating an OTU collection or adding to an existing OTU colletion.
    
    <p>Note the choices available in the <strong>Select Mass Operation drop-down</strong> vary with the My Manager tab.
    </p>
    </li>
	
	<li><strong>Submit</strong>: Click this button to carry out the <strong>Mass Operation</strong> selected.</li>
	
	<li><strong>Icons</strong>: The <strong>icons</strong> and their functions are explained fully in Morphbank 
	<a href="<?php echo $config->domain; ?>About/Manual/graphicGuide.php">Guide to Graphic Buttons</a>. An abbreviated 
	explanation can be found on the <a href="<?php echo $config->domain; ?>About/Manual/myManagerAll.php">All</a> page
	for My Manager.
	<p>Briefly, the <img src="<?php echo $config->domain; ?>style/webImages/infoIcon-trans.png" /> icon
	shows metadata for the image.
	<br />
	The <img src="<?php echo $config->domain; ?>style/webImages/edit-trans.png" /> icon
	 gives users access to <strong>change/update/edit</strong> data for any Specimens they've contributed/submitted.
	 <br /> The <img src="<?php echo $config->domain; ?>style/webImages/camera-min16x12.gif" alt="camera icon" /> <strong>camera icon</strong> is
	 the user's link to see existing images of a particular specimen in Morphbank.
	 </p>
	 </li>
	<li><strong>Annotations</strong>: If annotations exist for any Image of a given Specimen, this will be a blue link (as
	in the above third Specimen <strong>id 196228</strong>) to the annotations.
	</li>
		
	<li><strong>Creating Collections with My Manager</strong>: To group objects (in this case, specimens) together creating a specimen collection, follow
	the steps in the box below. Currently, there are 3 types of Morphbank Collections: a Collection of Images (and / or other objects), a Character Collection created to illustrate Character States for a defined Character, and an OTU Collection consisting of Specimens and Taxon Names -- designed to help a user describe operational taxonomic units (OTU)s. One can also create collections of collections, which is very useful when putting links in publications to objects in Morphbank. 
   
     <p>Collection options presented in the <em>Select Mass Operation</em> <strong>drop-down</strong> change depending on the My Manager tab. <strong>OTUs</strong> are a type of collection whose constituents are Specimen records and Taxon records. So, this OTU collection option is only available in the Specimen and Taxa tabs.
     </p>
	

	
    <div class="specialtext2">
    <h3>Short-cut instructions to creating a collection</h3>
	<ul>
	<li>To group objects together <em>creating a collection</em>, use the 
	<img src="ManualImages/check_box.png" alt="check box" /> <strong>check box</strong> next to each Object Id and Title.
	<br />
	<em>Each item checked will be highlighted in green</em>.
	</li> 
	<li>After checking all desired items, go up to the <img src="ManualImages/select_mass_operation.png" align="middle"  alt="select mass operation"> drop-down
	and choose the desired action (like <strong>Create New Collection</strong>, <strong>Create New OTU</strong>,
	or <strong>Copy to existing collection/OTU...</strong> and then click <strong>Submit</strong>. OTUs express a concept of a possible new classification, or grouping of taxa.
	</li>
	<li>Other objects from other tabs in My Manager can be added to a Morphbank user's existing Collection in the same manner.
	</li>
	<li>Any regular or character state <strong>Collections</strong> created can be seen in the 
	<a href="<?php echo $config->domain; ?>About/Manual/myManagerCollections.php">Collections</a> tab.
	</li>
	<li>Any <strong>OTU</strong> collections created can be seen in the <a href="<?php echo $config->domain; ?>About/Manual/myManagerTaxa.php" >Taxa tab</a>
	</li>
	</ul>
	<br />
    Morphbank users can create 3 different types of Collections. One type of collection represents an <strong>OTU.</strong>  As an example, a user may group representative Specimen records along with one or more Taxon records into a Morphbank Collection to express a <em>possible new classification</em> or <em>grouping of taxa.</em> So the <strong>Select Mass Operation</strong> drop-down only shows the OTU option in the <strong>Specimen</strong> and <strong>Taxa</strong> tabs.
	</div>	
	</li>
 	
		
    <li><a href="<?php echo $config->domain; ?>About/Manual/zoomingViewer.php" ><strong>Zooming Viewer</strong></a>: Morphbank utilizes this 
    open source viewer in order to be able to add more functionality to increase the value of the photograph for the user. 
    Click on any thumbnail or click on the resulting image in the <strong>Image Record Show</strong> to 
	open the image in the Zooming Viewer.
    </li>
    </ul>

<a href="<?php echo $config->domain; ?>About/Manual/uploadSubmitSpecimen.php" ><h3>Add a Specimen to Morphbank</h3></a>
<br /><br />
<a href="<?php echo $config->domain; ?>About/Manual/edit.php" ><h3>Edit a Specimen in Morphbank</h3></a>
<br /><br />					
		<a href="<?php echo $config->domain; ?>About/Manual/myManager.php" ><h3>Known Version Issues </h3></a>
		<br />
		<br />
		<a href="<?php echo $config->domain; ?>About/Manual/myManager.php" ><h3>Introduction to My Manager</h3></a>
		<br />
			<br />
		
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?php echo $config->domain; ?>About/Manual/myManagerViews.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</tr>
				</div>
		
			<?php
// Finish with end of HTML
finishHtml();
?>
