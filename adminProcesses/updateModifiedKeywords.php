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
$config->errorRedirect = 0;
echo("Updating keywords for modified objects\n");
//echo phpinfo();
// Pear class for handling database connection
echo "Time at start of updateModifiedKeywords: ".date("H:i:s")."\n";

include_once("updateObjectKeywords.php"); // Located /adminProcesses/
// update the list of modified ids
include_once('recentlyModified.php'); // Located /adminProcesses/
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

