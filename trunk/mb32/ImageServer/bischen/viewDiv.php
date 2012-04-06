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

define('PHP_ENTRY',0);// valid Web app entry point


include_once ("image.class.php");
include_once ("bischen/tileserver/TilepicParser.inc");

$id = $_REQUEST['id'];
// session Id from server for use in accessing private images
$sessionId = $_REQUEST['sessionId'];
$width = $_REQUEST['width'];
$height = $_REQUEST['height'];

$helpForViewer = 'http://collection.movingimage.us/help/viewers/bischen.php';

$image = new Image($id, 'tpc', null, $sessionId);
// The beginning of HTML
if ($image->getAuthorized()===true){
	$tpcFile = $image->getImageFilePath();
	echo tilePicTag($image, $width, $height);
} else {
	global $DEFAULT_IMAGES;
	//echo "viewDiv ". htmlentities($image->getAuthorized());
	echo '<img src="'.$config->hostServerBaseUrl.'/style/webImages/'.$config->imgPrivate.'"/> ';
}

function tilePicTag ($image, $width, $height){
	global $config;
  echo $image->getImageFilePath();
	$imageId = $image->getImageId();
	$sessionId = $image->getSessionId();
	$p = $imageId;// parameter "p" of getTile.php holds both image and session ids
	if (!empty($sessionId)){
		$p.= '+'.$sessionId;
	}
	//get properties from the tile pic file

	$properties = getTpcProperties($image->getImageFilePath());
	if (!$properties) {
		echo '<img src="'.$config->hostServerBaseUrl.'/style/webImages/'.$config->imgNotFound.'"/>';
		return;
	}
	// property header looks like:
	//Nwidth/Nheight/Ntile_width/Ntile_height/Ntiles/nlayers/nratio/Nattributes_size/Nattributes
	$initMag = (int)($width/$properties['width']);

	$flashVars = 'tpViewerUrl='.$config->imgServerUrl."bischen/tileserver/getTile.php"
	//.'&tpLabelProcessorURL='.$config->imgServerUrl.'bischen/labels.php'
	.'&tpImageUrl='.$p
	.'&tpWidth='.$properties['width']
	.'&tpHeight='.$properties['height']
	.'&tpInitMagnification='.$initMag
	.'&tpScales='.$properties['layers']
	.'&tpRatio='.$properties['ratio']
	.'&tpTileWidth='.$properties['tile_width']
	.'&tpTileHeight='.$properties['tile_height']
	.'&tpUseLabels=0'
	.'&tpEditLabels=0'
	.'&tpParameterList=representation_id;user_id;objectId;sessionId'
	.'$representation_id='.$p
	.'$objectId='.$id
	.'$sessionId='.$sessionId
	.'&image_id='.$imageId
	.'&user_id='.$userId;
	$tag = '<div style="text-align: center; border-bottom: 1px solid #999999; background-color: #DEDEDE;">'
	.'<div style="float:left; width: 150px;" class="content">&nbsp;</div>'
	.'<div style="float:right; width: 150px;" class="content">&nbsp;</div>'
	.'<a href="#" onClick="OpenWindow(\'../help/viewers/bischen.php\',\'help\',\'resizable=yes,width=760,height=400\')" '
	.' class="content">how to use the viewer</a><br/></div>'
	.'<div style="clear: both; height:1px; font-size:11px;">&nbsp;</div>';
	
	$tag .= "<!-- Bischen tilepic viewer tag for id $imageId -->\n";
	$tag .= "<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" \n";
	$tag .= "		codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0\"\n";
	$tag .= "		width=\"$width\" height=\"$height\" id=\"bischen\" align=\"middle\">\n";
	$tag .= "	<param name=\"allowScriptAccess\" value=\"sameDomain\" />\n";
	$tag .= "	<param name=\"FlashVars\" value=\"$flashVars\" />\n";
	$tag .= "	<param name=\"movie\" value=\"".$config->imgServerUrl."bischen/bischen.swf\" />\n";
	$tag .= "	<param name=\"quality\" value=\"high\" />\n";
	$tag .= "	<param name=\"bgcolor\" value=\"#666666\" />\n";
	$tag .= "	<embed src=\"".$config->imgServerUrl."bischen/bischen.swf\" quality=\"high\" bgcolor=\"#330099\" width=\"$width\"\n\n";
	$tag .= "		height=\"$height\"name=\"bischen\" align=\"middle\" \n";
	$tag .= "	FlashVars=\"$flashVars\" />\n";
	$tag .= "</object>\n";
	return $tag;
}

?>
