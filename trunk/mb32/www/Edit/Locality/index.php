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

include_once('head.inc.php');
include_once('extLinksRefs.php');
include_once('showFunctions.inc.php');

$includeJavaScript = array('jquery.1-4-2.min.js', 'jquery-ui-1.8.min.js', 'jquery.validate.min.js', 'formMethods.js');

// The beginnig of HTML
$title = 'Edit Locality';
initHtml($title, null, $includeJavaScript);

// Add the standard head section to all the HTML output.
echoHead(false, $title);
echo '<div class="mainGenericContainer" style="width: Auto;">';

// Check authorization
if (!checkAuthorization($_REQUEST['id'], null, null, 'edit')) {
	echo getNonAuthMessage(getNonAuthCode()) . "<br /><br />";
} else {
	echo '<h1><b>Edit Locality</b></h1><br /><br />';
	getLocalityMsg($_REQUEST['id'], $_REQUEST['code']);
	if(!$row = getLocality($_REQUEST['id'])){
		echo '<h1>No Localities for User: ' . $objInfo->getName() . '</h1><br /><br />';
	} else {
		displayLocalityForm($row);	
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
function getLocalityMsg($id, $code) {
	if ($code == 1) {
		echo "<h3>You have successfully updated the <a href=\"/?id=$id\">Locality with id $id</a></h3><br /><br />\n";
	} elseif ($code == 2) {
		echo '<div class="searchError">Error retrieving Country data</div><br /><br />';
	} elseif ($code == 3) {
		echo '<div class="searchError">Error retrieving User Id data</div><br /><br />';
	} elseif ($code == 4) {
		echo '<div class="searchError">Error retrieving Locality data</div><br /><br />';
	} elseif ($code == 5) {
		echo '<div class="searchError">Failed updating BaseObject</div><br /><br />';
	} elseif ($code == 6) {
		echo '<div class="searchError">Failed updating Locality</div><br /><br />';
	} elseif ($code == 7) {
		echo '<div class="searchError">Error retriving Specimen Ids for locality</div><br /><br />';
	} elseif ($code == 8) {
		echo '<div class="searchError">Error retriving Image Ids for specimens</div><br /><br />';
	} elseif ($code == 9) {
		echo '<div class="searchError">Error inserting external links or refences</div><br /><br />';
	} elseif ($code == 10) {
		echo '<div class="searchError">Error updating external links or refences</div><br /><br />';
	} elseif ($code == 30) {//
		echo '<div class="searchError">Could not select BaseObject Id to delete external link/reference</div><br /><br />'."\n";
	} elseif ($code == 31) {//
		echo '<div class="searchError">Error deleting external link/reference</div><br /><br />'."\n";
	} elseif ($code == 32) {//
		echo "<h3>You have successfully deleted an external link/reference</h3><br /><br />\n";
	} elseif ($code == 33) {//
		echo '<div class="searchError">Error inserting value into Country.</div><br /><br />'."\n";
	}
	return;
}

/**
 * Returns locality if it exists
 * @param integer $id
 * @return boolean|resource
 */
function getLocality($id){
	$db = connect();
	$sql = "SELECT l.id, l.continent, l.ocean, l.country, l.state, l.county, l.locality as Locality, 
			l.latitude as Latitude, l.longitude as Longitude, 
			l.coordinatePrecision as CoordinatePrecision, 
			l.minimumElevation as MinimumElevation, l.maximumElevation as MaximumElevation, 
			l.minimumDepth as MinimumDepth, l.maximumDepth as MaximumDepth,
			b.userId, b.groupId, g.groupName
			FROM Locality l 
      LEFT JOIN BaseObject b on b.id = l.id 
      LEFT JOIN Groups g on g.id = b.groupId 
      WHERE l.id = ?";
	$row = $db->getRow($sql, null, array($id), null, MDB2_FETCHMODE_ASSOC);
  isMdb2Error($row, "Selecting Locality information");

  return !$row ? false : $row;
}

function displayLocalityForm($row){
	global $config, $objInfo;
	$db = connect();
	
	$id = $row['id'];
	
	$specimenSql = "SELECT COUNT(*) AS count FROM Specimen WHERE localityId = ?;";
	$count = $db->getOne($specimenSql, null, array($id));
  isMdb2Error($row, "Error select specimen data.");
	?>
	<form id="editLocality" class="frmValidate" name="editLocality" method="post" action="modifyLocality.php">
	<input type="hidden" name="objId" id="objId" value="<?php echo $id; ?>" />
	<input type="hidden" name="objType" id="objType" value="locality" />
	<input type="hidden" name="objRelated" id="objRelated" value="specimen" />
	<input type="hidden" name="objAction" id="objAction" value="change" />
	<input type="hidden" name="count" id="count" value="<?php echo $count; ?>" />
	<table width="600">
    <tr>
				<td><b>Group Name: </b></td>
				<td><b><a href="/?id=<?php echo $row['groupid']; ?>"><?php echo $row['groupname']; ?></a></b></td>
	  </tr>
		<tr>
				<td><b>Locality Id: </b></td>
				<td><b><?php echo $id; ?></b> &nbsp; 
				<a href="javascript: confirmChange('<?php echo $id; ?>', 'locality', 'specimen', 'delete', '<?php echo $count; ?>');" title="Delete Locality.">
		    <img src="/style/webImages/delete-trans.png" name="Delete" alt="Delete" /> </a></td>
		</tr>
    <tr>
      <td><b>Continent:</b></td>
			<td align="left"><?php echo getContinentSelect($row['continent']); ?></td>
    </tr>
    <tr>
        <td><b>Ocean: </b></td>
        <td><input type="text" size="55" id="ocean" name="ocean" title="Enter Ocean" value="<?php echo $row['ocean']; ?>" /></td>
    </tr>
    <tr>
        <td><b>Country: </b></td>
        <td><input type="text" size="55" id="Country" class="autocomplete country" name="Country" title="Enter Country" value="<?php echo $row['country']; ?>" /></td>
    </tr>
		<tr>
      <td><b>State/Province:</b></td>
      <td align="left"><input name="state" id="state" size="40" title="Enter a state or province." value="<?php echo $row['state']; ?>" /></td>
    </tr>
    <tr>
      <td><b>County:</b></td>
      <td align="left"><input name="county" id="county" size="40" title="Enter a county." value="<?php echo $row['county']; ?>" /></td>
    </tr>
		<tr>
      <td><b>Locality: </b></td>
      <td><input type="text" size ="55" name="Locality" title = "Enter locality" value="<?php echo $row['locality']; ?>" /></td>
    </tr>
		<tr>
      <td><b>Latitude: </b></td>
      <td><input type="text" name="Latitude" value="<?php echo ($row['latitude'] < 0 ? abs($row['latitude']) : $row['latitude']); ?>" title = "Enter the latitude in decimal degrees (0.0000 - 90.0000) and select North or South." size="11" />
          <select name="NS">
          <option value="1">North</option>
          <option value="2" <?php echo ($row['latitude'] < 0 ? 'selected="selected"' : ''); ?>>South</option>
          </select>
      </td>
    </tr>
		<tr>
      <td><b>Longitude: </b></td>
      <td><input type="text" name="Longitude" value="<?php echo ($row['longitude'] < 0 ? abs($row['longitude']) : $row['longitude']); ?>" title = "Enter the longitude in decimal degrees (0.0000 - 180.0000) and select East or West." size="11" />
				<select name="EW">
				<option value="1">East</option>
				<option value="2" <?php echo ($row['longitude'] < 0 ? 'selected="selected"' : ''); ?>>West</option>
				</select>
      </td>
		</tr>
		<tr>
      <td><b>Coordinate Precision: </b></td>
      <td><input type="text" name="CoordinatePrecision" value="<?php echo $row['coordinateprecision']; ?>" title = "Estimate the coordinate precision expressed as distance in meters that corresponds to a radius around the latitude-longitude coordinates." size="11" /></td>
    </tr>
		<tr>
      <td><b>MinimumElevation: </b></td>
      <td><input type="text" name="MinimumElevation" value="<?php echo $row['minimumelevation']; ?>" title = "Enter the minimum distance in meters above or below (negetive) sea level of this locality." size="11" /></td>
    </tr>
		<tr>
      <td><b>MaximumElevation: </b></td>
      <td><input type="text" name="MaximumElevation" value="<?php echo $row['maximumelevation']; ?>" title = "Enter the maximum distance in meters above or below (negetive) sea level of this locality." size="11" /></td>
    </tr>
		<tr>
      <td><b>MinimumDepth: </b></td>
      <td><input type="text" name="MinimumDepth" value="<?php echo $row['minimumdepth']; ?>" title = "Enter the minimum distance in meters below water surface (positive below the surface, negative above.) at which the collection was made." size="11" /></td>
    </tr>
		<tr>
      <td><b>MaximumDepth: </b></td>
      <td><input type="text" name="MaximumDepth" value="<?php echo $row['maximumdepth']; ?>" title = "Enter the maximum distance in meters below water surface (positive below the surface, negative above.) at which the collection was made." size="11" /></td>
    </tr>
		<?php echo getContributorSelectTag($row['userid'], $row['groupid']); ?>
	</table>
  <?php echo extLinksRefs($id, "/Edit/Locality/?id=$id"); ?>
	<?php echo frmSubmitButton('Update'); ?>
	</form>
<?php
}

