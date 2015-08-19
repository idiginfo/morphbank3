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
	
		<div class="mainGenericContainer">
		<!--change the header below -->
		<h1>Select Groups</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
<p>
In Morphbank, a user may belong to one or more Groups. Groups are usually formed by a set of individuals for the purpose of collaboration.
Membership in a group is also part of the security model Morphbank uses to determine what objects can be seen (or not) by a given individual. To find out more about Morphbank Groups and Group Roles go to the <a href="<?php echo $config->domain; ?>About/Manual/userPrivileges.php">Users and Their Privileges</a> page.
<div class="specialtext3"> Before uploading any objects to Morphbank, it is important to <em><strong>choose the Group</strong></em> with which you would like the object to be associated. In other words, if you the Morphbank Contributor belong to more than one group, select the appropriate group after login, <strong>before upload</strong>. If the object is to remain private, you will need to be in this group to see/edit/annotate the object. 
</div>
</p>
<p>
After login, ...
<ul>
<li>the user sees the <strong>Images</strong> tab of the <strong>My Manager</strong> 
interface.
</li>
<li>new drop-down options (like <strong>Taxon Search</strong>) become active under <strong>Browse</strong> and new drop-down options appear under <strong>Tools</strong> and <strong>Help</strong> in the header.
</li>
</ul>
<h3>Find and Select a Group</h3>
<p>
 Note that the Group and User (aka Contributor) are displayed with each Morphbank image (as well as with other Morphbank objects). The group(s) module(s) to which the user belongs can be accessed from two places (see below): 
 </p>
<ol>
<li>hover over <strong>Group</strong> in the header or
</li>
<li>
from the <strong>Tools
</strong> > <strong>Select Group</strong>  > [choose group from sub-menu].
</li>


<img src="ManualImages/select_group3.png"  vspace="10"/>
<li>Make a group selection (click on group name) that corresponds to the information
that will be worked on in the current session.
</li>
<li>To work within another authorized
group choose again from one of the above two menus.
</li>
</p>
</ol>
<p>Everyone, logged in or not, uses this My Manager interface. Once <strong>logged in</strong>, and <em>after group selection</em>,
the user has new menu options under <strong>Tools</strong> such as Submit, Group &amp; Account Settings. Once logged in, contributors can now Edit their objects found in each of the <strong>My Manager tabs</strong>. Some options not seen above in the Tools menu like <strong>Manage Mirror</strong> are based upon login permissions and are not available to all Morphbank members. Each <a href="<?php echo $config->domain; ?>About/Manual/myManager.php" target="_blank">
My Manager</a> Tab allows users access to their own objects as well as other's objects if they are published. 
</p>
<div class="specialtext3">
Note: Here users may jump to sections in the manual covering <a href="<?php echo $config->domain; ?>About/Manual/uploadSubmit.php">
<strong>Submit</strong></a> and <a href="<?php echo $config->domain; ?>About/Manual/edit.php"><strong> Edit</strong></a>.
</div>
<p>To <strong>Submit</strong> objects to Morphbank: Login, <strong>Select a Group</strong>, go to Header Menu > Tools > Submit > select the <br>
type of object to be uploaded and then enter the required data. Group selection is important. If you need to keep the images private for a 
time, all the members of the group you are in when you upload will see the images. Any other Morphbank members or the public will not see these
images. If you are a member of multiple groups, select the group whose members need to be able to see the images, before you upload.
</p>

<br />

			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?php echo $config->domain; ?>About/Manual/modifyGroup.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	
