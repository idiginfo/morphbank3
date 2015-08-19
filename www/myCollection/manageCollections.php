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

include_once('head.inc.php');
checkIfLogged();
groups();
include_once('gettables.inc.php');
include_once ('collectionFunctions.inc.php');
include_once('mainMyCollection.php');

$link = Adminlogin();

if ($_POST['formFlag'] == "update")
	updateCollections();
elseif ($_POST['formFlag'] == "delete")
	deleteCollection($_POST['flagData']);
	

if (!$_GET['orderBy'])
	$orderBy = 'id';
else
	$orderBy = $_GET['orderBy'];

if (!$_GET['order'])
	$order = 'DESC';
else 
	$order = $_GET['order'];
	
if (!$_GET['orderFlag'])
	$orderFlag = 'id';
else 
	$orderFlag = $_GET['orderFlag'];

// The beginnig of HTML
$title = 'My Collection';

$spryScript = '<script language="javascript" type="text/javascript" src="'.$config->domain.'js/spry/SpryValidationTextField.js"></script>';
$spryScript .= '<style type="text/css">@import url(/style/SpryValidationTextField.css);</style>';
initHtml( $title, $spryScript);

// Add the standard head section to all the HTML output.
echoHead( false, $title);



echo'

	<div class="mainGenericContainer" style="width: 770px;">';
		outputCollectionTable();
echo'
		
	</div>'; //mainGenericContainer

// Finish with end of HTML
finishHtml();


function outputCollectionTable() {
	global $objInfo, $orderBy, $order;
	
	$collectionArray = getCollectionArray($objInfo->getUserId(), $objInfo->getUserGroupId(), $orderBy, $order);

echo '<div style="float:left;">
	<h1>Collection Manager</h1></div>';
	
	if ($collectionArray)
		echo' <a href="javascript: submitCollectionForm(\'update\', \'default\');" class="button smallButton right"><div>Update</div></a>';
		
	echo '<br /><br /><br />';

	if ($_POST['formFlag'])
		echo '<h2 class="red">Update Successful !</h2><br /><br />';
	echo '
	
<form action="manageCollections.php" method="post" name="collectionForm">
	<table class="manageCollectionTable" width="100%" border="0" cellspacing="0" cellpadding="0">'; 	
		outputTableHeaders();
		
		$numOfDateFields = 0;
		if ($collectionArray) {
			for ($i = 0; $i < $collectionArray[0]['numRows']; $i++) {
			echo '
				<tr valign="bottom">
					<td class="first">';						
				echo '<a href="index.php?collectionId='.$collectionArray[$i]['id'].'" title="Go to collection '.$collectionArray[$i]['id'].'"><strong>['.$collectionArray[$i]['id'].']</strong></a>
					</td>
					
					<td title="Collection Name">';
					if ($collectionArray[$i]['dateToPublish'] > $collectionArray[$i]['now']) {
						echo '
						<input type="text" name="name'.$collectionArray[$i]['id'].'" value="'.$collectionArray[$i]['name'].'" onkeypress="return submitenter(event)" ';
						if ($collectionArray[$i]['id'] == $_GET['newCollectionId'])
							echo 'id="newCollection" />';
						else
							echo ' />';
					}
					else {
						echo $collectionArray[$i]['name'];
						echo '<input type="hidden" name="name'.$collectionArray[$i]['id'].'" value="'.$collectionArray[$i]['name'].'" />';
					}							
					echo '</td>
					
					<td title="YYYY-MM-DD" >';
					if ($collectionArray[$i]['dateToPublish'] > $collectionArray[$i]['now']) {
						echo '
						<span id="date'.$numOfDateFields.'">
								<input type="text" name="dateToPublish'.$collectionArray[$i]['id'].'" value="'.$collectionArray[$i]['dateToPublish'].'" onkeypress="return submitenter(event)" />
								<span class="textfieldRequiredMsg">The value is required. yyyy-mm-dd</span>
						
								<span class="textfieldInvalidFormatMsg">Invalid format. yyyy-mm-dd</span>
						</span>';
						//echo '<input type="text" name="dateToPublish'.$collectionArray[$i]['id'].'" value="'.$collectionArray[$i]['dateToPublish'].'" onkeypress="return submitenter(event)" />';
						$numOfDateFields++;
					}
					else {
						echo $collectionArray[$i]['dateToPublish'];
						echo '<input type="hidden" name="dateToPublish'.$collectionArray[$i]['id'].'" value="'.$collectionArray[$i]['dateToPublish'].'" />';						
					}
					echo '
					</td>
					
					<td title="YYYY-MM-DD" >'.$collectionArray[$i]['dateCreated'].'</td>
					<td title="YYYY-MM-DD" >'.$collectionArray[$i]['dateLastModified'].'</td>
					<td title="Number of objects in Collection"><center><strong>['.$collectionArray[$i]['objectCount'].']</strong></center></td>
					
					<td class="last">
						<center>';
						if ($collectionArray[$i]['dateToPublish'] > $collectionArray[$i]['now']) 
							echo '<a href="javascript: confirmDelete(\''.$collectionArray[$i]['id'].'\');" ><img src="/style/webImages/delete-trans.png" alt="delete" title="Delete Collection" width="16" height="16" /></a>';
						else
							echo '<img src="/style/webImages/deleteGrey-trans.png" alt="delete" title="Can\'t Delete a Published Collection" width="16" height="16" />';
					echo '
						</center>
					</td>
					
				</tr>';			
			}		
		}
		else 
			echo '<tr><td colspan="7" class="last"><h1>No Collections for User: <strong>'.$objInfo->getName().'</strong><br /> in Group: <strong>'.$objInfo->getUserGroup().'</strong>&nbsp;&nbsp;<a href="javascript:history.go(-1);" class="button smallButton"><div>Back</div></a></td></tr>';
	echo '
		
	</table>
	<!--input type="submit" value="submit" /-->
	<input type="hidden" name="formFlag" value="default" />
	<input type="hidden" name="flagData" value="default" />
</form>
	<br />';
	
	
	echoJavascript($numOfDateFields);
	
	if ($collectionArray)
		echo' <a href="javascript: submitCollectionForm(\'update\', \'default\');" class="button smallButton right"><div>Update</div></a>';


}

