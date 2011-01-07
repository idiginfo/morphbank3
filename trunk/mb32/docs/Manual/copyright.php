<?php 
	global $includeDirectory, $dataDirectory, $imgDirectory;
	
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>


	<div class="main">	<div class="mainGenericContainer" style="width: 820px;">
		<!--change the header below -->
		<h1></h1>
		<br /><br />
			<table class="manualContainer" cellspacing="0" width="100%">
			<tbody><tr>
			<td width="100%">
			
<h1>Morphbank Copyright Policy</h1>
<img src="http://morphbank.net/style/webImages/blueHR-trans.png" width="700" height="5" class="blueHR" />
Morphbank is an open web repository of images serving the biological
research community. Morphbank is designated as a Fair Use Web Site. The
objective of morphbank is not to reward the labor of authors, but to promote
the Progress of Science. The images in morphbank that are not password
protected can be used for private, education, research or other noncommercial
purposes for free, provided that the source and the copyright
holder are cited. Any commercial use requires consent from the copyright
holder. The images in morphbank that are password protected are
considered "work in-progress" and are not released to the public. These
images may not be used without specific written authorization from the
copyright holder. Contributors to morphbank agree to these terms.
<br /><br />
<a href="javascript:window.close();">Close Window</a>
<br />
<br />
<a href="<?echo $config->domain;?>About/Manual/index.php">Back to Manual Table of Contents</a>
			</td>
			</tr>
			</tbody></table>
			</div>
		
<?php
    include( $includeDirectory.'manual_comments.php');
	?>