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

require_once('../configuration/app.server.php');
echo "gogo\n";

$startTime = time();
$treeTableName = 'Tree';

// Connect to MySQL server

$db = connect();

$updateSql = "update $treeTableName set lft=?, rgt=? where tsn=?";
$param_types = array('integer','integer','integer');
$updateStmt = $db->prepare($updateSql, $param_types, MDB2_PREPARE_MANIP);
isMdb2Error($updateStatement,$updateSql);
$getSql = "select tsn from $treeTableName where parent_tsn=?";
$param_types = array('integer');
$getStmt = $db->prepare($getSql, $param_types, array('integer'));
isMdb2Error($getStmt,$getSql);

rebuildTree(0,1);

function rebuildTree($parent, $left) {
	// the right value of this node is the left value + 1
	$right = $left+1;

	global $updateSql, $updateStmt, $getSql, $getStmt;

	//echo "parent $parent, left: $left, right: $right\n";
	// get all children of this node
	$result = $getStmt->execute(array($parent));
	isMdb2Error($result,$getSql);
	//if ($left<10) {
		while ($tsn = $result->fetchOne()) {
			$right = rebuildTree($tsn, $right);
		}
	//}
	// we've got the left value, and now that we've processed
	// the children of this node we also know the right value
	$result = $updateStmt->execute(array($left,$right,$parent));
	isMdb2Error($result, $updateSql);
	// return the right value of this node + 1
	return $right+1;
}

?>

