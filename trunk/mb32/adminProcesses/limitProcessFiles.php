<?php
$OPTIONAL = true;
function OPTIONAL_INIT(){
	global $config, $SELECT_LIMIT, $OUTPUT_DIR;
	// redefine base file path, limits on select and delete and dependence on TIF file
	$SELECT_LIMIT = " where b.problems not like 'orig%' limit 1 ";
	$config->processTiff = true;
	$OUTPUT_DIR = "/tmp/";
}

include "ProcessImages.php";
?>
