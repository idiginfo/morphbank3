<?php 
	//global $includeDirectory, $dataDirectory, $imgDirectory;
	global $domainName;
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>
	
		<div class="mainGenericContainer" width="100%">
		<!--change the header below -->
		<h1>Browse - Collections</h1>
<div id=footerRibbon></div>
<br />			
A collection is a group of specimen images that are assembled from the
Morphbank database by Morphbank members for the purpose of manipulating
(e.g. rearranging the order, editing, and/or annotating, etc.) and storing the
images for future use. By selecting the <strong>Browse - Collections</strong> option, the user will
be presented with a list of all published (released by the creator) collections that
are registered in the Morphbank database.
<br />
<br />
<img src="ManualImages/browse_collections.png" />
<div class="specialtext3">
Note: In this newly released (beta) version that includes the <strong>My Manager User Interface</strong> 
users can create collections that include <em>any Morphbank object.</em> The
collection can be of one object type (e.g. all publications) or many (e.g. images, publications, specimens and a view).
So one can create a mixed collection of publications, annotations, specimens etc that can be shared with other users! In
addition, users can now create and define characters with their associated states in a <em>character collection.</em> 
</div>
In <strong>Browse - Collections</strong>, users can:
<ul>
<li>see all public collections and copy them if desired by clicking the <img src="../../style/webImages/copy-trans.png" /> icon</li>
<li>view the images in these collections by clicking the <img src="../../style/webImages/camera-min16x12.gif" /> icon</li>
<li>view the images in these collections by clicking the <img src="../../style/webImages/infoIcon.png" /> icon for the Collection - Single Show</li>
<li>from the <img src="../../style/webImages/camera-min16x12.gif" /> or 
<img src="../../style/webImages/infoIcon.png" /> Collection - Single Show, email the creator of the 
collection with the <img src="../../style/webImages/envelope.gif" /> icon</li>
<li>if logged-in, edit an unpublished personal collection by clicking the <img src="../../style/webImages/edit-trans.png" /> icon</li>
<li>keyword search and sort the collections</li>
</ul>
<h3>Browse - Collections by Keywords and Sort the Results</h3>
<br />
<br />
To display a list of collections based on a keyword(s) search, type the keyword(s) 
in the box and select <strong>Search</strong>. For example, to browse for all collections
 pertaining to <strong>HymAtol</strong> (group name),
<strong>Sharkey</strong> (username); type in <strong>HymAtol Sharkey</strong> and select
 <strong>Search</strong>.
 <br />
<br />
<img src="ManualImages/browse_collections_search.png" />
<br />
<br />
Sort the list of collections
To sort the list of collections, select the Sort By criteria from the drop down
list(s). The more criteria selected, (up to 3 levels) the more refined the browse
results. The resulting page will display the collection list with the
initial Sort By option grouped together first, followed by groups of any of the
other sort criteria that was selected.
<br />
<br />
The Sort feature of Morphbank is explained in detail in
 <a href="<?echo $domainName;?>About/Manual/browseSort.php">Browse - Sort Search Results</a>
<br />
<br />
Sort criteria options for Browse-Collections include:
<ul>
<li><strong>Collection id</strong>: Unique Morphbank-issued identifier for a collection.
</li>
<li><strong>Username</strong>: Name of the person who created the collection.
</li>
<li><strong>Group name</strong>: Name of the group to which the collection creator belongs.
</li>
<li><strong>Publication</strong>: The external publication that references a Morphbank collection.
</li>
<li><strong>Collection name</strong>: The name given to the collection by the collection's creator.
</li>
<li><strong>Number of images</strong>: pertaining to one collection</li>
</ul>
			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $domainName;?>About/Manual/browseP.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $domainName;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</div>
		<?php
//Finish with end of HTML	
finishHtml();
?>
	
