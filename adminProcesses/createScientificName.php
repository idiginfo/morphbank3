<?php
//This script creates scientific name strings from the
//ITIS imported names by making concatenations

//Author: Karolina Maneva-Jakimoska
//date: March 24 2007


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


$query = "select max(tsn) as tsn from Tree where tsn<999000000;";
$result = mysqli_query($link,$query);
if(!$result){
  mysqli_error($link);
  echo "Problems querying the database1";
  exit();
}
else{
  $row=mysqli_fetch_array($result);
  $maxtsn = $row['tsn'];
  UpdateHigherSingleRecord($link,$maxtsn);
  UpdateLowerSingleRecord($link,$maxtsn);
  UpdateSinonyms($link,$maxtsn);
}


function UpdateHigherSingleRecord($link,$tsn){

	global $treeTableName;

	$query = "SELECT * FROM Tree WHERE tsn>0 AND tsn<=".$tsn." AND rank_id<=200";
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
	    echo "current tsn is ".$current_tsn;
	    if($row['rank_id']<=180){
	      $unit_ind1 = $row['unit_ind1'];
	      $unit_ind1 = trim($unit_ind1);
	      $scientificName = $unit_ind1." ".trim($row['unit_name1']);
	      $scientificName = trim($scientificName); 
	      //exit();
	    }
	    else{
	      $query = "SELECT scientificName FROM Tree where tsn=".$row['parent_tsn'];
	      $result2 = mysqli_query($link,$query);
	      if(!$result2){
		mysqli_error($link);
		echo "problems querying the database" ;
	      }
	      else{
		$row2 = mysqli_fetch_array($result2);
		$parent_name=$row2['scientificName'];
		$parent_name = trim($parent_name);
		$unit_ind2 = $row['unit_ind2'];
		$unit_ind2 = trim($unit_ind2);
		$scientificName = $parent_name." ".$unit_ind2;
		$scientificName = trim($scientificName);
		$scientificName = scientificName." ".trim($row['unit_name2']);
		$scientificName = trim(scientificName);
	      }
	    }
	    $query = "UPDATE Tree SET scientificName='".$scientificName."' WHERE tsn=".$current_tsn;
	    echo $query;
	    $result_update = mysqli_query($link,$query);
	    if(!$result_update){
	      mysqli_error($link);
	      echo "problems updating the database" ;
	    }
	    continue;
	  }
	}
}//end of function UpdateHigherSingleRecord
	
function UpdateLowerSingleRecord($link,$tsn){

  //global $treeTableName;
  $rank = 210;
  while($rank<280){
    $query = "SELECT * FROM Tree WHERE tsn<=".$tsn." AND rank_id=".$rank;
    echo $query;
    $result = mysqli_query($link,$query);
    if(!$result){
      mysqli_error($link);
      echo "problems querying the database" ;
    }
    else{
      $numrows = mysqli_num_rows($result);
      if($numrows>0){
	echo "more than 0 rows";
	for($i=0;$i<$numrows;$i++){
	  $row = mysqli_fetch_array($result);
	  $unit_name2 = trim($row['unit_name2']);
	  $unit_ind2 = trim($row['unit_ind2']);
	  $unit_name3 = trim($row['unit_name3']);
          $unit_ind3 = trim($row['unit_ind3']);

	  $query = "SELECT scientificName FROM Tree where tsn=".$row['parent_tsn'];
	  $result2 = mysqli_query($link,$query);
	  if(!$result2){
	    mysqli_error($link);
	    echo "problems querying the database" ;
	  }
	  else{
	    $row2 = mysqli_fetch_array($result2);
	    $parent_name=trim($row2['scientificName']);
	    echo "parent_name is ".$parent_name."\n";
	    if($rank==210 || $rank==220){
	      $scientificName = $parent_name." ".$unit_ind2;
	      $scientificName = trim($scientificName)." ".$unit_name2;
	    }
	    else{
	      $scientificName = $parent_name." ".$unit_ind3;
	      $scientificName = trim($scientificName)." ".$unit_name3;
	    }
       
	    echo "scientificName ".$scientificName."\n";
	    //exit();
	    $query = "UPDATE Tree SET scientificName='".$scientificName."' WHERE tsn=".$row['tsn'];
	    $result_update = mysqli_query($link,$query);
	    if(!$result_update){
	      mysqli_error($link);
	      echo "problems updating the database" ;
	    }
	    else
	      continue;
	  }
	  continue;
	}
      }
    }
    $rank = $rank +10;
    echo "rank is".$rank;
    continue;
  }//end of while
}//end of UpdateLowerSingleRecord

function UpdateSinonyms($link,$tsn){

  $query = "SELECT * from Tree where `usage`='not accepted' OR `usage`='invalid' AND tsn<=".$tsn;
  echo $query;
  $result = mysqli_query($link,$query);
  if(!$result){
    echo mysqli_error($link);
    exit();
  }
  else{
    $num_rows = mysqli_num_rows($result);
    for($i=0;$i<$num_rows;$i++){
      $row = mysqli_fetch_array($result);
      $scientificName = trim(trim($row['unit_ind1']." ".$row['unit_name1'])." ".trim($row['unit_ind2']." ".$row['unit_name2']));
      $scientificName .= " ".trim(trim($row['unit_ind3']." ".$row['unit_name3'])." ".trim($row['unit_ind4']." ".$row['unit_name4']));
      $query = 'UPDATE Tree SET scientificName="'.$scientificName.'" WHERE tsn='.$row['tsn'];
      echo $query;
      $results = mysqli_query($link,$query);
      if(!$results){
	echo mysqli_error($link);
	exit();
      }
      else
	continue;
    }
  }
}
echo "This process took: ".time()-$startTime." seconds";
?>

