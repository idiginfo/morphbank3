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

include ('coordValue.php');
include_once ('urlFunctions.inc.php');

function getBaseObjectData ($id) {
	if (empty($id)) return false;
	$link = adminLogin();
	$sql = 'select date_format(b.dateCreated, \'%Y-%m-%d\') as dateCreated '
	.', date_format(b.dateLastModified, \'%Y-%m-%d\') as dateLastModified'
	.', date_format(b.dateToPublish, \'%Y-%m-%d\') as dateToPublish'
	.', b.id as id, b.description, b.userId as userId, b.groupId as groupId'
	.', u.name as contributor, u.id  as contributorId, u.email as contributorEmail'
	.', g.groupName as groupName'
	.', s.name  as submitter, s.id as submitterId'
	.', s.email as submitterEmail'
	.', b.objecttypeid as objecttypeid'
	.' from BaseObject b join User u ON b.userId = u.id  '
	.' join User s on s.id = b.submittedby join Groups g on b.groupId=g.id'
	.' where b.id = '.$id.' ';

	$result = mysqli_query($link, $sql) or die(mysql_error($link));
	if ($result) {
		$total = mysqli_num_rows($result);
		if ($total = 1) {
			$array = mysqli_fetch_array($result);
			return $array;
		}
	}
	return false;
}

$FULL_IMAGE_WIDTH = 400;//TODO move to config file

/**
 * Create HTML tag to display an image in medium size
 * used in Show pages
 * @param $id
 * @param $imageWidth
 * @param $imageHeight
 * @return unknown_type
 */
function showMediumImage($id, $imageWidth = null, $imageHeight = null, $sessionId = null){
	global $FULL_IMAGE_WIDTH, $defaultImage, $config;
	if (empty($id)){
		// no image
		return "<img src=\"/style/webImages/" . $config->notFoundImg."\"/>";
	}
	if (empty($sessionId)){
		$sessionId = session_id();
	}
	if (empty($imageWidth) ){// get info from database
		$sql = "select imageWidth, imageHeight from Image where id=$id";
		$db = connect();
		$result = $db->query($sql);
		if (Pear::isError($result)){
			//??
		}
		list ($imageWidth, $imageHeight) = $result->fetchRow();
	}
	if ($imageWidth > $FULL_IMAGE_WIDTH) {// shrink to fit
		$ratio = $imageWidth / $FULL_IMAGE_WIDTH;
		$width = $FULL_IMAGE_WIDTH;
		$height = (int)($imageHeight / $ratio);
	} else {
		$width = $imageWidth;
		$height = $imageHeight;
	}

	if (!isset($config->useSimpleDisplay) || $config->useSimpleDisplay) {
		$tag = mediumImageTag($id, getObjectImageUrl($id,'jpg'), $FULL_IMAGE_WIDTH, $height);
	} else {
		$tag = imageServerTpcFrame($id, $width, $height, $sessionId);
		$tag .= '<br/>'.bischenViewerLink($id, $sessionId, 'View the full image');
		//$tag .= fsiViewerLink($id);
	}
	return $tag;
}

function showBaseObjectData($baseObjectArray) {
	global $id, $popUrl, $objInfo, $config;
	$editOk = checkAuthorization($id,null,null,'edit');
	$objectType = $baseObjectArray['objecttypeid'];
	if ($editOk) {
		echo '<p>'.getEditObjectLink($id, "Click to edit this ".$objectType, true).'</p>';
	}
	echo '<table id="showBaseObject" border="0" width="100%">'
	.'<tr><th>Contributor:</th><td colspan="2">'
	.'<a href="'.$popUrl.$baseObjectArray['contributorId'].'" >'.$baseObjectArray['contributor'].'</a>'
	.'<a href="mailto:'.$baseObjectArray['contributorEmail'].'">'
	.'<img src="/style/webImages/envelope.gif" border="0" alt="email" /></a>'
	.'</td></tr>';
	if ($baseObjectArray['submitter'] != NULL){
		echo'<tr><th>Submitter:</th><td colspan="2">'
		.'<a href="'.$popUrl.$baseObjectArray['submitterId'].'" >'.$baseObjectArray['submitter'].'</a>'
		.'<a href="mailto:'.$baseObjectArray['submitterEmail'].'">'
		.'<img src="/style/webImages/envelope.gif" border="0" alt="email" /></a>';
		echo '</td></tr>';
	}
	echo'<tr><th>Group:</th><td colspan="2">'
	.'<a href="'.$popUrl.$baseObjectArray['groupId'].'" >'.$baseObjectArray['groupName'].'</a>';
	echo'</td></tr>';
	echo '<tr><th>Date Submitted:</th><td>'.$baseObjectArray['dateCreated'].'</td>';
	echo '<td rowspan="3">';
	echo'</td></tr><tr><th>Last Modified:</th><td>'
	.$baseObjectArray['dateLastModified'].'</td>'
	.'<td rowspan = "3">' . showUserLogo($baseObjectArray['contributorId'])
	.'</tr><tr><th>Publish Date:</th><td>'.$baseObjectArray['dateToPublish'].'</td></tr>';

	$description = $baseObjectArray['description'];
	if (!empty($description)) {
		echo '<tr><th>Description:</th><td>'.$description.'</td></tr>';
	}
	showUserDefinedInfo($id);
	if ($editOk) {
		echo "<tr><th>Edit $objectType:</th><td>" . getEditObjectLink($id);
	}
	echo '</table>';
}

function showUserLogo($userId){
	global $id, $popUrl, $objInfo, $config;

	$userLogoArray = getUserLogo($userId);
	if ( $userLogoArray ) {
		//var_dump($userLogoArray);
		$userLogo = $config->domain . '/images/userLogos/'.str_replace(' ', '%20', $userLogoArray['userLogo']);
		$logoUrl = $userLogoArray['logoURL'];
		echo '<div class="logothumbnail"><a href="'.$logoUrl.'"><img src="'
		.$userLogo.'" alt="logo" /></a></div>';
	} else {
		echo '&nbsp;';
	}
}

