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
		<h1>Publications tab</h1>
<div id=footerRibbon></div>
		<td width="100%">		
<p>	My Manager's Publication tab is a tool designed to help the scientist with a Morphbank account manage 
	publications in Morphbank. The logged-in user can limit search results to show any publications in Morphbank
	or some subset of publications they've personally contributed to the database.
</p>
<p>Paths to Publication tab:</p>
<ul>
<li>From <a href="http://www.morphbank.net">www.morphbank.net</a>
</li>
<li>Header Menu: hover over <strong>Browse</strong> to open drop-down > <strong>click Publication</strong> or
</li>
<li>Header Menu: <strong>click Browse</strong> to open My Manager > <strong>click Publication tab</strong>
</li>
<li>To add or edit Publications, Morphbank users will need to login.
</li>
</ul>	
   
 	
		<img src="ManualImages/my_manager_publications.png" />
		<br />
<h2>Features and Functions of the Publications tab in My Manager</h2>
	<ul>

	<li><strong>Feedback</strong>: please use this link to our automated feedback system. 
	We appreciate your comments so that we can continue to improve and enhance Morphbank.</li>
	
	<li><strong>Keywords</strong>: This is an updated search feature in Morphbank.
	A mouse-over will indicate the fields the Keywords Search function utilizes.</li>
	
	<li><strong>Sort by</strong>: See drop down for sorting search criteria.</li>
	
	<li><strong>Limit Search by</strong>: allows Morphbank Users wishing to see only those objects
	(in this case, Publications) they have contributed can click the appropriate box to limit the search results. 
	Results may also be restricted to any particular group the user selects. This functionality makes collecting, annotating 
	and editing select items easy and quick.</li>
	
	<li><strong>Select Mass Operation</strong>: The drop-down choices will vary depending the My Manager Tab open. The user can select more
	than one object/publication using the box to the left of the <strong>Publication title</strong> (see <strong>Publication [197043]</strong>... above).
	 Having selected several (or many) publications, choose the action to be performed on those publications from the <strong>Select Mass Operation
	</strong> drop-down. With this method, a user may create a collection of Publications or add Publications to an existing Collection of 
	any Morphbank objects.</li>
		
		<ul>
		<li>Every object in Morphbank has a unique identifier (Id). These numbers are useful for searches.
		An example would be <strong>197043</strong> which is the <strong>Publication Id</strong> that will point directly to the Publication above.</li>
		</ul>
	
	<li><strong>Icons</strong>: Click to jump to the 
	<a href="<?echo $config->domain;?>About/Manual/graphicGuide.php" target="_blank" >guide to Morphbank graphics</a> for a thorough
	overview.</li>
		<ul>
		<li>In Morphbank, the <img src="<?echo $config->domain;?>style/webImages/infoIcon-trans.png" />
	 	<strong>information</strong> icon opens a feature called <strong>Single Show</strong> that varies with
	 	the object. In general, one sees data about the object; in this case, the Publication Record Show opens.
	 	</li>
	 
	 	<li>Clicking the 
	 	<img src="../../style/webImages/edit-trans.png" /> <strong>edit icon</strong> opens the original screen where the user
	 	entered the Publication information. The user who contributed the Publication data may edit the data here if needed.
	 	Complete instructions on this area can be found in the 
	 	<a href="<?echo $config->domain;?>About/Manual/edit_publications.php">Edit Publication</a> section.</li>
		</ul>
		</ul>

	<h3>Creating Collections of Publications and/or other Objects in Morphbank</h3>
<div class="specialtext3">
Using the 
<img src="ManualImages/check_box.png" /> <strong>check box</strong> next to any <strong>Object Id </strong>in Morphbank, items are selected
for inclusion in: a <strong>New or Existing Collection</strong>. In the <strong>Publications tab </strong>of My Manager, one
can create a New Collection of Publications, or perhaps Add Publications to an Existing Collection containing a variety of objects. The
<strong>Check All</strong> button allows a user to select all the objects on a given page for inclusion in a Collection. Then,
one uses the <strong>Select Mass Operation: Create New Collection or Copy to existing collection</strong> and <strong>clicks
Submit</strong>. The created collection will appear in the <strong>Select Mass Operation drop-down</strong> of the other My Manager 
tabs so that more objects can be added to the same collection, if desired.
</div>

<h3>Clicking the <img src="<?echo $config->domain;?>style/webImages/infoIcon-trans.png" />
 <strong>info icon</strong>
opens the <strong>Publication Record (Single) Show</strong> </h3>
<br /> In this case, the <img src="<?echo $config->domain;?>style/webImages/infoIcon-trans.png" />
associated with <strong>Publication Id 197041</strong>
<img src="ManualImages/publication_record_show.png" hspace="20" vspace="15" />
<p>
<h3>Add a new Publication</h3>: Header Menu > <strong>Tools > Submit > Publication </strong>
will take the user to the <strong>Add Publication</strong> screen where the user can add more
Publications to Morphbank. Directions for this process are located in <a href="<?echo $config->domain;?>About/Manual/uploadSubmitPublication.php">
Submit Publication.</a>
</p>
			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/browseTaxonH.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</tr>
				</div>
		
			<?php
// Finish with end of HTML
finishHtml();
?>
