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

if (isset($_GET['pop'])){
	include_once('pop.inc.php');
} else {
	include_once('head.inc.php');
}

checkIfLogged();
groups();
$link = Adminlogin();

/* Enable for deleting TSN
 if (isset($_GET['tsn'])) {
 header('Location:' .$config->domain. 'Admin/TaxonSearch/editTSN.php?id='.$_GET['id']. '&prevURL=\''.$url.'\'');
 exit;
 }
 */

if ($_GET['id']) {
	$ObjectType = 'SELECT objectTypeId, dateToPublish, userId, submittedBy FROM BaseObject WHERE id = \'' . $_GET['id'] . '\';';

	$row = mysqli_fetch_array(runQuery($ObjectType));

	$url = preg_replace("/\?tab=(.*)Tab/e", '', $_SERVER['HTTP_REFERER']);
	
	if ($row) {
		//  if($objInfo->getUserId() == $row['userId'] || $objInfo->getUserId() == $row['submittedBy'] || $objInfo->getUserGroupRole() == 'administrator'){
		if ($objInfo->getUserGroupRole() == 'administrator') {
			//$query = "CALL " .$row['objectTypeId']. "Delete("  .$id. ")"; //Can be used to handle delete of all objects

			if ($row['objectTypeId'] == 'View') {
				$query = "CALL ViewDelete(@oMsg," . $_GET['id'] . ")";
			} elseif ($row['objectTypeId'] == 'Specimen') {
				$query = "CALL SpecimenDelete(@oMsg," . $_GET['id'] . ")";
			} elseif ($row['objectTypeId'] == 'Locality') {
				$query = "CALL LocalityDelete(@oMsg," . $_GET['id'] . ")";
			} elseif ($row['objectTypeId'] == 'Publication') {
				$query = "CALL PublicationDelete(@oMsg," . $_GET['id'] . ")";

				//}elseif ($row['objectTypeId'] == 'Taxa'){
			} else {
				// if the query returned a valid set, but no matches

				$title = 'Delete';
				initHtml($title, null, null);

				// Add the standard head section to all the HTML output.
				echoHead(false, $title);

				echo '<div class="mainGenericContainer" style="width:600px ">
          The object provided cannot be deleted. Contact Morphbank Administration: <b>mbadmin <font color="blue">at</font> scs <font color="blue">dot</font> fsu <font color="blue">dot</font> edu</b>
          <a href = "javascript: top.location = \'./\'" class="button smallButton"><div>Cancel</div></a>';
			}
		} else {
			header('Location:' . $config->domain . 'Edit/' . $row['objectTypeId'] . '/?id=' . $_GET['id'] . '&code=\'99\'&prevURL=\'' . $url . '\'');
		}

		$results = mysqli_query($link, $query) or die('Could not run delete query. MB id= ' . $_GET['id'] . '<br />' . $query . '<br />' . mysqli_error($link));
		if ($results)
		header('Location:' . $config->domain . 'Edit/' . $row['objectTypeId'] . '/?id=' . $_GET['id'] . '&prevURL=\'' . $url . '\'');
	}

	// if the query returned a invalid record set or was null
	else {
		echo '<div class="popContainer">
      <div class="innerContainer7">
        Invalid set or Null.        
      </div>  
    </div>';
	}
} else {
	include_once('mainEdit.php');
	// The beginnig of HTML
	$title = 'Edit';
	initHtml($title, null, null);

	// Add the standard head section to all the HTML output.
	echoHead(false, $title);
	echo '<div class="mainGenericContainer" style="width:600px ">';
	mainEdit();
}
echo '</div>';


// Finish with end of HTML
finishHtml();
?>
