<?php
/**
 * File name: index.php
 * @package Morphbank2
 * @subpackage Submit View
 */

// Config includes
include_once('head.inc.php');

include_once('extLinksRefs.php');
include_once('showFunctions.inc.php');

// Include javascript files
$includeJavaScript = array('jquery.1-4-2.min.js', 'jquery.validate.min.js', 'formMethods.js');

// Begin HTML
$title = 'Add View';
initHtml( $title, NULL, $includeJavaScript);

// Add the standard head section to all the HTML output.
echoHead(false, $title);
echo '<div class = "mainGenericContainer" style="width:Auto">';

if(!checkAuthorization(null, $objInfo->getUserId(), $objInfo->getUserGroupId(), 'add')){
	echo getNonAuthMessage(getNonAuthCode()) . "<br /><br />";
}  else {
	checkViewMessage($_REQUEST['id'], $_REQUEST['code']);
	displayViewForm();
}

// Finish HTML
echo '</div>';
finishHtml();


/**
 * Check for message codes sent via GET
 * @param array $array GET variables
 * @return 
 */
function checkViewMessage($id, $code){
    if ($code == 1) {
    	// If adding via a popup, update parent window with new view and close window
		if ($_GET['pop'] == 'yes') echo "<script>opener.update('View', ".$id.", '".$_GET['objTitle']."'); window.close();</script>";
    	
        echo "<h3>You have successfully added a <a href=\"/?id=$id\">View with id $id</a></h3>\n";
		echo "<br/>The form has been filled in to make it easy to submit a similar view\n";
		echo "<br/><a href=\"index.php\">Click here to clear the form</a></h3><br/><br/>\n";
    } elseif ($code == 2) {
    	echo '<div class="searchError">Error in CreateObject procedure</div><br /><br />';
	} elseif ($code == 3) {
    	echo '<div class="searchError">Error updating View</div><br /><br />';
	} elseif ($code == 4) {
    	echo '<div class="searchError">Error inserting external link or reference</div><br /><br />';
	}
}

/**
 * Display submit view form top
 * @return void
 */
function displayViewForm(){
	
	?>
	<form name ="addView" id="addView" class="frmValidate" method="post" action="commitview.php" enctype = "multipart/form-data">
	<input type="hidden" name="pop" id="pop" value="<?php echo ($_GET['pop'] == 'yes' ? 'yes' : 'no'); ?>" />
	<h1><b>Add View</b></h1>
	<br /><br />
	<table>
		<?php 
		echo getImageTechniqueSelectTag($_REQUEST['ImagingTechnique']);
		echo getImagePrepSelectTag($_REQUEST['ImagingPreparationTechnique']);
		echo getSpecimenPartSelectTag($_REQUEST['SpecimenPart']);
		echo getSexSelectTag($_REQUEST['Sex']);
		echo getFormSelectTag($_REQUEST['Form']);
		echo getDevelopmentalStageSelectTag($_REQUEST['DevelopmentalStage']);
		echo getViewAngleSelectTag($_REQUEST['ViewAngle']);
		?>
		<tr>
			<td><b>View Applicable To: <span class="req">*</span></b></td>
			<td align="left"><input type="text" name="tsnId"  size="10" value="<?php echo $_REQUEST['tsnId']; ?>" />
			<input type="text" name="Determination" value="<?php echo $_REQUEST['Determination']; ?>" size="20" title="Enter name of the highest taxon to which this view is applicable." readonly="readonly" />
			 &nbsp;&nbsp; <a href="javascript: pop('TSN', '/Admin/TaxonSearch/index.php?tsn=0&pop=yes&searchonly=0&view=1');"> <img src="/style/webImages/selectIcon.png" alt="Select taxon" title="Click to Select the TSN that suits this view" />(Select)</a>
			</td>
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
