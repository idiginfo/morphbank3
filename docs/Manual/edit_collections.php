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
<h1>Edit Collection</h1>
<div id=footerRibbon></div>
<br />
Edit Collection contains the previously entered collection data that can be 
edited by the owner (only available if the collection is <strong><em>not yet published.</em></strong>)   
 <div class="specialtext2">
Edit Collection is now accessed through the Collections tab of the new My Manager interface.

<ol>
<li>Select from the Header Menu <b>Tools > My Manager</b> and then click on the Collections tab.
</li>
<li>Or, Header Menu > <a href="<?php echo $config->domain; ?>About/Manual/browseC.php"><strong>Browse > Collections.</strong></a>
The old Collection Manager has been replaced with the My Manager Collections tab.
</li>
<li>Find your collections with <strong>Keyword search</strong> and/or <strong>Limit Search by</strong> Contributor.
</li>
<li>Click the <img src="<?php echo $config->domain; ?>style/webImages/infoIcon-trans.png" /> icon to open the Collection.
</li>
<li>You may wish to: Change the order of the objects and save the order, change the titles of some of the objects in the collection,
or perhaps delete objects from the collection.
</li>
<li>All changes must be followed by clicking on the update/submit button to register the 
change. 
</li>
</ol>
</div>

<br />To get complete instructions for editing Collections go to the 
<a href="<?php echo $config->domain; ?>About/Manual/browseImages.php#createCollection">Browse - Add Images to a Collection</a>
 or <a href="<?php echo $config->domain; ?>About/Manual/collections.php">Collections</a> areas of this manual. 
			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?php echo $config->domain; ?>About/Manual/edit_taxon_name.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
				</div>
		
			<?php
// Finish with end of HTML
finishHtml();
?>
	
