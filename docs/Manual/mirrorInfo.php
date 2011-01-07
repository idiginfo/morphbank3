<?php 
	//global $includeDirectory, $dataDirectory, $imgDirectory;
	
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>

	
		<div class="mainGenericContainer" width="100%">
		<!--change the header below -->
		<h1>Mirror Information</h1>
<div id=footerRibbon></div>

			
<br />			
			<h3>Creating a Mirror:</h3>
<br />Morphbank users can mirror published images on Morphbank to any ftp location.  This is beneficial because you can have the images you use most often geographically close to you, making page load time a lot faster.  
If you would like to be a mirror please contact Morphbank Administration at <b>mbadmin <font color="blue">at</font> scs <font color="blue">dot</font> fsu <font color="blue">dot</font> edu</b>

<br /><br />The following information is needed to set up the mirror:
<ul>
<li>server address</li>
<li>Country </li>
<li>State/Province</li>
<li>City</li>
<li>Administrator </li>
<li>Mirroring group: you can create a new group to include Morphbank users that can add images to the mirror or create a mirror for an already existing group.  The group coordinator is the only person who may edit mirror information.</li>
<li>Ftp login name</li>
<li>Ftp login password</li>
<li>Contact email for the mirror coordinator.</li>
<li>Directory Path</li>
<li>URL that points to the images</li>
<li>A logo that will appear on Morphbank pages if someone is using your mirror.  This logo should be relatively small, somewhat square (not more than twice as long as high).  </li>
	</ul>
	<br />
				<h3>Example:</h3><br />
<br />
<img src="ManualImages/mirror_server_info.jpg" />
<br />
<br />
			<h2>Editing Mirror Information:</h2>
<br />
To edit information regarding a mirror you must be listed as the coordinator and be in the Group that is the Mirror Group.  Once these conditions are met from the <b>Header Menu under Tools</b> you can select 
<b>Mirror Settings</b>.  
			<br />
			<br />
			<h3>Adding Images to the Mirror:</h3>
			<br />
	Any member of the Mirror Group can add images to the mirror.  From the <b>Header Menu </b>under <b>Tools</b>, select <b>Manage Mirror</b>.  
			<br />
			Select the arrow next to *Taxon Name* to add a new group to the mirror.  As many images as you like can be added to the mirror.
			<br />
			<br />
			<img src="ManualImages/manage_mirror_sample.jpg" />
			<br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/customWorkbook.php"class="button smallButton"><div>Next</div></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
</div>
			<?php
//Finish with end of HTML	
finishHtml();
?>	

	
