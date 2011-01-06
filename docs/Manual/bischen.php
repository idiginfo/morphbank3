<?php 
	//global $includeDirectory, $dataDirectory, $imgDirectory;
	global $domainName;
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>

	
		<div class="mainGenericContainer" width="100%">
		<!--change the header below -->
		<h1>Bischen Viewer</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
			<td width="100%">

<p>
The magnifying glass <img src="../../style/webImages/magnifyGlass.ico" alt="Bischen Viewer" /> will open the image using the Bischen image viewer, allowing the user to move the image and zoom in / out; labeling is a function that may be available soon. The Bischen image viewer is used to display high-resolution imagery on Morphbank. Bischen is a component of <a href="http://www.collectiveaccess.org/" target="_blank">CollectiveAccess</a>, community-developed open-source collections management and presentation software for museums, archives and arts organizations. For more information, visit <a href="http://www.collectiveaccess.org/" target="_blank">CollectiveAccess</a>.
</p>
In Morphbank the viewer is accessed several ways. 
<ul>
<li>The <img src="../../style/webImages/magnifyShadow-trans.png" alt="Bischen Viewer"> icon will open a given image with the Bischen Viewer. This <img src="../../style/webImages/magnifyShadow-trans.png" alt="Bischen Viewer"> icon is present for any image in Morphbank in the My Manager <strong>Images</strong> or <strong>All</strong> tabs. Simply go to Header Menu > Browse > Images or Header Menu > Browse > All to get to these tabs.</li>
<li>The user may click on the <img src="../../style/webImages/infoIcon-trans.png" alt="i icon"/> icon or thumbnail to open the Single Show for any given image in Morphbank. Then, click on the image there or the link just below the image that reads "Bischen Viewer" to open the image in this unique viewer.</li>
<li>A user may narrow the set of images to be opened with Bischen Viewer by utilizing the <strong>Keyword Search</strong> feature at the top left of the <strong>All</strong> or <strong>Images</strong> tabs of My Manager.
<li>Other paths to show (and hence the Bischen Viewer) include selecting the <strong>Object id</strong> where it is listed in blue type i.e. in another <strong>Show</strong> screen.</li>
<li>The Bischen viewer is also available by clicking on an image in a <strong>Collection</strong> from the Browse > Collections tab of the My Manager interface.
</li>
</ul>
<br />
<br />
<img src="ManualImages/FSIViewer_image.png" alt="FSIViewer Sample Image" hspace="20" />
<br /><br />
Tag Descriptions for Figure 5 (FSI Viewer located above)
<ul>
<li>1. Click to display viewer information and a link to the FSI Viewer website
(<a href="http://www.fsi-viewer.com/" target="_blank">http://www.fsi-viewer.com/</a>). By clicking on the Read more button then
selecting the Customer Care section on the website, the user can obtain
detailed instructional information on the use of the viewer to include tutorials.
</li>
<br />
<li>2.
 This tiny corner window displays the image that is presently seen on the viewer screen. 
 When only a portion of the original image is currently being viewed, that area is
displayed in a red rectangle. The display region can be changed on this small screen by dragging the rectangle
or clicking on the desired part of the screen. <em>The Mini Display can be moved in order to see the scale bar. With 
the mouse, click the bottom bar of the Mini Display and drag to a new location.</em>
</li>
<br />
<li>3. The body of the screen where the selected image is viewed and
manipulated.
</li><br />
<li>4. Menu bar
<br />
<img src="ManualImages/FSIreturn%20copy.jpg" />
Return to initial view
<br />
<img src="ManualImages/FSIZoomOut%20copy.jpg" />
Zoom out to decrease magnification
<br />
<img src="ManualImages/FSIZoomIn%20copy.jpg" />
Zoom in to increase magnification
<br />
<img src="ManualImages/FSIMagnify%20copy.jpg" />
Mouse activated menu item. Use to enlarge segments with the mouse.
Click on the image and drag to highlight the desired area to enlarge..or
click on the image, without marking the segment to enlarge the entire
image. The current image segment will then be enlarged in steps. To
zoom out in steps, hold down the CTRL-key and click on the image.
<br />
<img src="ManualImages/FSIDrag%20copy.jpg" />
Mouse activated menu item. Use to move the viewable image in the body
of the screen. Click to drag the image. CTRL-key and left click will reset
the image.
<br />
<img src="ManualImages/FSIViewer_rotate.jpg" alt="rotate" />
Mouse activated menu item. Use to rotate the image on the screen Use
CTRL to reset the tilt.
<br />
<img src="ManualImages/FSI_history_button.jpg" width="38" height="23" />
History buttons. Click on the left arrow to revert to previous or the right
arrow to again advance the historical settings.
<br />
<img src="ManualImages/FSI_mag_glass.jpg"  width="25" height="22" />
Magnifying glass. Click to access the magnifying glass. Drag to observe
a magnified image through the glass. Click on the button again to hide the
magnifying glass.
<br />
<img src="ManualImages/FSI_hot_spot.jpg" width="21" height="23" />
Hot spot. Click to show 
or hide hot spots. This feature is not currently available.
<br />
<img src="ManualImages/color_adjust.png" />
Color adjust. Click to open a color adjust screen.
</li>
<br />
<li>5. Show or hide the user interface. Click this icon if a full page image void
of all menu items is desired.
</li>
</ul>

<br />
<br />								
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?echo $domainName;?>About/Manual/services.php" class="button smallButton"><div>Next</DIV></a></td>
<td><a href="<?echo $domainName;?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</div>
		
			<?php
//Finish with end of HTML	
finishHtml();
?>	

	