function showUserDefinedInfo($id) {
	global $link;
	$sql = 'select * from UserProperty where objectId ='.$id;
	$results = mysqli_query($link, $sql);
	while ($array = mysqli_fetch_assoc($results)) {
		echo '<tr>';
		echo '<th>'.$array['name'].': </th><td>'. $array['value'].'</td>';
		//mb_convert_encoding($array['value'],"HTML-ENTITIES","UTF-8")
		//htmlentities($array['value'], ENT_QUOTES, "UTF-8")
		echo '</tr>';
	}
}

function getUserLogo($userId ) {
	global $objInfo, $link;
	if (empty($userId)) return false;
	$sql = 'select userLogo, logoURL from User where id='.$userId;
	$result = mysqli_query($link, $sql);
	if ($result) {
		$array = mysqli_fetch_array($result);
		if ($array[0] != NULL)
		return $array;
	}
}

function showImageAnnotations($imgId, $image = false, $tsn = false) {
	global $link, $config, $id, $config;
	global $popUrl, $objInfo;
	echo '<h3>Other Annotations</h3>';
	if ($image) {
		$url = 'javascript: openPopup(\''.$config->domain.'Annotation/?id='.$id.'&amp;type=General&amp;pop=yes\')';
		echo '&nbsp;&nbsp;<a href="'.$url.'">&nbsp;<img src="/style/webImages/annotate-trans.png" alt="annotate" title="click to annotate this image" />(Add Annotation...)</a>';
	}
	if ($tsn){
		$url= $config->domain . 'Admin/TaxonSearch/annotateTSN.php?tsn='.$tsn.'&pop=yes';
		echo '&nbsp;&nbsp;<a href="'.$url.'">&nbsp;<img src="/style/webImages/annotate-trans.png" alt="annotate" title="click to annotate this image" />(Add Annotation...)</a>';
	}
	echo '<br/><br/>';
	$sql = 'Select Annotation.*, date_format(BaseObject.dateCreated, "%m-%d-%Y") as dateCreated, User.name as userName '
	.'From Annotation '
	.'left join BaseObject on Annotation.id = BaseObject.id '
	.'left join User on BaseObject.userId = User.id  '
	.'Where objectId="'.$imgId.'" and typeAnnotation <> "Determination";';
	$total = mysqli_fetch_array( mysqli_query($link, $sql));

	$result = mysqli_query($link, $sql);
	if (!$result) {
		echo 'NO External links were found<br/><br/><br/><br/>';
		return;
	}
	$color = array($config->lightListingColor, $config->lightBackground);
	$i=0;
	echo '<div class="overflowContainer">';
	while ($row = mysqli_fetch_array($result)) {
		$colorIndex = $i%2;
		echo '<div class="imagethumbnail" width="90%">'
		.'<table height="50px" bgcolor="'.$color[$colorIndex].'" border="0" with="90%">'
		.'<tr><td width="225px"><strong>Title: </strong>'.$row['title'].'<br/>'
		.'<strong>Type: </strong>'.$row['typeAnnotation'].'</td>'
		.'<td width="125px"><strong>Date: </strong>'.$row['dateCreated'].'<br/>'
		.'<strong>User: </strong>'.$row['userName'].'</td>'
		.'<td width="16px"valign="middle"><a href="'.$popUrl.$row['id'].'">'
		.'<img border="0" src="/style/webImages/infoIcon.png" alt = "Info" title="Click for more details" />'
		.'</a></td></tr></table></div>';
		$i++;
	}
	echo '</div>';
}

function showExternalLinks($id = NULL) {
	global $link, $config;
	global $objectType;

	echo '<h3>External links/identifiers</h3><br/><br/>';
	if (!$id) {
		echo 'NO External links were found<br/><br/><br/><br/>';
		return;
	}
	$specimenId = 1;
	if (strtoupper($objectType) == strtoupper('Image')) {
		$sql = 'select specimenId from Image where id = '.$id;
		$result = mysqli_query($link, $sql) or die(mysqli_error($link));
		if($result) {
			$array = mysqli_fetch_array($result);
			$specimenId = $array['specimenId'];
		}
	}

	$sql = 'select distinct Label, urlData, ExternalLinkType.name as linkTypeName, ExternalLinkObject.description as description, ExternalLinkObject.externalId '
	.', mbId from ExternalLinkObject '
	.'left join ExternalLinkType on ExternalLinkObject.extLinkTypeId = ExternalLinkType.linkTypeId '
	.'where (mbId ='.$id.' OR mbId = '.$specimenId.') '
	.'order by linkTypeName ';
	//echo $sql;

	$result = mysqli_query($link, $sql);
	if (!$result) {
		echo 'NO External links were found<br/><br/><br/><br/>';
		return;
	}
	$color[0] = $config->lightListingColor;
	$color[1] = $config->lightBackground;
	$i=0;
	echo '<div class="overflowContainer">';
	$linkTypeOld = '-1'; //firts time and different
	while ( $row = mysqli_fetch_array($result)) {
		$colorIndex = $i%2;
		if ($linkTypeOld != $row['linkTypeName']) { // NEW TYPE
			if ($linkTypeOld != '-1') { // no first time
				echo '</table> </div>'; // closing the group
			}
			echo '<div class="imagethumbnail" width="90%">
				<table height="50px" bgcolor="'.$color[$colorIndex].'" border="0" with="90%">
					<td><strong>'.$row['linkTypeName'].':</strong></td>';
			$linkTypeOld = $row['linkTypeName'];
			$i++;
		}
		if (substr_count($row['urlData'], "http://") != 1) {
			$url = 'http://'.$row['urlData'];
		} else {
			$url = $row['urlData'];
		}
		echo '<tr><td>';
		if (empty($row['externalId'])) {
			echo '<a href="'.$url.'" title="'.$row['description'].'">'.$row['Label'].'</a>';
		} else {
			if ($row['mbId']==$specimenId) echo "Specimen $specimenId  ";
			echo $row['description'] . ': ' . $row['externalId'];
		}
		echo '</td></tr>';
	}
	// close the last one
	if ($linkTypeOld != '-1') echo '</table></div></div>';
}

