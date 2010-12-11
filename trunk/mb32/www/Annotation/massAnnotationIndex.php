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
if (isset($_GET['pop'])){
	include_once('pop.inc.php');
} else {
	include_once('head.inc.php');
} 

  include_once('gettables.inc.php');
  include_once('Admin/admin.functions.php');
  include_once('mainAnnotation.php');
  include_once('postItFunctions.inc.php');
  include_once('CanAnnotate.inc.php');
  
  
  checkIfLogged();
  
  // The beginnig of HTML
  $title = 'Mass Annotation';
  initHtml($title, null, null);
  
  // Add the standard head section to all the HTML output.
  echoHead(false, $title);
  setUpPostIt();
  if (isset($_POST['imageArray']))
      $imageArray = $_POST['imageArray'];
  elseif (isset($_GET['imageArray']))
      $imageArray = $_GET['imageArray'];
  else
      die;
  if (isset($_POST['collectionId']))
      $myCollectionId = $_POST['collectionId'];
  elseif (isset($_GET['collectionId']))
      $myCollectionId = $_GET['collectionId'];
  else
      $myCollectionId = null;
  
  $array = explode("-", $imageArray);
  
  if (!checkForOnlyImages($array)) {
      echo '<div class="mainGenericContainer">';
      echo '<h3>Can not mass annotate mixed collection.  Only annotation of Images is allowed.</h3>&nbsp;&nbsp;<a href="javascript: window.close();" class="button smallButton" title="Click to return to the previous screen"\><div>Close</div></a>';
      echo '<br />
  <br />
  <br />
  <br />
  <br />
  </div>';
      finishHtml();
      exit(0);
  }
  
  
  
  
  //$prevURL = massAnnotationIndex.php;  
  $prevURL = $_SERVER['HTTP_REFERER'];
  if (CanAnnotate($myCollectionId))
      mainAnnotation($imageArray, $myCollectionId, $prevURL);
  else {
      echo '<div class="mainGenericContainer">';
      DisplayError();
      echo $myCollectionId . '<br />' . $imageArray . '<br />';
      var_dump($_GET);
      echo '</div>';
      exit(0);
  }
  // Finish with end of HTML
  finishHtml();
  
  function checkForOnlyImages($array)
  {
      foreach ($array as $k => $v) {
          if (getBaseObjectTypeId($v) != "Image")
              return false;
          else
              continue;
      }
      
      return true;
  }
?>
