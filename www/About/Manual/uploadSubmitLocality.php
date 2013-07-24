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
		<h1>Submit: Add Locality</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->

<!--<div class="specialtext2">
<a href="<?php echo $config->domain; ?>About/Manual/Movies/submitlocality.avi" target='_blank'><img src="ManualImages/movieicon.jpg" /></a>  How to <strong>Submit a Locality</strong>: <a href="<?php echo $config->domain; ?>About/Manual/Movies/submitlocality.avi" target='_blank'>video</a>
</div>
-->

<p>
The <strong>Add Locality</strong> screen contains detailed information about the locality where
the specimen was <em>collected</em> or <em>photographed</em>. Note that as Morphbank also contains images of living plants growing in botanical gardens, as well as plants <em>in situ</em>, the locality information in these cases usually refers to where the image was taken and there may or may not be a vouchered specimen in something like an herbarium collection. Check the metadata for the Specimen for more information.
</p>
<p>Path to <strong>Add Locality</strong>: <em>header menu</em> <strong>> Tools > Submit > Locality</strong>
</p>
<img src="ManualImages/add_locality.png" hspace="20"/>
<br /><br />
<h3>Add Locality Hints</h3>
<ul>
<li>If a Specimen has locality data, a Locality must exist before a Specimen can be entered.
</li>
<li>A Specimen record <strong>does not</strong> require Locality data.
</li>
<li>N.B. Morphbank displays locality information to everyone, logged-in or not. Use care when uploading images of threatened / endangered / protected entities. When adding a Locality for this situation, please enter only general locality data for these records; avoid precise locality details like latitude / longitude data.
</li>
</ul>

<h3>Locality Fields</h3>
<ul>
<li><strong>Country</strong><br />
Choose one country /political unit from where the specimen was collected.
The drop-down list contains names of currently recognized countries. If
you need to add new entries to this list, please contact <strong>mbadmin <font color="blue">at</font>
scs <font color="blue">dot</font> fsu <font color="blue">dot</font> edu</strong>. 
For old specimens, we recommend
that the original country of collection, if applicable, is added to the locality
description and that the country column is used to provide the current
political unit for the locality (if this is possible to determine).</li>

<li><strong>Locality Description</strong><br />
Enter the name of the Region/Province/County/Place and displacement
from this name (if applicable) from which the Specimen was collected.
Examples: "FL Tallahassee, Apalachicola National Forest", "FL St George
Island". The locality description should correspond to label data if possible.</li>

<li><strong>Latitude</strong><br />
Enter the latitude of the locality. Use the decimal format followed by
north/south from the dropdown list. Convert minutes and seconds to a
decimal part, if applicable. The number entered should be between 0.0000
and 90.0000. (There are conversion websites available that convert
degrees to decimal such as <a href="http://www.jeepreviews.com/wireless-gps-coordinates/">GPS Coordinate Conversion</a>).</li>

<li><strong>Longitude</strong><br />
Enter the longitude of the locality. Use the decimal format followed by
east/west from the dropdown list. Convert minutes and seconds to a
decimal part, if applicable. The number entered should be between 0.0000
and 180.0000. (There are conversion websites available that convert
degrees to decimal such as <a href="http://www.jeepreviews.com/wireless-gps-coordinates/">GPS Coordinate Conversion</a>).</li>

<li><strong>Coordinate Precision</strong><br />
Enter an estimate of how tightly the longitude and latitude of the collecting
locality was specified. Express the precision as a distance, in meters, that
corresponds to a radius around the latitude-longitude coordinates. Leave
the field blank if the precision is unknown, can not be established or is not
applicable.</li>

<li><strong>Minimum Elevation</strong><br />
Enter the minimum elevation of the locality in meters above (positive) or
below (negative) sea level.</li>

<li><strong>Maximum Elevation</strong><br />
Enter the maximum elevation of the locality in meters above (positive) or
below (negative) sea level.</li>

<li><strong>Minimum Depth</strong><br />
Enter the minimum depth of the locality in meters below the surface of the
water where the collection was made. All material collected for this
specimen record should be at least this deep. Use positive number for
below the surface and negative for above.</li>

