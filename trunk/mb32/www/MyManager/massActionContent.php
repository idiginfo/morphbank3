<?php

include_once('head.inc.php');
include_once('MyManager/myManager.class.php');
$id = isset($_GET['actionId'])?$_GET['actionId']:"image";
$myManager = new MyManager($title);
$collectionArray = $myManager->getCollectionArray();
$characterArray = $myManager->getCharacterArray();
$otuArray = $myManager->getOtuArray();

if ($id == "image" || $id == "imageTab") {
	imageActionOptions();
} elseif ($id == "specimen" || $id == "specimenTab"){
	specimenActionOptions();
} elseif ($id == "view" || $id == "viewTab") {
	viewActionOptions();
} elseif ($id == "taxa" || $id == "taxaTab") {
	taxaActionOptions();
} elseif ($id == "collection" || $id == "annotation" || $id == "collectionTab" || $id == "annotationTab") {
	collectionAnnotationActionOptions();
} else {
	defaultActionOptions();
}

function imageActionOptions() {
	global $collectionArray, $characterArray;
	echo '	<select name="massOperation" size="1" style="display:block; width:275px;">
		<option value="" selected="selected"  >Select Mass Operation...</option>
		<option value="" >--------------------</option>
		<option value="changeDate">Change Date to Publish</option>
		<option value="publishNow" >Publish Now</option>
		<option value="copyToNewCollection" >Create New Collection</option>
		<option value="createPhyloChar" >Create New Character</option>
		<!--option value="addExtLinks">Add External Links</option>
		<option value="emailIds">Email Ids</option-->
		<option value="">&nbsp;</option>
		<option value="">--------------------</option>
		<option value="">Copy to existing collection....</option>';
	if ($collectionArray){
		foreach($collectionArray as $array) {
			echo '<option value="'.$array['id'].'">&nbsp;&nbsp;'.$array['name'].'</option>';
		}
	}
	echo '
		<option value="">&nbsp;</option>
		<option value="">Copy to existing character....</option>';
	// no value for $characterArray for 'keywords' search from '/?keywords=...' and other contexts GR
	if ($characterArray){
		foreach($characterArray as $array) {
			echo '<option value="characterId='.$array['id'].'">&nbsp;&nbsp;'.$array['name'].'</option>';
		}
	}
	echo'	</select>';
}

function specimenActionOptions() {
	global $collectionArray, $characterArray, $otuArray;
	echo '
	<select name="massOperation" size="1" style="display:block; width:275px;">
		<option value="" selected="selected"  >Select Mass Operation...</option>
		<option value="" >--------------------</option>
		<option value="copyToNewCollection" >Create New Collection</option>
		<option value="createOTU" >Create New OTU</option>
		<!--option value="addExtLinks">Add External Links</option>
		<option value="emailIds">Email Ids</option-->
		<option value="">&nbsp;</option>
		<option value="">--------------------</option>
		<option value="">Copy to existing collection....</option>';
	if ($collectionArray){
		foreach($collectionArray as $array) {
			echo '<option value="'.$array['id'].'">&nbsp;&nbsp;'.$array['name'].'</option>';
		}
	}
	echo '
		<option value="">&nbsp;</option>		
		<option value="">Copy to existing OTU....</option>';
	if($otuArray) {
		foreach($otuArray as $array) {
			echo '<option value="Specimen_otuId='.$array['id'].'">&nbsp;&nbsp;'.$array['name'].'</option>';
		}
	}
	echo'	</select>';
}

function viewActionOptions() {
	global $collectionArray, $characterArray;
	echo '
	<select name="massOperation" size="1" style="display:block; width:275px;">
		<option value="" selected="selected"  >Select Mass Operation...</option>
		<option value="" >--------------------</option>
		<option value="copyToNewCollection" >Create New Collection</option>
		<!--option value="addExtLinks">Add External Links</option>
		<option value="emailIds">Email Ids</option-->
		<option value="">&nbsp;</option>
		<option value="">--------------------</option>
		<option value="">Copy to existing collection....</option>';
	if($collectionArray) {
		foreach($collectionArray as $array) {
			echo '<option value="'.$array['id'].'">&nbsp;&nbsp;'.$array['name'].'</option>';
		}
	}
	echo '		<option value="">&nbsp;</option>';
	echo'	</select>';
}

