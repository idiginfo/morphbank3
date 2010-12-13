<?php


function getSpamCode($val = null) {
	$id = empty($val) ? rand(1, 20) : $val; 
	$db = connect();
	$sql = "SELECT * FROM Spam WHERE id = $id";
	$result = $db->getRow($sql, null, null, null, MDB2_FETCHMODE_ASSOC);
	return $result ? $result : false;
}

?>
