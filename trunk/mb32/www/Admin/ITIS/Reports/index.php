<?php
//Created by: Karolina Maneva-Jakimoska
//Created on: February 20 2007
include_once('head.inc.php');
include_once('itisReports.php');
include_once('createReport.php');
//included on the end on purpose

// The beginnig of HTML
$title = 'ITIS - Reports';
initHtml($title, null, null);

// Add the standard head section to all the HTML output.
echoHead(false, $title);

echo '<div class="mainGenericContainer" style="width:680px;">';

if (isset($_POST['selection']))
createReport();
echo '<h1>Create ITIS Report</h1>';

if (!isset($_POST['selection'])) {
	echo '<span style="color:#17256B"><b><p>This page was created for users affiliated with ITIS.<br/>
         Reports can be genereated for new names as well as corrections on the existing names.<br/>
         Make a proper selection for the report you wish to generate.</p></b></span><hr/><br/>';
}
include_once('js/pop-update.js');
itisReports();

// Finish with end of HTML
finishHtml();
?>
