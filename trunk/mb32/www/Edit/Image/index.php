<?php
/**
 * File name: index.php
 * @package Morphbank2
 * @subpackage Edit
 * @subpackage Image
 * This is the standard script that calls mainUpload function which displays the 
 * options for Upload Module.
 */

/* Config script */
include_once('head.inc.php');
include_once('imageFunctions.php');
include_once('extLinksRefs.php');
include_once('showFunctions.inc.php');

$includeJavaScript = array('jquery.1-4-2.min.js', 'jquery.validate.min.js', 'formMethods.js');

// The beginning of HTML
$title='Edit Image';
initHtml($title, null, $includeJavaScript);

// Add the standard head section to all the HTML output.
echoHead(false, $title);
echo '<div class="mainGenericContainer" style="width: Auto;">';

// Check authorization
if (!checkAuthorization($_REQUEST['id'], null, null, 'edit')) {
	echo getNonAuthMessage(getNonAuthCode()) . "<br /><br />";
} else {
	echo "<h1><b>Edit Image</b></h1><br /><br />";
	checkEditImageMsg($_REQUEST['id'], $_REQUEST['code']);
	if (!$row = checkImage($_REQUEST['id'])) {
		echo '<h1>No Images for User: ' . $objInfo->getName() . '</h1>';
	} else {
		displayImageForm($row);
	}
}
// Finish HTML
echo '</div>';
finishHtml();

/**
 * Echo messages
 * @param integer $id
 * @param integer $code
 * @return void
 */
function checkEditImageMsg($id, $code) {
	if ($code == 1) {
		echo "<h3>You have successfully updated the <a href=\"/?id=$id\">Image with id $id</a></h3><br /><br />\n";
	} elseif ($code == 2) {//
		echo '<div class="searchError">Error selecting View information</div><br /><br />'."\n";
	} elseif ($code == 3) {//
		echo '<div class="searchError">The View entered does not exist</div><br /><br />'."\n";
	} elseif ($code == 4) {
		echo '<div class="searchError">Error selecting Specimen information</div><br /><br />'."\n";
	} elseif ($code == 5) {
		echo '<div class="searchError">The Specimen entered does not exisit</div><br /><br />'."\n";
	} elseif ($code == 6) {
		echo '<div class="searchError">Error selecting BaseObject information</div><br /><br />'."\n";
	} elseif ($code == 7) {
		echo '<div class="searchError">Error selecting Image information</div><br /><br />'."\n";
	} elseif ($code == 8) {
		echo '<div class="searchError">Error processing Image</div><br /><br />'."\n";
	} elseif ($code == 9) {
		echo '<div class="searchError">Error updating Base Object</div><br /><br />'."\n";
	} elseif ($code == 10) {
		echo '<div class="searchError">Error updating Image</div><br /><br />'."\n";
	} elseif ($code == 11) {
		echo '<div class="searchError">Error inserting external links or refences</div><br /><br />'."\n";
	} elseif ($code == 12) {
		echo '<div class="searchError">Error updating external links or references</div><br /><br />'."\n";
	} elseif ($code == 13) {
		echo '<div class="searchError">Error updating Image data</div><br /><br />'."\n";
	} elseif ($code == 14) {
		echo '<div class="searchError">Error updating BaseObject thumbUrl</div><br /><br />'."\n";
	} elseif ($code == 15) {
		echo '<div class="searchError">Error inserting External links and/or references</div><br /><br />'."\n";
	} elseif ($code == 30) {//
		echo '<div class="searchError">Could not select BaseObject Id to delete external link/reference</div><br /><br />'."\n";
	} elseif ($code == 31) {//
		echo '<div class="searchError">Error deleting external link/reference</div><br /><br />'."\n";
	} elseif ($code == 32) {//
		echo "<h3>You have successfully deleted an external link/reference</h3><br /><br />\n";
	}
	return;
}

/**
 * Check image exists
 * @param object $id
 * @return boolean|resource
 */
function checkImage($id){
	$db = connect();
	$sql = "select i.*, b.userId, b.groupId, date_format(b.dateToPublish, '%Y-%m-%d') as dateToPublish, g.groupName 
			from Image i 
			left join BaseObject b on b.id = i.id 
			left join Groups g on g.id = b.groupId 
			where i.id = ?";
	$row = $db->getRow($sql, null, array($id), null, MDB2_FETCHMODE_ASSOC);
	if(PEAR::isError($row)){
		die("SQL Error: " . $row->getMessage() . "<br />" . $row->getUserInfo());
		exit;
	}
	return !$row ? false : $row;
}

/**
 * Display image for for editing
 * @param array $row
 * @param integer $id
 * @return 
 */
function displayImageForm($row) {
	
	
	$id = $row['id'];
	$ref = '/Edit/Image/?id=' . $id;
	
	echo '<form id="editImage" class="frmValidate" name="editImage" method="post" action="modifyImage.php" enctype="multipart/form-data">';
	echo '<table width="720">';
	echo '<input type="hidden" name="objId" value="' . $id . '" />';
		echo '<tr>
				<td><b>Group Name: </b></td>
				<td><b><a href="/?id=' . $row['groupid'] . '">' . $row['groupname'] . '</a></b></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>';
		echo '<tr>
				<td><b>Image Id: </b></td>
				<td><b>' . $id . '</b></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><b>Change Image: </b></td>
				<td><input type="file" name="ImageFile" size="30" /> </td>
				<td id="imageThumb">' . thumbnailTag($id) . '</td>
			</tr>';
		echo '<tr>
				<td><b>Specimen Id:<span class="req">*</span></b></td>
				<td><input type="text" name="SpecimenId" value="' . $row['specimenid'] . '" title="" size="11" />
					&nbsp;&nbsp; <a href= "javascript: pop(\'Specimen\', \'/Browse/BySpecimen/index.php?pop=yes&referer=Submit\');">
					<img src="/style/webImages/selectIcon.png" alt="Select Specimen "  title="Click to select a Specimen" />(Select New)</a>
				</td>
			</tr>';
		echo '<tr>
				<td><b>View Id:<span class="req">*</span> </b></td>
				<td><input type="text" name="ViewId" value="' . $row['viewid'] . '" title="" size="11" />
					&nbsp;&nbsp; <a href= "javascript: pop(\'View\', \'/Browse/ByView/index.php?pop=yes&referer=Submit\');"> 
					<img src="/style/webImages/selectIcon.png" alt="Select View "  title="Click to select a View" />(Select New)</a>
				</td>
			</tr>';
		echo '<tr><td><b>Magnification: </b></td>
					<td><input type="text" name="Magnification" value="' . $row['magnification'] . '" size="10" /></td>
			</tr>';
		echo '<tr><td><b>Photographer: </b></td>
					<td><input type="text" name="photographer" value="' . $row['photographer'] . '" size="30" /></td>
			</tr>';
		echo '<tr><td><b>Copyright: </b></td>
					<td><input type="text" name="Copyright" value="' . $row['copyrighttext'] . '" size="10" /></td>
			</tr>';
		echo '<tr>
				<td><b>Date To Publish (YYYY-MM-DD): </b></td>
				<td><input type="text" name="DateToPublish" value="' . $row['datetopublish'] . '" size="30" /></td>
			</tr>';
		echo getContributorSelectTag($row['userid'], $row['groupid']);
	echo '</table>';
	echo extLinksRefs($id, $ref);
	echo frmSubmitButton('Update');
	echo '</form>';
}
