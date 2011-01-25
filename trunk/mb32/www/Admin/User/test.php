<?php
/*
if (is_writable($config->userLogoPath)) {
  echo "writable";
} else {
  echo "not writable";
}
*/

if (is_dir('/images/userLogos')) {
  echo "directory exists<br />";
} else {
  echo "directory missing<br />";
}

if (is_writable('/images/userLogos')) {
  echo "writable";
} else {
  echo "not writable";
}
?>