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


require_once(dirname(dirname(__FILE__)) . '/configuration/app.server.php');
require_once('admin.inc.php');

$db = connect();
echo date("H:i:s\n");

$specCount = updateTable('Specimen','specimenId');
echo "\n\nTotal Specimens: ".$spCount."\n";
$viewCount = updateTable('View','viewId');
echo "\n\nTotal Views: ".$viewCount."\n\n";
echo date("H:i:s\n");


function updateTable($table, $field){
global $db;
	$sql = "SELECT id FROM $table WHERE (standardImageId = 0 OR standardImageId IS NULL) ";
	$results = $db->query($sql);
	isMdb2Error($results,$sql);

	$objCount = 0;
	while ($id = $results->fetchOne()) {
		$sql = "SELECT id FROM Image WHERE $field=$id ORDER BY id ASC LIMIT 1";
		//echo $sql."\n";
		$stdId = $db->queryOne($sql);
		isMdb2Error($stdId,$sql);
		if (!empty($stdId)){
			$updateSql = "UPDATE $table SET standardImageId=$stdId WHERE id=$id";
			//echo $updateSql."\n";
			$count = $db->exec($updateSql);
			isMdb2Error($count,$updateSql);
			$updateSql = "UPDATE BaseObject SET thumbURL=$stdId WHERE id=$id";
			//echo $updateSql."\n";
			$count = $db->exec($updateSql);
			isMdb2Error($count,$updateSql);
			$objCount++;
		}
	}
	return $objCount;
}


