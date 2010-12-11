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
include_once('head.inc.php');
include_once ('collectionFunctions.inc.php');


$link = AdminLogin();

$id = $_GET['id'];
$div = $_GET['div'];
$userId = $_GET['userId'];
$title = $_GET['title'];

$sql = 'SELECT *, NOW() as now FROM BaseObject WHERE id='.$id;
$result = mysqli_query($link, $sql);

if ($result) {
	$array= mysqli_fetch_array($result);
	
	if ($array['userId'] == $userId && $array['dateToPublish'] > $array['now']) {
		updateCollectionTitle($id, $title);
		updateCollectionKeywords($id);
		$divNum = substr($div, 3);
		echo '<span id="titleConfirm">'.$div.'</span>';
		echo '<span id="divNum">'.$divNum.'</span>';
		echo '<span id="title">'.$title.'</span>'; 
	}
	else
		echo '<div id="error">&nbsp;</div>';
}
else
	echo '<div id="error">&nbsp;</div>';


function updateCollectionTitle($id, $title) {
	global $link;
	$sql = 'UPDATE BaseObject SET name = \''.$title.'\' WHERE id = \''.$id.'\' ';
	mysqli_query($link, $sql);

}

?>
