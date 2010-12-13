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
