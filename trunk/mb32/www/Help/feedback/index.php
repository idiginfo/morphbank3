<?php
/**
 * File name: index.php
 *
 * This is an example of DocBlock at the header of the file.
 * @package Morphbank2
 *
 *
 */


include_once('head.inc.php');


include_once('spam.php');
/**
 *
 */
include_once('mainFeedback.php');

/**
 * Seting the title of the main window.
 */
$title = 'FeedBack';
/**
 * initHtml: echo out the beginnig and the header of the Html code.
 */
initHtml( $title, NULL, NULL);

/**
 * echoHead: Add the standard head section for all the Html code.
 */
echoHead( false, $title);

/**
 * mainBrowse: displays the content of the main frame.
 */


mainFeedback();

/**
 * finishHtml: echo out the end of Html code.
 */
finishHtml();
?>
