#-------------------------------------------------------------------------------
# Copyright (c) 2010 Greg Riccardi, Fredrik Ronquist.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Public License v2.0
# which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# 
# Contributors:
#   Fredrik Ronquist - conceptual modeling and interaction design
#   Austin Mast - conceptual modeling and interaction design
#   Greg Riccardi - initial API and implementation
#   Wilfredo Blanco - initial API and implementation
#   Robert Bruhn - initial API and implementation
#   Christopher Cprek - initial API and implementation
#   David Gaitros - initial API and implementation
#   Neelima Jammigumpula - initial API and implementation
#   Karolina Maneva-Jakimoska - initial API and implementation
#   Katja Seltmann - initial API and implementation
#   Stephen Winner - initial API and implementation
#-------------------------------------------------------------------------------
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
