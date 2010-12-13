<?php
/**
 * File name: error.php
 * @package Morphbank2
 */
include_once('head.inc.php');


/* Include javascript files */
$includeJavaScript = array('jquery.1-4-2.min.js', 'jquery.validate.min.js', 'formMethods.js');

/* Begin HTML */
$title = 'Error';
initHtml( $title, NULL, $includeJavaScript);

/* Add the standard head section to all the HTML output. */
echoHead(false, $title);
echo '<div class = "mainGenericContainer" style="width:Auto">';

// Put container content here
echo '<div class="searchError">An Error has occurred</div><br /><br />';
echo '<div class="searchError">';
echo $_SESSION['errMsg'] . "  Please contact the administration.";
echo '</div><br /><br />';

unset($_SESSION['errMsg']);

// Finish HTML
echo '</div>';
finishHtml();
