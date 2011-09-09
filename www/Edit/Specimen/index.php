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

include_once('tsnFunctions.php');
include_once('extLinksRefs.php');
include_once('showFunctions.inc.php');

$includeJavaScript = array('jquery.1-4-2.min.js', 'jquery.validate.min.js', 'formMethods.js');

// Begin HTML
$title = 'Edit Specimen';
initHtml($title, null, $includeJavaScript);
echoHead(false, $title);

echo '<div class="mainGenericContainer" style="width: Auto;">';

// Check authorization
if (!checkAuthorization($_REQUEST['id'], null, null, 'edit')) {
	echo getNonAuthMessage(getNonAuthCode()) . "<br /><br />";
} else {
	echo '<h1><b>Edit Specimen</b></h1><br /><br />';
	checkEditSpecimenMsg($_REQUEST['id'], $_REQUEST['code']);
	if(!$row = getSpecimen($_REQUEST['id'])) {
		echo '<h1>No Specimens for User: ' . $objInfo->getName() . '</h1>';
	} else {
		displaySpecimenForm($row);
	}
}
// Finish HTML
echo '</div>';
finishHtml();


/**
 * Get speciment result if exists
 * @param integer $id
 * @return array|boolean
 */
function getSpecimen($id){
	$db = connect();
	
	$sql = "SELECT s.*, b.userId, b.groupId, b.dateToPublish, l.locality, g.groupname   
			FROM Specimen s 
			LEFT JOIN BaseObject b on b.id = s.id 
			LEFT JOIN Locality l on l.id = s.localityId 
			LEFT JOIN Groups g on g.id = b.groupId
			WHERE s.id = ?";
	$row = $db->getRow($sql, null, array($id), null, MDB2_FETCHMODE_ASSOC);
	isMdb2Error($row, 'Select Specimen');
	return !$row ? false : $row;
}

/**
 * Echo messages
 * @param integer $id
 * @param integer $code
 * @return void
 */
