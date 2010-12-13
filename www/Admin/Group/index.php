<?php
/**
 * File name: index.php
 * @package Morphbank2
 * @subpackage Admin Group
 * @author Robert Bruhn
 */

include_once('head.inc.php');
include_once('groupFunctions.php');
checkIfLogged();

$includeJavaScript = array('jquery.1-4-2.min.js', 'jquery-ui-1.8.min.js', 'jquery.validate.min.js', 'formMethods.js');

// The beginnig of HTML
$title = 'Group';
initHtml($title, null, $includeJavaScript);

// Add the standard head section to all the HTML output.
echoHead(false, $title);
echo '<div class="mainGenericContainer" style="width:900px">';
getIndexMsg($_GET['code']);

$groupId = $objInfo->getUserGroupId();
$userId  = $objInfo->getUserId();

/**
 * If group is admin, show search
 */
if ($groupId == $config->adminGroup) {
	$groups = searchGroup();
}

/**
 * If search not performed, show groups user belongs to
 */
if (!isset($_GET['search'])) {
	$groups = getUserGroups($userId);
}

/**
 * Display group table
 * @var unknown_type
 */
$search = isset($_GET['search']) ? true : false;
$groupTable = buildGroupTable($groups, $search);
echo $groupTable;

echo '</div>';
// Finish with end of HTML
finishHtml();
