<?php
/**
 * Deletes TaxonConcept given a range of BaseObject ids.
 * Primarily used during tests for the taxon upload script.
 * 
 * Example:  [cli $] php deleteTaxonConcept.php 2000000 2000007
 */
require_once(dirname(dirname(__FILE__)) . '/configuration/app.server.php');
if (empty($argv[1]) || empty($argv[2])) die("Error: BaseObject Id range must be given.\n");
$min = $argv[1];
$max = $argv[2];


$db = connect();

$params = array($min, $max);
$sql = "select id from BaseObject where objectTypeId = 'TaxonConcept' and id between ? and ? order by id desc";
$results = $db->getAll($sql, null, $params, null, MDB2_FETCHMODE_OBJECT);
if (PEAR::isError($results)) die("Error retrieving BaseObject id range.\n");

if (empty($results)) die ("No data returned from BaseObject for id range $min - $max.\n");

foreach ($results as $row) {
  $sql = "select tsn from TaxonConcept where id = ?";
  $tsn = $db->getOne($sql, array('integer'), array($row->id));
  if (PEAR::isError($tsn)) die("Error retreiving tsn for TaxonConcept id $row->id.\n");
  
  $db->beginTransaction();
  $db->query("SET foreign_key_checks = 0");
  $error = FALSE;
  
  $result = $db->query("delete from TaxonConcept where id = $row->id");
  if (PEAR::isError($result)) {
    $error = TRUE;
    echo "Error while deleting id $row->id from TaxonConcept.\n";
    echo (__LINE__.$result->getUserInfo());
  }
  
  $result = $db->query("delete from Taxa where tsn = $tsn");
  if (PEAR::isError($result)) {
    $error = TRUE;
    echo "Error while deleting $tsn from Taxa.\n";
    echo (__LINE__.$result->getUserInfo());
  }
  
  $result = $db->query("delete from Tree where tsn = $tsn");
  if (PEAR::isError($result)) {
    $error = TRUE;
    echo "Error while deleting $tsn from Tree.\n";
    echo (__LINE__.$result->getUserInfo());
  }
  
  $result = $db->query("delete from BaseObject where id = $row->id");
  if (PEAR::isError($result)) {
    $error = TRUE;
    echo "Error while deleting id $row->id from BaseObject.\n";
    echo (__LINE__.$result->getUserInfo());
  }
  
  $result = $db->query("delete from ExternalLinkObject where mbid = $row->id");
  if (PEAR::isError($result)) {
    $error = TRUE;
    echo "Error while deleting mbid $row->id from ExternalLinkObject.\n";
    echo (__LINE__.$result->getUserInfo());
  }
  
  if ($error) {
    $db->rollback();
    die("Transaction rolled back.\n");
  } else {
    $db->commit();
    echo "TaxonConcept id $row->id deleted successfully.\n";
  }
  $db->query("SET foreign_key_checks = 1");
}
echo "Deletion of TaxonConcept range $min to $max completed.\n";