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
<h1>Validate Morphbank Excel Workbooks via Web Services</h1>
<div id=footerRibbon></div>
    <!--<table class="manualContainer" cellspacing="0" width="100%">
    <tr>-->
    <td width="100%">

<p>Use this <a href="http://services.morphbank.net/mb3/validateXls.jsp">web service</a> to proof the Morphbank Excel Workbooks. A user may upload
the original workbook or the custom workbook for validation.</p>

<img src="ManualImages/validate.jpg" align="center" alt="screen shot of validate Excel form"/>

<p>Browse your files to find the Excel workbook you filled out, click to upload
    and a report is returned. If no errors are found, the workbook is ready to
    send to Morphbank for upload.</p>

<p>The web services here checks validity of the following workbook fields.</p>
<ul>
	<li>Contributor Name: does this person have a valid Morphbank account?
	</li>
	<li>Contributor User Name: is the user name correct for the above person?
	</li>
	<li>Submitter Name: does this person have a valid Morphbank account?
	</li>
	<li>Date To Publish: needs to be a valid date in yyyy-mm-dd format.
	</li>
        <li>Creative Commons Copyright.
	</li>
        <li>
	</li>
        <li>
	</li>
        <li>
	</li>
	</ul>

<p>If you need assistance after validation to understand any error messages,
    please send an email to mbadmin@scs.fsu.edu</p>
<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?php echo $config->domain; ?>About/Manual/manualHints.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
</div>

<?php
//Finish with end of HTML
finishHtml();
?>

