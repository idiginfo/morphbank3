<?php
/**
 * Find nodes that are not in the tree (lft,rgt) and insert them
 */

require_once('../configuration/app.server.php');
echo("Updating tree by inserting nodes\n");
//echo phpinfo();
// Pear class for handling database connection
echo "Time at start of updateTree: ".date("H:i:s")."\n";
include_once('tsnFunctions.php');
$db = connect();

$selectNodeSql = "select tsn, parent_tsn from Tree where lft is null and rgt is null order by tsn";
$result = $db->query($selectNodeSql);
isMdb2Error($result,$selectNodeSql);

echo "Inserting ".$result->numRows()." nodes into tree\n";
while (list($child,$parent) = $result->fetchRow()){
	echo "Insert child $child into tree with parent $parent\n";
	insertIntoTree($child, $parent);
}

echo "Time at end of updateTree: ".date("H:i:s")."\n";
