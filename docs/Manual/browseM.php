<?php 
	//global $includeDirectory, $dataDirectory, $imgDirectory;
	
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>
	
		<div class="mainGenericContainer" width="100%">
		<!--change the header below -->
		<h1>Browse - Mirrors</h1>
<div id=footerRibbon></div>

<br />
<br />

<br />
<br />
<a href="javascript:window.close()" class="button smallButton"><div>Close</div></a>
<a href="<?echo $config->domain;?>About/Manual/browseC.php" class="button smallButton"><div>Next</DIV></a>
<a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a>
		
		</div>
		<?php
//Finish with end of HTML	
finishHtml();
?>
	