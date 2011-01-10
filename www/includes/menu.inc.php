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

include('mbMenu_data.php');


function makeMenu () {
	global $mainMenuWidth;
	//showing the main Menu
	echo '	<div class="mainNavContainer" style="width:'.($mainMenuWidth*5).'px;">';

	$i = 0;
	echo '		<table class="mainNav" border="0">
				<tr>';
	global $mainMenu;
	foreach($mainMenu as $menu) {
		echo '	<td>';
		echo '	<a class="mainNavLink" href="'.$menu['href'].'" onmouseover="hideAllMenus();expandMenuOptions(\''.$menu['name'].'\');stopTime();"
				onmouseout="startTime();">'.$menu['name'].'</a>';
		echo '	</td>';
	}
	echo '
				</tr>
				</table>
		
			</div>
			';
}

function makeMenuOptions ( ) {
	global $mainMenu, $mainMenuOptions, $userTypes;
	global $objInfo, $link;

	if ($objInfo->getLogged()) { // if logged => Admin or User
		$userType_name = ($objInfo->getUserGroupRole() != NULL)?$objInfo->getUserGroupRole():'user';
		if ($objInfo->getUserGroup() == "Morphbank Administration")
		$userType_name = "admin";
		if (strtolower($objInfo->getUserGroupRole()) == "leadscientist" || strtolower($objInfo->getUserGroupRole()) == "scientist" || strtolower($objInfo->getUserGroupRole()) == "superadmin")
		$userType_name = "user";
	} else //guest
	$userType_name = 'world';

	// for testing
	// $userType_name = 'user';

	// iframe for working around Mac firefox issue (and IE to some extent) of scrollbars showing through or on top of a higher z-indexed element.
	echo'<iframe id="iFrameMainMenuId" class="iFrameClass" style="display: none; left: 0px; position: absolute; top: 0px; z-index:2; border:0;" src="" scrolling="no"></iframe>';
	echo'<iframe id="iFrameSubMenuId" class="iFrameClass" style="display: none; left: 0px; position: absolute; top: 0px; z-index:2; border:0;" src="" scrolling="no"></iframe>';

	$sql = "SELECT serverId FROM ServerInfo WHERE mirrorGroup = " .$objInfo->getUserGroupId();
	$result = mysqli_query($link, $sql);
	if ($result)
	$numRows = mysqli_num_rows($result);

	$manageMirror = ($numRows == 1)? true : false;

	$itis = (strtolower($objInfo->getUserGroup()) == "itis")? true : false;

	$i = 1;
	foreach ( $mainMenu as $menu) {
		$menuName = $menu['name'];
		// getting max length of the menu options
		$menuMaxLength = 0;
		$j=0;
		foreach($mainMenuOptions as $menuOptions) {
			if (($menuOptions['belongTo'] == $menuName) && ($userTypes[$userType_name][$j] == 1)) {
				$l = strlen($menuOptions['name']);
				if ($menuMaxLength < $l)
				$menuMaxLength = $l;
			}
			$j++;
		}

		echo '<div class="mainNavSubMenu" id="'.$menuName.'"
				style="display:none;left:'.$menu['horPosition'].'px;width:'.($menuMaxLength*7).'px" onmouseover="stopTime();" onmouseout="startTime();">';
		// Displaying the menu
		$j = 0;
		foreach($mainMenuOptions as $menuOptions) {
			if ($menuOptions['belongTo'] == $menuName && $userTypes[$userType_name][$j] == 1){
				if ($menuOptions['hideOption']) {
					// don't show it
				} elseif($userType_name == "reviewer" && $menuOptions['greyOut']) {
					echo '<p class="greyOut">'.$menuOptions['name'].'</p>';
				} else {
					echo '<a class="mainNavSubLink" href="'.$menuOptions['href'].'" '.$menuOptions['target'].'>'.$menuOptions['name']."</a>\n";
					//			}
					//			if ($menuOptions['separatorLine']) {
					//				echo '<table width="100%"><tr><td height="2px" bgcolor="#aaaaff"></td></tr></table>';
				}
			}
			$j++;
		}
		echo '</div>';
		$i++;
	}
}

