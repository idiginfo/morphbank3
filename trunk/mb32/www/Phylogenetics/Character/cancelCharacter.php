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

//File to delete the phyloCharacter Information from the
//database
//
//@author Karolina Maneva-Jakimoska
//@date created June 26th 2007
//

include_once('head.inc.php');
include_once('Phylogenetics/phylo.inc.php');


$url = $config->domain . 'MyManager/index.php';
//echo "url is ".$url;
$flag=0;
$collection=0;

if(isset($_GET['id'])){
	$link = Adminlogin();
	$collection = $_GET['collection'];
	$phylo = new PhyloCharacter($link,$_GET['id']);
	if($collection==0)
	$phylo -> deleteCharacterCollectionFromDB();
	else
	$phylo -> deleteCharacterFromDB();
}

//$url .= "&flag=".$flag;
?>
<script language="javascript">
window.location = "<? echo $url;?>";
</script>
