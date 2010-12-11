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
 * File commitPublication
 * Process submitted Publication form
 */


include_once('updateObjectKeywords.php');
include_once('extLinksRefs.php');
include_once('urlFunctions.inc.php');
include_once('updater.class.php');

$userId = $objInfo->getUserId();
$groupId = $objInfo->getUserGroupId();

// Check authorization
$indexUrl = 'addPublication.php?'.getParamString($_REQUEST);
if(!checkAuthorization(null, $userId, $groupId, 'add')){
	header ("location: $indexUrl");
	exit;
}

$contributor = $_POST['Contributor'];
$publishDate = $_POST['DateToPublish'];
if(!$publishDate) {
	$dateToPublish = date('Y-m-d', strtotime("+6 months"));
} else {
	$dateToPublish = date("Y-m-d", strtotime($publishDate));
}

$params = array($db->quote("Publication"), $contributor, $groupId, $userId, $db->quote($dateToPublish), $db->quote("Publication added"), $db->quote(NULL));
$result = $db->executeStoredProc('CreateObject', $params);
if(isMdb2Error($result, 'Create Object procedure', false)) {
	header("location: $indexUrl&code=2");
	exit;
}
$id = $result->fetchOne();
clear_multi_query($result);
	
// prepare update
$pubUpdater = new Updater($db, $id, $userId , $groupId, 'Publication');
$pubUpdater->addField('publicationType', $_POST['publicationtype'], null);
$pubUpdater->addField('doi', $_POST['doi'], null);
$pubUpdater->addField('publicationTitle', $_POST['publicationtitle'], null);
$pubUpdater->addField('title', $_POST['title'], null);
$pubUpdater->addField('author', $_POST['author'], null);
$pubUpdater->addField('year', $_POST['year'], null);
$pubUpdater->addField('month', $_POST['month'], null);
$pubUpdater->addField('day', $_POST['day'], null);
$pubUpdater->addField('number', $_POST['number'], null);
$pubUpdater->addField('series', $_POST['series'], null);
$pubUpdater->addField('organization', $_POST['organization'], null);
$pubUpdater->addField('school', $_POST['school'], null);
$pubUpdater->addField('pages', $_POST['pages'], null);
$pubUpdater->addField('chapter', $_POST['chapter'], null);
$pubUpdater->addField('volume', $_POST['volume'], null);
$pubUpdater->addField('edition', $_POST['edition'], null);
$pubUpdater->addField('editor', $_POST['editor'], null);
$pubUpdater->addField('howPublished', $_POST['howpublished'], null);
$pubUpdater->addField('institution', $_POST['institution'], null);
$pubUpdater->addField('publisher', $_POST['publisher'], null);
$pubUpdater->addField('address', $_POST['address'], null);
$pubUpdater->addField('isbn', $_POST['isbn'], null);
$pubUpdater->addField('issn', $_POST['issn'], null);
$pubUpdater->addField('note', $_POST['note'], null);
$numRows = $pubUpdater->executeUpdate();
if (is_string($numRows)) { // Error returned
	header("location: $indexUrl&id=$id&code=3");
	exit;
}

// Update keywords
if ($numRows == 1) {
	updateKeywordsTable($id);
}

// Add external links and references
$insertLinkRes = insertLinks($id, $_POST);
$insertRefRes = insertReferences($id, $_POST);
if(!$insertLinkRes || !$insertRefRes) {
	header("location: $indexUrl&id=$id&code=4");
	exit;
}

$title = !empty($_POST['title']) ? $_POST['title'] : $_POST['publicationtitle'];
$objTitle = $_POST['author'] . '; ' . $_POST['year'] . '; ' . $title;
if (strlen($objTitle) > 55) $title = substr($objTitle, 0, 55) . '...';
header("location: $indexUrl&id=$id&code=1&objTitle=".$objTitle);
exit;
?>
