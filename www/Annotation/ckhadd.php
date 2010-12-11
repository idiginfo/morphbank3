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
include_once('pop.inc.php');
include_once('gettables.inc.php');
include_once('imageFunctions.php');

$includeJavaScript = array('global.js', 'determination.js');

$title = 'Add Annotation';
initHtml($title, null, $includeJavaScript);
echoHead(false, $title);

$link = Adminlogin();

$userId = $_SESSION['userId'];
$groupId = $_SESSION['groupId'];

$imageId = trim($_GET['id']);
$_SESSION['imageId'] = $imageId;

$imgUrl = getObjectImageUrl($imageId, 'jpeg');
list($width, $height) = getDBImageSize($imageId);
//TODO get rid of size calculation
//echo "HW $width, $height<br/>";
if ($width>0){
	$factor = 680.0 / $width;
} else {
	$factor = 1;
}
// Open image to 680 pixels wide
$rwidth = (integer)($width * $factor);
$rheight = (integer)($height * $factor);
if ($rwidth == 0) $rwidth = 680;
if ($rheight == 0) $rheight = 680;

$PublishDate = date('Y/m/d', (mktime(0, 0, 0, date("m") + 6, date("d") - 1, date("Y"))));

echo '<img id="marker" name="marker" alt="" src="" onClick="placeArrow(event, \'animage\', ' . $rwidth . ', ' . $rheight . ');" />';
//container definitions are found in mb2_pop.css; button definitions are in mb2_button.css
echo '<div class="mainGenericContainer" style="width:700px;" >';
echo '<img id="animage" src ="' . $imgUrl . '" alt = "Morphbank Image" border="3" style="display:visible;"'
.' height="' . $rheight . '" width="' . $rwidth . '" onClick="placeArrow(event, \'animage\', ' 
. $rwidth . ', ' . $rheight . ');" border = "1">';
echo '<br />';

echo '<br /><h3>Markup Options</h4>';
echo '<div class="popInnerContainer ">';
echo '<table>';
echo '<form name="form1" id="imageForm" method="post" enctype="multipart/form-data"> ';
echo '<tr><td><b>Arrow:</b></td>
<td><input type="radio" name="arrowmark" checked="checked" onclick="javascript:switchColor(event);"><img src = "../style/webImages/arrowbutton1-trans.png"></td>
<td>   <input type="radio" name="arrowmark"                      onclick="javascript:switchColor(event);"><img src = "../style/webImages/arrowbutton2-trans.png"></td>
<td> <input type="radio" name="arrowmark"                    onclick="javascript:switchColor(event);">  <img src = "../style/webImages/arrowbutton3-trans.png"></td>
<td><input type="radio" name="arrowmark"                      onclick="javascript:switchColor(event);">   <img src = "../style/webImages/arrowbutton4-trans.png"></td></tr>';
//possible modification for button to be image <td><input type="image"  img src = "../style/webImages/arrow_button_4.png"     name="arrowc"      value="3"       onclick="javascript:switchColor(event);"></td></tr>';

echo '<tr><td><b>Square:</b></td>
<td><input type="radio" name="arrowmark"                   onclick="javascript:switchColor(event);"> small</td>
   <td><input type="radio" name="arrowmark"                      onclick="javascript:switchColor(event);"> med</td>
   <td><input type="radio" name="arrowmark"                    onclick="javascript:switchColor(event);">large</td>';

echo '<tr><td><b>Color:</b></td>
<td><input type="radio" name="color" onclick="javascript:switchColor(event);">black </td>
<td><input type="radio" name="color"    onclick="javascript:switchColor(event);">white </td>
<td><input type="radio" name="color" checked="checked" onclick="javascript:switchColor(event);">red</td></tr>';
echo '</table>';
echo '<table>';
echo '  <tr><td><b>Annotation Label:</b></td>';
echo '  <td align="left"><input type="text" tabindex="2" name="annotationLabel" size="53" maxlenth="53" value=""
     title="Enter a label that will appear on the Image"></td></tr>';
echo '</table>';
echo '</table>';
echo '<br />';
echo '<input type="hidden" name="userid" value="' . $userId . '" />';
echo '<input type="hidden" name="imageid" value ="' . $imageId . '" />';
echo '<input type="hidden" name="imageArray" value="' . $imageId . '" />';
echo '<input type="hidden" name="groupid" value ="' . $groupId . '" />';
echo '<input type="hidden" name="xLocation" id="xLocation" value ="0" />';
echo '<input type="hidden" name="yLocation" id="yLocation" value ="0" />';
echo '<input type="hidden" name="arrowc" value ="0" />';
echo '</div>';
echo '<table align="right">';

echo '<tr><td><a href="javascript: updateAnnotation(); self.close()" class="button smallButton"><div>Submit</div></a></td>';
echo '<td><a href="javascript:window.close()" class="button smallButton"><div>Cancel</div></a></td>';
echo '</tr></table>';
echo '</form>';
echo '<script type="text/javascript">
function updateAnnotation(){
  var openerForm = opener.document.getElementById("frmAnnotate");
  var imageForm = document.getElementById("imageForm");
  openerForm.xLocation.value=imageForm.xLocation.value;
  openerForm.yLocation.value=imageForm.yLocation.value;
  openerForm.annotationLabel.value=imageForm.annotationLabel.value;
  openerForm.arrowc.value=imageForm.arrowc.value;
}
</script>';

echo '</body>';
echo '</html>';
?>
