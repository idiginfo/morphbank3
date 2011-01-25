<?php
if (is_writable($config->userLogoPath)) {
  echo "writable";
} else {
  echo "not writable";
}
?>