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
$iipOK = viewIipFrame($id);
if (!$iipOK) {
	// could not show iip frame, use bischen instead
	$sessionId = $_REQUEST['sessionId'];
	$width = $_REQUEST['width'];
	$height = $_REQUEST['height'];
	$frameUrl = "/bischen/viewDiv.php?id=$id&width=$width&height=$height";
	if (!empty($sessionId)) $frameUrl .= "&sessionId=$sessionId";
	header("Status: 302 Temporary redirect");
	header("Location: $frameUrl");
}

function ViewIipFrame($id) {
	global $config;
	// session Id from server for use in accessing private images
	$sessionId = $_REQUEST['sessionId'];
	$width = $_REQUEST['width'];
	$height = $_REQUEST['height'];

	$image = new Image($id, 'iip', null, $sessionId);
	// The beginning of HTML
	if (!$image->getAuthorized()===true){
		echo '<img src="'.$config->appServerBaseUrl.'/style/webImages/'.$config->imgPrivate.'"/> ';
		return true;
	}
	$iipFile = $image->getImageFilePath();
	if (!file_exists($iipFile)) {// no iip file
		error_log("no iip file: " . $iipFile);
		return false;
	}
	echo iipTag($image, $width, $height);
	return true;
}

function iipTag ($image, $width, $height){
	global $config;
	$imageId = $image->getImageId();
	$sessionId = $image->getSessionId();
	$imageFilePath = $image->getImageFilePath();
	$iipDir = $config->iipDir;
  $iipFcgi = $_REQUEST['tileUrl'];
  if ($iipFcgi == null) {
    $iipFcgi = $config->iipFcgi;
  }
	$iipSwf = $config->iipSwf;

  $tag = "<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js\"></script>\n"
	."<script type=\"text/javascript\">\n"
	."      var server = \"$iipFcgi\"; \n"
	."      var image = \"$imageFilePath\"; \n"
	."      var credit = \"Showing image $imageId\"; \n"
	."      var flashvars = {server: server,image: image,navigation: true,credit: credit} \n"
	."      var params = {scale: \"noscale\",bgcolor: \"#000000\",allowfullscreen: \"true\",allowscriptaccess: \"always\"} \n"
	."      swfobject.embedSWF(\"$iipSwf\", \"container\", \"100%\", \"100%\", \"9.0.0\",\"$iipDir/expressInstall.swf\", flashvars, params); \n"
	."</script>\n"
	."<style type=\"text/css\">\n"
	."      html, body { background-color: #000; height: 100%; overflow: hidden; margin: 0; padding: 0; }\n"
	."      body { font-family: Helvetica, Arial, sans-serif; font-weight: bold; color: #ccc; }\n"
	."      #container { width: 100%; height: 100%; text-align: center; }\n"
	."</style>  <div id=\"container\"></div>";
  
  if ($config->mb4 === true) {
    return $tag;
  }
  return "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n"
	."<html xmlns=\"http://www.w3.org/1999/xhtml\">\n"
	."<head>\n"
	."</head>\n"
	."<body>"
  .$tag
  ."</body></html>\n";
  

  /*
	$tag ="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n"
	."<html xmlns=\"http://www.w3.org/1999/xhtml\">\n"
	."<head>\n"
	."<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js\"></script>\n"
	."<script type=\"text/javascript\">\n"
	."      var server = \"$iipFcgi\"; \n"
	."      var image = \"$imageFilePath\"; \n"
	."      var credit = \"Showing image $imageId\"; \n"
	."      var flashvars = {server: server,image: image,navigation: true,credit: credit} \n"
	."      var params = {scale: \"noscale\",bgcolor: \"#000000\",allowfullscreen: \"true\",allowscriptaccess: \"always\"} \n"
	."      swfobject.embedSWF(\"$iipSwf\", \"container\", \"100%\", \"100%\", \"9.0.0\",\"$iipDir/expressInstall.swf\", flashvars, params); \n"
	."</script>\n"
	."<style type=\"text/css\">\n"
	."      html, body { background-color: #000; height: 100%; overflow: hidden; margin: 0; padding: 0; }\n"
	."      body { font-family: Helvetica, Arial, sans-serif; font-weight: bold; color: #ccc; }\n"
	."      #container { width: 100%; height: 100%; text-align: center; }\n"
	."</style></head>\n"
	."<body><div id=\"container\"></div></body></html>\n";
*/
	return $tag;
}
