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
	
		<div class="mainGenericContainer">
		<!--change the header below -->
		<h1>Annotations tab - My Manager</h1>
<div id=footerRibbon></div>
<p>
The <strong>My Manager > Annotations Tab</strong> offers the user a list of all the annotations that have
been created under the current user name as well as all other published annotations in the system.
There is no limit on the number of annotations a user may have. Use the <strong>Limit Search by:</strong>
 or <strong>Keywords</strong> search features to display a select group of annotations.
</p>
<p>Paths to <strong>Annotations tab</strong>:</p>
<ul>
<li>Header Menu: <strong>Browse</strong> to reveal drop-down > click <strong>Annotation</strong>
</li>
<li>Header Menu: click <strong>Browse</strong> to open My Manager > click <strong>Annotation tab</strong>
</li>
</ul>

<img src="ManualImages/my_manager_annotation_tab.png" hspace="20" vspace="10" />
<br />
<h3>Features and Functions of the Annotations Tab of My Manager</h3>

<ul>

<li><strong>Feedback</strong>: please use this link to our automated feedback system. We appreciate your comments so that 
	we can continue to improve and enhance Morphbank.</li>
	
	<li><strong>Keywords</strong>: This is an updated search feature in Morphbank. A mouse-over will indicate the fields the Keywords search function
	utilizes.</li>
	
	<li><strong>Sort by</strong>: Also a feature of the prior version of Morphbank, but with new choices in the drop down for sorting search results.</li>
	
	<li><strong>Limit Search by</strong>: Users wishing to see only those objects they
	have contributed can click the appropriate box to limit the search results. Results may also be restricted to any particular
	group the user selects. This functionality makes collecting, annotating and editing select items easy and quick.</li>
	
	<li><strong>Select Mass Operation</strong>: The drop-down choices will vary depending the My Manager Tab open. The user can select more
	than one object using the box to the left of the Object title (see <strong>General Annotation [192650] Note variation</strong>... above). Having selected
	several (or many) objects, choose the action to be performed on those objects from the <strong>Select Mass Operation
	</strong> drop-down. With this method, a user might change the date-to-publish on many objects at once. Or perhaps, a user
	might create a New Collection of Annotations.
	
	
	<ul><li>Every object in Morphbank has a unique identifier (Id). These numbers are useful for searches.
			An example would be <strong>192650</strong> which is the <strong>General Annotation Id</strong> that
			will point directly to the Annotation above labeled: <strong>Note variation in ...</strong></li></ul>
	
	</li>
	<li><strong>Icons</strong>: Click to jump to the 
	<a href="<?echo $config->domain;?>About/Manual/graphicGuide.php" target="_blank" >guide to Morphbank graphics</a> for a thorough
	overview.
		<ul>
	
	<li>In Morphbank, the <img src="<?echo $config->domain;?>style/webImages/infoIcon-trans.png" />
	 <strong>information</strong> icon opens a feature called <strong>Single Show</strong> that varies with
	 the object. In general, one sees metadata about the object; in this case, the Annotation Record Show opens. Note once
	 the Annotation Record is open, a user can click on <strong>Add Annotation</strong> to add another comment to the
	 same object.</li>
	 <li>Clicking the 
	 <img src="../../style/webImages/edit-trans.png" /> <strong>edit icon</strong> opens the original screen where the user
	 entered the Annotation information. If the Annotation is not yet published, the annotator may make changes here.
	 Complete instructions on this area can be found in the 
	 <a href="<?echo $config->domain;?>About/Manual/annotationEdit.php">Edit Annotation</a> section.</li>
	
	<li>Use the  <img src="<?echo $config->domain;?>style/webImages/calendar.gif" /> <strong>calendar</strong>
	icon  to easily change the
	date to make an object visible to all who use Morphbank or extend the time the object remains private.</li>
	<p>Users may change the date-to-publish to <strong>today</strong> by clicking on the "Publish now" 
	link for any of their own objects or click on the date and change the date on a calendar pop-up.</p>
	
	<li>With <img src="<?echo $config->domain;?>style/webImages/delete-trans.png" />, the user may <strong>delete
	</strong> an object in Morphbank (only available if the object is not published).</li>
		</ul>
	<li><strong>Annotation type</strong>: Note the types of Annotations seen in the above screen shot (Determination,
	General and Taxon Name). There are currently five types of annotations possible:
<strong>Determination</strong>, <strong>General</strong>, <strong>Legacy</strong>, 
<strong>.XML </strong> and <strong>Taxon Name</strong> (see <a href="<?echo $config->domain;?>About/Manual/annotation.php">Types of Annotations</a>).</li>
	</li>
</ul>

<h3>More Annotation Topics</h3><br />
<p><a href="<?echo $config->domain;?>About/Manual/annotation.php" target="_blank"><strong>What is an Annotation?/ Types of Annotations</strong></a>
<br />
<a href="<?echo $config->domain;?>About/Manual/annotationAdd.php" target="_blank"><strong>How and Where to Add an Annotation</strong></a>
<br />
<a href="<?echo $config->domain;?>About/Manual/annotationEdit.php" target="_blank"><strong>Editing an Annotation</strong></a>
<br />
<a href="<?echo $config->domain;?>About/Manual/annotationShow.php" target="_blank"><strong>What does an Annotation Single Show look like?</strong></a>
<br />
<a href="<?echo $config->domain;?>About/Manual/annotate_taxon_name.php" target="_blank"><strong>How to Annotate a taxon name</strong></a>
</p>

<h3>Creating Collections of Annotations</h3>
<div class="specialtext3">
Using the 
<img src="ManualImages/check_box.png" /> <strong>check box</strong> next to any <strong>Object Id </strong>in Morphbank, items are selected
for inclusion in: a <strong>New or Existing Collection</strong>. In the <strong>Annotations tab </strong>of My Manager, one
can create a New Collection of Annotations, or perhaps Add Annotations to a Collection containing a variety of objects. The
<strong>Check All</strong> button allows a user to select all the objects on a given page for inclusion in a Collection. Then,
one uses the <strong>Select Mass Operation: Create New Collection or Copy to existing collection</strong> and <strong>clicks
Submit</strong>. The created collection will appear in the <strong>Select Mass Operation drop-down</strong> of the other My Manager 
tabs so that more objects can be added to the same collection.
</div>


<h3>Clicking the <img src="<?echo $config->domain;?>style/webImages/infoIcon-trans.png" />
 <strong>info icon</strong>
opens the <strong>Annotation Record (Single) Show</strong> </h3>
<br />
<br />
<img src="ManualImages/annotation_record_show.png" hspace="20" vspace="15" />


<ul>

<li><strong>Object id</strong>: This represents the identifying
number of the object (image, specimen, etc.) being annotated. In the above <strong>Annotation Record
Show</strong> Clicking on the <strong>Image Id [142755]</strong>will take the
user to the <strong>Image Record Single Show</strong> screen that displays the record which contains the image and
related information.
</li>
<li><strong>Type of object being annotated</strong>: Currently, only images, specimens and taxon names
will have annotation options but in future versions, users will be able to annotate any Morphbank object 
(i.e. image, specimen, locality, view, publication, annotation, character, etc). In the above
screen shot, the type of object being annotated is an <strong>image.</strong></li>
<li><strong>Add a new annotation</strong>: Clicking on<strong> Add Annotation</strong> 
will take the user to the <strong>Add Annotation</strong> screen where the user can add an 
additional annotation of any type to the selected object. Directions for this process are located later in section.
<img src="ManualImages/annotation_manager_add_annotation.jpg" vspace="5"/>
</li>
</ul>

			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/annotation.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	
