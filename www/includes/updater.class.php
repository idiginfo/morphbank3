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

class Updater {
	private $db;
	private $updateQuery;
	private $updateParams = array();
	private $historyQuery;
	private $updateHistoryStmt;
	private $modifiedFrom;
	private $modifiedTo;
	private $comma = '';
	private $id;
	private $keyField;
	private $userId;
	private $groupId;
	private $tableName;

	function __construct ($db, $id, $userId, $groupId, $tableName, $keyField = "id", $setDateModified = true){
		$this->db = $db;
		$this->updateQuery = "update `$tableName` set ";
		$this->historyQuery = "INSERT INTO History (id,userId,groupId,dateModified,modifiedFrom,modifiedTo,tableName) "
		. " VALUES(?,?,?,now(),?,?,?)";
		$this->updateHistoryStmt = $this->db->prepare($this->historyQuery, null);
		$this->id=$id;
		$this->keyField = $keyField;
		$this->userId=$userId;
		$this->groupId=$groupId;
		$this->tableName=$tableName;
	}

	/**
	 * Add a field to the update query and modified to/from is new and old values differ
	 *
	 * @param string $fieldName
	 * @param string $fieldValue
	 * @param string $prevFieldValue
	 */
	function addField($fieldName, $fieldValue, $prevFieldValue){
		$value = trim($fieldValue);
		//echo "Add field $fieldName old: $prevFieldValue new: $value<br/>\n";

		if ($value !== $prevFieldValue) {
			$this->updateQuery .= $this->comma . "`" . $fieldName . "` = ?";
			$this->comma = ',';
			$this->updateParams[] = $value;
			$this->modifiedFrom .= " $fieldName: ".$this->db->escape($prevFieldValue);
			$this->modifiedTo .= " $fieldName: " . $this->db->escape($value);
		}
	}
	
	function addPasswordField($fieldName, $fieldValue){
		$value = trim($fieldValue);
		if (!empty($value)){
			$this->updateQuery .= $this->comma . $fieldName."=password(?)";
			$this->comma = ',';
			$this->updateParams[] = $value;
			$this->modifiedFrom .= " $fieldName: unknown";
			$this->modifiedTo .= " $fieldName: unknown";
			
		}
	}
	
	function addFields($fieldNames, $fieldValues, $prevFieldValues){
		foreach ($fieldNames as $fieldName){
			$val = $fieldValues[$fieldName];
			if (empty($val)) $val = $fieldValues[strtolower($fieldName)];
			$this->addField($fieldName, $fieldValues[$fieldName], $prevFieldValues[$fieldName]);
		}
	}

	function executeUpdate(){

		if (empty($this->modifiedFrom)) return 0;

		$this->updateQuery .= " where " . $this->keyField . " = ?";
		$this->updateParams[] = $this->id;

		// prepare the update
		$updateStmt = $this->db->prepare($this->updateQuery, null);
		isMdb2Error($updateStmt, $this->updateQuery);

		// execute the update
		$numUpdated = $updateStmt->execute($this->updateParams);
		isMdb2Error($numUpdated, $this->updateQuery);
		
		if ($numUpdated == 1 && $setDateModified) {
			//update date modified
			$setModified = "update BaseObject set datelastmodified=now() where id=".$this->id;
			$result = $this->db->exec($setModified);
			isMdb2Error($result,$this->$setModified);
	
			// update history
			$historyParams = array($this->id, $this->userId, $this->groupId, $this->modifiedFrom,
			$this->modifiedTo, $this->tableName );
			$numUpdated = $this->updateHistoryStmt->execute($historyParams);
			isMdb2Error($numUpdated,$this->updateQuery);
		}
		return $numUpdated;
	}

	/**
	 * This routine adds data to the History table which tracks the changes
	 *  made by the user.
	 * @param $id primary key in History table and foreign key to BaseObject id
	 * @param $userId The id of the user who added the object
	 * @param $groupId
	 * @param $modifiedFrom
	 * @param $modifiedTo
	 * @param $tableName
	 * @return unknown_type
	 */
	function updateHistory( ){
		$params = array($this->x);
		$results = mysqli_query($link, $query) or die ('Could not run history query: '. $query . mysqli_error($link));
		if($results) {
			return 1;
		} else {
			return 0;
		}
	}

	function getQuery(){
		return $this->updateQuery;
	}

	function getParams(){
		return $this->params;
	}
}
