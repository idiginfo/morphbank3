<?php
  
  
  
  include_once('collectionFunctions.inc.php');
  //include_once ('http_build_query.php');
  //echo http_build_query($_POST);
  
  $postElements = count($_POST);
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
      
      $newCollection = createCollection($collectionIdArray, $objInfo->getUserId(), $objInfo->getUserGroupId());
      if ($newCollection) {
          //insertObjects( NULL, $newCollection, $numObjs);
          echo '  <html>
          <head>          
          </head>
          <body onload="document.collectionForm.submit();">
        
            <form name="collectionForm" action="' . $config->domain . 'MyManager/index.php" method="get">
              <input type="hidden" name="tab" value="collectionTab" />
              <input type="hidden" name="newCollectionId" value="' . $newCollection . '" />
            </form>
          </body>
          </html>
    ';
      }
  }
?>
