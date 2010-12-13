<?php
include_once('pop.inc.php');
include_once('addImgPrepTech.php');


checkIfLogged();
groups();


$title = 'Add Preparation Technique';
initHtml ($title,NULL, NULL);
echoHead (false, $title);

echo '<div class = "popContainer" style = "width:420px">';
 $part_name = htmlspecialchars(str_replace("'", "\'", $_GET['name']));

if($_GET['code'] == '0'){
        echo '<h3><b>Imaging Preparation Technique: ' .$_GET['name'].' exists.</b></h3><br /><br />
        <a href = "javascript: window.close();" class="button smallButton"><div>Close</div> </a>';
        exit;

}else if($_GET['code'] == 1){

        echo '<h3><b>You have successfully added Imaging Preparation Technique: ' .$_GET['name']. '.<br /><br />
		Please click Select button.</b></h3><br /><br />
		<a href = "javascript:opener.update(\'Preparation\', \''.$part_name.'\', \'' .$part_name.'\'); 
			window.close();" class="button smallButton"><div>Select<span>&#8730</span></div> </a>';

}else if($_GET['code'] == 3){
        echo '<div class = "searchError"> You do not have permissions to add Imaging Preparation Technique</div><br /><br />';
}   

if(!$_GET['code'] || ($_GET['code'] != 0 && $_GET['code'] != 1))
	addImgPrepTech();

echo '</div></div>';


// Finish with end of HTML
finishHtml();
?>
