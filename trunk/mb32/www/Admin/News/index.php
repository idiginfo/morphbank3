<?php
/**
 * File name: index.php
 * @package Morphbank2
 * @subpackage Admin News
 */
include_once('head.inc.php');
include_once('Admin/admin.functions.php');
include_once('mainNews.php');

// Check authorization
if (!isAdministrator()) {
	header("location: /Admin/User/edit");
}

$includeJavaScript = array('jquery.1-4-2.min.js', 'jquery-ui-1.8.min.js', 'jquery.validate.min.js', 'formMethods.js');

// The beginnig of HTML
$title = 'News';
initHtml($title, null, $includeJavaScript);

// Add the standard head section to all the HTML output.
echoHead(false, $title);

echo '<div class="mainGenericContainer" style="width:700px">';
// Output the content of the main frame
mainNews();

echo '</div>';

// Finish with end of HTML
finishHtml();
