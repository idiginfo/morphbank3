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

class publicationObject extends mbObjectClass {

	public $img;
	public $width;
	public $height;
	public $numString;
	public $sortByFields;

	function __construct($link, $config, $total) {

		parent::__construct($link, $config->domain, $total);
		$this->setupSortByFields();
		$this->myObjOptions = array (	'Info'=> TRUE,
		'Edit'=> TRUE,
		'Annotate'=> FALSE,
		'Select'=> FALSE,
		'Delete'=> FALSE,
		'Copy' => FALSE);
		if ($_GET['pop']) {
			$this->myObjOptions['Info'] = TRUE;
			$this->myObjOptions['Select'] = TRUE;
			$this->myObjOptions['Edit'] = FALSE;
			$this->myObjOptions['Annotate'] = FALSE;
			$this->myObjOptions['Copy'] = FALSE;
		}
		$this->callingPage = $config->domain . 'MyManager/content.php?id=pubTab&';
	}

	function displayResults($resultArray) {
		$num = count($resultArray);
		//echo $num.'))))))';
		echo '<br />';
		printLinksNew($this->total, $this->numPerPage, $this->offset, $this->callingPage);
		echo ' <strong>('.$this->total.' Publications)</strong><br /><br />';
		echo '
		<div class="TabbedPanelsContent">			
			<div class="imagethumbspage">';
		for ($i=0; $i < $num; $i++) {
			$this->displayResultRow($resultArray[$i], $i);
		}
		echo '</div></div>';
		printLinksNew($this->total, $this->numPerPage, $this->offset, $this->callingPage);
		echo ' <strong>('.$this->total.' Publications)</strong><br /><br />
		 <span id="num" style="visibility:hidden;">'.$this->numString.'</span>';
	} // end display results

	function displayResultRow($resultArray, $i) {
		$array = $this->getPublicationInfo($resultArray['id']);
		if (empty($array)) return;
		$array = $this->cleanArrayOfSpecialCharacters($array);
		$colorIndex = $i%2;
		$publicationTitle = $array[$this->sortByFields[2]['field']].'; '.$array[$this->sortByFields[6]['field']].'; '.$array[$this->sortByFields[7]['field']];
		if (strlen($publicationTitle) > 55)
		$publicationTitle = substr($publicationTitle, 0, 55).'...';
		echo '<div id="row'.($i+1).'" class="imagethumbnail" style="background-color:';
		echo $this->color[$colorIndex].';"><table><tr><td class="greenBottomBorder">';
		$this->echoCheckBox($i, $array['id']);
		echo '<span  title="Location Id">Publication ['.$array[$this->sortByFields[0]['field']].']</span></td>
			<td class="browseRight greenBottomBorder">'.printOptions($this->myObjOptions, $array[$this->sortByFields[0]['field']],'Publication', $publicationTitle).'&nbsp;';
		if ($array['dateToPublish'] > $array['now']) {
			echo'<a href="#" onclick="showCalendar(\''.$array['id'].'\', \'dateTest_';
			echo $i.'\', this); return false;">
				<img id="calId'.$i.'" border="0" src="'.$this->domainName.'style/webImages/calendar.gif" width="16" height="16" alt = "Edit date" title="Edit Date" />
				</a>&nbsp;<a href="#"><img border="0" src="';
			echo $this->domainName;
			echo 'style/webImages/delete-trans.png" width="16" height="16" alt = "delete" title="delete"/></a>';
		}
		echo '&nbsp;</td></tr><tr>
			<td>'.$this->sortByFields[2]['label'].': '.$array[$this->sortByFields[2]['field']].'</td>
			<td class="browseRight">'.$this->sortByFields[5]['label'].' / '.$this->sortByFields[6]['label'].': '.$array[$this->sortByFields[5]['field']].' / '.$array[$this->sortByFields[6]['field']].'</td>
			</tr>';
		echo '<tr>
			<td>'.$this->sortByFields[9]['label'].': '.$array[$this->sortByFields[9]['field']].'</td>
			<td>&nbsp;</td>
			</tr>';
		if (!empty($array[$this->sortByFields[7]['field']])) {
			echo '<tr><td>'.$this->sortByFields[7]['label'].': '.$array[$this->sortByFields[7]['field']];
			echo '</td><td class="browseRight">&nbsp;</td></tr>';
		}
		echo '<tr><td>'.$this->sortByFields[1]['label'].': '.$array[$this->sortByFields[1]['field']];
		echo '</td><td class="browseRight">&nbsp;</td></tr><tr><td>';
		echo $this->sortByFields[4]['label'].': '.$array[$this->sortByFields[4]['field']];
		echo '</td><td class="browseRight">&nbsp;</td></tr><tr>';
		if ($array['dateToPublish'] > $array['now'] &&  $array['userId'] == $this->userId) {
			echo'<td>Date to Publish: <span id="dateTest_'.$i;
			echo '" class="date" title="Click to Change" onclick="showCalendar(\'';
			echo $array['id'].'\', \'dateTest_'.$i.'\', this);">'.$array['dateToPublish'];
			echo '</span><input type="hidden" name="date'.$i.'" id="dateField_'.$i.'" />';
			$this->numString .= $i."-";
			echo '&nbsp;&nbsp;<a href="#" onclick="updateDate(\''.$this->domainName;
			echo 'MyManager/updateDate.php?id='.$array['id'].'&amp;date='.$array['now'];
			echo '&amp;spanId=dateTest_'.$i.'&amp;userId='.$this->userId.'&amp;groupId=';
			echo $this->groupId.'\'); return false;" >(Publish Now)</a></td>';
		} else {
			echo '<td>Date to Publish: '.$array['dateToPublish'].'</td>';					
		}
		echo'<td>&nbsp;</td></tr></table></div>';
	}

