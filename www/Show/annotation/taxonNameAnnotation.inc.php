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

$annotationArray = getAnnotationArray($id);
$taxonConceptArray = getTaxonConceptArray($taxonConceptId);
$baseObjectArray = getBaseObjectData($id);	

//var_dump($taxonConceptArray);
// echo "id: ".$id;
//$taxonArray = cleanArrayOfSpecialCharacters($taxonArray);

$popUrl = (isset($_GET['pop'])) ? "/Show/?pop=Yes&amp;id=" : "/?id=";

$upperLeftArray = returnSecondArray();
$upperRightArray = returnFirstArray();
//$lowerLeftArray = returnSecondArray();
//$lowerRightArray = returnThirdArray();

	if (isset($_GET['pop'])) 
		echo '<div class="popContainer" style="width:800px">';
	else
		echo '<div class="mainGenericContainer" style="width:800px">';
	
			echo '<h2>Taxon Name Annotation: ['.$id.']&nbsp;&nbsp;';
			printTitle($id);
			echo '</h2>			
				<table class="topBlueBorder" width="100%">
					<tr>
						<td class="firstColumn" width="50%">';
							//var_dump($taxonArray);
							showUpperLeft();	
	echo	 '			</td>
						<td width="50%">';
							showUpperRight();
						echo'							
						</td>
					</tr>
				</table>
				
				<table class="bottomBlueBorder" width="100%">
					<tr>
						<td>';
							
							showImageAnnotations($taxonConceptId, FALSE, $tsn);
						
						echo '
						</td>
					
					</tr>	
				</table>';
			
		echo '
		</div>'; // popContainer
		

function printTitle($id) {
	global $annotationArray;
	
	$titleString = $annotationArray['title'];
	
	if (strlen($titleString) > 55)
		echo substr($titleString, 0, 55).'...';
	else 
		echo $titleString;
	
}


function showUpperLeft() {
	global $annotationArray, $upperLeftArray, $baseObjectArray;
	
	$arraySize = count($upperLeftArray);
	
	echo '<h3>Annotation Data</h3><br /><br />';
	
	showBaseObjectData($baseObjectArray);	
	
	echo '
	
	<table border="0">';
	
	for ($i=0; $i < $arraySize; $i++ ) {
		if ($upperLeftArray[$i]['display']) {
			echo '
			<tr>
				<th>'.$upperLeftArray[$i]['label'].'</th>
				<td>'.$annotationArray[$upperLeftArray[$i]['field']].'</td>
			</tr>';
		}	
	}
	
	echo '
	</table>';
}


function showUpperRight() {
	global $taxonConceptArray, $upperRightArray;
	
	$arraySize = count($upperRightArray);
	
	echo '
	<h3>Taxon Concept Data</h3><br /><br />
	<table border="0">';
	
	for ($i=0; $i < $arraySize; $i++ ) {
		if ($upperRightArray[$i]['display']) {
			echo '
			<tr>
				<th>'.$upperRightArray[$i]['label'].'</th>
				<td>'.$taxonConceptArray[$upperRightArray[$i]['field']].'</td>
			</tr>';
		}	
	}
	
	echo '
	</table>';

}


function getAnnotationArray($id) {
	global $link;
	
	$sql = 'SELECT * FROM Annotation WHERE id='.$id;
		
	//echo $sql;
	$result = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if ($result) {
		$array = mysqli_fetch_array($result);
		return $array;
		
	} else
		return FALSE;
}

function getTaxonConceptArray($id) {
	global $link;
	
	$sql = 'SELECT TaxonConcept.*, Taxa.scientificName, Taxa.taxon_author_name, Taxa.nameType, Taxa.rank_name, Taxa.parent_name, '
		  .'Taxa.status, Publication.publicationTitle, Publication.pages FROM TaxonConcept INNER JOIN Taxa ON TaxonConcept.tsn = Taxa.tsn '
		  .'LEFT JOIN Publication ON Taxa.publicationId = Publication.id '
		  .'WHERE TaxonConcept.id='.$id;
		
	//echo $sql;
	$result = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if ($result) {
		$array = mysqli_fetch_array($result);
		return $array;
		
	} else
		return FALSE;
}



function cleanArrayOfSpecialCharacters($array) {
		
		foreach($array as $k => $v) {
			$array[$k] = htmlentities($v, ENT_QUOTES, "UTF-8");		
		}
		
		return $array;
		/*
		foreach($array as $k => $v) {
			$array[$k] = strtr($v, $this->specialCharsArray);
		}
		*/
	
	
}

function returnFirstArray () {
	$array =  array ( 		array('field' => 'scientificName',
								  'label' => 'Taxon Name:  ',
								  'width' => 10,
								  'display' => TRUE),
								  
							array('field' => 'nameType',
								  'label' => 'Type of Name: ',
								  'width' => 10,
								  'display' => TRUE),	
								  
							array('field' => 'rank_name',
								  'label' => 'Rank: ',
								  'width' => 10,
								  'display' => TRUE),	
							
							array('field' => 'parent_name',
								  'label' => 'Parent Name: ',
								  'width' => 10,
								  'display' => TRUE),		  
							
							array('field' => 'taxon_author_name',
								  'label' => 'Taxon Author, Year: ',
								  'width' => 10,
								  'display' => TRUE),
						
							array('field' => 'pages',
								  'label' => 'Pages: ',
								  'width' => 10,
								  'display' => TRUE),
								  
							array('field' => 'nameStatus',
								  'label' => 'Name Status: ',
								  'width' => 10,
								  'display' => TRUE)
					);
	return $array;	
}

function returnSecondArray () {
	$array =  array ( 		array('field' => 'title',
								  'label' => 'Annotation Title: ',
								  'width' => 10,
								  'display' => FALSE),

							array('field' => 'comment',
								  'label' => 'Comments: ',
								  'width' => 10,
								  'display' => TRUE)
					);
	return $array;	
}

?>
