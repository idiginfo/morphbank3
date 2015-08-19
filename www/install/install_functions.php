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
include_once('Classes/UUID.php');

/**
 * Default values used to write config.ini file on install
 */
$defaultConfigValues = array(
	'PHP Settings' => array (
			'display_errors' => 0
		),
	'Database' => array (
			'dbPort'    => 3306,
			'dbDriver'  => '"mysqli"',
			'dbCharset' => '"UTF8"',
			'dbDebug'   => 0
		),
	'Error handling' => array(
			'errorLogging'  => 1,
			'errorPriority' => 4,
			'errorRedirect' => 1,
			'errorResponse' => 5,
			'errorLogFile'  => 'APPLICATION_PATH "/log/application.log"'
		),
	'Image Server' => array (
			'imgAppPath'  => 'APPLICATION_PATH "/ImageServer/"',
			'jpgSize'     => '"-resize 400X"',
			'thumbSize'   => '"-resize 90X"',
			'processTiff' => false,
			'processTpc'  => true,
			'tpcScales'   => 6
		),
	'Application Server' => array(),
	'Default images' => array (
			'imgNotFound' => '"defaultThumbNail.png"',
			'imgPrivate'  => '"defaultThumbNailNotPub.png"'
		),
	'Application Settings' => array (
			'adminGroup'                 => 2,
		    'disableSite'                => 0,
		    'isBot'                      => 0,
			'domain'                     => '"/"',
			'webPath'                    => 'APPLICATION_PATH "/www"',
			'cvFolder'                   => 'APPLICATION_PATH "/www/Admin/User/cv/"',
			'newsImagePath'              => 'APPLICATION_PATH "/www/images/newsImages/"',
			'userLogoPath'               => 'APPLICATION_PATH "/www/images/userLogos/"',
			'mirrorLogos'                => 'APPLICATION_PATH "/www/images/mirrorLogos/"',
			'numberOfNews'               => 2,
			'lightListingColor'          => '"#ffffff"',        
			'darkListingColor'           => '"#e5e5f5"',
			'lightBackground'            => '"#faebd8"',
			'notFoundImg'                => '"defaultThumbNail.png"',
			'useSimpleDisplay'           => true,
			'displayThumbsPerPage'       => 20,
			'displayThumbsPerPageSelect' => 10,
			'displayThumbsGroup'         => 11,
		)
	);
	
/**
 * Build select options for timezones
 */
function getTimezoneOptions($tz = '') {
	$regions = array(
	    'Africa' => DateTimeZone::AFRICA,
	    'America' => DateTimeZone::AMERICA,
	    'Antarctica' => DateTimeZone::ANTARCTICA,
	    'Arctic' => DateTimeZone::ARCTIC,
	    'Aisa' => DateTimeZone::ASIA,
	    'Atlantic' => DateTimeZone::ATLANTIC,
	    'Australia' => DateTimeZone::AUSTRALIA,
	    'Europe' => DateTimeZone::EUROPE,
	    'Indian' => DateTimeZone::INDIAN,
	    'Pacific' => DateTimeZone::PACIFIC
	);
	$select = '<option value="">Select</option>';
	foreach ($regions as $name => $mask) {
	    $timezones = DateTimeZone::listIdentifiers($mask);
	    foreach ($timezones as $timezone) {
	    	$selected = $timezone == $tz ? 'selected="selected"' : '';
	    	$select .= '<option '.$selected.' value="'.$timezone.'">'.$timezone.'</option>';
	    }
	}
	return $select;
}

/**
 * Function to build configuration sections in config.ini
 * @param $section
 * @param $array
 */
function buildConfigSection($section, $array) {
	$string = "\n\n;########## $section ##########\n";
	foreach ($array as $key => $value) {
		$string .= $key.' = '.$value."\n";
	}
	return $string;
}

/**
 * Return IP for checking if localhost
 */
function localIp() {
	if (getenv("HTTP_CLIENT_IP"))
		$ip = getenv("HTTP_CLIENT_IP");
	else if(getenv("HTTP_X_FORWARDED_FOR"))
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	else if(getenv("REMOTE_ADDR"))
		$ip = getenv("REMOTE_ADDR");
	else
		$ip = "UNKNOWN";
	return '127.0.0.1' === $ip ? true : false;
}

