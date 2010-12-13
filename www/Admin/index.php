<?php

include_once('head.inc.php');
include_once('showFunctions.inc.php');
include_once('mbAdmin_data.php');

// The beginnig of HTML
$title = 'Admin Modules';
initHtml($title, null, null);

// Add the standard head section to all the HTML output.
echoHead(false, $title);
echo '<div class="mainGenericContainer" style="width:600px">';

if (!checkAuthorization($config->adminGroup, null, null, 'edit')) {
	echo getNonAuthMessage(getNonAuthCode()) . "<br /><br />";
} else {
	// Output the content of the main frame
	foreach ($adminMenu as $menu) {
		echo '<div class="introNavText">';
		echo '<a class="introNav" href="' . $menu['href'] . '">' . $menu['name'] . '</a></div>';
	}
}
echo '</div>';
// Finish with end of HTML
finishHtml();
?>
