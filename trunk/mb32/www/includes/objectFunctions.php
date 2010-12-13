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
