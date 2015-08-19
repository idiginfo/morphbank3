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
		<h1>Taxa tab - My Manager</h1>
<div id=footerRibbon></div>
		<td width="100%">
        <br />		

<p>
In the My Manager <strong>Taxa </strong>tab users can access all the taxon names in Morphbank with the associated data. 
  </p>
  <div class="specialtext2">
<h2>What can a Morphbank Contributor do with the Taxa tab?</h2>
    <ul>
    <li>A user may see all (if any) of the taxon names they've contributed to Morphbank.</li>
    <li><strong>Add</strong> a new Taxon Name to Morphbank from this screen's <strong><img src="ManualImages/add_new_taxa_button.png" alt="add new taxa button" /></strong> button in the left side-bar as well as	
    </li>
    <li><strong>Edit</strong> any <em>unpublished</em> name they've added via the <img src="../../style/webImages/edit-trans.png" alt="edit icon" /> icon displayed to the right of the taxon name.
    </li>
    <li>In addition, users may <strong>Annotate</strong> any taxon name in Morphbank from the <img src="../../style/webImages/annotate-trans.png" alt="annotate icon" /> icon.
    </li>
    <li><strong>Operational Taxonomic Units (OTUs)</strong> can be defined 
	and added to Morphbank. 
    
    <p><strong>OTUs</strong> express a concept of a possible new classification, or grouping 
	of taxa. These can then be shared with other users.  Functionality to export these may be added in the future.
    Here's a <a href="<?php echo $config->domain; ?>myCollection/?id=221291" >Sample OTU in Morphbank</a> using Specimen records and 4 taxon names to express the concept of <strong>Apidae</strong>.</p>
    
	</li>
    <li>Note the <strong>Toggle Taxa/OTU</strong> radio buttons allowing the viewer to limit the data being
	reviewed.
    </li>
    </ul>
  </div>
  
    <p>Path to My Manager Taxa tab:</p>
    <ul>
    <li>Header: <strong>click Browse</strong> to open My Manager interface > click the <strong>Taxa tab</strong>
    </li>
    </ul>
    
        
	<img src="ManualImages/my_manager_taxa.png" alt="My Manager Taxa Tab" hspace="25"/>	
	<br /><br />
