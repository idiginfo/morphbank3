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
		<h1>Morphbank Modules</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
<p>
After log-in, the user is now automatically taken to the<a href="<?echo $domainName;?>About/Manual/myManagerImages.php" target="_blank">
 Images</a> tab of the new (beta)
<a href="<?echo $domainName;?>About/Manual/myManager.php" target="_blank">
 My Manager</a> user-interface. From here,
the user may select a group from the Header Menu > Tools > Select Group > choose group from sub-menu.
<img src="ManualImages/my_manager_select_group.png" vspace="10" hspace="5">

 After group selection,
the user has the option to <strong>Browse</strong>, <strong>Search</strong>, <strong>Submit</strong> or
<strong>Edit</strong> data or jump to any of the other Tabs in the My Manager interface seen above including: 
All, Images, Specimens, Views, Localities, Taxa (including OTUs), Collections, Annotations and Publications.
Some options seen above in the Tools menu like <strong>Manage Mirror</strong> or <strong>Group</strong> are based upon login permissions and are 
not available to all Morphbank members. Each <a href="<?echo $domainName;?>About/Manual/myManager.php" target="_blank">
My Manager</a> Tab allows users access to their own objects as well as other's
objects if they are published. 
</p>
<!--<img src="ManualImages/morphbank_modules.jpg" />-->
<div class="specialtext3">
Note: The instructions for <a href="<?echo $domainName;?>About/Manual/uploadSubmit.php">
<strong>Submit</strong></a>, and <a href="<?echo $domainName;?>About/Manual/edit.php"><strong> Edit</strong></a> are covered here in Morphbank 
modules. For directions in the use of <a href="<?echo $domainName;?>About/Manual/browse.php"> <strong>Browse</strong></a> or <a href="<?echo $domainName;?>About/Manual/search.php"><strong>Search</strong></a> return to appropriate section in this manual.
</div>
<p>To <strong>Submit</strong> objects to Morphbank: Login, Select a Group and click on Submit. Select the <br>
type of object to be uploaded and enter the required data as required. Or Login, choose Tools (from Header Menu), and choose
Submit > Object to submit (from the sub-menu).
<br />
<img src="ManualImages/submit_select_upload.png" vspace="10" />
</p>
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $domainName;?>About/Manual/uploadSubmit.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $domainName;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	
