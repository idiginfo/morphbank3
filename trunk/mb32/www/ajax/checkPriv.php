<?php

include_once('head.inc.php');




$id = $_GET['id'];

$tsnArray = $objInfo->getUserInfo();
$groupTSN = $objInfo->getUserGroupInfo();

$sql = 'SELECT tsnId FROM Specimen INNER JOIN Image ON Specimen.id = Image.specimenId WHERE Image.id = '.$id;

$result = mysqli_query($link, $sql);

if ($result) {
	$array = mysqli_fetch_array($result);
	$objectTSN = $array['tsnId'];
	$userTSN = $tsnArray[2];
	$groupTSN = $groupTSN[3];

	if (!taxonPermit($objectTSN, $userTSN, $groupTSN)) {
		echo "FALSE";
	} else {
		echo "TRUE";
	}

} else {
	echo "FALSE";
}

?>
