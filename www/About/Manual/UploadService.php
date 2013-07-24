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
		
<div class="mainGenericContainer" width="100%">
<!--change the header below -->
<h1>Mapping, Mass Uploads and Web Services</h1>
<div id=footerRibbon></div>
    <!--<table class="manualContainer" cellspacing="0" width="100%">
    <tr>-->
    <td width="100%">

<p>For help with mapping, mass uploads and web services, Morphbank can put you in touch with people to assist you.</p>

<p>The following items serve as examples of how one of these individuals may charge for a given service. 
This outline helps users decide how they might proceed as would-be contributors of many images to Morphbank. 
Note, each use-case is unique and these contractors will evaluate each request 
and give the contributor a quote up-front.</p>


<p>1. Upload via Morphbank Custom Workbook and XML. RATE:  $40.00 / hour<br />
This assumes the contributor's existing database has been mapped to Morphbank. 
See # 2 below.Use this option if the contributor's data is already in a database. 
NB. The Specify team is building a piece of software to automate data export from Specify6 into Morphbank.
It is currently in the beta testing phase.
</p>

<p>2. Mapping to Morphbank. One-Time Fee @ RATE:  $40.00 /  hour<br />
For users with existing databases, mapping is provided at $40.00 / hour and is a one-time fee. 
The contributor receives a map of the Schema. Hint here to users: the more familiar a would-be 
contributor is with Morphbank, the faster this process will be. Users may send a sample dataset 
to get an estimate of how long the mapping might take.
</p>

<p>3. Upload via Morphbank Excel Workbook - 250 records per workbook	RATE:  $40.00 / hour<br />
Suitable for smaller datasets - where the contributor's data is not already databased. 
Upload of one workbook of this size will take 1 - 2 hours and assumes the contributor does the following:
</p>
	<ul>
	<li>Checks all Taxon Names in the dataset with Taxon Names in Morphbank. 
	Note there is an automated way to do this via <a href="/Help/nameMatch/">Name Query</a>. Contact mbadmin@scs.fsu.edu for any needed help with this.
	</li>
	<li>Adds any Taxon Names that need adding (via the web-interface) or the Specimen Taxon Data sheet in the Excel Workbook.
	</li>
	<li>Sends a completed Excel Workbook with no (or only minor) errors.
	</li>
	<li>Sends images via ftp, DVD or harddrive.
	</li>
	</ul>

	<ul>
	<li>The contractor will:</li>
    <li>Upload Taxon Names for the contributor, if desired.</li>
	<li>Check the Excel Workbook, especially Taxon Names &amp; Image file names and return a time / cost 
	estimate and/or a list of errors found that need correction before upload.
	</li>
	<li>Upload the data and images from the completed Morphbank Excel Workbook.</li>
	<li>Return a report to the Contributor and / or point the contributor to <?php echo $config->servicesUrl; ?></li> 
	</ul>


<p>4. Helpdesk &amp; User Feedback Response. RATE:  $40.00 / hour<br />
Time will be accrued &amp; billing will take place on a to-be-agreed-upon schedule (quarterly, perhaps).
</p>

<p>5. Subcontract or Consultant<br />
One may pay our support person/s directly for support with lower overhead on our side. 
Contact mbadmin@sc.fsu.edu for an estimate.
</p>

			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?php echo $config->domain; ?>About/Manual/validateExcel.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	
