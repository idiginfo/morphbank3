<?php
if ($_REQUEST['pop']) {
	include_once('pop.inc.php');
} else {
	include_once('head.inc.php');
}

  include_once('mainBrowseByPublication.php');
  
  // The beginnig of HTML
  $title = 'Browse - Publications';
  initHtml($title, null, null);
  
  // Add the standard head section to all the HTML output.
  echoHead(false, $title);
  
  include_once('postItFunctions.inc.php');
  setupPostIt();
  
  // Output the content of the main frame
  mainBrowseByPublication("Publications");

  // Finish with end of HTML
  finishHtml();
?>
