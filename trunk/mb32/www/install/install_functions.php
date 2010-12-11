#-------------------------------------------------------------------------------
# Copyright (c) 2010 Greg Riccardi, Fredrik Ronquist.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Public License v2.0
# which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# 
# Contributors:
#   Fredrik Ronquist - conceptual modeling and interaction design
#   Austin Mast - conceptual modeling and interaction design
#   Greg Riccardi - initial API and implementation
#   Wilfredo Blanco - initial API and implementation
#   Robert Bruhn - initial API and implementation
#   Christopher Cprek - initial API and implementation
#   David Gaitros - initial API and implementation
#   Neelima Jammigumpula - initial API and implementation
#   Karolina Maneva-Jakimoska - initial API and implementation
#   Katja Seltmann - initial API and implementation
#   Stephen Winner - initial API and implementation
#-------------------------------------------------------------------------------
<?php
/**
 * Require MDB2
 */
require_once("MDB2.php");

/**
 * Default values used to write config.ini file on install
 */
$defaultConfigValues = array(
	'PHP Settings' => array (
			'phpsettings.display_errors' => 0
		),
	'Database' => array (
			'dsn.param.port'    => 3306,
			'dsn.param.driver'  => '"mysqli"',
			'dsn.param.charset' => '"UTF8"',
			'dsn.param.debug'   => 0
		),
	'Error handling' => array(
			'error.logging'  => 1,
			'error.priority' => 4,
			'error.redirect' => 1,
			'error.response' => 5,
			'error.logFile'  => 'APPLICATION_PATH "/log/application.log"'
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
	    'Aisa' => DateTimeZone::ASIA,
	    'Atlantic' => DateTimeZone::ATLANTIC,
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
	$dsn = array(
		'phptype'  => 'mysqli',
		'username' => $_POST['db_user'],
		'password' => $_POST['db_pass'],
		'hostspec' => $_POST['db_host'],
		'port'     => $_POST['db_port'],
		'database' => $_POST['db_name']
	);
	
	$db = MDB2::connect($dsn);
	if (PEAR::isError($db)) return $db;
	$db->loadModule('Function');
	$db->loadModule('Extended');
	$db->loadModule('Date');
	$db->setCharset('UTF8');
	return $db;
}

/**
 * Install application
 */
function installApp() {
	global $defaultConfigValues;
	
	if (!isset($_POST['submit'])) return;
	
	// check all post values
	foreach ($_POST as $key => $value) {
		if ($key == 'db_pass' && empty($value) && localIp()) continue;
		if ($key == 'db_pass' && empty($value) && !localIp()) return "Database password required.";
		if (empty($value) && !preg_match('/mail_/', $key)) return "All fields are require.";
	}
	
	$db = dbConnect();
	if (PEAR::isError($db)) {
		return $db->getUserInfo();
	}
	
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
					'phpsettings.date.timezone' => '"'.$_POST['app_timezone'].'"'
				);
				$array = array_merge($postArray, $array);
				$string = buildConfigSection($section, $array);
				fwrite($fh, $string);
				break;
			case 'Database':
				$postArray = array (
					'dsn.param.dbname'   => '"'.$_POST['db_name'].'"',
					'dsn.param.username' => '"'.$_POST['db_user'].'"',
					'dsn.param.password' => '"'.$_POST['db_pass'].'"',
					'dsn.param.hostname' => '"'.$_POST['db_host'].'"',
					'dsn.param.port'     => $_POST['db_port']
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
	if (PEAR::isError($result)) {
		return $result->getUserInfo();
	}
	return;
}

