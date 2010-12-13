<?php

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
