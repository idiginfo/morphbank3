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

include_once ("imageFunctions.php");

/**
 * Return the URL to show the object in its show page
 * @param $id
 * @return unknown_type
 */
function showUrl ($id, $pop = false, $opener = "", $extraUrlField = ""){
	global $config;
	$url = $config->domain . '?id=' . $id . $extraUrlField;
	if ($pop){
		return showPopUrl($url,$opener);
	}
	return $url;
}

function copyCollectionUrl($id, $pop=false){
	global $config;
	$url = $config->domain . 'includes/copyCollection.php?id=' . $objId;
	if ($pop){
		return showPopUrl($url,$opener);
	}
	return $url;
}

/**
 * Return a hyperlink (<a  >) tag for to the show page for the object
 * @param $id
 * @param $imgUrl
 * @param $pop
 * @param $opener
 * @param $extraUrlField
 * @param $target
 * @return unknown_type
 */
function showTag($id, $pop = false, $opener = "", $extraUrlField = "", $target = 'target="imageshow"') {
	$tag = '<a href="';
	$tag .= showUrl($id, $pop, $opener, $extraUrlField);
	if (!$pop) $tag .= '" '.$target;
	$tag .= '>';
	return $tag;
}

function imageTag($url, $attrs){
	$tag = '<img src="'.$url.'"'.$attrs.'/>';
	return $tag;
}


function thumbnailTag($id, $imgUrl = null, $pop = false, $opener = "", $extraUrlField = "",
$target = 'target="imageshow"'){
	if ($imgUrl == null){
		$imgUrl = getObjectImageUrl($id, "thumbs");
	}
	$tag = showTag($id, $pop, $opener, $extraUrlField, $target);
	$tag .= imageTag($imgUrl,'" alt="image thumbnail" border="1" title="Thumbnail"');
	$tag .= '</a>';
	return $tag;
}

function showPopUrl($url, $opener){
	if ($opener=="") return $url;
	$popUrl = "javascript:openPopup('$url&pop=yes').$opener.','830','600')";
	return $popUrl;
}

function showCalendarTag($i, $id){
	
	return 	'<a href="#" onclick="showCalendar(\''.$id.'\', \'dateTest_'.$i.'\', this); return false;">'
	.'<img id="calId'.$i.'" border="0" src="/style/webImages/calendar.gif"'
	. 'width="16" height="16" alt = "Edit date" title="Edit Date" /></a>';
}

function publishNowTag($id, $date, $i, $userId, $groupId){
	global $config;
	$tag = '<a href="#" onclick="updateDate(\''.$config->domain.'MyManager/updateDate.php?id='
	.$id.'&amp;date='.$date.'&amp;spanId=dateTest_'.$i.'&amp;userId='.$userId.'&amp;groupId='
	.$groupId.'\'); return false;" >(Publish Now)</a>';
	return $tag;
}

function showDetailTag($id, $pop = false, $opener = "", $extraUrlField = "",
$target = 'target="imageshow"'){
	
	$tag = '<a href="'.showUrl($id, null, false, $extraUrlField).'" ' .$target .'>'
	.'<img src="/style/webImages/infoIcon-trans.png"'
	.' width="16" height="16" class="collectionIcon" alt="Info"'
	.' title="Click to open image information." /></a>';
	return $tag;
}

function getCharacterEditTag($id){
	global $config;
	$tag = '<a href="'
	.$config->domain.'Phylogenetics/Character/editCharacter.php?id='
	.$id.'"><img src="/style/webImages/edit-trans.png"'
	.' alt="Edit" title="Edit" /></a>';
	return $tag;
}

function getUpdateTitleTag($id, $i, $userId){
	global $config;
	$tag = '<a href="javascript: updateTitle(\''
	.$config->domain.'MyManager/updateTitle.php?id='.$id.'&div=row'.($i+1).'&userId='
	.$userId.'\');" title="Click to edit title" >(edit...)</a>';
	return $tag;
}

function getCalendarTag($id, $i){
	
	$tag = '<a href="#" onclick="showCalendar(\''.$id.'\', \'dateTest_'.$i
	.'\', this); return false;">';
	$tag .= '<img id="calId'.$i.'" border="0" src="/style/webImages/calendar.gif" width="16" height="16" alt = "Edit date" title="Edit Date" />"';
	$tag .= '</a>';
	return $tag;
}

