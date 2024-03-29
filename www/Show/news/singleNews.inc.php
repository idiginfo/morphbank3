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

$newsArray = getNewsData($id);
$baseObjectArray = getBaseObjectData($id);
$popUrl = (isset($_GET['pop'])) ? "/Show/?pop=Yes&amp;id=" : "/?id=";

if (!empty($newsArray['image'])) {
	$img = $newsArray['image'];
	$imgTitle = $newsArray['imageText'];
} else {
	$img = '/style/webImages/defaultNews.png';
	$imgTitle = 'Img Title goes here.';
}
//TODO see mb4:/data/mb30 for changes
list($width, $height, $imgType) = getSafeImageSize($img);
if($width <= $height) {
	$sizeParam = 'width="270"';
} else {
	$sizeParam = 'height="270"';
}

// Output the content of the main frame
include_once( 'tsnFunctions.php');
if (isset($_GET['pop'])) {
	echo '<div class="popContainer" style="width:770px">';
} else {
	echo '<div class="mainGenericContainer" style="width:770px">';
}
echo'			<h2>News record: ['.$newsArray['id'].'] '.$newsArray['title'].'</h2>
			<table class="topBlueBorder" width="100%" cellspacing="0" cellpadding="2">
				<tr>
					<td class="firstColumn" valign="top">
						<div class="popCellPadding" >
						<div class="popNews">'
					.$newsArray['body'].'
						</div>
						</div>
					</td>
					<td>
						<img src="'.$img.'" '.$sizeParam.' title="'.$imgTitle.'" alt="image" />								
					</td>
				</tr>
			</table>';

echo '(<a href="'.$config->domain.'About/News">all past news</a>)';

echo  '</div>'; // popContainer


function getNewsData ($id) {
	global $link;

	$sql ='SELECT * FROM News WHERE News.id = '.$id.'';

	$result = mysqli_query($link, $sql);

	if ($result) {
		$numRows = mysqli_num_rows($result);

		if ($numRows = 1) {
			$array = mysqli_fetch_array($result);
			return $array;

		}
	}
}


?>