function showTsnData ($tsnId) {
	global $link;
	global  $config, $browseByTaxonHref, $browseByNameHref;

	$taxonRankIdToDisplay[10] = array ('name' => 'Kingdom', 'display' => TRUE);
	$taxonRankIdToDisplay[20] = array ('name' => 'Subkingdom', 'display' => false);
	$taxonRankIdToDisplay[30] = array ('name' => 'Phylum', 'display' => TRUE);
	$taxonRankIdToDisplay[40] = array ('name' => 'Subphylum', 'display' => false);
	$taxonRankIdToDisplay[50] = array ('name' => 'Superclass', 'display' => false);
	$taxonRankIdToDisplay[60] = array ('name' => 'Class', 'display' => TRUE);
	$taxonRankIdToDisplay[70] = array ('name' => 'Subclass', 'display' => false);
	$taxonRankIdToDisplay[80] = array ('name' => 'Infraclass', 'display' => false);
	$taxonRankIdToDisplay[90] = array ('name' => 'Superorder', 'display' => false);
	$taxonRankIdToDisplay[100] = array ('name' => 'Order', 'display' => TRUE);
	$taxonRankIdToDisplay[110] = array ('name' => 'Suborder', 'display' => false);
	$taxonRankIdToDisplay[120] = array ('name' => 'Infraorder', 'display' => false);
	$taxonRankIdToDisplay[130] = array ('name' => 'Superfamily', 'display' => false);
	$taxonRankIdToDisplay[140] = array ('name' => 'Family', 'display' => TRUE);
	$taxonRankIdToDisplay[150] = array ('name' => 'Subfamily', 'display' => false);
	$taxonRankIdToDisplay[160] = array ('name' => 'Tribe', 'display' => false);
	$taxonRankIdToDisplay[170] = array ('name' => 'Subtribe', 'display' => false);
	$taxonRankIdToDisplay[180] = array ('name' => 'Genus', 'display' => TRUE);
	$taxonRankIdToDisplay[190] = array ('name' => 'Subgenus', 'display' => false);
	$taxonRankIdToDisplay[220] = array ('name' => 'Species', 'display' => TRUE);
	$taxonRankIdToDisplay[230] = array ('name' => 'Subspecies', 'display' => TRUE);

	// Applies to plants only
	$taxonRankIdToDisplay[240] = array ('name' => 'Variety', 'display' => TRUE);
	$taxonRankIdToDisplay[250] = array ('name' => 'Subvariety', 'display' => TRUE);
	$taxonRankIdToDisplay[260] = array ('name' => 'Form', 'display' => TRUE);
	$taxonRankIdToDisplay[270] = array ('name' => 'Subform', 'display' => TRUE);
	//echo '<h3>Taxonomy</h3><br/>';
	$arrayOfParents = getTaxonBranchArray($tsnId);
	if (!$arrayOfParents) {
		echo '<div class="error">Taxonomic Id not found, please contact the administration group</div>';
		return;
	}
	$arraySize = count($arrayOfParents);
	echo '<table>';
	for($i=0; $i < $arraySize; $i++) { // skip life node
		if ($taxonRankIdToDisplay[$arrayOfParents[$i]['rank_id']]['display']) {
			// this was the way to open it to browse by name.
			//$hrefTsnName = isset($_GET['pop'])?('javascript:loadInOpener(\''.$browseByNameHref.'#letter'.$arrayOfParents[$i]['name'][0].'\')')
			//					:($browseByNameHref.'#letter'.$arrayOfParents[$i]['name'][0]);
			$hrefTsnName = isset($_GET['pop'])?('javascript:loadInOpener(\''.$config->domain.'Browse/ByImage/?tsn='.$arrayOfParents[$i]['tsn'].'\')')
			:($config->domain.'Browse/ByImage/?tsn='.$arrayOfParents[$i]['tsn']);

			$hrefTsnTree = isset($_GET['pop'])?('javascript:loadInOpener(\''.$browseByTaxonHref.'?tsn='.$arrayOfParents[$i]['tsn'].'\')')
			:($browseByTaxonHref.'?tsn='.$arrayOfParents[$i]['tsn']);

			echo '<tr><th>'.$taxonRankIdToDisplay[$arrayOfParents[$i]['rank_id']]['name'].':</th>'
			.'<td valign="bottom" align="left">'
			.'<a href="'.$hrefTsnName.'">'.$arrayOfParents[$i]['name'].'</a>&nbsp;&nbsp;'
			.'<a href="'.$hrefTsnTree.'"><img border=0 src="/style/webImages/hierarchryIcon.png"  align="top" title="See hierarchy tree"></a>'
			.'</td></tr>';
		}
	}
	echo '</table>';
}

