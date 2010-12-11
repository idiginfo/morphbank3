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
require_once('../configuration/image.server.php');

/* Initialize Variables */

$options = array();
$rsync_file = "/temp/mb_file_transfer.txt";
$handle = fopen($rsync_file, "w") or die("Can't open file");

/* Create connection */
$db = connect();
if (PEAR::isError($db)) {
	die("\n" . $this->db->getUserInfo() . "\n");
}

echo date("H:i:s\n");
/* Creates list of original images */

$SQL_search = "SELECT id, accessNum, imageType FROM Image where id not in (select id from MissingImages)"
." order by id";
$results =& $db->query($SQL_search);
if(PEAR::isError($results)){
	die("\n" . $results->getUserInfo() . "\n");	
}

//TODO replace with new code
while($row =& $results->fetchRow()) {
	$id = $row[0];
	$accessNum = $row[1];
	$imageType = $row[2];
	if ($row[0] > 0){
		$imgPath = getImageFilePath($id, $accessNum, $imageType);
		$path = $imgPath;
		$path .= "\n";
		fwrite($handle, $path);
	}
}
fclose($handle);
$db->disconnect();
$rsync_command = "rsync --archive --prune-empty-dirs --files-from=" . $rsync_file . " / root@dev.morphbank.net:/";
exec($rsync_command);
echo date("H:i:s\n");
?>
