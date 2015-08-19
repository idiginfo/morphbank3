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

//this file creates the form to edit values for
//given character and its states
//
//@author Karolina Maneva-Jakimoska
//@date created Aug 23 2007
//


include_once('head.inc.php');


include_once('collectionFunctions.inc.php');
include_once('Phylogenetics/phylo.inc.php');

global $config;
checkIfLogged();
$groupId = $objInfo->getUserGroupId();
$userId= $objInfo->getUserId();

$link = Adminlogin();

$title = 'Edit Character Information';
initHtml ($title,NULL, NULL);
echoHead (false, $title);
echoJavaScript();

if(isset($_POST['url']))
$url = $_POST['url'];
else if(isset($_GET['url']))
$url = $_GET['url'];
else
$url = $_SERVER['HTTP_REFERER'];

$pos = strpos($url, $config->domain.'MyManager/');
if($pos===FALSE)
$tab = false;
else
$url = $config->domain . 'MyManager/index.php?tab=collectionTab';

echo '<div class="mainGenericContainer" style="width:750px">
         <h1>Edit Character Information</h1>
           <br/><br/>';


if(isset($_POST['id']))
$id =$_POST['id'];

if(isset($_GET['id']) && $_GET['id']!=null){
	if(!ObjectForUser($link,$_GET['id'],$userId)){
		echo '<span style="color:#17256B" ><h3>This object is contributed/submited by a different user. You do not have privileges to edit it. However you can send an e-mail to the mbadmin team with suggested changes.</h3><br/><br/>';
		echo '<table width="700"><tr>
                            <td align = "right"><a href = "'.$returnUrl.'" class="button smallButton"><div>Return</div></a>
                            </td></tr>
                         </table>
                   </div>';
		finishHtml();
		exit();
	}
	else
	$id = $_GET['id'];
}

$character = new PhyloCharacter($link, $id);
if(isset($_GET['fe']) && $_GET['fe']==1)
echo '<span style="color:red"><b>Some error occured while editing this reccord some information might not have been changed, please try again</b></span><br/><br/>';
if(isset($_GET['fe']) && $_GET['fe']==0)
echo '<span style="color:#17256B"><b>Character information updated successfuly.</b></span><br/><br/>';
?>
<form name="editCharacter" method="post"
	action="commitEditCharacter.php">
<table border="0">
	<tr>
		<td width="20%px"><b>Title: </b></td>
		<td><input type="text" name="title" size="52" maxlength="50"
			value="<? echo $character->getName();?>"
			title="Edit the full title of the character" /> <input type="hidden"
			name="title_old" value="<? echo $character->getName();?>" /></td>
	</tr>
	<tr>
		<td width="20%px"><b>Description: </b></td>
		<td><textarea name="description" rows="4" cols="39"
			title="Edit complete description for the character"><?echo $character->getDescription();?></textarea>
		<input type="hidden" name="description_old"
			value="<?echo $character->getDescription(); ?>" /></td>
		<td>
		<table frame="border" title="Select the character type">
			<caption><b>Character Type: </b></caption>
			<tr>
				<td><input type="radio" name="ordered" value="1"
				<?if ($character->getOrdered()==1){?> checked="checked" <?}?>
					onclick="return false;" /><i>Ordered*</i></td>
				<td><input type="radio" name="ordered" value="0"
				<?if($character->getOrdered()==0) {?> checked="checked" <?}?>
					onclick="return true;">Unordered</td>
				<input type="hidden" name="ordered_old"
					value="<?echo $character->getOrdered()?>" />
			</tr>
			<tr>
				<td><input type="radio" name="discrete" value="1"
					onclick="return true" <? if($character->getDiscrete()==1){?>
					checked="checked" <?}?> />Discrete</td>
				<td><input type="radio" name="discrete" value="0"
					onclick="return false;" <? if($character->getDiscrete()==0){?>
					checked="checked" <?}?> /><i>Continuous*<i></td>
				<input type="hidden" name="discrete_old"
					value="<?echo $character->getDiscrete()?>" />
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td width="20%px"><b>Short title/Label: </b></td>
		<td><input type="text" name="label" size="6" maxlength="6"
			value="<?echo $character->getLabel();?>"
			title="Edit short title for the character as you want to be displayed in the matrix" /></td>
		<input type="hidden" name="label_old"
			value="<?echo $character->getLabel();?>" />
	</tr>
	<tr>
		<td width="20%px"><b>Publication Id/Title: </b></td>
		<td><input type="text" name="publicationId" size="6" maxlength="16"
			value="<? echo $character->getPublicationId();?>"
			title="Select Publication Id/ Name, add one if it does not exist in morphbank" /><b>
		/ </b><input type="text" name="publication" size="40" maxlength="128"
			value="<? $publication = $character->getPublication(); if ($publication!=null) echo $publication['title']; else echo "";?>"
			readonly="true"
			title="Edit Literature Reference Id/Name, add one if it does not exist in morphbank">&nbsp;<a
			href="javascript:openPopup('<?echo $config->domain;?>Browse/ByPublication/?pop=YES')"><img
			src="/style/webImages/selectIcon.png" /></a></td>
		<input type="hidden" name="publicationId_old"
			value="<?echo $character->getPublicationId();?>" />
		<input type="hidden" name="publication_old"
			value="<?echo $character->getPublication()?>" />
	</tr>
	<tr>
		<td width="20%px"><b>Publication Comment: </b></td>
		<td><textarea name="pubComment" rows="4" cols="42"
			title="Edit additional information about the character described in the publication"><?echo $character->getPubComment();?></textarea>
		<input type="hidden" name="pubComment_old"
			value="<? echo $character->getPubComment(); ?>"></td>
	</tr>
	<tr>
		<td width="20%px"><b>Date To Publish: </b></td>
		<td><input type="text" name="dateToPublish"
			value="<?echo $character->getDateToPublish();?>"
			title="Edit date in yyyy-mm-dd format of when the character should become public">
		<input type="hidden" name="dateToPublish_old"
			value="<?echo $character->getDateToPublish();?>" /></td>
		<input type="hidden" name="id" value="<? echo $character->getId();?>">
		<input type="hidden" name="url" value="<? echo $url;?>">
	</tr>