function outputTableHeaders() {
global $orderBy, $order, $orderFlag;
$width = '8';
$height = '7';
echo '
	<tr>
		<th class="first" title="Click to sort by id">';
			if ($orderFlag == 'id' && $order == 'ASC') 
				echo '<a href="manageCollections.php?orderBy=id&amp;order=DESC&amp;orderFlag=id">id</a><img src="/style/webImages/sortArrowUp-trans.png" width="'.$width.'" height="'.$height.'" align="top" alt="Sort"  />';
			elseif ($orderFlag == 'id' && $order == 'DESC') 
				echo '<a href="manageCollections.php?orderBy=id&amp;order=ASC&amp;orderFlag=id">id</a><img src="/style/webImages/sortArrowDown-trans.png" width="'.$width.'" height="'.$height.'" align="top" alt="Sort"  />';
			else
				echo '<a href="manageCollections.php?orderBy=id&amp;order=ASC&amp;orderFlag=id">id</a>';
		echo '
		</th>
		
		<th title="Click to sort by Collection Name">';
			if ($orderFlag == 'name' && $order == 'ASC') 
				echo '<a href="manageCollections.php?orderBy=BaseObject.name&amp;order=DESC&amp;orderFlag=name">Name</a><img src="/style/webImages/sortArrowUp-trans.png" width="'.$width.'" height="'.$height.'" align="top" alt="Sort"  />';
			elseif ($orderFlag == 'name' && $order == 'DESC') 
				echo '<a href="manageCollections.php?orderBy=BaseObject.name&amp;order=ASC&amp;orderFlag=name">Name</a><img src="/style/webImages/sortArrowDown-trans.png" width="'.$width.'" height="'.$height.'" align="top" alt="Sort"  />';
			else
				echo '<a href="manageCollections.php?orderBy=BaseObject.name&amp;order=ASC&amp;orderFlag=name">Name</a>';
			
		echo '
		</th>
		
		<th title="Click to sort by Date to Publish">';
			if ($orderFlag == 'publish' && $order == 'ASC') 
				echo '<a href="manageCollections.php?orderBy=BaseObject.dateToPublish&amp;order=DESC&amp;orderFlag=publish">Date to Publish</a><img src="/style/webImages/sortArrowUp-trans.png" width="'.$width.'" height="'.$height.'" align="top" alt="Sort"  />';
			elseif ($orderFlag == 'publish' && $order == 'DESC') 
				echo '<a href="manageCollections.php?orderBy=BaseObject.dateToPublish&amp;order=ASC&amp;orderFlag=publish">Date to Publish</a><img src="/style/webImages/sortArrowDown-trans.png" width="'.$width.'" height="'.$height.'" align="top" alt="Sort"  />';
			else
				echo '<a href="manageCollections.php?orderBy=BaseObject.dateToPublish&amp;order=ASC&amp;orderFlag=publish">Date to Publish</a>';
		echo '
		</th>
		
		<th width="120" title="Click to sort by Date Created">';
			if ($orderFlag == 'created' && $order == 'ASC') 
				echo '<a href="manageCollections.php?orderBy=BaseObject.dateCreated&amp;order=DESC&amp;orderFlag=created">Date Created</a><img src="/style/webImages/sortArrowUp-trans.png" width="'.$width.'" height="'.$height.'" align="top" alt="Sort"  />';
			elseif ($orderFlag == 'created' && $order == 'DESC') 
				echo '<a href="manageCollections.php?orderBy=BaseObject.dateCreated&amp;order=ASC&amp;orderFlag=created">Date Created</a><img src="/style/webImages/sortArrowDown-trans.png" width="'.$width.'" height="'.$height.'" align="top" alt="Sort"  />';
			else
				echo '<a href="manageCollections.php?orderBy=BaseObject.dateCreated&amp;order=ASC&amp;orderFlag=created">Date Created</a>';
		echo '	
		</th>
		
		<th width="120" title="Click to sort by Date Last Modified">';
			if ($orderFlag == 'modified' && $order == 'ASC') 
				echo '<a href="manageCollections.php?orderBy=BaseObject.dateLastModified&amp;order=DESC&amp;orderFlag=modified">Last Modified</a><img src="/style/webImages/sortArrowUp-trans.png" width="'.$width.'" height="'.$height.'" align="top" alt="Sort"  />';
			elseif ($orderFlag == 'modified' && $order == 'DESC') 
				echo '<a href="manageCollections.php?orderBy=BaseObject.dateLastModified&amp;order=ASC&amp;orderFlag=modified">Last Modified</a><img src="/style/webImages/sortArrowDown-trans.png" width="'.$width.'" height="'.$height.'" align="top" alt="Sort"  />';
			else
				echo '<a href="manageCollections.php?orderBy=BaseObject.dateLastModified&amp;order=ASC&amp;orderFlag=modified">Last Modified</a>';
		echo '	
		</th>
		
		<th width="65" title="Number of objects in Collection">';
			if ($orderFlag == 'objects' && $order == 'ASC') 
				echo '<a href="manageCollections.php?orderBy=objectCount&amp;order=DESC&amp;orderFlag=objects">Objects</a><img src="/style/webImages/sortArrowUp-trans.png" width="'.$width.'" height="'.$height.'" align="top" alt="Sort"  />';
			elseif ($orderFlag == 'objects' && $order == 'DESC') 
				echo '<a href="manageCollections.php?orderBy=objectCount&amp;order=ASC&amp;orderFlag=objects">Objects</a><img src="/style/webImages/sortArrowDown-trans.png" width="'.$width.'" height="'.$height.'" align="top" alt="Sort"  />';
			else
				echo '<a href="manageCollections.php?orderBy=objectCount&amp;order=ASC&amp;orderFlag=objects">Objects</a>';	
		echo '	
		</th>
		
		<th class="last">Delete</th>
	</tr>';

}