function checkEditSpecimenMsg($id, $code) {
	if ($code == 1) {
		echo "<h3>You have successfully updated the <a href=\"/?id=$id\">Specimen with id $id</a></h3><br /><br />\n";
	} elseif ($code == 2) {
		echo '<div class="searchError">Error selecting TSN id.</div><br /><br />';
	} elseif ($code == 3) {
		echo '<div class="searchError">Please enter/select a proper taxon.</div><br /><br />';
	} elseif ($code == 4) {
		echo '<div class="searchError">Error retrieving Speciment object data.</div><br /><br />';
	} elseif ($code == 5) {
		echo '<div class="searchError">Error retrieving Basis of Record description.</div><br /><br />';
	} elseif ($code == 6) {
		echo '<div class="searchError">Error updating Specimen object.</div><br /><br />';
	} elseif ($code == 7) {
		echo '<div class="searchError">Error updating Base object.</div><br /><br />';
	} elseif ($code == 8) {
		echo '<div class="searchError">Error selecting images for updating.</div><br /><br />';
	} elseif ($code == 9) {
		echo '<div class="searchError">Error inserting external links or refences.</div><br /><br />';
	} elseif ($code == 10) {
		echo '<div class="searchError">Error updating external links or refences.</div><br /><br />';
	} elseif ($code == 11) { // Error from submit specimen
		echo '<div class="searchError">Error inserting specimen.</div><br /><br />';
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
 * Display specimen form
 * @param resource $result
 * @return 
 */
function displaySpecimenForm($row){
	global $objInfo;
	$db = connect();
	
	$id = $row['id'];
	
	$count = $row['imagesCount'];
	$tsnName = getTsnName($row['tsnid']); 
	
	?>
	<form id="editSpecimen" class="frmValidate" name="editSpecimen" method="post" action="modifySpecimen.php">
	<input type="hidden" name="objId" id="objId" value="<?php echo $id; ?>" />
	<input type="hidden" name="objType" id="objType" value="specimen" />
	<input type="hidden" name="objRelated" id="objRelated" value="image" />
	<input type="hidden" name="objAction" id="objAction" value="change" />
	<input type="hidden" name="count" id="count" value="<?php echo $count; ?>" />
	<table>
		<tr>
			<td><b>Group Name: </b></td>
			<td><b><a href="/?id=<?php echo $id ?>"><?php echo $row['groupname']?></a></b></td>
		</tr>
		<tr>
			<td><b>Specimen Id: </b></td>
			<td><b><?php echo $id ?></b>&nbsp; 
				<a href="javascript: confirmChange('<?php echo $id ?>', 'specimen', 'image', 'delete', '<?php echo $count ?>');" 
					title="Delete Specimen."><img src="/style/webImages/delete-trans.png" name="Delete" alt="Delete" />
				</a>
			</td>
		</tr>
		<?php
		echo getBasisRecordSelectTag($row['basisofrecordid']);
		echo getSexSelectTag($row['sex']);
		echo getFormSelectTag($row['form']);
		echo getDevelopmentalStageSelectTag($row['developmentalstage']);
		echo getTypeStatusSelectTag($row['typestatus']);
		?>	
		<tr>
			<td><b>Preparation Type: </b></td>
			<td align="left">
				<input type="text" name="PreparationType" size="30" value="<?php echo $row['preparationtype']; ?>" title="Enter the type of specimen preparation, if any." />
			</td>
		</tr>
		<tr>
			<td><b>Number of Individuals: </b></td>
			<td align="left">
				<input type="text" name="IndividualCount" value="<?php echo $row['individualcount']; ?>"size="11"
				title="Enter the number of individuals for this specimen record." />
			</td>
		</tr>
		<tr>
			<td><b>Determination Id/Name: <span class="req">*</span></b></td>
			<td>
				<input type="text" name="tsnId" size="10" value="<?php echo $row['tsnid']; ?>" />
				<h3>/</h3>
				<input type="text" name="Determination" size="20"
				readonly="readonly" 
				value="<?php echo $tsnName['name']; ?>" />
				&nbsp;&nbsp; 
				<a href= "javascript: pop('TSN', '/Admin/TaxonSearch/index.php?tsn=<?php echo $objInfo->getGroupTSN() ?>&amp;pop=yes&amp;searchonly=0')">
				<img src="/style/webImages/selectIcon.png" alt="Select Taxon "  title="Click to select Taxon Serial Number" />(Select)</a>
			</td>
		</tr>
		<tr>
			<td><b>Determined By: </b></td>
			<td align="left">
				<input type="text" name="DeterminedBy" size="30" 
				value="<?php echo $row['name']; ?>" 
				title="Enter the name(s) of the person(s) who determined the specimen."  
				onchange="document.forms[0].flag.value = true;" />
			</td>
		</tr>
		<tr>
			<td><b>Date Determined (YYYY-MM-DD): </b></td>
			<td align="left">
				<?php $date = explode(" ", $row['dateidentified']); ?>
				<input type="text" id= "DateDet" name="DateDetermined" size="10" 
				value="<?php echo $date[0]; ?>"
				title="Enter the date when the specimen was determined (yyyy-mm-dd).  Use yyyy-mm-00 if the day is unknown and yyyy-00-00 if the month is unknown." />
			</td>
		</tr>
		<tr>
			<td><b>Determination Notes: </b></td>
			<td align="left">
				<input type="text" name="Comment" size="30" 
				value="<?php echo $row['comment']; ?>"
				title="Enter notes related to the determination of the specimen." />
			</td>
		</tr>
		<tr>
		<td><b>Institution Code: </b></td>
			<td align="left">
				<input type="text" name="InstitutionCode" size="20" 
				value="<?php echo $row['institutioncode']; ?>"
				title="Enter the code of the institution owning the collection to which the specimen belongs." />
			</td>
		</tr>
		<tr>   
			<td><b>Collection Code: </b></td>
			<td align="left">
				<input type="text" name="CollectionCode" size="20" 
				value="<?php echo $row['collectioncode']; ?>"
				title="Enter a unique alphanumeric value which identifies the collection within the institution." />
			</td>
		</tr>
		<tr>
			<td><b>Catalog Number: </b></td>
			<td align="left">
				<input type="text" name="CatalogNumber" size="20"
				value="<?php echo $row['catalognumber']; ?>"
				title ="Enter a unique alphanumeric value which identifies the specimen record within the collection." />
			</td>
		</tr>
		<tr>
			<td><b>Previous Catalog Number: </b></td>
			<td align="left">
				<input type="text" name="PreviousCatalogNumber" size="20" 
				value="<?php echo $row['previouscatalognumber'];	?>"
				title="Enter a previous catalog number if the specimen was earlier identified by another &#10;catalog number in the current catalog or at/in another institution/catalog." />
			</td>
		</tr>
		<tr>   
			<td><b>Related Catalog Item: </b></td>
			<td align="left">
				<input type="text" name="RelatedCatalogItem" size="20" 
				value="<?php echo $row['relatedcatalogitem']; ?>"
				title="Enter a fully qualified identifier of a related catalog item (a reference to another specimen)." />
			</td>
		</tr>
		<tr>
			<td><b>Relationship Type: </b></td>
			<td align="left">
				<input type="text" name="RelationshipType" size="20"
				value="<?php echo $row['relationshiptype']; ?>"
				title="Enter a string that identifies the relationship between the specimen and the related catalog item. For instance &quot;parasite of&quot;. See the manual for more examples." />
			</td>
		</tr>
		<tr>
			<td><b>Collection Number: </b></td>
			<td align="left">
				<input type="text" name="CollectionNumber" size="20"
				value="<?php echo $row['collectionnumber']; ?>"
				title="Enter an identifying number or string applied to the specimen at the time of collection, if applicable." />
			</td>
		</tr>
		<tr>
			<td><b>Collector/Observer Name(s): </b></td>
			<td align="left">
				<input type="text" name="CollectorName" size="20" 
				value="<?php echo $row['collectorname']; ?>"
				title="Enter the name(s) of the collector(s) responsible for collecting the specimen or taking the observation." />
			</td>
		</tr>
		<tr>
			<td><b>Date Collected/<br />Photographed(YYYY-MM-DD): </b></td>
			<td align="left">
				<?php $date = explode(" ", $row['datecollected']); ?>
				<input type="text" id="DateColl" name="DateCollected" size="10"
				value="<?php echo $date[0]; ?>"
				title="Enter the date when the specimen was collected (yyyy-mm-dd). Use yyyy-mm-00 if the day is not known and yyyy-00-00 if the month is not known." />
			</td>
		</tr>
		<tr>
			<td><b>Earliest Date Collected (YYYY-MM-DD): </b></td>
			<td align="left">
				<?php $edate = explode(" ", $row['earliestdatecollected']); ?>
				<input type="text" id="earliestDateCollected" name="earliestDateCollected" size="10"
				value="<?php echo $edate[0]; ?>"
				title="Enter the earliest date specimen was collected (yyyy-mm-dd). Use yyyy-mm-00 if the day is not known and yyyy-00-00 if the month is not known." />
			</td>
		</tr>
		<tr>
			<td><b>Latest Date Collected (YYYY-MM-DD): </b></td>
			<td align="left">
				<?php $ldate = explode(" ", $row['latestdatecollected']); ?>
				<input type="text" id="latestDateCollected" name="latestDateCollected" size="10"
				value="<?php echo $ldate[0]; ?>"
				title="Enter the latest date specimen was collected (yyyy-mm-dd). Use yyyy-mm-00 if the day is not known and yyyy-00-00 if the month is not known." />
			</td>
		</tr>
		<tr>
			<td><b>Locality Id/Locality: </b></td>
			<td>
				<input type="text" name="LocalityId" size="5" value="<?php echo $row['localityid']; ?>" />
				<h3>/</h3>
				<input type="text" name="Locality" size="25" readonly="readonly" value="<?php echo $row['locality']; ?>" />	
				&nbsp;&nbsp; 
				<a href= "javascript: pop('Locality', '/Browse/ByLocation/index.php?pop=YES');">
				<img src="/style/webImages/selectIcon.png" alt="Select Locality"  
				title="Click to select Locality" />(Select)</a>
			</td>
		</tr>
		<tr>
			<td><b>Standard Image: </b></td>
			<td align="left">
				<input type="text" name="StandardImage"  size="6"
				title="Select the Image that best describes this View." 
				value="<?php echo $row['standardimageid']; ?>" />
				&nbsp;&nbsp; <a href= "javascript: pop('Image', '/Browse/ByImage/?pop=yes');">
				<img src="/style/webImages/selectIcon.png" alt="Select Image" 
				title="Click to Select the image that best describes this view" />(Select) </a>
			</td>
		</tr>
		<tr>
			<td><b>Notes:</b></td>
			<td align="left">
        <input type="text" name="Notes" size="50" value="<?php echo htmlentities($row['notes']); ?>" />
			</td>
		</tr>
		 <tr>
			<td><b>Date To Publish (YYYY-MM-DD): </b></td>
			<?php 
			$date = !empty($row['datetopublish']) ? 
					date("Y-m-d", strtotime($row['datetopublish'])) : 
					date('Y-m-d', (mktime(0, 0, 0, date("m") +6, date("d") - 1, date("Y"))));
			?>
			<td align="left"><input type="text" name="DateToPublish" size="10" value="<?php echo $date ?>" /></td>
		</tr>
		<?php echo getContributorSelectTag($row['userid'], $row['groupid']); ?>
	</table>
	<?php
		echo extLinksRefs($id, "/Edit/Specimen/?id=$id");
		echo frmSubmitButton('Update');
	?>
	</form>
<?php
}
?>