function  showDeterminationAnnotation($specimenId, $autoHeight = false, $image = false) {
	global $link, $id, $config, $config;
	global $popUrl, $objInfo;
	$height = $autoHeight ? ' style="height:200px;" ' : '';
	echo '<h3>Determination annotations</h3>';
	if (!empty($image)) {
		$url = 'javascript: openPopup(\''.$config->domain.'Annotation/?id='.$id.'&amp;type=Determination&amp;pop=yes\')';
		echo '&nbsp;&nbsp;<a href="'.$url.'">&nbsp;<img src="/style/webImages/annotate-trans.png" alt="annotate" title="click to annotate this image" />(Add Annotation...)</a>';
	}
	echo '<br/><br/>';

	$sql = 'select Annotation.id as id, Annotation.title as title,
				date_format(BaseObject.dateCreated, "%m-%d-%Y") as dateCreated, 
				sourceOfId, rankName, suffix, prefix, tsnId, typeDetAnnotation as AorD '
				.'from DeterminationAnnotation '
				.'left join Annotation on Annotation.id = DeterminationAnnotation.annotationId '
				.'left join BaseObject on Annotation.id = BaseObject.id '
				.'where DeterminationAnnotation.specimenId=\''.$specimenId.'\' and ( BaseObject.dateToPublish < now() ';
					
				if ($objInfo->getUserId() != NULL)
				$sql .= ' OR BaseObject.userId = \''.$objInfo->getUserId().'\' ';
				if ($objInfo->getUserGroupId() != NULL)
				$sql .= ' OR BaseObject.groupId = \''.$objInfo->getUserGroupId().'\' ';

				$sql .= ')';

				//echo $sql;

				$result = mysqli_query($link, $sql);
				if (!$result) {
					echo 'NO Determination annotation were found<br/><br/><br/><br/>';
					return;
				}
				$color[0] = $config->lightListingColor;
				$color[1] = $config->lightBackground;
				$i=0;
				echo '<div class="overflowContainer" '.$height.'>';
				while ( $row = mysqli_fetch_array($result)) {
					$colorIndex = $i%2;
					$taxonName = getScientificName($row['tsnId']);
					
					if ($row['prefix'] != 'none') {
						$rank_id = getRankId($row['tsnId']);
						$parentTsn = getParentTsn($row['tsnId']);
						$parentName = getScientificName($parentTsn);
						$taxonName = annotateAddPrefix($taxonName, $rank_id, $parentName, $parentTsn, $row['prefix']);
					}										
					
					$typeDetAnnotation = ($row['AorD'] == "agree") ? '' : '<b>Not</b>';

					echo '<div class="imagethumbnail" width="90%">
							<table height="50px" bgcolor="'.$color[$colorIndex].'" border="0" with="90%">
								<tr>
									<td width="50%"><strong>Taxon: </strong>'.$typeDetAnnotation.' '.$taxonName.'</td>
									<td>
										<strong>Date: </strong>'.$row['dateCreated'].'<br/>
										<strong>User: </strong>'.$row['sourceOfId'].'</td>
									<td width="30px" valign="middle" align="left">
										<a href="'.$popUrl.$row['id'].'">
											<img border="0" src="/style/webImages/infoIcon.png" alt = "Info" title="Click for more details" />
										</a>
									</td>
								</tr>
							</table>
						</div>'; 
					$i++;
				}
				echo '</div>';
}

function checkForExtLinks($id) {
	global $link;

	$sql = 'select * from ExternalLinkObject where mbId =\''.$id.'\'';

	$results = mysqli_query($link, $sql) or die(mysqli_error($link));

	if ($results) {
		$num = mysqli_num_rows($results);
		if ($num >= 1)
		return TRUE;
		else
		return false;

	} else
	return false;

}

function getRelatedObjects($id) {
	global $link;
	$sql = 'select * from CollectionObjects where collectionId = '.$id;
	$results = mysqli_query($link, $sql);
	if ($results) {
		$num = mysqli_num_rows($results);
		if ($num == 0)	return false;
		for ($i=0; $i < $num; $i++) {
			$resultArray[$i] = mysqli_fetch_array($results);
		}
		return $resultArray;
	}
	return false;
}

function showRelatedObjects($id) {
	global $popUrl, $config;

	$relatedObjectArray = getRelatedObjects($id); 
	if ($relatedObjectArray) {
		$objectCount = count($relatedObjectArray);
		$thumbLimit = ($objectCount < 25) ? $objectCount : 25;
		echo '<table class="bottomBlueBorder" width="100%" cellspacing="0" cellpadding="0">
			<tr><td valign="bottom">
			<h3>Related Objects for object: '.$id.'</h3><br/><br/>';
		echo 'Showing '.$thumbLimit.' of '.$objectCount.' Objects: ';
		echo'<ul id="boxes">';
		$largestHeight = 0;

		for ($i = 0; $i < $thumbLimit; $i++) {
			$imgId = getObjectImageId($relatedObjectArray[$i]['objectId']);
			$thumbUrl[$i] = getImageUrl($imgId, 'thumbs');
			$size = getSafeImageSize('/style/webImages/defaultSpecimenThumb.png');
			$largestHeight = ($size[1] > $largestHeight) ? $size[1] : $largestHeight;
		}

		$styleHeight = 'height:'.($largestHeight+30).'px;';
		for ($i = 0; $i < $thumbLimit; $i++) {
			echo '<li style="'.$styleHeight.' padding:5px;">
										<a href="'.$popUrl.$relatedObjectArray[$i]['objectId'].'" >';
			echo '<img src="'.$thumbUrl[$i].'" alt="thumb" title="Thumbnail" /></a><br/>';
			echo $relatedObjectArray[$i]['objectTypeId'];
			echo '<br/>'.$relatedObjectArray[$i]['objectRole'].'</li>';
		}
		echo '</ul></div></td></tr></table>';
	}
}

function getLocalityData($localityId) {
	$link = adminLogin();
	$sql = 'select Locality.*, '.'Locality.country as countryCode, '
	.'Locality.continentOcean as continentOceanCode '.'from Locality '
	.'where Locality.id = '.$localityId;
	//echo $sql;
	$result = mysqli_query($link, $sql);
	if (!$result) return false;
	$localityRecord = mysqli_fetch_array($result);
	mysqli_free_result($result);
	return $localityRecord;
}

