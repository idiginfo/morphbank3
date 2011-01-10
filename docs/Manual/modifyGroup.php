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
		<h1>Modify Group Membership</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
<p>
In Morphbank, a User (aka Contributor) automatically has their own Morphbank Group. This User / Contributor is the Coordinator of their own group. As Group Coordinator, the user may add / remove other Morphbank account holders to / from their own group. Members of Morphbank groups have various roles (coordinator, lead scientist, scientist, guest). The Coordinator may also change the roles of the current group members.
</p>
<div class="specialtext2">
Groups are usually formed by a set of individuals for the purpose of collaboration. Membership in a group is also part of the security model Morphbank uses to determine what objects can be seen (or not) by a given individual. To find out more about Morphbank Groups and Group Roles go to the <a href="<?echo $config->domain;?>About/Manual/userPrivileges.php">Users and Their Privileges</a> page.
</div>

<h3>Open Group Settings</h3>
<p>To modify roles of existing group members or add / remove members to / from your Morphbank Group:</p>
<ul>
<li>Login</li>
<li>If you belong to more than one Morphbank Group, select Group from drop-down (see below), Or from <strong>Tools > Select Group</strong>,</li>
<li>If you are Coordinator of the Group selected you will now be able to go to: <strong>Tools > Group Settings</strong>.
  <ul>
    <li>If you are Coordinator of more than one Morphbank Group, you'll see a list of Groups here (instead of just one).</li>
  </ul>
</li>
<li>Note after Group selection, your role in the group is displayed in the top left of the header, just under your name.</li>
</ul>
</p>
<strong>Tools > Group Settings</strong> opens the following screen:
</p>
<br />
<img src="ManualImages/tools_groupsettings.png" alt="show group information" hspace="40" vspace="5"/>

<div class="specialtext3">
<strong>Edit group</strong> allows one to change the name of an existing group and change the group status from active to inactive.
<strong>Create Reviewer</strong> gives a Group Coordinator the ability to add a Group member that only has permissions to view the objects in the group.
No editing or upload privileges are granted to a Reviewer.
</div>

<h3>Modify Group Membership</h3>
<p>To add / remove group members OR to change the role of an existing member:
<ul>
<li>Click <strong>Modify members</strong> to open <strong>Modify Group Members</strong> page (see next screen shot).</li>

<img src="ManualImages/modify_groupmembers.png" alt="show group members" hspace="20" vspace="10" />
<li>All current members and their roles are listed.
  <ul>
    <li>Uncheck a box for a user and click Update to remove them from the group.</li>
    <li>Change the  <a href="<?echo $config->domain;?>About/Manual/userPrivileges.php">User Role</a> (coordinator, leadscientist, scientist, guest) for a group member using the drop-down menu, click Update.</li>
    	<ul>
        <li><strong>Only 1 Coordinator</strong> for each group.</li>
        </ul>
  </ul>
</li>

<li>To add new members, search for them. Search via radio buttons to select search method.</li>
	<ul>
    <li>Partial entries work. For example, Search=tom (via email) finds persons with 'tom' anywhere in their email address.</li>
    <li>User Ids can be found via Morphbank Keyword Search by name. If they've contributed Images, you can click on the link showing their name to see their Morphbank User Id.</li>
    <li>First Name and Last Name choices accept partial entries as well.</li>
    <li>To see everyone whose last name begins with A, just put an A in the Search (by last name).</li>
    </ul>
<img src="ManualImages/group_addmembers.png" alt="add group members" hspace="40" vspace="10"/>

<li>To add a member, click in the box next to their name, select their role in the group from the drop-down and click Add.</li>
</ul>

<img src="ManualImages/group_memberadded.png" alt="group member added" hspace="40" vspace="10"/>
 
<div class="specialtext3"> Before uploading any objects to Morphbank, it is important to <em><strong>choose the Group</strong></em> with which you would like the object to be associated. In other words, if you the Morphbank Contributor belong to more than one group, select the appropriate group after login, <strong>before upload</strong>. If the object is to remain private, you will need to be in this group to see/edit/annotate the object. Other members of your group will also need to select this group after login to see these images.
</div>
<br />

			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $config->domain;?>About/Manual/myManager.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $config->domain;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	
