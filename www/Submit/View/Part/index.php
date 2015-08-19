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

include_once('pop.inc.php');
include_once('addPart.php');


checkIfLogged();
groups();

$title = 'Add Specimen Part';
initHtml ($title,NULL, NULL);
echoHead (false, $title);

echo '<div class = "popContainer" style = "width:420px">';
 $part_name = htmlspecialchars(str_replace("'", "\'", $_GET['name']));
 
if($_GET['code'] == '0'){
        echo '<h3><b>Specimen Part: ' .$_GET['name'].' exists.</b></h3><br /><br />
        <a href = "javascript: window.close();" class="button smallButton"><div>Close</div> </a>';
	exit;

}else if($_GET['code'] == 1){
		
        echo '<h3><b>You have successfully added Specimen Part: ' .$_GET['name'].'</b></h3><br /><br />
		Please click Select button.</b></h3><br /><br />
		<a href = "javascript:opener.update(\'Part\', \''.$part_name.'\', \'' .$part_name. '\'); 
			window.close();" class="button smallButton"><div>Select<span>&#8730</span></div> </a>';

}else if($_GET['code'] == 3){
        echo '<div class = "searchError"> You do not have permissions to add a Specimen Part</div><br /><br />';
}   

if(!$_GET['code'] || ($_GET['code'] != 0 && $_GET['code'] != 1))
	addPart();

echo '</div>';


// Finish with end of HTML
finishHtml();
?>
