<?php

$newsArray = getNewsData($id);
$baseObjectArray = getBaseObjectData($id);
$popUrl = (isset($_GET['pop'])) ? "/Show/?pop=Yes&amp;id=" : "/?id=";

if (!empty($newsArray['image'])) {
	$img = '/images/newsImages/' . $newsArray['image'];
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
