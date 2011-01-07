<?php 
	//global $includeDirectory, $dataDirectory, $imgDirectory;
	
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>

	
		<div class="mainGenericContainer" width="100%">
		<!--change the header below -->
		<h1>User Manual Hints</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
		
<ul>
<li>Use the table of Contents to find the topic or area of interest.</li>
<li>Use Browser keyboard shortcuts to find a keyword (i.e. Internet Explorer,
Mozilla Firefox use CTRL-F) and type in keyword. These vary according
to the machine and browser being used.</li>
<li>Use Links at the bottom of pages to jump to Contents or Next manual page.</li>
<li>If a URL link is to website outside Morphbank, use "right click" (PC) or "control-click" (Mac) to open a new tab to the URL.</li >
<li>Links to pages within this manual take the user directly to the desired page. Use "right click" (PC) or "control-click" (Mac) to open a tab to another manual page.</li>
<li>Use the Contents button to choose another page in the Manual.
</li>
<li> Click the <img src="ManualImages/feedback.png" alt="feedback link" align="middle" /> link at the <strong>top</strong> of any page in the My Manager interface of Morphbank. It opens the following window providing an easy and timely way for users to share their observations as they experience Morphbank. <!--It is also accessible at the <strong>bottom</strong> of every User Manual page.-->
<p><img src="ManualImages/feedback_form.png"></p>
</li>
</ul>
</p>

			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/systemRequire.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</div>
		
			<?php
//Finish with end of HTML	
finishHtml();
?>	
