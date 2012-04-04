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
$locationArray = getLocationData($id);
$baseObjectArray = getBaseObjectData($id);

$className = checkForExtLinks($id) ? "topBlueBorder" : "blueBorder";
$popUrl = (isset($_GET['pop'])) ? "/Show/?pop=Yes&amp;id=" : "/?id=";

if (isset($_GET['pop'])) {
  echo '<div class="popContainer" style="width:770px">';
} else {
  echo '<div class="mainGenericContainer" style="width:770px">';
}
echo'
<h2>Locality Record: [' . $id . ']</h2>
<table class="' . $className . '" width="100%"  border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="firstColumn"  width="40%" valign="top">
      <div class="popCellPadding">';
showBaseObjectData($baseObjectArray);
echo'</div>
    </td>
    <td width="60%" valign="top">
      <table align="left" border="0">
        <tr>
          <th>Continent:</th><td>' . $locationArray['continent'] . '</td>
        </tr>
        <tr>
          <th>Water Body:</th><td>' . $locationArray['ocean'] . '</td>
        </tr>
        <tr>
          <th>Country:</th><td>' . $locationArray['country'] . '</td>
        </tr>
        <tr>
          <th>State/Province:</th><td>' . $locationArray['state'] . '</td>
        </tr>
        <tr>
          <th>County:</th><td>' . $locationArray['county'] . '</td>
        </tr>
        <tr>
          <th>Locality :</th><td>' . $locationArray['locality'] . '</td>
        </tr>
        <tr>
          <th>Latitude:</th><td>' . truncateValue($locationArray['latitude']) . '</td>
        </tr>
        <tr>
          <th>Longitude:</th><td>' . truncateValue($locationArray['longitude']) . '</td>
        </tr>
        <tr>
          <th>Precision:</th><td>' . $locationArray['coordinateprecision'] . '</td>
        </tr>';

if ($locationArray['minimumelevation'] == '' || $locationArray['maximumelevation'] == '') {
  echo '<tr>
          <th>Elevation (m):</th><td> ' . $locationArray['minimumelevation'] . '  ' . $locationArray['maximumelevation'] . ' </td>
        </tr>';
} else {
  echo '<tr>
          <th>Elevation (m):</th><td> ' . $locationArray['minimumelevation'] . ' &nbsp;-&nbsp; ' . $locationArray['maximumelevation'] . ' </td>
        </tr>';
}

if ($locationArray['minimumdepth'] == '' || $locationArray['maximumdepth'] == '') {
  echo '<tr>
          <th>Depth (m):</th><td> ' . $locationArray['minimumdepth'] . '  ' . $locationArray['maximumdepth'] . '</td>
        </tr>';
} else {
  echo '<tr>
          <th>Depth (m):</th><td> ' . $locationArray['minimumdepth'] . ' &nbsp;-&nbsp; ' . $locationArray['maximumdepth'] . '</td>
        </tr>';
}

if ($locationArray['paleogroup'] != null || $locationArray['paleogroup'] != '') {
  echo '<tr>
          <th>Paleo group:</th><td> ' . $locationArray['paleogroup'] . ' </td>
        </tr>';
} 

if ($locationArray['paleoformation'] != null || $locationArray['paleoformation'] != '') {
  echo '<tr>
          <th>Paleo formation:</th><td> ' . $locationArray['paleoformation'] . ' </td>
        </tr>';
} 

if ($locationArray['paleomember'] != null || $locationArray['paleomember'] != '') {
  echo '<tr>
          <th>Paleo member:</th><td> ' . $locationArray['paleomember'] . ' </td>
        </tr>';
} 

if ($locationArray['paleobed'] != null || $locationArray['paleobed'] != '') {
  echo '<tr>
          <th>Paleo bed:</th><td> ' . $locationArray['paleobed'] . ' </td>
        </tr>';
} 

echo '</table>
    </td>
  </tr>
</table>';

if (checkForExtLinks($id)) {
  echo'
<table class="bottomBlueBorder" width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td>';
  showExternalLinks($id);
  echo'</td>
        </tr>
      </table>
    </td>
  </tr>
</table>';
}
echo'</div>';

function getLocationData($id) {
  $db = connect();

  $sql = "SELECT * FROM Locality  WHERE id = ?";
  $row = $db->getRow($sql, null, array($id), null, MDB2_FETCHMODE_ASSOC);
  isMdb2Error($row, "Error select locality data.");

  return $row;
}

