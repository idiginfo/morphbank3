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

include_once ("urlFunctions.inc.php");
$groupArray = getGroupData($id);
$baseObjectArray = getBaseObjectData($id);
$groupMembersArray = getGroupMembers($id);

$memberCount = $groupMembersArray ? count($groupMembersArray) : 0;

$popUrl = (isset($_GET['pop'])) ? "/Show/?pop=Yes&amp;id=" : "/?id=";

if (isset($_GET['pop']))
echo '<div class="popContainer" >';
else
echo '<div class="mainGenericContainer" >';

echo'
		<h1>Group record: ['.$groupArray['id'].'] '.$groupArray['groupName'].'</h1>
		<table class="blueBorder" width="100%">
			<tr>
				<td class="firstColumn" valign="top">
					<div class="popCellPadding" >
											<table border="0"> 
							<tr>
								<th align="right" width="160">Group Name:</th><td>'.$groupArray['groupName'].'</td>
							</tr>
							<tr>
								<th align="right">Group Coordinator:</th><td>'.$groupArray['coordinatorName'].'</td>
							</tr>							
							<tr>
								<th align="right">Coordinator Contact:</th><td>'.$groupArray['email'].'</td>
							</tr>	
							<tr><td colspan="2">&nbsp;</td></tr>		
							<tr>
								<th align="right" width="160">Group Image RSS:</th><td><a href="http://services.morphbank.net/mb2/request?method=search&objecttype=Image&user=&group='.$groupArray['id'].'&format=rss"><img src="/style/webImages/feed-icon-96x96.jpg" width = "20"/></a></td>
							</tr>
						</table>
					</div>
				</td>
				
				<td valign="top">
					<div class="popCellPadding" >
						<table border="0"> 
							<tr>
								<th>Group Since:</th><td>'.$baseObjectArray['dateCreated'].'</td>
								<td rowspan="3"></td>
							</tr>		
							<tr>
							<th>Number of Images In this Group:</th><td><b><font color ="red">'.numberImages($id).'</font></b></td>
							<td rowspan="3"></td>
							</tr>
						</table>
					</div>	
				</td>			
			
			</tr>
		</table>';
$groupId = $groupArray['id'];
$name = $groupArray['groupName'];
getRelatedUsers($groupId, $name);



//returns users of the group names
/*
 if ($memberCount) {
 for ( $i=0; $i < $memberCount; $i++) {
 echo $groupMembersArray[$i]['name'];

 if (($i+1) < $memberCount)
 echo ',';
 echo ' ';
 }

 }
 */


function getRelatedUsers($groupId, $name) {
	global $id, $popUrl, $objInfo, $link, $config;

	$sql = 'SELECT distinct User.name, User.id from User LEFT JOIN UserGroup ON UserGroup.user = User.id where UserGroup.groups = '.$groupId;
	//echo $sql;

	$result = mysqli_query($link, $sql) or die(mysqli_error());
	$objectCount = count($result);
	$thumbLimit = ($objectCount < 25) ? $objectCount : 25;
	echo ' <table class="bottomBlueBorder" width="100%" cellspacing="0" cellpadding="4">
			<tr>
				<td>
					<h3> All of the members of the group '. $name.': </h3><br /><br />
						<ul id="boxes">';

	while ($row = mysqli_fetch_array($result)){
		//TODO use the capabilities of the image handling routines
		$largestHeight = 0;
		for ($i = 0; $i < $objectCount; $i++) {
			$userId[$i] = $row['id'];
			$imgId[$i] = publicImage($groupId,$userId[$i]);
			$thumbUrl[$i] = getImageUrl($imgId[$i], 'thumbs');
			//$size[$i] = getImageSize($thumbPath[$i]);
		}
		$styleHeight = '99px';
		for ($i = 0; $i < $objectCount; $i++) {
			echo '<li style="'.$styleHeight.' padding:2px;">';
			echo thumbnailTag($userId[$i], $thumbUrl[$i]);
		}
	}
	echo '</ul></td></tr></table></div>';
}

function publicImage($groupId, $userId) {
	global $link;
	$sql = "Select * from BaseObject where objectTypeId = 'Image' AND groupId = $groupId "
	."AND (BaseObject.dateToPublish < NOW()) AND userId=$userId limit 1 ";
	//echo $sql;
	$result = mysqli_query($link, $sql) or die(mysqli_error());
	while ($row = mysqli_fetch_array($result)){
		$publicImageId = $row['id'];
		return $publicImageId;
	}
}

function getGroupData ($id) {
	global $link;
	$sql ='SELECT Groups.groupName, Groups.groupManagerId, User.name As coordinatorName, '
	. 'User.email, UserGroup.UserGroupRole AS role, Groups.id '
	.'FROM Groups '
	.'INNER JOIN UserGroup ON Groups.id = UserGroup.`groups` '
	.'INNER JOIN User ON Groups.groupManagerId = User.id  '
	."WHERE Groups.id = $id";
	$result = mysqli_query($link, $sql) or die(mysqli_error($link));
	if ($result) {
		$numRows = mysqli_num_rows($result);
		if ($numRows = 1) {
			$array = mysqli_fetch_array($result);
			return $array;
		}
	}
	return FALSE;
}

function getGroupMembers ($id) {
	global $link;
	$sql = "SELECT User.name FROM UserGroup INNER JOIN User ON UserGroup.user = User.id  WHERE UserGroup.`groups`='".$id."'";
	$results = mysqli_query($link, $sql) or die(mysqli_error($link));
	if ($results) {
		$num = mysqli_num_rows($results);
		for ($i=0; $i < $num; $i++) {
			$array[$i] = mysqli_fetch_array($results);
		}
		return $array;
	} else {
		return FALSE;
	}
}

function numberImages($id) {
	global $link;
	$sql = 'SELECT COUNT(*) from BaseObject where objectTypeId = "Image" AND groupId = '.$id;
	//echo $sql;
	$result = mysqli_query($link, $sql) or die(mysqli_error());
	$count = mysqli_fetch_row($result);
	return $count[0];
}

?>
