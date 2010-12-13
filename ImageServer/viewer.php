<?php
define('PHP_ENTRY',0);// valid Web app entry point


$id = $_REQUEST['id'];

// The beginning of HTML
$title = "Bischen Viewer for $id";
echo "<html><head><title>$title</title></head><body>";

include_once(PATH_ROOT.'bischen/viewDiv.php');

echo '</body></html>';
?>
