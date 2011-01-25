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

include_once ('pop.inc.php');

$link = Adminlogin();
include_once ('mainMyCollection.php');

$title = 'Create Character State';
initHtml( $title, NULL, NULL);

// Add the standard head section to all the HTML output.
echoHead( false, $title);

$url = $config->domain . 'myCollection/index.php?id='.$_GET['characterId'].'&idString='.$_GET['idString'].'&createState=true';

//$stateValue = getStateValue();

echo '

<script type="text/javascript" language="JavaScript">
<!--			
	function createStateSubmit(url) {
		var title = document.createStateForm.stateTitle.value;
		var value = document.createStateForm.characterStateValue.value;
		var fullUrl = url+"&title="+title+"&value="+value;
		//alert(fullUrl);
		loadInOpener(fullUrl);	
	}
//-->
</script>


	<div class="mainGenericContainer" style="width:400px;">
		<h1>Add Character State</h1><br />
		<form name="createStateForm" action="#" method="post" onsubmit="createStateSubmit(\''.$url.'\');window.close();">
			<table width="400" border="0" cellspacing="5" cellpadding="0">
			  <tr>
				<td valign="bottom" width="200"><h2>State Title:&nbsp;</h2></td> <td><input type="text" name="stateTitle" onkeypress="return checkEnter(event);" value="" /></td>
			  </tr>
			  <tr>
			  	<td valign="bottom" width="200"><h2>State Value:&nbsp;</h2></td> <td><input type="text" onkeypress="return checkEnter(event);" name="characterStateValue" value="" /></td>
			  </tr>
			  <tr>
				<td colspan="2" valign="bottom"><a href="javascript: createStateSubmit(\''.$url.'\');window.close();" class="button smallButton"><div>Submit</div></a></td>
			  </tr>
			</table>

				
		</form>
		
	</div>


<script type="text/javascript" language="JavaScript">
<!--			
	document.createStateForm.stateTitle.focus();
	
	function checkEnter (e) {
		var characterCode = returnKeyCode(e);
		
		if(characterCode == 13) {
			//if generated character code is equal to ascii 13 (if enter key)
			createStateSubmit(\''.$url.'\');
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
