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

// Created by David Gaitros on 09/01/2005
// Modified; 1/26/2006: Include files and static path names.
// File modified on Sep 20 2006 from Karolina Maneva-Jakimoska
// to avoid calling mainNews and report the News Item added to
// the database.
// Modified on 1/11/2007 to replace mysql with mysqli classes

include_once('head.inc.php');
include_once('imageFunctions.php');
include_once('Admin/admin.functions.php');

global $config;


checkIfLogged();
$userid = $objInfo->getUserId();

$link = Adminlogin();

$title = 'Add News';
initHtml($title, null, null);
echoHead(false, $title);

echo '<div class="mainGenericContainer" style="width:700px">';
echo '<h1>Add News</h1>';
echo '<form name="Add News">';


$title = $_POST['title'];
$body = $_POST['body'];
$imageText = $_POST['imageText'];

if (isset($_FILES['imageFile']) && ($_FILES['imageFile']['name']) > "") {
	$imagefile = $_FILES['imageFile']['name'];
	//  if($image=="")
	$image = $imagefile;
	//$imagename = substr($imagefile,0,strpos($imagefile,"."));
	$imagesize = $_FILES['imageFile']['size'];
	//if($imagesize > 100000)
	//echo "Image size is to big ";
	$imagetype = $_FILES['imageFile']['type'];
	$tmpFile = $_FILES['imageFile']['tmp_name'];

	move_uploaded_file($tmpFile, $config->newsImagePath . $imagefile);
	exec("chmod 777 " . $config->newsImagePath . $imagefile);
}


/***********************************************************************************************
 *  1. Get the current id from the id table.                                                *
 *  2. Add one to the id field.                                                                 *
 *  3. Update the id field in the id table.                                                   *
 *  4. Insert the new user record using the new id .                                          *
 *  5. Add a new record to the "baseobject" table referenceing the new user.                    *
 ***********************************************************************************************/
$db = connect();
$params = array("NOW()", $db->quote($title), $db->quote($body), $db->quote($image),
	 $db->quote($imageText), 1, $userid, 2, $userid, $db->quote("News added to Database"));
$result = $db->executeStoredProc('NewsInsert', $params) or die("yoyo");
isMdb2Error($result, "Creating News Stored Procedure");
$id = $result->fetchOne();
isMdb2Error($id, "Retrieving new id for news item");
clear_multi_query($result);

echo '<table align="right">
            <tr>
                     <td><a href="' . $config->domain . 'Admin/News/addNews.php" class="button smallButton" title="Click to add more News">
                         <div>Add new</div></a>
                     </td>
               <td><a href="' . $config->domain . 'Admin/News/?last=1" class="button smallButton" title="Click to return to News">
                         <div>Return</div></a>
                     </td>
                   </tr>
              </table>';
echo '</form>';
echo '</div>';
finishHtml();
?>