function getCollectionArray($userId, $groupId, $orderBy = 'id', $order = 'ASC') {
	global $link;
	
	$sql = 'SELECT BaseObject.id AS id, BaseObject.userId, BaseObject.groupId, BaseObject.objectTypeId, '
		  .'DATE_FORMAT(BaseObject.dateCreated, \'%Y-%m-%d\') AS dateCreated, ' 
		  .'DATE_FORMAT(BaseObject.dateLastModified, \'%Y-%m-%d\') AS dateLastModified, '
		  .'DATE_FORMAT(BaseObject.dateToPublish, \'%Y-%m-%d\') AS dateToPublish, '
		  .'BaseObject.description, '
		  .'BaseObject.name, '
		  .'(SELECT COUNT(*) FROM CollectionObjects WHERE CollectionObjects.collectionId = BaseObject.id ) AS objectCount, '
		  .'DATE_FORMAT(NOW(), \'%Y-%m-%d\') AS now '
		  .'FROM BaseObject '
		  .'WHERE BaseObject.userId ='.$userId.' AND BaseObject.groupId = '.$groupId.' AND (BaseObject.objectTypeId = \'Collection\' OR BaseObject.objectTypeId = \'PhyloCharacter\' OR BaseObject.objectTypeId = \'MbCharacter\') '
		  .'ORDER BY '.$orderBy.' '.$order.'' ; 
	
	$results = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	
	if ($results) {
		$numRows = mysqli_num_rows($results);
		
		if ($numRows > 0) {
		
			for ($i = 0; $i < $numRows; $i++) 
				$collectionArray[$i] = mysqli_fetch_array($results);				
			
			$collectionArray[0]['numRows'] = $numRows;
			
			return $collectionArray;
		}	
		else 
			return FALSE;
	} else
		return FALSE;
}

