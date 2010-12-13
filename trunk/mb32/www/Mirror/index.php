<?php

 /*
    File name: index.php 
    @author Neelima Jammigumpula <jammigum@scs.fsu.edu>
    @package Morphbank2
    @subpackage Mirror 
	This is the standard script that calls mainMirror function which displays the Mirror options.
*/


include_once('head.inc.php');


checkIfLogged();
groups();

if(!$objInfo->ifMirror())
	header ("location: " .$config->domain. "About");
else{

	include_once('mainMirror.php');

	// The beginnig of HTML
	$title = 'Manage Mirror Content';
	initHtml( $title, NULL, NULL);

	// Add the standard head section to all the HTML output.
	echoHead( false, $title);	

	echo '<div class="mainGenericContainer" style="width: Auto;">';

	if($_GET['code'] == 'no')
		echo 'You cannot change mirror group. <br />';
		mainMirror();

			echo '</div>';

	// Finish with end of HTML
	finishHtml();
}
?>
