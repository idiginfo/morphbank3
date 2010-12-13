<?php
/**
 * This file is used to update Publication. 
 * 
 * File name: editPublication.php
 * @package Morphbank2
 * @subpackage Submit Publication
 */

include_once('head.inc.php');
include_once('extLinksRefs.php');
include_once('showFunctions.inc.php');

/* Include javascript files */
$includeJavaScript = array('jquery.1-4-2.min.js', 'jquery.validate.min.js', 'formMethods.js');

// The beginning of HTML
$title = 'Edit Publication';
initHtml($title, null, $includeJavaScript);

// Output the content of the main frame
echoHead(false, $title);

echo '<div class="mainGenericContainer" style="width:760px">';

// Check authorization
if(!checkAuthorization($_REQUEST['id'], $objInfo->getUserId(), $objInfo->getUserGroupId(), 'edit')){
	echo getNonAuthMessage(getNonAuthCode()) . "<br /><br />";
} else {
	echo checkPublicationMessage($_REQUEST['id'], $_REQUEST['code']);
	if (!$row = checkPublication($_REQUEST['id'])) {
		echo '<h1>No Publications for User: ' . $objInfo->getName() . '</h1>';
	} else {
		displayPublicationForm($row);
	}
}

echo '</div>';
finishHtml();


/**
 * Check for any GET message codes
 * @param array $array
 * @return void
 */
function checkPublicationMessage($id, $code){
	$msg = '<h1><b>Edit Publication</b></h1><br/><br/>';
	if ($code == 1) {
		$msg .= "<h3>You have successfully updated <a href=\"/?id=$id\">Publication id $id</a></h3><br /><br />\n";
	} elseif ($code == 2) {
		$msg .=  '<div class="searchError">Error retrieving Publication data</div><br /><br />'."\n";
	} elseif ($code == 3) {
		$msg .=  '<div class="searchError">Error creating BaseObject and Publication</div><br /><br />'."\n";
	} elseif ($code == 4) {
		$msg .=  '<div class="searchError">Error updating Publication data</div><br /><br />'."\n";
	} elseif ($code == 5) {
		$msg .=  '<div class="searchError">Error inserting external link or reference</div><br /><br />'."\n";
	} elseif ($code == 6) {
		$msg .=  '<div class="searchError">Error updating external link or reference</div><br /><br />'."\n";
	} elseif ($code == 30) {//
		echo '<div class="searchError">Could not select BaseObject Id to delete external link/reference</div><br /><br />'."\n";
	} elseif ($code == 31) {//
		echo '<div class="searchError">Error deleting external link/reference</div><br /><br />'."\n";
	} elseif ($code == 32) {//
		echo "<h3>You have successfully deleted an external link/reference</h3><br /><br />\n";
	}
	return $msg;
}

/**
 * Check publication exists
 * @param object $id
 * @return boolean|resource
 */
function checkPublication($id){
	$db = connect();

	$sql = "SELECT p.*, b.userId, b.groupId, b.dateToPublish, g.groupName FROM Publication p 
			LEFT JOIN BaseObject b ON b.id = p.id 
			left join Groups g on g.id = b.groupId 
			WHERE p.id = ? AND b.objectTypeId = 'Publication'";
	$row = $db->getRow($sql, null, array($id), null, MDB2_FETCHMODE_ASSOC);
	isMdb2Error($row, 'select publication data');
	return !$row ? false : $row;
}

/**
 * Display Publication form
 * @return void
 */
function displayPublicationForm($array){
	$frmId = "editPublication";
	$frmAction = "modifyPublication.php";
	$frmButtonText = "Update";
	$id = $array['id'];
	$contributor = $array['userid'];
	$groupid = $array['groupid'];
	$datetopublish = $array['datetopublish'];
	$ref = "/Admin/Publication/editPublication.php?id=$id";
	$isEdit = true;
	include_once('publicationForm.php');
}
?>
