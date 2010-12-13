<?php
$OPTIONAL = true;
function OPTIONAL_INIT(){
	global $SELECT_LIMIT, $DELETE_LIMIT, $TIF_ERROR;
	// redefine base file path, limits on select and delete and dependence on TIF file
	$SELECT_LIMIT= " where ";
	$DELETE_LIMIT= null;
	$TIF_ERROR= null;
}

include "MissingImages.php";
?>
