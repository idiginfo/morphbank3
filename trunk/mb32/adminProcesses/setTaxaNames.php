<?php
require_once('../configuration/app.server.php');
echo "gogo\n";

// Define functions that perform keyword update on a single object

$db = connect();

$updateSql = "update Taxa set taxonomicNames = concat_ws(' ',?,?) where tsn=?";
$param_types = array('text','text','integer');
$updateStmt = $db->prepare($updateSql,$param_types, MDB2_PREPARE_MANIP);
//var_dump($updateStmt);
isMdb2Error($updateStmt);

$getSql = "select taxonomicNames from Taxa where tsn=?";
$param_types = array('integer');
$getStmt = $db->prepare($getSql,$param_types, array('text'));
isMdb2Error($getStmt);

setAllTaxNames("limit 50",1);

function setAllTaxNames($limit, $rate=1){
	global $db, $updateStmt,$updateSql, $getSql, $getStmt;
	
	// set tax names of Life, tsn=0
	$numRow = $updateStmt->execute(array(null,null,0));
	isMdb2Error($numRow,$updateSql);
	
	$sql = "select tsn, parent_tsn, scientificName, lft from Tree where "
	." tsn>0 and  lft>0 order by lft $limit";

	$numUpdated = 0;
	$result = $db -> query($sql);
	isMdb2Error($result,$sql);
	echo "Number to be updated ".$result->numRows()."\n";
	
	while (	$row = $result -> fetchRow()){
		list ($tsn, $parentTsn, $sciName, $lft) = $row;
		//$result = $getStmt->execute(array(1));
		$nameResult = $getStmt->execute(array($parentTsn));
		isMdb2Error($nameResult, $getSql);
		$sciNames = $nameResult->fetchOne();
		isMdb2Error($sciNames, $getSql);
		$numRows = $updateStmt->execute(array($sciNames,$sciName,$tsn));
		isMdb2Error($numRows);
		if ($numUpdated % $rate == 0){
			echo "row: $tsn, $parentTsn, $sciName, left: $lft, '$sciNames' num rows: $numRows\n";
		}
		$numUpdated ++;
	}
	echo "done?\n";
	return $numUpdated;
}

?>
