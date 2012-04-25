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
        <br />
&nbsp;&nbsp&nbsp;<h2><?php echo $config->servicesUrl; ?>validateXls.jsp</h2>

       <div class="specialtext3">
Use this <a href="<?php echo $config->servicesUrl; ?>validateXls.jsp">web service</a>
to proof the Morphbank Excel Workbooks (Original or Custom). A user may upload
the original workbook or the custom workbook for validation.
<ol><li>upload
        the <strong>original workbook</strong> (mb3a.xls or mb3p.xls) or the <strong>custom workbook</strong> for validation</li>
    <li>browse your files to find the Excel workbook you filled out</li>
    <li>click <strong>Submit Query</strong> to upload</li>
    <li>a report is returned</li>
    <li>If no errors are found, the workbook is ready to send to Morphbank for upload.</li>
    <li>Else, fix issues found. Get Morphbank assistance if needed. Try the validator again.</li>
</ol>
        </div>

 
        
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="center" src="ManualImages/validate.jpg" alt="screen shot of validate Excel form"/>
</p>

<p>The <a href="<?php echo $config->servicesUrl; ?>validateXls.jsp">web services</a> code checks validity of the following Original Workbook fields (mb3a or mb3p).</p>
<ul>
    <li>version number of the workbook</li>
    <li>empty credential cells (Morphbank Account-holder name, user name, submitter name, date to publish, creative commons field)
    <li>checks Locality column on Specimen sheet (compared to Locality sheet)</li>
    <li>checks Specimen column on Image sheet (compared to Specimen sheet)</li>
    <li>checks View column on Image sheet (compared to View sheet)</li>
    <li>validates date collected fields are yyyy-mm-dd format</li>
    <li>proofs image file names to insure no spaces and that extensions (.jpg, etc) are provided</li>
    <li>insures latitude and longitude are decimal values</li>
    <li>on View sheet, if (any cell in a row is not empty) then ViewTSN cannot be empty</li>
</ul>

<p>For the custom workbook, the following issues are checked:</p>
<ul>
    <li>version number</li>
    <li>empty cells for both Morphbank user name and id (one of them must be filled)</li>
    <li>empty cell for date to publish</li>
    <li>user names have matching ids</li>
    <li>user id belongs to the group specified</li>
    <li>duplicate entries in Image External Id; they must be unique</li>
    <li>format of date determined (if exists) as text</li>
    <li>scientific names are not in the database</li>
    <li>tsn or Morphbank mtsn matches the scientific name string</li>
    <li>tsn with extra spaces</li>
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

