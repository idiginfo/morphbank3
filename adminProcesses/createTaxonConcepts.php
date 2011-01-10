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
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

// Username and password
$db_user = 'webuser';
$db_passwd = 'namaste';
$host = 'localhost';
$port = '3307';
$database = 'MB30';

$startTime = time();

$link = mysqli_connect($host , $db_user , $db_passwd , $database , $port);

if (!$link){
  printf("Can not connect to MySQL server. Errorcode: %s\n",mysqli_connect_error());
  exit; } // end if


        $query = "SELECT tsn,`usage` FROM Tree WHERE tsn>=999000000";
	$result = mysqli_query($link,$query);
	if(!$result){
	  mysqli_error($link);
	  echo "Problems querying the database"	;
	}
	else{
	  $numrows = mysqli_num_rows($result);
	  for($i=0;$i<$numrows;$i++){
	    $row = mysqli_fetch_array($result);
	    $current_tsn = $row['tsn'];
            $usage = $row['usage'];
	    echo "current tsn is ".$current_tsn;
            if(CheckTaxon($link,$current_tsn)==false)
              NewTaxon($link,$current_tsn,$usage);
            else
              continue;
	  }
        }
	
function CheckTaxon($link,$tsn){

    $query = "SELECT id FROM TaxonConcepts WHERE tsn=".$tsn;
    echo $query;
    $result = mysqli_query($link,$query);
    if(!$result){
      mysqli_error($link);
      echo "problems querying the database" ;
    }
    else{
      $numrows = mysqli_num_rows($result);
      if($numrows<1)
         return false;
      else
         return true;
    }
}//end of CheckTaxon

function NewTaxon($link,$tsn,$usage){

  $query = "SELECT max(id) as id from BaseObject";
  echo $query;
  $result = mysqli_query($link,$query);
  if(!$result){
    echo mysqli_error($link);
    exit();
  }
  else{
    $row = mysqli_fetch_array($result);
    $new_id = $row['id'] + 1;
      $query = "INSERT INTO BaseObject (id,userId,groupId,dateCreated,dateLastModified,dateToPublish,objectTypeId,submittedBy) VALUES(";
      $query .= $new_id.", 77426, 2,NOW(),NOW(),NOW(),'Taxon Concept',77426)";
      echo $query;
      $results = mysqli_query($link,$query);
      if(!$results){
	echo mysqli_error($link);
	exit();
      }
      else{
	 $query = "INSERT INTO TaxonConcepts(id,tsn,status) VALUES(";
      $query .= $new_id.",".$tsn.",'".$usage."')";
      echo $query;
      $results = mysqli_query($link,$query);
      if(!$results){
        echo mysqli_error($link);
        exit();
      }
    }
  }
}
echo "This process took: ".time()-$startTime." seconds";
?>

