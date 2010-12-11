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


include_once('head.inc.php');

include_once('tsnFunctions.php');
include_once('objOptions.inc.php');
include_once('imageFunctions.php');

$id = $_GET['id'];


if ($id == "imageContent") {
echo '
<div class="TabbedPanelsContent">			
		<div class="imagethumbspage">
			<center>				
				<img src="/style/webImages/loading3.gif" alt="loading" />
			</center>
			<script type="text/javascript">
				setTimeout( "blindDownContent(\'image\')" , 1000);
			
			</script>';
		
echo '			
		</div>			
	 </div>';

} elseif($id == "specimenContent") {

echo '
<div class="TabbedPanelsContent">			
		<div class="imagethumbspage">
			<center>				
				<img src="/style/webImages/loading3.gif" alt="loading" />
			</center>
			<script type="text/javascript">
				setTimeout( "blindDownContent(\'specimen\')" , 1000);
			
			</script>';
		
echo '			
		</div>			
	 </div>';

}elseif($id == "viewContent") {
echo '
<div class="TabbedPanelsContent">			
		<div class="imagethumbspage">
			<center>				
				<img src="/style/webImages/loading3.gif" alt="loading" />
			</center>
			<script type="text/javascript">
				setTimeout( "blindDownContent(\'view\')" , 1000);
			
			</script>';
		
echo '			
		</div>			
	 </div>';
	
}elseif($id == "localityContent") {
echo '
<div class="TabbedPanelsContent">			
		<div class="imagethumbspage">
			<center>				
				<img src="/style/webImages/loading3.gif" alt="loading" />
			</center>
			<script type="text/javascript">
				setTimeout( "blindDownContent(\'locality\')" , 1000);
			
			</script>';
		
echo '			
		</div>			
	 </div>';
}elseif($id == "taxonContent") {
	echo"Taxon Content<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		";
}elseif($id == "collectionContent") {
echo '
<div class="TabbedPanelsContent">			
		<div class="imagethumbspage">
			<center>				
				<img src="/style/webImages/loading3.gif" alt="loading" />
			</center>
			<script type="text/javascript">
				setTimeout( "blindDownContent(\'collection\')" , 1000);
			
			</script>';
		
echo '			
		</div>			
	 </div>';
}elseif($id == "annotationContent") {
echo '
<div class="TabbedPanelsContent">			
		<div class="imagethumbspage">
			<center>				
				<img src="/style/webImages/loading3.gif" alt="loading" />
			</center>
			<script type="text/javascript">
				setTimeout( "blindDownContent(\'annotation\')" , 1000);
			
			</script>';
		
echo '			
		</div>			
	 </div>';

}elseif($id == "matrixContent") {
	echo"Matrix Content<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		";
}elseif($id == "pubContent") {
	echo"Publication Content<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />
		";
}

function showPageControls() {
		global $config;
		echo'		
		<br />	
			<a href="'.$config->domain.'Browse/ByImage/?" title="go to first"><img src="'.$config->domain.'style/webImages/goFirst2.png" border="0" alt="goToFirst" /></a>&nbsp;&nbsp;<font color="#d50200">1</font> &nbsp;
			<a href="'.$config->domain.'Browse/ByImage/?&amp;log=NO"><font color="#2e2eff">2</font></a> &nbsp;
			<a href="'.$config->domain.'Browse/ByImage/?"><font color="#2e2eff">3</font></a> &nbsp;
			<a href="'.$config->domain.'Browse/ByImage/?"><font color="#2e2eff">4</font></a> &nbsp;
			<a href="'.$config->domain.'Browse/ByImage/?"><font color="#2e2eff">5</font></a> &nbsp;
			<a href="'.$config->domain.'Browse/ByImage/?"><font color="#2e2eff">6</font></a> &nbsp;
			<a href="'.$config->domain.'Browse/ByImage/?"><font color="#2e2eff">7</font></a> &nbsp;
			<a href="'.$config->domain.'Browse/ByImage/?"><font color="#2e2eff">8</font></a> &nbsp;
			<a href="'.$config->domain.'Browse/ByImage/?"><font color="#2e2eff">9</font></a> &nbsp;
			<a href="'.$config->domain.'Browse/ByImage/?"><font color="#2e2eff">10</font></a> &nbsp;
			<a href="'.$config->domain.'Browse/ByImage/?"><font color="#2e2eff">11</font></a> &nbsp;
			<a href="'.$config->domain.'Browse/ByImage/?" title="next"><img src="'.$config->domain.'style/webImages/forward-gnome.png" border="0" alt="foward" /></a>&nbsp;&nbsp;
			<a href="'.$config->domain.'Browse/ByImage/?"><img src="'.$config->domain.'style/webImages/goLast2.png"  border="0" alt="goToLast" /></a>
			&nbsp;&nbsp;<b> of 3389</b> &nbsp; (67769 images)<br /><br />';	
}
?>