function getDateToPublishTag ($id, $i, $dateToPublish, $userId, $groupId, $now){
	global $config;
	$tag = 'Date to Publish: <span id="dateTest_'.$i
	.'" class="date" title="Click to Change" onclick="showCalendar(\''
	.$id.'\', \'dateTest_'.$i.'\', this);">'.$dateToPublish
	.'</span><input type="hidden" name="date'.$i.'" id="dateField_'.$i.'" />';
	$tag .= '&nbsp;&nbsp;<a href="#" onclick="updateDate(\''.$config->domain
	.'MyManager/updateDate.php?id='.$id.'&amp;date='.$now.'&amp;spanId=dateTest_'
	.$i.'&amp;userId='.$userId.'&amp;groupId='.$groupId
	.'\'); return false;" title="Click to change date to publish to today (now)">(Publish Now)</a>';
	return $tag;
}

function getCameraViewTag(){
	
	$tag = '<img border="0" src="/style/webImages/camera-min16x12.gif" title="Click to view" alt="images" />';
	return $tag;
}

function getInfoImageTag(){
	
	$tag = '<img border="0" src="/style/webImages/infoIcon.png" alt = "Info" '
	. 'title="Click for more details" />';
	return $tag;
}

function getCopyImageTag($title){
	
	$tag = '<img border="0" src="/style/webImages/copy-trans.png" width="16" height="16"'
	.' alt = "Copy" title="'. $title . '" /></a>';
	return $tag;
}

function getEditObjectLink($id, $title = 'Edit', $showTitle = false){
	global $domain;
	$editUrl = $domain."/Edit/?id=".$id;
	$tag = "<a href=\"$editUrl\" target=\"_blank\">"
	.getEditImageTag($title). " " . ($showTitle == true ? $title : '') . "</a>";
	return $tag;
}

function getEditImageTag($title="Edit"){
	
	$tag = '<img border="0" src="/style/webImages/edit-trans.png" width="16" height="16"'
	.' alt = "' . "Edit" . '" title="'.$title.'" />';
	return $tag;
}

function getAnnotateImageTag($title = 'Click to Annotate', $extraAttrs = '') {
	
	$tag = '<img src="/style/webImages/annotate-trans.png" width="16" height="16"  '
	.'align="top" alt="image" title="'.$title.'" '.$extraAttrs.'/>';
	return $tag;
}

$productionServer = "http://morphbank4.scs.fsu.edu/";
$fsiViewerUrl = $productionServer.'Show/imageViewer/?pop=yes&amp;id=';

function fsiViewerShowTag($id){
	global $fsiViewerUrl;
	$tag = '<a href="javascript: openPopup(\''.$fsiViewerUrl.$id.'\')" title="See image">';
	return $tag;
}

function fsiViewerLink($id){
	
	$tag =  fsiViewerShowTag($id);
	$tag .=  '<img src="/style/webImages/magnifyShadow-trans.png" height="16" width="16" alt="Image Viewer" />&nbsp;FSI Viewer</a>';
	return $tag;
}

function mediumImageTag($id, $imgUrl, $width, $height){
	$tag = bischenViewerShowTag($id);
	$tag .= '<img src="'.$imgUrl.'" border="0" width="'.$width.'px" ';
	$tag .= getImageAlt($id);
	$tag .=  '/></a><br />';
	$tag .=  bischenViewerLink($id,null,'View the full image');
	return $tag;
}

function fsiViewerTag($id, $imgUrl, $width, $height){
	$tag = fsiViewerShowTag($id);
	$tag .= '<img src="'.$imgUrl.'" border="0" width="'.$width.'px" ';
	$tag .= getImageAlt($id);
	$tag .=  '/></a><br />';
	$tag .=  fsiViewerLink($id);
	return $tag;
}

function showViewerTag($id){
	global $fsiViewerUrl;
	$tag = '<a href="javascript: openPopup(\'' . $fsiViewerUrl . $id . '&amp;pop=yes\')">';
	return $tag;
}

function showImageViewerTag($id){
	$tag = bischenViewerLink($id);
	return $tag;
}

function showImageViewerThumbnailTag($id, $imgUrl, $imageData){
	$tag = showViewerTag($id)
	.'<img src ="' . $imgUrl . '" width=100 onMouseOver="javascript:startPostIt(event,\''
	. $imageData . '\');" onMouseOut="javascript:stopPostIt();"</A>';
	return $tag;

}
function getImageViewerPostitTag($id, $imgUrl, $width, $height, $postItContents){
	$tag = showViewerTag($id);
	$tag .= '<img id="animage" name="myimage" src ="';
	$tag .= $imageloc . '" alt = "Image not available" height="' . $height . '" width="' . $width;
	$tag .= '"  border = "1" onMouseOver="javascript:startPostIt(event,\'';
	$tag .= $postItContents . '\');" onMouseOut="javascript:stopPostIt();"></a>';
	return $tag;
}

