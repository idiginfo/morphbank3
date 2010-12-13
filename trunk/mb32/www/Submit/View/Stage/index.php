<?php
include_once('pop.inc.php');

include_once('addStage.php');


checkIfLogged();
groups();

$title = 'Add Stage';
initHtml ($title,NULL, NULL);
echoHead (false, $title);

echo '<div class = "popContainer" >';
$part_name = htmlspecialchars(str_replace("'", "\'", $_GET['name']));


if($_GET['code'] == '0'){
	echo '<h3><b>Developmental Stage: ' .$_GET['name'].' exists.</b></h3><br /><br />
        <a href = "javascript: window.close();" class="button smallButton"><div>Close</div> </a>';
        exit;
}else if($_GET['code'] == '1'){

        echo '<h3><b>You have successfully added a Developmental Stage: ' .$_GET['name']. '.</b></h3><br /><br />';
	echo '<h3><b>Please click Select button.</b></h3><br /><br />
	<a href = "javascript:opener.update(\'Stage\', \''.$part_name.'\', \'' .$part_name. '\'); 
		window.close();" class="button smallButton"><div>Select<span>&#8730</span></div> </a>';

}else if($_GET['code'] == 3){
        echo '<div class = "searchError"> You do not have permissions to add a Developmental Stage.</div><br /><br />';
}

if(!$_GET['code'] || ($_GET['code'] != 0 && $_GET['code'] != 1))
	addStage();

echo '</div></div>';


// Finish with end of HTML
finishHtml();
?>
