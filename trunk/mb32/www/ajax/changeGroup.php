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

include_once ('menu.inc.php');

$groupId = (isset($_GET['groupId'])) ? $_GET['groupId'] : NULL;

	$sql = 'SELECT Groups.id, groupName, Groups.tsn, userGroupRole FROM User, UserGroup, Groups
					WHERE User.uin = \'' . $objInfo->getUserName(). '\' AND User.id = UserGroup.user
					AND UserGroup.groups = Groups.id AND Groups.id = \'' .$groupId. '\';';
	
	$row = mysqli_fetch_array(runQuery($sql));
	
	if($row){
			$nameSql = 'SELECT unit_name1 FROM Tree WHERE tsn = ' .$row['tsn']. ';';
			$name = mysqli_fetch_array(runQuery($nameSql));
	}
	
	if($row ){ //&& $name){
	
			$objInfo->setUserGroupInfo($row['id'], $row['groupName'], $row['userGroupRole'], $row['tsn'], $name['unit_name1']);
			$_SESSION['userInfo'] = serialize($objInfo);
			
			echo '<div id="newGroupId">';
			echo $objInfo->getUserGroup().' ('.$objInfo-> getUserGroupRole().')';
			echo '</div>';
			
			echo '<div id="ajaxToolMenuId">';
			populateToolMenuContents($objInfo);
			echo '</div>';
	} else {
		echo "sql: $sql<br/> name: '$name' FALSE";
	}


?>
