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

$baseObjectArray = getBaseObjectData($id);	
$publicationArray = getPublicationData($id);

$popUrl = (isset($_GET['pop'])) ? "/Show/?pop=Yes&amp;id=" : "/?id=";
$className = checkForExtLinks($id) ? "blueBorder" : "bottomBlueBorder";

$upperRightArray = returnFirstArray();
$lowerLeftArray = returnSecondArray();
$lowerRightArray = returnThirdArray();

	if (isset($_GET['pop'])) 
		echo '<div class="popContainer" style="width:800px">';
	else
		echo '<div class="mainGenericContainer" style="width:800px">';
	
			echo '<h2>Publication Record: ['.$id.']&nbsp;&nbsp;';
			printTitle($id);
			echo '</h2>			
				<table class="topBlueBorder" width="100%">
					<tr>
						<td class="firstColumn" width="50%">';
							showBaseObjectData($baseObjectArray);	
	echo	 '			</td>
						<td width="50%">';
							showUpperRight();
						echo'							
						</td>
					</tr>
				</table>
			
				<table class="'.$className .'" width="100%">
					<tr>
						<td class="firstColumn" valign="middle" width="50%">';
							showLowerLeft();
	echo '				</td>
						<td width="50%">';
							showLowerRight();
	echo '				</td>
					</tr>
				</table>';
			if (checkForExtLinks($id)) {
				echo'
			
				<table class="bottomBlueBorder" width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td>
							<table width="100%" border="0" cellspacing="0" cellpadding="2">
								<tr>
									<td>';
									showExternalLinks($id);
									echo'								
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>';
			}
		echo '
		</div>'; // popContainer
		

function printTitle($id) {
	global $publicationArray;
	
	$titleString = $publicationArray['author'].'; '.$publicationArray['year'].'; '.$publicationArray['title'];
	
	if (strlen($titleString) > 55)
		echo substr($titleString, 0, 55).'...';
	else 
		echo $titleString;
	
}

function showUpperRight() {
	global $upperRightArray, $publicationArray;
	
	$arraySize = count($upperRightArray);
	
	echo '
	<table border="0">';
	
	for ($i=0; $i < $arraySize; $i++ ) {
		if ($upperRightArray[$i]['display'] && !empty($publicationArray[$upperRightArray[$i]['field']])) {
			echo '
			<tr>
				<th>'.$upperRightArray[$i]['label'].'</th>
				<td>'.$publicationArray[$upperRightArray[$i]['field']].'</td>
			</tr>';
		}	
	}
	
	echo '
	</table>';
}

function showLowerLeft() {
	global $lowerLeftArray, $publicationArray;
	
	$arraySize = count($lowerLeftArray);
	
	echo '
	<table border="0">';
	
	for ($i=0; $i < $arraySize; $i++ ) {
		if ($lowerLeftArray[$i]['display'] && !empty($publicationArray[$lowerLeftArray[$i]['field']])) {
			echo '
			<tr>
				<th>'.$lowerLeftArray[$i]['label'].'</th>
				<td>'.$publicationArray[$lowerLeftArray[$i]['field']].'</td>
			</tr>';
		}	
	}
	
	echo '
	</table>';
}

function showLowerRight () {
	global $lowerRightArray, $publicationArray;
	
	$arraySize = count($lowerRightArray);
	
	echo '
	<table border="0">';
	
	for ($i=0; $i < $arraySize; $i++ ) {
		if ($lowerRightArray[$i]['display'] && !empty($publicationArray[$lowerRightArray[$i]['field']])) {
			echo '
			<tr>
				<th>'.$lowerRightArray[$i]['label'].'</th>
				<td>'.$publicationArray[$lowerRightArray[$i]['field']].'</td>
			</tr>';
		}	
	}
	
	echo '
	</table>';
}


function getPublicationData($id) {
	global $link;
	
	$sql='SELECT Publication.*, BaseObject.* FROM Publication INNER JOIN BaseObject ON Publication.id = BaseObject.id WHERE Publication.id = '.$id;
	
	$result = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if ($result) {
		$numRows = mysqli_num_rows($result);
		
		if ($numRows == 1) {
			$array = mysqli_fetch_array($result);
			return $array;
		} else
			return FALSE;
	} else
		return FALSE;
}

function returnFirstArray () {
	$array =  array ( 	0=>		array('field' => 'author',
									  'label' => 'Author(s): ',
									  'width' => 10,
									  'display' => TRUE),
						1=>		array('field' => 'year',
									  'label' => 'Year: ',
									  'width' => 10,
									  'display' => TRUE),
						2=>		array('field' => 'month',
									  'label' => 'Month: ',
									  'width' => 10,
									  'display' => TRUE),
						3=>		array('field' => 'day',
									  'label' => 'Day: ',
									  'width' => 10,
									  'display' => TRUE),
						4=>		array('field' => 'publicationTitle',
									  'label' => 'Pub. Title: ',
									  'width' => 10,
									  'display' => TRUE),
						5=>		array('field' => 'title',
									  'label' => 'Title: ',
									  'width' => 10,
									  'display' => TRUE),
						6=>		array('field' => 'publicationType',
									  'label' => 'Pub. Type: ',
									  'width' => 10,
									  'display' => TRUE),
						7=>		array('field' => 'doi',
									  'label' => 'DOI: ',
									  'width' => 10,
									  'display' => TRUE),
						8=>		array('field' => 'isbn',
									  'label' => 'ISBN: ',
									  'width' => 10,
									  'display' => TRUE),
						9=>		array('field' => 'issn',
									  'label' => 'ISSN: ',
									  'width' => 10,
									  'display' => TRUE)
					);
	return $array;	
}

function returnSecondArray () {
	$array =  array ( 	0=>		array('field' => 'edition',
									  'label' => 'Edition: ',
									  'width' => 10,
									  'display' => TRUE),
						1=>		array('field' => 'editor',
									  'label' => 'Editor: ',
									  'width' => 10,
									  'display' => TRUE),
						2=>		array('field' => 'publisher',
									  'label' => 'Publisher: ',
									  'width' => 10,
									  'display' => TRUE),
						3=>		array('field' => 'address',
									  'label' => 'Address: ',
									  'width' => 10,
									  'display' => TRUE),
						4=>		array('field' => 'howPublished',
									  'label' => 'How Published: ',
									  'width' => 10,
									  'display' => TRUE),
						5=>		array('field' => 'institution',
									  'label' => 'Institution: ',
									  'width' => 10,
									  'display' => TRUE),
						6=>		array('field' => 'organization',
									  'label' => 'Organization: ',
									  'width' => 10,
									  'display' => TRUE),
						7=>		array('field' => 'school',
									  'label' => 'School: ',
									  'width' => 10,
									  'display' => TRUE)
					);
	return $array;	
}

function returnThirdArray () {
	$array =  array ( 	0=>		array('field' => 'volume',
									  'label' => 'Volume: ',
									  'width' => 10,
									  'display' => TRUE),
						1=>		array('field' => 'number',
									  'label' => 'Number: ',
									  'width' => 10,
									  'display' => TRUE),
						2=>		array('field' => 'chapter',
									  'label' => 'Chapter: ',
									  'width' => 10,
									  'display' => TRUE),
						3=>		array('field' => 'series',
									  'label' => 'Series: ',
									  'width' => 10,
									  'display' => TRUE),
						4=>		array('field' => 'pages',
									  'label' => 'Pages: ',
									  'width' => 10,
									  'display' => TRUE),
						5=>		array('field' => 'note',
									  'label' => 'Note: ',
									  'width' => 10,
									  'display' => TRUE)
					);
	return $array;	
}
?>
