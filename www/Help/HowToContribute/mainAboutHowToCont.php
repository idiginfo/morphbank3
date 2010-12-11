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

$mainAboutHowToContText = '<p>
			There are several ways in which a user or a group of users can upload images to MorphBank:
			</p><p>
			1.	Using the existing web interface. Each image is uploaded separately together with the associated text information by filling out a web form. This requires a user name and password, which can be obtained from <a href="mailto:jammigum@csit.fsu.edu">Neelima Jammigumpula</a>.
			</p><p>
			2.	Batch uploading. We offer help with batch uploading of large sets of images provided that the image files and the associated data are formatted according to our instructions. Contact <a href="mailto:blanco@csit.fsu.edu">Wilfredo Blanco</a> for more information about this option.
			</p><p>
			3.	Automatic uploading from an image-handling client. In the spring of 2005, we will be offering convenient uploading of images from a platform-independent client developed in Java. We\'re also working together with several development teams in designing project-specific clients that can help research teams to upload images to MorphBank as part of their normal workflow. Contact <a href="mailto:jammigum@csit.fsu.edu">Neelima Jammigumpula</a> for more information about this.
			</p><p>
Many research teams are interested in depositing working sets of images in MorphBank without making them immediately available to users outside of their team. We will be offering these services in the next major release of the MorphBank system, scheduled for December 2004.
			</p>
			</p> ';

// simply echos the following contents to the web browser.
// This helps keep the main scripts simpler to read.
function mainAboutHowToContribute() {
	include('../../config.inc');
	include('../../data/mbMenu_data.php');

	global $mainAboutHowToContText;
	echo '<div id="main">';
	echo '<div class="mainGenericContainer">
			<h1 align="center">How to contribute</h1>'
			.$mainAboutHowToContText.'
		 </div>';
			echo ' <div class="mainFooter"><img src="/style/webImages/footer.gif" border="0"></div>';
			echo '</div>';
}

?>