function outputMenu(){

	makeMenu();
	/*
	 if (session_is_registered('admin')){
	 //loggedInAdminMenu($domain, $currentDir);
	 makeMenu('admin');
	 }
	 else {
	 if (session_is_registered('username')){
	 //loggedInMenu($domain, $currentDir);
	 makeMenu('username');
	 }
	 else {
	 //loggedOutMenu($domain,$currentDir);
	 makeMenu('guest');
	 }
	 }
	 */
}

function outputLoginInfo() {
	global $objInfo, $config;

	if ($objInfo->getUserId() != NULL) {
		echo 'User: '.$objInfo->getName().' &nbsp;&nbsp;<a href="'.$config->domain.'Submit/logout.php">[logout]</a><br />';
		if ($objInfo->getUserGroup() != NULL) {
			$uginfo = $objInfo->getUserGroupInfo();
			//echo 'Group: '.$uginfo[0].' as '.$uginfo[2];
			echo '<a class="mainNavLink" href="#" onmouseover="hideSubMenus(); hideAllMenus(); expandGroupMenu(false, \'notMenu\'); stopSubTime(); " onmouseout="startSubTime();">Group: </a>&nbsp;<span id="groupNameMenuId">'.$objInfo->getUserGroup().' ('.$objInfo-> getUserGroupRole().')</span>';
		}
	} else {
		//echo 'User: Guest&nbsp;&nbsp;<a href="'.$config->domain.'Submit/index.php">[click to login]</a>';
		echo 'User: Guest&nbsp;&nbsp;<a href="#" onclick="show_login_ajax(); return false;">[click to login]</a>';

	}


}

function getGroupArray() {
	global $objInfo;

	$sql = 'SELECT groupName, groups FROM User, UserGroup, Groups
	WHERE User.uin = \'' . $objInfo->getUserName(). '\' AND User.id = UserGroup.user 
	AND UserGroup.groups = Groups.id order by groupName';

	$results = runQuery($sql);

	if ($results) {
		for($i=0; $i < mysqli_num_rows($results); $i++)
		$array[$i] = mysqli_fetch_array($results);

		return $array;
	}

	else
	return false;
}

function populateGroupMenu() {
	if($groupArray = getGroupArray()){

		foreach($groupArray as $group) {
			// Redirect method
			//echo '<a class="mainNavSubLink" href="'.$config->domain.'Submit/updateInfo.php?group='.$group['groups'].'&amp;fromMenu=true">'.$group['groupName'].'</a><br/>';

			// AJAX method
			echo '<a class="mainNavSubSubLink" href="javascript: changeGroup('.$group['groups'].');">'.$group['groupName'].'</a>';
		}
	}

}

function populateToolMenuContents($objInfo) {
	global $mainMenu, $mainMenuOptions, $userTypes;
	global $link;

	if ($objInfo->getLogged()) { // if logged => Admin or User
		$userType_name = ($objInfo->getUserGroupRole() != NULL)?$objInfo->getUserGroupRole():'user';
		if ($objInfo->getUserGroup() == "Morphbank Administration")
		$userType_name = "admin";
		if (strtolower($objInfo->getUserGroupRole()) == "leadscientist" || strtolower($objInfo->getUserGroupRole()) == "scientist" || strtolower($objInfo->getUserGroupRole()) == "superadmin")
		$userType_name = "user";
	} else //guest
	$userType_name = 'world';
	$menuName = "Tools";

	$j = 0;
	foreach($mainMenuOptions as $menuOptions) {
		foreach($mainMenuOptions as $menuOptions) {
			if ($menuOptions['belongTo'] == $menuName && $userTypes[$userType_name][$j] == 1){
				if ($menuOptions['hideOption']) {
					// don't show it
				} elseif($userType_name == "reviewer" && $menuOptions['greyOut']) {
					echo '<p class="greyOut">'.$menuOptions['name'].'</p>';
				} else {
					echo '<a class="mainNavSubLink" href="'.$menuOptions['href'].'" '.$menuOptions['target'].'>'.$menuOptions['name']."</a>\n";
					//			}
					//			if ($menuOptions['separatorLine']) {
					//				echo '<table width="100%"><tr><td height="2px" bgcolor="#aaaaff"></td></tr></table>';
				}
			}
			$j++;
		}
	}
}

