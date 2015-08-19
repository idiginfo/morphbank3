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
*   Deborah Paul - initial API and implementation implementation
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

require_once 'MDB2.php';
include_once('head.inc.php');

class baseObjectSearch {
	/* Initialize Variables */
	//TODO move this login!

	private $options = array();

	public function __construct() {
		global $db;
		$db = connect();
		//TODO check connection
	}

	public function __destruct() {}

	/**
	 *  This function uses a very simple query utilizing MyISAM's natural language text searching.
	 *  FULLTEXT indexing is used on keywords field and is automatically in BOOLEAN MODE.
	 *  Results are returned for any phrase matching in the keywords field and is automatically
	 *  ranked with relevancy and automatically restricts matches according to MyISAM's stopwords
	 *  list.
	 *
	 * @param $phrase
	 * @param $objectTypeId
	 * @param $orderBy
	 * @param $limitBy
	 * @return unknown_type
	 */
	public function getWhereClause($phrase, $objectTypeId, $limitBy){
		global $objInfo, $db, $config;
		$mode = "IN BOOLEAN MODE";
		$whereClause = " WHERE ";
		// add the objectTypeId restriction
		if (empty($objectTypeId)){
			$whereClause .= " true ";
		} else if ($objectTypeId == "all") {
			$whereClause .= "(id in (select id from  Keywords where objectTypeId!='Groups' "
			." AND objectTypeId!='News' AND objectTypeId != 'User'"
			." AND objectTypeId!='Group')) ";
		} else if ($objectTypeId=="Taxa"){
			// no $objectTypeClause, but must add dependence on status for display
			$taxonSearch = true;
		} else {
			$whereClause .= " (id in (select id from  Keywords where objectTypeId='$objectTypeId')) ";
		}

		// add the keyword match clause
		$matchPhrase = $this->getMatchPhrase($phrase);
		if (!empty($matchPhrase)){
			// get search phrase ready for inclusion
			$whereClause .= " and (MATCH (keywords) AGAINST (".$db->quote($matchPhrase)." $mode)) ";
		}

		// add the limit clause, if not in admin group
		if ($objInfo->getUserGroupId() != $config->adminGroup && $limitBy != null && $limitBy != "") { // admin group
			$whereClause .= " AND ($limitBy) ";
		}
		// add the order by clause
		$whereClause .= $orderBy;
		/* End Query definitions */
		return $whereClause;
	}

		public function getCountWhereClause($phrase, $objectTypeId, $limitBy){
		global $objInfo, $db, $config;
		$mode = "IN BOOLEAN MODE";
		$whereClause = " WHERE ";
		// add the objectTypeId restriction
		if (empty($objectTypeId)){
			$whereClause .= " true ";
		} else if ($objectTypeId == "all") {
			$whereClause .= "  objectTypeId!='Groups' "
			." AND objectTypeId!='News' AND objectTypeId != 'User'"
			." AND objectTypeId!='Group' ";
		} else if ($objectTypeId=="Taxa"){
			// no $objectTypeClause, but must add dependence on status for display
			$taxonSearch = true;
		} else {
			$whereClause .= " objectTypeId='$objectTypeId' ";
		}

		// add the keyword match clause
		$matchPhrase = $this->getMatchPhrase($phrase);
		if (!empty($matchPhrase)){
			// get search phrase ready for inclusion
			$whereClause .= " and (MATCH (keywords) AGAINST (".$db->quote($matchPhrase)." $mode)) ";
		}

		// add the limit clause, if not in admin group
		if ($objInfo->getUserGroupId() != $config->adminGroup && $limitBy != null && $limitBy != "") { // admin group
			$whereClause .= " AND ($limitBy) ";
		}
		// add the order by clause
		$whereClause .= $orderBy;
		/* End Query definitions */
		return $whereClause;
	}
	
	/**
	 * Get the number of matches and the specified number of rows
	 * @param $phrase the match string
	 * @param $objectTypeId a single value to restrict the type of objects returned
	 * @param $orderBy an "order by" clause
	 * @param $limitBy a limit clause, e.g.
	 * @param $limitOffset the limit/offset clause for MySql
	 * @param $select the select and from clauses, must include "from Keywords"
	 * 	examples include the default "select * from Keywords" and "select count(*) from Keywords"
	 * @return unknown_type
	 */
	public function countAndSearch($phrase, $objectTypeId, $orderBy, $limitBy, $limitOffset, $select = "select * from Keywords ", $countSelect = "select count(*) from Keywords") {
		$returnVals = array ('numMatches' => 0, 'search' => null);
		$countResult =& $this->count($phrase, $objectTypeId, "", $limitBy, "", $countSelect);
		if (isMdb2Error($countResult)) {
			echo "<br/>countAndSearch error: ".$countResult->getUserInfo()." \n";
		} else {
			$row = $countResult -> fetchRow();
			$returnVals['numMatches'] = (int) $row[0];
		}
		// add default order by clause when no keywords or orderBy are provided
		//if ($orderBy == "" && ($phrase == "" || $phrase == NULL) ) {
		if ($orderBy == null || $orderBy == "") {
			$orderBy = "ORDER By id DESC " ;
		}
		$returnVals['search'] = $this->search($phrase, $objectTypeId, $orderBy, $limitBy, $limitOffset, $select);
		return $returnVals;
	}

