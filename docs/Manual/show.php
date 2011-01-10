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
		<h1>Morphbank Show</h1>
<div id=footerRibbon></div>
		<td width="100%">
			
<p>Morphbank is a large complex relational database with an intricate matrix of inter-related
tables. Easily finding and <strong><em>showing</em></strong> information in Morphbank is a necessity. Since each Morphbank object can be uniquely identified by its <em>Object Id</em> number alone, the Morphbank developers have created a simple method to reference any Morphbank object and the data associated with it through this unique number. This method is called <strong><em>Show</em></strong>. 
</p>
<p>Click on the question of choice to jump down the page.
<ul>
<li><h3><a href="#showURL">What does the Morphbank Show and URL look like?</a></h3></li>
<li><h3><a href="#objectIds">Where are object ids found within Morphbank?</a></h3></li>
<li><h3><a href="#whichOb">Which Morphbank objects have this <em>Show</em> feature?</a></h3></li>
<li><h3><a href="#whereShow">Where can the Show feature be accessed within Morphbank?</a></h3></li>
</ul>
</p>

<h3><a name="showURL">What does the Morphbank Show and URL look like?</a></h3>
<!--<a href="<?echo $config->domain;?>Show/index.php?id=224074" target="_blank">http://morphbank.net/Show/index.php?id=224074</a>
-->
<a href="<?echo $config->domain;?>Show/?id=224074" target="_blank">http://morphbank.net/Show/?id=224074</a>
is an example of the URL. Click it to see what a Show referenced image object looks like in Morphbank version 2.8. 
Note this example is an <strong>Image Record Show</strong>.
The other objects (Specimen, Image, View, Locality, Collection, Annotation, Publication) in Morphbank have the same display and referencing feature. To find these objects, go to the <strong>Header Menu > Browse ></strong> and choose from the Morphbank objects in the <strong>drop-down menu</strong>.
<br />
<div class="specialtext3">
Early in the requirements phase of the Morphbank project, scientists expressed a
desire to cite the Morphbank web site and display images and data in journal,
conference, and workshop research papers. Other researchers expressed a
desire to reference data in Morphbank using external URL addresses. Rather
than performing complex queries each time the data is requested, Morphbank
developers came up with <strong>Show </strong>as a more efficient method to display this data.
</div>
<div class="specialtext3">
Every Morphbank object (image, specimen, view, locality, publication, collection, user, group, annotation, news, taxa)
 that can be referenced externally, is identified by a
  unique integer string (up to 18 digits) and other pertinent information.
  Collectively the information listed below is cataloged in the Morphbank
baseObject table:
<ul>
<li>Morphbank Identifier</li>
<li>Object type (which table the record is in)</li>
<li>User Id (Id of the user who created the object)</li>
<li>Group Id (references the group that owns the object)</li>
<li>Date the object was created</li>
<li>Date the object was last modified</li>
<li>Date the object will be published</li>
<li>Description of the object</li>
<li>Name of object (example, Collection Name, Annotation Name, Taxon Name...)</li>
<li>Person who submitted the object (if different from the owner)</li>
<li>Logo for the user, if supplied</li>
</ul>
</div>

<br />
<h3><a name="objectIds">Where are object ids found within Morphbank?</a></h3>
When browsing / searching via <a href="<?echo $config->domain;?>About/Manual/myManager.php" target="_blank">My Manager</a> tabs, the id is displayed with each object. 
<br />
<br />
<img src="ManualImages/specimen_object_id.png" /> 

<br />
<br />
<h3><a name="whichOb">Which Morphbank objects have this feature?</a></h3>As of the writing of this manual,
Specimen, Image, View, Locality, Annotation, Collection, User, Group, 
Publication, Taxa, OTUs, Characters and News have the Show capability. 
<br />
<br />
<h3>The data in these objects is quite different. How does Morphbank know
    how to show the data? </h3> Each Morphbank object that can be displayed using
    the Show function has its own unique display module. Morphbank reads the
    id number, looks up the type of object in the baseObject table, and calls the
    appropriate module.</p>
