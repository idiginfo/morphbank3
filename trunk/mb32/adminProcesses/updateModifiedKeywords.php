<?php
require_once('../configuration/app.server.php');
echo("Updating keywords for modified objects\n");
//echo phpinfo();
// Pear class for handling database connection
echo "Time at start of updateModifiedKeywords: ".date("H:i:s")."\n";

include_once("updateObjectKeywords.php");
// update the list of modified ids
include_once('recentlyModified.php');
echo "Time at end of recentlyModified ".date("H:i:s")."\n";
// include the methods for object update

$SQL_getModifiedIds = "SELECT id from RecentlyModified";
//$SQL_getModifiedIds = "SELECT id from BaseObject where id>400000 LIMIT 100";

$massUpdate = true;

updateKeywords($SQL_getModifiedIds);
updateGeolocated();

echo "End of Geolocated update Time: ".date("H:i:s")."\n";
$db->disconnect();
echo "That's all folks!!";

