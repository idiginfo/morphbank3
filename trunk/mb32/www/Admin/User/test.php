<?php
/*
if (is_writable($config->userLogoPath)) {
  echo "writable";
} else {
  echo "not writable";
}
*/

if (is_writable('/images/userLogos')) {
  echo "writable";
} else {
  echo "not writable";
}
?>