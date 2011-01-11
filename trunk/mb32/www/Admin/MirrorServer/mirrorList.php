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

// This file was created to display the list of currently
// registared mirroring servers with morphbank and their location
// so the user will choose the clossest one
//
// Created by: Karolina Maneva-Jakimoska
// Created on: Feb 13th 2007



if (isset($_GET['pop']) && $_GET['pop'] == YES) {
	include_once('pop.inc.php');
} else {
	include_once('head.inc.php');
}
include_once('Admin/admin.functions.php');

checkIfLogged();



$userid = $objInfo->getUserId();

$title = 'Mirror Servers List';
initHtml($title, null, null);
echoHead(false, $title);

if (isset($_GET['pop']) && $_GET['pop'] == YES) {
	echo '<div class="popBackground">';
} else {
	echo '<div class="main">';
}

echo '<div class="mainGenericContainer" style="width:620px">';
echo '<h1>Mirror Servers List</h1>';

include_once('js/pop-update.js');

$link = Adminlogin();

//query the database to retreive all mirroring servers
//registered with Morphbank in the moment

$query = "SELECT serverId, url , locality FROM ServerInfo";
$result = mysqli_query($link, $query);
if (!$result) {
	echo '<span style="color:red"><b>Problems querying the data base</b></span>';
} else {

	$numRows = mysqli_num_rows($result);

	// fetch the row information...
	for ($index = 0; $index < $numRows; $index++) {
		$row = mysqli_fetch_array($result);
		$mirror['serverId'][$index] = $row['serverId'];
		$mirror['url'][$index] = $row['url'];
		$mirror['locality'][$index] = $row['locality'];
		if (strpos($mirror['locality'][$index], ",") > 0) {
			$mirror['country'][$index] = substr($mirror['locality'][$index], 0, strpos($mirror['locality'][$index], ","));
			$mirror['locality'][$index] = substr($mirror['locality'][$index], strpos($mirror['locality'][$index], ",") + 1, strlen($row['locality']));
			$mirror['state'][$index] = substr($mirror['locality'][$index], 0, strpos($mirror['locality'][$index], ","));
			$mirror['city'][$index] = substr($mirror['locality'][$index], strpos($mirror['locality'][$index], ",") + 1, strlen($mirror['locality'][$index]));
		}
	}

	echo '<form name="mirror_list">
              <br/>
              <table width="600px" >
                <tr>
                  <td>&nbsp;&nbsp;</td>
                  <td><b><span style="color:#17256B">Server URL:</span></b></td>
                  <td><b><span style="color:#17256B">Country: </span></b></td>
                  <td><b><span style="color:#17256B">State/Provance: </span></b></td>
                  <td><b><span style="color:#17256B">City: </span></b></td>
                <tr>';
	for ($index = 0; $index < $numRows; $index++) {
		echo '<tr>
                   <td><input type="radio" name="server" onclick="javascript: opener.UpdatePrefServ(\'' . $mirror['serverId'][$index] . '\',\'' . $mirror['url'][$index] . '\'); window.close();" title="Click the radio button to select your preferred server" /></td>
                   <td>' . $mirror['url'][$index] . '</td>
                   <td>' . $mirror['country'][$index] . '</td>
                   <td>' . $mirror['state'][$index] . '</td>
                   <td>' . $mirror['city'][$index] . '</td>
                 </tr>';
	}
}
finishHtml();
?>
