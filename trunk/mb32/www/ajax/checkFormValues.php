<?php
/**
 * Checks values on forms via ajax.
 *
 * @package Morphbank2
 */


// Function for json_encode if PHP version not > 5.2
if (!function_exists('json_encode')) {
	function json_encode($a=false) {
		if (is_null($a)) return 'null';
		if ($a === false) return 'false';
		if ($a === true) return 'true';
		if (is_scalar($a)) {
			if (is_float($a)) {
				// Always use "." for floats.
				return floatval(str_replace(",", ".", strval($a)));
			}

			if (is_string($a)) {
				static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
				return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
			} else {
				return $a;
			}
		}
		$isList = true;
		for ($i = 0, reset($a); $i < count($a); $i++, next($a)) {
			if (key($a) !== $i) {
				$isList = false;
				break;
			}
		}
		$result = array();
		if ($isList) {
			foreach ($a as $v) $result[] = json_encode($v);
			return '[' . join(',', $result) . ']';
		} else {
			foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
			return '{' . join(',', $result) . '}';
		}
	}
}

$db = connect();

// Checks spam verification
if ($_GET['action'] == 'check_spam') {
	$id = $_GET['spamid'];
	$spamCode = strtolower(trim($_GET['spamcode']));
	$sql = "select code from Spam where id = ?";
	$code = $db->getOne($sql, null, array($id));
	if (PEAR::isError($code)) {
		echo json_encode(false);
		exit;
	}
	$data = $spamCode == strtolower($code) ? true : false;
	echo json_encode($data);
	exit;
}

// Checks if user name exists
if ($_GET['action'] == 'check_uin') {
	$sql = "select count(*) as count from User where uin = ?";
	$count = $db->getOne($sql, null, array(trim($_GET['uin'])));
	if (PEAR::isError($count)) {
		echo json_encode(false);
		exit;
	}
	$data = $count > 0 ? false : true;
	echo json_encode($data);
	exit;
}

// Checks already existing unique external reference if change is made
if ($_GET['action'] == 'check_exist_ref') {
	$checkValue = trim($_GET['refExternalId'][0]);
	$refLinkId = trim($_GET['refLinkId']);
	$sql = "select * from ExternalLinkObject where externalId = ?";
	$row = $db->getRow($sql, null, array($checkValue), null, MDB2_FETCHMODE_ASSOC);
	if (PEAR::isError($row)) {
		echo json_encode(false);
		exit;
	}
	if (empty($row)) { // empty row means nothing exists
		$data = true;
	} elseif ($row['linkid'] == $refLinkId) { // Editing same ref.
		$data = true;
	} elseif ($row['linkid'] == $refLinkId) { // chose new ref that exists already
		$data = false;
	}
	echo json_encode($data);
	exit;
}

// Checks unique external reference
if ($_GET['action'] == 'check_unique_ref') {
	$checkValue = trim($_GET['refExternalIdAdd'][0]);
	$sql = "select count(*) as count from ExternalLinkObject where externalId = ?";
	$count = $db->getOne($sql, null, array($checkValue));
	if (PEAR::isError($count)) {
		echo json_encode(false);
		exit;
	}
	$data = $count == 0 ? true : false;
	echo json_encode($data);
	exit;
}

// Checks if TSN id exists
if ($_GET['action'] == 'check_tsn') {
	$tsn = isset($_GET['tsnId']) ? trim($_GET['tsnId']) : trim($_GET['parent_tsn']);
	$sql = "select count(*) as count from Tree where tsn = ?";
	$count = $db->getOne($sql, null, array($tsn));
	if (PEAR::isError($count)) {
		echo json_encode(false);
		exit;
	}
	$data = $count > 0 ? true : false;
	echo json_encode($data);
	exit;
}

// Checks if Specimen id exists
if ($_GET['action'] == 'check_specimen') {
	$sql = "select count(*) as count from Specimen where id = ?";
	$count = $db->getOne($sql, null, array(trim($_GET['SpecimenId'])));
	if (PEAR::isError($count)) {
		echo json_encode(false);
		exit;
	}
	$data = $count > 0 ? true : false;
	echo json_encode($data);
	exit;
}

