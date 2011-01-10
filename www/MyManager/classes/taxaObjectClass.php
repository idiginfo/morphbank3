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

require_once('mbObjectClass.php');

class taxaObject extends mbObjectClass {

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
		'Annotate'=> TRUE,
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
		$this->callingPage = $config->domain . 'MyManager/content.php?id=taxaTab&';
	}

	function displayResults($resultArray) {
		$num = count($resultArray);
		echo '<br />';
		printLinksNew($this->total, $this->numPerPage, $this->offset, $this->callingPage);
		echo ' <strong>('.$this->total.' Taxa)</strong><br /><br />';
		echo '<div class="TabbedPanelsContent"><div class="imagethumbspage">';
		for ($i=0; $i < $num; $i++) {
			$this->displayResultRow($resultArray[$i], $i);
		}
		echo '</div></div>';
		printLinksNew($this->total, $this->numPerPage, $this->offset, $this->callingPage);
		echo ' <strong>('.$this->total.' Taxa)</strong><br /><br />
		 <span id="num" style="visibility:hidden;">'.$this->numString.'</span>';
	} // end display results

	function displayResultRow($resultArray, $i) {
		$countArray = $this->getTaxaObjectCount($resultArray['tsn']);
		$resultArray = $this->cleanArrayOfSpecialCharacters($resultArray);
		if (empty($resultArray)) return;
		
		//var_dump($resultArray);
		//echo 'Testing....'.$array[$this->sortByFields[11]['field']].' '.$array[$this->sortByFields[0]['field']];

		$colorIndex = $i%2;
		if (strtolower($resultArray['objecttypeid']) == "otu") {
			$this->displayOtuResultRow($resultArray, $i);
		} else {
			//var_dump($resultArray);
			echo '<div id="row'.($i+1).'" class="imagethumbnail" style="background-color:';
			echo $this->color[$colorIndex].';"><table><tr><td class="greenBottomBorder">';
			$this->echoCheckBox($i, 'Taxon='.$resultArray["tsn"]);
			echo '<span  title="Taxonomic Serial Number">'.$resultArray['scientificname'].' [';
			echo $resultArray['tsn'].']';
			echo '<a href="'.$this->domainName.'Browse/ByTaxon/?tsn='.$resultArray['tsn'].'">
				<img border="0" src="'.$this->imgDirectory.'hierarchryIcon.png" title="See hierarchy tree of ['
			.$resultArray['tsn'].']" alt="hierarchy" />
				</a></span></td><td class="browseRight greenBottomBorder">';
			echo printOptions($this->myObjOptions, $resultArray['tsn'],'Taxa').'&nbsp;</td></tr>';
			echo '<tr><td>Taxon Author, Year: '.$resultArray['taxon_author_name'].'</td>
				<td class="browseRight">';
			if ($countArray['specimenCount'] > 0) {
				echo '<a href="javascript: searchTab(\'specimenTab\',\''.$resultArray['tsn'].'\');"> No. Specimen(s): '.$countArray['specimenCount'].'</a>';
			} else {
				echo 'No. Specimen(s): '.$countArray['specimenCount'];
			}
			echo '</td></tr><tr>';
			echo '<td>Taxon Rank / Parent: '.$resultArray['rank_name'].' / '.$resultArray['parent_name'];
			echo '</td><td class="browseRight">';
			if ($countArray['mySpecimenCount'] > 0) {
				echo '<a href="javascript: searchTab(\'specimenTab\',\''.$resultArray['tsn'].' '.$this->userId.'\');">No. My Specimen(s): '.$countArray['mySpecimenCount'].'</a>';
			} else {
				echo 'No. My Specimen(s): '.$countArray['mySpecimenCount'];
			}
			echo '</td></tr><tr><td>';
			if ($resultArray['publicationid'] != 0 && $resultArray['publicationid'] != NULL) {
				echo 'Publication Id: <a href="javascript: openPopup(\''
				.$this->domainName.'Show/?pop=yes&amp;id='.$resultArray['publicationid'].'\');">'
				.$resultArray['publicationid'].'</a>';
			} else {
				echo 'Publication Id: '.$resultArray['publicationid'];
			}
			echo '</td><td class="browseRight">Name Source: '.$resultArray['name_source'].'</td>
				</tr><tr><td>Type of Name: '.$resultArray['nametype'].'</td>
				<td class="browseRight">&nbsp;</td></tr><tr>
				<td>Name Status: '.$resultArray['status'].'</td>
				<td class="browseRight">&nbsp;</td></tr></table>
			</div>';
		} // end else
	}

	function displayOtuResultRow($resultArray, $i) {

		$array = $this->getOtuInfo($resultArray['boid']);
		$colorIndex = $i%2;

		echo' <div id="row'.($i+1).'" class="imagethumbnail" style="background-color:';
		echo $this->color[$colorIndex].';"><table><tr><td class="greenBottomBorder">';
		//var_dump($array);
		echo '<input id="box-'.($i+1).'" type="checkbox" name="object'.($i+1).'" value="Otu='.$array['id'];
		echo '" onclick="swapColor(\''.($i+1).'\')"/><span title="Collection Id"> OTU ['.$array['id'];
		echo ']</span>&nbsp;<a href="'.$this->domainName.'myCollection/index.php?id='.$array['id'];
		echo '">'.$array['title'].'</a>';
		echo '</td><td class="browseRight greenBottomBorder">';
		echo printOptions($this->myObjOptions, $array['id'],$array['objectTypeId']).'</td>';
		echo '</tr><tr><td>Short Label: '.$array['label'].'</td>
			<td class="browseRight">User: '.$array['name'].'</td></tr><tr>
			<td>Date Created: '.$array['dateCreated'].'</td>
			<td class="browseRight">Group: '.$array['groupName'].'</td></tr><tr>		
			<td>Date to Publish: '.$array['dateToPublish'].'</td>';	
		echo'<td class="browseRight">No. Objects: '.$array['objectCount'].'
			<a href="'.$this->domainName.'myCollection/index.php?id='.$array['id'].'">
			<img border="0" src="'.$this->domainName;
		echo 'style/webImages/camera-min16x12.gif" title="List of images" alt="images" /></a>
			</td></tr></table></div>';
	}

	function getOtuInfo($id) {
		$sql = 'SELECT BaseObject.id AS id, BaseObject.userId, BaseObject.groupId, BaseObject.objectTypeId, '
		.'DATE_FORMAT(BaseObject.dateCreated, \'%Y-%m-%d\') AS dateCreated, '
		.'DATE_FORMAT(BaseObject.dateLastModified, \'%Y-%m-%d\') AS dateLastModified, '
		.'DATE_FORMAT(BaseObject.dateToPublish, \'%Y-%m-%d\') AS dateToPublish, '
		.'BaseObject.description, '
		.'BaseObject.name AS title, BaseObject.thumbURL, '
		.'(SELECT COUNT(*) FROM CollectionObjects WHERE CollectionObjects.collectionId = BaseObject.id ) AS objectCount, '
		.'DATE_FORMAT(NOW(), \'%Y-%m-%d\') AS now, '
		.'User.name, Groups.groupName, Otu.label '
		.'FROM  BaseObject INNER JOIN User ON BaseObject.userId = User.id INNER JOIN Otu ON BaseObject.id = Otu.id INNER JOIN Groups ON BaseObject.groupId = Groups.id '
		.'WHERE BaseObject.id = '.$id;

		//echo $sql.'<br /><br /><br />';
		$result = mysqli_query($this->link, $sql) or die(mysqli_error($this->link).$sql);

		if ($result) {
			return mysqli_fetch_array($result);
		}
	}

	function getTaxaObjectCount($tsn) {
		$sql = 'SELECT count(*) as specimenCount FROM Specimen WHERE tsnId ='.$tsn;
		//echo $sql.'<br /><br /><br />';
		$result = mysqli_query($this->link, $sql);
		if ($result) {
			$specimenArray = mysqli_fetch_array($result);
		}
		$sql = 'SELECT count(*) as mySpecimenCount FROM Specimen INNER JOIN BaseObject ON Specimen.id = BaseObject.id WHERE Specimen.tsnId ='.$tsn.' AND BaseObject.userId = '.$this->userId;
		//echo $sql.'<br /><br /><br />';
		$result = mysqli_query($this->link, $sql);
		if ($result) {
			$myArray = mysqli_fetch_array($result);
		}

		$returnArray = array();
		$returnArray['specimenCount'] = $specimenArray['specimenCount'];
		$returnArray['mySpecimenCount'] = $myArray['mySpecimenCount'];
		return $returnArray;
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
					'order' => 'ASC')
		);
	}
}
?>
