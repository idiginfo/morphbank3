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

<?php 
/**
 * Hold common object functions
 * @author bruhn
 *
 */

/**
 * Return count for given object and id
 * @param $objType Table name
 * @param $objId ID of object
 * @param $whereCol Name of column in where clause
 * @return string|int
 */
function getObjectCount($objType, $objId, $whereCol = 'id') {
	$db = connect();
	$sql = "select count(*) from $objType where $whereCol = ?";
	$count = $db->getOne($sql, array('integer'), array($objId));
	if ($message = isMdb2Error($count, "select object count", 5)) {
		return $message;
	}
	return $count;
}

/**
 * Returns data for object
 * @param $objType
 * @param $objId
 * @param $objColumns
 * @param $whereCol
 * @return string|array
 */
function getObjectData($objType, $objId, $objColumns = '*', $whereCol = 'id') {
	$db = connect();
	$sql = "select $objColumns from $objType where $whereCol = ?";
	$row = $db->getRow($sql, null, array($objId), null, MDB2_FETCHMODE_ASSOC);
	if ($message = isMdb2Error($row, "select Object row", 5)) {
		return $message;
	}
	return $row;
}
?>
