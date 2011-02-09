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


require_once(dirname(dirname(__FILE__)) . '/configuration/app.server.php');
$config->errorRedirect = 0;

echo date("H:i:s\n");
$startTime = time();
echo "Start time: $startTime\n";

$db = connect();

if (false){
	echo  "Reset imagesCount field\n";
	$sql = "UPDATE Tree SET imagesCount=0, myImagesCount=0";
	$result = $db->query($sql);
	if(PEAR::isError($result)){
		echo("Error in SQL query".$result->getUserInfo()." $sql\n");
		die();
	}
}

// count images
echo  "Counting images per Taxon for database ".$db->getDatabase()." \n";
$sql = "SELECT s.tsnId, COUNT(*) as imagesCount, t.myImagesCount FROM Specimen s join Image i on i.specimenId =s.id "
." join Tree t on t.tsn=s.tsnId GROUP BY tsnId, t.myImagesCount  having count(*)<>t.myImagesCount ";

echo "Sql: $sql\n";

// get all parents path for each classification
$result = $db->query($sql);
if(PEAR::isError($result)){
	echo("Error in SQL query".$result->getUserInfo()." $sql\n");
	die();
}
echo "Number of taxa with changed number of images: ".$result->numRows()."\n";

$numRows = 0;
$numImages = 0;
while ($row = $result->fetchRow()) {
	$numRows++;
	$tsn = $row[0];
	$imagesCount =$row[1];
	$myImagesCount = $row[2];
	$numImages += $imagesCount;
	$diffNumImages = $imagesCount - $myImagesCount;
	$updateMyImagesSql = "update Tree set myImagesCount=$imagesCount where tsn=$tsn";
	$numUpdated = $db->exec($updateMyImagesSql);
	if(PEAR::isError($numUpdated)){
		echo("Error in SQL query".$numUpdated->getUserInfo()." $sql\n");
		die();
	}
	$getBranchSql = "select tsn from TaxonBranch where child=$tsn order by rankid desc";
	$branchNodes = $db->query($getBranchSql);
	if(PEAR::isError($branchNodes)){
		echo("Error in SQL query".$branchNodes->getUserInfo()." $sql\n");
		die();
	}
	//echo $branchNodes->numRows()." branch nodes for $tsn with $imagesCount images\n";
	$numUpdated = 0;
	while ($branch = $branchNodes->fetchRow()){
		$node = $branch[0];
		$updateTreeSql = "UPDATE Tree SET imagesCount=imagesCount+$diffNumImages WHERE tsn=$node";
		$updateResult = $db->exec( $updateTreeSql);
		if(PEAR::isError($numUpdated)){
			echo("Error in SQL query".$numUpdated->getUserInfo()." $sql\n");
			die();
		}
		$numUpdated += $updateResult;
	}
	if ($numRows%100 == 0){
		echo "$numRows taxa $numImages images: last taxon $tsn, diff: $diffNumImages, $numUpdated in branch \n";
	}

}

//Update Life (should be part of the previus process)
$sql = "SELECT SUM(imagesCount) as total FROM Tree WHERE parent_tsn=0";
$result = $db->query($sql);
if(PEAR::isError($result)){
	echo("Error in SQL query".$result->getUserInfo()." $sql\n");
	die();
}

$row =$result->fetchRow();
$numImages = $row[0];

$sql = "UPDATE Tree SET imagesCount=myImagesCount+$numImages WHERE tsn=0";
$result1 =$db->query( $sql);
if(PEAR::isError($result1)){
	echo("Error in SQL query".$result1->getUserInfo()." $sql\n");
	die();
}

$endTime = time();
echo "End time: $endTime\n";
echo "This process took: ".($endTime-$startTime)." seconds\n";
echo date("H:i:s\n");
?>

