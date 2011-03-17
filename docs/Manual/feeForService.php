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
	<h1>Fee For Service</h1>
	<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
			<td width="100%">		

<p>For ongoing sustainability after the end of initial grant funding, Morphbank charges
a relatively small <em>fee-for-service</em> to just cover the cost of a help desk
person and one-time storage fee based on total space (file size) of all images to be uploaded.
Please see the following for more details and contact Morphbank if more information is needed.</p>

<p>The following items serve as examples of how Morphbank may charge for a given service. 
This outline helps users decide how they might proceed as would-be contributors to Morphbank. 
Note, each use-case is unique and someone on the Morphbank Staff will evaluate each request 
and give the contributor a quote up-front.</p>


<p>1. Upload via Morphbank XML. RATE:  $30.00 / hour</br>
This assumes the contributor's existing database has been mapped to Morphbank. 
See # 2 below.Use this option if the contributor's data is already in a database. 
NB. Morphbank is building a piece of software to automate data export from Specify6 into Morphbank.
</p>

<p>2. Mapping to Morphbank. One-Time Fee @ RATE:  $30.00 /  hour</br>
For users with existing databases, mapping is provided at $30.00 / hour and is a one-time fee. 
The contributor receives a map of the Schema. Hint here to users: the more familiar a would-be 
contributor is with Morphbank, the faster this process will be. Users may send a sample dataset 
to get an estimate of how long the mapping might take.
</p>

<p>3. Upload via Morphbank Excel Workbook - 250 records per workbook	RATE:  $30.00 / hour</br>
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
	<li type='0'>The Morphbank Staff member will:</li>
	<li>Check the Excel Workbook, especially Taxon Names &amp; Image file names and return a time / cost 
	estimate and/or a list of errors found that need correction before upload.
	</li>
	<li>Upload the data and images from the completed Morphbank Excel Workbook.</li>
	<li>Return a report to the Contributor and / or point the contributor to http://services.morphbank.net/mb3/</li> 
	</ul>


<p>4. Morphbank Helpdesk &amp; User Feedback Response. RATE:  $30.00 / hour</br>
Time will be accrued &amp; billing will take place on a to-be-agreed-upon schedule (quarterly, perhaps).
</p>

<p>5. One-time Infrastructure / Equipment Fee		   One-Time Fee @ RATE:  $1.00 / GB</br>
To defray the costs of equipment, a one-time fee based on the GB total for the images to be uploaded to Morphbank.
</p>

<p>6. Overhead - Subcontract or Consultant</br>
Subcontract with FSU for &amp; Morphbank has to pay overhead of 46%. 
One may pay our support person directly for support with lower overhead on our side. 
We can do this at 20%. Contact riccardi@ci.fsu.edu for an estimate.
</p>

			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/manualHints.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	