	/**
	 * Return a result set that represents the specified number of rows via keyword match
	 * @param $phrase the match string
	 * @param $objectTypeId a single value to restrict the type of objects returned
	 * @param $orderBy an "order by" clause
	 * @param $limitBy a limit clause, e.g.
	 * @param $limitOffset the limit/offset clause for MySql
	 * @param $select the select and from clauses, must include "from Keywords"
	 * 	examples include the default "select * from Keywords" and "select count(*) from Keywords"
	 * @return result set from query execution
	 */
	public function search($phrase, $objectTypeId, $orderBy, $limitBy, $limitOffset, $select = "select * from Keywords ") {
		$db = connect();
		$where = $this->getWhereClause($phrase, $objectTypeId, $limitBy);
		// default to descending by id when no keywords and no orderBy given
		$sqlSearch = $select . $where. $orderBy . $limitOffset ;
		//echo htmlentities($sqlSearch)."<br/>\n";
		return($db->query($sqlSearch));
	}

	public function count($phrase, $objectTypeId, $orderBy, $limitBy, $limitOffset, $select = "select * from Keywords ") {
		$db = connect();
		$where = $this->getCountWhereClause($phrase, $objectTypeId, $limitBy);
		// default to descending by id when no keywords and no orderBy given
		$sqlSearch = $select . $where. $orderBy . $limitOffset ;
		//echo htmlentities($sqlSearch)."<br/>\n";
		return($db->query($sqlSearch));
	}

	public function insert($id, $objectTypeId, $keywords)
	{
		$db = connect();
		// Insert a new entry into Keywords
		$SQL_insert = 'INSERT INTO Keywords (id, objectTypeId, keywords) VALUES ('
		. $db->quote($id, 'integer') . ', '
		. $db->quote($objectTypeId, 'objectTypeId') . ', '
		. $db->quote($keywords, 'keywords') . ')';
		return($db->query($SQL_insert));
	}

	/**
	 * Update and/or replace existing entry in Keywords
	 * @param $id
	 * @param $objectTypeId
	 * @param $keywords
	 * @return unknown_type
	 */
	public function update($id, $objectTypeId, $keywords) {
		$db = connect();
		$SQL_update = "UPDATE Keywords SET objectTypeId = $objectTypeId, keywords = $keywords WHERE id = $id";
		return($db->query($SQL_update));
	}

	/**
	 * Delete an existing entry in Keywords
	 * @param $id
	 * @return unknown_type
	 */
	public function delete($id) {
		$db = connect();
		$SQL_delete = "DELETE FROM Keywords WHERE id = $id";
		return($db->query($SQL_delete));
	}

	/**
	 * Append new keyword
	 * @param $id
	 * @param $keyword
	 * @return unknown_type
	 */
	public function append_keyword($id, $keyword) {
		$db = connect();
		$SQL_append_keyword = "UPDATE Keywords SET keywords = CONCAT(keywords, ' ', $keywords) WHERE id = $id";
		return($db->query($SQL_append_keyword));
	}

	/**
	 * 		 Used to regenerate keywords index from baseObject table data. MySQL procedure
	 * deletes, recreates, populates, reindexes all data in the Keywords table. Using this
	 * function will delete ANY data currently in the Keywords table.
	 * @return database result
	 */
	public function RegenerateKeywords() {
		$db = connect();
		// Query definitions
		$SQL_RegenerateKeywords = "CALL RegenerateKeywords()";
		/* Regenerate Stored Procedure  */
		return($db->exec($SQL_RegenerateKeywords));
	}

	function getMatchPhrase($phrase){
		$matchPhrase = '';
		// pattern matches quoted strings (matched quotes) and splits on white space
		$pattern = "/[\s,]*(\\\"[^\\\"]+\\\")[\s,]*|[\s,]*('[^']+')[\s,]*|[\s,]+/";
		$terms = preg_split($pattern, $phrase, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
		// add quotes onto terms as needed
		foreach($terms as $term){
			if (($term[0]=='"' && $term[strlen($term)-1]=='"')
			|| ($term[0]=="'" && $term[strlen($term)-1]=="'")){
				$matchPhrase .= "+$term ";
			} else {
				$matchPhrase .= "+$term* ";
			}
		}
		return $matchPhrase;
	}
}
?>

