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

// TODO Need to change file names to differentiate between AdminProcesses and www/includes/
/**
 * Adds following folders to the php include path
 */
$paths = array(
    get_include_path(),
     realpath(dirname(__FILE__) . '/../www'),
     realpath(dirname(__FILE__) . '/../security'),
     realpath(dirname(__FILE__) . '/../configuration'),
     realpath(dirname(__FILE__) . '/../www/includes'),
     realpath(dirname(__FILE__) . '/../www/data'),
     realpath(dirname(__FILE__) . '/../library'),
     realpath(dirname(__FILE__) . '/../adminProcesses'),
);
set_include_path(implode(PATH_SEPARATOR, $paths));

/**
 * Define application path (ie. folder holding www, security, adminProcesses)
 */
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../'));

/**
 * Check for config.ini. Redirect to install if it does not exist
 */
if (!file_exists(APPLICATION_PATH . '/configuration/config.ini')) {
	header ("location: /install/install.php");
	exit;
}

/**
 * Set Zend autoloader istance to load classes automatically
 * Classes must be in include path or need to add namespace to getInstance()
 */
require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();

/**
 * Load default and config INI files then merge
 */
$config = new Zend_Config_Ini(APPLICATION_PATH . '/configuration/config.ini');

/**
 * Set up error logger if logging turned on
 */
if ($config->error->logging == 1) {
	$writer = new Zend_Log_Writer_Stream($config->error->logFile);
	$logger = new Zend_Log($writer);
	$filter = new Zend_Log_Filter_Priority((int) $config->error->priority);
	$logger->addFilter($filter);
}

/**
 * Normally, php settings in config.ini are handled by Zend_Application automatically
 * Any php settings added to config.ini will need to be added here
 */
ini_set('display_errors', $config->phpsettings->display_errors);
ini_set('date.timezone', $config->phpsettings->date->timezone);

/**
 * Include admin and database functions
 */
require_once('admin.inc.php');
require_once('validate.inc.php');
require_once('sessionHandler.class.php');
require_once('validateUser.inc.php');

/**
 * Add Anticrawl
 */
require_once('anticrawl/crawl.php');
