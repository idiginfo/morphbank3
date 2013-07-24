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
$title = 'About - Manual';
initHtml($title, NULL, NULL);
echoHead(false, $title);
?>

<div class="mainGenericContainer">
    <!--change the header below -->
    <h1>My Manager - Annotation</h1>
    <div id=footerRibbon></div>
    <p>The <strong>Annotation Tab</strong> is
        directly accessed by choosing <strong>My Manager</strong> from the <strong>Tools </strong>menu
        (located on the opening Morphbank screen or on the page header) and then choosing the Annotations Tab.</p>

    <img src="ManualImages/my_manager_annotation_tab_closeup.png" />

    <p>
        The <strong>My Manager > Annotations Tab</strong> offers the user a list of all the annotations that have
        been created under the current username and group as well as all other published annotations in the system.
        There is no limit on the number of annotations a user may have. To access other
        annotations under the same username but created under another authorized
        group, return to the <strong>Select Group</strong> screen and select the desired group. Or simply use the Keyword
        Search or Limit By features to display a select group of annotations.
    </p>
    <img src="ManualImages/my_manager_annotation_tab.png" hspace="20" />


    <p>
        Tag Information
    <ol>
        <li><strong>Annotations</strong>: Use the Keyword Search to find a particular annotation. Holding the mouse
            over the Keyword Search box will indicate which fields the Keyword Search recognizes. One can limit the search
            so that only a user's own annotations will be displayed using the <strong>Limit By:</strong> feature.</li>
        <li><strong>Annotation id</strong>: This is a Morphbank-issued identifier. Click on it to view
            the associated annotation.</li>
        <li><strong>Annotation title</strong>: Clicking on this title will take the user to the <strong>Edit
                Annotation</strong> screen. This screen contains the previously entered
            annotation data that can be edited by the owner. Take note that the type of
            annotation can not be altered. (Edit Annotation is only available to the owner if the
            annotation is not yet published). Complete instructions on this area can
            be found in the Edit Annotation area of this manual. This example is from clicking on
            <strong>Nixonia bini notes</strong> Title as seen in the above screen.
            <img src="ManualImages/edit_annotation_from_annotation_manager.jpg" vspace="5" />
        </li>
        <li><strong>Annotation type</strong>: There are currently five types of annotations possible:
            <strong>Determination</strong>, <strong>General</strong>, <strong>Legacy</strong>,
            <strong>.XML </strong> and <strong>Taxon Name</strong> (see <a href="<?php echo $config->domain; ?>About/Manual/annotationTypes.php">Types of Annotations</a> later in this chapter.)</li>
        <li><strong>Object id</strong>: This represents the identifying
            number of the object (image, specimen, etc.) being annotated. Clicking on the id will take the
            user to the <strong>Single Show</strong> screen that displays the record which contains the image and
            related information.
            <img src="ManualImages/annotation_manager_single_show_access.jpg" vspace="5" />
        </li>
        <li><strong>Type of object being annotated</strong>: Currently, only images, specimens and taxon names
            will have annotation options but in future versions, users will be able to annotate any Morphbank object
            (i.e. image, specimen, locality, view, publication, annotation, character, etc).</li>
        <li><strong>Date created</strong>: This is the date that the annotation was submitted to
            Morphbank. It is automatically generated.</li>
        <li>Select a <strong>date to publish</strong>: Type in any date from the date created to 5
            years from that date. (The publish date defaults to 6 months from the date the
            collection was established.) After changing the date(s) click on the update button
            to register all the date changes in Morphbank.</li>
        <li><strong>Add a new annotation</strong>: Clicking on<strong> Add</strong> will take the user to the <strong>Add
                Annotation</strong> screen where the user can add an additional annotation of any type to
            the selected object. Directions for this process are located later in this chapter.
            <img src="ManualImages/annotation_manager_add_annotation.jpg" vspace="5"/>
        </li>
        <li><strong>Delete an annotation</strong>: To delete an annotation, click on the delete icon. A
            confirmation message will appear prior to completing the delete. (This option is
            available only if the annotation is not yet published).</li>
        <li><strong>Update button</strong>:  To register
            changes, click on the update button.</li>
    </ol>
</p>
<br />
<br />
<div id=footerRibbon></div>
<table align="right">
    <td><a href="<?php echo $config->domain; ?>About/Manual/annotationTypes.php" class="button smallButton"><div>Next</div></a></td>
    <td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
</div>

<?php
//Finish with end of HTML	
finishHtml();
?>	