function updateCollections() {
	global $link, $objInfo;
	$collectionArray = getUserCollectionArray($objInfo->getUserId(), $objInfo->getUserGroupId());
	
	if ($collectionArray) {
		$numRows = count($collectionArray);
		
		for ($i = 0; $i < $numRows; $i++) {
			$collectionName = str_replace("'", "", $_POST['name'.$collectionArray[$i]['id'].'']);
			$sql = 'UPDATE Collection SET name = \''.$collectionName.'\' WHERE id = \''.$collectionArray[$i]['id'].'\' ';
			mysqli_query($link, $sql);
			//echo $sql.'<br />';
			$sql = 'UPDATE BaseObject SET dateToPublish = STR_TO_DATE(\''.$_POST['dateToPublish'.$collectionArray[$i]['id'].''].'\', \'%Y-%m-%d\') WHERE id = \''.$collectionArray[$i]['id'].'\' ';
			mysqli_query($link, $sql);
			//echo $sql.'<br /><br />';	
			updateCollectionKeywords($collectionArray[$i]['id']);		
		}
	
	}
}


/*function echoJavaScriptFocus($numRows) {
echo '
	<script type="text/javascript" language="JavaScript">
		<!--			
			if (document.collectionForm.newCollection) {
				document.collectionForm.newCollection.focus();
				document.collectionForm.newCollection.select();
			}';
		for ($i=0; $i < $numRows; $i++) {
			echo 'var date'.$i.' = new Spry.Widget.ValidationTextField("date'.$i.'", "date", {format:"yyyy-mm-dd", hint:"yyyy-mm-dd", validateOn:["blur"], useCharacterMasking:true});';
   		}
		echo '
		//-->
	</script>';

}
*/
function echoJavascript($numRows) {
echo '
	<script type="text/javascript" language="JavaScript">
		<!--
			if (document.collectionForm.newCollection) {
				document.collectionForm.newCollection.focus();
				document.collectionForm.newCollection.select();
			}';
			
			for ($i=0; $i < $numRows; $i++) {
				echo 'var date'.$i.' = new Spry.Widget.ValidationTextField("date'.$i.'", "date", {format:"yyyy-mm-dd", hint:"yyyy-mm-dd", validateOn:["blur"], useCharacterMasking:true});';
			}
			
			echo '
			function confirmDelete(id) {
				var checkDelete = confirm("Delete Collection?");
				
				if (checkDelete) {
					submitCollectionForm("delete", id);				
				}		
			}
			
			function submitCollectionForm(formFlag, flagData) {
				var formId = document.collectionForm;
				formId.formFlag.value = formFlag;
				formId.flagData.value = flagData;';
				for ($i=0; $i < $numRows; $i++) {
					echo 'var bool'.$i.' = date'.$i.'.validate();';
   				}
				
				
				echo '
				var okToSubmit = true;';
				for ($i=0; $i < $numRows; $i++) {
					echo 'if (!bool'.$i.') okToSubmit = false;';
   				}
				echo'
					if (okToSubmit)
						formId.submit();		
			}
			
			function submitenter(e) {
				var keycode;
				if (window.event) keycode = window.event.keyCode;
				else if (e) keycode = e.which;
				else return true;
				
				if (keycode == 13) {
				   submitCollectionForm(\'update\', \'default\');
				}
				else
				   return true;
			}
  	 	//-->
	</script>';

}


?>
