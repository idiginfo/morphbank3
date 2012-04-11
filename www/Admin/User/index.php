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

include_once('head.inc.php');
include_once('tsnFunctions.php');
include_once('showFunctions.inc.php');
include_once('forms.inc.php');
include_once('spam.php');
include_once('mainUser.php');

// Get User ID and Group ID
$userId = $objInfo->getUserId();
$groupId = $objInfo->getUserGroupId();

// Set action, ID, and msg if REQUEST
$action = $_REQUEST['action'];
$id     = empty($_REQUEST['id']) ? $userId : $_REQUEST['id'];
$code   = $_REQUEST['code'];

// Check Logged in and group if action other than signup
if ($action != 'new') {
	checkIfLogged();
	groups();
}

// The beginnig of HTML
$title = 'User';
$includeJavaScript = array('jquery.1-4-2.min.js', 'jquery.validate.min.js', 'formMethods.js');

initHtml($title, null, $includeJavaScript);

// Add the standard head section to all the HTML output.
echoHead(false, $title);
echo '<div class="mainGenericContainer" style="width:700px">';

if ($groupId != $config->adminGroup && $config->disableUserFunctions) {
  echo "<h2>User functions currently disabled.</h2>";
} else {
  // Show search form on all pages for administrators
  if ($groupId == $config->adminGroup) {
      searchUser();
  }

  // Action determines what is displayed
  if ($action == 'edit') {
      echo getMessage($code);
      editUser($id);
  } elseif ($action == 'add') {
      echo getMessage($code);
      addUser($_REQUEST);
  } elseif ($action == 'new') {
      echo getMessage($code);
      newUser($_REQUEST);
  } elseif ($action == 'cv') {
      echo getMessage($code);
      viewCV($_REQUEST);
  }
}

echo '</div>';
// Finish with end of HTML
finishHtml();
?>