/**
 * Get connection to database
 */
function dbConnect() {
    global $db;
    
    if ($db){
		$database = $db->getDatabase();
		if (!PEAR::isError($database)) {
			return $db;
		}
	}
	
	$dsn = array(
		'phptype'  => 'mysqli',
		'username' => $_POST['db_user'],
		'password' => $_POST['db_pass'],
		'hostspec' => $_POST['db_host'],
		'port'     => $_POST['db_port'],
		'database' => $_POST['db_name']
	);
	
	$db = MDB2::connect($dsn);
	if (PEAR::isError($db)) die("Could not connect to database");
	$db->loadModule('Function');
	$db->loadModule('Extended');
	$db->loadModule('Date');
	$db->setCharset('UTF8');
	return $db;
}

/**
 * Adds function to errors from updater class
 * @param $dbObject
 * @param $label Label for error
 * @param $priority
 */
function isMdb2Error($dbObject, $label=null, $priority = 4){
	if (!PEAR::isError($dbObject)) return false;
	return $dbObject->getDebugInfo();
}

/**
 * Checks if CurrentIds table already holds information
 * @param $type
 */
function checkCurrentIds($type) {
  $db = dbConnect();
  $sql = "select type from CurrentIds where type = '$type'";
  $result = $db->query($sql);
  return $result->numRows();
}

/**
 * Insert or update CurrentIds table
 * @param $count
 * @param $min
 * @param $max
 * @param $type
 */
function setCurrentIds($count, $min, $max, $type) {
  $db = dbConnect();

  $data = array($min, $max, $type);
  $sql = ($count == 0) ? "insert into CurrentIds set minId = ?, maxId = ?, type = ?"
                          : "update CurrentIds set minId = ?, maxId = ? where type = ?";
  $stmt = $db->prepare($sql);
  if (PEAR::isError($stmt)) return $stmt->getUserInfo();
  $result = $stmt->execute($data);
  if (PEAR::isError($result)) return $result->getUserInfo();
  $stmt->free();
  return;
}

/**
 * Install application
 */