function showLocality($localityId){
	global $popUrl;

	echo '<h3>Locality</h3>&nbsp;';
	$localityTableFields = array(
	array('field' => 'id', 'label' => 'Locality Id: ', 'width' => 10, 'display' => true),
	array('field' => 'continentOceanCode', 'relatedTable' => true, 'label' => 'Continent ocean: ',
		'width' => 10, 'display' => true), 
	array('field' => 'countryCode', 'relatedTable' => true, 'label' => 'Country: ', 'width' => 10,
		'display' => true), 
	array('field' => 'locality', 'label' => 'Locality: ', 'width' => 10, 'display' => true),
	array('field3' => 'latitude', 'field4' => 'Latitude: ', 'width' => 10, 'display' => true),
	array('field3' => 'longitude', 'field4' => 'Longitude: ', 'width' => 10, 'display' => true),
	array('field' => 'coordinatePrecision', 'label' => 'Coordinate precision: ', 'width' => 30,
		'display' => false), 
	array('field' => 'minimumElevation', 'label' => 'Min. elevation: ', 'width' => 20, 'display' => false),
	array('field' => 'maximumElevation', 'label' => 'Max. elevation: ', 'width' => 20, 'display' => false),
	array('field' => 'minimumDepth', 'label' => 'Minimum depth: ', 'width' => 30, 'display' => false),
	array('field' => 'maximumDepth', 'label' => 'Maximum depth: ', 'width' => 30, 'display' => false)
	);
	if(!empty($localityId)){
		echo getEditObjectLink($localityId, "Edit this locality", true );
	}
	echo '<br/>';
	$localityRecord = getLocalityData($localityId);

	$arraySize = count($localityTableFields);
	echo '<table width="100%" border="0">';
	for ($i = 0; $i < $arraySize; $i++) {
		if ($localityTableFields[$i]['display']) {
			echo '<tr><th>'.$localityTableFields[$i]['label'].'</th>
		<td align="left" width="65%">';
			$formatOutput = false;
			// formating ids like [id] - name
			if ($localityRecord[$localityTableFields[$i]['relatedTable']]) {
				$formatOutput = true;
				echo '['.$localityRecord[$localityTableFields[$i]['field']].'] - '
				.$localityRecord[substr($localityTableFields[$i]['field'], 0, -4)];
			}
			// formating boolean fields (Yes or No)
			if ($localityRecord[$localityTableFields[$i]['boolean']]) {
				$formatOutput = true;
				if ($localityRecord[$localityTableFields[$i]['field']] == 1)
				echo 'YES';
				else
				echo 'NO';
			}
			// Normal string values
			if (!$formatOutput) {
				if ($localityTableFields[$i]['field'] == 'id') {
					echo '<a href="'.$popUrl.$localityId.'">'
					.$localityRecord[$localityTableFields[$i]['field']].'</a>';
				} else {
					echo $localityRecord[$localityTableFields[$i]['field']];
				}
			}
			echo '</td></tr>';
		}
	}
	//lat/long
	echo '<tr><th><strong> Latitude:</strong></th>
		<td align="left" width="65%">'.truncateValue($localityRecord[$localityTableFields[4]['field3']]);
	echo "</td></tr>\n";
	echo '<tr><th><strong> Longitude:</strong></th>
		<td align="left" width="65%">'.truncateValue($localityRecord[$localityTableFields[5]['field3']]);
	echo "</td></tr>\n";
	// Elevation
	echo '<tr><th><strong> Elevation (m):</strong></th>
		<td align="left" width="65%">'.$localityRecord[$localityTableFields[7]['field']];
	if (($localityRecord[$localityTableFields[8]['field']] != $localityRecord[$localityTableFields[7]['field']])
	&& ($localityRecord[$localityTableFields[8]['field']] != null))
	echo '-'.$localityRecord[$localityTableFields[8]['field']];
	echo "</td></tr>\n</table>";
}

/**
 * returns the date an object was created
 * @param $id
 * @return unknown_type
 */
function whenCreated ($id){
	global $link;
	$sql = 'select date_format(dateCreated, "%Y-%m-%d") from BaseObject where Id = '.$id;
	//echo $sql;
	$result = mysqli_query($link, $sql) or die(mysqli_error());
	$count = mysqli_fetch_row($result);
	return $count[0];

}

function getNonAuthDiv($code) {
	$msg = '<div class="searchError">';
	$msg .= getNonAuthMessage($code);
	$msg .= '</div>';
	return $msg;
}

/**
 * Returns message for authorization code
 * @param int $code
 * @return string $msg
 */
function getNonAuthMessage($code) {
	switch($code) {
		case 1: // Not logged in and trying to view non-public object
			$msg = 'The requested object is private';
			break;
		case 2: // Not logged in and trying to update
			$msg = 'You must be logged in to submit or edit';
			break;
		case 3: // Group role is guest or reviewer and is insert
			$msg = 'Guests and reviewers are not authorized to submit';
			break;
		case 4: // Not user's object and not a member of the group
			$msg = 'You are not authorized for this object';
			break;
		case 5: // Not user's object and user's role in group does not allow edit/delete
			$msg = 'Your group role does not allow editing';
			break;
		case 6: // no object for the id
			$msg = 'There is no object with this id';
			break;
		case 7: // Site disabled. Only viewing allowed
		    $msg = 'Site updates have been temporarily suspended while maintenance is performed.';
		    break;
		case 8:
		default: // Unexpected result
			$msg = 'An Unexpected system error occured';
	}
	return '<div class="searchError">'.$msg.'</div><br/><br/>';
}

