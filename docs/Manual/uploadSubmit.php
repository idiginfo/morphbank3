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
		<h1>Submit</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
<p>The next seven manual pages explain <em>how to add data and images to Morphbank via the web-interface</em>. A Morphbank Contributor may submit data and images via: 1) the web interface, 2) one of 2 different <a  href="<?php echo $config->domain?>Help/Documents/">Excel spreadsheets</a>, or 3) via XML. Contact <strong>mbadmin <font color="blue">at</font> scs <font color="blue">dot</font> fsu <font color="blue">dot</font> edu</strong> for more information on the XML option.
</p>            

<!--<div class="specialtext2">
<a href="<?php echo $config->domain; ?>About/Manual/Movies/submitlocality.avi" target='_blank'><img src="ManualImages/movieicon.jpg" /></a>  How to <strong>Submit a Locality</strong>: <a href="<?php echo $config->domain; ?>About/Manual/Movies/submitlocality.avi" target='_blank'>video</a>
<br /><a href="<?php echo $config->domain; ?>About/Manual/Movies/SubmitSpecimen.avi" target='_blank'><img src="ManualImages/movieicon.jpg" /></a>  How to <strong>Submit a Specimen</strong>: <a href="<?php echo $config->domain; ?>About/Manual/Movies/SubmitSpecimen.avi" target='_blank'> video</a>
<br /><a href="<?php echo $config->domain; ?>About/Manual/Movies/submitview.avi" target='_blank'><img src="ManualImages/movieicon.jpg" /></a>  How to <strong>Submit a View</strong>: <a href="<?php echo $config->domain; ?>About/Manual/Movies/submitview.avi" target='_blank'>video</a>
<br /><a href="<?php echo $config->domain; ?>About/Manual/Movies/submitimage.avi" target='_blank'><img src="ManualImages/movieicon.jpg" /></a>  How to <strong>Submit an Image</strong>: <a href="<?php echo $config->domain; ?>About/Manual/Movies/submitimage.avi" target='_blank'>video</a>
<br /><a href="<?php echo $config->domain; ?>About/Manual/Movies/AddTaxonNamenew.avi" target='_blank'><img src="ManualImages/movieicon.jpg" /></a>  How to <strong>Add a Taxon Name</strong>: <a href="<?php echo $config->domain; ?>About/Manual/Movies/AddTaxonNamenew.avi" target='_blank'>video</a>
</div>
-->
<p>
After selecting <strong>Submit</strong>, the screen displays a sub-menu with choices of <strong>Locality</strong>, <strong>Specimen</strong>, <strong>View</strong>,
<strong>Image</strong>, <strong>Publication</strong> or <strong>Taxon Name</strong>. The next 6 manual pages explain how to submit each of these objects to Morphbank.
</p>

<img src="ManualImages/submit_object.png" hspace="20" />

<div class="specialtext2">N.B. For all Morphbank objects, the <strong>Contributor</strong> and <strong>Submitter</strong> fields default to the person logged-in &amp; submitting via the web. If a Submitter is entering data on behalf of a Contributor, select the Contributor's name from the drop-down for each object. Go to <a href="<?php echo $config->domain; ?>About/Manual/userPrivileges.php">Users and their Privileges</a> for more about Morphbank Group Roles &amp; Contributor / Submitter status. See example next.
</div>
<img src="ManualImages/submitterSample.jpg" hspace="20" />
<br />
<br />
<h3>Best Practices for Submit</h3>
<p>
<ul>
<li>If there are many <a href="<?php echo $config->domain; ?>About/Manual/addTaxonName.php" >Taxon Names to add</a> to Morphbank, add them before any other data associated with these taxon names.
</li>
<li>Preferred order for submitting data is: Taxon Names, Locality, Specimen, View, Image.
</li>
<li>Before an Image is uploaded, both a Specimen and View should exist.
</li>
<li>If person (A) is submitting objects to Morphbank for a different Morphbank account holder, person (B): for each object submitted, be sure to select person (B) for the <strong>Contributor</strong>. The submitter is automatically the person logged-in to Morphbank uploading the data and images. Go to <a href="<?php echo $config->domain; ?>About/Manual/userPrivileges.php">Users and their Privileges</a> for more about Morphbank Group Roles &amp; Contributor / Submitter status.
</li>
</ul>
</p>
			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?php echo $config->domain; ?>About/Manual/uploadSubmitLocality.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	