</table>
<br />
<br />
<table>
	<tr>
		<td width="20%px"><b>Character States: </b></td>
		<? displayStateInformation();?>
	</tr>
</table>
<br />
<table align="right">
	<tr>
		<td><a href="javascript:document.editCharacter.submit();"
			class="button smallButton "
			title="Click to submit changes of the Character information to the database">
		<div>Submit</div>
		</a> <a href="<? echo $url; ?>" class="button smallButton"
			title="Click to return to the previuos page">
		<div>Return</div>
		</a></td>
	</tr>
</table>
<span style="color: #17256B"><b>* - Note: Continuous and ordered
characters are not supported in this version</b></span></form>
		<?
		echo '</div>';

		function displayStateInformation(){

			global $link, $id, $character;
			$stateList = $character->getCharacterStates();

			for($i=0;$i<count($stateList);$i++){
				$newState = new CharacterState($link,$stateList[$i]->getObjectId());

				echo '<tr>
            <td></td>
             <td>
              <table>
                <tr>
                  <td><b>Title: </b></td>
                  <td><input type="text" size="25" maxlength="50" name="stateTitle'.$i.'" value="'.$newState->getName().'"'; 
				if(trim($newState->getName())=='Undesignated state') echo 'readonly="true"';
				echo '></td>
                      <input type="hidden" name="stateTitle_old'.$i.'" value="'.$newState->getName().'" />
                </tr>
                <tr>
                  <td><b>Value: </b></td>
                  <td><input type="text" size="15" maxlength="32" name="stateValue'.$i.'" value="'.$newState->getCharStateValue().'"></td>
                      <input type="hidden" name="stateValue_old'.$i.'" value="'.$newState->getCharStateValue().'" />
                </tr>
              </table>
             </td>
             <td align="right"><b>Description: </b></td>
             <td><textarea cols="30" rows="3" name="stateDescription'.$i.'" >'.$newState->getDescription().'</textarea></td>
                 <input type="hidden" name="stateDescription_old'.$i.'" value="'.$newState->getDescription().'" />
                 <input type="hidden" name="stateId'.$i.'" value="'.$newState->getId().'" />
            </tr>';
			}
			echo '<input type="hidden" name="state_count" value="'.count($stateList).'">';
		}

		function ObjectForUser($link,$id,$userId){
			$query ="select userId, submittedBy from BaseObject where id=".$id;
			$result = mysqli_query($link,$query);
			if(mysqli_num_rows($result)<1)
			return false;
			else{
				$row = mysqli_fetch_array($result);
				if($row['userId']==$userId || $row['submittedBy']==$userId)
				return true;
				else
				return false;
			}
		}

		function echoJavaScript(){
			echo '<script type="text/javascript">

                function updatePublication(id,title){

                   document.editCharacter.publication.value = title;
                   document.editCharacter.publicationId.value = id;

                }

                function checkAll(){
                   document.editCharacter.submit();
                }
      </script>';
		}

		finishHtml();

		?>
