<?php
require_once('../configuration/app.server.php');
// Pear class for handling database connection
require_once 'MDB2.php';
require_once('imageFunctions.php');


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
." order by id limit 10";
$results =& $db->query($SQL_search);
if(PEAR::isError($results)){
	die("\n" . $results->getUserInfo() . "\n");	
}

$RSYNC = "rsync --archive ";
$SRC_PATH = $NEW_IMAGE_ROOT_PATH;
$DEST_PATH = "/data/backup/";
//TODO replace with new code
while($row =& $results->fetchRow()) {
	$id = $row[0];
	$accessNum = $row[1];
	$imageType = $row[2];
	if ($row[0] > 0){
		$NEW_IMAGE_ROOT_PATH = $SRC_PATH;
		$srcPath = getImageFilePath($id, $accessNum, $imageType);
		if (!file_exists($srcPath)){
			echo "No source file $id.$imageType trying jpeg";
			$imageType = "jpeg";
			$srcPath = getImageFilePath($id, $accessNum, $imageType);
		}
		if (!file_exists($srcPath)){
		
		$NEW_IMAGE_ROOT_PATH = $DEST_PATH;
		$destPath = getImageFilePath($id, $accessNum, $imageType);
		$cmd = $RSYNC . $srcPath ." ".$destPath."\n";
		fwrite($handle, $cmd);
		} else {
			echo "No $imageType file: $srcPath\n";
		}
	}
}
fclose($handle);
$db->disconnect();
$rsync_command = "bash $rsync_file";
exec($rsync_command);
echo date("H:i:s\n");
?>
