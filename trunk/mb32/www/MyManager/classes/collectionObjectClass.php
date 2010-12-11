#-------------------------------------------------------------------------------
# Copyright (c) 2010 Greg Riccardi, Fredrik Ronquist.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Public License v2.0
# which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# 
# Contributors:
#   Fredrik Ronquist - conceptual modeling and interaction design
#   Austin Mast - conceptual modeling and interaction design
#   Greg Riccardi - initial API and implementation
#   Wilfredo Blanco - initial API and implementation
#   Robert Bruhn - initial API and implementation
#   Christopher Cprek - initial API and implementation
#   David Gaitros - initial API and implementation
#   Neelima Jammigumpula - initial API and implementation
#   Karolina Maneva-Jakimoska - initial API and implementation
#   Katja Seltmann - initial API and implementation
#   Stephen Winner - initial API and implementation
#-------------------------------------------------------------------------------
<?php

require_once('mbObjectClass.php');

class collectionObjectMM extends mbObjectClass {

	public $width;
	public $height;
	public $numString;
	//public $sortByFields;

	function __construct($link, $config, $total) {
		parent::__construct($link, $config->domain, $total);

		$this->myObjOptions = array (	'Info'=> TRUE,
										'Edit'=> FALSE,
										'Annotate'=> FALSE,
										'Select'=> FALSE,
										'Delete'=> FALSE,
										'Copy' => TRUE);
		if ($_GET['pop']) {
			$this->myObjOptions['Info'] = FALSE;
			$this->myObjOptions['Select'] = TRUE;
			$this->myObjOptions['Edit'] = FALSE;
			$this->myObjOptions['Annotate'] = FALSE;
		}
		$this->callingPage = $config->domain . 'MyManager/content.php?id=collectionTab&';
	}

	function displayResults($resultArray) {
		$num = count($resultArray);

		echo '<br />';
		printLinksNew($this->total, $this->numPerPage, $this->offset, $this->callingPage);
		echo ' <strong>('.$this->total.' Collections)</strong><br /><br />';

		echo '<div class="TabbedPanelsContent">
			<div id="imagethumbspage" class="imagethumbspage">';
		for ($i=0; $i < $num; $i++) {
			$this->displayResultRow($resultArray[$i], $i);
		}
		echo '</div></div>';
		printLinksNew($this->total, $this->numPerPage, $this->offset, $this->callingPage);
		echo ' <strong>('.$this->total.' Collections)</strong><br /><br />
		 <span id="num" style="visibility:hidden;">'.$this->numString.'</span>';
	} // end display results

	function displayResultRow($resultArray, $i) {
		//var_dump($resultArray);
		$id = $resultArray['id'];
		$array = $this->getCollectionInfo($id);
		if (empty($array)) return;
		$array = $this->cleanArrayOfSpecialCharacters($array);
		$colorIndex = $i%2;
		$objectTypeId = $array['objectTypeId'];
		$dateToPublish = $array['dateToPublish'];
		$userId = $array['userId'];
		if ($objectTypeId == "MbCharacter") {
			$objectType = "<i>Character</i>";
			$characterEdit = getCharacterEditTag($id);
		} else {
			$objectType = "Collection";
			$characterEdit = '';
		}

		if (isset($_GET['newCollectionId'])) {
			//var_dump($_POST);
			if ($_GET['newCollectionId'] == $array['id'] ) {
				$backgroundColor = "#ff0000;";
			} else {
				$backgroundColor = $this->color[$colorIndex];
			}
		} else {
			$backgroundColor = $this->color[$colorIndex];
		}

		echo'<div id="row'.($i+1).'" class="imagethumbnail" style="background-color:'.$backgroundColor.';">
			<table><tr><td class="greenBottomBorder">';
		$this->echoCheckBox($i, $array['id']);

		echo '<span title="Collection Id"> '.$objectType.' ['.$id.']</span> &nbsp; ';

		echo '<a id="title'.($i+1).'" href="'.showUrl($id) . '" title="Click to view" >'.$array['title'].'</a>';

		// if this is the user's collection and it is not published, then allow to change/edit titile
		if ($dateToPublish > $array['now'] &&  $userId == $this->userId) {
			echo getUpdateTitleTag($id, $i, $this->userId);
		}

		echo '</td>	<td class="browseRight greenBottomBorder">';
		echo printOptions($this->myObjOptions, $id, $objectTypeId).'';
		if ($dateToPublish > $array['now'] &&  $userId == $this->userId) {
			echo "&nbsp;" . $characterEdit;
			echo "&nbsp;" . getCalendarTag($id, $i);
		}
		echo'&nbsp;</td>';
		echo '<td rowspan="4" class="browseRight browseImageCell">';
		echo thumbnailTag($id);
		echo '</td></tr><tr>
				<td>Date Created: '.$array['dateCreated'].'</td>
				<td class="browseRight">User: '.$array['name'].'</td>
				</tr><tr><td>Last Modified: '.$array['dateLastModified'].'</td>
				<td class="browseRight">Group: '.$array['groupName'].'</td>	
				</tr><tr>';
		if ($dateToPublish > $array['now'] &&  $array['userId'] == $this->userId) {
			echo'<td>';
			echo getDateToPublishTag ($id, $i, $dateToPublish, $userId, $this->groupId, $this->now);
			$this->numString .= $i."-";
			echo '</td>';
		} else {
			echo '<td>Date to Publish: '.$array['dateToPublish'].'</td>';
		}
		echo '<td class="browseRight">No. Images: ';
		if ($objectTypeId == "Collection") {
			echo $array['objectCount'].'<a href="'.showUrl($id).'">
				<img border="0" src="'.$this->domainName.'style/webImages/camera-min16x12.gif" title="Click to view" alt="images" /></a>';
		} else {
			echo PhyloCharacter::getImageIds($array['id']).'<a href="'.showUrl($id).'">';
			echo getCameraViewTag();
			echo '</a>';
		}
		echo '</td></tr></table></div>';
	}

	function getCollectionInfo($id) {
		$sql = 'SELECT BaseObject.id AS id, BaseObject.userId, BaseObject.groupId, BaseObject.objectTypeId, '
		.'DATE_FORMAT(BaseObject.dateCreated, \'%Y-%m-%d\') AS dateCreated, '
		.'DATE_FORMAT(BaseObject.dateLastModified, \'%Y-%m-%d\') AS dateLastModified, '
		.'DATE_FORMAT(BaseObject.dateToPublish, \'%Y-%m-%d\') AS dateToPublish, '
		.'BaseObject.description, '
		.'BaseObject.name AS title, BaseObject.thumbURL, '
		.'(SELECT COUNT(*) FROM CollectionObjects WHERE CollectionObjects.collectionId = BaseObject.id ) AS objectCount, '
		.'DATE_FORMAT(NOW(), \'%Y-%m-%d\') AS now, '
		.'User.name, Groups.groupName '
		.'FROM  BaseObject INNER JOIN User ON BaseObject.userId = User.id INNER JOIN Groups ON BaseObject.groupId = Groups.id '
		.'WHERE BaseObject.id = '.$id;

		//echo $sql.'<br /><br /><br />';
		$result = mysqli_query($this->link, $sql) or die(mysqli_error($link));

		if ($result) {
			return mysqli_fetch_array($result);
		}
	}
}

?>
