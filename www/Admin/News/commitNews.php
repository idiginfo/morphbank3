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

/*
include_once('imageFunctions.php');
include_once('Admin/admin.functions.php');
*/

checkIfLogged();
$userid = $objInfo->getUserId();

$title     = trim($_POST['title']);
$body      = trim($_POST['body']);
$imageText = trim($_POST['imageText']);

if (isset($_FILES['imageFile']) && !empty($_FILES['imageFile']['name'])) {
	$imagefile = $_FILES['imageFile']['name'];
	$imagesize = $_FILES['imageFile']['size'];
	$imagetype = $_FILES['imageFile']['type'];
	$tmpFile   = $_FILES['imageFile']['tmp_name'];
	move_uploaded_file($tmpFile, $config->newsImagePath . $imagefile);
	exec("chmod 777 ". $config->newsImagePath . $imagefile);
}

$db = connect();
$params = array("NOW()", $db->quote($title), $db->quote($body), $db->quote($imagefile),
	 $db->quote($imageText), 1, $userid, 2, $userid, $db->quote("News added to Database"));
$result = $db->executeStoredProc('NewsInsert', $params) or die("yoyo");
isMdb2Error($result, "Creating News Stored Procedure");
$id = $result->fetchOne();
isMdb2Error($id, "Retrieving new id for news item");
clear_multi_query($result);

header("Location: /Admin/News/addNews.php?&code=1");