function localityActionOptions() {
	global $collectionArray, $characterArray;
	echo '
	<select name="massOperation" size="1" style="display:block; width:275px;">
		<option value="" selected="selected"  >Select Mass Operation...</option>
		<option value="" >--------------------</option>
		<option value="copyToNewCollection" >Create New Collection</option>
		<!--option value="createPhyloChar" >Create New Character</option-->		
		<!--option value="addExtLinks">Add External Links</option>
		<option value="emailIds">Email Ids</option-->
		<option value="">&nbsp;</option>
		<option value="">--------------------</option>
		<option value="">Copy to existing collection....</option>';
	if($collectionArray) {
		foreach($collectionArray as $array) {
			echo '<option value="'.$array['id'].'">&nbsp;&nbsp;'.$array['name'].'</option>';
		}
	}
	echo '		<option value="">&nbsp;</option>';
	echo '</select>';
}

function taxaActionOptions() {
	global $collectionArray, $characterArray, $otuArray;
	echo '
	<select name="massOperation" size="1" style="display:block; width:275px;">
		<option value="" selected="selected"  >Select Mass Operation...</option>
		<option value="" >--------------------</option>
		<!--option value="copyToNewCollection" >Create New Collection</option-->
		<option value="createOTU" >Create New OTU</option>
		<!--option value="publishNow" >Publish Now</option-->
		<!--option value="addExtLinks">Add External Links</option>
		<option value="emailIds">Email Ids</option-->
		<option value="">&nbsp;</option>
		<option value="">--------------------</option>';
	echo '
		<option value="">&nbsp;</option>
		<option value="">Copy to existing OTU....</option>';
	if($otuArray) {
		foreach($otuArray as $array) {
			echo '<option value="Tsn_otuId='.$array['id'].'">&nbsp;&nbsp;'.$array['name'].'</option>';
		}
	}
	echo '	</select>';
}

function collectionAnnotationActionOptions() {
	global $collectionArray, $characterArray;
	echo '
	<select name="massOperation" size="1" style="display:block; width:275px;">
		<option value="" selected="selected"  >Select Mass Operation...</option>
		<option value="" >--------------------</option>
		<option value="changeDate" >Change Date to Publish</option>
		<option value="publishNow" >Publish Now</option>
		<option value="copyToNewCollection" >Create New Collection</option>
		<!--option value="createPhyloChar" >Create New Character</option-->		
		<option value="deleteObjects">Delete Checked Objects</option>
		<!--option value="addExtLinks">Add External Links</option>
		<option value="emailIds">Email Ids</option-->
		<option value="">&nbsp;</option>
		<option value="">--------------------</option>
		<option value="">Copy to existing collection....</option>';
	if ($collectionArray) {
		foreach($collectionArray as $array) {
			echo '<option value="'.$array['id'].'">&nbsp;&nbsp;'.$array['name'].'</option>';
		}
	}
	echo '		<option value="">&nbsp;</option>';
	echo '	</select>';
}

function publicationActionOptions() {
	global $collectionArray, $characterArray;
	echo '
	<select name="massOperation" size="1" style="display:block; width:275px;">
		<option value="" selected="selected"  >Select Mass Operation...</option>
		<option value="" >--------------------</option>
		<option value="copyToNewCollection" >Create New Collection</option>
		<!--option value="createPhyloChar" >Create New Character</option-->		
		<!--option value="addExtLinks">Add External Links</option>
		<option value="emailIds">Email Ids</option-->
		<option value="">&nbsp;</option>
		<option value="">--------------------</option>
		<option value="">Copy to existing collection....</option>';
	foreach($collectionArray as $array) {
		echo '<option value="'.$array['id'].'">&nbsp;&nbsp;'.$array['name'].'</option>';
	}
	echo '		<option value="">&nbsp;</option>';
	echo'	</select>';
}

function defaultActionOptions() {
	global $collectionArray, $characterArray;
	echo '
	<select name="massOperation" size="1" style="display:block; width:275px;">
		<option value="" selected="selected"  >Select Mass Operation...</option>
		<option value="" >--------------------</option>
		<option value="copyToNewCollection" >Create New Collection</option>
		<!--option value="createPhyloChar" >Create New Character</option-->
		<!--option value="addExtLinks">Add External Links</option>
		<option value="emailIds">Email Ids</option-->
		<option value="">&nbsp;</option>
		<option value="">--------------------</option>
		<option value="">Copy to existing collection....</option>';
	if($collectionArray) {
		foreach($collectionArray as $array) {
			echo '<option value="'.$array['id'].'">&nbsp;&nbsp;'.$array['name'].'</option>';
		}
	}
	echo '		<option value="">&nbsp;</option>';
	echo '	</select>';
}
?>
