<?php

// simply echos the following contents to the web browser.
// This helps keep the main scripts simpler to read.

function mainUpload() {
	include_once('mbUpload_data.php');
	foreach($uploadMenu as $menu) {

		echo '<div class="introNavText">';
		echo '<a class="introNav" href="'.$menu['href'].'">'.$menu['name'].'</a></div> ';
	}
	
}
?>
