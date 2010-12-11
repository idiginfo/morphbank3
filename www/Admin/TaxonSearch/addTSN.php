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
 * This file is used to submit a taxon
 */
include_once('head.inc.php');
include_once('tsnFunctions.php');
include_once('postItFunctions.inc.php');
include_once('updateObjectKeywords.php');
include_once('extLinksRefs.php');
include_once('showFunctions.inc.php');

$includeJavaScript = array('jquery.1-4-2.min.js', 'jquery-ui-1.8.min.js', 'jquery.validate.min.js', 'formMethods.js');

// The beginning of HTML
$title='Add Taxon Name';
initHtml($title, null, $includeJavaScript);

// Add the standard head section to all the HTML output.
echoHead(false, $title);
setupPostIt();
echo '<div class="mainGenericContainer" style="width: 94%; min-width: 750px">';

$tsn = $_REQUEST['tsn'];

// Check authorization
if(!checkAuthorization(null, $objInfo->getUserId(), $objInfo->getUserGroupId(), 'add')){
	echo getNonAuthMessage(getNonAuthCode()) . "<br /><br />";
} else {
	echo "<h1><b>Add Taxon</b></h1><br /><br />";
	checkTaxonMsg($_REQUEST);
	if ($row = getTaxonInfo($tsn)) {
		showTaxonForm($row, null, $tsn, null, $row['rank_id']);
	} else {
		echo '<h1>Cannot retrieve parent taxa information</h1>';
	}
}
// Finish HTML
echo '</div>';
finishHtml();

/**
 * Retrieve information for taxon
 * @param int $tsn
 * @return array|bool
 */
function getTaxonInfo($tsn) {
	$db = connect();
	if (empty($tsn)) $tsn = $_REQUEST['main_tsn'];
	if (empty($tsn)) $tsn = $_REQUEST['parent_tsn'];
	if(!empty($tsn)){
		$query = 'select tsn as parent_tsn, scientificname as parentname, kingdom_id, rank_id from Tree where tsn = ?';
		$row = $db->getRow($query, null, array($tsn), null, MDB2_FETCHMODE_ASSOC);
		isMdb2Error($row);
	}
	return !$row ? false : $row;
}

/**
 * Check for any message codes
 * @param array $array
 * @return void
 */
function checkTaxonMsg($array){
	$code = $array['code'];
	$scientificName = $array['scientificName'];
	$id = $array['id'];
	$parentTsn = $array['maintsn'];
	$parentName = !(empty($parentTsn)) ? getScientificName($parentTsn) : '';
	if ($code == 1) {
		// If adding via a popup, update parent window with new taxon and close window
		if ($_GET['pop'] == 'yes') echo "<script>opener.update('TSN',".$id.",'".$scientificName."'); window.close();</script>";
		
		echo "<h3>You have successfully added the Taxon \"$scientificName\" with tsn <a href=\"/?id=$id&tsn=true\">$id</a></h3>\n";
		echo "<br />Click here to edit this record <a href=\"/Admin/TaxonSearch/editTSN.php?id=$id\">\"$scientificName\" with tsn $id</a>\n";
		echo "<br />Click here to return to parent Taxon: <a href=\"/Admin/TaxonSearch/index.php?tsn=$parentTsn&searchonly=1\">\"$parentName\"</a>";
		echo "<br />1. New name is in the Taxon Name Search table now\n";
		echo "<br />2. New name will be added to the Taxon Hierarchy in the next 24 hours</h3><br/><br/>\n";
	} elseif ($code == 2) {
		echo '<div class="searchError">You can not enter infraspecific epithet without specific epithet ex. species</div><br /><br />'."\n";
	} elseif ($code == 3) {
		echo '<div class="searchError">This taxon is in the database, contact the Morphbank admin team if you cannot find it in the Taxon Search table probably is not published yet</div><br /><br />'."\n";
	} elseif ($code == 4) {
		echo '<div class="searchError">Error inserting into Tree</div><br /><br />'."\n";
	} elseif ($code == 5) {
		echo '<div class="searchError">Error updating Taxa table</div><br /><br />'."\n";
	} elseif ($code == 10) {
		echo '<div class="searchError">Error inserting External links and/or references</div><br /><br />'."\n";
	}
	return;
}
?>
