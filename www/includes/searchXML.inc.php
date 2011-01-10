<?php
/**
* Copyright (c) 2011 Greg Riccardi, Fredrik Ronquist.
* All rights reserved. This program and the accompanying materials
* are made available under the terms of the GNU Public License v2.0
* which accompanies this distribution, and is available at
* http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
* 
* Contributors:
*   Fredrik Ronquist - conceptual modeling and interaction design
*   Austin Mast - conceptual modeling and interaction design
*   Greg Riccardi - initial API and implementation
*   Wilfredo Blanco - initial API and implementation
*   Robert Bruhn - initial API and implementation
*   Christopher Cprek - initial API and implementation
*   David Gaitros - initial API and implementation
*   Neelima Jammigumpula - initial API and implementation
*   Karolina Maneva-Jakimoska - initial API and implementation
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

define("BASE_TABLE_NAME", "search");
define("MAX_URL_LENGTH", "256");
define("MAX_ELEMENT_LENGTH", "256");
define("MAX_NODE_LENGTH", "256");

require_once 'MDB2.php';

class searchXML {
	/* Initialize Variables */
	private $options = array();

	private function create_table($table_name) {
		global $db;
		// SQL DEFINITIONS
		$SQL_create_table = "CREATE TABLE IF NOT EXISTS $table_name ("
		. "id MEDIUMINT, url VARCHAR("
		. MAX_URL_LENGTH
		. "), element VARCHAR("
		. MAX_ELEMENT_LENGTH
		. "), node VARCHAR("
		. MAX_NODE_LENGTH
		. ")) ENGINE = MyISAM"
		. " DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";

		// SQL EXECUTIONS
		$db->exec($SQL_create_table);
		if (PEAR::isError($db)) {
			die("create_table of searchXML.inc.php ".$db->getMessage() . "\n".$db->getUserInfo() . "\n");
		}
	}

	private function index_table($table_name) {
		global $db;
		// SQL DEFINITIONS
		$SQL_index_element = "ALTER TABLE $table_name ADD FULLTEXT (element)";
		$SQL_index_node = "ALTER TABLE $table_name ADD FULLTEXT (node)";
		$SQL_index_url = "ALTER TABLE $table_name ADD FULLTEXT (url)";
		$SQL_index_id = "ALTER TABLE $table_name ADD INDEX (id)";

		/*---- SQL EXECUTIONS ----*/
		$db->exec($SQL_index_element);
		if (PEAR::isError($db)) {
			die("index_table of searchXML.inc.php ".$db->getMessage() . "\n".$db->getUserInfo() . "\n");
		}
		$db->exec($SQL_index_node);
		if (PEAR::isError($db)) {
			die("index_table of searchXML.inc.php ".$db->getMessage() . "\n".$db->getUserInfo() . "\n");
		}
		$db->exec($SQL_index_url);
		if (PEAR::isError($db)) {
			die("index_table of searchXML.inc.php ".$db->getMessage() . "\n".$db->getUserInfo() . "\n");
		}
		$db->exec($SQL_index_id);
		if (PEAR::isError($db)) {
			die($db->getMessage() . "\n");
		}
	}

	private function insert_direct($id, $url, $element, $node) {
		global $db;
		$uncategorized_table_name = BASE_TABLE_NAME;
		$categorized_table_name = BASE_TABLE_NAME . "_" . $element;
		$this->create_table($uncategorized_table_name);
		$this->create_table($categorized_table_name);

		/*---- CHOP STRINGS INTO VALID LENGTH, PREVENT BOUND BREAKING, INCREASE SECURITY ----*/
		$url = substr($url, 0, MAX_URL_LENGTH);
		$element = substr($element, 0, MAX_ELEMENT_LENGTH);
		$node = substr($node, 0, MAX_NODE_LENGTH);

		/*---- SQL DEFINITIONS ----*/
		$SQL_insert_into_base_table = "INSERT INTO $uncategorized_table_name (id, url, element, node) VALUES (\"$id\", \"$url\", \"$element\", \"$node\")";
		$SQL_insert_into_categorized_table = "INSERT INTO $categorized_table_name (id, url, element, node) VALUES (\"$id\", \"$url\", \"$element\", \"$node\")";

		/*---- SQL EXECUTIONS ----*/
		$db->exec($SQL_insert_into_base_table);
		if (PEAR::isError($db)) {
			die($db->getMessage() . "\n");
		}
		$db->exec($SQL_insert_into_categorized_table);
		if (PEAR::isError($db)) {
			die($db->getMessage() . "\n");
		}
	}

	private function insert_into_buffer($id, $url, $element, $node) {
		global $db;
		$categorized_table_name = BASE_TABLE_NAME . "_buffer";

		/*---- CHOP STRINGS INTO VALID LENGTH, PREVENT BOUND BREAKING, INCREASE SECURITY ----*/
		$url = substr($url, 0, MAX_URL_LENGTH);
		$element = substr($element, 0, MAX_ELEMENT_LENGTH);
		$node = substr($node, 0, MAX_NODE_LENGTH);

		/*---- SQL DEFINITIONS ----*/
		$SQL_insert_into_categorized_table = "INSERT INTO $categorized_table_name (id, url, element, node) VALUES (\"$id\", \"$url\", \"$element\", \"$node\")";

		/*---- SQL EXECUTIONS ----*/
		$db->exec($SQL_insert_into_categorized_table);
		if (PEAR::isError($db)) {
			die($db->getMessage() . "\n");
		}
	}

	private function search_buffer() {
	}

	public function __construct() {
		global $db;
		/*---- Create connection ---*/
		$db =& MDB2::connect($this->dsn, $this->options);
		if (PEAR::isError($db)) {
			die($db->getMessage() . '\n');
		}
		$this->create_table(BASE_TABLE_NAME . "_buffer");
	}

	public function __destruct() {}

	public function search($dom) {
		global $db;
		/* Define arrays for Query construction */
		$table_references = array();
		$where_condition = array();

		/* Get search parameters */
		$search_params = $dom->getElementsByTagName('search');

		/* Query construction */
		foreach ($search_params as $search_param) {
			if (count($table_references) > 0) {
				array_push($table_references, $search_param->getAttribute('domain') . " USING (id)");
			} else {
				array_push($table_references, $search_param->getAttribute('domain'));
			}
			array_push($where_condition, "MATCH (" . $search_param->getAttribute('domain') . ".node) AGAINST ('" . $search_param->getAttribute('value') . "')");
		}
		$table_references_clause = implode(" INNER JOIN ", $table_references);
		$where_clause = implode(" AND ", $where_condition);

		$query = "SELECT id FROM " . $table_references_clause . " WHERE " . $where_clause;

		/* Perform search query */
		$res =& $db->query($query);
		if (PEAR::isError($res)) {
			die($res->getMessage());
		}
		while (($row = $res->fetchRow())) {
			print $row[0] . "\n";
		}
	}

	public function insert($dom) {
		/* Define stack for XML keywords */
		$keyword_stack = array();

		/* Parse XML into datatype struct */
		$parser = xml_parser_create();
		xml_parse_into_struct($parser, $dom->saveXML(), $values, $index);
		xml_parser_free($parser);

		/* Pull static ID and URL data from struct */
		$id = $values[$index['ID'][0]]['value'];
		$url = $values[$index['URL'][0]]['value'];

		/* Unroll XML struct into keyword stacks and insert into TABLES */
		foreach ($values as $array) {
			if (!(strcmp($array['type'],"complete"))) {
				$element = implode("_", $keyword_stack) . "_" . $array['tag'];
				$this->insert_into_buffer($id, $url, $element, $array['value']);
				array_pop($keyword_stack);
			} else {
				$keyword_stack[] = $array['tag'];
			}
		}
	}

	public function insert_bypass_buffer($dom) {
		/* Define stack for XML keywords */
		$keyword_stack = array();

		/* Parse XML into datatype struct */
		$parser = xml_parser_create();
		xml_parse_into_struct($parser, $dom->saveXML(), $values, $index);
		xml_parser_free($parser);

		/* Pull static ID and URL data from struct */
		$id = $values[$index['ID'][0]]['value'];
		$url = $values[$index['URL'][0]]['value'];

		/* Unroll XML struct into keyword stacks and insert into TABLES */
		foreach ($values as $array) {
			if (!(strcmp($array['type'],"complete"))) {
				$element = implode("_", $keyword_stack) . "_" . $array['tag'];
				$this->insert_direct($id, $url, $element, $array['value']);
				array_pop($keyword_stack);
			} else {
				$keyword_stack[] = $array['tag'];
			}
		}
	}

	public function delete($dom) {
	}

	public function reindex() {
		//    $this->index_table(BASE_TABLE_NAME);
	}

	public function reindex_all() {
		global $db;
		$SQL_show = "SHOW TABLES";
		$res =& $db->query($SQL_show);
		if (PEAR::isError($res)) {
			die($res->getMessage());
		}
		while (($row = $res->fetchRow())) {
			$this->index_table($row[0]);
		}
	}
}
?>

