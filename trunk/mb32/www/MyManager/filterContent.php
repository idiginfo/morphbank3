<?php

include_once('head.inc.php');
require_once('filters/sort.class.php');

$id = isset($_GET['filterId'])?$_GET['filterId']:"image";

if ($id == "image" || $id == "imageTab")
imageFilters();
elseif ($id == "specimen" || $id == "specimenTab")
specimenFilters();
elseif ($id == "view" || $id == "viewTab")
viewFilters();
elseif ($id == "taxa" || $id == "taxaTab")
taxaFilters();
elseif ($id == "collection" || $id == "collectionTab")
collectionFilters();
else
otherFilters();
if ($id == "taxa") {
	echo '<br /><br />
	<hr /><br />
	<a href="'.$config->domain.'Admin/TaxonSearch/" class="button largeButton right"><div>Add New Taxa</div></a>';
}


function imageFilters() {
	global $config;
	$sortByFields = array ( 	array(	'field' => 'id',
							  	'label' => 'Image id',
								'width' => 40,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'DESC'),
	array(	'field' => 'dateToPublish',
							  	'label' => 'Date To Publish',
								'width' => 40,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'DESC'),
	array(	'field' => 'userId',
							  	'label' => 'Contributor',
								'width' => 40,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'DESC'),
	array(	'field' => 'groupId',
							  	'label' => 'Group Id',
								'width' => 40,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'DESC')
	);

	$sortTool = new sort($config->domain, $sortByFields, "manager");

	$sortTool->display();
}

function specimenFilters() {
	global $config;

	$sortByFields = array ( 	array(	'field' => 'id',
								'label' => 'Specimen Id',
								'width' => 40,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'DESC'),
	array(	'field' => 'dateToPublish',
							  	'label' => 'Date To Publish',
								'width' => 40,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'DESC'),
	array(	'field' => 'userId',
							  	'label' => 'Contributor',
								'width' => 40,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'DESC'),
	array(	'field' => 'groupId',
							  	'label' => 'Group Id',
								'width' => 40,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'DESC')
	);

	$sortTool = new sort($config->domain, $sortByFields, "manager");

	$sortTool->display();
}

function viewFilters() {
	global $config;

	$sortByFields = array ( 	array(	'field' => 'id',
							  	'label' => 'View id',
								'width' => 40,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'DESC'),
	array(	'field' => 'dateToPublish',
							  	'label' => 'Date To Publish',
								'width' => 40,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'DESC'),
	array(	'field' => 'userId',
							  	'label' => 'Contributor',
								'width' => 40,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'DESC'),
	array(	'field' => 'groupId',
							  	'label' => 'Group Id',
								'width' => 40,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'DESC')
	);

	$sortTool = new sort($config->domain, $sortByFields, "manager");

	$sortTool->display();
}

function taxaFilters() {
	global $config, $objInfo;

	$sortByFields = array ( 	array(	'field' => 'tsn',
							  	'label' => 'TSN',
								'width' => 40,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'DESC'),
	array(	'field' => 'dateToPublish',
							  	'label' => 'Date To Publish',
								'width' => 40,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'DESC'),
	array(	'field' => 'userId',
							  	'label' => 'Contributor',
								'width' => 40,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'DESC'),
	array(	'field' => 'groupId',
							  	'label' => 'Group Id',
								'width' => 40,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'DESC')
	);


	$sortTool = new sort($config->domain, $sortByFields, "manager");

	$sortTool->display();

	$session_taxaToggle = $objInfo->getTaxaToggle();
	$selected = (isset($_GET['taxaOtuToggle'])) ? $_GET['taxaOtuToggle'] : (!empty($session_taxaToggle)) ? $session_taxaToggle : "both";

	$bothSelected = ($selected == "both") ? "checked='checked'" : "" ;
	$taxaSelected = ($selected == "taxa") ? "checked='checked'" : "" ;
	$otuSelected = ($selected == "otu") ? "checked='checked'" : "" ;

	echo '
	<hr />
	
	<h3>Toggle Taxa/OTU:</h3><br /><br />
	
	<input type="radio" name="taxaOtuToggle" value="both" '.$bothSelected.' onclick="manager_submitForm();" /><b>Both</b>&nbsp;&nbsp;
	<input type="radio" name="taxaOtuToggle" value="taxa" '.$taxaSelected.' onclick="manager_submitForm();"  /><b>Taxa</b>&nbsp;&nbsp;
	<input type="radio" name="taxaOtuToggle" value="otu" '.$otuSelected.' onclick="manager_submitForm();"  /><b>OTU</b>';
}

function collectionFilters() {
	global $objInfo;

	otherFilters();

	$session_collectionToggle = $objInfo->getCollectionToggle();
	$selected = (isset($_GET['characterCollectionToggle'])) ? $_GET['characterCollectionToggle'] : (!empty($session_collectionToggle)) ? $session_collectionToggle : "both";

	$bothSelected = ($selected == "both") ? "checked='checked'" : "" ;
	$characterSelected = ($selected == "character") ? "checked='checked'" : "" ;
	$collectionSelected = ($selected == "collection") ? "checked='checked'" : "" ;

	echo '
	<hr />
	
	<h3>Toggle Character/Collection:</h3><br /><br />
	
	<input type="radio" name="characterCollectionToggle" value="both" '.$bothSelected.' onclick="manager_submitForm();" /><b>Both</b><br />
	<input type="radio" name="characterCollectionToggle" value="character" '.$characterSelected.' onclick="manager_submitForm();"  /><b>Character</b><br />
	<input type="radio" name="characterCollectionToggle" value="collection" '.$collectionSelected.' onclick="manager_submitForm();"  /><b>Collection</b>';
}


function otherFilters() {
	global $config;
	$sortByFields = array ( 	array(	'field' => 'id',
							  	'label' => 'Id',
								'width' => 40,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'DESC'),
	array(	'field' => 'dateToPublish',
							  	'label' => 'Date To Publish',
								'width' => 40,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'DESC'),
	array(	'field' => 'userId',
							  	'label' => 'Contributor',
								'width' => 40,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'DESC'),
	array(	'field' => 'groupId',
							  	'label' => 'Group Id',
								'width' => 40,
								'toSort' => true,
								'inGet' => 0,
								'order' => 'DESC')
	);
	$sortTool = new sort($config->domain, $sortByFields, "manager");

	$sortTool->display();
}







?>
