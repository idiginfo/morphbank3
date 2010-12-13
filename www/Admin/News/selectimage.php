<?php
/************************************************************************************************
 *  Author: David A. Gaitros                                                                     *
 *  Date:  9/16/2005                                                                             *
 *  Description:  PhP script to display the User add screen. The form will automatically call    *
 *                the adduserscript.php upon pressing of the submit button.                      *
 *  Modified: 1/26/2006:  Include files and static path names.                                   *
 *                                                                                               *
 ************************************************************************************************/

include_once('pop.inc.php');
include_once('../admin.functions.php');

$title = 'Select Image';
initHtml($title, null, null);
echoHead(false, $title);
echo '<div class="popBackground">';
echo '<div class="maingenericcontainer" style="width:730px">';
echo '<h1>Select News Image</h1>';
$currdir = "images";
$directory = opendir($currdir);
$index = 0;
while ($file = readdir($directory)) {
	if ($file != "." && $file != ".." && $file != ".svn")
	$FileItem[$index++] = "images/" . $file;
}
$size = sizeof($FileItem);
echo '<br/><br/><br/><center><h1>Available Images in the News Image Directory</h1>';
echo '<table width="500" border="2">';
$col = 1;
echo '<tr>';
for ($index = 0; $index < $size; $index++) {
	$imagesize = getSafeImageSize($FileItem[$index]);
	//$height = $imagesize[1]/20;
	$width = 93;
	?>
<td title="Click to Select"><input type="image"
	src="<?php
      echo $FileItem[$index]
?>"
	height="<?php
      echo $height
?>" width="<?php
      echo $width
?>"
	onclick="opener.updateimage('<?php
      echo $FileItem[$index]
?>');window.close();" /></td>
<?php
if ($col > 4) {
	echo "</tr><tr>";
	$col = 1;
} else
$col++;
}

echo "</tr></table></center></div></div></body></html>";
?>
