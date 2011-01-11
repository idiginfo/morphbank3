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

$link = Adminlogin();

if ($_POST['formFlag'] == "update")
updateAnnotations();
elseif ($_POST['formFlag'] == "delete")
deleteAnnotation($_POST['flagData']);

if (!$_GET['orderBy'])
$orderBy = 'Annotation.id';
else
$orderBy = $_GET['orderBy'];

if (!$_GET['order'])
$order = 'ASC';
else
$order = $_GET['order'];

if (!$_GET['orderFlag'])
$orderFlag = 'id';
else
$orderFlag = $_GET['orderFlag'];

// The beginnig of HTML
$title = 'Annotation Manager';
initHtml($title, null, null);

// Add the standard head section to all the HTML output.
echoHead(false, $title);

echoJavascript();

echo '

  <div class="mainGenericContainer" style="width: 870px;">';
outputTable();
//mainGenericContainer
echo '</div>';

// Finish with end of HTML
finishHtml();


function outputTable()
{
	global $objInfo, $orderBy, $order, $config;
	$annotationArray = getAnnotationArray($objInfo->getUserId(), $objInfo->getUserGroupId(), $orderBy, $order);
	echo '<div style="float:left;">
  <h1>Annotation Manager</h1></div>';
	if ($annotationArray)
	echo '<a href="javascript: submitAnnotationForm(\'update\', \'default\');" class="button smallButton right"><div>Update</div></a>';
	echo '
  <br /><br /><br />';
	if ($_POST['formFlag'] == "update")
	echo '<h2 class="red">Update Successful !</h2><br /><br />';
	elseif ($_POST['formFlag'] == "delete")
	echo '<h2 class="red">Annotation Deleted !</h2><br /><br />';
	echo '
  <table class="manageCollectionTable" width="100%" border="0" cellspacing="0" cellpadding="0"> 
    <form action="annotationManager.php" method="post" name="annotationForm">';

	outputTableHeaders();

	if ($annotationArray) {
		for ($i = 0; $i < $annotationArray[0]['numRows']; $i++) {
			echo '
        <tr valign="bottom">
          <td class="first">';
			echo '<a href="' . $config->domain . 'Show/?id=' . $annotationArray[$i]['id'] . '" title="Go to Show Annotation" >';
			echo '<strong>' . $annotationArray[$i]['id'] . '</strong></a>
          </td>
          
          <td>';
			echo '<a href="' . $config->domain . 'Edit/Annotation/index.php?id=' . $annotationArray[$i]['id'] . '" title="Go to annotation Edit ">' . $annotationArray[$i]['title'] . '</a>
          </td>
          
          <td>';

			echo $annotationArray[$i]['typeAnnotation'] . '
          </td>
          <td>';
			echo '<a href="' . $config->domain . 'Show/?id=' . $annotationArray[$i]['objectId'] . '" title="Go to Object Show">' . $annotationArray[$i]['objectId'] . '</a>
          </td>
          
          <td>' . $annotationArray[$i]['objectTypeId'] . '</td>
          <td><span title="YYYY-MM-DD">' . $annotationArray[$i]['dateCreated'] . '</span></td>
          <td>';
			if ($annotationArray[$i]['dateToPublish'] > $annotationArray[$i]['now'])
			echo '<input type="text" name="dateToPublish' . $annotationArray[$i]['id'] . '" size="13" title="YYYY-MM-DD" value="' . $annotationArray[$i]['dateToPublish'] . '">';
			else {
				echo '<span title="YYYY-MM-DD">' . $annotationArray[$i]['dateToPublish'] . '</span>';
				echo '<input type="hidden" name="dateToPublish' . $annotationArray[$i]['id'] . '" value="' . $annotationArray[$i]['dateToPublish'] . '">';
			}
			echo '</td>
          <td><a href="' . $config->domain . 'Annotation/index.php?id=' . $annotationArray[$i]['objectId'] . '" title="Add an Annotation for Object: ' . $annotationArray[$i]['objectId'] . '">add</a></td>
          <td class="last">
            <center>';
			if ($annotationArray[$i]['dateToPublish'] > $annotationArray[$i]['now'])
			echo '<a href="javascript: confirmDelete(\'' . $annotationArray[$i]['id'] . '\');" ><img src="/style/webImages/delete-trans.png" alt="delete" title="Delete Annotation" width="16" height="16" /></a>';
			else
			echo '<img src="/style/webImages/deleteGrey-trans.png" alt="delete" title="Can\'t Delete a Published Annotation" width="16" height="16" />';
			echo '
            </center>
          </td>
        </tr>';
		}
	} else
	echo '<tr><td colspan="9" class="last"><h1>No Annotations for User: <strong>' . $objInfo->getName() . '</strong></td></tr>';

	echo '
    <input type="hidden" name="formFlag" value="default" />
    <input type="hidden" name="flagData" value="default" />
    </form>
  </table><br />';

	if ($annotationArray)
	echo '<a href="javascript: submitAnnotationForm(\'update\', \'default\');" class="button smallButton right"><div>Update</div></a>';
}

