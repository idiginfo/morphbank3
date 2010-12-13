<?php
  
  
  
  include_once('collectionFunctions.inc.php');
  include_once('http_build_query.php');
  //echo http_build_query($_POST);
  
  $postElements = count($_POST);
  // collectionId one less
  $numObjs = $postElements - 1;
  
  /*
   echo "<br>before";
   foreach ($_POST as $k => $v) {
   echo "<br>\$_POST[$k] => $v";
   }
   */
  
  //exit;
  if ($objInfo->getUserId() == null)
      header('Location:' . $config->domain . 'Submit/');
  elseif ($objInfo->getUserGroupId() == null)
      header('Location:' . $config->domain . 'Submit/groups.php');
  else {
      $collectionIdArray = getIdArrayFromPost();
      //========================
      //Adminlogin();
      insertObjects($collectionIdArray, null, $_POST['collectionId'], $numObjs);
      header('Location:' . $config->domain . 'myCollection/index.php?collectionId=' . $_POST['collectionId']);
  }
?>
