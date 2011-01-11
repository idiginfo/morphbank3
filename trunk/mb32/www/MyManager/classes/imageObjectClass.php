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

class imageObject extends mbObjectClass {

	public $width;
	public $height;
	public $jpeg;
	public $tiff;
	public $numString;

	function __construct($link, $config, $total) {

		parent::__construct($link, $config->domain, $total);

		// setup a new mbImage object with no id.  Passing only the link will
		// setup the object, dblink and variables.
		$this->imgObject = new mbImage($this->link);

		$this->myObjOptions = array (	'Info'=> TRUE,
										'Edit'=> TRUE,
										'Viewer' => TRUE,
										'Annotate'=> TRUE,
										'Select'=> FALSE,
										'Delete'=> FALSE,
										'Copy' => FALSE);

		if (isset($_GET['pop'])) {
			$this->myObjOptions['Info'] = FALSE;
			$this->myObjOptions['Select'] = TRUE;
			$this->myObjOptions['Edit'] = FALSE;
			$this->myObjOptions['Annotate'] = FALSE;
		}

		$this->callingPage = $config->domain . 'MyManager/content.php?id=imageTab&';
	}

	function displayResults($resultArray) {
		$num = count($resultArray);
		if ($num==null) {
			$num=0;
			echo "display Results: no rows!<br/>\n";
		}
		$this->numString = "";

		//echo $num.'))))))';
		echo '<br />';
		printLinksNew($this->total, $this->numPerPage, $this->offset, $this->callingPage);
		echo ' <strong>('.$this->total.' Images)</strong><br /><br />';
		echo '<div class="TabbedPanelsContent"><div class="imagethumbspage">';

		//var_dump($this->objInfoRef);
		for ($i=0; $i < $num; $i++) {
			$this->displayResultRow($resultArray[$i], $i);
		}

		echo '</div></div>';
		printLinksNew($this->total, $this->numPerPage, $this->offset, $this->callingPage);
		echo ' <strong>('.$this->total.' Images)</strong><br /><br />
		 <span id="num" style="visibility:hidden;">'.$this->numString.'</span>';

	} // end display results

