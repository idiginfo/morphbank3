<?php
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
