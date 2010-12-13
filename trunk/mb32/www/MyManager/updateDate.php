<?php
include_once('head.inc.php');

$link = AdminLogin();

$id = $_GET['id'];
$date = $_GET['date'];
$spanId = $_GET['spanId'];
$userId = isset($_GET['userId'])?$_GET['userId']:0;
$groupId = isset($_GET['groupId'])?$_GET['groupId']:0;

// if idString is set, then it is a mass date update (i.e. check all and update date)
if (isset($_GET['idString'])) {
	$spanId="";

	$idArray = explode("_", $_GET['idString']);
	$spanArray = explode("_", $_GET['spanString']);

	$idCount = count($idArray)-1;
	//echo $count.'<br />';
	//echo $spanCount;

	//echo $date.'<br />';
	for ($i=0; $i < $idCount; $i++) {
		//echo $i.' '.$idArray[$i].'<br />';
		if ( !isPublished($idArray[$i]) && ownObject($idArray[$i], $userId, $groupId) ) {
			updateDate($idArray[$i], $date);
			$array = explode('-', $spanArray[$i]);
				
			$spanId .= 'dateTest_'.($array[1]-1);
				
			if ($i < ($idCount-1)) {
				$spanId .= '-';
			}
		}
	}
	$spanId .= '-'.$date;

} else { // this is for a single date.
	updateDate($id, $date);
	$spanId .= "|".$date;
}

echo '<span id="dateUpdated">'.$spanId.'</span>';

function updateDate($id, $date) {
	global $link;

	$query = 'SELECT objectTypeId FROM BaseObject where id = '.$id;
	$result = mysqli_query($link, $query);

	if ($result) {
		$row = mysqli_fetch_array($result);

		if (strtolower($row['objectTypeId']) == "image") {
			$query = 'UPDATE Image SET dateToPublish = "'.$date.'" WHERE id = '.$id;
			$result = mysqli_query($link, $query);
		}
	}

	$sql = 'UPDATE BaseObject SET dateToPublish = "'.$date.'" WHERE id = '.$id;
	$result = mysqli_query($link, $sql);

	$sql = 'UPDATE Keywords SET dateToPublish = "'.$date.'" WHERE id = '.$id;
	$result = mysqli_query($link, $sql);
}

function ownObject($id, $userId, $groupId) {
	global $link;

	$sql = 'SELECT userId, groupId FROM BaseObject WHERE id='.$id;
	$result = mysqli_query($link, $sql) or die("Could not run the query\n");

	if ($result) {
		$array = mysqli_fetch_array($result);

		if ($array['userId'] == $userId) {
			return TRUE;
		} else {
			return FALSE;
		}
	} else {
		return FALSE;
	}
}

?>
