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

function getStyleArray ($objectArray, $size, $imgType, $icons) {
	
	$objectId = $objectArray['objectId'];
	//echo "<pre>";var_dump($objectArray);echo "</pre>";die();
	// Try to get an image URl and size for the object
	$thumbId = getObjectImageId($objectId);
	if ($thumbId != null && $thumbId != '') {
		// object has a thumbnail
		$url = getImageUrl($thumbId, $imgType);
	} else {
		// no thumbUrl, use appropriate default
		$url = getDefaultObjectTypeUrl($objectArray['objectTypeId']);
	}
	$returnArray = array('imgUrl' => $url, 'thumbId'=>$thumbId);
	list($fileSize, $width, $height, $imgType) = getRemoteImageSize($thumbId, $imgType);
	if ($size < 100) {
		if ($width >= $height) {
			$returnArray['imgSize'] = ' width="'.$size.'" ';
		} else {
			$returnArray['imgSize'] = ' height="'.($size-5).'" ';
		}

		if ($icons === "false") {
			$liWidth = $size+2;
			$liHeight = $size+35;
			$liStyleString = 'font-size:9px; width: '.$liWidth.'px; height: '.$liHeight.'px; padding-top:1px; ';
		} else {
			$liWidth = $size+20;
			$liHeight = $size+60;
			$liStyleString = 'width: '.$liWidth.'px; height: '.$liHeight.'px; padding-top:1px; ';
		}

		$returnArray['liWidth'] = $liWidth;
		$returnArray['liStyle'] = $liStyleString;

		return $returnArray;
	} else {
		if ($width >= $height) {
			$returnArray['imgSize'] = ' width="'.$size.'" ';
		} else {
			$returnArray['imgSize'] = ' height="'.$size.'" ';
		}

		if ($icons === "false") {
			$liWidth = $size+2;
			$liHeight = $size+35;
			$liStyleString = 'width: '.$liWidth.'px; height: '.$liHeight.'px; ';
		} else {
			$liWidth = $size+25;
			$liHeight = $size+45;;
			$liStyleString = 'width: '.$liWidth.'px; height: '.$liHeight.'px; ';
		}

		$returnArray['liWidth'] = $liWidth;
		$liStyleString .= ' font-size:12px; ';
		$returnArray['liStyle'] = $liStyleString;

		return $returnArray;
	}
}

function showImageTile($i, $row, $imgSize, $imgType, $iconFlag){
	global $config;
	$styleArray = getStyleArray($row, $imgSize, $imgType, $iconFlag);
	$objectId = $row['objectId'];
	$thumbId = $styleArray['thumbId'];
	$objectTypeId= $row['objectTypeId'];
	$title = 'ObjectId:['.$objectId.']';
	$objectTitle = $row['objectTitle'];
	$postItContent = $config->domain . 'ajax/postItSource.php?id='.$objectId
	.'&objectTypeId='.$objectTypeId;

	$url = $config->domain . 'myCollection/editTitle.php?objectId='.$objectId
	.'&amp;collectionId='.$collectionId.'&amp;imgSize='.$imgSize;

	if ($imgSize < 100) {
		if ($iconFlag === "false") $numChars = 12;
		else $numChars = 12;
		$objectTitle = wordwrap($objectTitle, $numChars, "<br />", 1);
	}

	// if the tile is a character state, add the value of the state to the title
	if ($objectTypeId == "CharacterState") {
		$objectTitle .= '('.getStateValue($objectId).')';
	}

	// display image
	echo "\n\n".'<li style="'.$styleArray['liStyle'].'" >';
	echo '<img src="'.$styleArray['imgUrl'].'" class="moveCursor" '.$styleArray['imgSize'];
	echo ' title="" alt="'.$title.'" ondblclick="javascript: openPopup(\'';
	echo bischenPageUrl($thumbId, null).'\', \'true\',\'true\')"';
	echo ' onmouseover="javascript:startPostItSpry( event, \''.$postItContent;
	echo '\');" onmouseout="javascript:stopPostItSpry();" /><br/>'."\n";
	echo '<br />';

	// display icons
	if ($iconFlag === "false") { // if the iconFlag variable is set to boolean FALSE (ex. Dont display icons)
		if ($imgSize < 100) {
			$tableAlign = "left";
		} else {
			$tableAlign = "center";
		}
		echo'<table id="noIcons" cellpadding="0" cellspacing="0" align="'.$tableAlign.'"><tr>';
		echo '<td align="left" valign="top"><input id="inputId'.$i.'" class="pointerCursor" ';
		echo 'type="checkbox" name="object'.$row['objectOrder'].'" value="';
		echo $objectId.'" onclick="javascript: swapColor(this, \'inputId';
		echo $i.'\', false);" /></td>';
		echo '<td align="left" valign="top">'.$objectTitle.'</td></tr>';
		echo'</table>';
	} else { // if iconFlag isn't FALSE or isn't set then display icons
		// Size of the "li", minus the collectionIcon Div(88) divided by 2 gives the proper left to center the icons.
		$iconStyleLeft = round(($styleArray['liWidth'] - 88) / 2); 
 		echo $objectTitle.'<br />';
		
		echo '<div id="iconSet'.$i.'" class="collectionIcons" align="center" style="left:';
		echo $iconStyleLeft.'px;">';

		echo '<input id="inputId'.$i.'" class="pointerCursor" type="checkbox" name="object';
		echo ($i+1).'" value="'.$objectId.'" onclick="javascript: swapColor(this, \'inputId';
		echo $i.'\', true);" />';
		if ($objectTypeId != "CharacterState") {
			echo'<a href="javascript:openTitleEdit(\''.$url.'\')">';
			echo '<img src="';
			echo '/style/webImages/edit-trans.png" width="16" height="16" class="collectionIcon" ';
			echo 'align="top" alt="image" title="Click to Edit Title" /></a>';

			$annotationId = hasAnnotation($objectId);
			//$annotationType = ($objectTypeId == "TaxonConcept") ? "annotateSingleTaxon" : "annotateSingle" ;
			if ($objectTypeId == "TaxonConcept") {
				$annotationUrl =  $config->domain.'Admin/TaxonSearch/annotateTSN.php?tsn=';
			} else {
				$annotationUrl =  $config->domain.'Annotation/index.php?id='  ;
			}
			// if image (or object) has an annotation, display a different icon, that opens up the first annotation.  User can add additional annotations from there.
			if ($annotationId){
				echo '<a href="'.$config->domain.'?id='.$annotationId.'">';
				echo getAnnotateImageTag("Click to Annotate", 'class="collectionIcon" align="top"');
				echo '</a>';
			} else {
				echo'<a href="'.$annotationUrl.$objectId.'" >';
				echo getAnnotateImageTag("Click to Annotate", 'class="collectionIcon"');
				echo '</a>';
			}
			echo showDetailTag($objectId);
		}
		echo '</div>';
	}
	echo '</li>';
}
