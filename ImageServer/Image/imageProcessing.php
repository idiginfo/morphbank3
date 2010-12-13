<?php
//if (!defined('PHP_ENTRY')){ die('Cannot be run directly');}

include_once ('imagepath.inc.php');

/**
 * Fix problems with image files including fetching original and moving to correct location
 * @param $id
 * @param $fileName
 * @param $imageType
 * @param $problems
 * @param $uin
 * @param $fileSourceDir
 * @param $width
 * @param $height
 * @return array(message, width, height)
 */
function fixImageFiles($id, $fileName, $imageType=null, $problems = null, $uin = null,  $fileSourceDir = null,
$width = null, $height = null){
	global $config, $message;

	//$returnArray = array('message'=> null, 'width'=>null, 'height'=>null);

	$message = "Fixing files  for id: $id fileName: $fileName \n";
	//$imageType = fetchOriginal ($originalImgPath, $fileName, $uin, $imageType, $fileSourceDir);

	$imageType = strtolower($imageType);
	if($imageType=="jpg") $imageType = "jpeg"; // jpg original stored in jpeg

	$originalImgPath = getImageFilePath($id, $imageType);
	$message .= ":original type $imageType path $originalImgPath:";
	$fileImageType = getImageFileType($originalImgPath, $fileName);
	if (!file_exists($originalImgPath) || empty($fileImageType)){
		// missing or corrupted original file
		$message .= "Fetching original file  for id: $id \n";
		//get new original file and put it in place
		// Note that if original is jpg or jpeg, this will move the file to jpeg path
		$fileImageType = replaceOriginal ($id, $fileName, $fileName, $fileSourceDir);
		if (getImageFileType($fileName)){
			$message .= "original ";
			$numFixed++;
		} else {
			$message.=": corrupted original";
		}
	}
	if (!empty($fileImageType)) {
		// get file paths
		$jpgImgPath = getImageFilePath($id, "jpg");
		$jpegImgPath = getImageFilePath($id, "jpeg");
		$thumbImgPath = getImageFilePath($id, "thumb");
		$tifImgPath = getImageFilePath($id, "tif");
		$tpcImgPath = getImageFilePath($id, "tpc");

		// create full resolution jpeg
		if ($imageType != 'jpeg' && !checkFileDate($jpegImgPath, $originalImgPath)){
			if ($imageType=="dng") {// convert to raw and use the raw file as input for further processing
				$originalImgPath=convertDng($originalImgPath);
			}

			// Creating full resolution jpeg file for id: $id
			$fixedFile = convertOriginal( $originalImgPath, $jpegImgPath, "");
			if (!$fixedFile){
				$message .= "jpeg ";
				$numFixed++;
			}
		} else {
			// original file is jpg or it's already in place
		}
		// create medium resolution jpg
		if (!checkFileDate($jpgImgPath, $originalImgPath)){ // make Web resolution from jpeg
			$message .= "Creating jpg file for id: $id \n";
			$fixedFile = convertOriginal( $jpegImgPath, $jpgImgPath, $config->jpgSize);
			if (!$fixedFile){
				$message .= "jpg ";
				$numFixed++;
			}
		}
		// create thumb
		if (!checkFileDate($thumbImgPath, $originalImgPath)) { // make thumb from jpeg
			$message .= "Creating thumb file for id: $id \n";
			$fixedFile = convertOriginal( $jpegImgPath, $thumbImgPath, $config->thumbSize);
			if (!$fixedFile){
				$message .= "thumb ";
				$numFixed++;
			}
		}
		// create tilepic
		if (!checkFileDate($tpcImgPath, $originalImgPath)){
			//$message .= "No file for path '$tpcImgPath'\n";
			$converted = convertTpc($id, $jpegImgPath);
			if ($converted){
				$message .= "tpc";
				$numFixed ++;
			}
		}
		// create tif, if required by TIF_PROCESS
		if ($config->processTiff && $imgType != "tif" && !checkFileDate($tifImgPath, $originalImgPath)) { // make tif from original
			$message .= "Creating tif file for id: $id original type: $imageType \n";
			$fixedFile = convertOriginal( $originalImgPath, $tifImgPath, "");
			if (!$fixedFile){
				$message .= "tif ";
				$numFixed++;
			}
		}
		if ($numFixed>0) $message = "Number fixed $numFixed ".$message;
		list($width, $height, $type) = @getimagesize($jpegImgPath);
	}
	//$message .= "Fixed $numFixed files for id: $id file types: $message \n";
	$returnArray= array($message, $width, $height);
	return $returnArray;
}

function checkOriginal($id, $imageType, $replacementFile){

}

/**
 * Move a new file into the original file location
 * Don't move the file if the original is the same as the new file
 *
 * @param $origPath
 * @param $fileAccessPath source of original image
 * @param $fileName name of original image
 * @param $fileSourceDir directory to search for image (eg ftp directory)
 * @return boolean true if file moved to original path
 */
