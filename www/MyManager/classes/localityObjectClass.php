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

class localityObject extends mbObjectClass {

	public $img;
	public $width;
	public $height;
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
			$this->myObjOptions['Info'] = FALSE;
			$this->myObjOptions['Select'] = TRUE;
			$this->myObjOptions['Edit'] = FALSE;
			$this->myObjOptions['Annotate'] = FALSE;
		}
		$this->callingPage = $config->domain . 'MyManager/content.php?id=localityTab&';
	}

	function displayResults($resultArray) {
		$num = count($resultArray);
		echo '<br />';
		printLinksNew($this->total, $this->numPerPage, $this->offset, $this->callingPage);
		echo '<strong>('.$this->total.' Localities)</strong><br /><br />';
		echo '<div class="TabbedPanelsContent">	<div class="imagethumbspage">';
			
		for ($i=0; $i < $num; $i++) {
			$this->displayResultRow($resultArray[$i], $i);
		}
		echo '</div></div>';
		printLinksNew($this->total, $this->numPerPage, $this->offset, $this->callingPage);
		echo ' <strong>('.$this->total.' Localities)</strong><br /><br />';
	} // end display results

	function displayResultRow($resultArray, $i) {
		
		include_once('coordValue.php');
		$array = $this->getLocalityInfo($resultArray['id']);
		if (empty($array)) return;
		$array = $this->cleanArrayOfSpecialCharacters($array);
		$colorIndex = $i%2;
		$showCameraHtml = "";
		if (!$_GET['pop'] && $array[$this->sortByFields[11]['field']]){ // no pop and imagesCount > 0
			$showCameraHtml = '<a href="'.$this->domainName.'Browse/ByImage/?localityId='.$array[$this->sortByFields[0]['field']].'">
				<img border="0" src="'.$this->imgDirectory.'camera-min16x12.gif" title="List of images" alt="link"/></a>';
		}
		echo '
		<div id="row'.($i+1).'" class="imagethumbnail" style="background-color:'.$this->color[$colorIndex].';">
				<table><tr><td class="greenBottomBorder">';
		$this->echoCheckBox($i, $array['id']);

		echo '<span  title="Location Id"> Locality ['.$array[$this->sortByFields[0]['field']];
		//TODO get the layout correct

		echo ']</span>&nbsp;'.$array[$this->sortByFields[1]['field']].' / ';
		echo $array[$this->sortByFields[2]['field']].'</td>';
		echo '<td class="browseRight greenBottomBorder">';
		echo printOptions($this->myObjOptions, $array[$this->sortByFields[0]['field']],'Location'
		,$array[$this->sortByFields[3]['field']]);
		echo '</td></tr><tr><td>';
		echo $this->sortByFields[3]['label'].': '.$array[$this->sortByFields[3]['field']]
		.'</td><td class="browseRight">'.$this->sortByFields[11]['label'].': '
		.$array[$this->sortByFields[11]['field']].$showCameraHtml
		.'</td></tr><tr><td>'.$this->sortByFields[4]['label'].'/' .$this->sortByFields[5]['label']. ': ';
		echo truncateValue($array[$this->sortByFields[4]['field']]). '/';
		echo truncateValue($array[$this->sortByFields[5]['field']]);
		echo '</td><td class="browseRight">&nbsp;</td></tr>
			<tr><tr><td>Elevation (m) : ';
		if ($array[$this->sortByFields[7]['field']] == $array[$this->sortByFields[8]['field']]) {
			echo $array[$this->sortByFields[7]['field']];
		} else {
			echo $array[$this->sortByFields[7]['field']].' - '.$array[$this->sortByFields[8]['field']];
		}
		echo '</td><td class="browseRight">&nbsp;</td></tr></table></div>';
	}

	function getLocalityInfo($id) {
		//modified by katja seltmann july 2008 update sql statement to reflect new db
		$sql  = 'SELECT Locality.id as id,
					Locality.continentOcean as ContinentOcean, 
					Locality.country as Country, 
					Locality.longitude, 
					Locality.latitude, 
					Locality.imagesCount,
					Locality.locality, 
					Locality.minimumElevation, 
					Locality.maximumElevation, 
					User.name, 
					Groups.groupName 
					FROM Locality 
					INNER JOIN BaseObject ON Locality.id = BaseObject.id 
					INNER JOIN User ON BaseObject.userId = User.id 
					INNER JOIN Groups ON BaseObject.groupId = Groups.id ';
		$sql .= 'WHERE  Locality.id = '.$id;
		//echo $sql.'<br /><br /><br />';
		$result = mysqli_query($this->link, $sql);
		if ($result) {
			return mysqli_fetch_array($result);
		}
	}

	function setupSortByFields() {
		$this->sortByFields = array ( 	array(	'field' => 'id',
												'label' => 'Location Id',
												'width' => 40,
												'toSort' => true,
												'inGet' => 0,
												'order' => 'DESC'),
		array(	'field' => 'ContinentOcean',
												'label' => 'Continent Ocean',
												'width' => 40,
												'toSort' => true,
												'inGet' => 0,
												'order' => 'ASC'),
		array(	'field' => 'Country',
												'label' => 'Country',
												'width' => 60,
												'toSort' => true,
												'inGet' => 0,
												'order' => 'ASC'),
		array(	'field' => 'locality',
												'label' => 'Locality',
												'width' => 50,
												'toSort' => true,
												'inGet' => 0,
												'order' => 'ASC'),
		array(	'field' => 'latitude',
												'label' => 'Latitude',
												'width' => 30,
												'toSort' => false,
												'inGet' => 0,
												'order' => 'ASC'),
		array(	'field' => 'longitude',
												'label' => 'Longitude',
												'width' => 30,
												'toSort' => false,
												'inGet' => 0,
												'order' => 'ASC'),
		array(	'field' => 'coordinatePrecision',
												'label' => 'Coordinate Precision',
												'width' => 30,
												'toSort' => false,
												'inGet' => 0,
												'order' => 'ASC'),
		array(	'field' => 'minimumElevation',
												'label' => 'Minimum Elevation',
												'width' => 30,
												'toSort' => false,
												'inGet' => 0,
												'order' => 'ASC'),
		array(	'field' => 'maximumElevation',
												'label' => 'Maximum Elevation',
												'width' => 30,
												'toSort' => false,
												'inGet' => 0,
												'order' => 'ASC'),
		array(	'field' => 'minimumDepth',
												'label' => 'Minimum Depth',
												'width' => 30,
												'toSort' => false,
												'inGet' => 0,
												'order' => 'ASC'),
		array(	'field' => 'maximumDepth',
												'label' => 'Maximum Depth',
												'width' => 30,
												'toSort' => false,
												'inGet' => 0,
												'order' => 'ASC'),
		array(	'field' => 'imagesCount',
												'label' => 'No. Images',
												'width' => 50,
												'toSort' => true,
												'inGet' => 0,
												'order' => 'ASC')
		);
	}
}
?>
