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
/**
 * File name: editGroupMembers.php
 * @package Morphbank2
 * @subpackage Admin Group
 * @author Robert Bruhn
 */
include_once('head.inc.php');
include_once('showFunctions.inc.php');
include_once('urlFunctions.inc.php');
include_once('groupFunctions.php');

$includeJavaScript = array('jquery.1-4-2.min.js', 'jquery-ui-1.8.min.js', 'jquery.validate.min.js', 'formMethods.js');

// The beginnig of HTML
$title = 'Select Group Membership';
initHtml($title, null, $includeJavaScript);


// Add the standard head section to all the HTML output.
echoHead(false, $title);

$id = $_GET['id'];
$search = $_GET['search'];
$code = $_GET['code'];
$row = getGroupInfo($id);
$header = isset($search) ? 'Add' : 'Modify';

echo '<div class="mainGenericContainer" style="width:600px">';
echo '<br></br><h2>' . $header . ' Group Members</h2>';

echo '<p><span style="color:#17256B"><b>Group Name: ' . $row['groupname'] . '</b></span></p>';
$gmId = $row['groupmanagerid'];

if (!checkGroupEditAuthorization()) {
  getMemberEditMsg($id, 2);
} else {
  if (!empty($id)) {
    getMemberEditMsg($id, $code);
    $users = getGroupUsers($id, true);
    searchUser($id, $gmId, $users);
    if (empty($search)) {
      showUsers($id, $gmId, $users);
    }
  } else {
    getMemberEditMsg($id, 3);
  }
}
echo '</div>';
finishHtml();
