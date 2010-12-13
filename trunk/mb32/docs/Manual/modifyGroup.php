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
		<h1>Modify Group Membership</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
<p>
In Morphbank, a User (aka Contributor) automatically has their own Morphbank Group. This User / Contributor is the Coordinator of their own group. As Group Coordinator, the user may add / remove other Morphbank account holders to / from their own group. Members of Morphbank groups have various roles (coordinator, lead scientist, scientist, guest). The Coordinator may also change the roles of the current group members.
</p>
<div class="specialtext2">
Groups are usually formed by a set of individuals for the purpose of collaboration. Membership in a group is also part of the security model Morphbank uses to determine what objects can be seen (or not) by a given individual. To find out more about Morphbank Groups and Group Roles go to the <a href="<?echo $domainName;?>About/Manual/userPrivileges.php">Users and Their Privileges</a> page.
</div>

<h3>Open Group Settings</h3>
<p>To modify roles of existing group members or add / remove members to / from your Morphbank Group:
<ul>
<li>Login</li>
<li>If you belong to more than one Morphbank Group, select Group from drop-down (see below), Or from <strong>Tools > Select Group</strong>, 
</li>
<li>If you are Coordinator of the Group selected you will now be able to go to: <strong>Tools > Group Settings</strong>.
</li>
<li>Note after Group selection, your role in the group is displayed in the top left of the header, just under your name.
</li>
</ul>
</p>
<p>
<strong>Tools > Group Settings</strong> opens the following screen:
</p>
<img src="ManualImages/group_settings.png" hspace="20"/>

<br />
<br />
<h3>Modify Group Membership</h3>
<p>To add / remove group members OR to change the role of an existing member:
<ul>
<li>Click <strong>Modify members</strong> to open <strong>Select Group Membership</strong> page (see next screen shot).
<li>All Morphbank members that can be added to the group are listed. Those already in your group have a check mark by their name.</li>
<li>To add a member, click in the box next to their name, select their role in the group from the drop-down and click Update.
</li>
<li>To change the role of an existing member, choose the new role from the drop-down next to their name and click Update.
</li>
<li>To remove a group member, uncheck the box next to their name and click Update.
</li>
</ul>
</p>
<img src="ManualImages/select_group_membership.png" alt="select group membership" />
<p>
<div class="specialtext3"> Before uploading any objects to Morphbank, it is important to <em><strong>choose the Group</strong></em> with which you would like the object to be associated. In other words, if you the Morphbank Contributor belong to more than one group, select the appropriate group after login, <strong>before upload</strong>. If the object is to remain private, you will need to be in this group to see/edit/annotate the object. Other members of your group will also need to select this group after login to see these images.
</div>

<br />

			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $domainName;?>About/Manual/myManager.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $domainName;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
		</div>
		
<?php
//Finish with end of HTML	
finishHtml();
?>	
