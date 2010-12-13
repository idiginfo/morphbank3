<?php
/**
 * File Name: index.php
 * @packkage Morphbank2
 * @subpackage Submit
 */
include_once('head.inc.php');
require_once('showFunctions.inc.php');

if(!$objInfo->getLogged()) {
	$title = 'Login';
	$msg = $_GET['login'] == 'failed' ? '<h1><span class="req">Please try again </span> </h1>' : '';
	ob_start();
	include_once("loginForm.php");
	$content = ob_get_contents();
	ob_end_clean();
}else{
	if ($objInfo->getUserGroup() == "") {
		$title = 'Groups';
		$sql = "select ug.groupName, ug.groups from User u 
				left join UserGroup ug on ug.user = u.id 
				left join Groups g on g.id = ug.groups
				where u.uin = '" . $objInfo->getUserName(). "'";
		$results = $db->queryAll($sql, null, MDB2_FETCHMODE_ASSOC);
		isMdb2Error($results, 'Selecting User groups');
		ob_start();
		$msg = !$_GET['group'] == 'groups' ? 
			'<br /><b>Please select the group you want to work with </b><br /><br />' : 
			'<br /><h2><span class="req">You must select the group before Uploading.</span></h2><br /><br />';
		foreach ($results as $row) {
			echo '<a class = "introNav" href="/Submit/updateInfo.php?group=' .$row['groups']. '" >' .$row['groupName']. '</a><br />';
		}
		$content = ob_get_contents();
		ob_end_clean();
	} else {
		$title = 'Forms';
		ob_start();
		include_once("mbUpload_data.php");
		foreach($uploadMenu as $menu) {
			echo '<div class="introNavText">';
			echo '<a class="introNav" href="'.$menu['href'].'">'.$menu['name'].'</a></div> ';
		}
		$content = ob_get_contents();
		ob_end_clean();
	}
}

/* Include javascript files */
$includeJavaScript = array('jquery.1-4-2.min.js', 'jquery.validate.min.js', 'formMethods.js');
initHtml( $title, NULL, $includeJavaScript);
echoHead( false, $title);
echo '<div class="mainGenericContainer" style="width:600px">';
echo $msg;
echo $content;
echo '</div>';
finishHtml();
