<?php

// lookup external id and return local id

function getIdFromExtId($extId){
	$db = connect();
	$getIdSelect = "select mbId from ExternalLinkObject where externalId=?";
	$param_types = array('text');
	$getIdStmt = $db->prepare($getIdSelect,$param_types);
	if(PEAR::isError($getIdStmt)){
		die("prepare getIdSelect\n".$getIdStmt->getUserInfo()." $getIdSelect\n");
	}
	$result = $getIdStmt->execute(array($extId));
	if(PEAR::isError($result)){
		echo "error in query".$result->getUserInfo();
	}
	$id = $result->fetchOne();
	return $id;
}