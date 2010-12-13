<?php

include_once('head.inc.php');
include_once('mainBrowse.php');

/**
 * Seting the title of the main window.
 */
$title = 'Browse';
/**
 * initHtml: echo out the beginnig and the header of the Html code.
 */
initHtml($title, null, null);

/**
 * echoHead: Add the standard head section for all the Html code.
 */
echoHead(false, $title);

/**
 * mainBrowse: displays the content of the main frame.
 */
mainBrowse();

/**
 * finishHtml: echo out the end of Html code.
 */
finishHtml();
?>
