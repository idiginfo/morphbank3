#-------------------------------------------------------------------------------
# Copyright (c) 2010 Greg Riccardi, Fredrik Ronquist.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Public License v2.0
# which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# 
# Contributors:
#   Fredrik Ronquist - conceptual modeling and interaction design
#   Austin Mast - conceptual modeling and interaction design
#   Greg Riccardi - initial API and implementation
#   Wilfredo Blanco - initial API and implementation
#   Robert Bruhn - initial API and implementation
#   Christopher Cprek - initial API and implementation
#   David Gaitros - initial API and implementation
#   Neelima Jammigumpula - initial API and implementation
#   Karolina Maneva-Jakimoska - initial API and implementation
#   Katja Seltmann - initial API and implementation
#   Stephen Winner - initial API and implementation
#-------------------------------------------------------------------------------
<?php

//This script creates trinomials from the names entered in 

//Author: Karolina Maneva-Jakimoska
//date:  March 26th 2007

include_once('../www/includes/tsnFunctions.php');

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

$treeTableName = 'Tree';

CreateHigherTrinomial($link);
CreateLowerTrinomial($link);


function CreateHigherTrinomial($link){

 
	$query = "SELECT * FROM Tree WHERE tsn>=999000000 AND rank_id>190 AND rank_id<=220 and unit_ind3!='X'";
	echo $query;
	$result = mysqli_query($link,$query);
	if(!$result){
	  mysqli_error($link);
	  echo "problems querying the database"	;
	}
	else{
	  $numrows = mysqli_num_rows($result);
	  for($i=0;$i<$numrows;$i++){
	    $row = mysqli_fetch_array($result);
	    $current_tsn = $row['tsn'];
	    echo "current tsn is ".$current_tsn;
	    $unit_ind1 = $row['unit_ind1'];
	    $unit_name1 = $row['unit_name1'];
	    $unit_ind2 = $row['unit_ind2'];
	    $unit_name2 = $row['unit_name2'];
            $unit_ind3 = $row['unit_ind3'];
            $unit_name3 = $row['unit_name3'];

	    if($unit_name3!="" && $unit_name3!=null){
	      $unit_ind2 = $unit_ind3;
	      $unit_name2 = $unit_name3;
	      $unit_ind3 = null;
	      $unit_name3 = null;
	    }
	    $query = "UPDATE Tree SET ";
	    $query .= "unit_ind2='".$unit_ind2."',unit_name2='".$unit_name2."',unit_ind3='".$unit_ind3."', unit_name3='".$unit_name3."'"; 
            $query .=" WHERE tsn=".$current_tsn;
	    echo $query;
	    $result_update = mysqli_query($link,$query);
	    if(!$result_update){
	      mysqli_error($link);
	      echo "problems updating the database" ;
	    }
	    continue;
	  }
	}
}//end of function CreateHigherTrinomial
	
function CreateLowerTrinomial($link){

  global $treeTableName;

    $query = "SELECT * FROM Tree WHERE tsn>=999000000 AND rank_id>220";
    echo $query;
    $result = mysqli_query($link,$query);
    if(!$result){
      mysqli_error($link);
      echo "problems querying the database" ;
    }
    else{
      $numrows = mysqli_num_rows($result);
      if($numrows>0){
	for($i=0;$i<$numrows;$i++){
	  $row = mysqli_fetch_array($result);
          $unit_ind1 = $row['unit_ind1'];
          $unit_name1 = $row['unit_name1'];
	  $unit_name2 = $row['unit_name2'];
	  $unit_ind2 = $row['unit_ind2'];
	  $unit_name3 = $row['unit_name3'];
          $unit_ind3 = $row['unit_ind3'];
	  echo "unit 1 ".$unit_name1;
	  echo "unit 2 ".$unit_name2;
	  echo "unit 3 ".$unit_name3; 
          $array = getArrayParents($row['tsn']);
          $arraysize = count($array);
          for($j=0;$j<$arraysize;$j++){
	    if($array[$j]['rank_id']==180 || $array[$j]['rank_id']==220){
	      $query = "SELECT unit_ind1, unit_name1, unit_ind2, unit_name2 FROM Tree where tsn=".$array[$j]['tsn'];
	      $result2 = mysqli_query($link,$query);
	      if(!$result2){
		mysqli_error($link);
		echo "problems querying the database" ;
	      }
	      else{
		$row2 = mysqli_fetch_array($result2);
		if($array[$j]['rank_id']==180){
		  $unit_ind1 = $row2['unit_ind1'];
		  $unit_name1 = $row2['unit_name1'];
		}
		else{
		  $unit_ind1 = $row['unit_ind1'];
		  $unit_name1 = $row['unit_name1'];
		}
		if($array[$j]['rank_id']==220){
		  $unit_ind2 = $row2['unit_ind2'];
		  $unit_name2 = $row2['unit_name2'];
		}
		else{
		  $unit_name2 = $row['unit_name2'];
		  $unit_ind2 = $row['unit_ind2'];
		}
	      }
	    }
          }
          if($row['unit_name4']!=null && $row['unit_name4']!=""){
	    $unit_name3 = $row['unit_name4'];
	    $unit_ind3 = $row['unit_ind4'];
	    $unit_name4 = null;
	    $unit_ind4 = null;
          }
    
	  echo "unit 1 ".$unit_name1;
          echo "unit 2 ".$unit_name2;
          echo "unit 3 ".$unit_name3;

	  $query = "UPDATE Tree SET ";
	  $query .= "unit_ind2='".$unit_ind2."',unit_name2='".$unit_name2."',unit_ind3='".$unit_ind3."', unit_name3='".$unit_name3."'";
	  $query .= ",unit_ind1='".$unit_ind1."',unit_name1='".$unit_name1."',unit_ind4='".$unit_ind4."', unit_name4='".$unit_name4."'";
	  $query .= " WHERE tsn=".$row['tsn'];
	  echo $query;
	  $result_update = mysqli_query($link,$query);
	  if(!$result_update){
	    mysqli_error($link);
	    echo "problems updating the database" ;
	  }
	}
      }
    }
}//end of UpdateLowerSingleRecord

echo "This process took: ".time()-$startTime." seconds";
?>

