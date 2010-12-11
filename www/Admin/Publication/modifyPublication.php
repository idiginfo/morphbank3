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
 * File modifyPublication
 * @package Edit
 * @subpackage Publication
 */


include_once('updateObjectKeywords.php');
include_once('extLinksRefs.php');
include_once('urlFunctions.inc.php');
include_once('updater.class.php');

$id = $_POST['objId'];
$userId = $objInfo->getUserId();
$groupId = $objInfo->getUserGroupId();

// Check authorization
$indexUrl = 'editPublication.php?id=' . $id;
if(!checkAuthorization($id, $userId, $groupId, 'add')){
	header ("location: $indexUrl");
	exit;
}

$db = connect();
$sql = "select p.*, b.userId, b.groupId, b.dateToPublish FROM Publication p 
		left join BaseObject b ON b.id = p.id 
		where p.id = ? AND b.objectTypeId = 'Publication'";
$row = $db->getRow($sql, null, array($id), null, MDB2_FETCHMODE_ASSOC);
if (isMdb2Error($row, 'select publication data', false)) {
	header ("location: $indexUrl&code=2");
	exit;
}

// Update BaseObject
$baseObjUpdater = new Updater($db, $id, $userId , $groupId, 'BaseObject');
$baseObjUpdater->addField('userId', $_POST['Contributor'], $row['userid']);
$baseObjUpdater->addField('submittedBy', $userId, $row['submittedby']);
$baseObjUpdater->addField('dateToPublish', $_POST['DateToPublish'], $row['datetopublish']);
$numRowsBO = $baseObjUpdater->executeUpdate();
if (is_string($numRowsBO)) { // Error returned
	header("location: $indexUrl&id=$id&code=3");
	exit;
}


// prepare update
$pubUpdater = new Updater($db, $id, $userId , $groupId, 'Publication');
$pubUpdater->addField('publicationType', $_POST['publicationtype'], $row['publicationtype']);
$pubUpdater->addField('doi', $_POST['doi'], $row['doi']);
$pubUpdater->addField('publicationTitle', $_POST['publicationtitle'], $row['publicationtitle']);
$pubUpdater->addField('title', $_POST['title'], $row['title']);
$pubUpdater->addField('author', $_POST['author'], $row['author']);
$pubUpdater->addField('year', $_POST['year'], $row['year']);
$pubUpdater->addField('month', $_POST['month'], $row['month']);
$pubUpdater->addField('day', $_POST['day'], $row['day']);
$pubUpdater->addField('number', $_POST['number'], $row['number']);
$pubUpdater->addField('series', $_POST['series'], $row['series']);
$pubUpdater->addField('organization', $_POST['organization'], $row['organization']);
$pubUpdater->addField('school', $_POST['school'], $row['school']);
$pubUpdater->addField('pages', $_POST['pages'], $row['pages']);
$pubUpdater->addField('chapter', $_POST['chapter'], $row['chapter']);
$pubUpdater->addField('volume', $_POST['volume'], $row['volume']);
$pubUpdater->addField('edition', $_POST['edition'], $row['edition']);
$pubUpdater->addField('editor', $_POST['editor'], $row['editor']);
$pubUpdater->addField('howPublished', $_POST['howpublished'], $row['howpublished']);
$pubUpdater->addField('institution', $_POST['institution'], $row['institution']);
$pubUpdater->addField('publisher', $_POST['publisher'], $row['publisher']);
$pubUpdater->addField('address', $_POST['address'], $row['address']);
$pubUpdater->addField('isbn', $_POST['isbn'], $row['isbn']);
$pubUpdater->addField('issn', $_POST['issn'], $row['issn']);
$pubUpdater->addField('note', $_POST['note'], $row['note']);
$numRowsPub = $pubUpdater->executeUpdate();
if (is_string($numRowsPub)) { // Error returned
	header("location: $indexUrl&id=$id&code=4");
	exit;
}

// Update keywords
if ($numRowsPub == 1 || $numRowsBO == 1) {
	updateKeywordsTable($id, 'update');
}

// Update and Add external links and unique references
$insertLinkRes = insertLinks($id, $_POST);
$updateLinkRes = updateLinks($id, $_POST);
$insertRefRes  = insertReferences($id, $_POST);
$updateRefRes  = updateReferences($id, $_POST);
if(!$insertLinkRes || !$insertRefRes) {
	header("location: $indexUrl&code=5");
	exit;
}
if(!$updateLinkRes || !$updateRefRes) {
	header("location: $indexUrl&code=6");
	exit;
}

header("location: $indexUrl&code=1");
exit;
?>
