<?php
include_once('head.inc.php');
include_once('collectionFunctions.inc.php');

$link = AdminLogin();

$collectionId = (isset($_GET['collectionId'])) ? $_GET['collectionId'] : 0;
$thumbId = (isset($_GET['thumbId'])) ? $_GET['thumbId'] : 0;

if ($thumbId != 0 && $collectionId != 0) {

	if (createCollectionThumb($collectionId, $thumbId))
		echo '<div id="updateSuccess">'.$thumbId.'</div>';
	else
		echo '<div id="updateFail1">&nbsp;</div>';
}
else {
	echo '<div id="updateFail2">&nbsp;</div>';
}


?>
