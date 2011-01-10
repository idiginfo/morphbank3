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

$query = isset($_GET['keywords']) ? $_GET['keywords'] : "";
$phrase = $query;

$sort1 = ($_GET['listField1'] != "") ? $_GET['listField1'] : "";
$sort2 = ($_GET['listField2'] != "") ? $_GET['listField2'] : "";
$sort3 = ($_GET['listField3'] != "") ? $_GET['listField3'] : "";

$joinArray1 = explode("_", $sort1);
$joinArray2 = explode("_", $sort2);
$joinArray3 = explode("_", $sort3);

$sort1 = $joinArray1[0];
$sort2 = $joinArray2[0];
$sort3 = $joinArray3[0];

$order1 = ($_GET['orderAsc1'] != "") ? $_GET['orderAsc1'] : "";
$order2 = ($_GET['orderAsc2'] != "") ? $_GET['orderAsc2'] : "";
$order3 = ($_GET['orderAsc3'] != "") ? $_GET['orderAsc3'] : "";
//echo $query;

$querySort = "";

if ($sort1 != "") {
	$querySort .= ' ORDER BY '.$sort1.' '.$order1;
	if ($sort2 != "") {
		$querySort .= ', '.$sort2.' '.$order2;
		if ($sort3 != "") {
			$querySort .= ', '.$sort3.' '.$order3;
		}
	}
}

// $limitBy is used in the where clause to restrict object matches
// to those that are public, belong to user, etc.
$limitBy = ' (dateToPublish<NOW() ';
if (!isLoggedIn()) {
	$limitBy .= ") ";
} else {
	$userId = $objInfo->getUserId();
	$groupId = $objInfo-> getUserGroupId();
	$groupArray = $objInfo->getGroupIdArray();
	$groupArraySize = count($groupArray);

	$limitBy .= " OR userId=$userId OR groupId=$groupId ";

	$limitBy .= ") ";

	if (isset($_GET['limit_contributor'])) {
		$limitBy .= ' AND userId='.$userId;
	}

	if (isset($_GET['limit_submitter'])) {
		$limitBy .= ' AND submittedBy='.$userId;
	}

	if (isset($_GET['limit_current']) && !isset($_GET['limit_any'])) {
		$limitBy .= ' AND groupId='.$groupId;
	}

	if (isset($_GET['limit_any'])) {
		$limitBy .= ' AND (';
		for ($i=0; $i < $groupArraySize; $i++) {
			$limitBy .= ' groupId='.$groupArray[$i].' ';
			if ($i < ($groupArraySize-1)) {
				$limitBy .= ' OR ';
			}
		}
		$limitBy .= ')';
	}
}
// create offset/page clause $limitOffset
$limitOffset = " LIMIT 20 ";
$offset = $_GET['offset'];
$page = (int) $_GET['numPerPage'];

if (isset($page)) {
	$limitOffset = " LIMIT $page ";
}

if (isset($offset) && $offset>0){
	$limitOffset .= " OFFSET $offset ";
} else {
	if ($_GET['goTo']){
		$goTo = (int) $_GET['goTo'];
		$offset = $page * ($goTo-1);
		$limitOffset .= " OFFSET $offset ";
	}
}
?>
