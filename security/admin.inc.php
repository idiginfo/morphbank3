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

require_once("MDB2.php");

/**
 * This routine, called by modules with privileges to modify the database  *
 *    login the function into mysql database as designated by the $database variable.
 * Ensures that global $link is an active connection
 * @return mysqii database link
 */
function Adminlogin() {
	global $link, $config;
	if (!$link || mysqli_connect_error()){
		$link = mysqli_connect(
			$config->dbHostname,
			$config->dbUsername,
			$config->dbPassword,
			$config->dbName,
			$config->dbPort
		);
		if (mysqli_connect_error()){
			print("Can not connect to MySQL server. The Administration is working on the issue. Thank you.");
			exit;
		}
	}
	mysqli_set_charset($link, $config->dbCharset);
	return $link;
}  //end function

/**
 * Sql functions for backwards compatibility
 */
$link = Adminlogin();

function runQuery($sql){
	$link = Adminlogin();
	$result = mysqli_query($link,$sql);
	return $result;
}

function freeResult($result){
	@mysqli_free_result($result);
}

function close(){
	global $link;
	mysqli_close($link);
}


function reconnect(){
	// NOTE for keyword usage, call prepareKeywordQueries after reconnecting
	global $db;
	if ($db){// not null
		$disResult = $db->disconnect();
		if (PEAR::isError($disResult)) {
			die("\n" . $disResult->getUserInfo() . "\n");
		}
	}
	return connect();
}

/**
 * Connect to database
 * Additional statements to add logging and debugging
 */
function connect(){
	global $db, $config;
	if ($db){
		$database = $db->getDatabase();
		if (!PEAR::isError($database)) {
			return $db;
		}
	}

	$dsn = array(
		'phptype'  => $config->dbDriver,
		'username' => $config->dbUsername,
		'password' => $config->dbPassword,
		'hostspec' => $config->dbHostname,
		'port'     => $config->dbPort,
		'database' => $config->dbName
	);
	
	$db = MDB2::connect($dsn);
	if (PEAR::isError($db)) {
		die("\n" . $db->getUserInfo() . "\n");
	}
	$db->loadModule('Function');
	$db->loadModule('Extended');
	$db->loadModule('Date');
	$db->setCharset($config->dbCharset);
    
	if ($config->dbDebug) {
		require_once ("explainQueryClass.php");
		$my_debug_handler = new Explain_Queries($db);
		$db->setOption('debug', 1);
		$db->setOption('log_line_break', "\n\t");
		$db->setOption(
		  'debug_handler',
		  array($my_debug_handler, 'collectInfo')
		);
		register_shutdown_function(
		  array($my_debug_handler, 'executeAndExplain')
		);
		register_shutdown_function(
		  array($my_debug_handler, 'dumpInfo')
		);
	}
	return $db;
}

/**
 * Clears multi query results after running MDB execStoredProc()
 * @param $result
 * @return void
 */
function clear_multi_query($stmt) {
	while($stmt->nextResult()) $stmt->store_result();
	return;
}

function dbAdminlogin(){
	return connect();
}

/**
 * Adds error processing and logging to database operations
 * @param $dbObject
 * @param $label Label for error
 * @param $priority Zend logging error leve
 */
function isMdb2Error($dbObject, $label=null, $priority = 4){
	if (!PEAR::isError($dbObject)) return false;
	if (false === $priority) $priority == 0;
	if (true === $priority) $priority == 1;
	errorLog($label, $dbObject->getDebugInfo(), $priority);
	return true;
}

/**
 * Error logging
 * 
 * @param $message
 * @param $priority
 */
function errorLog($label, $message = null, $priority = 4) {
	global $config, $logger, $objInfo;
	
	$priorities = array(
      0 => PEAR_LOG_EMERG,
      1 => PEAR_LOG_ALERT,
      2 => PEAR_LOG_CRIT,
      3 => PEAR_LOG_ERR,
      4 => PEAR_LOG_WARNING,
      5 => PEAR_LOG_NOTICE,
      6 => PEAR_LOG_INFO,
      7 => PEAR_LOG_DEBUG
    );
	
	$errorMsg = '';
	
	if (is_object($objInfo)){
		$errorMsg .= "\n" . 'UserId: ' . $objInfo->getUserId() . "\n";
    $errorMsg .= 'GroupId: ' . $objInfo->getUserGroupId() . "\n";
	}
	
	$message = '<pre>' . print_r($message, true) . '</pre>';
  if ($config->dbDebug) {
  	$backtrace = '<pre>' . print_r(debug_backtrace(), true) . '</pre>';
  } else {
    $deBug = debug_backtrace();
    $backtrace = 'File: ' . $deBug[1]['file'] . "\n";
    $backtrace .= 'Line: ' . $deBug[1]['line'] . "\n";
  }

	$errorMsg .= 'Page URL: ' . getPageURL() . "\n";
	$errorMsg .= 'IP: ' . getIP() . "\n";
	$errorMsg .= 'Message: ' . $message . "\n";
	$errorMsg .= 'Backtrace: ' . $backtrace . "\n";
	
	if ($config->errorLogging == 1) {
	  $logger->log($errorMsg, $priorities[$priority]);
	}
	if ($priority > $config->errorResponse) return;
	if ($config->errorRedirect) {
		$_SESSION['errMsg'] = $label;
		header("location: /error.php");
		exit;
	} else {
		echo $errorMsg;
		exit;
	}
	return;
}

