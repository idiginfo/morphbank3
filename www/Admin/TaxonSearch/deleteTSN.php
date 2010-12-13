<?php
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
