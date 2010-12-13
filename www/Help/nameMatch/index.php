<?php
/**
 * File name: index.php
 * script that returns the wildcard matches from mb Tree based on scientific name
 * @author Katja Seltmann moon@begoniasociety.org
 *
 *
 *
 */

include_once('head.inc.php');
include_once('form_upload.php');
include_once('textarea_name_form.php');
include_once('tsnFunctions.php');


/**
 * Seting the title of the main window. These are in defined in the head.inc.php
 */
$title = 'Query Morphbank Names';
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

main_namematch($link,  $objInfo);

/**
 * finishHtml: echo out the end of Html code.
 */
finishHtml();
?>
