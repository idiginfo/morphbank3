<?php
//require_once '../www/includes/baseObjectSearchClass.inc.php';
require_once(dirname(__FILE__).'/../www/includes/baseObjectSearchClass.inc.php');

$regenerator = new baseObjectSearch();
echo date("H:i:s\n");
$res &= $regenerator->RegenerateKeywords();
if (PEAR::isError($res)){
  die($res->getMessage);
}
echo date("H:i:s\n");
?>
