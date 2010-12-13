<?php
/**
 * File name: index.php
 * @package Morphbank2
 * @subpackage Annoatation
 */

if (isset($_GET['pop']) || isset($_POST['pop'])) {
	include_once('pop.inc.php');
} else {
	include_once('head.inc.php');
}

include_once('annotateFunctions.php');
include_once('mainAnnotation.php');
include_once('Admin/admin.functions.php');

include_once('gettables.inc.php');
include_once('thumbs.inc.php');
include_once('imageFunctions.php');
include_once('postItFunctions.inc.php');
include_once('tsnFunctions.php');
include_once('showFunctions.inc.php');


$id           = isset($_GET['id']) ? $_GET['id'] : null;
$collectionId = isset($_GET['collectionId']) ? $_GET['collectionId'] : null;

$includeJavaScript = array(
	'jquery.1-4-2.min.js', 
	'jquery.validate.min.js', 
	'formMethods.js',
	'datescript.js'
);

// The beginnig of HTML
$title = 'Add Annotation';
initHtml($title, null, $includeJavaScript);
echoHead(false, $title);

echo '<div class = "mainGenericContainer" style="width:Auto">';

$db = connect();

if(!$objArray = checkForTaxonConcept($id, $collectionId)) {
	echo '<div class="searchError">Annotation requires an Object or Collection Id</div><br /><br />'."\n";
}else {	
	if(!checkAuthorization(null, $objInfo->getUserId(), $objInfo->getUserGroupId(), 'add')){
		echo getNonAuthMessage(getNonAuthCode()) . "<br /><br />";
	} else {
		getAnnotateMsg($_GET['code']);
		setUpPostIt();
		mainAnnotation($objArray, $collectionId);
	}
}
echo "</div>";
// Finish with end of HTML
finishHtml();
