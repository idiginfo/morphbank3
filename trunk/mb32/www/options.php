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

/*
 File name: options.php 
 @author Neelima Jammigumpula <jammigum@scs.fsu.edu>
 @package Morphbank2
 @subpackage Submit 

 This script gets the group the user is interested in and sets session variables to appropriate values based on user group. setUserGroupInfo function will be used for this purpose in the near future.

*/

include_once('head.inc.php');

include('mbSubmitOptions_data.php');

// The beginnig of HTML
$title = 'MorphBank Tools';
initHtml( $title, NULL, NULL);

// Add the standard head section to all the HTML output.
echoHead( false, $title);



if($objInfo->getUserGroupRole() == 'reviewer'){
	echo ' <div class="mainGenericContainer" style ="width: 600px;">
  <pre><h3> You have just signed into morphbank with a Reviewer Account.</h3>

  <font size = "3" face = "Verdana, Arial, Helvetica, sans-serif" color = "#17256b">
  This type of account allows you to view unpublished records from a morphbank 
  user, or group of users, without the ability to modify these records. You can 
  use any of the top menus to navigate through morphbank. The Browse menu will 
  take you to all of the records (published and unpublished) that are accessible 
  as a regular member of this group.  Also, links embedded in .pdf and .doc files 
  sent out for review will now be visible on this page.  You may access them by 
  clicking directly in the document itself or by pasting the URL in your browser 
  navigation bar.  If you have trouble with these links please be sure that you 
  have the correct Reviewer Account for that manuscript.  We create different 
  accounts for every manuscript being reviewed.

  There is no record of your access to morphbank either visible by the group
  members or from morphbank. If you have any questions please feel free to
  contact us, or have the editor contact us. 
  '.$config->email.'

  Thank-you and welcome to Morphbank</pre></font><br /></div>';

}else{
	echo ' <div class="mainGenericContainer" style="width: 500px">';


	if($objInfo->GetUserGroup() == 'MorphBank Administration'){
		header('Location ' .$config->domain. 'Admin');
	}else{
		
		echo '<h1>Morphbank Tools..</h1>
		<img src="/style/webImages/blueHR-trans.png" width="475" height="5" class="blueHR" alt="" /><br />';
		foreach($optionsMenu as $menu) {

			echo '<div class="introNavText">
        		<a class="introNav" href="'.$menu['href'].'">'.$menu['name'].'</a> </div>';

		}

		$mirror = runQuery("SELECT serverId FROM ServerInfo WHERE mirrorGroup = " .$objInfo->getUserGroupId(). ";");

        	if(mysqli_num_rows($mirror) > 0)
			echo '<div class="introNavText"> <a class="introNav" href="' .$config->domain. 'Mirror/"> Manage Mirror</a></div>';
	}

	if($objInfo->GetUserGroupRole() == 'coordinator'){

		echo '<div class="introNavText">';
	        echo '<a class="introNav" href="./Admin/Group/group.php"> Group</a></div>';
	}
	echo '</div>';
}
// Finish with end of HTML
finishHtml();
?>
