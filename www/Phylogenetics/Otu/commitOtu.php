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

//File to commit the OTU to the database
//
//@author Karolina Maneva-Jakimoska
//@date created Sep 17th 2007
//
include_once('head.inc.php');
include_once('collectionFunctions.inc.php');
include_once('Phylogenetics/phylo.inc.php');

$url = $config->domain . 'myCollection/?id='.$_POST['id'];
$flag=0;


if(isset($_POST['id'])){
  $link = Adminlogin();
  $id=$_POST['id'];
  $query = "Update BaseObject set description='".$_POST['description']. "', dateToPublish='".$_POST['dateToPublish'];
  $query .="',name='".$_POST['title']."' where id=".$_POST['id'];
  $result = mysqli_query($link,$query);
  if(!$result)
    $flag=0;
  else{
    if($_POST['label']!=""){
      $query = "UPDATE Otu set label='".$_POST['label']."' where id=".$_POST['id'];
      $result = mysqli_query($link,$query);
      if(!$result)
	$flag=0;
      else{

	//call the Taxa Procedure to create Taxa record
	$query = "CALL `TaxaInsert`(".$_POST['id'].",NULL,'".$_POST['title']."',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,";
        $query .="NULL,NULL,NULL,".$_POST['groupId'].",".$_POST['userId'].",NOW(),NULL,'Otu')";
	$result = mysqli_multi_query($link,$query);

	//update the Taxa keywords and BaseObject insert
	TaxaKeywords($link, NULL, $_POST['id']);

	$otu = new Otu($link,$_POST['id']);
      }
    }
  }
}

//createOtuThumb($id);

echo '
        <html>
            <body onLoad="document.autoForm.submit();">
                <form name="autoForm" action="'.$config->domain.'myCollection/?id='.$id.'" method="post">
                      <input name="id" value="'.$id.'" type="hidden" />
                      <input name="flag" value="'.$flag.'" type="hidden" />
                </form>
            </body>
        </html>';


?>
