<?php

require_once('mbObjectClass.php');

class specimenObject extends mbObjectClass {

	public $img;
	public $width;
	public $height;
	public $sortByFields;

	function __construct($link, $config, $total) {
		parent::__construct($link, $config->domain, $total);

		//$this->imgObject = new mbImage($this->link);
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
		$this->callingPage = $config->domain . 'MyManager/content.php?id=specimenTab&';
	}

	function displayResults($resultArray) {
		$num = count($resultArray);

		echo '<br />';
		printLinksNew($this->total, $this->numPerPage, $this->offset, $this->callingPage);
		echo ' <strong>('.$this->total.' Specimens)</strong><br /><br />';
		echo '<div class="TabbedPanelsContent"><div class="imagethumbspage">';

		for ($i=0; $i < $num; $i++) {
			$this->displayResultRow($resultArray[$i], $i);
		}
		echo '</div></div>';
		printLinksNew($this->total, $this->numPerPage, $this->offset, $this->callingPage);
		echo ' <strong>('.$this->total.' Specimens)</strong><br /><br />';
	} // end display results

	function displayResultRow($resultArray, $i) {
		$id = $resultArray['id'];
		$array = $this->getSpecimenInfo($id);
		if (empty($array)) {echo "No specimen for $id";return;} // no row
		$array = $this->cleanArrayOfSpecialCharacters($array);
		//$imgUrl = getObjectImageUrl($id, "thumbs");
		//$imgId = $array[$this->sortByFields[13]['field']];//TODO thumbUrl
		//$this->imgObject->setNewImage($imgId);
		//$this->img = $this->imgObject->getImgUrl();
		$colorIndex = $i%2;
		$showCameraHtml = "";
		if (!$_GET['pop'] && $array[$this->sortByFields[10]['field']]) // not pop and imagesCount > 0
		$showCameraHtml = '<a href="'.$this->domainName.'Browse/ByImage/?specimenId='.$array[$this->sortByFields[0]['field']].'">
					<img border="0" src="'.$this->imgDirectory.'camera-min16x12.gif" alt="images" title="List of images" /></a>';
			
		// get the tsn Name
		$tsnName = getTsnName( $array[$this->sortByFields[9]['field']]); // return tsnName['name'] and tsnName['tsn']
		echo'
		<div id="row'.($i+1).'" class="imagethumbnail" style="background-color:'.$this->color[$colorIndex].';">
			<table>
				<tr>
					<td class="greenBottomBorder">';
		//echo '<input id="box-'.($i+1).'" type="checkbox" name="object'.($i+1).'" value="'.$array[$this->sortByFields[0]['field']].'" onclick="swapColor(\''.($i+1).'\')"/>&nbsp;
			
		$this->echoCheckBox($i, $id);
		echo '<span class="idField" title="' .$sortByFields[0]['label']. '"> Specimen ['.$id.']</span>
				&nbsp;'.printTsnNameLinks( $tsnName).'</td><td class="browseRight greenBottomBorder">';
		echo printOptions($this->myObjOptions, $array[$this->sortByFields[0]['field']], 'Specimen', $tsnName['name']);
		echo '</td><td rowspan="5" class="browseRight browseImageCell">';
		echo thumbnailTag($id);
		echo '</td></tr><tr><td>'.$this->sortByFields[1]['label'].' / '.$this->sortByFields[5]['label'];
		echo ' : '.$array[$this->sortByFields[1]['field']].' / '.$array[$this->sortByFields[5]['field']];
		echo '</td><td class="browseRight"><div style="white-space:nowrap;">';
		echo $this->sortByFields[10]['label'].': '.$array[$this->sortByFields[10]['field']];
		echo $showCameraHtml.'</div></td></tr><tr><td>'.$this->sortByFields[4]['label'];
		echo ' / ' .$this->sortByFields[2]['label']. ' / ' .$this->sortByFields[3]['label'];
		echo ': '.$array[$this->sortByFields[4]['field']];
		echo ' / '.$array[$this->sortByFields[2]['field']];
		echo ' / '.$array[$this->sortByFields[3]['field']].'</td><td class="browseRight">';
		$count = $this->getAnnotationCount($array[$this->sortByFields[0]['field']]);
		if ($count == 0) {
			echo 'No. Annotations: '.$count;
		} else {
			echo '<a href="javascript: searchTab(\'annotationTab\', \''.$array[$this->sortByFields[0]['field']].'\');">No. Annotations:</a> '.$count;
		}
		echo '</td></tr><tr>
			<td>'.$this->sortByFields[7]['label'].' / '.$this->sortByFields[11]['label'];
		echo ' / '.$this->sortByFields[12]['label'];
		echo ': '.$array[$this->sortByFields[7]['field']];
		echo ' / '.$array[$this->sortByFields[11]['field']];
		echo ' / '.$array[$this->sortByFields[12]['field']].'</td>';
		echo '<td class="browseRight">&nbsp;</td></tr><tr><td>';
		echo $this->sortByFields[6]['label'].': '.$array[$this->sortByFields[6]['field']];
		echo '<td>';
		echo showUserGroup($array['userId'], $array['name'], $array['groupId'], $array['groupName']);
		echo '</td>';
		
		echo '</td><td>&nbsp;</td></tr></table></div>';
	}

