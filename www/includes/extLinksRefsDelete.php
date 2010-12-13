<?php
$linkId = trim($_GET['extLink']);
$ref = trim($_GET['ref']);

$db = connect();

// Get object ID for link
$sql = "select mbId from ExternalLinkObject where linkId = ?";
$id = $db->getOne($sql, null, array($linkId));
if (isMdb2Error($id, 'Selecting BaseObject Id for external link/reference: ' . $sql, false)) {
    header("location: $ref&code=30");
	exit;
}

// Check authorization to edit (removing external links is editing) 
if(!checkAuthorization($id, $objInfo->getUserId(), $objInfo->getUserGroupId(), 'delete')){
	header ("location: $ref");
	exit;
}

$stmt = $db->prepare("delete from ExternalLinkObject where linkId = ?", array('integer'));
isMdb2Error($stmt, 'Preparing to delete external link of reference');
$affectedRows = $stmt->execute(array($linkId));
isMdb2Error($affectedRows, 'Error deleting external link of reference');

header("location: $ref&code=32");
exit;