<p><h3>What Morphbank objects can be seen? </h3> If a user is not logged-in, only objects
    that have been released (&quot;published&quot;) can be seen through Show. Users of Morphbank
    who wish to share their data in Morphbank through Show should ensure that
    the data has been released. Users who are logged into Morphbank can view
    any released object, objects they own, or objects owned by the group they
    are logged-in with.
	</p>

<p>
<h3><a name="whereShow">Where can the Show feature be accessed within Morphbank?</a></h3>
	<ul>
	<li>Show information is displayed on a popup screen.</li>
	<li>Show can be accessed anywhere in the My Manager user-interface through the results of a Browse or Keyword Search by clicking on any<img src="../../style/webImages/infoIcon.png" /> Information icon, or on one of the 
	thumbnail images in the results list.
	</li>
	<li>Also, selecting the Object id where it is listed in blue type or on another Show screen will open the Show.
	</li>
	<li>In the <a href="<?echo $config->domain;?>About/Manual/myManagerAnnotations.php" target="blank">My Manager > Annotations tab</a>, 
	clicking on the blue type (next to a published Annotation id), the Single Show Annotation Record is displayed.
	</li>
	</ul>
</p>
<p>
<h3>Example: Single Show</h3>
In this example, the single show popup was accessed by clicking on the thumbnail image of 224074.
</p>
<br />
<img src="ManualImages/image_record_show2.png" />
<!--<object width="550" height="400">
<param name="movie" value="ManualImages/image_record_show2.swf">
<embed src="ManualImages/image_record_show2.swf" width="550" height="400">
</embed>
</object>-->
<br />
Tag Descriptions<br />
<ol>
<li>Contributors/Users/Groups may associate a logo with the objects they upload to Morphbank.
To submit a Logo for your objects go to: <strong>Header Menu > Tools > Account Settings >
 User Logo</strong> and upload the logo of your choice. You may change it as you like. If you 
 would like the same logo for a large set of objects, contact mbadmin at <strong>mbadmin 
 <font color="blue">at</font> scs <font color="blue">dot</font> fsu <font color="blue">dot</font>  edu</strong>
 </li>
<li>Left click on image to bring up the FSI viewer by Neptune Labs. For
complete information on this process refer to the <a href="<?echo $config->domain;?>About/Manual/FSI.php">FSI Viewer</a> section of this
manual. Or click on the FSI Viewer link seen just below the image.
</li>
<li>Click once on any of the areas in blue type will lead the user to
additional information. In this case, information associated with the view of this image appears. By clicking on
View 136058 the single show of the view record will display in a popup
screen. As in other show screens, clicking on the blue highlighted type will
display additional information. See next:
<br /><br />
<img src="ManualImages/view_single_show2.png" />
</li><br />
<br />
<li>Click on the FSI Viewer link seen just below the image. This will bring up the FSI viewer by Neptune Labs. For
complete information on this process refer to the <a href="<?echo $config->domain;?>About/Manual/FSI.php">FSI Viewer</a> section of this
manual.
</li>
<li>Click on these image links to download the images in the version of choice.</li>
<li>Information pertaining to the specimen associated with this image.
Click on the Specimen Id to access the Single show of the Specimen (shown next). The
Specimen Show will present the user with details about the specimen as well
as inform about associated collections or published determination
annotations associated with the specimen. Included at the bottom of the
Specimen Single Show is an image list of associated images for this
specimen (if any). The total number of images is listed and can be viewed through
browse-specimens by clicking on the camera icon. Double clicking on a
thumbnail image in this area will bring up the Single Show for the image.
<br />
<br />
<img src="ManualImages/specimen_single_show2.png" />
</li><br />
<li>Information about the locality of the image. Clicking on the Locality ID
will access the Single Show of the Locality Record displayed in a popup
screen<br /><br />
<img src="ManualImages/locality_single_show2.png" />
<br /><br />
</li>
<li>List of published determination annotations associated with this image.
Clicking on the information button will access the annotation. Unpublished
determination annotations will not be listed. Click on Add Annotation to add one, if desired.
</li>
<li>Scrollable list of external links associated with this image. Clicking on
the blue highlighted type will hyperlink to the appropriate external link.
</li>
</ol>


<br />
<br />		
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/zoomingViewer.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</div>
			<?php
//Finish with end of HTML	
finishHtml();
?>	

	
