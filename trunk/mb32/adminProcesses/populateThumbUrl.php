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

// Script will populate BaseObject table with the thumbUrl that is used for displaying thumbnails.
// Need to run standardImageIds.php first
// thumbURL is just an image id at this point.  Could be a url in future.

require_once(dirname(dirname(__FILE__)) . '/configuration/app.server.php');

// Located in www folder
include_once('Phylogenetics/phylo.inc.php');
//TODO get a subspecimen image if not direct image

$db = connect();
echo date("H:i:s\n");

$updateSql = "update BaseObject set thumbURL = ? where id = ?";
$updateStmt = $db->prepare($updateSql, array('integer','integer'));

// update image thumburls
$sql = "SELECT id, id FROM BaseObject where objecttypeid='Image' and (thumbURL is null or thumbUrl<>id)";
$imageCount = updateThumbUrl($sql);
echo "\n\nTotal Images: ".$imageCount."\n";
unset($imageIds);

// update specimen thumburls
// Get specimens where thumburl needs to be updated
$sql = "SELECT standardImageId, s.id FROM Specimen s join BaseObject b on s.id=b.id "
."where standardimageid is not null and (thumburl is null or standardimageid<>thumburl)";
$spCount = updateThumbUrl($sql);

// Update specimen from child specimen
$sql = "select min(BSS.thumbUrl), BS.id from BaseObject BSS join CollectionObjects C on BSS.id=C.objectId"
." join BaseObject BS on C.collectionId = BS.id "
. " where BS.thumbUrl is null and BSS.thumbUrl is not null and "
. " objectrole = 'child' and BS.objecttypeid='Specimen'"
." group by BS.id ";

echo "Child sql: $sql\n";
$childCount = updateThumbUrl($sql);

echo "\nTotal Specimens: $spCount, children: $childSpCount\n";
unset($rows);
//exit(0);

// update view thumburls

$sql = "SELECT standardImageId, v.id  FROM View v join BaseObject b on v.id=b.id "
."  where standardImageId is not null and thumburl is null ";
$viewCount = updateThumbUrl($sql);

echo "\nTotal Views: $viewCount\n";
unset($viewArray);

// update collection thumburls
// get the ids of the first
//TODO GR check and fix this sql
$firstCollObjSql = "SELECT id, min(objectOrder) as objectOrder FROM BaseObject b join CollectionObjects on id=collectionId".
" WHERE b.objectTypeId in ('Collection','MbCharacter') and thumbUrl is null group by id ";

$sql =  "select b.thumbURL, objectId from CollectionObjects c join BaseObject b on c.objectId = b.id "
. " join ($firstCollObjSql) f on (f.id = collectionId and c.objectOrder=f.objectOrder) "
."  ";
echo $sql;
$collectionCount = updateThumbUrl($sql);

echo "\nTotal Collections and Characters: $collectionCount\n\n";

// now do the Annotations of Images

$sql = "SELECT  a.objectId, a.id FROM Annotation a join BaseObject b on a.id=b.id "
." WHERE a.objectTypeId = 'Image' and thumbUrl is null";

$annotationCount = updateThumbUrl($sql);

echo "\nTotal Annotations: $annotationCount\n";
unset($annotationArray);

$sql = "select min(b.id), u.id from BaseObject b join BaseObject u on (b.userId=u.id or b.submittedBy = u.id)"
." where b.objecttypeid='Image' and u.objecttypeid='User' and u.thumburl is null";
$userCount = updateThumbUrl($sql);
echo "\nTotal Users: $userCount\n\n";

echo date("H:i:s\n");


function updateThumbUrl($sql){
	global $db, $updateStmt;
	$count = 0;
	$rows = $db->query($sql);
	if(PEAR::isError($rows)){
		echo("Error in Missing SQL query".$rows->getUserInfo()." sql is: $sql\n");
		die();
	}
	while ($specArray = $rows->fetchRow(MDB2_FETCHMODE_ORDERED)){
		$numRowsUpdated = $updateStmt->execute($specArray);
		$count++;
	}
	return $count;
}
?>
