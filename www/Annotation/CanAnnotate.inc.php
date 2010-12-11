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
  // ********************************************************************************************
  // this is the security for the annotation; should be moved to security folder.
  // *  If an object is released, in can be annotated. 
  // *  If an object is not released and owned by the user it can be annotated. 
  // *  If an object is not released and not owned by the user but belongs to the group
  // *  Otherwise it cannot be anotated. 
  // *  Note:  Individual images in a collection are not checked, only the collection object 
  // *      itself is checked.
  // ********************************************************************************************
  
  
  function CanAnnotate($objectId)
  {
      global $objInfo;
      global $link;
      $userId = $objInfo->getUserId();
      $groupId = $objInfo->getUserGroupId();
      
      $sql = "select * from BaseObject where id=" . $objectId;
      $results = mysqli_query($link, $sql);
      $row = mysqli_fetch_array($results);
      
      $sql = "select * from UserGroup where user=" . $userId . " and groups=" . $groupId;
      $results2 = mysqli_query($link, $sql);
      mysqli_error($link);
      
      if (!$results2 || !$results)
          return false;
      
      $row2 = mysqli_fetch_array($results2);
      
      $today = date("Y-m-d H:i:s");
      
      if ($row['dateToPublish'] <= $today)
          return true;
      
      if ($row['userId'] == $userId)
          return true;
      
      if ($groupId == $row['groupId'])
          return true;
      
      return false;
  }
  
  function DisplayError()
  {
      global $config;
      echo "<h3>Not Authorized to Annotate this image</h3>";
      echo "<UL>";
      echo "  <li>Either you are not the owner of the object</LI>";
      echo "  <li>You are not a member of the group authorized to annotate this object</li>";
      echo '<br/><br/>
     <table align="right">
           <tr>
           <td>
            <a href="javascript: window.close();" class="button smallButton right" title="Click to return to the previous screen"\><div>Close</div></a>
          </td>
      </tr>
    </table>';
  }
  
  function GoBackOnlyOne()
  {
      global $config;
      echo '<br/><br/><table align="right">';
      echo '<tr><td><a href="' . $config->domain . 'Annotation/annotationManager.php" class="button smallButton right" 
      title="Click to return to the previous screen"\><div>Return</div></a></td></tr></table>';
  }
  
  //removed security to check privilege tsn as we are no longer limiting what a user can annotate by taxonomic specialty 
  function checkPrivTSN($objectTSN, $userTSN, $groupTSN)
  {
      if ($privilegeTsn == $userTSN && $privilegeTsn == $groupTSN)
          return true;
      else {
          $specimenParents = getTaxonBranchArray($privilegeTsn);
          $arraysize = count($specimenParents);
          
          
          for ($i = 0; $i < $arraysize; $i++) {
              if ($specimenParents[$i]['tsn'] == $userTSN) {
                  for ($j = 0; $j < $arraysize; $j++) {
                      if ($specimenParents[$j]['tsn'] == $groupTSN) {
                          return true;
                      } else
                          continue;
                  }
                  return false;
              } else
                  continue;
          }
          return false;
      }
  }
  //end of checkPrivTSN
?>
