<?php
  global $link;
  $SelectTSN = '/style/webImages/selectIcon.png';
  echo '<div id="ddiv">';
  $TSN = "0";
  $sql = "select * from User where id=" . $userId;
  $results = mysqli_query($link, $sql);
  echo mysqli_error($link);
  $row = mysqli_fetch_array($results);
  $UserTSN = $row['primaryTSN'];
  $DeterminationData = getDeterminationData($AnnotationId);
  $typeDetAnnotation = $DeterminationData['typeDetAnnotation'];
  
  if ($typeDetAnnotation == "disagree")
      $disagree = 'selected="Selected"';
  else
      $agree = 'selected="selected"';
  
  $nonepf = 'selected="selected"';
  $not = "";
  $aff = "";
  $cd = "";
  $forsan = "";
  $near = "";
  $oflowestrank = "";
  $question = "";
  switch ($DeterminationData['prefix']) {
      case 'none':
          $nonepf = 'selected="selected"';
          break;
      case 'not':
          $not = 'selected="selected"';
          break;
      case 'aff':
          $aff = 'selected="selected"';
          break;
      case 'cf':
          $cf = 'selected="selected"';
          break;
      case 'forsan':
          $forsan = 'selected="selected"';
          break;
      case 'near':
          $near = 'selected="selected"';
          break;
      case 'of lowest rank':
          $oflowestrankpf = 'selected="selected"';
          break;
      case '?':
          $question = 'selected="selected"';
          break;
      default:
          $nonepf = 'selected="selected"';
          break;
  }
  switch ($DeterminationData['suffix']) {
      case 'none':
          $nonesu = 'selected="selected"';
          break;
      case 'sensu lato':
          $sensulato = 'selected="selected"';
          break;
      case 'sensu stricto':
          $sensustricto = 'selected="selected"';
          break;
      case 'of lowest rank':
          $oflowestrank = 'selected="selected"';
          break;
      default:
          $nonesu = 'selected="selected"';
          break;
  }
  displayRelatedAnnotations($AnnotationData['objectId'], getTsnSpecies($DeterminationData['tsnId']), $DeterminationData['prefix'], $DeterminationData['suffix']);
  echo '<H3>Determination Annotation</H3>';
  echo '<table class="topBlueBorder" width="740">';
  echo '<TR><TD Colspan=2 Align="Center"><B>Determination Data Fields</B></TD></TR>';
  echo '<TR>';
  echo '<TR>';
  echo '  <TD><B>Determination Action <span class="req">*</span></B></TD>';
  echo '  <TD colspan="2"><Select name="typeDetAnnotation" tabindex="3" onChange="togglePS(document.form1.typeDetAnnotation.value);" ';
  echo '    title="Select whether you agree, disagree, or qualify with a name above or choose new name">';
  echo '       <Option Value="agree" ' . $agree . ' >Agree - choose name above</Option>';
  echo '       <Option value="disagree" ' . $disagree . ' >Disagree - choose name above</Option>';
  echo '       <Option value="agreewq" >Qualify lowest rank - choose name above</Option>';
  echo '       <Option value="newdet" >Give different name - choose name below</Option>';
  echo '   </Select></TD>';
  echo '<TR>';
  echo '  <TD><B>New Taxon</B></TD>';
  echo '  <TD ><input type="text"  name="Determination" size="35" " tabindex="4" value="' . getTaxonName($DeterminationData['tsnId']) . '" 
                   title="Select the taxon name that best describes this specimen or collection. Replaces any selection of previous determination. ">';
  echo "  <width=100><A href=\"javascript:openPopup('" . $config->image . "Admin/TaxonSearch/index.php?tsn=" . $UserTSN . "&amp;pop=yes&amp;searchonly=0&amp;annotation=1');\" Title=\"Click to select a Taxonomic name\">";
  echo '  <img src="' . $SelectTSN . '" Border="0" alt="Select TSN"></A>';
  echo '  <input type="hidden" name="TSN" value="' . $DeterminationData['tsnId'] . '"\></td>';
  echo '</TR></TABLE>';
  
  echo '<table class="topBlueBorder" width="740">';
  echo '<TR><TD width="310px"><B>Prefix</B></td>';
  echo '<TD><Select title="Select a prefix with agreement with qualification or new taxon"  tabindex="5" name="prefix" size="1">';
  echo '   <option value="none" ' . $nonepf . '>None</option>';
  echo '   <option value="not" ' . $not . '>Not</option>';
  echo '   <option value="aff" ' . $aff . '>aff - akin to</option>';
  echo '   <option value="cf" ' . $cf . '>cf - compare with</option>';
  echo '   <option value="forsan" ' . $forsan . '>forsan - perhaps</option>';
  echo '   <option value="near" ' . $near . '>near - close to</option>';
  echo '   <option value="of lowest rank" ' . $oflowestrankpf . '>Of Lowest Rank</option>';
  echo '   <option value="?" ' . $question . '>? - Questionable</option>';
  echo '</select></TD></TR>';
  echo '<TR><TD><B>Suffix</B></TD>';
  echo '<TD><select name="suffix" size="1" tabindex="6" title="Select a suffix with agreement with qualification or new taxon">';
  echo '   <option value="none" ' . $nonesu . '>None</option>';
  echo '   <option value="sensu lato" ' . $sensulato . '>Sensu Lato - In the broad sense</option>';
  echo '   <option value="sensu stricto" ' . $sensustricto . '>Sensu Stricto - In the narrow sense</option>';
  echo '   <option value="of lowest rank" ' . $oflowestranksu . '>Of Lowest Rank</option>';
  echo '</select></TD></TR></Table>';
  
  echo '<TABLE class="topBlueBorder" width="740">';
  
  echo '  <TD width="310px"><B>Materials used in Id</B></TD><TD><Select name="materialsUsedInId" size="1" tabindex="7"';
  echo '    title="Identify the type of materials used in the identification">';
  $MEArray = getMaterialsExamined();
  $size = sizeof($MEArray);
  for ($i = 0; $i < $size; $i++) {
      echo '<Option value="' . $MEArray[$i] . '"';
      if ($MEArray[$i] == $DeterminationData['materialsUsedInId'])
          echo 'selected="Selected"';
      echo '>' . $MEArray[$i] . '</option>';
  }
  echo '</select></TD>';
  echo '</TR>';
  echo '<TR>';
  
  echo '  <TD><B>Source of Identification <span class="req">*</span></B></TD>';
  echo '  <TD><input type="text"  name="sourceOfId" tabindex="8" size="35" value="' . $DeterminationData['sourceOfId'] . '"
                       title="Enter the person who made the determination, defaults to user">';
  echo '</TR>';
  echo '<TR>';
  echo '  <TD><B>Resources used in Identification <span class="req">*</span></B></TD>';
  echo '  <TD><input type="text"  name="resourcesused" size="64" tabindex="9" value="' . $DeterminationData['resourcesused'] . '"
                       title="Enter the citation for literature or other resources used in identification of specimen, including expert opinion">';
  echo '</TR></TABLE>';
  echo '</div>';
?>
