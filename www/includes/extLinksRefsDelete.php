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
$linkId = trim($_GET['extLink']);
$ref = trim($_GET['ref']);

$db = connect();

// Get object ID for link
$sql = "select mbId from ExternalLinkObject where linkId = ?";
$id = $db->getOne($sql, null, array($linkId));
if (isMdb2Error($id, 'Selecting BaseObject Id for external link/reference: ' . $sql, false)) {
    header("location: $ref&code=30");
	exit;
}

// Check authorization to edit (removing external links is editing) 
if(!checkAuthorization($id, $objInfo->getUserId(), $objInfo->getUserGroupId(), 'delete')){
	header ("location: $ref");
	exit;
}

$stmt = $db->prepare("delete from ExternalLinkObject where linkId = ?", array('integer'));
isMdb2Error($stmt, 'Preparing to delete external link of reference');
$affectedRows = $stmt->execute(array($linkId));
isMdb2Error($affectedRows, 'Error deleting external link of reference');

header("location: $ref&code=32");
exit;
