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

class viewObject extends mbObjectClass {

	public $img;
	public $width;
	public $height;
	public $sortByFields;

	function __construct($link, $config, $total) {
		$this->config = $config;
		
		parent::__construct($link, $this->config->domain, $total);
		// setup the object, dblink and variables.
		$this->setupSortByFields();
		$this->myObjOptions = array (	'Info'=> TRUE,
										'Edit'=> TRUE,
										'Annotate'=> FALSE,
										'Select'=> FALSE,
										'Delete'=> FALSE,
										'Copy' => FALSE);
		if (isset($_GET['pop'])) {
			$this->myObjOptions['Info'] = FALSE;
			$this->myObjOptions['Select'] = TRUE;
			$this->myObjOptions['Edit'] = FALSE;
			$this->myObjOptions['Annotate'] = FALSE;
		}
		$this->callingPage = $this->config->domain . 'MyManager/content.php?id=viewTab&';
	}

	function displayResults($resultArray) {
		$num = count($resultArray);
		//echo $num.'))))))';
		echo '<br />';
		printLinksNew($this->total, $this->numPerPage, $this->offset, $this->callingPage);
		echo '<strong>('.$this->total.' Views)</strong><br /><br />';
		echo '<div class="TabbedPanelsContent">
			<div class="imagethumbspage">';
		for ($i=0; $i < $num; $i++) {
			$this->displayResultRow($resultArray[$i], $i);
		}
		echo '</div></div>';
		printLinksNew($this->total, $this->numPerPage, $this->offset, $this->callingPage);
		echo ' <strong>('.$this->total.' Views)</strong><br /><br />';
	} // end display results

	function displayResultRow($resultArray, $i) {
		$array = $this->getViewInfo($resultArray['id']);
		if (empty($array)) {echo "No view for ".$resultArray['id'];return;} // no row
		
		$array = $this->cleanArrayOfSpecialCharacters($array);

		$colorIndex = $i%2;
		$url = $this->config->domain . 'Browse/ByImage/?viewId_Kw=id&amp;viewKeywords='.$viewId;
		$opener = ($_GET['opener'] == "true")? "opener.opener":"opener";
		$showCameraHtml = "";
		if ($array[$this->sortByFields[12]['field']]) { // not pop and imagesCount > 0
			//$showCameraHtml = '<a href="javascript: searchTab(\'imageTab\', \''.$array[$this->sortByFields[0]['field']].'\');">
			$showCameraHtml ='<a href="'.$url.'" target="_blank">
			<img border="0" src="'.$this->imgDirectory.'camera-min16x12.gif" alt="images" title="List of images" /></a>';
		}
		// get the tsn Name
		$tsnName = getTsnName( $array['viewTSN']); // return tsnName['name'] and tsnName['tsn']
		$id = $resultArray['id'];
		echo'<div id="row'.($i+1).'" class="imagethumbnail" style="background-color:';
		echo $this->color[$colorIndex].';"><table><tr><td class="greenBottomBorder">';
		//echo '<input id="box-'.($i+1).'" type="checkbox" name="object'.($i+1).'" value="'.$array[$this->sortByFields[0]['field']].'" onclick="swapColor(\''.($i+1).'\')"/>&nbsp;
		$this->echoCheckBox($i, $array[$this->sortByFields[0]['field']]);
		echo '<span title="View id">View ['.$id;
		echo ']</span>&nbsp; '.$array[$this->sortByFields[2]['field']].'/';
		echo $array[$this->sortByFields[3]['field']].'/'.$array[$this->sortByFields[4]['field']];
		echo '</td><td class="browseRight greenBottomBorder">';
		echo printOptions($this->myObjOptions, $array[$this->sortByFields[0]['field']],'View',
		$array[$this->sortByFields[2]['field']] . '/'
		. $array[$this->sortByFields[3]['field']].'/'
		. $array[$this->sortByFields[4]['field']]);
		echo '</td><td rowspan="4" class="browseRight browseImageCell">';
		echo thumbnailTag($id);
		echo '</td></tr>
		<tr><td>Stage/Form: '.$array[$this->sortByFields[5]['field']].'/'.$array[$this->sortByFields[6]['field']].'</td>
		<td class="browseRight"><div style="white-space:nowrap;">'.$this->sortByFields[12]['label'].': '.$array[$this->sortByFields[12]['field']].$showCameraHtml.'</div></td>
		</tr><tr>
		<td>Technique: '.$array[$this->sortByFields[7]['field']].'/'.$array[$this->sortByFields[8]['field']].'</td>
		<td class="browseRight">&nbsp;</td>
		</tr><tr><td>'.$this->sortByFields[10]['label'].': '.printTsnNameLinks( $tsnName).'</td>
		<td class="browseRight">&nbsp;</td></tr></table></div>';
	}

	function getViewInfo($id) {
		$sql  = 'SELECT View.id as id, View.viewName as viewName, View.standardImageId,
		View.imagesCount as imagesCount,
		View.imagingTechnique as imagingTechnique,
		View.imagingPreparationTechnique as imagingPreparationTechnique,
		View.specimenPart as specimenPart,
		View.viewAngle as viewAngle,
		View.developmentalStage as developmentalStage,
		View.sex as sex,
		View.form as form,
		View.viewTSN FROM View ';
		$sql .= 'WHERE  View.id = '.$id;
		$result = mysqli_query($this->link, $sql) or die(mysqli_error($this->link).$sql);
		if ($result) {
			return mysqli_fetch_array($result);
		}
	}

	function setupSortByFields() {
		$this->sortByFields = array ( 	array(	'field' => 'id',
												'label' => 'View id',
												'width' => 40,
												'toSort' => true,
												'inGet' => 0,
												'order' => 'DESC'),
		array(	'field' => 'viewName',
												'label' => 'View Name',
												'width' => 40,
												'toSort' => false,
												'inGet' => 0,
												'order' => 'ASC'),
		array(	'field' => 'specimenPart',
												'label' => 'Specimen Part',
												'width' => 60,
												'toSort' => true,
												'inGet' => 0,
												'order' => 'ASC'),
		array(	'field' => 'viewAngle',
												'label' => 'Angle',
												'width' => 50,
												'toSort' => true,
												'inGet' => 0,
												'order' => 'ASC'),
		array(	'field' => 'sex',
												'label' => 'Sex',
												'width' => 30,
												'toSort' => true,
												'inGet' => 0,
												'order' => 'ASC'),
		array(	'field' => 'developmentalStage',
												'label' => 'Stage',
												'width' => 30,
												'toSort' => true,
												'inGet' => 0,
												'order' => 'ASC'),
		array(	'field' => 'form',
												'label' => 'Form',
												'width' => 30,
												'toSort' => true,
												'inGet' => 0,
												'order' => 'ASC'),
		array(	'field' => 'imagingTechnique',
												'label' => 'Imaging',
												'width' => 30,
												'toSort' => true,
												'inGet' => 0,
												'order' => 'ASC'),
		array(	'field' => 'imagingPreparationTechnique',
												'label' => 'Preparation',
												'width' => 30,
												'toSort' => true,
												'inGet' => 0,
												'order' => 'ASC'),
		array(	'field' => 'viewTSN',
												'label' => 'Taxon Id',
												'width' => 40,
												'toSort' => true,
												'inGet' => 0,
												'order' => 'ASC'),		
		array(	'field' => 'viewTSName',
												'label' => 'Taxon name',
												'width' => 130,
												'toSort' => true,
												'inGet' => 0,
												'order' => 'ASC'),
		array(	'field' => 'standardImageId',
												'label' => 'Image prototype',
												'width' => 0,
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
