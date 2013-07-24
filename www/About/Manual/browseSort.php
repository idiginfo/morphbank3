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
		<h1>Browse - Sorting Search Results</h1>
<div id=footerRibbon></div>
<br />
After browsing any Morphbank object like images, specimens, views, localities, publications, and collections via the My Manager interface, the user will be presented with a list of sort criteria options for sorting the search results. These options vary for each Morphbank object. Check out the various sort criteria options on each My Manager tab found in the left side-bar.
<div class="specialtext3">
<strong>Example - Sorting Images</strong>: To sort the list of images after a search, select the Sort By criteria from the drop down list(s). The more criteria selected, (up to 3 levels) the more refined the browse will be. The resulting page will display the images list with the initial Sort By option grouped together first, followed by groups of any of the other sort criteria selected.
</div>
<img align="left"  src="ManualImages/browse_sort.png" hspace="15"   />
Sort criteria for images are listed next.
<br />
<br />
<br />
<br />
<ul>
<li><strong>Image id</strong>: unique Morphbank-issued id for an image
</li>
<br />
<br />
<li><strong>Date To Publish</strong>: the date an image in Morphbank appears to the general public.
</li>
<br />
<br />
<li><strong>Contributor</strong>: person responsible for contributing the data and images to Morphbank. Also referred to as the User.
</li>
<br />
<br />
<li><strong>Group Id</strong>: Morphbank Groups (like all Morphbank objects) have Ids. A search result may contain objects from several groups. This <em>sort criteria</em> would order the objects by Group Id.
</li>
<br />
<br />
</ul>
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />			
<p>Use the <img src="ManualImages/reset.gif" alt="reset button" /> to clear the Keyword Search and Sort By boxes of all criteria.</p>
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?php echo $config->domain; ?>About/Manual/myManagerAll.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
	<?php
//Finish with end of HTML	
finishHtml();
?>


	
