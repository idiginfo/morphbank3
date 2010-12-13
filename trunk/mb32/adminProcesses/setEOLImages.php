<?php
/**
 * This script updates Image EOL for specified user
 * @param string|integer $argv[1] May pass user id or uin
 * @param boolean [optional] $arg[2] Defaults to true (add to EOL). Passing false removes from EOL
 */
require_once('../configuration/app.server.php');
include_once ('imageFunctions.php');

/* Get DB connection and set fetch mode */
$db = connect();

function getIdByInteger($table, $idVal){
	$db = connect();

	$query = "select id from $table where id = $idVal";

	$id = $db->queryOne($query);
	if(PEAR::isError($id)){
		die("Error executing select $table ID by integer statement. Query is '$query'\n".$id->getUserInfo()."\n");
	}

	return empty($id) ? false : $id;
}

function getIdByString($table, $name) {
	$db = connect();

	$field = $table == 'User' ? 'uin' : 'groupName';
	$nameVal = $db->quote($name);
	$query = "select id from $table where  $field = $nameVal";

	$id = $db->queryOne($query);
	if(PEAR::isError($id)){
		die("Error executing select $table ID by string statement. Query is '$query'\n".$id->getUserInfo()."\n");
	}
	return empty($id) ? false : $id;
}

/* Get passed arguments */
$arg1 = $argv[1];
$set  = (isset($argv[2]) && $argv[2] == 'false') ? false : true;

if (is_numeric($arg1)) {
	if ($id = getIdByInteger('User', $arg1)) {
		$where = "userid = $id";
	} elseif ($id = getIdByInteger('Groups', $arg1)) {
		$where = "groupid =  $id";
	} else {
		die("Could not retrieve ID from given integer argument.");
	}
}
else {
	if ($id = getIdByString('User', $arg1)) {
		$where = "userid = $id";
	} elseif ($id = getIdByString('Groups', $arg1)) {
		$where = "groupid = $id";
	} else {
		die("Could not retrieve ID from given string argument.");
	}
}
//echo "where clause is $where \n";
$affected = setEOL($where, $set);
echo "Updated $affected rows to eol=" . ($set?'1':'0') . "\n";
