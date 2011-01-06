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
		<h1>Login - Update User Account Information</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
<br />
<h2>Your Account Information in Morphbank</h2>
<p>To update personal user information such as: name, address, affiliation, password, or logo, go to:
<ul>
<li><strong>Header Menu > Tools > Login</strong>, then</li>
<li><strong>Header Menu > Tools > Account Settings</strong></li>
<br />
<li>Edit as needed, click <img src="ManualImages/update_button.png" align="middle"  alt="Update button" /></li>
<li>Grayed-out field need changing? Contact 
<strong>mbadmin <font color="blue">at</font> scs <font color="blue">dot</font> fsu <font color="blue">dot</font> edu</strong></li>
</ul>
</p>
<img src="ManualImages/account_information.png" alt="User Account Information" hspace="20">


<br />
<br />

			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $domainName;?>About/Manual/selectGroup.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $domainName;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	