/**
 * Function to eventually replace submitButton. Uses Jquery
 * @param $buttonText
 * @return unknown_type
 */
function frmSubmitButton($buttonText) {
	$button .= '<br/><br/><strong><span class="req">* -Required</span></strong>';
	$button .= '<table width="600"><tr>';
	$button .= '<td align="right">';
	$button .= '<input type="submit" class="button smallButton" value="' . $buttonText . '" />';
	$button .= '</td>';
	$button .= '<td align="center">';
	$button .= '<input type="button" id="clearFrm" class="button smallButton" value="Clear" />';
	$button .= '</td>';
	$button .= '</tr></table>';
	return $button;
}

/**
 * Function to replace old Button function located in navigation.php
 * @param $id
 * @param $objType
 * @param $relatedObj
 * @param $action
 * @return string
 */
function submitButton($id = null, $objType = null, $relatedObj = null, $action = null){
	$buttonText = is_null($action) ? 'Submit' : 'Update';
	$button .= '<br/><br/><strong><span class="req">* -Required</span></strong>';
	$button .= '<table width="600"><tr>';
	$button .= '<td align="right">';
	$button .= '<a href="javascript: checkall(\''.$id.'\', \''.$objType.'\', \''.$relatedObj.'\', \''.$action.'\');" class="button smallButton"><div>' .$buttonText. '</div></a>';
	$button .= '</td>';
	$button .= '</tr></table>';
	return $button;
}

/**
 * Get select options of users in a particular group
 * @return
 * @todo Change this method to something besides select.
 *
 * @param in $selectedUser [optional]
 * @return tag string
 */
function getContributorSelectTag($selectedUser = null, $groupId = null){
	global $objInfo;
	if (empty($selectedUser)) $selectedUser = $objInfo->getUserId();
	if (empty($groupId)) $groupId = $objInfo->getUserGroupId();
	$sql = "select u.id, u.name from User u join UserGroup r on u.id=r.user "
	." where r.groups = $groupId and r.UserGroupRole != 'reviewer' "
	." group by u.id, u.name order by u.name";
	$result = runQuery($sql);
	if($result){
		$contrib = '<tr><td><b>Contributor: </b></td><td>';
		$contrib .= '<select id="Contributor" name="Contributor" title="Please Select the contributor you are submitting for.">';
		while($names = mysqli_fetch_array($result ,MYSQL_ASSOC)){
			$selected = $names['id'] == $selectedUser ? 'selected="selected"' : '';
			$contrib .= '<option ' .$selected. ' value="' .$names['id']. '">' .$names['name']. '</option>';
		}
		$contrib .= '</select></td></tr>';
	}
	freeResult($result);
	return $contrib;
}

/**
 * Return Basis of Record select
 * @param $selectedRecord [optional]
 * @return string $records
 */
function getBasisRecordSelectTag($selectedRecord = null){
	

	$selectedRecord = !empty($selectedRecord) ? $selectedRecord : 'UNSPECIFIED';

	$result = runQuery("select name,description from BasisOfRecord order by description;");
	if	($result) {
		$records  = '<tr><td><b> Basis of Record: <span class="req">*</span></b></td>';
		$records .= '<td><select name="BasisOfRecord" title="Select from drop-down list. To add a new entry, contact the MorphBank admin group.">';
		$records .= '<option value="">--- Select from the following ---</option>';
		while($row = mysqli_fetch_array($result ,MYSQL_ASSOC)){
			$selected = $row['name'] == $selectedRecord ? 'selected="selected"' : '';
			$records .= '<option value="' .$row['name']. '" ' .$selected. '>' .$row['description']. ' </option>';
		}
		$records .= '</select>';
		$records .= '</td></tr>';
	}
	freeResult($result);
	return $records;
}

/**
 * Return Sex select
 * @param $selectedSex [optional]
 * @return string $sex
 */
function getSexSelectTag($selectedSex = null){
	

	$selectedSex = !empty($selectedSex) ? $selectedSex : 'Unspecified';

	$result = runQuery("select name from Sex order by name;");
	if ($result) {
		$sex  = '<tr><td><b>Sex: </b></td><td>';
		$sex .= '<select name="Sex" title = "Select from drop-down list. To add a new entry, contact the MorphBank admin group.">';
		while($row = mysqli_fetch_array($result ,MYSQL_ASSOC)){
			$selected = $row['name'] == $selectedSex ? 'selected="selected"' : '';
			$sex .= '<option value="' .$row['name']. '" ' .$selected. '>' .$row['name']. ' </option>';
		}
		$sex .= '</select>';
		$sex .= '&nbsp;&nbsp; <a href= "javascript: pop(\'Sex\',\'/Submit/View/Sex/\');">';
		$sex .= '<img src="/style/webImages/plusIcon.png" alt="Add Sex" title="Click to Add Sex" /> (Add)</a>';
		$sex .= '</td></tr>';
	}
	freeResult($result);
	return $sex;
}

/**
 * Return Form select
 * @param $selectedForm [optional]
 * @return string $form
 */
function getFormSelectTag($selectedForm = null){
	

	$selectedForm = !empty($selectedForm) ? $selectedForm : 'Not specified';

	$result = runQuery("select name from Form order by name;");
	if ($result) {
		$form  = '<tr><td><b>Form: </b></td><td>';
		$form .= '<select name="Form" title = "Select from drop-down list. To add a new entry, contact the MorphBank admin group.">';
		while($row = mysqli_fetch_array($result ,MYSQL_ASSOC)){
			$selected = $row['name'] == $selectedForm ? 'selected="selected"' : '';
			$form .= '<option value="' .$row['name']. '" '.$selected. '>' .$row['name']. ' </option>';
		}
		$form .= '</select>';
		$form .= '&nbsp;&nbsp; <a href= "javascript: pop(\'Form\',\'/Submit/View/Form/\');">';
		$form .= '<img src="/style/webImages/plusIcon.png" alt="Add Form"  title="Click to Add Form" /> (Add)</a>';
		$form .= '</td></tr>';
	}
	freeResult($result);
	return $form;
}

