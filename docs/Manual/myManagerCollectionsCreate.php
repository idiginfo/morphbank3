<?php 
	//global $includeDirectory, $dataDirectory, $imgDirectory;
	global $domainName;
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>
	
		<div class="mainGenericContainer">
		<!--change the header below -->
		<h1>Collections - How To</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
<div class="specialtext2">
<a href="<?echo $domainName;?>About/Manual/Movies/Makecollection.avi" target='_blank'><img src="ManualImages/movieicon.jpg" /></a>  How to <strong>Create a Collection</strong> from My Manager: <a href="<?echo $domainName;?>About/Manual/Movies/Makecollection.avi" target='_blank'>video</a>
</div>
<p>From any tab in the <strong>My Manager</strong> interface, users can select Objects to include in a
 collection. The collection can be kept private or made public with the <em>date-to-publish</em> feature. This
 section of the manual illustrates how to gather several objects into a collection.</p>

<ul>
<li>In this version of Morphbank <em><strong>a collection may include images only or images and any
other objects</strong></em> such as: annotations, localities, publications, specimens, or views.
</li>

<li>A <strong><em>Character Collection</em></strong> can be created, shared and stored
in this version of Morphbank. A Character Collection allows a user to define a character/entity
and select objects in Morphbank to illustrate the defined States for that character. This type of Collection is made up of
Images and defined Character states.
<!--In the next version of
Morphbank (v 2.8), users will be able to access <em>My Matrices</em> to create matrices with the Character information.
-->
</li>
<li><strong>OTU Collections</strong> are created by placing Specimen records &amp; Taxon records in Morphbank into a Collection for the purpose of illustrating <em>the concept of a possible new classification</em>, or I <em>describing a new taxonomic grouping</em>. OTU collections are found in the Taxa tab.
</ul>
<h3>Creating a Collection of Morphbank Objects</h3>
<p>After logging in, go to the desired tab in the <strong>My Manager</strong> interface. The <strong>Images
tab</strong> is shown next to illustrate making an Image collection. Three images are selected (highlighted) using the 
<img src="ManualImages/check_box.png" alt="check box" /> <strong>check box</strong> just to the left of the
Image Id and Taxon Name.
</p>

<img src="ManualImages/my_manager_create_collection.png" hspace="30" />
<br />
<p>From <strong>Select Mass Operation</strong>, choose the action to apply to the checked objects. In this
example, <strong>Create New Collection</strong> is selected. Click on the <strong>Submit</strong> button to create the collection and
give the Collection a name when prompted (see next screen shot).
<br />
<br />
<img src="ManualImages/my_manager_collection_title.png"hspace="30" />
</p>
<p>Add more images if desired or go to another tab in My Manager and add other objects (views, localities, publications,
etc,) to the same collection to make a Mixed Collection. The Collection Title <strong>Hermetia Larva</strong> will now appear in the <strong> Select Mass Operation</strong> drop-down under <strong>Copy to existing collection</strong> in any My Manager Tab where this type of Collection is supported.
</p>
<p>
To View the Collection, go to the <strong>Collection tab</strong> in My Manager. Find the collection using the <strong>Keyword Search</strong>. Putting in the Contributor name finds all collections by that Contributor. Searching by the Collection Title also finds the desired collection/s. Click the <img src="../../style/webImages/infoIcon.png" alt="information icon" /> next to the collection created to open it. For this collection, a user would see:
</p>
<img src="ManualImages/sample_collection.png" alt="sample collection" hspace="30" />
<p>In the image above, note the modularity again. Within a collection, a user can carry out actions on the objects by using 
the <img src="ManualImages/check_box.png"> with each image. The actions are listed in the left sidebar as <strong>Tools</strong>. Two examples,

<ul>
<li>click a check box for an image, then select <strong>Delete Checked Objects</strong> to remove the image from the collection.
<li>use the <img src="ManualImages/check_all.png"> button to select all the images, choose <strong>Annotate Checked Objects</strong>
from <strong>Tools</strong>. In this way, a user can do a <strong>Mass Annotation</strong> that applies to all the checked images.</li>
</ul></p>

<p>Note that a user can modify a given collection as long as it is not <strong>published.</strong></p>

<p>From the <strong>My Manager > Collections tab</strong> each collection is represented by an image chosen by the collection's creator.
 The user creates the collection, uses the check box to pick the representative image, and clicks from Tools -- <strong>Set Collection Thumbnail</strong>.</p>
	<a href="<?echo $domainName;?>About/Manual/FSI.php"><h3>FSI Viewer</h3></a>: Morphbank utilizes
		 a proprietary viewer with its unique capabilities to increase the value
	of the photograph for the user. Clicking on any image thumbnail in a collection will
	open the image in the FSI Viewer. 
	    <br />
		<br />
		<a href="<?echo $domainName;?>About/Manual/myManager.php" ><h3>Known Version Issues</h3></a>
		<br />
		<br />
		<a href="<?echo $domainName;?>About/Manual/myManager.php" ><h3>Introduction to My Manager</h3></a>
		<br />
			<br />

			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $domainName;?>About/Manual/myManagerCollectionsSample.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $domainName;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</tr>
				</div>
		
			<?php
// Finish with end of HTML
finishHtml();
?>