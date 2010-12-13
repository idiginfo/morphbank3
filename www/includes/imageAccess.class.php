<?php
$DIR_DEPTH = 5; // length of directory path
$PAD_LENGTH = $DIR_DEPTH+2;
$FIRST_NEW_ACCESS_NUMBER = 1000000;
$FIRST_NEW_ID = 473891;
//TODO create class for managing image access including paths, etc.
class ImageAccess {
	private $accessNum;
	private $id;
	private $url;
	private $path;
}
?>