function installApp() {
	global $defaultConfigValues;
	
	// Check if directories are writable
	$writable = '';
	
	if (!is_writable('../../configuration')) $writable .= "/configuration directory is not writable.<br />";
	if (!is_writable('../../log')) $writable .= "/log directory is not writable.<br />";
	if (!is_writable('../../www/images/userLogos')) $writable .= "/www/images/userLogos directory is not writable.<br />";
    if (!is_writable('../../www/images/newsImages')) $writable .= "/www/images/newsImages directory is not writable.<br />";
	if (!is_writable('../../www/images/mirrorLogos')) $writable .= "/www/images/mirrorLogos directory is not writable.<br />";
	if (!empty($writable)) return $writable;
	
	if (!isset($_POST['submit'])) return;
	
	// check if passwords are the same
	if ($_POST['pin'] != $_POST['confirm_pin']) return "Admin passwords not the same.";
	
	// check all post values for empty values
	foreach ($_POST as $key => $value) {
		if ($key == 'db_pass' && empty($value) && localIp()) continue;
		if ($key == 'db_pass' && empty($value) && !localIp()) return "Database password required.";
		if (empty($value) && !preg_match('/mail_/', $key)) return "All fields are require.";
	}
	
	$db = dbConnect();
	
	// Open config file for writing
	$filePath = '../../configuration';
	$file = $filePath.'/config.ini';
	if (file_exists($file)) unlink($file);
	if (!is_writable($filePath)) return "Configuration directory not writable";
	if (!$fh = fopen($file, 'a')) return "Can't open /configuration/config.ini to write";
	
	// Loop through default config array and write sections
	foreach ($defaultConfigValues as $section => $array) {
		switch ($section) {
			case 'PHP Settings':
				$postArray = array (
					'timezone' => '"'.$_POST['app_timezone'].'"'
				);
				$array = array_merge($postArray, $array);
				$string = buildConfigSection($section, $array);
				fwrite($fh, $string);
				break;
			case 'Database':
				$postArray = array (
					'dbName'   => '"'.$_POST['db_name'].'"',
					'dbUsername' => '"'.$_POST['db_user'].'"',
					'dbPassword' => '"'.$_POST['db_pass'].'"',
					'dbHostname' => '"'.$_POST['db_host'].'"',
					'dbPort'     => $_POST['db_port']
				);
				$array = array_merge($postArray, $array);
				$string = buildConfigSection($section, $array);
				fwrite($fh, $string);
				break;
			case 'Error handling':
				$string = buildConfigSection($section, $array);
				fwrite($fh, $string);
				break;
			case 'Image Server':
				$postArray = array (
					'imgServer'       => '"'.$_POST['img_server'].'"',
					'imgServerUrl'    => '"http://'.$_POST['img_server'].'/"',
					'imgTmpDir'       => '"'.$_POST['img_tmp_path'].'"',
					'imgServerAltUrl' => '"http://'.$_POST['img_server'].'/"',
					'imgRootPath'     => '"'.$_POST['img_root_path'].'"',
					'imagemagik'      => '"'.$_POST['img_magik_path'].'"'
				);
				$array = array_merge($postArray, $array);
				$string = buildConfigSection($section, $array);
				fwrite($fh, $string);
				break;
			case 'Application Server':
				$postArray = array (
					'appServer'        => '"'.$_POST['app_server'].'"',
					'appServerBaseUrl' => '"http://'.$_POST['app_server'].'/"',
					'fileSource'       => '"'.$_POST['ftp_path'].'"',
				);
				$array = array_merge($postArray, $array);
				$string = buildConfigSection($section, $array);
				fwrite($fh, $string);
				break;
			case 'Default images':
				$string = buildConfigSection($section, $array);
				fwrite($fh, $string);
				break;
			case 'Application Settings':
				$postArray = array (
					'email'                 => '"'.$_POST['app_email'].'"',
					'appName'               => '"'.$_POST['app_name'].'"',
				    'welcomeMsg'            => '"'.$_POST['app_welcome'].'"',
					'mailList'              => $_POST['mail_enabled'],
					'mailListUrl'           => '"'.$_POST['mail_url'].'"',
					'mailListPasswordInput' => '"'.$_POST['mail_password_input'].'"',
					'mailListPassword'      => '"'.$_POST['mail_password'].'"'
				);
				$array = array_merge($postArray, $array);
				$string = buildConfigSection($section, $array);
				fwrite($fh, $string);
				break;
		}
	}
	fclose($fh);
	
	// Set default hostSErver in BaseObject
	$hostServer = $db->escape($_POST['app_server']);
	$result = $db->exec("ALTER TABLE `BaseObject` CHANGE `hostServer` `hostServer` VARCHAR( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '$hostServer'");
	if (PEAR::isError($result)) return $result->getUserInfo();
	
	// Set min and max ids for object and tsn
	$obj_min_id = $_POST['db_object_min_id'];
	$obj_max_id = $obj_min_id + 499999;
	$tsn_min_id = $_POST['db_tsn_min_id'];
	$tsn_max_id = $tsn_min_id + 499999;
	
	// Check CurrentIds for object and tsn
	$obj_count = checkCurrentIds('object');
	$tsn_count = checkCurrentIds('tsn');
	
	// Set CurrentIds for object
	$obj = setCurrentIds($obj_count, $obj_min_id, $obj_max_id, 'object');
	if (!empty($obj)) return $obj;
	
	// Set CurrentIds for tsn
	$tsn = setCurrentIds($tsn_count, $tsn_min_id, $tsn_max_id, 'tsn');
	if (!empty($tsn)) return $tsn;
	
	// require updater class for following operations
	require_once('../includes/updater.class.php');
	
	// Create new user for admin
    $first_name   = $_POST['first_name'];
    $last_name    = $_POST['last_name'];
    $uin          = $_POST['uin'];
    $pin          = $_POST['pin'];
    $email        = $_POST['email'];
    $affiliation  = $_POST['affiliation'];
    $country      = $_POST['country'];
    $name         = trim($first_name) . " " . trim($last_name);
    
    // Get Admin user id for default
    $groupId = 2;
	$sql = "SELECT min(userId) as userId FROM `BaseObject` WHERE groupId = $groupId";
	$userId = $db->getOne($sql);
	if (PEAR::isError($userId)) return $userId->getUserInfo();
	
	// Insert BaseObject for User
    $uuid = UUID::v4();
    $params = array($db->quote("User"), $userId, $groupId, $userId, "NOW()", $db->quote("User added"), $db->quote(NULL), $db->quote($uuid));
    $stmt = $db->executeStoredProc('CreateObject', $params);
    if (PEAR::isError($stmt)) return 'Create Object procedure for User: ' . $stmt->getUserInfo();
    $id = $stmt->fetchOne();
    while($stmt->nextResult()) $stmt->store_result();
    
    // Insert BaseObject for Groups
    $uuid = UUID::v4();
    $params = array($db->quote("Groups"), $userId, $groupId, $userId, "NOW()", $db->quote("Group added"), $db->quote(NULL), $db->quote($uuid));
    $stmt = $db->executeStoredProc('CreateObject', $params);
    if (PEAR::isError($stmt)) return 'Create Object procedure for Groups: ' . $stmt->getUserInfo();
    $group_id = $stmt->fetchOne();
    while($stmt->nextResult()) $stmt->store_result();
	
    // prepare user update to insert into User Table
    $userUpdater = new Updater($db, $id, $userId , $groupId, 'User');
    $userUpdater->addField('first_Name', $first_name, null);
    $userUpdater->addField('last_Name', $last_name, null);
    $userUpdater->addField('uin', $uin, null);
    $userUpdater->addPasswordField('pin', $pin, null);
    $userUpdater->addField('email', $email, null);
    $userUpdater->addField('status', 1, null);
    $userUpdater->addField('affiliation', $affiliation, null);
    $userUpdater->addField('privilegeTSN', 0, null); // TODO Default 0. Eventually remove
    $userUpdater->addField('primaryTSN', 0, null); // TODO Default 0. Eventually remove
    $userUpdater->addField('secondaryTSN', 0, null); // TODO Default 0. Eventually remove
    $userUpdater->addField('country', $country, null);;
    $userUpdater->addField('name', $name, null);
    $userUpdater->addField('address', $address, null);
    $userUpdater->addField("preferredGroup", $group_id, null);
    $numRows = $userUpdater->executeUpdate();
    if (is_string($numRows)) return $numRows;

    // prepare Groups update
    $userUpdater = new Updater($db, $group_id, $userId , $groupId, 'Groups');
    $userUpdater->addField('groupName', "$uin's group", null);
    $userUpdater->addField('tsn', 0,null);
    $userUpdater->addField('groupManagerId', $id, null);
    $userUpdater->addField('status', 1, null);
    $userUpdater->addField('dateCreated', $db->mdbNow(), null);
    $numRows = $userUpdater->executeUpdate();
    if (is_string($numRows)) return $numRows;
    
    // Insert user's group into UserGroup
    $data = array($id, $group_id, $userId, $db->mdbNow(), $db->mdbToday(), 'coordinator');
    $sql = "insert into UserGroup (user, groups, userId, dateCreated, dateToPublish, userGroupRole) values (?,?,?,?,?,?)";
    $stmt = $db->prepare($sql);
    $affRows = $stmt->execute($data);
    if (PEAR::isError($affRows)) return 'Create user group error:' . $affRows->getUserInfo();
    
    // Insert user as administrator into UserGroup
    $data = array($id, $groupId, $userId, $db->mdbNow(), $db->mdbToday(), 'administrator');
    $sql = "insert into UserGroup (user, groups, userId, dateCreated, dateToPublish, userGroupRole) values (?,?,?,?,?,?)";
    $stmt = $db->prepare($sql);
    $affRows = $stmt->execute($data);
    if (PEAR::isError($affRows)) return 'Create user group error:' . $affRows->getUserInfo();

	return;
}