/**
 * Return Developmental Stage select
 * @param $selectedDevStage [optional]
 * @return string $stage
 */
function getDevelopmentalStageSelectTag($selectedDevStage = null){
	

	$selectedDevStage = !empty($selectedDevStage) ? $selectedDevStage : 'Unspecified';

	$result = runQuery("select name from DevelopmentalStage order by name;");
	if ($result) {
		$stage  = '<tr><td><b>Developmental Stage: </b></td><td>';
		$stage .= '<select name="DevelopmentalStage" title="Select from drop-down list. To add a new entry, contact the Morphbank admin group.">';
		while($row = mysqli_fetch_array($result ,MYSQL_ASSOC)){
			$selected = $row['name'] == $selectedDevStage ? 'selected="selected"' : '';
			$stage .= '<option value="' .$row['name']. '" ' .$selected. '>' .$row['name']. ' </option>';
		}
		$stage .= '</select>';
		$stage .= '&nbsp;&nbsp; <a href= "javascript: pop(\'Stage\',\'/Submit/View/Stage/\');">';
		$stage .= '<img src="/style/webImages/plusIcon.png" alt="Add Stage"  title="Click to Add Stage" /> (Add)</a>';
		$stage .= '</td></tr>';
	}
	freeResult($result);
	return $stage;
}

/**
 * Return Type Status Tag
 * @param $selectedType [optional]
 * @return string $types
 */
function getTypeStatusSelectTag($selectedType = null){
	

	$result = runQuery("select name from TypeStatus order by name;");
	if ($result) {
		$types  = '<tr><td><b>Type Status: <span class="req">*</span></b></td><td>';
		$types .= '<select name="TypeStatus" title="Select from drop-down list. To add a new entry, contact the MorphBank admin group.">';
		$types .= '<option value ="">--- Select TypeStatus ---</option>';
		while($row = mysqli_fetch_array($result ,MYSQL_ASSOC)){
			$selected = $row['name'] == $selectedType ? 'selected="selected"' : '';
			$types .= '<option value="' .$row['name']. '" ' .$selected. '>' .$row['name']. ' </option>';
		}
		$types .= '</select>';
		$types .= '</td></tr>';
	}
	freeResult($result);
	return $types;
}

/**
 * Return image technique select
 * @param $selectedImage [optional]
 * @return string $image
 */
function getImageTechniqueSelectTag($selectedImage = null){
	

	$selectedImage = !empty($selectedImage) ? $selectedImage : 'Unspecified';

	$result = runQuery("select name from ImagingTechnique order by name;");
	if ($result) {
		$image  = '<tr><td><b>Imaging Technique: </b></td><td>';
		$image .= '<select name="ImagingTechnique" title = "Select imaging technique from the drop-down list.">';
		while($row = mysqli_fetch_array($result ,MYSQL_ASSOC)){
			$selected = $row['name'] == $selectedImage ? 'selected="selected"' : '';
			$image .= '<option value="' .$row['name']. '" ' .$selected. '>' .$row['name']. ' </option>';
		}
		$image .= '</select>&nbsp;&nbsp; <a href="javascript: pop(\'ImagingTechnique\', \'/Submit/View/ImagingTechnique/\');">';
		$image .= '<img src="/style/webImages/plusIcon.png" alt="Add Imaging Technique" title="Click to Add Imaging Technique" /> (Add)</a>';
		$image .= '</td></tr>';
	}
	freeResult($result);
	return $image;
}

/**
 * Return urn image preparation techinique select
 * @param $selectedPrep [optional]
 * @return string $prep
 */
function getImagePrepSelectTag($selectedPrep = null){
	

	$selectedPrep = !empty($selectedPrep) ? $selectedPrep : 'Unspecified';

	$result = runQuery("select name from ImagingPreparationTechnique order by name;");
	if ($result) {
		$prep  = '<tr><td><b>Imaging Preparation Technique: </b></td><td>';
		$prep .= '<select name="ImagingPreparationTechnique" title = "Select preparation technique from the drop-down list.">';
		while($row = mysqli_fetch_array($result ,MYSQL_ASSOC)){
			$selected = $row['name'] == $selectedPrep ? 'selected="selected"' : '';
			$prep .= '<option value="' .$row['name']. '" ' .$selected. '>' .$row['name']. ' </option>';
		}
		$prep .= '</select>';
		$prep .= '&nbsp;&nbsp; <a href= "javascript: pop(\'ImagingPreparationTechnique\', \'/Submit/View/ImagingPreparationTechnique/\');">';
		$prep .= '<img src="/style/webImages/plusIcon.png" alt="Add Imaging Preparation Technique" title="Click to Add Imaging Preparation Technique" /> (Add)</a>';
		$prep .= '</td></tr>';
	}
	freeResult($result);
	return $prep;
}

/**
 * Return specimen part select
 * @param $selectedPart [optional]
 * @return string $part
 */
function getSpecimenPartSelectTag($selectedPart = null){
	

	$selectedPart = !empty($selectedPart) ? $selectedPart : 'Unspecified';

	$result = runQuery("select name from SpecimenPart order by name;");
	if ($result) {
		$part  = '<tr><td><b>Specimen Part: </b></td><td>';
		$part .= '<select name="SpecimenPart" title="Select specimen part imaged in this view from the drop-down list.">';
		while($row = mysqli_fetch_array($result ,MYSQL_ASSOC)){
			$selected = $row['name'] == $selectedPart ? 'selected="selected"' : '';
			$part .= '<option value="' .$row['name']. '" ' .$selected. '>' .$row['name']. ' </option>';
		}
		$part .= '</select>';
		$part .= '&nbsp;&nbsp; <a href="javascript: pop(\'Part\', \'/Submit/View/Part/\');">';
		$part .= '<img src="/style/webImages/plusIcon.png" alt="Add Specimen Part" title="Click to Add Specimen Part" /> (Add)</a>';
		$part .= '</td></tr>';
	}
	return $part;
}

