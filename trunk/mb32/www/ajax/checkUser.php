<?php
include_once('head.inc.php');



$id = $_GET['id'];

$userId = $objInfo->getUserId();
$groupId = $objInfo->getUserGroupId();

	if (isset($_GET['tsn'])) {// request for access to taxon
		$s = 'SELECT boId FROM Taxa where tsn='.$id;
		$r = mysqli_query($link, $s);
		if ($r) {
			$a = mysqli_fetch_array($r);
			$id = $a['boId'];
		}
	}
	
$authorization = checkAuthorization($id, $userId, $groupId, 'edit');

if ($authorization) {
	echo "TRUE";
} else {
	echo "checkUser: id $id user $userId group $groupId";
}

?>
