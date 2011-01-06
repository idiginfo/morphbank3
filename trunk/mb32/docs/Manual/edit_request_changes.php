<?php 
	//global $includeDirectory, $dataDirectory, $imgDirectory;
	global $domainName;
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);

	?>
		
<div class="mainGenericContainer" width="100%">
<!--change the header below -->
<h1>Edit: View and Request Changes</h1>
<div id=footerRibbon></div>
<br />
Some information in morphbank is used by a large number of users.  Only requests can be made to edit information in these tables:
<ul>
    		<li>Imaging Technique</li>
            <li>Imaging Preparation Technique</li>
            <li>Specimen Part</li>
            <li>Sex</li>
            <li>Form</li>
            <li>Developmental Stage</li>
            <li>View Angle</li>
            <li>Link Type</li>
</ul>

You can view the information from <b>Tools> Edit</b>
<br />
<br />
<img src="ManualImages/edit_view_request_changes.jpg" hspace="20" />
<br />
<br />
To request a change, modify the desired fields above and when you hit the 
<img src="../../style/webImages/buttons/updateButton.jpg" /> button an email will be generated for 
morphbank administration.
<br />
<br />
Example email next shows a request to change the Description field of the Imaging Technique ESEM from "not provided"
to "Environmental Scanning Electron Microscope."
<br />
<img src="ManualImages/edit_request_mail.jpg" vspace="5" />
<br />
A user may add comments for the morphbank team and click the send button to email the request.
			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $domainName;?>About/Manual/edit_taxon_name.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $domainName;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
				</div>
		
			<?php
// Finish with end of HTML
finishHtml();
?>
	