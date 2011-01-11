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

//File to delete the TaxonName from the database
//
//@author Karolina Maneva-Jakimoska
//@date created Oct 2nd 2007
//

include_once('head.inc.php');

$flag = 0;

if (isset($_GET['tsn'])) {
	$url = $config->domain . 'Admin/TaxonSearch/editTSN.php';
	$link = Adminlogin();

	$query = "Delete from Tree where tsn=" . $_GET['tsn'];
	mysqli_query($link, $query);

	$query = "Delete from Taxa where tsn=" . $_GET['tsn'];
	mysqli_query($link, $query);

	$query = "Delete from TaxonConcept where tsn=" . $_GET['tsn'];
	mysqli_query($link, $query);

	$query = "Delete from BaseObject where id=" . $_GET['id'];
	mysqli_query($link, $query);

	$url .= "?delete=1&tsn=" . $_GET['tsn'];
}
?>
<script language="javascript">
window.location = "<?php
  echo $url;
?>";
</script>
