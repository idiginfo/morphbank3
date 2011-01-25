<?php
/*
if (is_writable($config->userLogoPath)) {
  echo "writable";
} else {
  echo "not writable";
}
*/
echo $_SERVER['DOCUMENT_ROOT'] . "<br />";
if (is_dir($config->userLogoPath)) {
  echo "directory exists<br />";
} else {
  echo "directory missing<br />";
}

if (is_writable($config->userLogoPath)) {
  echo "writable";
} else {
  exec("chmod 777 " . $config->userLogoPath);
  if (is_writable($config->userLogoPath)) {
    echo "now writable";
  } else {
    echo "still not writable";
  }
}
?>