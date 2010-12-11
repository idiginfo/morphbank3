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
function mainAboutContributors() {
	
	global $link, $config;

	$db = connect();
	$sql = "SELECT distinct u.id, u.name, u.affiliation FROM User u WHERE u.id IN (
			SELECT b.userId FROM BaseObject b WHERE b.objectTypeId IN ('Specimen', 'View', 'Image', 'Location', 'Collection'))
			ORDER BY u.last_name";
	$results = $db->queryAll($sql, null, MDB2_FETCHMODE_ASSOC);
	isMdb2Error($results, 'Select contributor information');
	echo '<div class="mainGenericContainer" style="width:800px">
	      <h1 align="center">Contributors</h1>
	      <img src="/style/webImages/blueHR-trans.png" width="725" height="5" class="blueHR" alt="" />';
	if (empty($results)) {
		echo 'There are no contribuitors';
	} else {
		echo '<table width="100%" cellpadding="5" cellspacing="0" border="0">
				<tr>
					<td height="25px" width="25%" valign="top"><h3>Name</h3></td>
					<td width="33%" valign="top"><h3>Email</h3></td>
					<td width="42%" valign="top"><h3>Affiliation</h3></td></tr>';
		foreach ($results as $data) {
			echo '<tr><td height="25px" width="25%" valign="top"><b>' . $data['name'] . '</b></td>';
			echo '<td width="33%" valign="top"><img src="' . $config->domain . 'includes/mail.php?id=' . $data['id'] . '" alt="email" /></td>';
			echo '<td width="42%" valign="top">' . $data['affiliation'] . '</td></tr>';
		}
		echo '</table></div>';
	}
}
