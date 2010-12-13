<?php
/**
 * File name: getCV.php
 * 
 * Retrieves uploaded CV/Resumes for new users
 * 
 * @package Morphbank2
 * @subpackage Admin User
 */

// Check if get variable set
if (!isset($_GET['cv']) || empty($_GET['cv'])) exit;

// Validate admin user and logged in
include_once 'validateUser.inc.php';
$groupId = $objInfo->getUserGroupId();
if ($groupId !== $config->adminGroup) {
	echo "Permission denied";
	exit;
}

// retrieve file and download contents
$file = $config->cvFolder . $_GET['cv'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimetype = finfo_file($finfo, $file);
finfo_close($finfo);
header('Content-Type: '.$mimetype );
echo readfile($file);