function outputTableHeaders()
{
	global $orderBy, $order, $orderFlag;
	$width = '8';
	$height = '7';
	echo '
  <tr>
    <th class="first">';
	if ($orderFlag == 'id' && $order == 'ASC')
	echo '<a href="annotationManager.php?orderBy=id&amp;order=DESC&amp;orderFlag=id">Id</a><img src="/style/webImages/sortArrowUp-trans.png" width="' . $width . '" height="' . $height . '" align="top"  />';
	elseif ($orderFlag == 'id' && $order == 'DESC')
	echo '<a href="annotationManager.php?orderBy=id&amp;order=ASC&amp;orderFlag=id">Id</a><img src="/style/webImages/sortArrowDown-trans.png" width="' . $width . '" height="' . $height . '" align="top"  />';
	else
	echo '<a href="annotationManager.php?orderBy=id&amp;order=ASC&amp;orderFlag=id">Id</a>';
	echo '
    </th>
    
    <th>';
	if ($orderFlag == 'title' && $order == 'ASC')
	echo '<a href="annotationManager.php?orderBy=Annotation.title&amp;order=DESC&amp;orderFlag=title">Title</a><img src="/style/webImages/sortArrowUp-trans.png" width="' . $width . '" height="' . $height . '" align="top"  />';
	elseif ($orderFlag == 'title' && $order == 'DESC')
	echo '<a href="annotationManager.php?orderBy=Annotation.title&amp;order=ASC&amp;orderFlag=title">Title</a><img src="/style/webImages/sortArrowDown-trans.png" width="' . $width . '" height="' . $height . '" align="top"  />';
	else
	echo '<a href="annotationManager.php?orderBy=Annotation.title&amp;order=ASC&amp;orderFlag=title">Title</a>';
	echo '
    </th>
    
    <th width="120">';
	if ($orderFlag == 'type' && $order == 'ASC')
	echo '<a href="annotationManager.php?orderBy=Annotation.typeAnnotation&amp;order=DESC&amp;orderFlag=type">Type Annotation</a><img src="/style/webImages/sortArrowUp-trans.png" width="' . $width . '" height="' . $height . '" align="top"  />';
	elseif ($orderFlag == 'type' && $order == 'DESC')
	echo '<a href="annotationManager.php?orderBy=Annotation.typeAnnotation&amp;order=ASC&amp;orderFlag=type">Type Annotation</a><img src="/style/webImages/sortArrowDown-trans.png" width="' . $width . '" height="' . $height . '" align="top"  />';
	else
	echo '<a href="annotationManager.php?orderBy=Annotation.typeAnnotation&amp;order=ASC&amp;orderFlag=type">Type Annotation</a>';

	echo '
    </th>
    
    <th width="80">';
	if ($orderFlag == 'objectId' && $order == 'ASC')
	echo '<a href="annotationManager.php?orderBy=Annotation.objectId&amp;order=DESC&amp;orderFlag=objectId">Object Id</a><img src="/style/webImages/sortArrowUp-trans.png" width="' . $width . '" height="' . $height . '" align="top"  />';
	elseif ($orderFlag == 'objectId' && $order == 'DESC')
	echo '<a href="annotationManager.php?orderBy=Annotation.objectId&amp;order=ASC&amp;orderFlag=objectId">Object Id</a><img src="/style/webImages/sortArrowDown-trans.png" width="' . $width . '" height="' . $height . '" align="top"  />';
	else
	echo '<a href="annotationManager.php?orderBy=Annotation.objectId&amp;order=ASC&amp;orderFlag=objectId">Object Id</a>';
	echo '
    </th>
    
    <th width="50">';
	if ($orderFlag == 'objectType' && $order == 'ASC')
	echo '<a href="annotationManager.php?orderBy=Annotation.objectTypeId&amp;order=DESC&amp;orderFlag=objectType">Object</a><img src="/style/webImages/sortArrowUp-trans.png" width="' . $width . '" height="' . $height . '" align="top"  />';
	elseif ($orderFlag == 'objectType' && $order == 'DESC')
	echo '<a href="annotationManager.php?orderBy=Annotation.objectTypeId&amp;order=ASC&amp;orderFlag=objectType">Object</a><img src="/style/webImages/sortArrowDown-trans.png" width="' . $width . '" height="' . $height . '" align="top"  />';
	else
	echo '<a href="annotationManager.php?orderBy=Annotation.objectTypeId&amp;order=ASC&amp;orderFlag=objectType">Object</a>';
	echo '
    </th>
    
    <th width="100">';
	if ($orderFlag == 'dateCreated' && $order == 'ASC')
	echo '<a href="annotationManager.php?orderBy=BaseObject.dateCreated&amp;order=DESC&amp;orderFlag=dateCreated">Date Created</a><img src="/style/webImages/sortArrowUp-trans.png" width="' . $width . '" height="' . $height . '" align="top"  />';
	elseif ($orderFlag == 'dateCreated' && $order == 'DESC')
	echo '<a href="annotationManager.php?orderBy=BaseObject.dateCreated&amp;order=ASC&amp;orderFlag=dateCreated">Date Created</a><img src="/style/webImages/sortArrowDown-trans.png" width="' . $width . '" height="' . $height . '" align="top"  />';
	else
	echo '<a href="annotationManager.php?orderBy=BaseObject.dateCreated&amp;order=ASC&amp;orderFlag=dateCreated">Date Created</a>';
	echo '
    </th>
    
    <th width="120">';
	if ($orderFlag == 'dateToPublish' && $order == 'ASC')
	echo '<a href="annotationManager.php?orderBy=BaseObject.dateToPublish&amp;order=DESC&amp;orderFlag=dateToPublish">Publication Date</a><img src="/style/webImages/sortArrowUp-trans.png" width="' . $width . '" height="' . $height . '" align="top"  />';
	elseif ($orderFlag == 'dateToPublish' && $order == 'DESC')
	echo '<a href="annotationManager.php?orderBy=BaseObject.dateToPublish&amp;order=ASC&amp;orderFlag=dateToPublish">Publication Date</a><img src="/style/webImages/sortArrowDown-trans.png" width="' . $width . '" height="' . $height . '" align="top"  />';
	else
	echo '<a href="annotationManager.php?orderBy=BaseObject.dateToPublish&amp;order=ASC&amp;orderFlag=dateToPublish">Publication Date</a>';
	echo '
    </th>
    
    <th>Add</th>
    <th class="last">Delete</th>
  </tr>';
}

