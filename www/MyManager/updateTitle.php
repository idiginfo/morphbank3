<?php
include_once('head.inc.php');
include_once ('collectionFunctions.inc.php');


$link = AdminLogin();

$id = $_GET['id'];
$div = $_GET['div'];
$userId = $_GET['userId'];
$title = $_GET['title'];

$sql = 'SELECT *, NOW() as now FROM BaseObject WHERE id='.$id;
$result = mysqli_query($link, $sql);

if ($result) {
	$array= mysqli_fetch_array($result);
	
	if ($array['userId'] == $userId && $array['dateToPublish'] > $array['now']) {
		updateCollectionTitle($id, $title);
		updateCollectionKeywords($id);
		$divNum = substr($div, 3);
		echo '<span id="titleConfirm">'.$div.'</span>';
		echo '<span id="divNum">'.$divNum.'</span>';
		echo '<span id="title">'.$title.'</span>'; 
	}
	else
		echo '<div id="error">&nbsp;</div>';
}
else
	echo '<div id="error">&nbsp;</div>';


function updateCollectionTitle($id, $title) {
	global $link;
	$sql = 'UPDATE BaseObject SET name = \''.$title.'\' WHERE id = \''.$id.'\' ';
	mysqli_query($link, $sql);

}

?>
