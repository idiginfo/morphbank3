#-------------------------------------------------------------------------------
# Copyright (c) 2010 Greg Riccardi, Fredrik Ronquist.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Public License v2.0
# which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# 
# Contributors:
#   Fredrik Ronquist - conceptual modeling and interaction design
#   Austin Mast - conceptual modeling and interaction design
#   Greg Riccardi - initial API and implementation
#   Wilfredo Blanco - initial API and implementation
#   Robert Bruhn - initial API and implementation
#   Christopher Cprek - initial API and implementation
#   David Gaitros - initial API and implementation
#   Neelima Jammigumpula - initial API and implementation
#   Karolina Maneva-Jakimoska - initial API and implementation
#   Katja Seltmann - initial API and implementation
#   Stephen Winner - initial API and implementation
#-------------------------------------------------------------------------------
<?php
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

list($id,$sessionId) = split('[ +]',$_REQUEST["representation_id"]);
$method = $_REQUEST["service"];

//$pn_user_id = $_REQUEST("user_id", pString);

switch($method){
	case 'save':
		$response = saveLabel($id, $sessionId);
		break;
	case 'delete':
		$response = deleteLabel($id, $sessionId);
		break;
	default:
		$response = fetchLabels($id, $sessionId);
}
echo $response;

function saveLabel($id, $sessionId){

	$properties = array();
	$properties['labelId'] = $_REQUEST["label_id"];// don't know what this is for
	$properties['x'] = $_REQUEST["x"];
	$properties['y'] = $_REQUEST["y"];
	$properties['w'] = $_REQUEST["w"];
	$properties['h'] = $_REQUEST["h"];
	$properties['title'] = $_REQUEST["title"];
	$properties['content'] = $_REQUEST["content"];
	$properties['typecode'] = $_REQUEST["typecode"];
	//TODO add $_REQUEST["zoom"] or "layer"

	$newAnnotation = createAnnotation($id, $sessionId, $properties);
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

function deleteLabel($id, $sessionId){
	$labelId = $_REQUEST["label_id"];
	$deleteResponse = deleteAnnotation($labelId,$sessionId);
	$response = '';
	if (!$deleteResponse['success']) {
		$response = "&error=".urlencode($deleteResponse['message']);
	}
	return $response;
}

function fetchLabels($id, $sessionId){
	$annotations = fetchAnnotations($id, $sessionId);
	$typeCode = $annotation["typecode"];
	foreach($annotations as $annotation) {
		$title = htmlEntities($annotation['title'], ENT_QUOTES, "UTF-8");
			
		$x = $annotation["x"];
		$y = $annotation["y"];
		$w = $annotation["w"];
		$h = $annotation["h"];
		$typeCode = $annotation['typeCode'];
		$xml .= "<label typecode='$typeCode' id='".$properties["labelId"]."' title='".htmlentities($title, ENT_QUOTES, "UTF-8")."'
							x='$x' y='$y'";
		if ($typecode == 0){
			$xml .= "width='$w' height='$h'";
		}
		$xml .= " createdOn='".$properties["created_on"]."'>\n";

		$xml .= "<content><![CDATA[";
		$xml .= $properties["content"];
		$xml .= "]]></content>\n";
		$xml .= "<author id='".$properties["user_id"]."'>\n";
		$xml .= "<firstname>".$properties["fname"]."</firstname>\n";
		$xml .= "<lastname>".$properties["lname"]."</lastname>\n";
		$xml .= "</author>\n";
		$xml .= "</label>\n";
	}
	$xml .= "</labels>";
	return $xml;
}
# --------------------------------------------------------------------------------------------
?>
