<?php

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
