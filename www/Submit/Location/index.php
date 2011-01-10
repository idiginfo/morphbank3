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
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

include_once('head.inc.php');
include_once('extLinksRefs.php');
include_once('showFunctions.inc.php');

// Include javascript files
$includeJavaScript = array('jquery.1-4-2.min.js', 'jquery-ui-1.8.min.js', 'jquery.validate.min.js', 'formMethods.js');

// Begin HTML
$title = 'Add Locality';
initHtml( $title, NULL, $includeJavaScript);

// Add the standard head section to all the HTML output.
echoHead( false, $title);

echo '<div class = "mainGenericContainer" style="width:Auto">';

// Check authorization
if(!checkAuthorization(null, $objInfo->getUserId(), $objInfo->getUserGroupId(), 'add')){
	echo getNonAuthMessage(getNonAuthCode()) . "<br /><br />";
} else {
	checkLocationMessage($_GET['id'], $_GET['code']);
	displayLocationForm();
}
// Finishe HTML
echo '</div>';
finishHtml();


/**
 * Checks for any messages useing GET array
 * @param array $array
 * @return void
 */
function checkLocationMessage($id, $code){
	if ($code == 1) {
		// If adding via a popup, update parent window with new location and close window
		if ($_GET['pop'] == 'yes') echo "<script>opener.update('Location',".$id.",'".$_GET['objTitle']."',''); window.close();</script>";
		
		echo "<h3>You have successfully added a <a href=\"/?id=$id\">Locality with id $id</a></h3>\n";
		echo "<br/>The form has been filled in to make it easy to submit a similar locality\n";
		echo "<br/><a href=\"index.php\">Click here to clear the form</a></h3><br/><br/>\n";
	} elseif ($code == 2) {
		echo '<div class="searchError">Error selecting Country data</div><br /><br />';
	} elseif ($code == 3) {
		echo '<div class="searchError">Error in CreateObject procedure</div><br /><br />';
	} elseif ($code == 4) {
		echo '<div class="searchError">Error updating Locality</div><br /><br />';
	} elseif ($code == 5) {
		echo '<div class="searchError">Error inserting external link or reference.</div><br /><br />';
	}
	return;
}

/**
 * Display top portion of submit Location form
 * @return void
 */
function displayLocationForm(){
	?>
	<form id="addLocality" class="frmValidate" name="addLocation" action="commitLocation.php" method="post">
	<input type="hidden" name="pop" id="pop" value="<?php echo ($_GET['pop'] == 'yes' ? 'yes' : 'no'); ?>" />
	<h1><b>Add Locality</b></h1>
	<br /><br />
	<table border="0">
		<tr>
			<td><b>Country:</b></td>
			<td align="left"><input name="Country" id="Country" class="autocomplete country" size="40" title="Enter a Country." value="<?php echo $_REQUEST['Country'] ?>" /></td>
		</tr>
		<tr>
			<td><b>Locality:</b></td>
			<td align="left"><input name="Locality" size="40" title="Enter the locality." value="<?php echo $_REQUEST['Locality'] ?>" /></td>
		</tr>
		<tr>
			<td><b>Latitude (Decimal):</b></td>
			<td align="left"><input type="text" name="Latitude"
				title="Enter the latitude in decimal degrees (0.0000 - 90.0000) and select North or South." 
				size="11" value="<?php echo $_REQUEST['Latitude'] ?>" />
				<select	name="NS">
					<option value="1" <?php echo $_REQUEST['NS'] == 1 ? 'selected' : '' ?>>North</option>
					<option value="2" <?php echo $_REQUEST['NS'] == 2 ? 'selected' : '' ?>>South</option>
				</select>
			</td>
		</tr>
		<tr>
			<td><b>Longitude (Decimal):</b></td>
			<td align="left"><input type="text" name="Longitude"
				title="Enter the longitude in decimal degrees (0.0000 - 180.0000) and select East or West."
				size="11" value="<?php echo $_REQUEST['Longitude'] ?>" /> 
				<select	name="EW">
					<option value="1" <?php echo $_REQUEST['EW'] == 1 ? 'selected' : '' ?>>East</option>
					<option value="2" <?php echo $_REQUEST['EW'] == 2 ? 'selected' : '' ?>>West</option>
				</select>
			</td>
		</tr>
		<tr>
			<td><b>Coordinate Precision:</b></td>
			<td align="left"><input type="text" name="CoordinatePrecision"
				title="Estimate the coordinate precision expressed as distance in meters that corresponds to a radius around the latitude-longitude coordinates."
				size="11" value="<?php echo $_REQUEST['CoordinatePrecision'] ?>" /></td>
		</tr>
		<tr>
			<td><b>Minimum Elevation (meters):</b></td>
			<td align="left"><input type="text" name="MinimumElevation"
				title="Enter the minimum distance in meters above or below (negetive) sea level of this locality."
				size="6" value="<?php echo $_REQUEST['MinimumElevation'] ?>" /></td>
		</tr>
		<tr>
			<td><b>Maximum Elevation (meters):</b></td>
			<td align="left"><input type="text" name="MaximumElevation"
				title="Enter the maximum  distance in meters above or below (negetive) sea level of this locality."
				size="6" value="<?php echo $_REQUEST['MaximumElevation'] ?>" /></td>
		</tr>
		<tr>
			<td><b>Minimum Depth (meters):</b></td>
			<td align="left"><input type="text" name="MinimumDepth"
				title="Enter the minimum distance in meters below the surface of the water at which the collection was made. Positive below the surface, negative above."
				size="6" value="<?php echo $_REQUEST['MinimumDepth'] ?>" /></td>
		</tr>
		<tr>
			<td><b>Maximum Depth (meters):</b></td>
			<td align="left"><input type="text" name="MaximumDepth"
				title="Enter the maximum distance in meters bellow the surface of the water at which the collection was made. Positive below the surface, negative above."
				size="6" value="<?php echo $_REQUEST['MaximumDepth'] ?>" /></td>
		</tr>
		<?php echo getContributorSelectTag($_REQUEST['Contributor']); ?>
	</table>
	<?php 
	echo extLinksRefs();
	echo frmSubmitButton('Submit');
	?>
	</form>
<?php
}
