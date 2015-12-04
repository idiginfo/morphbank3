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

/*
 * TopNav
 */
function TopNav($rowNum, $numRows){

	

	//Row navigation icons for edit pages
	/*     echo '<hr /><table width="600">
	<tr>
	<td width="100">&nbsp;</td>
	<td><a href="index.php?row=0" ><img src="/style/webImages/goFirst2.png" alt = "First Record" title = "First Record" border="0"></a></td>';

	if ($rowNum > 0) {
	echo '<td><a href="index.php?row=' .($rowNum-1). '" ><img src="/style/webImages/backward-gnome.png" border="0"></a></td> ';
	}
	echo '<td>&nbsp;</td><td>' .($rowNum+1). '&nbsp;&nbsp;of&nbsp;&nbsp;   ' .$numRows. '</td><td>&nbsp;</td>';
	if ($rowNum < $numRows-1) {
	echo '<td><a href="index.php?row=' .($rowNum+1). '" ><img src="/style/webImages/forward-gnome.png" border="0"></a></td>';
	}
	echo '<td><a href="index.php?last=1" ><img src="/style/webImages/goLast2.png" border="0"></a></td>
	<td align = "right">
	<input type = "text" name = "rownum" size = "3" value = "' .($rowNum+1). '" onchange = "checkvalue(); document.topNav.go.click(); " /></td><td><a href = "javascript: onclick = GoToRecord(\'index.php?row=\')" class="button smallButton"><div>Go</div> </a>
	</tr>
	</table>
	<hr /><br /><br />'; */

}

/*
 * Navigation
 */
/*
function Navigation($rowNum, $numRows){
	
?>
<hr />
<table width="600">
<tr>
<td width="100">&nbsp;</td>
<td><a href="index.php?row=0" ><img src="/style/webImages/goFirst2.png" alt = "First Record" title = "First Record" border="0"></a></td>
<?php if ($rowNum > 0) { ?>
<td><a href="index.php?row=<?= $rowNum-1 ?>" ><img src="/style/webImages/backward-gnome.png" border="0"></a></td> <?php } ?>
<td>&nbsp;</td><td> <?php echo $rowNum+1; ?>&nbsp;&nbsp;of&nbsp;&nbsp;  <?php echo $numRows; ?> </td>
<td> &nbsp;</td>
<?php if ($rowNum < $numRows-1) { ?>
<td><a href="index.php?row=<?= $rowNum+1 ?>" ><img src="/style/webImages/forward-gnome.png" border="0"></a></td> <?php } ?>
<td><a href="index.php?last=1" ><img src="/style/webImages/goLast2.png" border="0"></a></td>
<td align = "right"><input type = "text" name = "rowNum" size = "3" value = "<?php echo $rowNum+1; ?>" onchange = "document.forms[0].rownum.value = document.forms[0].rowNum.value; " /></td>
<td><a href = "javascript: onclick = GoToRecord('index.php?row=')" class="button smallButton"><div>Go</div> </a></td>
</tr>
</table>
<hr />
<?php
}
*/


/*
 * Replaced by submitButton() in showFunctions.php
function Buttons($popvalue, $prevURL, $id, $objType, $relatedObj, $action){
	echo '<table width="600"><tr>';
	echo '<td align="right">';
	echo "<a href=\"javascript: checkall('$id', '$objType', '$relatedObj', '$popvalue', '$action');\" class=\"button smallButton\"><div>Update</div></a>";
	echo ($_GET['pop'] ? '<a href="javascript: window.close();" class="button smallButton"><div>Cancel</div></a>' : '<a href="'.$prevURL.'"	class="button smallButton"><div>Return</div></a>');
	echo '</td>';
	echo '</tr></table>';
}
//end of Buttons
 * */

?>
