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


<div class="mainGenericContainer" width="100%">
    <!--change the header below -->
    <h1>Guide to Graphic Buttons</h1>
    <div id=footerRibbon></div>
    <p>Morphbank is modular in form. The icons and various buttons seen in the My Manager interface
        work analogously throughout Morphbank to enhance the learning curve for users.
    </p>
                            <!--<table class="manualContainer" cellspacing="0" width="100%">
                            <tr>-->
    <td width="100%">

        <table align="center" border=".2" cellpadding="10" cellspacing="0">
            <tr>
                <th>Graphic</th>
                <th>Explanation</th>
            </tr>
            <tr>
                <td align="center"><img src="../../style/webImages/camera-min16x12.gif" alt="camera icon" /></td>
                <td>Click this button to display associated list of images or objects (in the case of a collection)</td>
            </tr>
            <tr>
                <td align="center"><img src="../../style/webImages/magnifyShadow-trans.png" alt="FSIviewer icon"></td>
                <td>This icon appears next to images in Morphbank. It is a link to the <a href="<?php echo $config->domain; ?>About/Manual/zoomingViewer.php" target="_blank"><strong>Bischen image viewer</strong></a> which offers users the ability to zoom in / out and move around any image in Morphbank. Via the zooming viewer, functionality to allow labels to be associated with images is under development.</td>
            </tr>
            <tr>
                <td align="center"><img src="../../style/webImages/infoIcon.png" alt="information icon" /></td>
                <td>Click to show information (metadata) about this item. Standard throughout Morphbank and the resulting page is referred to as the <strong>single show</strong> page for the object (Image, View, Specimen, Locality,...)</td>
            </tr>
            <tr>
                <td align="center"><img src="ManualImages/select_mass_operation.png" alt="select mass operation" /></td>
                <td>A powerful Morphbank feature. Some tasks on many objects can be done at one time. Use the <em>check box</em>
                    to choose items to be changed. From the <strong><em>Select Mass Operation</em></strong> choose the task. An example would be
                    to change the date-to-publish to "now" on many images at one time. For more on this, jump to
                    <a href="<?php echo $config->domain; ?>About/Manual/myManagerNewFeatures.php" target="_blank">My Manager Features</a>.
                </td>
            </tr>
            <tr>
                <td align="center"><img src="ManualImages/feed-icon-96x96.jpg" alt="rss feed icon" /></td>
                <td>Click this rss feed icon from any group record in Morphbank to create a link to that group's current images in Morphbank.
                </td>
            </tr>
            <tr>
                <td align="center"><img src="../../style/webImages/envelope.gif" alt="mail to"></td>
                <td>Mail this object link to an e-mail address</td>
            </tr>
            <tr>
                <td align="center"><img src="../../style/webImages/hierarchryIcon.png" />
                    <br /><img src="../../style/webImages/hierarchryButton.gif" /></td>
                <td>Selecting this button will display the taxon hierarchy tree</td>
            </tr>
            <tr>
                <td align="center"><img src="../../style/webImages/plusIcon.png" alt="add icon"/></td>
                <td>Add a new object or item (in the case of a drop-down list).</td>
            </tr>
            <tr>
                <td align="center"><img src="../../style/webImages/checkIcon.png" alt="browse select check icon"/></td>
                <td>Select this object.</td>
            </tr>
            <tr>
                <td align="center"><img src="ManualImages/select.gif" alt="select check button" />
                    <img src="ManualImages/search.gif" alt="search button">
                    <br />
                    <img src="ManualImages/go.gif" alt="go button" />
                    <br />
                    <img src="ManualImages/save_order_button.gif" alt="save order button" />
                    <br />
                    <img src="ManualImages/reset.gif" alt="reset button" />
                </td>
                <td>Examples of various instructional buttons, that when selected, will perform the
                    function displayed on the button.</td>
            </tr>
            <tr>
                <td align="center"><img src="ManualImages/browseAlpha-trans.png" height="41" width="41" alt="abc icon"/></td>
                <td>Show alphabetically all taxon names stored in Morphbank.</td>
            </tr>
            <tr>
                <td align="center"><img src="../../style/webImages/edit-trans.png" height="16" width="16" alt="edit icon"/></td>
                <td>Select this edit icon to alter data. This option is only available to those authorized
                    users through login permissions. A user may edit any object they've contributed or submitted to Morphbank as long
                    as it is not yet published (public). </td>
            </tr>
            <tr>
                <td align="center"><img src="../../style/webImages/annotate-trans.png" height="16" width="16" alt="annotate icon" /></td>
                <td>Select item to annotate. This option is only available to authorized users
                    through login permissions.</td>
            </tr>
            <tr>
                <td align="center"><img src="../../style/webImages/annotateIcon.png" alt="alternate annotate icon" /></td>
                <td>Once an object in a collection has been annotated, the annotation icon
                    <img src="../../style/webImages/annotate-trans.png" height="16" width="16" alt="annotate icon" /> changes to this
                    <img src="../../style/webImages/annotateIcon.png" alt="alternate annotate icon" /> icon to indicate one or more Annotations
                    exist for a given object. Click the <img src="../../style/webImages/annotateIcon.png" alt="alternate annotate icon" /> to make another annotation.</td>
            </tr>
            <tr>
                <td align="center"><font color="red" size="4">*</font></td>
                <td>When this symbol is located next to the description of a user input text box, it
                    signifies that the input information is required to proceed.</td>
            </tr>
            <tr>
                <td align="center"><img src="../../style/webImages/selectIcon.png" alt="select check icon"/></td>
                <td>This icon is found next to fields where it is desirable/mandatory to select an entry
                    from a list to insure accuracy. Click this icon to redirect to the appropriate list.</td>
            </tr>
            <tr>
                <td align="center"><img src="ManualImages/page_scroll_bar.gif" alt="page scroll bar" /></td>
                <td>Page scroll bar. Arrow pointing left "advance to first page", first arrow
                    pointing right "advance one page", second arrow pointing right "advance
                    to last page", or select any number to advance to that page.</td>
            </tr>
            <tr>
                <td align="center"><img src="ManualImages/taxon_hierarchy_snippet.gif" alt="taxon hierarchy example" /></td>
                <td>Click on triangles in "browse" to expand or contract taxon hierarchy lists</td>
            </tr>
            <tr>
                <td align="center"><img src="../../style/webImages/copy-trans.png" alt="copy icon" /></td>
                <td>Used in <strong>Collections</strong> and in <strong>My Manager</strong> --
                    Meaning: "make a copy of this object".
                    This option is only available to those authorized through login permissions. Only collections/objects that are published
                    can be copied.</td>
            </tr>
            <tr>
                <td align="center"><img src="../../style/webImages/delete-trans.png" height="16" width="16" alt="delete icon" /></td>
                <td>Presence of this symbol indicates a user may delete an object. For instance, click on to delete an annotation in the <strong>My Manager, Annotation tab</strong>.
                </td>
            </tr>
            <tr>
                <td align="center"><img src="../../style/webImages/calendar.gif" /></td>
                <td>Releasing images in Morphbank for public view is easy. Images in Morphbank can remain private, viewable only to the User who contributed the image and others in that User's group. This <strong>calendar icon</strong> indicates a user may
                    change the "date-to-publish" which extends the time the Image is private or allows the user to make the Image visible to all
                    Morphbank users including the general public.
                </td>
            </tr>
        </table>

        <br />
        <br />
        <div id=footerRibbon></div>
        <table align="right">
            <td><a href="<?php echo $config->domain; ?>About/Manual/terms.php" class="button smallButton"><div>Next</div></a></td>
            <td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
        </table>
</div>

<?php
//Finish with end of HTML
finishHtml();
?>
