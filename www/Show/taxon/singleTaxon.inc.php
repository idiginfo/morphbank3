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

$tsn = $_REQUEST['id'];
$db = connect();
$boid = $db->queryOne("select id from TaxonConcept where tsn = $tsn");
isMdb2Error($boid, 'Select BaseObject id');
	
$taxonArray = getTaxonArray($tsn);
// var_dump($taxonArray);
// echo "id: ".$id;
//$taxonArray = cleanArrayOfSpecialCharacters($taxonArray);
$title = "Taxon $tsn";
initHtml( $title, NULL, NULL);
echoHead( false, $title, false);

$popUrl = (isset($_GET['pop'])) ? "/Show/?pop=Yes&amp;id=" : "/?id=";

$upperLeftArray = returnFirstArray();
$upperRightArray = returnSecondArray();
//$lowerLeftArray = returnSecondArray();
//$lowerRightArray = returnThirdArray();

if (isset($_GET['pop'])) {
	echo '<div class="popContainer" style="width:800px">';
} else {
	echo '<div class="mainGenericContainer" style="width:800px">';
}

echo '<h2>Taxon Record: ['.$id.']&nbsp;&nbsp;';
printTitle($id);
echo '</h2><table class="topBlueBorder" width="100%"><tr><td class="firstColumn" width="50%">';
//var_dump($taxonArray);
showUpperLeft();
echo '</td><td width="50%">';
showUpperRight();
echo '</td></tr></table>';
echo '<table class="bottomBlueBorder" width="100%"><tr>'
.'<td class="firstColumn" valign="middle" width="50%">';
showLowerLeft();
echo '</td><td width="50%">';
showLowerRight();
echo '</td></tr></table>';

if (checkForExtLinks($boid)) {
	echo'<table class="bottomBlueBorder" width="100%" border="0" cellspacing="0" cellpadding="2">'
		.'<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="2">'
		.'<tr><td>';
	showExternalLinks($boid);
	echo'</td></tr></table></td></tr></table>';
}
echo '</div>'; // popContainer
finishHtml();

function printTitle($id) {
	global $taxonArray;

	$titleString = $taxonArray['scientificName'];

	if (strlen($titleString) > 55)
	echo substr($titleString, 0, 55).'...';
	else
	echo $titleString;

}

function showUpperLeft() {
	global $taxonArray, $upperLeftArray;

	$arraySize = count($upperLeftArray);

	echo '
	<table border="0">';

	for ($i=0; $i < $arraySize; $i++ ) {
		if ($upperLeftArray[$i]['display']) {
			echo '
			<tr>
				<th>'.$upperLeftArray[$i]['label'].'</th>
				<td>'.$taxonArray[$upperLeftArray[$i]['field']].'</td>
			</tr>';
		}
	}

	echo '
	</table>';

}

function showUpperRight() {
	global $taxonArray, $upperRightArray;

	$arraySize = count($upperRightArray);

	echo '
	<table border="0">';

	for ($i=0; $i < $arraySize; $i++ ) {
		if ($upperRightArray[$i]['display']) {
			echo '
			<tr>
				<th>'.$upperRightArray[$i]['label'].'</th>
				<td>'.$taxonArray[$upperRightArray[$i]['field']].'</td>
			</tr>';
		}
	}

	echo '
	</table>';
}

function showLowerLeft() {
	global $taxonArray;

	echo '<h3>Determination</h3><br/><br>';

	showTsnData($taxonArray['tsn']);
}

function showLowerRight () {
	global $id;

	echo '
	<table border="0">
		<tr>
			<td>'.showExternalLinks($id).'</td>
		</tr>';

	echo '
	</table>';
}


function getTaxonArray($tsn) {
	global $link;

	$sql='SELECT Taxa.*, Publication.*, Vernacular.vernacular_name '
	.'FROM Taxa LEFT JOIN Publication ON Taxa.publicationId = Publication.id '
	.'LEFT JOIN Vernacular ON Taxa.tsn = Vernacular.tsn WHERE Taxa.tsn = '.$tsn;

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
	$array =  array ( 	0=>		array('field' => 'scientificName',
									  'label' => 'Scientific Name:  ',
									  'width' => 10,
									  'display' => TRUE),
	1=>		array('field' => 'taxon_author_name',
									  'label' => 'Taxon Author: ',
									  'width' => 10,
									  'display' => TRUE),
	2=>		array('field' => 'nameType',
									  'label' => 'Type of Name: ',
									  'width' => 10,
									  'display' => TRUE),
	3=>		array('field' => 'nameSource',
									  'label' => 'Name Source: ',
									  'width' => 10,
									  'display' => TRUE),
	4=>		array('field' => 'nameStatus',
									  'label' => 'Name Status: ',
									  'width' => 10,
									  'display' => TRUE),
	5=>		array('field' => 'vernacular_name',
									  'label' => 'Vernacular Name: ',
									  'width' => 10,
									  'display' => TRUE)
	);
	return $array;
}

function returnSecondArray () {
	$array =  array ( 	0=>		array('field' => 'publicationTitle',
									  'label' => 'Publication Title: ',
									  'width' => 10,
									  'display' => TRUE),
	1=>		array('field' => 'publicationType',
									  'label' => 'Pub. Type: ',
									  'width' => 10,
									  'display' => TRUE),
	2=>		array('field' => 'pages',
									  'label' => 'Pages: ',
									  'width' => 10,
									  'display' => TRUE),
	3=>		array('field' => 'title',
									  'label' => 'Title: ',
									  'width' => 10,
									  'display' => TRUE),
	4=>		array('field' => 'howPublished',
									  'label' => 'How Published: ',
									  'width' => 10,
									  'display' => TRUE),
	5=>		array('field' => 'publisher',
									  'label' => 'Publisher: ',
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
