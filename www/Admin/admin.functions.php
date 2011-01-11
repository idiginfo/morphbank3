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

//function to get group names list for the user
function prefGroup($link, $userId, $prefGroup) {
	$link = Adminlogin();
	$query = "Select Groups.groupName,Groups.id from Groups, UserGroup where UserGroup.user =" . $userId . " and Groups.id = UserGroup.groups";
	$result = mysqli_query($link, $query);
	$num_rows = mysqli_num_rows($result);
	echo '<select name="preferredGroup" >';
	for ($i = 0; $i < $num_rows; $i++) {
		$row = mysqli_fetch_array($result);
		if ($prefGroup == $row['id']) {
			echo '<option selected="selected" value="' . $row['id'] . '">' . $row['groupName'] . '</option>';
		} else {
			echo '<option value="' . $row['id'] . '">' . $row['groupName'] . '</option>';
		}
	}
	echo '</select>';
}
?>
