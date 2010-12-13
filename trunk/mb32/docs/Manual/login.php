<?php 
	//global $includeDirectory, $dataDirectory, $imgDirectory;
	global $domainName;
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>
	
		<div class="mainGenericContainer">
		<!--change the header below -->
		<h1>Login</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
<p>
A Morphbank user is required to login with a valid Morphbank-issued username and password. 
After login, users are redirected to the <strong>Images</strong> tab of the <strong>My Manager</strong>
interface. Also after login, new choices will appear in the Header Menu drop-downs.

<div class="specialtext3">
Note this Login includes the link for would-be Morphbank contributors to <strong>request an account</strong> as well as access to
<strong>resetting your password</strong> if you have forgotten it.
</div>
</p>
<p><img src="ManualImages/log_in_box.png" hspace="30" /></p>
			<br />
		<br />		
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $domainName;?>About/Manual/loginUsername.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $domainName;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	
