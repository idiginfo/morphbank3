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
		<h1>My Manager - important features</h1>
<div id=footerRibbon></div>
		<td width="100%">		
	<p>There are 4 noteworthy features common to each of the tabs in the Morphbank My Manager interface.
	
<ol>
<li><strong>Keyword </strong>Search makes it easier to find and collect objects
</li>
<li>A useful <strong>Limit Search by</strong> option is available. After a search, one can limit the resulting set to show:
	<ul>
	<li>only those objects they have contributed
	</li>
	<li>only those objects they have submitted
	</li>	
	<li>only those objects uploaded as part of the current group the user is logged in as a member of
	</li>
	<li>only those objects uploaded by any group the user belongs to
	</li>
	</ul>
</li>
<li><strong>Select Mass Operation</strong>: with this feature, a user can select more than one object and then carry out a task
on all those objects at one time. For example, 30 images for a user are private and the user needs them to be public. The user
can select all 30 images, go to Select Mass Operation, choose Publish Now. The date-to-publish is updated to the current date on all 30 images at one time.
</li>
<li><strong>Sort by</strong>: after a search, use this feature to order the result set by Morphbank Id, Date To Publish, Contributor or Group Id.
</li>
</ol>
<p>Examples of each feature are given below this screen shot.</p>
<img src="ManualImages/my_manager_common_features.png" alt="My Manager Common Features">
<br />
<h3>Keyword Search</h3>
<p>Keyword Search is designed to be fast. Querying only one table in the database, Keyword Search makes searches efficient. For each tab in My Manager, the fields queried are unique to that module. To see the searchable fields in each tab of My Manager, <em>hover over the Keywords box.</em> A yellow stickie will appear listing the fields searched as shown next.
</p>
<img  align="texttop" src="ManualImages/keyword_hover.png" />

<div class="specialtext3">
<h3>Sample Keyword Searches</h3>
<ol>
<li>Keyword Search = <strong>Insecta</strong>, the search will return all images associated with the Taxon <strong>Insecta</strong>.
</li>
<li>Keyword Search = <strong>Insecta <em>drosophila</em></strong>, the search will return all images that are both <strong>Insecta</strong> AND <em>drosophila</em>.
</li>
<li>Keyword Search = <strong>Insects -<em>drosophila</em></strong>, returns all <strong>Insecta</strong> <em>minus</em> the <em>drosophila</em> images.
</li>
</ol>
</div>
<div class="specialtext2">All Keyword Searches are <strong>Boolean AND</strong>. 
Each term entered in Keyword Search is automatically <strong>wild-carded*</strong> meaning a partial word will bring up any record that contains a word beginning with that string. For example, if a user enters <strong>Magnoli</strong> in the Keyword Search, the search is really <strong>Magnoli*</strong> and returns all records with <strong>Magnolia</strong> as well as <strong>Magnoliaceae</strong>. 
</div>

<h3>Limit Search by</h3>
<p>Easy to use, simply check the desired box and click Go. This feature may be used alone or in combination with a Keyword search. For example, in the Images tab of My Manager, upon checking <strong>Contributor</strong> and clicking Go, a Morphbank user will see only those images they have have contributed. If, in addition, they add a search term to Keywords, and leave the <strong>Contributor</strong> box checked, the search will be: Find all objects that have the search term AND have been contributed by this user.</p>
<div class="specialtext3">
<strong>Limit Search by:</strong>
<ul>
<li><strong>Contributor</strong>: results show only those objects the logged in user has contributed to Morphbank.</li>
<li><strong>Submitter</strong>: results show only objects the logged in user has submitted to Morphbank.</li>
<li><strong>Current Group</strong>: results show only objects the group has contributed to Morphbank.</li>
<li><strong>Any Group</strong>: shows all objects contributed by Any Group the user belongs to in Morphbank.</li>
</ul>
</div>
<p><strong>Note Well. </strong>Every Morphbank Object (Image, Specimen, View, Locality, Annotation, Collection, Taxon, Publication) uploaded to Morphbank has a Contributor and Submitter (they can be the same person). Every person with a Morphbank account has their own group to which they can <a href="<?php echo $config->domain; ?>About/Manual/modifyGroup.php">add more members</a>. Any additional Groups needed are currently added by mbadmin. For more about Morphbank Groups, read <a href="<?php echo $config->domain; ?>About/Manual/userPrivileges.php">Users and Their Privileges</a> and how to <a href="<?php echo $config->domain; ?>About/Manual/selectGroup.php">Select a Group</a>.</p>

<h3>Select Mass Operation</h3>
<p>The user can select more than one object using the box to the left of the Object title (see the green highlighted entries above). Having selected
	several (or many) objects, choose the action to be performed on those objects from the <strong>Select Mass Operation
	</strong> drop-down. With this method, a user might change the date-to-publish on many objects at once. Or perhaps, a user
	might create a collection. Step-by-step instructions follow:</p>

	<div class="specialtext3">
	<strong>Select Mass Operation</strong>: Drop-down choices vary with each <strong>My Manager</strong> tab. 
	<ol>
	<li>Select one or more objects using the <img src="ManualImages/check_box.png" alt="check" /> box to the left of the Object title (see the green highlighted image objects in the first screen shot above).
	</li>
	<li>To select all the given objects on a page, use the <img src="ManualImages/check_all.png" alt="Check All button" >.
	</li>
	<li><em>Choose the action to perform on all selected objects</em>. In the above example, a user might choose <strong>Create New collection</strong>
	or <strong>Copy (these checked images) to an existing collection</strong>. If the images were contributed by this user and were private, the user could make them public by choosing <strong>Publish Now</strong>.
	</li>
	<li>Click <img src="ManualImages/submit_button.gif" alt="Submit icon"> to carry out the task selected above.
	</li>
	</ol>
	</div>

<h3>Sort by</h3>
<p>After searching any Morphbank object like images, specimens, views, localities, publications, and collections via the My Manager interface, the user will be presented with a list of sort criteria options for sorting the search results. These options vary for each Morphbank object. Check out the various sort criteria options on each My Manager tab found in the left side-bar.
</p>
<div class="specialtext3">
<strong>Example - Sorting Images</strong>: To sort the list of images after a search, select the Sort By criteria from the drop down list(s). The more criteria selected, (up to 3 levels) the more refined the browse will be. The resulting page will display the images list with the initial Sort By option grouped together first, followed by groups of any of the other sort criteria selected.
</div>
Sort criteria for images:
<ul>
<li><strong>Image id</strong>: unique Morphbank-issued id for an image
</li>
<li><strong>Date To Publish</strong>: the date an image in Morphbank appears to the general public.
</li>
<li><strong>Contributor</strong>: person responsible for contributing the data and images to Morphbank. Also referred to as the User.
</li>
<li><strong>Group Id</strong>: Morphbank Groups (like all Morphbank objects) have Ids. A search result may contain objects from several groups. This <em>sort criteria</em> would order the objects by Group Id.
</li>
</ul>
<p>Use the <img src="ManualImages/reset.gif" alt="reset button" /> to clear the Keyword Search and Sort By boxes of all criteria.</p>
 	
	    <br />
		<a href="<?php echo $config->domain; ?>About/Manual/myManager.php" ><h3>Known Version Issues</h3></a>
		<br />
		<br />
		<a href="<?php echo $config->domain; ?>About/Manual/myManager.php" ><h3>Introduction to My Manager</h3></a>
		<br />
		<br />
			
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?php echo $config->domain; ?>About/Manual/myManagerAll.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</tr>
				</div>
		
			<?php
// Finish with end of HTML
finishHtml();
?>
