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

// simply echos the following contents to the web browser.
// This helps keep the main scripts simpler to read.

function mainIntro() {
	global $config;
	global $mainMenu;
	
	// Horizontal menu container for introduction page
	
	echo '	<div class="introBackGround" style="border:0px solid black;">
				<img src="/style/webImages/introBackGround.jpg" border="0" align="top" alt="backGround" />
			</div>';
	echo ' <div class="introLogInContainer">[<a href="'.$config->domain.'Submit/">login</a>]</div>';
	echo '	<div class="introNavContainer"> ';
	foreach($mainMenu as $menu) {
		echo '			<div class="introNavText"><img src="/style/webImages/bullet.gif" height="30" border="0" align="bottom" alt="bullet" />';
		echo '<a class="introNav" href="'.$menu['href'].'">'.$menu['name'].'</a></div>
		';
	}
	echo '	</div>'; //introNavContainer
	
	echo'<div class="introSponsors">
			
			<table align="right" cellspacing="6" border="0">
				<tr>
					<td align="right"><a href="http://www.nsf.gov/"><img src="/style/webImages/nsf-trans.png" width="50" height="50" alt="NSF Logo" title="National Science Foundation (NSF)" /></a></td>
					<td align="right"><a href="http://www.nescent.org/main/"><img src="/style/webImages/necent-trans.png" width="50" height="50" alt="NSF Logo" title="National Evolutionary Synthesis Center (NESCent)" /></a></td>
					<td align="right"><a href="http://atol.sdsc.edu/projects.html"><img src="/style/webImages/atol-trans.png" width="50" height="50" alt="NSF Logo" title="Assembling the Tree of Life (AToL)" /></a></td>
					<td align="right"><a href="http://www.fsu.edu"><img src="/style/webImages/fsu-trans.png" width="50" height="50" alt="NSF Logo" title="Florida State University (FSU)" /></a></td>
					<td align="right"><a href="http://www.scs.fsu.edu"><img src="/style/webImages/scs-trans.png" width="50" height="50" alt="NSF Logo" title="School of Computational Science (SCS)" /></a></td>
				</tr>
			</table>';
			
	/*echo'<center>
			<a href=""><img src="/style/webImages/nsf-trans.png" width="50" height="50" alt="NSF Logo" title="National Science Foundation (NSF)" /></a>&nbsp;&nbsp;&nbsp;
			<a href=""><img src="/style/webImages/necent-trans.png" width="50" height="50" alt="NSF Logo" title="National Evolutionary Synthesis Center (NECENT)" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
			<a href=""><img src="/style/webImages/atol-trans.png" width="50" height="50" alt="NSF Logo" title="Assembling the Tree of Life (AToL)" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
			<a href=""><img src="/style/webImages/fsu-trans.png" width="50" height="50" alt="NSF Logo" title="Florida State University (FSU)" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
			<a href=""><img src="/style/webImages/scs-trans.png" width="50" height="50" alt="NSF Logo" title="School of Computational Science (SCS)" /></a>
			
		</center>*/
	echo'
		</div>';
	
	// Description text for each menu container
	foreach($mainMenu as $menu) {
		echo '		<div class="introContainer" id="'.$menu['name'].'" style="display:none;">
					<h1>'.$menu['name'].'</h1>
						<div class="introTextContent">
		';
		echo $menu['description'];
		echo '			</div>
				</div>
				';
	}
}

?>
