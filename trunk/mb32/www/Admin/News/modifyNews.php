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

$id        = trim($_POST['id']);
$title     = trim($_POST['title']);
$body      = trim($_POST['body']);
$imageText = trim($_POST['imageText']);
$indexUrl  = "/Admin/News/?action=edit&id=$id";
$userId    = $objInfo->getUserId();
$groupId   = $objInfo->getUserGroupId();
if (!checkAuthorization($id, $userId, $groupId, 'add')) {
	header ("location: index.php");
	exit;
}

if (empty($_POST['title']) || empty($_POST['body'])) {
  header ("location: $indexUrl&code=3");
	exit;
}

$db = connect();

// Get news info and prepare update
$newsObj = getObjectData('News', $id);
if (is_string($newsObj)) { // Error returned
	header("location: $indexUrl&code=4");
	exit;
}
$newsUpdater = new Updater($db, $id, $userId , $groupId, 'News');
$newsUpdater->addField('title', $title, $newsObj['title']);
$newsUpdater->addField('body', $body, $newsObj['body']);
$newsUpdater->addField('imageText', $imageText, $newsObj['imagetext']);
// Update News
$numRowsNews = $newsUpdater->executeUpdate();
if (is_string($numRowsNews)) { // Error returned
	header("location: $indexUrl&code=5");
	exit;
}

if (!empty($_FILES['imageFile']['name'])) {
  $name = $_FILES['imageFile']['name'];
  $tmpFile = $_FILES['imageFile']['tmp_name'];
  if (!move_uploaded_file($tmpFile, $config->newsImagePath . $name)) {
    header("location: $indexUrl&code=17");
    exit;
  }
  exec("chmod 755 " . $config->newsImagePath . $name);
  
  $image = $config->appServerBaseUrl . 'images/newsImages/' . $name;
  $newsUpdater = new Updater($db, $id, $userId , $groupId, 'News');
  $newsUpdater->addField('image', $image, $newsObj['image']);
  $numRowsNews = $newsUpdater->executeUpdate();
  if (is_string($numRowsNews)) { // Error returned
      header("location: $indexUrl&code=5");
      exit;
  }
}

// Update keywords
if ($numRowsNews == 1) {
	updateKeywordsTable($id, 'update');
}

header("location: $indexUrl&code=1");
exit;
