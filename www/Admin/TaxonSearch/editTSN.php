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
include_once('postItFunctions.inc.php');
include_once('extLinksRefs.php');
include_once('showFunctions.inc.php');

$includeJavaScript = array('jquery.1-4-2.min.js', 'jquery-ui-1.8.min.js', 'jquery.validate.min.js', 'formMethods.js');

// The beginning of HTML
$title='Edit Taxon Name';
initHtml($title, null, $includeJavaScript);

// Add the standard head section to all the HTML output.
echoHead(false, $title);
setupPostIt();
echo '<div class="mainGenericContainer" style="width: auto; min-width:675px">';

// Get base object id from tsn being passed as request id
$db = connect();
$sql = "select id from TaxonConcept where tsn = ?";
$boId = $db->getOne($sql, null, array($_REQUEST['id']));
isMdb2Error($boId, 'Select BaseObject id for tsn: ' . $sql);

// Check authorization
if (!checkAuthorization($boId, null, null, 'edit')) {
	echo getNonAuthMessage(getNonAuthCode()) . "<br /><br />";
} else {
	echo "<h1><b>Edit Taxon</b></h1><br /><br />";
	checkEditMsg($_REQUEST['id'], $_REQUEST['code']);
	if ($row = getTaxonInfo($_REQUEST['id'])) {
		$parentRankId = getParentRank($parent_tsn);
		showTaxonForm($row, $row['tsn'], $row['parent_tsn'], $row['rank_id'], $parentRankId, $boId);
	} else {
		echo '<h1>Cannot retrieve taxa information</h1>';
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
function checkEditMsg($id, $code) {
	if ($code == 1) {
		echo "<h3>You have successfully updated the <a href=\"/?id=$id&tsn=true\">Taxa with id $id</a></h3><br /><br />\n";
	} elseif ($code == 2) {//
		echo '<div class="searchError">Error creating scientific name</div><br /><br />'."\n";
	} elseif ($code == 3) {//
		echo '<div class="searchError">Error selecting Tree information</div><br /><br />'."\n";
	} elseif ($code == 4) {//
		echo '<div class="searchError">Error selecting Taxa information</div><br /><br />'."\n";
	} elseif ($code == 5) {//
		echo '<div class="searchError">Error selecting BaseObject information</div><br /><br />'."\n";
	} elseif ($code == 6) {//
		echo '<div class="searchError">Error Taxon is used by other Morphbank entries. Please contact the Administrator to discuss changes</div><br /><br />'."\n";
	} elseif ($code == 7) {//
		echo '<div class="searchError">Cannot change rank when Taxon has children. Please contact the Administrator to disccuss changes</div><br /><br />'."\n";
	} elseif ($code == 8) {//
		echo '<div class="searchError">You do not have permissions to change children of this Taxon. Please contact the Administrator to discuss changes</div><br /><br />'."\n";
	} elseif ($code == 9) {//
		echo '<div class="searchError">Failed to update Tree table</div><br /><br />'."\n";
	} elseif ($code == 10) {//
		echo '<div class="searchError">Failed to update Taxa table</div><br /><br />'."\n";
	} elseif ($code == 11) {//
		echo '<div class="searchError">Failed to update Taxon Concept table</div><br /><br />'."\n";
	} elseif ($code == 12) {//
		echo '<div class="searchError">Failed to update Base Object table</div><br /><br />'."\n";
	} elseif ($code == 13) {//
		echo '<div class="searchError">Failed to update taxon children</div><br /><br />'."\n";
	} elseif ($code == 20) {//
		echo '<div class="searchError">Error inserting or updating vernacular</div><br /><br />'."\n";
	} elseif ($code == 21) {//
		echo '<div class="searchError">Error inserting external links and/or references</div><br /><br />'."\n";
	} elseif ($code == 22) {//
		echo '<div class="searchError">Error updating external links and/or references</div><br /><br />'."\n";
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
 * Get object data
 * @param object $id
 * @return boolean|resource
 */
function getTaxonInfo($id){
	$db = connect();
	
	$sql = "select t.*, tc.status, b.id as objid, b.userId as bouserid, b.groupId as bogroupid, tx.taxon_author_name, p.year, p.title, p.publicationTitle  
			from Tree t 
			left join Taxa tx on tx.tsn = t.tsn  
			left join TaxonConcept tc on tc.tsn = t.tsn 
			left join BaseObject b on b.id = tc.id 
			left join Publication p on p.id = t.publicationId 
			where t.tsn = ?";
	$row = $db->getRow($sql, null, array($id), null, MDB2_FETCHMODE_ASSOC);
	return isMdb2Error($row, "Error retrieving Taxon data") ? false : $row;
}
?>
