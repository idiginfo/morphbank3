<?php

include_once( 'imageFunctions.php');

// simply echos the following contents to the web browser.
// This helps keep the main scripts simpler to read.
function singleImageViewer( $title) {

	echo '<div class="popContainer" style="width: Auto;"><H2>'
	. 'The Viewer is not available for this image. <br/> Contact Morphbank Administration. <br/><br/>'
	.'<a href = "javascript: window.close(); " class="button smallButton"><div>Close</div> </a> </div>';
	exit;
}


function displayFSIViewer( $imagePath, $viewerWidth, $viewerHeigth, $size) {

	$fsi_url = "http://morphbank4.scs.fsu.edu:8080/erez4/fsi4/fsi.swf?FPXBase=http://morphbank4.scs.fsu.edu:8080/erez4%2Ferez%3Fsrc%3D";
	$fsi_url .= "&amp;fpxsrc=$imagePath%26quality%3d80";
	//$fsi_url .="&amp;fpxwidth=1351&amp;fpxheight=1019";
	//$fsi_url .="&amp;ViewerWidth=400&amp;ViewerHeight=509";
	$fsi_url .="&amp;Width=400&amp;Height=509";
	$fsi_url .="&amp;MouseModes_Mode2=0&amp;MouseModeSelect_Mode2=0&amp;InitialMouseMode=0&amp;plugins=coloradjust&amp;cfg=image&Measure_ImageWidth=" .($size[0]* .12);
	$fsi_urldebug = $fsi_url."&amp;debug=0";

	$width = $viewerWidth;
	$height =$viewerHeigth;

	//<param name=\"WIDTH\" value=\"500\">
	//<param name=\"HEIGHT\" value=\"300\">

	$bgcolor = "#FFFFFF";
	$template = "<object id=\"fsiviewer\" classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\"
			codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,65,0\" 
				width =\"$width\" height=\"$height\">
			<param name=\"movie\" value=\"$fsi_url;cfg=viewer\">
			<param name=\"quality\" value=\"high\">
			<param name=\"bgcolor\" VALUE=\"$bgcolor\">
			<param name=\"menu\" value=\"FALSE\">
			<embed name=\"fsiviewer\" swliveconnect=\"true\" 
				src=\"$fsi_urldebug&amp;cfg=viewer\" 
				width=\"$width\" height=\"$height\" 
				menu=\"false\" 
				type=\"application/x-shockwave-flash\" 
				pluginspage=\"http://www.macromedia.com/go/getflashplayer\" width=\"$width\" height=\"$height\"></embed>
		</object>";	
	return $template;
}

?>
