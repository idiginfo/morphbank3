<?php

 /*
    File name: index.php 
    @author Neelima Jammigumpula <jammigum@scs.fsu.edu>
    @package Morphbank2
    @subpackage Submit
	    @subpackage Upload 
	This is the standard script that calls mainUpload function which displays the options for Upload Module.
*/



include_once('head.inc.php');

//checkIfLogged();
groups();

include_once('mainUpload.php');

// The beginnig of HTML
$title = 'Upload';
initHtml( $title, NULL, NULL);

// Add the standard head section to all the HTML output.
echoHead( false, $title);	
echo '<div class="mainGenericContainer" style="width:600px ">';

mainUpload();

echo '</div>';

// Finish with end of HTML
finishHtml();
?>
