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

require_once('mbObjectClass.php');

class annotationObject extends mbObjectClass {

	public $width;
	public $height;
	public $numString;

	function __construct($link, $config, $total) {

		parent::__construct($link, $config->domain, $total);

		$this->myObjOptions = array (	'Info'=> TRUE,
										'Edit'=> TRUE,
										'Annotate'=> FALSE,
										'Select'=> FALSE,
										'Delete'=> FALSE,
										'Copy' => FALSE);
		if ($_GET['pop']) {
			$this->myObjOptions['Info'] = FALSE;
			$this->myObjOptions['Select'] = TRUE;
			$this->myObjOptions['Edit'] = FALSE;
			$this->myObjOptions['Annotate'] = FALSE;
		}
		$this->callingPage = $config->domain . 'MyManager/content.php?id=annotationTab&';
	}

	function displayResults($resultArray) {
		$num = count($resultArray);

		//echo $num.'))))))';
		echo '<br />';
		printLinksNew($this->total, $this->numPerPage, $this->offset, $this->callingPage);
		echo ' <strong>('.$this->total.' Annotations)</strong><br /><br />';
		echo '<div class="TabbedPanelsContent">
			<div class="imagethumbspage">';
			
		for ($i=0; $i < $num; $i++) {
			$this->displayResultRow($resultArray[$i], $i);
		}

		echo '</div></div>';
		printLinksNew($this->total, $this->numPerPage, $this->offset, $this->callingPage);
		echo ' <strong>('.$this->total.' Annotations)</strong><br /><br />
		 <span id="num" style="visibility:hidden;">'.$this->numString.'</span>';
	} // end display results

	function displayResultRow($resultArray, $i) {
		$id = $resultArray['id'];
		$array = $this->getAnnotationInfo($id);
		if (empty($array)) return;
		$array = $this->cleanArrayOfSpecialCharacters($array);
		$colorIndex = $i%2;
		echo '<div id="row'.($i+1).'" class="imagethumbnail" style="background-color:'.$this->color[$colorIndex].';">
			<table><tr><td class="greenBottomBorder">';
		//<input id="box-'.($i+1).'" type="checkbox" name="object'.($i+1).'" value="'.$array['id'].'" onclick="swapColor(\''.($i+1).'\')"/>

		$this->echoCheckBox($i, $array['id']);
		$id = $array['id'];
		echo '<span  title="Location Id"> '.$array['typeAnnotation'].' Annotation ['.$id.']</span> &nbsp; ';
		if ($array['dateToPublish'] <= $array['now']) {
			echo '<a id="title'.($i+1).'" href="javascript: openPopup(\''.$this->domainName.'Show/?pop=yes&id='.$array['id'].'\')">'.$array['title'].'</a>';
		} else {
			echo $array['title'];
		}
		echo '</td><td class="browseRight greenBottomBorder">';
		echo printOptions($this->myObjOptions, $array['id'], $array['typeAnnotation']).'&nbsp;';
		if ($array['dateToPublish'] > $array['now'] &&  $array['userId'] == $this->userId) {
			echo'<a href="#" onclick="showCalendar(\''.$array['id'].'\', \'dateTest_'.$i.'\', this); return false;">
					<img id="calId'.$i.'" border="0" src="'.$this->domainName.'style/webImages/calendar.gif" width="16" height="16" alt = "Edit date" title="Edit Date" />
				</a>&nbsp;
				<a href="javascript: deleteObjects(\''.$this->domainName.'MyManager/deleteObjects.php?id='.$array['id'].'&div=row'.($i+1).'&userId='.$this->userId.'\');">
					<img border="0" src="'.$this->domainName.'style/webImages/delete-trans.png" width="16" height="16" alt = "delete" title="delete" />
				</a>';
		}
		echo'&nbsp;</td>';
		echo thumbnailTag($id);
		echo '</td>';
		echo '</tr><tr><td>Date Created: '.$array['dateCreated'].'</td>
			<td class="browseRight">User: '.$array['name'].'</td></tr>
			<tr><td>Last Modified: '.$array['dateLastModified'].'</td>
			<td class="browseRight">&nbsp;</td></tr><tr>';
		if ($array['dateToPublish'] > $array['now'] &&  $array['userId'] == $this->userId) {
			echo'<td>Date to Publish: <span id="dateTest_'.$i.'" class="date" title="Click to Change" onclick="showCalendar(\''.$array['id'].'\', \'dateTest_'.$i.'\', this);">'.$array['dateToPublish'].'</span><input type="hidden" name="date'.$i.'" id="dateField_'.$i.'" />';
			$this->numString .= $i."-";
			echo '&nbsp;&nbsp;<a href="#" onclick="updateDate(\''.$this->domainName.'MyManager/updateDate.php?id='.$array['id'].'&amp;date='.$array['now'].'&amp;spanId=dateTest_'.$i.'&amp;userId='.$this->userId.'&amp;groupId='.$this->groupId.'\'); return false;" >(Publish Now)</a>';
			echo '</td>';
		} else {
			echo '<td>Date to Publish: '.$array['dateToPublish'].'</td>';
		}
		echo '<td>&nbsp;</td></tr></table></div>';
	}

	function getAnnotationInfo($id) {
		$sql = 'SELECT Annotation.*, Annotation.id AS id,  '
		.'DATE_FORMAT(BaseObject.dateCreated, \'%Y-%m-%d\') AS dateCreated, '
		.'DATE_FORMAT(BaseObject.dateLastModified, \'%Y-%m-%d\') AS dateLastModified, '
		.'DATE_FORMAT(BaseObject.dateToPublish, \'%Y-%m-%d\') AS dateToPublish, '
		.'DATE_FORMAT(NOW(), \'%Y-%m-%d\') AS now, BaseObject.userId, BaseObject.groupId, BaseObject.thumbURL, '
		.'User.name '
		.'FROM Annotation INNER JOIN BaseObject ON Annotation.id = BaseObject.id INNER JOIN User ON BaseObject.userId = User.id '
		.'WHERE Annotation.id = '.$id;

		//echo $sql.'<br /><br /><br />';
		$result = mysqli_query($this->link, $sql);
		if ($result) {
			return mysqli_fetch_array($result);
		}
	}
}
?>
