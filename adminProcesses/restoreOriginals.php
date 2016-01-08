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

/**
 * Restore original files from backup
 * Used to move files from originals backup to proper location in image file system
 */

define('PHP_ENTRY', 0);

require_once(dirname(dirname(__FILE__)) . '/configuration/image.server.php');
include_once("imageFunctions.php"); // Located in /ImageServer/Image
include_once("admin.inc.php");
$config->errorRedirect = 0;
$db = connect();

$STATUS_FILE = "/data/scratch/restorestatus.txt";
$BACKUP_IMAGE_PATH_ROOT = "/data/images/originals/";


// OPTIONAL fields

$SELECT_LIMIT = " order by i.id limit 200 ";
$SLEEP_AFTER_MKDIR = 1;
$rows = true;
while ($rows) {
    echo date("H:i:s\n");
    // get the id of the last image processed
    $file = fopen($STATUS_FILE, 'r');
    $lastId = fread($file, 20);
    fclose($file);
    if (empty($lastId)) $lastId = 0;

    $imageSql = "select  i.id,  i.imageType "
        . " from Image i where i.id > $lastId $SELECT_LIMIT ";

    echo "SQL: $imageSql\n";
    $db = connect();
    $result = $db->query($imageSql);
    if (PEAR::isError($result)) {
        echo("Error in Missing SQL query" . $result->getUserInfo() . " $sql\n");
        die();
    }

    $rows = false;
    while (list($id, $imageType) = $result->fetchRow()) {
        $rows = true;
        $lastId = $id;
        $imageCount++;
        // get fields i.id, i.accessNum, i.imageType, m.problems
        $message = restoreOriginalFile($id, $imageType);
        if (!empty($message)) echo "$message\n";
        if ($imageCount % 100 == 0) {
            echo "No. images: $imageCount\tlast id: $id \t last message $message\n";
        }
    }
    // refresh the status file with the last id
    $file = fopen($STATUS_FILE, 'w');
    fwrite($file, $lastId);
    fclose($file);

}

echo "\n\nTotal images checked: " . $imageCount . "\n";

echo date("H:i:s\n");


function restoreOriginalFile($id, $imageType) {

    $message = 'File $id ';

    if ($imageType == "jpg") $imageType = "jpeg"; // jpg original stored in jpeg
    $originalImgPath = getImageFilePath($id, $imageType);
    $backupImgPath = getBackupFilePath($id, $imageType);
    $originalOK = file_exists($originalImgPath);
    $backupOK = file_exists($backupImgPath);
    if (!$backupOK) {
        $message .= ":missing $imageType backup $backupImgPath";
        return $message;
    }
    if (checkFileDate($originalImgPath, $backupImgPath)) {
        $message .= ":original ok no copy";
        return $message;
    }
    $message .= copyFile($backupImgPath, $originalImgPath);
    return $message;
}

/**
 * @param $oldPath
 * @param $newPath
 * @return null|string
 *
 * Copy file and maintain permissions and timestamp
 */
function copyFile($oldPath, $newPath) {
    if (file_exists($newPath) && filemtime($oldPath) >= filemtime($newPath)) {
        $message = "no copy required";
        return null;
        //return $message;
    }
    $copy = "cp -p $oldPath $newPath";
    $res = shell_exec($copy);
    echo "$copy\n";
    $message = "file $oldPath copied to $newPath";
    return $message;
}

/**
 * @param $filePath
 * @param $origFilePath
 * @return bool
 *
 * return true if both files exist and filePath is not older than origFilePath
 */
function checkFileDate($filePath, $origFilePath) {
    if (!file_exists($filePath) || !file_exists($origFilePath))
        return false; // missing file
    if (filemtime($filePath) < filemtime($origFilePath))
        return false; // old file
    return true;
}

?>