/**
 * Build and return select view angle options
 * @param $selectedAngle [optional]
 * @return string $angle
 */
function getViewAngleSelectTag($selectedAngle = null){
	

	$selectedAngle = !empty($selectedAngle) ? $selectedAngle : 'Unspecified';

	$result = runQuery("select name from ViewAngle order by name;");
	if ($result) {
		$angle  = '<tr><td><b>View Angle: </b></td><td>';
		$angle .= '<select name="ViewAngle" title = "Select view angle from the drop-down list.">';
		while($row = mysqli_fetch_array($result ,MYSQL_ASSOC)){
			$selected = $row['name'] == $selectedAngle ? 'selected="selected"' : '';
			$angle .= '<option value="' .$row['name']. '" ' .$selected. '>' .$row['name']. ' </option>';
		}
		$angle .= '</select>';
		$angle .= '&nbsp;&nbsp; <a href= "javascript: pop(\'ViewAngle\', \'/Submit/View/ViewAngle/\');" >';
		$angle .= '<img src="/style/webImages/plusIcon.png" alt="Select View Angle" title="Click to Add View Angle" /> (Add)</a>';
		$angle .= '</td></tr>';
	}
	freeResult($result);
	return $angle;
}

/**
 * Returns select options for publication type
 * @return string $publications
 */
function publicationTypesSelectTag($selectedType = null) {
	$selectedType = !empty($selectedType) ? $selectedType : 'unpublished';
	$types = array("book" => "Book",
				   "booklet" => "Booklet",
				   "conference" => "Conference",
			       "inbook" => "InBook",
			       "inseries" => "InSeries",
			       "incollection" => "Incollection",
			       "inproceedings" => "Inproceedings",
			       "journal_article" => "Journal article",
			       "manual" => "Manual",
			       "misc" => "Misc",
			       "proceedings" => "Proceedings",
			       "techreport" => "Techreport",
			       "thesis" => "Thesis",
			       "unpublished" => "Unpublished",
			       "web_publication" => "Web publication");
	$publications = '<tr ><td><b>Publication type: </b></td><td>
					<select id="publicationtype" name="publicationtype">';
	foreach ($types as $key => $value) {
		$selected = $key == $selectedType ? 'selected="selected"' : '';
		$publications .= '<option value="' .$key.'" ' .$selected.'>' .$value. '</option>';
	}
	$publications .= '</select></td></tr>';
	return $publications;
}

/**
 * Create select options for month field
 * @return string $months
 */
function createMonthFieldSelectTag($selectedMonth = null){
	$monthArray = array("Jan" => "January",
						"Feb" => "February",
						"Mar" => "March",
						"Apr" => "April",
						"May" => "May",
						"Jun" => "June",
						"Jul" => "July",
						"Aug" => "August",
						"Sep" => "September",
						"Oct" => "October",
						"Nov" => "November",
						"Dec" => "December");
	$months = '<tr id="month"><td><b>Published in month: </b></td><td>
				<select name="month" title="Select the month of the publication.">
        		<option value="">--Select month--</option>';
	foreach ($monthArray as $key => $value) {
		$selected = $key == $selectedMonth ? 'selected="selected"' : '';
		$months .= '<option value="' .$key.'" ' .$selected.'>' .$value. '</option>';
	}
	$months .= '</selected></td></tr>';
	return $months;
}

/**
 * Html name type select for taxa form
 * @param $selectedName
 * @return string
 */
function taxaNameTypeSelect($selectedName = null) {
	$selected = $selectedName == 'Manuscript name' ? 'selected="selected"' : '';
	$html = '<tr><td width="30%"><b>Type Of Name:</b></td>'
	.'<td><select name="nametype" id="nametype">'
	.'<option value="Regular scientific name">Regular scientific name</option>'
	.'<option value="Manuscript name" ' . $selected . '>Manuscript name</option>'
	.'</select></td></tr>';
	return $html;
}

/**
 * Get select options for status in taxa form
 * @return
 * @param in $selectedStatus [optional]
 * @return tag string
 */
function getStatusSelectTag($selectedStatus = null){
	$selected = $selectedStatus == "public" ? 'selected="selected"' : '';
	$html = '<tr><td width="30%px"><b>Name status: </b></td>'
	.'<td><select name="status">'
	.'<option value="not public">Do not publish yet</option>'
	.'<option value="public" '.$selected.'>Publish now</option>'
	.'</select></td></tr>';
	return $html;
}

function showDataTable($fieldRows, $dataArray, $title=null){
	if (!empty($title))	$tag = "<h3>$title</h3>";
	$tag .= "<br/>";
	$size = count($fieldRows);
	$tag .= '<table>';
	for ($i=0; $i < $size; $i++) {
		$tag .= showArrayItem($fieldRows[$i],$dataArray);
	}
	$tag .= '</table>';
	return $tag;
}

function showArrayItem($fieldRow, $dataArray){
	$display = $fieldRow['display'];
	$field = $fieldRow['field'];
	$value = $dataArray[$field];
	if($display || !empty($value)) {
		$tag = '<tr><th>'.$fieldRow['label'].'</th><td>';
		$tag .= $value;
		//$tag .= mb_convert_encoding($value,"HTML-ENTITIES","UTF-8");
		$tag .= "</td></tr>\n";
	}
	return $tag;
}
