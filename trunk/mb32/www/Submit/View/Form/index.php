<?php
include_once('pop.inc.php');
include_once('addForm.php');


checkIfLogged();
groups();

$title = 'Add Form';
initHtml ($title,NULL, NULL);
echoHead (false, $title);

echo '<div class = "popContainer" style = "width:420px">';
$part_name = htmlspecialchars(str_replace("'", "\'", $_GET['name']));

if($_GET['code'] == '0'){
	echo '<h3><b>Form: ' .$_GET['name'].' exists.</b></h3><br /><br />
	<a href = "javascript: window.close();" class="button smallButton"><div>Close</div> </a>';
	exit;
}else if($_GET['code'] == '1'){

	echo '<h3><b>You have successfully added form: ' .$_GET['name']. '.</b></h3><br /><br />';
	echo '<h3><b>Please click Select button.</b></h3><br /><br />';
	echo ' <a href = "javascript:opener.update(\'Form\', \''.$part_name.'\', \'' .$part_name. '\'); window.close();" class="button smallButton"><div>Select<span>&#8730</span></div> </a>';

}else if($_GET['code'] == 3){
        echo '<div class = "searchError"> You do not have permissions to add a Form</div><br /><br />';
}   

if(!$_GET['code'] || ($_GET['code'] != 0 && $_GET['code'] != 1))
addForm();

echo '</div></div>';


// Finish with end of HTML
finishHtml();
?>
