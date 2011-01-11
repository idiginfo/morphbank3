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

<?php 

include_once ('pop.inc.php');

$link = Adminlogin();
include_once ('mainMyCollection.php');

$title = 'Edit Title';
initHtml( $title, NULL, NULL);

// Add the standard head section to all the HTML output.
echoHead( false, $title);

$title = getObjectTitle($_GET['objectId'], $_GET['collectionId']);
$url = $config->domain . 'myCollection/index.php?collectionId='.$_GET['collectionId'].'&amp;objectId='.$_GET['objectId'].'&amp;imgSize='.$_GET['imgSize'].'&amp;title=';

echo '

<script type="text/javascript" language="JavaScript">
<!--			
	function editTitleSubmit(url) {
		title = document.editTitleForm.title.value;
		fullUrl = url+title;
		loadInOpener(fullUrl);	
	}
//-->
</script>


	<div class="mainGenericContainer" style="width:400px;">
		<h1>Edit Title for Image: '.$_GET['objectId'].'</h1><br />
		<form name="editTitleForm" action="#" method="post" onsubmit="editTitleSubmit(\''.$url.'\');window.close();">
			<table width="400" border="0" cellspacing="5" cellpadding="0">
			  <tr>
				<th valign="bottom" width="200"><h2>Title:&nbsp;</h2> <input type="text" name="title" value="'.$title.'" /></th>
				<td valign="bottom"><a href="javascript: editTitleSubmit(\''.$url.'\');window.close();" class="button smallButton"><div>Submit</div></a></td>
			  </tr>
			</table>

				
		</form>
		
	</div>


<script type="text/javascript" language="JavaScript">
<!--			
	document.editTitleForm.title.focus();
	document.editTitleForm.title.select();
	
	function checkEnter (e) {
		var characterCode = returnKeyCode(e);
		
		if(characterCode == 13) {
			//if generated character code is equal to ascii 13 (if enter key)
			editTitleSubmit(\''.$url.'\');
			window.close();
			return false;
		} else {
			return true;
		}
	}
	
	function returnKeyCode(e) {
		var keycode;
		if (window.event) keycode = window.event.keyCode;
		else if (e) keycode = e.which;
		else return false;
		
		return keycode;
	}
//-->
</script>';

?>
