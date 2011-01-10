<?php
/**
* Copyright (c) 2011 Greg Riccardi, Fredrik Ronquist.
* All rights reserved. This program and the accompanying materials
* are made available under the terms of the GNU Public License v2.0
* which accompanies this distribution, and is available at
* http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
* 
* Contributors:
*   Fredrik Ronquist - conceptual modeling and interaction design
*   Austin Mast - conceptual modeling and interaction design
*   Greg Riccardi - initial API and implementation
*   Wilfredo Blanco - initial API and implementation
*   Robert Bruhn - initial API and implementation
*   Christopher Cprek - initial API and implementation
*   David Gaitros - initial API and implementation
*   Neelima Jammigumpula - initial API and implementation
*   Karolina Maneva-Jakimoska - initial API and implementation
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

// simply echos the following contents to the web browser.
// This helps keep the main scripts simpler to read.

function mainMirror() {
  global $config;
  
  
  include_once('../Admin/admin.functions.php');
  
  global $objInfo, $link;
  
  $mirrorInfo = mysqli_fetch_array(runQuery("SELECT url, locality, admin, tsns FROM ServerInfo WHERE mirrorGroup = " .$objInfo->getUserGroupId(). ";"));
  $groupName = mysqli_fetch_array(runQuery("SELECT groupName FROM Groups WHERE id = " .$objInfo->getUserGroupId(). ";"));
  
  $tsns = explode(",", $mirrorInfo['tsns']);
?>
<form name ="mirror" method="post" action="mirror.php" enctype="multipart/form-data">
       
<input type = "hidden" name = "mirrorTsns" value = "<? echo $mirrorInfo['tsns']; ?>" />
       
<h1> Manage Mirror Content </h1><br /><br />
<table>
<tr>
  <td><b> Mirror Name: </b></td>
  <td> <? echo $groupName['groupName']; ?> </td>
</tr>
<tr>
  <td><b> Mirror URL: </b></td>
  <td> <? echo $mirrorInfo['url']; ?> </td>
</tr>
<tr>
  <td><b> Locality : </b></td>
  <td> <? echo $mirrorInfo['locality']; ?> </td>
</tr>

<? if ($objInfo->getUserId() == $mirrorInfo['admin']) { ?>

<tr>
  <td>&nbsp;</td>
  <td> TaxonId &nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp<b>/</b>&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp Name </td>
</tr>
<tr>
<td><b>Taxon Name: </b></td>	
<td><input type="text" name="DeterminationId" size="11" maxlength="20"  value = 0 onchange = "intOnly(this);" onkeyup= "intOnly(this);"onkeypress= "intOnly(this);" />
<h3>/</h3><input type="text" name="Determination" size="20" maxlength="30" value = "Life / Full Mirror" readonly="readonly" />
&nbsp;&nbsp; <a href= "javascript: pop('TSN', '<? echo $config->domain.'Admin/TaxonSearch/index.php?&amp;tsn=' .$objInfo->getGroupTSN(). '&amp;pop=yes&amp;searchonly=0'?>')"> <img src = "/style/webImages/selectIcon.png" alt = "Select Taxon "  title="Click to select Taxon Serial Number" /></a>
</td>
</tr>
<? } ?>
<tr>
<td><b>Current content : </b></td><td>&nbsp; </td>
</tr>
<tr>
<td><br /></td><td>&nbsp; </td>
</tr>
<tr>
<td><b>[Taxon Id] Taxon Name </b></td><td><b>Date</b></td><td></td>
</tr>

<? 
  foreach($tsns as $val){    
    list($tsnId, $date) = explode("|", $val);
    echo '<tr><td>['.$tsnId.'] &nbsp;&nbsp;' .GetTaxonomyName($link, $tsnId). '</td><td>' .$date.  '</td> </tr>';
  } ?>

<tr><td>&nbsp;</td></tr>
<tr>
<td>&nbsp;  </td>
<td align = "right">
<a href = "javascript: document.mirror.submit()" class="button smallButton"><div>Submit</div> </a>
<a href = "javascript: top.location = '<? echo (ereg("Mirror", $_SERVER['HTTP_REFERER'])) ? $config->domain. 'MyManager' : $_SERVER['HTTP_REFERER']; ?>';" class="button smallButton"><div>Return</div> </a>
</tr>
</table>
</form>
<script language = "JavaScript" type ="text/javascript">
  function intOnly(i) {
    if(i.value.length>0) {
      i.value = i.value.replace(/[^\d]+/g, '');
    }
  }
</script>

<?
  include_once('js/pop-update.js');
}?>
