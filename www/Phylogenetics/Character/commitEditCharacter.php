#-------------------------------------------------------------------------------
# Copyright (c) 2010 Greg Riccardi, Fredrik Ronquist.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Public License v2.0
# which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# 
# Contributors:
#   Fredrik Ronquist - conceptual modeling and interaction design
#   Austin Mast - conceptual modeling and interaction design
#   Greg Riccardi - initial API and implementation
#   Wilfredo Blanco - initial API and implementation
#   Robert Bruhn - initial API and implementation
#   Christopher Cprek - initial API and implementation
#   David Gaitros - initial API and implementation
#   Neelima Jammigumpula - initial API and implementation
#   Karolina Maneva-Jakimoska - initial API and implementation
#   Katja Seltmann - initial API and implementation
#   Stephen Winner - initial API and implementation
#-------------------------------------------------------------------------------
<?php
//File to commit the phyloCharacter information changes to the
//database
//
//@author Karolina Maneva-Jakimoska
//@date created August 26th 2007
//

include_once('head.inc.php');
include_once('collectionFunctions.inc.php');
include_once('Phylogenetics/phylo.inc.php');

global $config;

$groupId = $objInfo->getUserGroupId();
$userId = $objInfo->getUserId();

if (isset($_POST['id'])) {
	$url = $_POST['url'];
	$url_current = $config->domain . 'Phylogenetics/Character/editCharacter.php?id=' . $_POST['id'];
	$link = Adminlogin();
	$id = $_POST['id'];
	$flag = 0;
	$flag_date = 0;
	$flag_error = 0;

	$query_updateBO = "Update BaseObject set ";
	$queryh = "INSERT INTO History (id,userId,groupId,dateModified,modifiedFrom,modifiedTo,tableName) VALUES(";
	$modifiedFrom = "";
	$modifiedTo = "";

	//update base object for character
	if ($_POST['description_old'] != $_POST['description']) {
		$query_updateBO .= " description = '" . $_POST['description'] . "',";
		$modifiedFrom .= " description: " . trim($_POST['description_old']);
		$modifiedTo .= " description: " . trim($_POST['description']);
		$flag = 1;
	}
	if ($_POST['dateToPublish_old'] != $_POST['dateToPublish']) {
		$query_updateBO .= " dateToPublish = '" . $_POST['dateToPublish'] . "',";
		$modifiedFrom .= " dateToPublish: " . trim($_POST['dateToPublish_old']);
		$modifiedTo .= " dateToPublish: " . trim($_POST['dateToPublish']);
		$flag = 1;
		$flag_date = 1;
	}
	if ($_POST['title_old'] != $_POST['title']) {
		$query_updateBO .= " name = '" . $_POST['title'] . "',";
		$modifiedFrom .= " name: " . trim($_POST['title_old']);
		$modifiedTo .= " name: " . trim($_POST['title']);
		$flag = 1;
	}
	if ($flag == 1) {
		if (strrpos($query_updateBO, ",") == (strlen($query_updateBO) - 1))
		$query_updateBO = substr($query_updateBO, 0, strlen($query_updateBO) - 1);
		$query_updateBO .= " where id = " . $_POST['id'] . ";";

		$result = mysqli_query($link, $query_updateBO);
		if (!$result)
		$flag_error = 1;
		else {

			$queryH = $queryh . $_POST['id'] . "," . $userId . "," . $groupId . ",NOW(),'" . $modifiedFrom . "','" . $modifiedTo . "','BaseObject')";
			$result = mysqli_query($link, $queryH);
			if (!$result)
			$flag_error = 1;
			$modifiedFrom = "";
			$modifiedTo = "";
		}
		$flag = 0;
	}

	//update character information in MbCharacter table
	$query_updateC = "Update MbCharacter set ";

	if ($_POST['ordered_old'] != $_POST['ordered']) {
		$query_updateC .= " ordered = " . $_POST['ordered'] . ",";
		$modifiedFrom .= " ordered: " . trim($_POST['ordered_old']);
		$modifiedTo .= " ordered: " . trim($_POST['ordered']);
		$flag = 1;
	}

	if ($_POST['discrete_old'] != $_POST['discrete']) {
		$query_updateC .= " discrete = " . $_POST['discrete'] . ",";
		$modifiedFrom .= " discrete: " . trim($_POST['discrete_old']);
		$modifiedTo .= " discrete: " . trim($_POST['discrete']);
		$flag = 1;
	}
	if ($_POST['publicationId_old'] != $_POST['publicationId']) {
		$query_updateC .= " publicationId = " . $_POST['publicationId'] . ",";
		$modifiedFrom .= " publication: " . trim($_POST['publicationId_old']);
		$modifiedTo .= " publication: " . trim($_POST['publicationId']);
		$flag = 1;
	}
	if ($_POST['pubComment_old'] != $_POST['pubComment']) {
		$query_updateC .= " pubComment = '" . $_POST['pubComment'] . "',";
		$modifiedFrom .= " pubComment: " . trim($_POST['pubComment_old']);
		$modifiedTo .= " pubCommment: " . trim($_POST['pubComment']);
		$flag = 1;
	}
	if ($_POST['label_old'] != $_POST['label']) {
		$query_updateC .= " label = '" . $_POST['label'] . "',";
		$modifiedFrom .= " label: " . trim($_POST['label_old']);
		$modifiedTo .= " label: " . trim($_POST['label']);
		$flag = 1;
	}
	if ($flag == 1) {
		if (strrpos($query_updateC, ",") == (strlen($query_updateC) - 1))
		$query_updateC = substr($query_updateC, 0, strlen($query_updateC) - 1);
		$query_updateC .= " where id = " . $_POST['id'] . ";";
		$result = mysqli_query($link, $query_updateC);
		if (!$result)
		$flag_error = 1;
		else {
			$queryH = $queryh . $_POST['id'] . "," . $userId . "," . $groupId . ",NOW(),'" . $modifiedFrom . "','" . $modifiedTo . "','MbCharacter')";
			$result = mysqli_query($link, $queryH);
			if (!$result)
			$flag_error = 1;
			$modifiedFrom = "";
			$modifiedTo = "";
		}
		$flag = 0;
	}

	//update base object for states
	for ($i = 0; $i < $_POST['state_count']; $i++) {
		$query_updateBO = "Update BaseObject set";
		if ($_POST['stateTitle_old' . $i] != $_POST['stateTitle' . $i]) {
			$query_updateBO .= " name = '" . $_POST['stateTitle' . $i] . "',";
			$modifiedFrom .= " name: " . trim($_POST['stateTitle_old.$i']);
			$modifiedTo .= " name: " . trim($_POST['stateTitle.$i']);
			$flag = 1;
		}

		if ($_POST['stateDescription_old' . $i] != $_POST['stateDescription' . $i]) {
			$query_updateBO .= " description = '" . $_POST['stateDescription' . $i] . "',";
			$modifiedFrom .= " description: " . trim($_POST['stateDescription_old' . $i]);
			$modifiedTo .= " description: " . trim($_POST['stateDescription' . $i]);
			$flag = 1;
		}

		if ($flag_date == 1) {
			$query_updateBO .= " dateToPublish = '" . $_POST['dateToPublish'] . "',";
			$modifiedFrom .= " dateToPublish: " . trim($_POST['dateToPublish_old']);
			$modifiedTo .= " dateToPublish: " . trim($_POST['dateToPublish']);
			$flag = 1;
		}
		if ($flag == 1) {
			if (strrpos($query_updateBO, ",") == (strlen($query_updateBO) - 1))
			$query_updateBO = substr($query_updateBO, 0, strlen($query_updateBO) - 1);
			$query_updateBO .= " where id = " . $_POST['stateId' . $i] . ";";

			//echo $query_updateBO;
			$result = mysqli_query($link, $query_updateBO);
			if (!$result)
			$flag_error = 1;
			else {

				$queryH = $queryh . $_POST['stateId' . $i] . "," . $userId . "," . $groupId . ",NOW(),'" . $modifiedFrom . "','" . $modifiedTo . "','BaseObject')";
				$result = mysqli_query($link, $queryH);
				if (!$result)
				$flag_error = 1;
				$modifiedFrom = "";
				$modifiedTo = "";

				$sql = 'UPDATE CollectionObjects SET objectTitle ="'.trim($_POST['stateTitle'.$i]).'" WHERE objectId='.$_POST['stateId'.$i].' AND collectionId='.$_POST['id'];
				//echo $sql;
				//exit(0);
				mysqli_query($link, $sql) or die(mysqli_error($link.' '.$sql));

			}
			$flag = 0;
		}
		$query_updateS = "Update CharacterState set";
		if ($_POST['stateValue_old' . $i] != $_POST['stateValue' . $i]) {
			$query_updateS .= " charStateValue = '" . $_POST['stateValue' . $i] . "'";
			$modifiedFrom .= " charStateValue: " . trim($_POST['stateValue_old' . $i]);
			$modifiedTo .= " charStateValue: " . trim($_POST['stateValue' . $i]);
			$flag = 1;
		}
		if ($flag == 1) {
			$query_updateS .= " where id = " . $_POST['stateId' . $i] . ";";
			//echo "state query is ".$query_updateS;
			$result = mysqli_query($link, $query_updateS);
			if (!$result)
			$flag_error = 1;
			else {

				$queryH = $queryh . $_POST['stateId' . $i] . "," . $userId . "," . $groupId . ",NOW(),'" . $modifiedFrom . "','" . $modifiedTo . "','CharacterState')";
				$result = mysqli_query($link, $queryH);
				if (!$result)
				$flag_error = 1;
				$modifiedFrom = "";
				$modifiedTo = "";
			}
		}
		$query_updateBO = "";
		$query_updateS = "";
	}
	if ($flag_error != 1) {
		//update the keywords table
		updateCollectionKeywords($_POST['id']);
	}
}
$url_current .= "&fe=" . $flag_error . "&url=" . $url;
header("location:" . $url_current);
exit();
?>
