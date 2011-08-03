<?php

// lookup external id and return local id

function getIdFromExtId($extId){
	// check for morphbank id
	$comps = parse_url($extId);
	if ($comps['host']=='www.morphbank.net'){
		// morphbank url
		$query = $comps['query'];
		$path = $comps['path'];
		// sample (a) http://www.morphbank.net/12345 ?id=12345
		$intPath = intval($path);
		if ($intPath){
			return $intPath;
		}
		// sample (b) http://www.morphbank.net/?id=12345
		parse_str($query);
		$intId = intval($id);
		if(intval($intId)){
			return $intId;
		}

	}
	// check for external id
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