<li><strong>Maximum Depth</strong><br />
Enter the maximum depth of the locality in meters below the surface of the
water where the collection was made. All material collected for this
specimen record should be at most this deep. Use positive number for
below the surface and negative for above.</li>

<li><strong>Contributor</strong> (Required)<br />
Select the name of the contributor (person having the authorization to
release the images) from the dropdown list. The contributor can be
different from the submitter (logged-in person entering the data). If you 
need to add new entries to this list, please contact <strong>mbadmin <font color="blue">at</font>
scs <font color="blue">dot</font> fsu <font color="blue">dot</font> edu</strong>.</li>
<div class="specialtext3">Note: The person logged-in will be the name of the person that displays
in the "Contributor" field above. If a Submitter is entering data on behalf of a Contributor, select the 
Contributor's name from the drop-down.
</div>

<li><strong>Add External Links</strong> to this record. <br />
For complete
instructions on providing external links refer to <a href="<?php echo $config->domain; ?>About/Manual/externalLink.php">External Linking</a> in the Information Linking section of this manual. Click on <font color="blue">Add External Links</font> to open this feature. See next: <br/>
</li>
<img src="ManualImages/add_externalLinksRef.png" alt="external link options" hspace="20" vspace="10"/>
	<ul>
    <li>Choose the <strong>Type</strong> of External Link (some examples are: GenBank, Project, Institution, ...)    </li>
    <li>Enter the text for the <strong>Label</strong> the user in Morphbank will click to go to this URL.    </li>
    <li>Enter the complete <strong>URL</strong> here.    </li>
    <li>The <strong>Description</strong> field is optional.    </li>
    <li>Click the <strong>+</strong> icon to add additional external links.    </li>
    <li>Click the <strong>-</strong> icon to remove any outdated links.    </li>
   </ul>
   <br /> 
<li><strong>Add External References</strong> to this record. For complete
instructions on providing external references refer to <a href="<?php echo $config->domain; ?>About/Manual/externalLink.php">External Linking</a> in the Information Linking section of this manual. Click on <font color="blue">Add External References</font> to open this feature. See next:<br/>
<img src="ManualImages/add_externalRefs.png" alt="external identifiers" hspace="20" vspace="10"/>
<ul>
    <li>Enter the <strong>Description</strong> for the External Reference. This will appear to the Morphbank user as a label in front of the unique id.
    </li>
    <li>Enter the <strong>Identifier</strong> unique for this locality in the remote database in the <strong>Unique Reference ID</strong> field.</li>
</ul>
</li>
</ul>
    	<div class="specialtext3">
        <ul>
        <li><strong>Unique Reference ID</strong> <em>best practice</em> is to combine an <strong>acronym prefix</strong> + an <strong>identifier</strong>.</li>
        <li>The database table storing this identifier requires the values be unique. If the identifier string entered is already in this table, the user will have to figure out a different prefix.</li>
        <li>For example, a user, Fred S Unstead, has a Locality with ID=123456 and puts his initials as the prefix for: <strong>FSU:123456</strong></li>
        	<ul>
            <li>Florida State University (FSU) entered Locality IDs as prefix: FSU + an identifier (123456).</li>
            <li>Fred S Unstead needs to change his prefix in some way, for example: <strong>FSU-L:123456</strong> (where the L is for Locality) and the identifier will upload into Morphbank.</li>
            </ul>
        <li>The external unique reference ID can be used in future uploads and for updates of current records in Morphbank.    
        </ul>
        </div>  
<p>
When the Add Locality form has been completed, <strong>Submit</strong> to complete
the add locality process. A message will confirm that you have successfully
added a locality. From this same screen the user can continue to add additional
localities, click return to go to the <strong>Add Locality</strong> screen or choose a different option from <em>header menu:</em><strong> Tools > Submit</strong>.
</p>
<div class="specialtext3">Note: When this screen is activated from the <strong>Add Specimen</strong> upload
screen no message will be seen. Instead, the new locality will appear in the
appropriate field on the <strong>Add Specimen</strong> submit form.
</div>
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?php echo $config->domain; ?>About/Manual/uploadSubmitSpecimen.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	
