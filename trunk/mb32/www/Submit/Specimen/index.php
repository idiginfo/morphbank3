#-------------------------------------------------------------------------------
# Copyright (c) 2010 Greg Riccardi, Fredrik Ronquist.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Public License v2.0
# which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# 
# Contributors:
#   Fredrik Ronquist - conceptual modeling and interaction design
#   Austin Mast - conceptual modeling and interaction design
#   Greg Riccardi - initial API and implementation
#   Wilfredo Blanco - initial API and implementation
#   Robert Bruhn - initial API and implementation
#   Christopher Cprek - initial API and implementation
#   David Gaitros - initial API and implementation
#   Neelima Jammigumpula - initial API and implementation
#   Karolina Maneva-Jakimoska - initial API and implementation
#   Katja Seltmann - initial API and implementation
#   Stephen Winner - initial API and implementation
#-------------------------------------------------------------------------------
<?php
/**
 * File name: index.php
 * @package Morphbank2
 * @subpackage Submit Specimen
 */
include_once('head.inc.php');

include_once('tsnFunctions.php');
include_once('extLinksRefs.php');
include_once('showFunctions.inc.php');

/* Include javascript files */
$includeJavaScript = array('jquery.1-4-2.min.js', 'jquery.validate.min.js', 'formMethods.js');

/* Begin HTML */
$title = 'Add Specimen';
initHtml( $title, NULL, $includeJavaScript);

/* Add the standard head section to all the HTML output. */
echoHead( false, $title);
echo '<div class = "mainGenericContainer" style="width:Auto">';

if(!checkAuthorization(null, $objInfo->getUserId(), $objInfo->getUserGroupId(), 'add')){
	echo getNonAuthMessage(getNonAuthCode()) . "<br /><br />";
} else {
	checkSpecimenMessage($_GET['id'], $_GET['code']);
	displaySpecimenForm();
}
// Finish HTML
echo '</div>';
finishHtml();


/**
 * Checks GET variables for message code
 * @param integer $id
 * @param integer $code
 * @return void
 */
function checkSpecimenMessage($id, $code){
	if ($code == 1) {
		// If adding via a popup, update parent window with new specimen and close window
		if ($_GET['pop'] == 'yes') echo "<script>opener.update('Specimen', ".$id.", '".$_GET['objTitle']."'); window.close();</script>";

		echo "<h3>You have successfully added a <a href=\"/?id=$id\">Specimen with id $id</a></h3>\n";
		echo "<br/>The form has been filled in to make it easy to submit a similar specimen\n";
		echo "<br/><a href=\"index.php\">Click here to clear the form</a></h3><br/><br/>\n";
	} elseif ($code == 2) {
		echo '<div class="searchError">Error selecting TSN Id</div><br /><br />';
	} elseif ($code == 3) {
		echo '<div class="searchError">Please select a valid Taxon</div><br /><br />';
	} elseif ($code == 4) {
		echo '<div class="searchError">Error in CreateObject procedure</div><br /><br />';
		echo '<div class="searchError">System Error. Please try again later.</div><br /><br />';
	} elseif ($code == 5) {
		echo '<div class="searchError">Error in updating Specimen in database</div><br /><br />';
	} elseif ($code == 6) {
		echo '<div class="searchError">Error inserting external link or reference.</div><br /><br />';
	}
	return;
}

/**
 * Build top of submit speciman form
 * @return void
 */
