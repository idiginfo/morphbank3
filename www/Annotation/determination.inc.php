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
*   Deborah Paul - initial API and implementation implementation
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

global $link;
$SelectTSN = '/style/webImages/selectIcon.png';
echo '<div id="ddiv" style="display:none;">';
$TSN = "0";
$sql = "select * from User where id=" . $userId;
$results = mysqli_query($link, $sql);
echo mysqli_error($link);
$row = mysqli_fetch_array($results);
$UserTSN = $row['primaryTSN'];

displayRelatedAnnotations($imageId);
echo '<h3>Determination Annotation</h3>';
echo '<table class="topBlueBorder" width="740">';
echo '<tr><td Colspan=2 Align="Center" Border=3><b>Determination Data Fields</b></td></tr>';
echo '<tr>';
echo '<tr>';
echo '  <td><b>Determination Action <span class="req">*</span></b></td>';
echo '  <td colspan="2"><Select name="typeDetAnnotation" tabindex="3" onChange="togglePS(document.form1.typeDetAnnotation.value);"
                   title="Select whether you agree, disagree, or qualify lowest rank or choose a new name.">';
echo '       <option Value="agree">Agree - choose name above</option>';
echo '       <option value="disagree">Disagree - choose name above</option>';
echo '       <option value="agreewq">Qualify lowest rank - choose name above</option>';
echo '       <option value="newdet" selected="Selected">Give different name - choose name below</option>';
echo '   </Select></td>';
echo '<tr>';
echo '  <td><b>New Taxon</b></td>';
echo '  <td ><input type="text"  name="Determination" size="35" " tabindex="4" value="' . $qTsnName . '"
                   title="Select the taxon name that best describes this specimen or collection">';
$searchUrl = urlencode($config->domain.'Admin/TaxonSearch/index.php?&tsn='
.$UserTSN.'&searchonly=0&annotation=1');
echo "<a href=\"$searchUrl\" Title=\"Click to select a Taxon name\">\n";
echo "<img src=\"$SelectTSN\" Border=\"0\" alt=\"Select TSN\"></a>\n";
	echo '<input type="hidden" name="TSN" value="'.$TSN.'"/></td>';
	echo '</tr></table>';
	echo '<div id="prepost" style="display:blank;">';
	echo '<table class="topBlueBorder" width="740">';
	echo '<tr><td width="310px"><b>Prefix</b></td>';
	echo '<td><Select title="Select a prefix with agreement with qualification or new taxon"  tabindex="5" name="prefix" size="1">';
	echo '   <option value="none" selected="selected">None</option>';
	echo '   <option value="not">Not</option>';
	echo '   <option value="aff">aff - akin to</option>';
	echo '   <option value="cf">cf - compare with</option>';
	echo '   <option value="forsan">forsan - perhaps</option>';
	echo '   <option value="near">near - close to</option>';
	echo '   <option value="of lowest rank">Of Lowest Rank</option>';
	echo '   <option value="?">? - Questionable</option>';
	echo '</select></td></tr>';
	echo '<tr><td><b>Suffix</b></td>';
	echo '<td><select name="suffix" size="1" tabindex="6" title="Select a suffix with agreement with qualification or new taxon">';
	echo '   <option value="none" selected="selected">None</option>';
	echo '   <option value="sensu lato">Senso Latu - In the broad sense</option>';
	echo '   <option value="sensu stricto">Senso Stricto - In the narrow sense</option>';
	echo '   <option value="of lowest rank">Of Lowest Rank</option>';
	echo '</select></td></tr></Table>';
	echo '</div>';
	echo '<table class="topBlueBorder" width="740">';

	echo '  <td width="310px"><b>Materials used in Id</b></td><td><Select name="materialsUsedInId" size="1" tabindex="7" title="Identify the type of materials used in the identification">';
	$MEArray = getMaterialsExamined();
	$size = sizeof($MEArray);
	for ($i = 0; $i < $size; $i++)
	echo '<option value="' . $MEArray[$i] . '">' . $MEArray[$i] . '</option>';
	echo '</select></td>';
	echo '</tr>';
	echo '<tr>';

	echo '  <td><b>Source of Identification <span class="req">*</span></b></td>';
	echo '  <td><input type="text"  name="sourceOfId" tabindex="8" size="35" value="' . $userName . '"
                       title="Enter the person who made the determination, defaults to user">';
	echo '</tr>';
	echo '<tr>';
	echo '  <td><b>Resources used in Identification <span class="req">*</span></b></td>';
	echo '  <td><input type="text"  name="resourcesused" size="64" tabindex="9"
                       title="Enter the citations for literature or other resouces used in identification of specimen, including expert opinion">';
	echo '</tr></table>';
	echo '</div>';