function imageServerTpcFrame($imageId, $width, $height, $sessionId = null){
	global $config;
	$imageTpcTag = $config->imgServerUrl."bischen/viewDiv.php?id=$imageId";
	$imageTpcTag .= "&width=$width&height=$height";
	if (!empty($sessionId)){
		$imageTpcTag .= '&sessionId='.$sessionId;
	}
	$html = "<iframe src=\"$imageTpcTag\" width=\"".($width+20)."\" height=".($height+50)."\">";
	$html .= '<p>Your browser does not support iframes.</p>';
	$html .= imageTag(getImageServerUrl($imageId, $imageType, $sessionId), "&width=$width&height=$height");
	$html .="</iframe>";
	return $html;
}

function getImageServerUrl($objectId, $imageType, $sessionId = null, $imgSize = null){
	global $config;
	//TODO get image id for object
	$imageId = getObjectImageId($objectId);
	$url = $config->imgServerUrl."?id=$imageId&imgType=$imageType";
	if (!empty($sessionId)) {
		$url .= "&sessionId=".$sessionId;
	}
	if (!empty($imgSize)){
		$url .= "&imgSize=".$imgSize;
	}
	return $url;
}


function bischenPageUrl ($id, $sessionId = null){
	global $config;
	$url = $config->appServerBaseUrl.'bischen/?id='.$id;
	if (!empty($sessionId)){
		$url .= '&sessionId='.$sessionId;
	}
	return $url;
}

function bischenAttributionLogo(){
	$tag = '<a href="http://www.collectiveaccess.org/">';
	$tag .= '<img src="http://demo.collectiveaccess.org/graphics/logo.png"/>';
	$tag .='</a>';
	return $tag;
}

function bischenAttributionTag(){
	$html = '<table><tr><td>';
	$html .= bischenAttributionLogo();
	$html .= '</td><td>The Bischen image viewer is used to display high-resolution imagery on Morphbank. ';
	$html .= 'Bischen is a component of CollectiveAccess, community-developed open-source collections ';
	$html .= 'management and presentation software for museums, archives and arts organizations. ';
	$html .= 'For more information, visit ';
	$html .= '<a href"http://www.collectiveaccess.org/">http://www.collectiveaccess.org/</a></td>';
	$html .= '</tr></table>';
	return $html;
}

function bischenViewerShowTag($id, $sessionId = null){
	$tag = '<a href="'.bischenPageUrl($id, $sessionId).'" target="bischen">';
	return $tag;
}

function bischenViewerLink($id, $sessionId = null, $label='', $title ='View this image'){
	
	$imageId = getObjectImageId($id);
	if (empty($imageId)) return "";
	$tag =  bischenViewerShowTag($imageId, $sessionId);
	$tag .=  "<img src=\"/style/webImages/magnifyShadow-trans.png\" height=\"16\" width=\"16\" "
	."alt=\"$title\" title=\"$title\"/>&nbsp;$label</a>";
	return $tag;
}

function human_file_size($size) {
	if ($size == "?") return $size;
	$filesizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
	if ($size) {
		return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2).$filesizename[$i];
	} else {
		return '0'.$filesizename[0];
	}
}

/**
 * Returns '?' when the file is not local. That is when $url starts with "http"
 * @param $url
 * @return representation of size of file
 */
function getFileSizeUrl($id,$imageType) {
	// return unknown for url or missing file
	$sizes = getRemoteImageSize($id,$imageType);
	$fileSize = $sizes[0];
	if (intval($fileSize)) return $fileSize;
	return '?';
}

function showUserGroup($userId, $userName, $groupId, $groupName){
	$db = connect();
	if (empty($userName)){
		$sql = "select name from User where id = $userId";
		$userName = $db->getOne($sql);
	}
	if (empty($groupName)){
		$sql = "select groupName from Groups where id = $groupId";
		$groupName = $db->getOne($sql);
	}
	$html = "User:";
	$html .= showTag($userId).$userName.'</a>';
	$html .= " Group:";
	$html .= showTag($groupId).$groupName.'</a>';
	return $html;
}

/**
 * Make a GET parameter string from $_REQUEST values
 * Passing a parameter in case we wish to change the 
 * opeartion of this function to work with arrays
 * @param $request pass paramter array
 * @return string
 */
function getParamString($request = array()){
	unset($request['PHPSESSID']);
	return http_build_query($request);
}
