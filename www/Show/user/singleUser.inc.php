<?php

$userArray = getUserData($id);
$baseObjectArray = getBaseObjectData($id);
$popUrl = (isset($_GET['pop'])) ? "/Show/?pop=Yes&amp;id=" : "/?id=";
if (isset($_GET['pop'])) {
	echo '<div class="popContainer">';
} else {
	echo '<div class="mainGenericContainer" >';
	$userId = $userArray['id'];
	$name = $userArray['name'];
	$preferredGroup = $userArray['preferredGroup'];
	//echo $preferredGroup;
}
echo' 		<h2>User record: ['.$userArray['id'].'] '.$userArray['name'].'</h2>
		<table class="blueBorder" width="100%">
			<tr>
				<td class="firstColumn" valign="top">
					<div class="popCellPadding" >
											<table border="0"> 
							<tr>
								<th>Name:</th><td>'.$userArray['name'].'</td>	

							</tr>
							<tr>
								<th>Email:</th><td><img src="'.$config->domain.'includes/mail.php?id='.$userArray['id'].'" border="0" alt="email" /></td>								
							</tr>
							<tr>
								<th>Affiliation:</th><td>'.$userArray['affiliation'].'</td>
							</tr>							
							<tr>
								<th>Country:</th><td>'.$userArray['country'].'</td>
							</tr>	
							<tr>
							<th align="right" width="160">User Image RSS:</th><td><a href="http://services.morphbank.net/mb2/request?method=search&objecttype=Image&user='.$userArray['id'].'&group=&format=rss"><img src="/style/webImages/feed-icon-96x96.jpg" width = "20"/></a></td>
							</tr>
						</table>
					</div>
				</td>
				<td valign="top">
					<div class="popCellPadding" >
						<table border="0"> 
							<tr>
								<th>User Since:</th><td>'.whenCreated($id).'</td>
								<td rowspan="3"></td>
							</tr>		
							<tr>
							<th>Number of Images Contributed:</th><td><b><font color ="red">'.numberImages($userId).'</font></b></td>
							<td rowspan="3"></td>
							</tr>
							<tr>
								<th>User Logo:</th><td>'; echo showUserLogo($userArray['id']); echo'</td>
								<td rowspan="3"></td>
							</tr>		
						</table>
					</div>	
				</td>			
			</tr>
		</table>';
getRelatedGroups($userId, $name);

/**
 *	Function to return an array of group id's for this user
 */
function getRelatedGroups($userId, $name) {
	global $id, $popUrl, $objInfo, $link, $config;
	$sql = 'SELECT distinct Groups.groupName, Groups.id from Groups LEFT JOIN UserGroup ON UserGroup.groups = Groups.id where UserGroup.user = '.$userId;
	//echo $sql;
	$result = mysqli_query($link, $sql) or die(mysqli_error());
	$objectCount = count($result);
	$thumbLimit = ($objectCount < 25) ? $objectCount : 25;
	echo '<table class="bottomBlueBorder" width="100%" cellspacing="0" cellpadding="0"><tr>';
	echo '<td valign="bottom"><h3>'. $name.' is a member of the Group:</h3><br/><br/><ul id="boxes">';
	while ($row = mysqli_fetch_array($result)){
		$largestHeight = 0;
		for ($i = 0; $i < $thumbLimit; $i++) {
			$groupId = $row['id'];
			$imgId = publicImage($groupId);
			$thumbUrl[$i] = getImageUrl($imgId, 'thumbs');

			$size = getSafeImageSize(null);//TODO get rid of size calculation
			//$largestHeight = ($size[1] > $largestHeight) ? $size[1] : $largestHeight;
		}
		$styleHeight = '99px';
		for ($i = 0; $i < $objectCount; $i++) {
			echo '<li style="'.$styleHeight.' padding:2px;"><a href="'.$popUrl.$row['id'].'" >';
			echo '<img src="'.$thumbUrl[$i].'" height="68px" alt="thumb" title="Thumbnail" /></a><br />'
			.$row['groupName'].'</li>';
		}
	}
	echo '</ul></td></tr></table></div>';
}

function publicImage($groupId) {
	global $link;
	$sql = 'Select * from BaseObject where objectTypeId = "Image" AND groupId = '.$groupId.' AND (BaseObject.dateToPublish < NOW()) limit 1';
	//echo $sql;
	$result = mysqli_query($link, $sql) or die(mysqli_error());
	while ($row = mysqli_fetch_array($result)){
		$publicImageId = $row['id'];
		return $publicImageId;
	}
}

function numberImages($userId) {
	global $link;
	$sql = 'SELECT COUNT(*) from BaseObject where objectTypeId = "Image" AND userId = '.$userId;
	//echo $sql;
	$result = mysqli_query($link, $sql) or die(mysqli_error());
	$count = mysqli_fetch_row($result);
	return $count[0];
}

function getUserData ($id) {
	global $link;
	$sql ='SELECT * FROM User WHERE User.id  = '.$id.'';
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
?>
