<?php


# ----------------------------------------------------------------------
# tilepic/tile.php
#
# Tilepic tile server. Given a Tilepic image url on the localhost and a tile number,
# will return the tile image
# For application, the 'p' parameter carries the image and session ids
# This getTile.php script has been adapted to expect those parameters
#
# Only works with JPEG tiles; we skip extracting tile mimetype from the Tilepic file
# to save time. If you are using non-JPEG tiles (unlikely, right?) then change the
# Content-type header below.
#
# Created on    20 February 2004
# Last update	23 August 2005
#
# Author	seth@whirl-i-gig.com
# adapted for application usage G Riccardi June 24, 2009
# ----------------------------------------------------------------------

define('PHP_ENTRY',0);// valid Web app entry point

include_once('TilepicParser.inc');
include_once('checkAuthorization.php');
include_once('imageFunctions.php');

$tilePicParser = new TilepicParser();

// parameter 'p' is of the form id+sessionId
$p = $_REQUEST['p'];

list($id, $sessionId) = explode('+', $p);

$tileNumber = $_REQUEST['t'];
$filePath = getImageFilePath($id, 'tpc');
$fileExists = file_exists($filePath);

if (!$fileExists){
	header('Content-type: image/png');
	readfile($config->webPath.'/style/webImages/'.$config->imgNotFound);
	exit;
}

$tilePicParser = new TilePicParser($tpcFile);

// get tile
if (!$tilePicParser->error) {
	header('Content-type: image/jpeg');
	print $tilePicParser->getTileQuickly($filePath, $tileNumber);
	exit;
} else {
	die('Invalid file');
}

