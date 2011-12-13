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
    <h1>Anatomy of Morphbank Pages</h1>
    <div id=footerRibbon></div>
                            <!--<table class="manualContainer" cellspacing="0" width="100%">
                            <tr>-->
    <td width="100%">
        <div>
            <img align="right" src="ManualImages/add_locality_screen.png" alt="add locality screen"/>
        </div>
        <div float="left">
            <br />
            Tag descriptions
            <br />
            <br />
            <ol>
                <li>Click on logo to return to the Morphbank home page at any time.
                </li>
                <br />
                <li>Page Name.
                </li>
                <br />
                <li>Click to logout. (Only visible after login).
                </li>
                <br />
                <li>Username and Morphbank Group the user logged-in with (Only visible after
                    login). Hover over Group to see all Morphbank Groups of which one is a member. Use the Group drop-down to choose a different group.
                </li>
                <br />
                <li>Hover over Browse to reveal a selectable drop-down list that will advance/return the user to other
                    areas within Morphbank.
                </li>
                <br />
                <li>Hover over Tools to reveal a selectable drop-down list that will advance/return the user to other
                    areas within Morphbank including: Account Settings, Group Settings and the Submit Locality, View, Specimen, Publication, Taxon Name and Image screens.
                </li>
                <br />
                <li>Select to reveal drop-down list.
                </li>
                <br />
                <li>Entry boxes for user to input appropriate data.
                </li>
                <br />
                <li>Click to open Add External Links. Any user may add External Links to their Morphbank Objects. For example, a user may link to GenBank or a published journal article.
                </li>
                <br />
                <li>Click to open Add External References. For the user with an existing database or who might be referencing an object in an existing database, this section allows a Morphbank User to provide Morphbank with an existing unique identifier for this object. Examples here include Taxonomic Id numbers from Taxonomic name servers like Tropicos, IPNI, UBio or Catalogue of Life, or perhaps doi numbers for publications.
                </li>
                <br />
                <li>No fields in Add Locality are required. Any required field is marked with a red "<font color="red">*</font>"
                </li>
                <br />
                <li>Click on buttons to perform various tasks in Morphbank (see <a href="<?php echo $config->domain; ?>About/Manual/graphicGuide.php" target="_blank">"Guide to Graphic Buttons"</a>section in this manual).
                </li>
                <br />
                <li>Redirect to the corresponding area within Morphbank.
                </li>
                <br />
                <br />
                <li>Any user in a Morphbank group may submit images on behalf of other members in the same Morphbank group.
                    Choose the person to be the <strong>Morphbank Contributor</strong> of the record. The person currently
                    logged in is automatically the <strong>Morphbank Submitter</strong>. Any member of this Morphbank group
                    may edit the record as needed.
                </li>
                <br />
            </ol>
        </div>

        <div>
            Screen Use Tips (see image tags below):
            <p>The Morphbank My Manager interface is modular. Use the header menu options Browse and Tools to access My Manager. Features found in one My Manager tab work the same way in each tab. This screen shot serves as a general introduction to this user-interface.</p>
            <p>The number of hits displayed on each page can be designated <strong>(A)</strong> and a user can advance to
                a specific page number by listing that page <strong>(B)</strong> and selecting the go button. Keep in mind that the quantity of information requested to display per page will
                affect the speed at which that screen loads (i.e. requesting 100 records per page will take
                longer to load than the screen that has only 10 records to load).<strong>(C)</strong> Help and Feedback are available from any tab in Morphbank. The Help link is contextual to where the user is when they click on Help. Feedback opens a form the user may fill out to send an automated message to Morphbank.
            </p>
            <img src="ManualImages/anatomy_browse_images.png" alt="browse image sample" />
            <p>
                At the top left is the Keywords search box <strong>(D)</strong>. Metadata associated with each object in Morphbank is stored in a single table for quick searching. Any string entered here is wild-carded. Searches here are <em>boolean and</em> so that the user may narrow the result set. Click on the Search button, or press enter to perform the search. Use the tabs <strong>(E)</strong> to jump to different Morphbank objects (Images is the tab shown here). At the left side-bar, note the <em>Limit Search by</em> feature <strong>(F)</strong>. By checking one of the boxes, the Morphbank user can limit the resulting objects on display as desired. For example, the user may only wish to see objects they have contributed to Morphbank. Then, by using the check box <strong>(G)</strong> or the Check All button, the Morphbank user can group this set of objects into a Morphbank collection. First the user checks the objects to be collected and then goes to <strong>(H)</strong> Select Mass Operation. From the Select Mass Operation drop-down, the user chooses Create New Collection and then clicks on the Submit button.
            </p>
            <p>
                Clicking on the <strong>jpeg</strong> or <strong>Original</strong> <strong>(I)</strong> links will open images uploaded to Morphbank. If the original images have other formats, those will be found here.
                Selecting the information icon <strong>(J)</strong> will display detailed information about that object. Other options such as edit <strong>(K) </strong>and annotate <strong>(L)</strong> will be
                available only for those authorized through login permissions. The magnifying glass<strong> (M)</strong> will open the image using the open source <strong>zooming image viewer</strong>, allowing the user
                to move the image and zoom in / out; labeling is a function that may be available soon. The <strong>zooming image viewer </strong>is used to display high-resolution imagery on Morphbank. This viewer, known as Bischen, is a component of CollectiveAccess, community-developed open-source collections management and presentation software for museums, archives and arts organizations. For more information, visit <a href="http://www.collectiveaccess.org/">http://www.collectiveaccess.org</a>  Selecting the tree of life symbol <strong>(N)</strong> will list the taxonomic hierarchy of the
                Taxon name. <strong>(O)</strong> indicates a unique feature of the <strong>My Manager > Images Tab</strong>. For example, clicking on the "Group" link for a given image, will perform a search for all images associated with that Group. Clicking on "User" will return images for that Morphbank User/Contributor, etc.
            </p>
        </div>
        <br />
        <br />
        <div id=footerRibbon></div>
        <table align="right">
            <td><a href="<?php echo $config->domain; ?>About/Manual/ITIS.php" class="button smallButton"><div>Next</div></a></td>
            <td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
        </table>
</div>

<?php
//Finish with end of HTML	
finishHtml();
?>	
