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
 * File name: commitView.php
 * @package Morphbank2
 * @subpackage Submit View
 */

/* Config scripts */

include_once('updateObjectKeywords.php');
include_once('extLinksRefs.php');
include_once('urlFunctions.inc.php');
include_once('updater.class.php');

$userId = $objInfo->getUserId();
$groupId = $objInfo->getUserGroupId();
$indexUrl = 'index.php?'.getParamString($_REQUEST);
if(!checkAuthorization(null, $objInfo->getUserId(), $objInfo->getUserGroupId(), 'add')){
	header ("location: $indexUrl");
	exit;
}

$db = connect();

// Insert Object and View returning id
$params = array($db->quote("View"), $_POST['Contributor'], $groupId, $userId, "NOW()", $db->quote("View added"), $db->quote(NULL));
$result = $db->executeStoredProc('CreateObject', $params);
if(isMdb2Error($result, 'Create Object procedure', false)) {
	header("location: $indexUrl&code=2");
	exit;
}
$id = $result->fetchOne();
clear_multi_query($result);

$viewName = implode('/', array($_POST['SpecimenPart'], $_POST['ViewAngle'], $_POST['ImagingTechnique'], $_POST['ImagingPreparationTechnique'], 
								$_POST['DevelopmentalStage'], $_POST['Sex'], $_POST['Form']));

$viewUpdater = new Updater($db, $id, $userId, $groupId, 'View');
$viewUpdater->addField("viewName", $viewName, null);
$viewUpdater->addField("imagingTechnique", $_POST['ImagingTechnique'], null);
$viewUpdater->addField("imagingPreparationTechnique", $_POST['ImagingPreparationTechnique'], null);
$viewUpdater->addField("specimenPart", $_POST['SpecimenPart'], null);
$viewUpdater->addField("sex", $_POST['Sex'], null);
$viewUpdater->addField("form", $_POST['Form'], null);
$viewUpdater->addField("developmentalStage", $_POST['DevelopmentalStage'], null);
$viewUpdater->addField("viewAngle", $_POST['ViewAngle'], null);
$viewUpdater->addField("viewTSN", $_POST['tsnId'], null);
$numRows = $viewUpdater->executeUpdate();
if (is_string($numRows)) { // Error returned
	header("location: $indexUrl&code=3&id=' . $id");
	exit;
}

// Update keywords
if ($numRows > 0) {
	updateKeywordsTable($id);
}
	
/* Update and Add external links and unique references */
$insertLinkRes = insertLinks($id, $_POST);
$insertRefRes  = insertReferences($id, $_POST);
if(!$insertLinkRes || !$insertRefRes) {
	header("location: $indexUrl&id=$id&code=4");
	exit;
}

$objTitle = $_POST['SpecimenPart'] . '/' . $_POST['ViewAngle'] . '/' . $_POST['Sex'];
header ("location: $indexUrl&id=$id&code=1&objTitle=$objTitle");
exit;
?>