/**
 * Get IP
 */
function getIP() {
	if (getenv('HTTP_CLIENT_IP')) {
		$ip = getenv('HTTP_CLIENT_IP');
	} elseif (getenv('HTTP_X_FORWARDED_FOR')) {
		$ip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif (getenv('HTTP_X_FORWARDED')) {
		$ip = getenv('HTTP_X_FORWARDED');
	} elseif (getenv('HTTP_FORWARDED_FOR')) {
		$ip = getenv('HTTP_FORWARDED_FOR');
	} elseif (getenv('HTTP_FORWARDED')) {
		$ip = getenv('HTTP_FORWARDED');
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

/**
 * Get Page url and request for error logs
 */
function getPageURL() {
	$pageURL = 'http://';
	$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].'?'.http_build_query($_REQUEST);
	return $pageURL;
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
function History($id, $userId, $groupId, $modifiedFrom, $modifiedTo, $tableName ){
	$link = Adminlogin();
	$query = "CALL HistoryInsert($id, $userId, $groupId, NOW(), \"$modifiedFrom\", \"$modifiedTo\", "
	."\"$tableName\")";
	//echo $query;
	$results = mysqli_query($link, $query) or die ('Could not run history query: '. $query . mysqli_error($link));
	if($results) {
		return 1;
	} else {
		return 0;
	}
}

/****************************************************************************************
 *                                                                                       *
 *  Routine/Module Name: recordExists                                                    *
 *                                                                                       *
 *  Parameter Description:  $id:  The column used as the primary key of Database Table   *
 *                          $idval: The value of the key of the record to be found.      *
 *                          $table: The name of the table to be searched.                *
 *                                                                                       *
 *  Description:  Determines if a record exists within a given table.                    *
 *                                                                                       *
 *  Author: David A. Gaitos, MorphBank Project Director                                  *
 *  Date: Aug 13, 2005                                                                   *
 *  Modified: December 12, 2005: Removed the nee for to reference a database.
 ****************************************************************************************/
function recordExists( $id, $idval,$table,$link) {
	$link=Adminlogin();
	$result = mysqli_query($link, "SELECT * FROM ".$table." WHERE ".$id."='".$idval."'");
	if($result) return true;
	return false;
}

/****************************************************************************************
 *                                                                                       *
 *  Routine/Module Name: getNewId                                                      *
 *                                                                                       *
 *  Parameter Description: None                                                          *
 *  Return Value: Returns a new id to be used for insertion of a new Morphbank  *
 *    object.                                                                            *
 *                                                                                       *
 *  Description: The last id used in the table is stored in a single table with a      *
 *    single column called 'id'.  To obtain a new id, we retrieve the value, add 1     *
 *    to the number and insert that number back into the table. We then return the       *
 *    new value back to the calling routine.                                             *
 *                                                                                       *
 *  Author: David A. Gaitos, MorphBank Project Director                                  *
 *  Date: Oct 11, 2005                                                                   *
 ****************************************************************************************/
function getNewId() {
	$link =  Adminlogin();
	$results = mysqli_query($link,'select MAX(id) as id from BaseObject');
	$row = mysqli_fetch_array($results);
	$id = $row['id'];
	$newid = $id + 1;
	return $newid;
}

function GetNewLSID($link) {
	return getnewId();
}


/****************************************************************************************
 *                                                                                       *
 *  Routine/Module Name: UpdateBaseObject                                                *
 *                                                                                       *
 *  Parameter Description: $id: The id of the object being update.                     *
 *                         $description: A desription of the change.                     *
 *                                                                                       *
 *  Description: The latest change in each MorphBank is tracked by the date and the      *
 *    last change made. This module updates that information.  The object should         *
 *    already exist or this routine would not be called.  Simply, this executes          *
 *    an SQL statement to update the description field in the baseObject table.          *
 *                                                                                       *
 *  Author: David A. Gaitos, MorphBank Project Director                                  *
 *  Date: Oct 13, 2005                                                                   *
 ****************************************************************************************/
function UpdateBaseObject($id,$description)
{
	Adminlogin();
	$query = 'update baseObject set ';
	$query .= 'dateLastModified = NOW(),';
	$query .= 'description = "'.$description.'" ';
	$query .= 'where id = "'.$id.'";';
	$results = mysql_query($query);
}

/****************************************************************************************
 *                                                                                       *
 *  Routine/Module Name: AddBaseObject                                                   *
 *                                                                                       *
 *  Parameter Description: $id: The new id of the object to be added.                  *
 *                         $userid: The id of the user who added the object.             *
 *                         $objecttypeid:  The name of the table where the object        *
 *                              was added.                                               *
 *                         $datetopublish:  The data to release the object. Can be null. *
 *                         &description:  A short description of the object.             *
 *                                                                                       *
 *  Description: Function adds a record to the baseObject table. It assumes that you     *
 *     have already obtained a new id and have passed it in via the parameters listed  *
 *     above.            *
 *                                                                                       *
 *  Author: David A. Gaitos, MorphBank Project Director                                  *
 *  Date: Dec 13, 2005                                                                   *
 ****************************************************************************************/
function AddBaseObject ( $id, $userid, $groupid, $objecttypeid,$datetopublish,$description,$link) {
	if(!recordExists("id",$id,"BaseObject",$link)) return false;
	//$link = Adminlogin();
	$query = "insert into BaseObject (id, userId, groupId, dateLastModified, dateCreated, dateToPublish, objectTypeId, description, submittedBy)
values (";
	$query .= "\"".$id."\",";
	$query .= "\"".$userid."\",";
	$query .= "\"".$groupid."\",";
	$query .= "NOW(), NOW(),";
	$query .= "\"".$datetopublish."\",";
	$query .= "\"".$objecttypeid."\",";
	$query .= "\"".$description."\",";
	$query .= "\"".$userid."\");";
	$results = mysqli_query($link, $query) or die(mysqli_error($link).' '.$sql);
	if($results) return true;
	return false;
}

/****************************************************************************************
 *                                                                                       *
 *  Routine/Module Name: printAnnotations                                                *
 *                                                                                       *
 *  Parameter Description:$id: Image Id                                                  *
 *                                                                                       *
 *  Description: Temporary routine to test the possibilty of displaying annotations      *
 *    in a table area.                                                                   *
 *                                                                                       *
 *  Author: David A. Gaitos, MorphBank Project Director                                  *
 *  Date: Dec 13, 2005                                                                   *
 ****************************************************************************************/
function printAnnotations($id,$link) {
	//Adminlogin();
	$results = mysql_query("select * from ImageAnnotation where imageId =".$id);
	if($results) {
		$resultcount = mysql_query("select count(8) from ImageAnnotation where imageid=".$id);
		$rowcount = mysql_fetch_row($resultcount);

		echo '<TD rowspan="10">';
		$numrows = mysql_num_rows($results);
		echo '<TABLE BORDER="1" ALIGN="CENTER"><TR><TD>'.$rowcount[0].' Related Annotations</TD></TR></TABLE>';
		echo '<div class="annotationContainer">';
		echo '<Table width="425" >';
		for($index=0; $index<$numrows; $index++) {
			$row = mysql_fetch_array($results);
			$title = $row['title'];
			$Annotationid    = $row['id'];

			$results1 = mysql_query('select * from baseObject where id ='.$Annotationid);
			$row1 = mysql_fetch_array($results1);
			$userid = $row1['userId'];
			$dateCreated = $row1['dateCreated'];
			$results2 = mysql_query('select * from User where id ='.$userid);
			$row2 = mysql_fetch_array($results2);
			$name = $row2['name'];
			echo '<TR><TD title="Select Annotation to view" border="2"><A HREF="index.php?id='.$id.'&annotationid='.$Annotationid.'">';
			echo '<B>TITLE:</B> '.$title;
			echo '<BR><B>BY:</B>'.$name.'<BR><B>DATE CREATED:</B> '.$dateCreated.'</a></TD>';
		}
		echo '</TABLE></div>';
		echo '</TR>';
	}
}

function setThumb($id, $thumbId) {
	$db = connect();
	$updateSql = "UPDATE BaseObject SET thumbURL = $thumbId WHERE id = $id";
	//echo $updateSql."\n";
	$result = $db->exec($updateSql);
	if (isMdb2Error($result,$updateSql,6)) return false;
	return true;
}
