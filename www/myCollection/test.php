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
*   Deborah Paul - initial API and implementation implementation
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

<?php 





include_once('head.inc.php');


// The beginnig of HTML
$title =  'About - Collaborations';
initHtml( $title, NULL, NULL);

// Add the standard head section to all the HTML output.
echoHead( false, $title);

include_once('postItFunctions.inc.php');
setupPostIt();

// Output the content of the main frame

$content = "This is \"some\" test<br />Content for 'the' image<br />With some HTML<b>in it</b>";
$content = str_replace("'", '"', $content);
$content = htmlentities($content);

$content2 = "This is some test<br />Content for the text field<br />With some HTML<b>in it</b>";
$content2 = htmlentities($content2);


echo '
<div id="main">
	<div class="mainGenericContainer">
		<img src="/style/webImages/mNew.png" alt="img" onmouseover="javascript:startPostIt( event, \''.$content.'\');" onmouseout="javascript:stopPostIt();" />
		<input type="text" name="test" value="" onmouseover="javascript:startPostIt( event, \''.$content2.'\');" onmouseout="javascript:stopPostIt();"/><br />
		<img src="/style/webImages/copy.ico" alt="copy" />
	</div>

</div>';


// Finish with end of HTML
finishHtml();
?>