	function displayResultRow($resultArray, $i) {

		$id = $resultArray['id'];
		$array = $this->getImageInfo($id);
		if (empty($array)) return;
		
		$array = $this->cleanArrayOfSpecialCharacters($array);
		// set the object to the id of the image to be displayed
		$this->imgObject->setNewImage($id);
		// get the url of the thumbnail that goes in the scr="" of the img tag
		$this->img = $this->imgObject->getImgUrl();
		// get url for the jpeg
		$this->jpeg = $this->imgObject->getJpeg();
		// get url for original image.  This should be a download link.
		$this->tiff = $this->imgObject->getImgUrlOriginal();
		$this->originalUrl = $this->imgObject->getImgUrlOriginal();
		
		$colorIndex = $i%2;
		$tsnName = getTsnName($array['tsn']);

		echo '<div id="row'.($i+1).'" class="imagethumbnail" style="background-color:';
		echo $this->color[$colorIndex];
		echo ';">';
		echo '<table><tr><td class="greenBottomBorder">';
		//if( (!isset($_GET['pop'])) && ($objInfo->getUserId() != NULL))
		echo '<input id="box-'.($i+1).'" type="checkbox" name="object'.($i+1);
		echo '" value="'.$id.'" onclick="swapColor(\''.($i+1).'\')"/>&nbsp;';
		echo'<span>Image ['.$id.']</span> &nbsp;';
		echo printTsnNameLinks( $tsnName);
		echo '</td><td class="browseRight greenBottomBorder">';
		echo printOptions($this->myObjOptions, $array['imageId'],'Image');
		echo '&nbsp;';
		if ($array['dateToPublish'] > $array['now'] &&  $array['userId'] == $this->userId) {
			echo showCalendarTag($i,$array['imageId']);
		}
		echo '</td><td rowspan="5" class="browseRight browseImageCell">';
		echo thumbnailTag($id, $this->img);
		echo '</td></tr><tr><td>View: ';
		echo $array['specimenPartName'].'/'.$array['viewAngleName'].'</td>';
		echo '<td class="browseRight">Dim: '.$array['imageWidth'].'x'.$array['imageHeight'].'</td>';
		echo '</tr><tr>';
		echo '<td>Specimen: ';
		echo $array['sexName'].'/'.$array['specimenDevelStageName'].'/'.$array['specimenFormName'].'</td>';
		echo '<td class="browseRight">[<a href="'.$this->jpeg.'">jpeg</a>]';
		echo ' &nbsp; [<a href="'.$this->tiff.'">original</a>]</td>';
		echo '</tr><tr>';
		echo '<td>Technique: '.$array['imagingTechniqueName'].'/'.$array['imagingPreparationTechniqueName'];
		echo '</td><td class="browseRight">Original: '.$array['imageType'].'</td>';
		echo '</tr><tr>';
		echo '<td>';
		$userId = $array['userId'];
		$groupId = $array['groupId'];
		echo showUserGroup($userId, $array['name'], $groupId, $array['groupName']);
		echo '</td>';
		echo '</tr><tr>';
		//TODO get publish status from query
		if ($array['dateToPublish'] > $array['now'] &&  $resultArray['userid'] == $this->userId) {
			echo'<td>Date to Publish: <span id="dateTest_'.$i.'" class="date" title="Click to Change"  ';
			echo 'onclick="showCalendar(\''.$array['imageId'].'\', \'dateTest_'.$i.'\', this);">';
			echo $array['dateToPublish'].'</span><input type="hidden" name="date'.$i.'" id="dateField_';
			echo $i.'" />';
			$this->numString .= $i."-";
			echo '&nbsp;&nbsp';
			echo publishNowTag($array['imageId'],$array['now'],$i,$this->userId,$this->groupId);
		} else {
			echo'<td>Date to Publish: '.$array['dateToPublish'].'</td>';
		}
		echo '<td class="browseRight">';
		$count = $this->getAnnotationCount($array['imageId']);
		if ($count == 0)
		echo 'No. Annotations: '.$count;
		else
		echo '<a href="javascript: searchTab(\'annotationTab\', \''.$array['imageId'].'\');">No. Annotations:</a> '.$count;
		echo '</td></tr></table></div>';
	}

	function getImageInfo($id) {
		$sql = 'SELECT Image.id as imageId,
					imageHeight, imageWidth, imageType, accessNum,
					BaseObject.dateToPublish AS dateToPublish,
					View.imagingTechnique as imagingTechniqueName,
					View.imagingPreparationTechnique as imagingPreparationTechniqueName,
					View.specimenPart as specimenPartName,
					View.viewName as viewName,
					Specimen.developmentalStage as specimenDevelStageName,
					View.viewAngle as viewAngleName,
					Specimen.form as specimenFormName,
					Specimen.tsnId as tsn,
					Specimen.sex as sexName,
					Groups.groupName as groupName, Groups.id as groupId,
					User.name as name, User.id as userId,
					DATE_FORMAT(NOW(), \'%Y-%m-%d\') AS now
				FROM BaseObject join Image on BaseObject.id=Image.id
					LEFT JOIN Specimen ON Image.specimenId = Specimen.id
					LEFT JOIN View ON Image.viewId = View.id
					LEFT JOIN User ON Image.userId = User.id
					LEFT JOIN Groups ON Image.groupId = Groups.id
					LEFT JOIN BasisOfRecord ON Specimen.basisOfRecordId = BasisOfRecord.name
					LEFT JOIN Locality ON Specimen.localityId = Locality.id
					LEFT JOIN ContinentOcean ON Locality.continentOcean = ContinentOcean.name
					LEFT JOIN Country ON Locality.country = Country.name 
		 		WHERE Image.id = '.$id;

		$result = mysqli_query($this->link, $sql);
		if ($result) {
			return mysqli_fetch_array($result);
		}
	}
}
?>
