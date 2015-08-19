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
	$message = reorganizeImageFiles ( $id, $fileName, $imageType, $problems, $FILE_SOURCE_DIR, $width, $height );
	echo "$message\n";
	
	if ($imageCount % 1000 == 0) {
		echo "No. images: $imageCount\tlast id: $id\tlast message: $message\t last width $w height $h layers $l\n";
	}
}
echo "\n\nNumber of image objects checked: " . $imageCount . "\n";
echo "\n\nNumber of image files fixed: " . $numFixed . "\n";
function reorganizeImageFiles($id, $fileName, $imageType = null, $problems = null, $fileSourceDir = null, $width = null, $height = null) {
	global $config, $message;
	
	// $returnArray = array('message'=> null, 'width'=>null, 'height'=>null);
	
	$message = "Fixing files  for id: $id fileName: $fileName \n";
	// $imageType = fetchOriginal ($originalImgPath, $fileName, $uin, $imageType, $fileSourceDir);
	
	$imageType = strtolower ( $imageType );
	if ($imageType == "jpg")
		$imageType = "jpeg"; // jpg original stored in jpeg
	
	$originalImgPath = getImageFilePath ( $id, $imageType );
	$message .= "original type $imageType path $originalImgPath\n";
	$fileImageType = getImageFileType ( $originalImgPath, $fileName );
	if (! file_exists ( $originalImgPath ) || empty ( $fileImageType )) {
		// missing or corrupted original file
		$message .= "Skipping id $id: missing or corrupt original file\n";
	} else {
		if (! empty ( $fileImageType )) {
			// get file paths
			$jpegImgPath = getImageFilePath ( $id, "jpeg" );
			$tifImgPath = getImageFilePath ( $id, "tiff" );
			$tpcImgPath = getImageFilePath ( $id, "tpc" );
			$iipImgPath = getImageFilePath ( $id, "iip" );
			
			// remove tiff if not original
			// BIG PROBLEM: when imageType is 'tif', file was removed GR 6/26/2014
			if ($imageType != 'tiff' && file_exists ( $tifImgPath )) {
				$message .= "found unneeded tiff for id $id path $tifImgPath\n";
				
				//unlink ( $tifImgPath );
				//$message .= ": removed tiff";
			}
			
// 			if ($imageType == 'tif' || $imageType == 'tiff') {
// 				// create iip from jpeg if tif original is missing
// 				if (! file_exists ( $originalImgPath )) {
// 					$originalImgPath = getImageFilePath ( $id, "jepg" );
// 				}
// 			}
			
			// create openzoom (iip)
			if (! checkFileDate ( $iipImgPath, $originalImgPath )) {
				// $message .= "No file for path '$iipImgPath'\n";
				if ($imageType == 'dng' || ((($imageType == 'tif' || $imageType == 'tiff') && ! file_exists ( $originalImgPath )))) {
					$sourcePath = $jpegImgPath;
				} else {
					$sourcePath = $originalImgPath;
				}
				$converted = convertIip ( $sourcePath, $iipImgPath );
				if ($converted) {
					$numFixed ++;
					$message .= ": created iip";
				}
			}
			
			// remove tilepic
			if (file_exists ( $tpcImgPath )) {
				// unlink($tpcImgPath);
				$message .= ": removed tpc";
			}
		}
	}
	return $message;
}
?>
