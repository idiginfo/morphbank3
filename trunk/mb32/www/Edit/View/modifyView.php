<?php
/**
 * File: modifyView.php
 */


include_once('tsnFunctions.php');
include_once('updateObjectKeywords.php');
include_once('objectFunctions.php');
include_once('extLinksRefs.php');
include_once('updater.class.php');

$id = trim($_POST['objId']);
$userId = $objInfo->getUserId();
$groupId = $objInfo->getUserGroupId();
$indexUrl = "index.php?&id=$id";
if(!checkAuthorization($id, $userId, $groupId, 'edit')){
	header ("location: $indexUrl");
	exit;
}

// Get base object contributor
$result = getObjectData('BaseObject', $id, 'userId');
if(is_string($result)) { //Error returned
	header("location: $indexUrl&code=2");
	exit;
}
$baseContributor = $result['userid'];

// Get original view  information
$curr = getObjectData('View', $id);
if(is_string($curr)) { // Error returned
	header("location: $indexUrl&code=3");
	exit;
}

// Baseobject updater
$baseObjectUpdater = new Updater($db, $id, $userId, $groupId, 'BaseObject');
$baseObjectUpdater->addField("userId", $_POST['Contributor'], $baseContributor);
$baseObjectUpdater->addField("thumbURL", $_POST['StandardImage'], $curr['standardimageid']);
$numRowsBO = $baseObjectUpdater->executeUpdate();
if (is_string($numRowsBO)) { // Error returned
	header("location: $indexUrl&code=4");
	exit;
}

$viewName = implode('/', array($_POST['SpecimenPart'], $_POST['ViewAngle'], $_POST['ImagingTechnique'], $_POST['ImagingPreparationTechnique'], 
								$_POST['DevelopmentalStage'], $_POST['Sex'], $_POST['Form']));

// View updater
$viewUpdater = new Updater($db, $id, $userId, $groupId, 'View');
$viewUpdater->addField("viewName", $viewName, $curr['viewName']);
$viewUpdater->addField("imagingTechnique", $_POST['ImagingTechnique'], $curr['imagingtechnique']);
$viewUpdater->addField("imagingPreparationTechnique", $_POST['ImagingPreparationTechnique'], $curr['imagingpreparationtechnique']);
$viewUpdater->addField("specimenPart", $_POST['SpecimenPart'], $curr['specimenpart']);
$viewUpdater->addField("sex", $_POST['Sex'], $curr['sex']);
$viewUpdater->addField("form", $_POST['Form'], $curr['form']);
$viewUpdater->addField("developmentalStage", $_POST['DevelopmentalStage'], $curr['developmentalstage']);
$viewUpdater->addField("viewAngle", $_POST['ViewAngle'], $curr['viewangle']);
$viewUpdater->addField("viewTSN", $_POST['tsnId'], $curr['viewtsn']);
$viewUpdater->addField("standardImageId", $_POST['StandardImage'], $curr['standardimageid']);
$numRowsView = $viewUpdater->executeUpdate();
if (is_string($numRowsView)) { // Error returned
	header("location: $indexUrl&code=5");
	exit;
}

// update view keywords
if($numRowsView == 1 || $numRowsBO == 1) {
	updateKeywordsTable($id, 'update');
	// find all images with this view and loop through them and update the image keywords (new view info) blah
	$sql = "select id as imgId from Image where viewId = ?";
	$imgRows = $db->getAll($sql, null, array($id), null, MDB2_FETCHMODE_ASSOC);
	if(isMdb2Error($imgRows, 'Select Image ids for view', false)) {
		header("location: $indexUrl&code=6");
		exit;
	}
	if (!empty($imgRows)) {
		foreach ($imgRows as $imgRow) {
			updateKeywordsTable($imgRow['imgid'], 'update');
		}
	}
}

/* Update and Add external links and unique references */
$insertLinkRes = insertLinks($id, $_POST);
$updateLinkRes = updateLinks($id, $_POST);
$insertRefRes  = insertReferences($id, $_POST);
$updateRefRes  = updateReferences($id, $_POST);
if(!$insertLinkRes || !$insertRefRes) {
	header("location: $indexUrl&code=7");
	exit;
}
if(!$updateLinkRes || !$updateRefRes) {
	header("location: $indexUrl&code=8");
	exit;
}
header("location: $indexUrl&code=1");
exit;
?>
