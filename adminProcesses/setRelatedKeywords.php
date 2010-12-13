<?php
require_once('../configuration/app.server.php');
echo("Updating keywords for modified objects\n");
//echo phpinfo();
// Pear class for handling database connection
echo "Time at start of setRelatedKeywords: ".date("H:i:s")."\n";

include_once("updateObjectKeywords.php");
// update the list of modified ids

// OPTIONAL fields
$SELECT_LIMIT = " and objecttypeid!='Collection' and "
."(id in (select collectionId from CollectionObjects)"
." or id in (select mbid from ExternalLinkObject)"
." or id in (select objectId from Annotation ))"
." order by id limit 300 ";
$SLEEP_AFTER_MKDIR = 1;

// get the id of the last image processed
$STATUS_FILE = "/data/scratch/statusRel.txt";
$objCount = 0;
$db = connect();

while (true){

	$file = @fopen($STATUS_FILE,'r');
	$lastId = @fread($file, 20);
	@fclose($file);
	if (empty($lastId)) $lastId = 0;

	$sql = "SELECT id from BaseObject where id > $lastId  $SELECT_LIMIT";
	echo "Sql: $sql\n";
	//$SQL_getModifiedIds = "SELECT id from BaseObject where id>400000 LIMIT 100";
	$updateBaseSql = "update BaseObject set keywords = concat_ws(' ',keywords,?) where id= ?";
	$updateBaseStmt = $db -> prepare ($updateBaseSql, null, MDB2_PREPARE_MANIP);
	isMdb2Error($updateBaseStmt, $updateBaseSql);
	$updateKeywordsSql = "update Keywords K join BaseObject B on K.id=B.id set K.keywords = B.keywords where B.id = ?";
	$updateKeywordsStmt = $db -> prepare ($updateKeywordsSql, array('integer'), MDB2_PREPARE_MANIP);
	isMdb2Error($updateKeywordsStmt, $updateKeywordsSql);

	$result = $db->query($sql);
	if(PEAR::isError($result)){
		echo("Error in SQL query".$result->getUserInfo()." $sql\n");
		die();
	}
	$rows = 0;
	while($row = $result->fetchRow()){
		$objCount++;
		$rows++;
		$id = $row[0];
		$keywordsText = '';
		$keywordsText .= getRelatedKeywords($id, $collectionObjectKeywordsStmt);
		$keywordsText .= getRelatedKeywords($id, $externalKeywordsStmt);
		$keywordsText .= getRelatedKeywords($id, $annotationKeywordsStmt);
		if(!empty($keywordsText)) {
			$count = $updateBaseStmt->execute(array(substr($keywordsText,0,10000),$id));
			isMdb2Error($count,"update base");
			$count = $updateKeywordsStmt->execute(array($id));
			isMdb2Error($count,"update keywords");
		}
		if (($objCount % 10) == 0){
			echo "No. objects: $objCount\tlast id: $id \t last message $message\n";
		}
	}
	if (!$rows) break;

	// refresh the status file with the last id
	$file = fopen($STATUS_FILE,'w');
	fwrite($file, $id);
	fclose($file);

	echo "Time at end of batch: ".date("H:i:s")." last id $id\n";
}

echo "Time at end of setRelatedKeywords: ".date("H:i:s")." last id $id\n";
