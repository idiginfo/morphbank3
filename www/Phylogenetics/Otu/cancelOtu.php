<?php

//File to delete the OTU from the database
//
//@author Karolina Maneva-Jakimoska
//@date created Sep 17th 2007
//

include_once('head.inc.php');
include_once('Phylogenetics/phylo.inc.php');


$url = $config->domain . 'MyManager/index.php?tab=taxaTab';
//echo "url is ".$url;
$flag=0;
$collection=0;

if(isset($_GET['id'])){
	$link = Adminlogin();
	$otu = new Otu($link,$_GET['id']);
	$otu -> deleteOtuFromDB();
}

//$url .= "&flag=".$flag;
?>
<script language="javascript">
window.location = "<? echo $url;?>";
</script>
