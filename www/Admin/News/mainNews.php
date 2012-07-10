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

include_once('imageFunctions.php');

/**
 * Shows list of news items
 * Displays only title, author, and edit/view link
 */
function listNews($id = null, $action = null) {
  $db = connect();
  
  if ($action == 'delete')
    deleteNews($id);
  
  $pager_options = array(
    'mode'       => 'Sliding',
    'perPage'    => 10,
    'delta'      => 2,
    'extraVars'  => array(
      'action' => $_GET['action'], 
      'id' => $_GET['id'], 
      'code' => $_GET['code']
    ),
  );
  $query = "SELECT n.title, bo.id, DATE_FORMAT(bo.dateCreated, '%m/%d/%Y') as date_created, u.uin from News n  
            LEFT JOIN BaseObject bo on bo.id = n.id 
            LEFT JOIN User u on u.id = bo.userId
            ORDER BY bo.dateCreated DESC";
  $paged_data = Pager_Wrapper_MDB2($db, $query, $pager_options);
  
  echo "<h1>News</h1>";
  echo '&nbsp;&nbsp;&nbsp;<a href="/Admin/News/?action=add">Create News</a>';
  echo "<br /><br />";
  //show the links
  echo $paged_data['links'];
  echo '<ul class="admin-news-list">';
  foreach ($paged_data['data'] as $record) {
      echo '<li><h3>' . $record['title'] . '</h3><br />';
      echo 'Submitted by: ' . $record['uin'] . ' on ' . $record['date_created'];
      echo '&nbsp;&nbsp;&nbsp;&nbsp;';
      echo '<a href="/Admin/News/?action=edit&id=' . $record['id'] . '">edit</a>';
      echo '&nbsp;&nbsp;&nbsp;&nbsp;';
      echo '<a href="/Admin/News/?action=delete&id=' . $record['id'] . '" onClick=" return confirm(\'Confirm you wish to delete\');">delete</a>';
      echo '</li>';
  }
  echo '</ul>';

  //show the links
  echo $paged_data['links'];
  
  return;
}

/**
 * Edit single news item
 */
function editNews() {
  global $config;
  $db = connect();
  
  $sql = "SELECT bo.*, n.* from BaseObject bo  
            LEFT JOIN News n on n.id = bo.id 
            WHERE bo.id = ?";
	$row = $db->getRow($sql, null, array($_GET['id']), null, MDB2_FETCHMODE_ASSOC);
  if (isMdb2Error($row, "Error selecting news information.", 6)) {
    echo getMessage(2);
  }
  
  $frmTitle = "<h1>Edit News #{$_GET['id']}</h2>";
  $postFile = "modifyNews.php";
  $action = "edit";
  $id = $row['id'];
  $image = $row['image'];
	include('newsForm.php');
  
  return;
}

/**
 * Create new item
 */
function addNews() {
  $frmTitle = "<h1>Add News</h2>";
  $postFile = "commitNews.php";
  $action = "add";
  $id = $_GET['id'];
  include('newsForm.php');
  
  return;
}

/**
 * Delete news item
 */
function deleteNews($id) {
	global $config;
  
  $db = connect();
	if (empty($id)) return;
	
	$sql = "select image from News where id = ?";
	$image = $db->getOne($sql, null, array($id));
	if (isMdb2Error($image, 'Selecting image from News', 6)) {
    echo getMessage(10);
    return;
  }
	
	if ($image)
    $delete_image = str_replace($config->appServerBaseUrl . 'images/newsImages/', '', $image);
    @unlink($config->newsImagePath . $delete_image);
	
	$sth = $db->prepare('delete from News where id = ? limit 1', array('integer'));
  if (isMdb2Error($sth, 'Error preparing delete News statement', 6)) {
    echo getMessage(11);
    return;
  }
  $affectedRows = $sth->execute(array($id));
  if (isMdb2Error($affectedRows, 'Error executing delete News statement', 6)) {
    echo getMessage(12);
    return;
  }
  $sth->free();
  
  if ($affectedRows) {
    $sth = $db->prepare('delete from BaseObject where id = ? limit 1', array('integer'));
    if (isMdb2Error($sth, 'Error preparing delete BaseObject news statement', 6)) {
      echo getMessage(13);
      return;
    }
    $affectedRows = $sth->execute(array($id));
    if (isMdb2Error($affectedRows, 'Error executing delete BaseObject News statement', 6)) {
      header ("location: /Admin/News/?action=view&code=14");
      exit;
    }
    $sth->free();
  }
  
  echo getMessage(16);
  return;
}

/**
 * Get messages for given codes
 * @param $code
 */
function getMessage($code) {
	if ($code == 1) {
		return "<br /><br /><h3>You have updated news successfully</h3><br /><br />";
	} elseif ($code == 2) {
		echo '<br /><br /><div class="searchError">Error selecting news item</div>'."\n";
	} elseif ($code == 3) {
		return '<br /><br /><div class="searchError">Please fill out all required fields</div>'."\n";
	} elseif ($code == 4) {
		return '<br /><br /><div class="searchError">Error retrieving News information while updating</div>'."\n";
	} elseif ($code == 5) {
		return '<br /><br /><div class="searchError">Internal processing error while updating News</div>'."\n";
	} elseif ($code == 6) {
		return '<br /><br /><div class="searchError">You do not have permissions to add users</div>'."\n";
	} elseif ($code == 7) {
		return '<br /><br /><div class="searchError">Error uploading file</div>'."\n";
	} elseif ($code == 8) {
		return '<br /><br /><div class="searchError">Error inserting BaseObject for news</div>'."\n";
	} elseif ($code == 9) {
		return '<br /><br /><div class="searchError">Error retrieving new BaseObject id for news</div>'."\n";
	} elseif ($code == 10) {
		return '<br /><br /><div class="searchError">Error retriving image information during news delete</div>'."\n";
	} elseif ($code == 11) {
		return '<br /><br /><div class="searchError">Error preparing News delete statement</div>'."\n";
	} elseif ($code == 12) {
		return '<br /><br /><div class="searchError">Error executing News deletion</div>'."\n";
	} elseif ($code == 13) {
		return '<br /><br /><div class="searchError">Error preparing BaseObject News delete statement</div>'."\n";
	} elseif ($code == 14) {
		return '<br /><br /><div class="searchError">Error executing BaseObject News deletion</div>'."\n";
	} elseif ($code == 15) {
		return "<br /><br /><h3>You have added a News item successfully</h3><br /><br />";
	} elseif ($code == 16) {
		return "<br /><br /><h3>You have successfully deleted a News item</h3><br /><br />";
	}
  
	return;
}

