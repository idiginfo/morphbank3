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
		<h1>Add Annotations</h1>
<div id=footerRibbon></div>
<br />
<br />			
<a name="addSingle"></a>
Annotations can be added to images, taxa or images that are inside a collection. Annotations are possible
in Morphbank anywhere the 
<img src="../../style/webImages/annotate-trans.png" height="16" width="16" alt="annotate icon" />
<strong>annotate icon</strong> appears. Inside of a collection an 
<img src="../../style/webImages/annotateIcon.png" alt="alternate annotate icon" /> <strong>icon</strong>
replaces the <img src="../../style/webImages/annotate-trans.png" height="16" width="16" alt="annotate icon" />
<strong>icon</strong> if <strong>Annotations</strong> exist for that object.

<p>Click the <strong>Annotation Icon</strong> <img src="../../style/webImages/annotate-trans.png" /> to add
a New single (one at a time) annotation or click on several images inside an image collection to annotate
mass (multiple) images at one time.
</p>

<h3>Adding new single (or Mass) annotations:</h3>
<p>After logging in, a user may create Single annotations from
	<ul>
	<li>Browse - Images > Click <img src="../../style/webImages/annotate-trans.png" height="16" width="16" alt="annotate icon" />
	 to open Add Annotation Screen > Choose the type of Annotation and complete the required
	 fields.
	 </li>
	<li>My Manager > Images tab / Taxa tab or / Collections tab</li>
	<ul>
	<li>Use the <strong>Keywords</strong> search to limit the set of objects to be annotated</li>
	<li>In the Collections Tab, choose a collection, open it, click on the 
	<img src="../../style/webImages/annotate-trans.png" height="16" width="16" alt="annotate icon" />
	to add a single annotation to any particular image in the collection or the
	<img src="../../style/webImages/annotateIcon.png" alt="alternate annotate icon" />
	of any object in a collection.
	</li>
	<li><em><strong>To annotate more than one image in a collection</strong></em>, <strong>check all to be annotated</strong> and <strong>choose
	Annotate Checked Objects...</strong> from the left menu bar to open the Mass Annotation
	Screen. Fill out the required fields and click <strong>Submit</strong>.</li>
	</li>
	</ul>
	<li>My Manager > Taxa tab > Toggle to OTU > open an OTU with the "Info Icon" or OTU title >
	click the <strong>Annotate Icon</strong> for any taxon name in the OTU to open the Add Annotation
	screen. Complete the fields; be sure to note or change the date-to-publish and click Submit.</li>
	</ul>



</p>
<p>Single annotations are created through <strong>Browse - Images</strong>, through the results of a
<strong>Search</strong>, through an existing annotation (annotation - show, etc.) or through a 
<strong>Collection</strong> (i.e. browse - collection, collection, collection - show etc.)</p>
<p>For example, to reach the <strong>Add Annotation</strong> screen, logged-in
users can select the annotation <img src="../../style/webImages/annotate-trans.png" /> icon located
beside the thumbnail image of the record to be annotated as seen in <strong>Browse - Images</strong> (see next) or through
the results of a <strong>Search</strong>.</p>
<img src="ManualImages/add_annotation_screen_detail.jpg" hspace="20" />
<p>The <strong>Add Annotation</strong> screen is accessible from many places in Morphbank.</p>
<a name="pathToAnnotation"></a>
<div class="specialtext2">

Paths to <strong>Add Annotation</strong>
<ul>
<li>Browse - Images > <img src="../../style/webImages/annotate-trans.png" /> icon</li>
<li>Search >  <img src="../../style/webImages/annotate-trans.png" /> icon</li>
<li>Browse - Images > click any thumbnail or <img src="../../style/webImages/infoIcon.png" />  to open Image Record - Single Show> scroll down to click on "<font color="blue">Add Annotation</font>" </li>
<img src="ManualImages/annotation_sample.jpg" vspace="5" />
<!--<li>Annotation Manager> click on "<font color="blue">add</font>" to open <strong>Add Annotation</strong> for a particular Morphbank object</li>
<img src="ManualImages/add_annotation_from_manager.jpg" />--></strike>
<!--<li>Collection Manager> click desired Collection id> click <img src="../../style/webImages/annotate-trans.png" /> icon to annotate any single item in the collection.</li>
<img src="ManualImages/annotate_from_collection.jpg" vspace="5" />--></strike>
<li>Header Menu > My Manager > Annotation tab: Click the <img src="../../style/webImages/infoIcon.png" /> to open the 
Single Show for the Annotation. To add an Annotation, click on Add Annotation.</li>
</ul>
</div>
All required fields are followed by an <font color="red">*</font>. Fields change depending on the <strong>Type
 of Annotation</strong> selected. Users can view the image with the unique features of <strong>FSIViewer</strong> by
 clicking on the <strong>Image id number</strong> in the Annotation Title (see example above).
<ul>