// Checks if View id exists
if ($_GET['action'] == 'check_view') {
	$sql = "select count(*) as count from View where id = ?";
	$count = $db->getOne($sql, null, array(trim($_GET['ViewId'])));
	if (PEAR::isError($count)) {
		echo json_encode(false);
		exit;
	}
	$data = $count > 0 ? true : false;
	echo json_encode($data);
	exit;
}

// Checks if Image id exists
if ($_GET['action'] == 'check_image') {
	$standardImage =trim($_GET['StandardImage']);
	$sql = "select count(*) as count from Image where id = ?";
	$count = $db->getOne($sql, null, array($standardImage));
	if (PEAR::isError($count)) {
		echo json_encode(false);
		exit;
	}
	$data = $count > 0 ? true : false;
	echo json_encode($data);
	exit;
}

// Returns autocomplete for Vernacular languages
if ($_GET['action'] == 'vernacular') {
	$term = trim($_GET['term']);
	$query = "select distinct language from Vernacular where language like ? order by language";
	$result = $db->getAll($query, null, array("$term%"), null, MDB2_FETCHMODE_ASSOC);
	if (!empty($result)) {
		foreach ($result as $data) {
			$languageArray[] = $data['language'];
		}
	}
	echo !empty($languageArray) ? json_encode($languageArray) : json_encode(array());
}

// Returns autocomplete for Country
if ($_GET['action'] == 'country') {
	$term = trim($_GET['term']);
	$query = "select description from Country where description like ?";
	$result = $db->getAll($query, null, array("$term%"), null, MDB2_FETCHMODE_ASSOC);
	if (!empty($result)) {
		foreach ($result as $data) {
			$descArray[] = $data['description'];
		}
	}
	echo !empty($descArray) ? json_encode($descArray) : json_encode(array());
}

// Returns autocomplete for Taxon author names
if ($_GET['action'] == 'taxon_author') {
	$term = trim($_GET['term']);
	$kingdom_id = trim($_GET['kingdomid']);
	$query = "select distinct taxon_author from TaxonAuthors where taxon_author like ? and kingdom_id = ? order by taxon_author";
	$result = $db->getAll($query, null, array("%$term%", $kingdom_id), null, MDB2_FETCHMODE_ASSOC);
	if (!empty($result)) {
		foreach ($result as $data) {
			$authorArray[] = $data['taxon_author'];
		}
	}
	echo !empty($authorArray) ? json_encode($authorArray) : json_encode(array());
}

// Check if publication id exists
if ($_GET['action'] == 'check_reference') {
	$id = trim($_GET['reference_id']);
	$sql = "select count(*) as count from Publication where id = ?";
	$count = $db->getOne($sql, null, array($id));
	if (PEAR::isError($count)) {
		echo json_encode(false);
		exit;
	}
	$data = $count > 0 ? true : false;
	echo json_encode($data);
	exit;
}

// Check if locality id exists
if ($_GET['action'] == 'check_locality') {
	$id = trim($_GET['LocalityId']);
	$sql = "select count(*) as count from Locality where id = ?";
	$count = $db->getOne($sql, null, array($id));
	if (PEAR::isError($count)) {
		echo json_encode(false);
		exit;
	}
	$data = $count > 0 ? true : false;
	echo json_encode($data);
	exit;
}

// Check if group name exists
if ($_GET['action'] == 'check_group') {
	$id = trim($_GET['id']);
	$name = trim($_GET['groupname']);
	if (preg_match('/Enter New/i', $name)) {
		echo json_encode(false);
		exit;
	}
	$sql = "select id, groupName from Groups where groupName = ?";
	$row = $db->getRow($sql, null, array($name), null, MDB2_FETCHMODE_ASSOC);
	if (PEAR::isError($row)) {
		echo json_encode(true);
		exit;
	}
	if (empty($row)) { // empty means no name exists
		$data = true;
	} elseif ($row['id'] == $id) { // Editing same group name.
		$data = true;
	} elseif ($row['id'] != $id) { // Group name exists
		$data = false;
	}
	echo json_encode($data);
	exit;
}
