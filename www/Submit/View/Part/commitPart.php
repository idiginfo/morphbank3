<?php 
include_once('head.inc.php');



checkIfLogged();
groups();
$link = Adminlogin();

if(checkAuthorization('SpecimenPart', $objInfo->getUserId(), $objInfo->getUserGroupId(), 'Add')){
	
	$partname_show = $_POST['SpecimenPart'];
	$partname =  	mysqli_real_escape_string($link, trim($_POST['SpecimenPart']));
	$description =  mysqli_real_escape_string($link, trim($_POST['description']));


  	$checksql = ' SELECT * FROM SpecimenPart WHERE  name = \'' .$partname.'\';' ;
  	//echo $checksql;

  	$exists = mysqli_fetch_array(runQuery($checksql));

  	if($exists){
		$url = 'index.php?code=0&name='.$exists['name'];
		header ("location: ".$url);
		exit;
  	}else{

                $query =  "CALL SpecimenPartInsert(\"".$partname."\",\"".$description."\")";
		//echo $query;

		$results = mysqli_query($link, $query) or die ('Could not run query ' . mysqli_error($link));

		if($results) { 

       			$url = 'index.php?code=1&name='.$partname_show; 
       			header ("location: ".$url);
       		exit; 
     		} else {
       			//echo $query. '<BR>';
       			echo  mysql_error(); 
     		}
  	} 
	mysqli_free_result($results);
}else{
	$url = 'index.php?code=3';
        header ("location: ".$url);
        exit;

}
?>
