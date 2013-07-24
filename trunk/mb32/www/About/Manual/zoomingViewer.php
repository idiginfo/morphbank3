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

	
	include_once('head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>

	
		<div class="mainGenericContainer" width="100%">
		<!--change the header below -->
		<h1>Zooming Viewer</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
			<td width="100%">

<p>
The magnifying glass <img src="../../style/webImages/magnifyGlass.ico" alt="Zooming Viewer" /> will open the image using this Zooming Image viewer, allowing the user to move the image and zoom in / out; labeling is a function that may be available soon. This viewer is used to display high-resolution imagery on Morphbank. Bischen is a component of <a href="http://www.collectiveaccess.org/" target="_blank">CollectiveAccess</a>, community-developed open-source collections management and presentation software for museums, archives and arts organizations. For more information, visit <a href="http://www.collectiveaccess.org/" target="_blank">CollectiveAccess</a>.
</p>
In Morphbank the zooming viewer is accessed several ways. 
<ul>
<li>The <img src="../../style/webImages/magnifyShadow-trans.png" alt="Zooming Viewer"> icon will open a given image with the Zooming Viewer. This <img src="../../style/webImages/magnifyShadow-trans.png" alt="ZoomingViewer"> icon is present for any image in Morphbank in the My Manager <strong>Images</strong> or <strong>All</strong> tabs. Simply go to Header Menu > Browse > Images or Header Menu > Browse > All to get to these tabs.</li>
<li>The user may click on the <img src="../../style/webImages/infoIcon-trans.png" alt="i icon"/> icon or thumbnail to open the Single Show for any given image in Morphbank. Then, click on the image there or the link just below the image that reads "view full image" to open the image in this unique viewer.</li>
<li>A user may narrow the set of images to be opened with the viewer by utilizing the <strong>Keyword Search</strong> feature at the top left of the <strong>All</strong> or <strong>Images</strong> tabs of My Manager.
<li>Other paths to show (and the Viewer) include selecting the <strong>Object id</strong> where it is listed in blue type i.e. in another <strong>Show</strong> screen.</li>
<li>The Viewer is also available by clicking on an image in a <strong>Collection</strong> from the Browse > Collections tab of the My Manager interface.
</li>
</ul>
<br />
<br />
<img src="ManualImages/zooming_viewer.png" alt="Zooming Viewer" hspace="20" />
<br /><br />
Tag Descriptions for Zooming Viewer
<ul>
<li>1. For more about the open source software project supporting this viewer go to <a href="http://www.collectiveaccess.org/" target="_blank">CollectiveAccess</a>.
</li>
<br />
<li>2.
 In the small grey box, click on the + to enlarge, - to reduce the image.
</li>
<br />
<li>3. Click on the cross-hatch, then use the mouse to click and hold down the left mouse button to move the image around.
</li><br />
<li>4. Clicking the  white box with arrows in the corners enlarges the image.
<br />
</li>
<br />
<li>5. Clicking 100% displays the image at this resolution.
</li>
<br />
<li>6. The small black triangle pointing right opens a sub-menu to navigator and labels. The Navigator is used to move around and look at different parts of an image while zoomed in. To use this function, click on 100%, then click on Navigator, move red box around inside the Navigator pop-up window with mouse to travel around the larger image. The labels sub-menu option is not operational yet.
</li>
</ul>

<br />
<br />								
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?php echo $config->domain; ?>About/Manual/services.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</div>
		
			<?php
//Finish with end of HTML	
finishHtml();
?>	

	
