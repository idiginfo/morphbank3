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

$iipOK = viewIipFrame();
if (!$iipOK) {
	// could not show iip frame, use bischen instead
	header("Status: 302 Temporary redirect");
	header("Location: /bischen/viewFrame.php?id=$id");
}

function ViewIipFrame() {
	// session Id from server for use in accessing private images
	$sessionId = $_REQUEST['sessionId'];
	$width = $_REQUEST['width'];
	$height = $_REQUEST['height'];

	$image = new Image($id, 'iip', null, $sessionId);
	// The beginning of HTML
	if (!$image->getAuthorized()===true){
		global $DEFAULT_IMAGES;
		echo '<img src="'.$config->hostServerBaseUrl.'/style/webImages/'.$config->imgPrivate.'"/> ';
		return true;
	}
	$iipFile = $image->getImageFilePath();
	if (!file_exists($iipFile)) return false;// no iip file
	echo iipTag($image, $width, $height);
	return true;
}

function iipTag ($image, $width, $height){
	global $config;
	$imageId = $image->getImageId();
	$sessionId = $image->getSessionId();
	$p = $imageId;// parameter "p" of getTile.php holds both image and session ids
	if (!empty($sessionId)){
		$p.= '+'.$sessionId;
	}
	//get properties from the tile pic file

	$tag ='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'
	.'<html xmlns="http://www.w3.org/1999/xhtml">'
	.'<head>'
	.'<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>'
	.'<script type="text/javascript">'
	.'var server = "/iipzoom/iipsrv.fcgi";'
	.'var image = "'.$image->getImageFilePath().'"'
	.'var credit = "Trying tif file";'
	.'var flashvars = {server: server,image: image,navigation: true,credit: credit}'
	.'var params = {scale: "noscale",bgcolor: "#000000",allowfullscreen: "true",allowscriptaccess: "always"}'
	.'swfobject.embedSWF("IIPZoom.swf", "container", "100%", "100%", "9.0.0","expressInstall.swf", flashvars, params);'
	.'</script>'
	.'<style type="text/css">'
	.'html, body { background-color: #000; height: 100%; overflow: hidden; margin: 0; padding: 0; }'
	.'body { font-family: Helvetica, Arial, sans-serif; font-weight: bold; color: #ccc; }'
	.'#container { width: 100%; height: 100%; text-align: center; }'
	.'</style></head>'
	.'<body><div id="container"></div></body></html>';

	return $tag;
}