function displaySpecimenForm(){
	global $objInfo;
	?>
	<form name="addSpecimen" id="addSpecimen" class="frmValidate" method="post" action="commitSpecimen.php" enctype="multipart/form-data"> 
	<input type="hidden" name="pop" id="pop" value="<?php echo ($_GET['pop'] == 'yes' ? 'yes' : 'no'); ?>" />
	<h1><b>Add Specimen</b></h1>
	<br /><br />
	<table>
		<?php 
		echo getBasisRecordSelectTag($_REQUEST['BasisOfRecord']);
		echo getSexSelectTag($_REQUEST['Sex']);
		echo getFormSelectTag($_REQUEST['Form']);
		echo getDevelopmentalStageSelectTag($_REQUEST['DevelopmentalStage']);
		echo getTypeStatusSelectTag($_REQUEST['TypeStatus']);
		?>	    
	    <tr>
			<td><b>Preparation Type: </b></td>
	  		<td align="left"><input type="text" name="PreparationType" value="<?php echo $_REQUEST['PreparationType'] ?>" size="30" title="Enter the type of specimen preparation, if any."/></td>
	    </tr>
	    <tr>
			<td><b>Number of Individuals: </b></td>
	  		<td align="left"><input type="text" name="IndividualCount" value="<?php echo $_REQUEST['IndividualCount'] ?>" size="11" title="Enter the number of individuals for this specimen record." /></td>
	    </tr>
	    <tr>
	  		<td><b>Determination Id/Name: <span class="req">*</span></b></td>
	   		<td>
	   			<input type="text" name="tsnId" value="<?php echo $_REQUEST['tsnId'] ?>" size="11" />
	  			<h3>/</h3><input type="text" name="Determination" size="20" value="<?php echo $_REQUEST['Determination']; ?>" readonly /> 
				&nbsp;&nbsp; <a href="javascript: pop('TSN', '/Admin/TaxonSearch/index.php?&tsn=<?php echo $objInfo->getUserTSN() ?>&pop=yes&searchonly=0')"> <img src="/style/webImages/selectIcon.png" alt="Select Taxon "  title="Click to select taxon name" />(Select)</a>
			</td>
	    </tr>
	    <tr>
	  		<td><b>Determined By: </b></td>
	  		<td align="left"><input type="text" name="tsnname" value="<?php echo $_REQUEST['tsnname'] ?>" size="30" title="Enter the name(s) of the person(s) who determined the specimen." /></td>
	    </tr>
	    <tr>
	  		<td><b>Date Determined (YYYY-MM-DD): </b></td>
	  		<td align="left"><input type="text" id= "DateDet" name="DateDetermined" value="<?php echo $_REQUEST['DateDetermined'] ?>" size="10" title="Enter the date when the specimen was determined (YYYY-MM-DD). Use YYYY-MM-00 if the day is unknown and YYYY-00-00 if the month and day are unknown." /></td>
	    </tr>
	    <tr>
	  		<td><b>Determination Notes: </b></td>
	  		<td align="left"><input type="text" name="Comment" value="<?php echo $_REQUEST['Comment'] ?>" size="30" title="Enter notes related to the determination of the specimen." /></td>
	    </tr>
	    <tr>
	  		<td><b>Institution Code: </b></td>
	  		<td align="left"><input type="text" name="InstitutionCode" value="<?php echo $_REQUEST['InstitutionCode'] ?>" size="20" title="Enter the code of the institution owning the collection to which the specimen belongs." /></td>
	    </tr>
	    <tr>
	  		<td><b>Collection Code: </b></td>
	  		<td align="left"><input type="text" name="CollectionCode" value="<?php echo $_REQUEST['CollectionCode'] ?>" size="20" title="Enter a unique alphanumeric value which identifies the collection within the institution." /></td>
	    </tr>
	    <tr>
	  		<td><b>Catalog Number:</b></td>
	  		<td align="left"><input type="text" name="CatalogNumber" value="<?php echo $_REQUEST['CatalogNumber'] ?>" size="20" title ="Enter a unique alphanumeric value which identifies the specimen record within the collection." /></td>
	    </tr>
	    <tr>
	  		<td><b>Previous Catalog Number: </b></td>
	  		<td align="left"><input type="text" name="PreviousCatalogNumber" value="<?php echo $_REQUEST['PreviousCatalogNumber'] ?>" size="20" title ="Enter a previous catalog number if the specimen was earlier identified by another &#10;catalog number in the current catalog or at/in another institution/catalog." /></td>
	    </tr>
	    <tr>
	  		<td><b>Related Catalog Item: </b></td>
	  		<td align="left"><input type="text" name="RelatedCatalogItem" value="<?php echo $_REQUEST['RelatedCatalogItem'] ?>" size="20" title="Enter a fully qualified identifier of a related catalog item (a reference to another specimen)." /></td>
	    </tr>
	    <tr>
	  		<td><b>Relationship Type: </b></td>
	  		<td align="left"><input type="text" name="RelationshipType" value="<?php echo $_REQUEST['RelationshipType'] ?>" size="20" title="Enter a string that identifies the relationship between the specimen and the related catalog item. For instance &quot;parasite of&quot;. See the manual for more examples." /></td>
	    </tr>
	    <tr>
	  		<td><b>Collection Number: </b></td>
	  		<td align="left"><input type="text" name="CollectionNumber" value="<?php echo $_REQUEST['CollectionNumber'] ?>" size="20" title="Enter an identifying number or string applied to the specimen at the time of collection, if applicable." /></td>
	    </tr>
	    <tr>
	  		<td><b>Collector/Observer Name(s): </b></td>
	  		<td align="left"><input type="text" name="CollectorName" value="<?php echo $_REQUEST['CollectorName'] ?>" size="20" title="Enter the name(s) of the collector(s) responsible for collecting the specimen or taking the observation." /></td>
	    </tr>
	    <tr>
	  		<td><b>Date Collected/<br />Photographed(YYYY-MM-DD): </b></td>
	  		<td align="left"><input type="text" id= "DateColl" name="DateCollected" size="10" title="Enter the date when the specimen was collected (YYYY-MM-DD). Use YYYY-MM-00 if the day is not known and YYYY-00-00 if the month and day are not known." /></td>
	    </tr>
	    <tr>
	  		<td><b>Earliest Date Collected (YYYY-MM-DD): </b></td>
	  		<td align="left"><input type="text" id="earliestDateCollected" name="earliestDateCollected" size="10" title="Enter the earliest date specimen was collected (YYYY-MM-DD). Use YYYY-MM-00 if the day is not known and YYYY-00-00 if the month and day are not known." /></td>
	    </tr>
	    <tr>
	  		<td><b>Latest Date Collected (YYYY-MM-DD): </b></td>
	  		<td align="left"><input type="text" id="latestDateCollected" name="latestDateCollected" size="10" title="Enter the latest date specimen was collected (YYYY-MM-DD). Use YYYY-MM-00 if the day is not known and YYYY-00-00 if the month and day are not known." /></td>
	    </tr>
	    <tr>
	  		<td><b>Locality Id/ Locality: </b></td>
	  		<td align="left"> <input type="text" name="LocalityId" value="<?php echo $_REQUEST['LocalityId'] ?>" size="10" />
			<h3> /</h3><input type="text" name="Locality" value="<?php echo $_REQUEST['Locality'] ?>" size="25" readonly="readonly" />
			&nbsp;&nbsp; <a href= "javascript: pop('Locality', '/Browse/ByLocation/index.php?pop=yes&referer=Submit');"> <img src="/style/webImages/selectIcon.png" alt="Select Locality"  title="Click to select Locality" />(Select)</a>
		</td>
	    </tr>
	    <tr>
	  		<td><b>Notes: </b></td>
	  		<td align="left"><input type="text" name="Notes" value="<?php echo $_REQUEST['Notes'] ?>" size="50" /></td>
	    </tr>
	    <tr>
			<td><b>Date To Publish (YYYY-MM-DD): </b></td>
			<td align="left"><input type="text" name="DateToPublish" size="10" value="<?php echo date('Y-m-d', (mktime(0, 0, 0, date("m") +6, date("d") - 1, date("Y")))); ?>" /></td>
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
?>
