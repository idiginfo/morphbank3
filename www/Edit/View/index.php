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

// Config includes
include_once('head.inc.php');

include_once('tsnFunctions.php');
include_once('extLinksRefs.php');
include_once('showFunctions.inc.php');

$includeJavaScript = array('jquery.1-4-2.min.js', 'jquery.validate.min.js', 'formMethods.js');

// The beginnig of HTML
$title = 'Edit View';
initHtml($title, null, $includeJavaScript);

// Add the standard head section to all the HTML output.
echoHead(false, $title);
echo '<div class="mainGenericContainer" style="width: Auto;">';

if(!checkAuthorization($_REQUEST['id'], null, null, 'edit')){
	echo getNonAuthMessage(getNonAuthCode()) . "<br /><br />";
} else {
	echo '<h1><b>Edit View</b></h1><br /><br />';
	checkViewMsg($_REQUEST['id'], $_REQUEST['code']);
	if(!$row = getView($_REQUEST['id'])) {
		echo '<h1>No Views for User: ' . $objInfo->getName() . '</h1>';
	} else {
		displayViewForm($row);
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
function checkViewMsg($id, $code) {
	if ($code == 1) {
		echo "<h3>You have successfully updated the <a href=\"/?id=$id\">View with id $id</a></h3><br /><br />\n";
	} elseif ($code == 2) {
		echo '<div class="searchError">Error retrieving contributor</div><br /><br />';
	} elseif ($code == 3) {
		echo '<div class="searchError">Error retrieving View information</div><br /><br />';
	} elseif ($code == 4) {
		echo '<div class="searchError">Error updating Base Object</div><br /><br />';
	} elseif ($code == 5) {
		echo '<div class="searchError">Error updating View</div><br /><br />';
	} elseif ($code == 6) {
		echo '<div class="searchError">Error retrieving image rows for update</div><br /><br />';
	} elseif ($code == 7) {
		echo '<div class="searchError">Error inserting external links or refences</div><br /><br />';
	} elseif ($code == 8) {
		echo '<div class="searchError">Error updating external links or refences</div><br /><br />';
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
 * Check for existance of view by id
 * @param $id
 * @return array|bool
 */
function getView($id) {
	$db = connect();
	
	$sql = "select v.*, b.userId, b.groupId from View v 
			left join BaseObject b on b.id = v.id 
			where v.id = ?";
	$row = $db->getRow($sql, null, array($id), null, MDB2_FETCHMODE_ASSOC);
	if(PEAR::isError($row)){
		die("SQL Error: " . $row->getMessage() . "<br />" . $row->getUserInfo());
		exit;
	}
	return !$row ? false : $row;
}

function displayViewForm($row) {
	
	
	$db = connect();
	$id = $row['id'];
	
	$count = $row['imagesCount'];
	$tsnName = getTsnName($row['viewtsn']);
	?>
	<form name="editView" id="editView" class="frmValidate" method="post" action="modifyView.php">
	<input type="hidden" name="objId" id="objId" value="<?php echo $id; ?>" />
	<input type="hidden" name="objType" id="objType" value="view" />
	<input type="hidden" name="objRelated" id="objRelated" value="image" />
	<input type="hidden" name="objAction" id="objAction" value="change" />
	<input type="hidden" name="count" id="count" value="<?php echo $count; ?>" />
	<table>
		<tr>
			<td><b>View Id: </b></td>
			<td><b><?php echo $id ?></b>&nbsp;
				<a href="javascript: confirmChange('<?php echo $id; ?>', 'view', 'image', 'delete', '<?php echo $count ?>')" 
					title="Delete View."><img src="/style/webImages/delete-trans.png" name="Delete" alt="Delete" />
				</a>
			</td>
		</tr>
		<?php 
		echo getImageTechniqueSelectTag($row['imagingtechnique']);
		echo getImagePrepSelectTag($row['imagingpreparationtechnique']);
		echo getSpecimenPartSelectTag($row['specimenpart']);
		echo getSexSelectTag($row['sex']);
		echo getFormSelectTag($row['form']);
		echo getDevelopmentalStageSelectTag($row['developmentalstage']);
		echo getViewAngleSelectTag($row['viewangle']);
		?>
		<tr>
			<td><b>View Applicable To: <span class="req">*</span></b></td>
			<td>
				<input type="text" name="tsnId" size="8" value="<?php echo $row['viewtsn']; ?>" />
				<input type="text" name="Determination" size="20"
						title="Enter name of the highest taxon to which this view is applicable."
						value="<?php echo $tsnName['name']; ?>"
						readonly="readonly" /> &nbsp;&nbsp; 
						<a href="javascript: pop('TSN', '/Admin/TaxonSearch/?pop=yes');">
						<img src="/style/webImages/selectIcon.png" alt="Select taxon"
						title="Click to Select the TSN that suits this view" /> (Select) </a>
			</td>
		</tr>
		<tr>
			<td><b>Standard Image: </b></td>
			<td align="left">
				<input type="text" name="StandardImage" size="6" title="Select the Image that best describes this View."
					value="<?php echo $row['standardimageid']; ?>" /> &nbsp;&nbsp; 
					<a href="javascript: pop('Image', '/Browse/ByImage/?pop=yes');">
					<img src="/style/webImages/selectIcon.png" alt="Select Image"
						title="Click to Select the image that best describes this view" /> (Select) </a>
			</td>
		</tr>
		<?php echo getContributorSelectTag($row['userid'], $row['groupid']); ?>
	</table>
	<?php
	echo extLinksRefs($id, "/Edit/View/?id=$id");
	echo frmSubmitButton('Update');
	?>
	</form>
	<?php
}
?>
