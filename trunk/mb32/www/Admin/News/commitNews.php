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

include_once('updater.class.php');
include_once('objectFunctions.php');
include_once('updateObjectKeywords.php');

$indexUrl = "/Admin/News/?action=add";
$userId = $objInfo->getUserId();
$groupId =$objInfo->getUserGroupId();
if (!checkAuthorization($id, $userId, $groupId, 'edit')) {
	header ("location: index.php");
	exit;
}

if (empty($_POST['title']) || empty($_POST['body'])) {
  header ("location: $indexUrl&code=3");
	exit;
}

$db = connect();
// Insert News Object returning id
$dateToPublish = date('Y-m-d');
$params = array($db->quote("News"), $userId, $groupId, $userId, $db->quote($dateToPublish,'date'), $db->quote("News added"), $db->quote(NULL));
$result = $db->executeStoredProc('CreateObject', $params);
if(isMdb2Error($result, 'Create Object procedure', 6)) {
  header("location: $indexUrl&code=8");
  exit;
}
$id = $result->fetchOne();
if(isMdb2Error($id, 'Error retrieving new id of BaseObject', 6) || empty($id)) {
  header("location: $indexUrl&code=9");
  exit;
}

clear_multi_query($result);

$title     = trim($_POST['title']);
$body      = trim($_POST['body']);
$imageText = trim($_POST['imageText']);

// prepare update
$newsUpdater = new Updater($db, $id, $userId , $groupId, 'News');
$newsUpdater->addField('title', $title, null);
$newsUpdater->addField('body', $body, null);
$newsUpdater->addField('imageText', $imageText, null);
$numRowsNews = $newsUpdater->executeUpdate();
if (is_string($numRowsNews)) { // Error returned
	header("location: /Admin/News/?&action=edit&id=$id&code=5");
	exit;
}


if (!empty($_FILES['imageFile']['name'])) {
  $name = $_FILES['imageFile']['name'];
  $tmpFile = $_FILES['imageFile']['tmp_name'];
  if (!move_uploaded_file($tmpFile, $config->newsImagePath . $name)) {
    header("location: /Admin/News/?&action=edit&id=$id&code=17");
    exit;
  }
  exec("chmod 755 " . $config->newsImagePath . $name);
  
  $image = $config->appServerBaseUrl . 'images/newsImages/' . $name;
  $newsUpdater = new Updater($db, $id, $userId , $groupId, 'News');
  $newsUpdater->addField('image', $image, null);
  $numRowsNews = $newsUpdater->executeUpdate();
  if (is_string($numRowsNews)) { // Error returned
    header("location: /Admin/News/?&action=edit&id=$id&code=7");
    exit;
  }
}

updateKeywordsTable($id, 'insert');

header("location: /Admin/News/?&action=edit&id=$id&code=15");