function replaceOriginal ($id, $fileAccessPath, $fileName, $fileSourceDir){
	global $config, $message;
	$fileAccessPath = trim($fileAccessPath);

	// find the new file to be used as original

	if (stripos($fileAccessPath,"http:")===0){// fileName is a URL
		// URL: copy the file to temporary location
		$message .= "URL ";
		$tmpPath = $config->imgTmpDir.mktime();
		copy($fileAccessPath, $tmpPath);
		$fileAccessPath = $tmpPath;
	} else if (!@file_exists($fileAccessPath) && !empty($fileSourceDir)){
		// try to find file in file system
		$fileAccessPath = getFileFromFileSystem($fileAccessPath, $fileSourceDir);
	}

	// get imageType from the file
	$imageType = getImageFileType($fileAccessPath, $fileName);
	if (empty($imageType)){
		$message .= ": corrupted or missing original";
		return false;
	}

	$origType = $imageType;
	if ($origType=='jpg') $origType ='jpeg';

	// get the target location for the new file
	$origPath = getImageFilePath($id, $origType);

	// copy the new file to the location of the original
	$copy = " cp $fileAccessPath $origPath; chmod 644 $origPath";
	$resp = shell_exec($copy);
	if (!empty($tmpPath)) unlink($tmpPath); // get rid of temporary file
	return $imageType;
}

function getFileFromFileSystem($fileAccessPath, $fileSourceDir){
	global $message;
	// try to find file in ftp site
	$escFileName = str_replace(" ", "\ ", $fileAccessPath);
	$escFileName = str_replace("[", "\[", $escFileName);
	$escFileName = str_replace("]", "\]", $escFileName);
	$escFileName = str_replace("'", "\'", $escFileName);
	$escFileName = str_replace("*", "\*", $escFileName);
	$escFileName = str_replace("]?", "\?", $escFileName);
	$find = "find $fileSourceDir -name $escFileName";

	$res = shell_exec ($find);
	if (strlen($res)==0) {
		$message .= "No original file found id: $id file name: $fileAccessPath orig: $origPath\n";
		$message .= "find command:  find $fileSourceDir -name '$fileAccessPath'\n";
		return false;
	}
	$filePath = trim($res);
	$charpos = strpos($filePath,"\n");
	if ($charpos>0){
		$message .= "Ambiguous file name in ftpsite: $res\n";
		return false; // more than one file found in ftpsite
	}
	return $filePath;
}

/**
 * Convert source to target, if target file is not present
 *
 * @param $sourceImgPath
 * @param $targetImgPath
 * @param $imgType
 * @param $size
 * @return message as string
 */
function fixFile($sourceImgPath, $targetImgPath, $imgType, $size = null){
	//TODO check dates of file
	if (!checkFileDate($targetImgPath, $sourceImgPath)){
		if ($imgType == 'tpc'){
			$message = convertTpc($id, $sourceImgPath, $targetImgPath);
		} else {
			$message = convertOriginal( $sourceImgPath, $targetImgPath, $imgType, $size);
		}
	}
	return $message;
}


function convertOriginal($source, $target, $size){
	global $config;
	$convert = $config->imagemagik."convert -compress LZW $size $source $target";
	$message .= date("H:i:s")." Executing: $convert\n";
	$reply = shell_exec($convert);
	if (strlen($reply)>0){
		$message .=  "conversion failed with message: '$reply'<br/>\n)";
	}
	$message .=  date("H:i:s")." Finished\n";
	return $message;
}

/**
 * Convert DNG to raw for input into imageMagick convert
 * @param $source
 * @return string path for new raw file
 */
function convertDng($source){
	global $DCDRAW_COMMAND, $message;
	$rawFile = "/tmp/orig.raw";
	$dcdraw = $DCDRAW_COMMAND . " -c $source > $rawFile";
	$message .=  date("H:i:s")." Executing: $dcdraw\n";
	$reply = shell_exec($dcdraw);
	if (strlen($reply)>0){
		$message .=  "conversion failed with message: '$reply'<br/>\n)";
	}
	$message .= date("H:i:s")." Finished\n";
	return $rawFile;
}

include_once ($config->webPath.'/bischen/makeTpc.php');

function convertTpc($id, $imgSrc = null){
	$success = makeTilePic($id, $imgSrc);
}

// list of file types recognized by getimagesize
$exts = array('tif','jpg','bmp','jpeg','png','gif','tiff');

/**
 * Return image type if the file is a legitimate image file
 * return null otherwise
 *
 * @param $filePath
 */
function getImageFileType($filePath, $fileName = null){
	global $config, $exts;
	if (!file_exists($filePath)) return null;
	$imageInfo = @getimagesize($filePath);
	if ($imageInfo)	{
		$imgCode = $imageInfo[2]; // image type
		return getImageTypeFromCode($imgCode);
	}
	$pathParts = pathinfo($fileName);
	$extension = strtolower($pathParts['extension']);
	if (in_array($extension , $exts)) return null;
	// check for raw type
	$cmd = $config->imagemagik."/dcraw -i -v $filePath 2>&1";
	$result = exec($cmd,$cmdOutput, $returnVar);
	if (empty($cmdOutput[0])){// ok to process, must be raw
		return $extension;
	}
	return null;
}

function getImageTypeFromCode($code){
	if($code == IMAGETYPE_TIFF_II || $code == IMAGETYPE_TIFF_MM) $type = 'tiff';
	else if($code == IMAGETYPE_JPEG) $type = 'jpg';
	else if($code == IMAGETYPE_GIF) $type = 'gif';
	else if($code == IMAGETYPE_BMP) $type = 'bmp';
	else if($code == IMAGETYPE_PNG) $type = 'png';
	return $type;
}

function checkFileDate($filePath, $origFilePath){
	if (!file_exists($filePath)) return false; // missing file
	if (filemtime($filePath)<filemtime($origFilePath)) return false; // old file
	return true;
}