	function getPublicationInfo($id) {
		$sql  = 'SELECT Publication.*, BaseObject.*, Groups.groupName, DATE_FORMAT(BaseObject.dateToPublish, \'%Y-%m-%d\') AS dateToPublish, DATE_FORMAT(NOW(), \'%Y-%m-%d\') AS now '
		.'FROM Publication '
		.'LEFT JOIN BaseObject ON BaseObject.id = Publication.id '
		.'LEFT JOIN Groups ON Groups.id = BaseObject.groupId '
		.'WHERE Publication.id = '.$id;
		$result = mysqli_query($this->link, $sql);
		if ($result) {
			return mysqli_fetch_array($result);
		}
	}

	function setupSortByFields() {
		$this->sortByFields= array (	
		0 => array(	'field' => 'id',
					'label' => 'Publication Id',
					'width' => 40,
					'toSort' => true,
					'inGet' => 0,
					'order' => 'DESC'),
		1 => array(	'field' => 'publicationTitle',
					'label' => 'Publication Title',
					'width' => 40,
					'toSort' => true,
					'inGet' => 0,
					'order' => 'ASC'),
		2 => array(	'field' => 'author',
					'label' => 'Author',
					'width' => 60,
					'toSort' => true,
					'inGet' => 0,
					'order' => 'ASC'),
		3 => array(	'field' => 'institution',
					'label' => 'Institution',
					'width' => 50,
					'toSort' => true,
					'inGet' => 0,
					'order' => 'ASC'),
		4 => array(	'field' => 'publicationType',
					'label' => 'Publication Type',
					'width' => 30,
					'toSort' => false,
					'inGet' => 0,
					'order' => 'ASC'),
		5 => array(	'field' => 'volume',
					'label' => 'Volume',
					'width' => 30,
					'toSort' => false,
					'inGet' => 0,
					'order' => 'ASC'),
		6 => array(	'field' => 'year',
					'label' => 'Year',
					'width' => 30,
					'toSort' => true,
					'inGet' => 0,
					'order' => 'ASC'),
		7 => array(	'field' => 'title',
					'label' => 'Article Title',
					'width' => 30,
					'toSort' => true,
					'inGet' => 0,
					'order' => 'ASC'),
		8 => array(	'field' => 'year',
					'label' => 'Year',
					'width' => 30,
					'toSort' => true,
					'inGet' => 0,
					'order' => 'ASC'),
		9 => array(	'field' => 'groupName',
					'label' => 'Group',
					'width' => 60,
					'toSort' => true,
					'inGet' => 0,
					'order' => 'ASC'),
		);
	}
}
?>
