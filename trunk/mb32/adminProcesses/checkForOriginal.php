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
 * Purpose of this script is to reorganize the image file store so that
 * 1.
 * openzoom multi-layered tiff is in place for each image
 * 2. no tile-pic (.tpc) files are present
 * 3. tiff files that are not originals are removed
 *
 * Greg Riccardi May 2, 2012
 */
define ( 'PHP_ENTRY', 0 ); // valid Web app entry point 592155
                           
// TODO Need database configuration and image server
require_once (dirname ( dirname ( __FILE__ ) ) . '/configuration/image.server.php');
include_once ("imageFunctions.php"); // Located in /ImageServer/Image
include_once ('imageProcessing.php'); // Located in ImageServer/Image
require_once ('admin.inc.php');
$config->errorRedirect = 0;

$db = connect ();

/*
 * argv[1] is id to start process from. If empty, get min id from BaseObject where dateCreated > than 1 day ago. Else, use passed in value as id.
 */
if (empty ( $argv [1] )) {
	$sql = "select min(id) as id from BaseObject where dateCreated > date_sub(NOW(), interval 3 day) and objectTypeId = 'Image'";
	$id = $db->queryOne ( $sql );
	if (isMdb2Error ( $result, "Error in min id SQL query", 5 )) {
		die ( "Error in min id SQL query" . $result->getUserInfo () . " $sql\n" );
	}
	if (empty ( $id ))
		die ( "No new BaseObjects created in the last day. Exiting process." );
	$SELECT_LIMIT = " i.id >= $id";
} else {
	$SELECT_LIMIT = $argv [1];
}

$OUTPUT_DIR = null;

/*
 * Optional file source if passed via argv[2]
 */
$FILE_SOURCE_DIR = ! empty ( $argv [2] ) ? $argv [2] . "/" : $config->fileSource;
echo "Looking for files in directory $FILE_SOURCE_DIR\n";

/*
 * query for records
 */
$missingSql = "select b.id, u.uin, i.originalFileName, i.imageType, '', i.imageWidth,
    i.imageHeight, up.value from BaseObject b
    join Image i on b.id = i.id
    join User u on b.userId = u.id
    left join UserProperty up on (b.id = up.objectId and up.name='imageurl')
    where  $SELECT_LIMIT";

echo "SQL: $missingSql\n\n";
$result = $db->query ( $missingSql );
if (isMdb2Error ( $result, "Error in Missing SQL query", 5 )) {
	die ( "Error in Missing SQL query" . $result->getUserInfo () . " $sql\n" );
}

// set up size update statement
$updateSizeSql = "update Image set imagewidth=?, imageheight=? where id=?";
$param_types = array (
		'integer',
		'integer',
		'integer' 
);
$updateSizeStmt = $db->prepare ( $updateSizeSql, $param_types );
isMdb2Error ( $updateSizeStmt, $updateSizeSql );

$imageCount = 0;
while ( $row = $result->fetchRow () ) {
	$imageCount ++;
	// get fields b.id, u.uin, i.originalFileName, i.imageType, m.problems
	list ( $id, $uin, $fileName, $imageType, $problems, $width, $height, $url ) = $row;
	$imageType = strtolower ( $imageType );
	if (! empty ( $url ))
		$fileName = $url;
	$message = checkImageFiles ( $id, $fileName, $imageType, $problems, $FILE_SOURCE_DIR, $width, $height );
	echo "$message\n";
	
	if ($imageCount % 1000 == 0) {
		echo "No. images: $imageCount\tlast id: $id\tlast message: $message\t last width $w height $h layers $l\n";
	}
}
echo "\n\nNumber of image objects checked: " . $imageCount . "\n";
echo "\n\nNumber of image files fixed: " . $numFixed . "\n";
function checkImageFiles($id, $fileName, $imageType = null, $problems = null, $fileSourceDir = null, $width = null, $height = null) {
	global $config, $message;
	
	// $returnArray = array('message'=> null, 'width'=>null, 'height'=>null);
	
	$message = "Checking files  for id: $id ";
	// $imageType = fetchOriginal ($originalImgPath, $fileName, $uin, $imageType, $fileSourceDir);
	
	$imageType = strtolower ( $imageType );
	if ($imageType == "jpg")
		$imageType = "jpeg"; // jpg original stored in jpeg
	
	$originalImgPath = getImageFilePath ( $id, $imageType );
	
	$fileImageType = getImageFileType ( $originalImgPath, $fileName );
	
	if (! file_exists ( $originalImgPath )) {
		// missing or corrupted original file
		$message .= "original file missing $imageType path $originalImgPath";
	}
	
	if (empty ( $fileImageType )) {
		$message .= "original file corrupted $imageType path $originalImgPath";
	}
	
	return $message;
}
?>
