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

// Configuration
include_once('head.inc.php');
include_once('extLinksRefs.php');
include_once('showFunctions.inc.php');

// Include javascript files
$includeJavaScript = array('jquery.1-4-2.min.js', 'jquery.validate.min.js', 'formMethods.js');

// Begin HTML
$title = 'Add Image';
initHtml( $title, NULL, $includeJavaScript);

// Add the standard head section to all the HTML output.
echoHead(false, $title);
echo '<div class = "mainGenericContainer" style="width:Auto">';

// Check authorization
if(!checkAuthorization(null, $objInfo->getUserId(), $objInfo->getUserGroupId(), 'add')){
	echo getNonAuthMessage(getNonAuthCode()) . "<br /><br />";
} else {
	checkImageMessage($_REQUEST['id'], $_REQUEST['code']);
	displayImageForm();
}

// Finish HTML
echo '</div>';
finishHtml();

/**
 * Check for any GET message codes
 * @param array $array
 * @return void
 */
function checkImageMessage($id, $code){
	if ($code == 1) {
		echo "<h3>You have successfully added an <a href=\"/?id=$id\">Image with id $id</a></h3>\n";
		echo "<br/>The form has been filled in to make it easy to submit a similar image\n";
		echo "<br/><a href=\"index.php\">Click here to clear the form</a></h3><br/><br/>\n";
	} elseif ($code == 2) {
		echo '<div class="searchError">Error verifying View Id</div><br /><br />'."\n";
	} elseif ($code == 3) {
		echo '<div class="searchError">Error retrieving locality id for Specimen</div><br /><br />'."\n";
	} elseif ($code == 4) {
		echo '<div class="searchError">Error in CreateObject procedure</div><br /><br />'."\n";
	} elseif ($code == 5) {
		echo '<div class="searchError">Error retrieving new BaseObject id</div><br /><br />'."\n";
	} elseif ($code == 6) {
		echo '<div class="searchError">BaseObject id empty</div><br /><br />'."\n";
	} elseif ($code == 7) {
		echo '<div class="searchError">Error processing remote image.</div><br /><br />'."\n";
	} elseif ($code == 8) {
		echo '<div class="searchError">Error retrieving access number for Image</div><br /><br />'."\n";
	} elseif ($code == 9) {
		echo '<div class="searchError">File input cannot be empty</div><br /><br />'."\n";
	}
	return;
}

/**
 * Display image form
 * @return void
 */
function displayImageForm(){
	
	?>
	<form id="adImage" class="frmValidate" name="addImage" method="post" action="commitImage.php" enctype="multipart/form-data">
	<h1><b>Add Image</b></h1>
	<br />
	<br />
	<table>
		<tr>
			<td><strong>Upload Image: <span class="req">*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></td>
			<td><input type="file" name="ImageFile" size="40"/></td>
		</tr>
		<tr>
			<td><strong>Specimen Id / Specimen: <span class="req">*</span></strong></td>
			<td align="left">
				<input type="text" name="SpecimenId" value="<?php echo $_REQUEST['SpecimenId']?>" size="10" />
				<h3>/</h3>
				<input type="text" name="Specimen" value="<?php echo $_REQUEST['Specimen']?>" size="40" readonly="readonly" />
				&nbsp;&nbsp; <a href="javascript: pop('Specimen', '/Browse/BySpecimen/index.php?pop=yes&referer=Submit');">
				<img src="/style/webImages/selectIcon.png" alt="Select Specimen " title="Click to select a Specimen" /> (Select)</a>
			</td>
		</tr>
		<tr>
			<td><strong>View Id / View: <span class="req">*</span></strong></td>
			<td align="left">
				<input type="text" name="ViewId" value="<?php echo $_REQUEST['ViewId']?>" size="10" />
				<h3>/</h3>
				<input type="text" name="View" value="<?php echo $_REQUEST['View']?>" size="40" readonly="readonly" /> 
				&nbsp;&nbsp; <a href="javascript: pop('View','/Browse/ByView/index.php?pop=yes&referer=Submit');">
				<img src="/style/webImages/selectIcon.png" alt="Select View" title="Click to select a View" /> (Select)</a></td>
		</tr>
		<tr>
			<td><b>Magnification: </b></td>
			<td align="left"><input type="text" name="Magnification" value="<?php echo $_REQUEST['Magnification']?>" title="Enter the magnification the image is taken." size="10" /></td>
		</tr>
		<tr>
			<td><b>Photographer: </b></td>
			<td align="left"><input type="text" name="photographer" value="<?php echo $_REQUEST['photographer']?>" title="Enter name of photographer." size="30" /></td>
		</tr>
		<tr>
			<td><b>Date To Publish (YYYY-MM-DD): </b></td>
			<td align="left"><input type="text" name="DateToPublish" size="10" value="<?php echo date('Y-m-d', strtotime('+6 months')); ?>" /></td>
		</tr>
		<tr>
			<td><b>Copyright: </b></td>
			<td align="left"><input type="text" name="Copyright" value="<?php echo $_REQUEST['Copyright']?>" size="30" /></td>
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