<h3>Features and Functions of the Taxa tab</h3>

	<p>Morpbhank users will catch on quickly due to the modular nature of the tools and icons. Note various highlighted areas of the above screen shot are briefly explained next.</p>
	
	<ul>
	
	<li><a href="<?php echo $config->domain; ?>About/Manual/manualHints.php" ><img src="ManualImages/feedback.png" alt="feedback link" align="middle" /></a>: please use this link to our automated feedback system.
	 We appreciate your comments so that we can continue to improve and enhance Morphbank. Clicking on <strong><font color="red">(Help)</font></strong> opens this Manual.</li>
	
	<li><a href="<?php echo $config->domain; ?>About/Manual/myManagerFeatures.php" ><strong>Keywords: </strong></a> Use the new <strong>enhanced</strong> search feature. Hold the mouse over the
	Keywords box to see an updated expanded list of fields the Keywords field searches.</li>
	
	<li><a href="<?php echo $config->domain; ?>About/Manual/myManagerFeatures.php" ><strong>Limit Search by:</strong></a> allows a user to view only the objects personally contributed/submitted AND/OR those from a particular group. Use the 
	<strong>Header Menu > Tools > Select Group > Choose group from sub-menu</strong> feature to change groups OR use the 
	<strong>Group</strong> link in the <strong>Header</strong> on the left side of the Main Screen.</li>
	
	<li><strong>Toggle Taxa/OTU:</strong><em> Two Morphbank features</em> are evident here. A user can look
	only at Taxon Names in Morphbank, or limit the display to <strong><strong><em>Operational Taxonomic
	Units (OTUs)</em></strong></strong> present in Morphbank. </li>
	
	<li><strong>Check box:</strong> Note the <img src="ManualImages/check_box.png"> to the left of each Taxon Id and Title. Use this
	feature to <strong>Select</strong> one or more Taxa which will then be <em>highlighted in green</em>. A particular action can now 
	be applied to all the Taxa selected at one time using the <strong>Select Mass Operation</strong> feature.</li>
	
	<li><a href="<?php echo $config->domain; ?>About/Manual/myManagerFeatures.php" ><strong>Select Mass Operation</strong></a>: After checking one or more Taxa with the <strong>check
	box</strong> feature, options in the drop-down of <strong>Select Mass Operation</strong> can be applied to all
	the Taxa checked. Examples include: <strong>Create new OTU</strong> or <strong>Copy to Existing OTU</strong>.
	<em>Specimens can also be used in the creation of an OTU or copied into an existing OTU</em>.</li>
	
	<li><strong>Submit</strong>: Click this button to carry out the <strong>Mass Operation</strong> selected.</li>
	
	<li><strong>Icons</strong>: The <strong>icons</strong> and their functions are explained fully in Morphbank 
	<a href="<?php echo $config->domain; ?>About/Manual/graphicGuide.php" >Guide to Graphic Buttons</a>. An abbreviated 
	explanation can be found on the <a href="<?php echo $config->domain; ?>About/Manual/myManagerAll.php" >All</a> page
	for My Manager. 
	
	<ul>
	<li>
	The <img src="<?php echo $config->domain; ?>style/webImages/infoIcon-trans.png" /> icon
	shows data details for a given object, in this case, a taxon Or an OTU.
	</li>
	
	<li><img src="<?php echo $config->domain; ?>style/webImages/edit-trans.png" />, the	<strong>Edit</strong> icon gives the contributor/submitter of a given
	taxon name, a link to the <a href="<?php echo $config->domain; ?>About/Manual/edit_taxon_name.php" ><strong>Edit Taxon Name</strong></a>
	 window to make any necessary changes. Users may Edit Taxon data only for any Taxa they've contributed/submitted if it's not yet published. <em>The name <strong>cannot</strong> be edited
	if it has been submitted to ITIS or has been used by others in the Morphbank system.</em>
	</li>
	
	<li>Morphbank's <img src="<?php echo $config->domain; ?>style/webImages/annotate-trans.png" /> Annotate icon gives any user the opportunity to leave a
	permanent comment about an object in Morphbank. In this case, a user may 
	<a href="<?php echo $config->domain; ?>About/Manual/annotate_taxon_name.php" ><strong>Annotate a taxon name</strong></a> by clicking on this
	icon for a given taxon name.
	</li>
	</ul>
	
	<li><strong>No. Specimens</strong>: If Specimens are in Morphbank with a particular Taxon Name,
	the <strong>No. Specimens</strong> link can be used to see these specimens. This will be a blue link (as
	in the above first Taxon Name) to the Specimen/s.
	</li>
	<br />
	<li>A user may also <img src="ManualImages/add_new_taxa_button.png"> from this My Manager page. Click on the button to open the Taxon Name Search.
	Click <a href="<?php echo $config->domain; ?>About/Manual/addTaxonName.php" >Add Taxon Name</a> for more detailed instructions.
	</li>
	</ul>
	
	<h3>Creating OTUs with My Manager</h3>: To group objects (in this case Taxa) together to create an OTU, ...
<div class="specialtext2">
	<ul><li>use the 
		<img src="ManualImages/check_box.png" alt="check box" /> <strong>check box</strong> seen just to the left
	of each Taxon Name and Id. <em>Each item checked will be highlighted green.</em>
	</li>
	<li>After checking all desired items,
	 go up to the <strong>Select Mass Operation</strong> drop-down
	and choose the desired action
	<br />(<strong>Create New OTU</strong> or <strong>Copy to Existing OTU</strong>).
	</li>
	<li>Click <img src="ManualImages/submit_button.gif"> to finish this task.</li>
	<li> Any <strong>OTUs</strong> created can be seen in this
	 <a href="<?php echo $config->domain; ?>About/Manual/myManagerTaxa.php" >Taxa</a> tab. Use the <strong>Toggle Taxa/OTU</strong>
	 radio buttons to limit the display to OTUs only, if desired.</li>
     <li>For any OTU, click the
	 <img src="<?php echo $config->domain; ?>style/webImages/infoIcon-trans.png" /> icon to see the objects in a particular
	 OTU.
     </li>
	</ul>
    Currently, there are 3 types of Morphbank Collections: a Collection of Images (and / or other objects), a Character Collection created to illustrate Character States for a defined Character, and an OTU Collection consisting of Specimens and Taxon Names -- designed to help a user describe operational taxonomic units (OTU)s.
</div>	

<a href="<?php echo $config->domain; ?>About/Manual/addTaxonName.php" ><h3>Add a Taxon Name to Morphbank</h3></a>
<br /><br />
<a href="<?php echo $config->domain; ?>About/Manual/edit_taxon_name.php" ><h3>Edit a Taxon Name in Morphbank</h3></a>
<br /><br />
		<a href="<?php echo $config->domain; ?>About/Manual/myManager.php" ><h3>Known Version Issues</h3></a>
		<br />
		<br />
		<a href="<?php echo $config->domain; ?>About/Manual/myManager.php" ><h3>Introduction to My Manager</h3></a>
			<br />
			<br />
			
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?php echo $config->domain; ?>About/Manual/myManagerCollections.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</tr>
				</div>
		
			<?php
// Finish with end of HTML
finishHtml();
?>