	function getSpecimenInfo($id) {
		$sql  = 'SELECT Specimen.id as specimenId,
				BasisOfRecord.description as BasisOfRecord, 
				Specimen.sex as Sex, 
				Specimen.form as Form, 
				Specimen.developmentalStage as Stage, 
				Specimen.typeStatus as typeStatus, 
				Specimen.collectorName, 
				DATE_FORMAT(Specimen.dateCollected, "%m-%d-%Y") AS dateCollected,
				Specimen.taxonomicNames, 
				Specimen.tsnId, 
				Specimen.imagesCount, 
				Specimen.standardImageId,
				Locality.locality as locality, Locality.country as country, 
				BaseObject.dateToPublish,
				BaseObject.userId,
				BaseObject.groupId
				
				FROM Specimen INNER JOIN BaseObject ON Specimen.id = BaseObject.id 
				LEFT JOIN BasisOfRecord ON Specimen.basisOfRecordId = BasisOfRecord.name 
				LEFT JOIN Locality ON Specimen.localityId = Locality.id ';
		$sql .= 'WHERE Specimen.id = '.$id;

		//echo $sql.'<br /><br /><br />';
		$result = mysqli_query($this->link, $sql);
		if ($result) {
			return mysqli_fetch_array($result);
		}
	}

	function setupSortByFields() {
		$this->sortByFields= array ( 	
		array(	'field' => 'specimenId',
				'label' => 'Specimen Id',
				'width' => 40,
				'toSort' => true,
				'inGet' => 0,
				'order' => 'DESC'),
		array(	'field' => 'BasisOfRecord',
				'label' => 'Basis Of Record',
				'width' => 40,
				'toSort' => true,
				'inGet' => 0,
				'order' => 'ASC'),
		array(	'field' => 'Sex',
				'label' => 'Sex',
				'width' => 60,
				'toSort' => true,
				'inGet' => 0,
				'order' => 'ASC'),
		array(	'field' => 'Form',
				'label' => 'Form',
				'width' => 50,
				'toSort' => true,
				'inGet' => 0,
				'order' => 'ASC'),
		array(	'field' => 'Stage',
				'label' => 'Developmental Stage',
				'width' => 30,
				'toSort' => true,
				'inGet' => 0,
				'order' => 'ASC'),
		array(	'field' => 'typeStatus',
				'label' => 'Type Status',
				'width' => 30,
				'toSort' => true,
				'inGet' => 0,
				'order' => 'ASC'),
		array(	'field' => 'collectorName',
				'label' => 'Collector Name',
				'width' => 30,
				'toSort' => true,
				'inGet' => 0,
				'order' => 'ASC'),
		array(	'field' => 'dateCollected',
				'label' => 'Date',
				'width' => 30,
				'toSort' => true,
				'inGet' => 0,
				'order' => 'ASC'),
		array(	'field' => 'taxonomicNames',
				'label' => 'Taxonomy',
				'width' => 30,
				'toSort' => false,
				'inGet' => 0,
				'order' => 'ASC'),
		array(	'field' => 'tsnId',
				'label' => 'TaxonomyId',
				'width' => 30,
				'toSort' => false,
				'inGet' => 0,
				'order' => 'ASC'),
		array(	'field' => 'imagesCount',
				'label' => 'No. Images',
				'width' => 50,
				'toSort' => true,
				'inGet' => 0,
				'order' => 'ASC'),
		array(	'field' => 'country',
				'label' => 'Country',
				'width' => 30,
				'toSort' => true,
				'inGet' => 0,
				'order' => 'ASC'),		
		array(	'field' => 'locality',
				'label' => 'Locality',
				'width' => 30,
				'toSort' => false,
				'inGet' => 0,
				'order' => 'ASC'),
		array(	'field' => 'standardImageId',
				'label' => 'image',
				'width' => 30,
				'toSort' => false,
				'inGet' => 0,
				'order' => 'ASC')	
		);
	}
}
?>
