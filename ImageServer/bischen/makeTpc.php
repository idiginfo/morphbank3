<?php
include_once ($config->webPath."/bischen/tileserver/TilepicParser.inc");
include_once ('imagepath.inc.php');

$tilepicParser = new TilepicParser();

function makeTilePic($id, $imgSrc = null){
	global $tilepicParser, $message;
	if (empty($imgSrc)) {
		$imgSrc = getImageFilePath($id, "jpeg");
	}
	$message .= "makeTilePic for $id path $imgSrc\n";
	if (! file_exists($imgSrc)) return false;

	$tpcPath = getImageFilePath($id, 'tpc');
	//$message .=  "calling encode for id $id and file $imgSrc putting result in  $tpcPath\n";
	$success = $tilepicParser -> encode($imgSrc, $tpcPath, $options);
	if (!$success){
		$message .= "parser error ".$tilepicParser->error."\n";
	}
	return $success;
}

?>