<li><strong>Type of annotation<font color="red">*</font></strong>: The default is <strong>Determination</strong> Annotation. The other options of <strong>General</strong>, <strong>Legacy</strong>, 
<strong>XML</strong>, and <strong>Taxon Name</strong> are
selected from the drop-down list. For a description of these annotations, jump to 
<a href="<?php echo $config->domain; ?>About/Manual/annotationTypes.php">Types of Annotations.</a></li><br />

<li><strong>Related annotations</strong>: (field with <strong>Determination </strong>Annotation) The user can select from a list of previously
submitted, related determination annotations for that image.<!-- (or related
images)--> To select the related annotation, click on the radio button to the left of
the taxonomic name. This field also contains a history of the previous
annotations (author, prefix/suffix, A (agree with taxon name), D (disagree with
taxon name), S (number of specimen(s) associated with this determination
and collection of images).
<p>Attributes of Related Annotations in the list for a single determination
annotation:
<ol>
<li>All annotations in the list have the same specimen (specimen id)</li>
<li>All annotations in the list must be determination annotations</li>
<li>Included in the related annotations list is the initial determination placed in
the specimen record.</li>
</ol><br />
This means that all of the images associated with a single specimen will have
the same related annotations visible in a determination annotation. 
</p>
</li><br />

<li><strong>Determination action<font color="red">*</font></strong>: (field with 
<strong>Determination </strong>Annotation) Choose to agree, disagree, or agree
with qualification (to agree with the taxon but not with a listed prefix or suffix).
<br /><br /><ul>

<li><strong>Agree</strong>: The user must choose a previous determination using the radio
buttons to the left of the related annotation. An annotation record will be
added that agrees with that taxonomic name, prefix and suffix.
</li>

<li><strong>Disagree</strong>: The user must choose a previous determination using the radio
buttons to the left of the related annotation. An annotation record will be
added that disagrees with that taxonomic name, prefix and suffix.
</li>

<li><strong>Qualify lowest rank</strong>: The user must choose a previous determination using
the radio buttons to the left of the related annotation. Additionally, the user
will have the ability to qualify the taxon with a prefix and/or suffix. (These
appear only after the qualify option is selected) The combination of
taxonomic name/prefix/suffix must be unique (if there is a duplicate, an Agree
annotation will be added).
</li>
</ul>
<br />

<li><strong>New taxon</strong>: (field with <strong>Determination </strong>Annotation) 
If no related annotation was chosen from the list, the user has the
option of selecting a new Taxon name from a list. To insure accuracy,
taxonomic names need to be selected <img src="../../style/webImages/selectIcon.png" /> from the <strong>Taxon Name Search</strong> selection
screen. Traverse through the levels by clicking on the desired taxon until the appropriate scientific
name is found. Then click the select icon <img src="ManualImages/select.gif" />; it will automatically direct
the user back to the <strong>Add Annotation</strong> screen and the appropriate name will be
filled in.
<p>If a new taxon name needs to be added select the <strong>Add New Taxon</strong> button
that is visible from the family level. The<strong> Add Taxon Name</strong> screen will popup. (This 
option is only available for authorized users.) For complete instructions on
this process see the ITIS, <a href="<?php echo $config->domain; ?>About/Manual/addTaxonName.php">Add New Taxon</a> section of this manual.</p>
</li>
<div class="specialtext2">Note: Great care must be taken when adding new taxon names to the local
copy of the database. New taxon names are submitted to the Department of Agriculture 
<a href="http://www.itis.gov/">http://www.itis.gov/</a>.</div>

<li><strong>Prefix/suffix</strong>: (field with <strong>Determination </strong>Annotation) Only available if user chose to agree with qualification or chose
a new taxon name). Uses can choose a prefix or suffix from the appropriate
drop-down list to qualify their determination action.
<p>Prefix options include:
<ul><li>None</li>
<li>Not</li>
<li>Aff (affinity with)</li>
<li>Cf (compare with)</li>
<li>Forsan (perhaps)</li>
<li>Near (close to)</li>
<li>Of lowest rank</li>
<li>? (questionable)</li>
</ul>
</p>
<p>Suffix options include:
<ul>
<li>None</li>
<li>Sensu lato (in the broad sense)</li>
<li>Sensu stricto (in the narrow sense)</li>
<li>Of lowest rank</li>
</ul>
</p>
<br />
</li>

<li><strong>Materials used in id</strong>: (field with <strong>Determination </strong>Annotation)
Indicate the materials examined to formulate this determination annotation by selecting an option from the drop-down list.</li>

<li><strong>Source of identification<font color="red">*</font></strong>: (field with <strong>Determination </strong>Annotation)
 Enter the name of the person who made the determination. The default for this option is the logged-in user.
The name can be changed if the annotation is being made on behalf of
someone else.</li>

<li><strong>Resources used in identification<font color="red">*</font></strong>: (field with <strong>Determination </strong>Annotation)
 Indicate the resources used to support the determination annotation. This is a free text entry for information
such as citations of literature or expert opinion.</li>

