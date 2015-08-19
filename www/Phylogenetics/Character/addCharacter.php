<?php
/**
* Copyright (c) 2011 Greg Riccardi, Fredrik Ronquist.
* All rights reserved. This program and the accompanying materials
* are made available under the terms of the GNU Public License v2.0
* which accompanies this distribution, and is available at
* http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
* 
* Contributors:
*   Fredrik Ronquist - conceptual modeling and interaction design
*   Austin Mast - conceptual modeling and interaction design
*   Greg Riccardi - initial API and implementation
*   Wilfredo Blanco - initial API and implementation
*   Robert Bruhn - initial API and implementation
*   Christopher Cprek - initial API and implementation
*   David Gaitros - initial API and implementation
*   Neelima Jammigumpula - initial API and implementation
*   Karolina Maneva-Jakimoska - initial API and implementation
*   Deborah Paul - initial API and implementation implementation
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

//this file creates the form for user provided values for
//given phylogenetic character
//
//@author Karolina Maneva-Jakimoska
//@date created June 26 2007
//
include_once('head.inc.php');


include_once('collectionFunctions.inc.php');
include_once('Phylogenetics/phylo.inc.php');

global $config;
//checkIfLogged();
$link = Adminlogin();

$title = 'Add Character Information';
initHtml ($title,NULL, NULL);
echoHead (false, $title);
echoJavaScript();

echo '<div class="mainGenericContainer" style="width:700px">
         <h1>Add Character Information</h1>
           <br/><br/>';

if(isset($_POST['id'])){
	$id =$_POST['id'];
	$collection = new Collection($link,$id);
	$arrayList = getIdArrayFromPost();
	$collection->madeCharacterFromCollection($arrayList);
}
?>
<form name="addCharacter" method="post" action="commitCharacter.php">
<table border="0">
	<tr>
		<td><b>Title: </b></td>
		<td><input type="text" name="title" size="52" maxlength="50"
			title="Enter the full title of the character" /></td>
	</tr>
	<tr>
		<td><b>Description: </b></td>
		<td><textarea name="description" rows="4" cols="39"
			title="Enter complete description for the character"></textarea></td>
		<td>
		<table frame="border" title="Select the character type">
			<caption><b>Character Type: </b></caption>
			<tr>
				<td><input type="radio" name="ordered" value="1"
					onclick="return false; " /><i>Ordered*</i></td>
				<td><input type="radio" name="ordered" value="0" checked="checked"
					onclick="return true; ">Unordered</td>
			</tr>
			<tr>
				<td><input type="radio" name="discrete" value="1" checked="checked"
					onclick="return true;" />Discrete</td>
				<td><input type="radio" name="discrete" value="0"
					onclick="return false;" /><i>Continuous*</i></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td><b>Short title/Label: </b></td>
		<td><input type="text" name="label" size="6" maxlength="6"
			title="Enter short title for the character as you want to be displayed in the matrix" /></td>
	</tr>
	<tr>
		<td><b>Publication Id/Title: </b></td>
		<td><input type="text" name="reference_id" size="6" maxlength="16"
			title="Select Publication Id/ Name, add one if it does not exist in morphbank" /><b>
		/ </b><input type="text" name="reference" size="37" maxlength="128"
			readonly="true"
			title="Select Literature Reference Id/Name, add one if it does not exist in morphbank">
		&nbsp;<a
			href="javascript:openPopup('<?echo $config->domain;?>Browse/ByPublication/?pop=YES')"><img
			src="/style/webImages/selectIcon.png" /></a></td>
	</tr>
	<tr>
		<td><b>Publication Comment: </b></td>
		<td><textarea name="pubComment" rows="4" cols="42"
			title="Enter additional information about the character described in the publication"></textarea></td>
		<input type="hidden" name="id" value="<? echo $_POST['id']?>">
	</tr>
	<tr>
		<td><b>Date To Publish: </b></td>
		<td><input type="text" name="dateToPublish"
			value="<?echo $collection->getDateToPublish();?> "
			title="Enter date in yyyy-mm-dd format of when the character should become public"></td>
		<input type="hidden" name="id" value="<? echo $_POST['id']?>">
	</tr>


</table>
<br />
<br />
<table align="right">
	<tr>
		<td><a href="javascript:checkAll();" class="button smallButton "
			title="Click to add PhyloCharacter information to the database">
		<div>Submit</div>
		</a> <a
			href="<? echo $config->domain;?>Phylogenetics/Character/cancelCharacter.php?id=<? echo $id;?>"
			class="button smallButton" title="Click to cancel the action">
		<div>Cancel</div>
		</a></td>
	</tr>
</table>
<span style="color: #17256B"><b>* - Note: Continuous and ordered
characters are not supported in this version</b></span></form>
<?
echo '</div>';

function echoJavaScript(){
	echo '<script type="text/javascript">

                function updatePublication(id,title){

                   document.addCharacter.reference.value = title;
                   document.addCharacter.reference_id.value = id;

                }

                function checkAll(){
                   document.addCharacter.submit();
                }
      </script>';
}

finishHtml();

?>