function getAnnotationArray($userId, $groupId, $orderBy = 'id', $order = 'ASC')
{
	global $link;

	$sql = 'SELECT Annotation.*, Annotation.id AS id,  ' . 'DATE_FORMAT(BaseObject.dateCreated, \'%Y-%m-%d\') AS dateCreated, ' . 'DATE_FORMAT(BaseObject.dateToPublish, \'%Y-%m-%d\') AS dateToPublish, ' . 'DATE_FORMAT(NOW(), \'%Y-%m-%d\') AS now ' . 'FROM Annotation LEFT JOIN BaseObject ON Annotation.id = BaseObject.id ' . 'WHERE BaseObject.userId = \'' . $userId . '\' ' . 'ORDER BY ' . $orderBy . ' ' . $order;

	$results = mysqli_query($link, $sql);

	if ($results) {
		$numRows = mysqli_num_rows($results);

		if ($numRows > 0) {
			for ($i = 0; $i < $numRows; $i++)
			$annotationArray[$i] = mysqli_fetch_array($results);

			$annotationArray[0]['numRows'] = $numRows;

			return $annotationArray;
		} else
		return false;
	} else
	return false;
}

function updateAnnotations()
{
	global $link, $objInfo;
	$annotationArray = getAnnotationArray($objInfo->getUserId(), $objInfo->getUserGroupId());

	if ($annotationArray) {
		$numRows = count($annotationArray);

		for ($i = 0; $i < $numRows; $i++) {
			$sql = 'UPDATE BaseObject SET dateToPublish = STR_TO_DATE(\'' . $_POST['dateToPublish' . $annotationArray[$i]['id'] . ''] . '\', \'%Y-%m-%d\') WHERE id = \'' . $annotationArray[$i]['id'] . '\' ';
			mysqli_query($link, $sql);
			//echo $sql.'<br /><br />';
		}
	}
}

function deleteAnnotation($id)
{
	deleteFromDetermination($id);
	deleteFromAnnotation($id);
	deleteFromBaseObject($id);
	deleteFromKeywords($id);
}

function deleteFromAnnotation($id)
{
	global $link;
	$sql = 'DELETE FROM Annotation WHERE id = \'' . $id . '\'';
	//echo $sql;
	mysqli_query($link, $sql) or die(mysqli_error($link));
}

function deleteFromBaseObject($id)
{
	global $link;
	$sql = 'DELETE FROM BaseObject WHERE id = \'' . $id . '\'';
	//echo $sql;
	mysqli_query($link, $sql) or die(mysqli_error($link));
}

function deleteFromDetermination($id)
{
	global $link;
	$sql = 'DELETE FROM DeterminationAnnotation WHERE annotationId = \'' . $id . '\'';
	//echo $sql;
	mysqli_query($link, $sql) or die(mysqli_error($link));
}

function deleteFromKeywords($id)
{
	global $link;
	$sql = 'DELETE FROM Keywords WHERE id = ' . $id;
	//echo $sql;
	mysqli_query($link, $sql) or die(mysqli_error($link));
}

function echoJavascript()
{
	echo '
  <script type="text/javascript" language="JavaScript">
    <!--
      function confirmDelete(id) {
        var checkDelete = confirm("Delete Annotation?");
        
        if (checkDelete) {
          submitAnnotationForm("delete", id);        
        }    
      }
      
      function submitAnnotationForm(formFlag, flagData) {
        var formId = document.annotationForm;
        formId.formFlag.value = formFlag;
        formId.flagData.value = flagData;
        formId.submit();      
      }
       //-->
  </script>';
}
?>