<li><strong>Title<font color="red">*</font></strong>:  Click on this field to change or enter a title for the
annotation. The default title is <strong>Determination</strong> for a determination annotation.
For other types of annotations enter an appropriate descriptive title.</li>

<li><strong>Comments<font color="red">*</font></strong>: Enter comments to support the annotation or
comments that might aid other users to understand the particulars of this
annotation, or add any other information that might be useful to keep with the
annotation. Examples: explain why the specimen was identified with the
particular taxon, comment on an image marker placement etc.</li>

<li><p><strong>Image label</strong>: When annotating a single image, the user has the option of
identifying a location on the image to associate a pointer or box and a label (If
annotating a group of images this option will not be available).
</p>
<img align="left" src="ManualImages/annotation_image_label.png" hspace="20" />
<p>To add a marker to the image, select the <img src="../../style/webImages/selectIcon.png" />
 beside the <strong>Image Label</strong> field. The current image will display. Click on the screen 
 (do not drag) where the point of the marker or corner of the box is to be located. To reposition the marker 
 or box, click on the screen in the new location. The old marker will be replaced by a new marker.
The marker/box color can be selected. Click the radio button next to the desired
color (choices are red [default], white and black). To add a label to the marker, type the label in the Annotation Label field
provided on the screen.</p>
<div class="specialtext2">Note: Only one marker and label is available for each annotation.
Multiple markers require separate annotations for each desired marker
and label.</div>
When the image has been marked and labeled, select submit. The screen
returns to the add annotation screen. If a marker label was added, it will
show up in the <strong>Image Label</strong> box. As long as the annotation is not yet
published, a submitted marker can be changed through edit annotation.
</li>
<br />
<br />
<li><strong>X/Y coordinates</strong>: This field will display automatically after a marker has been
placed on the image. It is not suggested that the coordinates be manually
changed by the user. The location of the marker on the image is represented
as a percentage (%) of pixels from the left of the image (x) and from the top of
the image(y).</li>

<li><strong>Date to publish<font color="red">*</font></strong>: Type in any date from the date created to 5
years from that date. (The publish date defaults to 6 months from the date the
collection was established.)</li>

<li><strong>Submit/Return</strong>: Select Submit to upload the annotation data to Morphbank
and go back to the place where the annotation was initiated or select Return
to go back to that screen without submitting any data.</li>
</ul>
<h3>Adding new Mass Annotations:</h3>
<p>A user with a login account can annotate a group of images called a "mass
annotation". Mass annotations can be made through any area in Morphbank that
accesses collections i.e. <strong>Browse - Collection</strong>, <strong>Collection</strong>, 
<strong>Collection - Show</strong>, etc.
By selecting all or any subset of a group of images, a user can request to
annotate that collection by calling the add annotation screen and entering the
data. This will cause an annotation record to be added for each individual image
selected. Additionally, if the annotation type was a determination, then a
<strong>Determination Annotation</strong> record will also be added or created through the
<strong>Annotation Show</strong> function.</p>
<p>To access annotations through <strong>Browse - Collection</strong>, locate the collection to
annotate. Click on the <img src="../../style/webImages/edit-trans.png" /> icon to open
the specific collection to annotate.</p>
<p>
Users can also access the mass annotation process from <strong>Tools - Collection Manager</strong>, click
on the Collection id of choice, then check images in a collection (check the box in the lower left side of the image). Then
click <strong>Annotate Checked Objects</strong>. If only one image is selected to be
annotated, the user will be directed make a single annotation.
</p>
<img src="ManualImages/annotate_from_collection.jpg" hspace="20"vspace="5" />
<br />
Sample <strong>Mass Annotation</strong>:
<br />
<img src="ManualImages/mass_annotation.jpg" />
<br />
<br />
Tag descriptions
<ol>

<li><strong>Mass annotation heading</strong>: This displays the collection id and name that
the mass annotation was initiated from as well as the number of images that
were selected to annotate from the collection.</li>

<li><strong>Image thumbnails</strong>: This list of thumbnails represents the images that are
included in this mass annotation. The list will scroll as needed to display all
included images.</li>

<li><strong>Related annotations</strong>: (available only with the annotation type of
Determination selected.)This list will contain all specimens associated with the
images contained in tag 2 above.
<ul>

<li><strong>Taxonomic name</strong> - represents the lowest level taxonomic name of the specimen.
</li>
<li><strong>Taxon Author</strong> - Author of the taxonomic name from the ITIS database.
</li>
<li><strong>History</strong> - This contains the historic data relating to prefix(s)/suffix(s) and totals
regarding previous annotations associated with this determination. A (agree with
taxon name), D (disagree with taxon name), S (number of specimen(s) with that
taxonomic name and collection of images).
</li>
</ul></ol>
The instructions for the remaining fields contained on the mass annotation page
can be found in the <a href="#addSingle">Add Single Annotation</a> section at the top of this page.
<div class="specialtext2">Note: The reference to image markers and labels on the add annotations
page are not available for mass annotations.</div>
			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?php echo $config->domain; ?>About/Manual/annotationEdit.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	
