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
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

/* ----------------------------------------------------------------------
 * Based on the label representation of the bischen viewer
 *
 * As illustrated by web/inc/search/object_representation_labels.php
 * ----------------------------------------------------------------------
 * CollectiveAccess 0.55
 * Open-source collections management software
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * ----------------------------------------------------------------------
 */
define('PHP_ENTRY',0);// valid Web app entry point

/**
 * Use remote service to create, delete and fetch annotations
 *
 */
//TODO check for POST request?

$id = $_REQUEST["representation_id"];
$method = $_REQUEST["service"];

//if (!checkAuthorization($id,null,null,'annotate')){
//	echo "hello, you can't use this service";
//	exit;
//}
//$pn_user_id = $_REQUEST("user_id", pString);
$properties = getPropertiesFromRequest();
switch($method){
	case 'save':
		$response = saveLabel($id, $properties);
		break;
	case 'delete':
		$response = deleteLabel($id, $properties);
		break;
	case 'list':
	default:
		$response = fetchLabels($id, $properties);
}
echo $response;

function getPropertiesFromRequest(){
	$properties = array();
	$properties['labelId'] = $_REQUEST["label_id"];// don't know what this is for
	$properties['x'] = $_REQUEST["x"];
	$properties['y'] = $_REQUEST["y"];
	$properties['w'] = $_REQUEST["w"];
	$properties['h'] = $_REQUEST["h"];
	$properties['title'] = $_REQUEST["title"];
	$properties['content'] = $_REQUEST["content"];
	$properties['typecode'] = $_REQUEST["typecode"];
	return $properties;
}
function saveLabel($id, $properties){

	$properties = getPropertiesFromRequest();
	//TODO add $_REQUEST["zoom"] or "layer"

	$newAnnotation = createAnnotation($id, $properties);
	//	switch($pn_typecode) {
	//		case 0: // box
	//		case 1: // point
	//		default: //error
	//	}
	if ($newAnnotation['success']===true) {
		$response = "&success=1&label_id=".$newAnnotation['labelId'];
	} else {
		$response = "&error=".urlencode($newAnnotation['message']);
	}
	return $response;
}

function deleteLabel($id, $properties){
	$deleteResponse = deleteAnnotation($id, $properties);
	$response = '';
	if (!$deleteResponse['success']) {
		$response = "&error=".urlencode($deleteResponse['message']);
	}
	return $response;
}

function fetchLabels($id, $properties){
	$annotations = fetchAnnotations($id, $properties);
	$xml = "<labels>\n";
	foreach($annotations as $annotation) {
		$title = $annotation['title'];
		$typeCode = $annotation["typecode"];
		$x = $annotation["x"];
		$y = $annotation["y"];
		$w = $annotation["w"];
		$h = $annotation["h"];
		$typeCode = $annotation['typeCode'];
		$createdOn = $annotation["created_on"];
		$content = $annotation["content"];
		$xml .= "<label typecode=\"$typeCode\" id=\"".$annotation["labelId"]
		."\" title=\"$title\" x=\"$x\" y=\"$y\"";
		if ($typecode == 0)	$xml .= " width=\"$w\" height=\"$h\"";
		$xml .= " createdOn=\"$created_on\">\n";

		$xml .= "<content><![CDATA[$content]]></content>\n";
		$xml .= "<author id=\"".$annotation["user_id"]."\">\n";
		$xml .= "<firstname>".$annotation["fname"]."</firstname>\n";
		$xml .= "<lastname>".$annotation["lname"]."</lastname>\n";
		$xml .= "</author>\n";
		$xml .= "</label>\n";
	}
	$xml .= "</labels>";
	return $xml;
}

function fetchAnnotations($id, $properties) {
	$annotations = array();
	$annotation = array();
	$annotation['title'] = "Yo Joe";
	$annotation["x"] = 100;
	$annotation["y"] = 150;
	$annotation["w"] = 20;
	$annotation["h"] = 25;

	$annotation['user_id'] = "1";
	$annotation['typeCode']="1";
	$annotation["fname"] = "Fredrik";
	$annotation["lname"] = "Ronquist";
	$annotation["created_on"]="";
	$annotation["labelId"] = "1111";
	$annotation['content']="My spot";
	$annotations[]=$annotation;

	return $annotations;
}

function createAnnotation($id) {

}
function deleteAnnotation($id){

}
