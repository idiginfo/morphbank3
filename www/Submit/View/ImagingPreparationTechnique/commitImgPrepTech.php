<?php 
include_once('head.inc.php');



checkIfLogged();
groups();
$link = Adminlogin();


if(checkAuthorization('ImagingPreparationTechnique', $objInfo->getUserId(), $objInfo->getUserGroupId(), 'Add')){

	$partname_show = $_POST['PreparationTechnique'];
	$imgpreptechname =  	  mysqli_real_escape_string($link, trim($_POST['PreparationTechnique']));
	$description =  	  mysqli_real_escape_string($link, trim($_POST['description']));

  	$checksql = ' SELECT * FROM ImagingPreparationTechnique WHERE  name = \'' .$imgpreptechname.'\';' ;
  	//echo $checksql;

  	$exists = mysqli_fetch_array(runQuery($checksql));

  	if($exists){
		$url = 'index.php?code=0&name='.$exists['name'];
		header ("location: ".$url);
		exit;
  	}else{

                $query =  "CALL ImagingPreparationTechniqueInsert(\"".$imgpreptechname."\",\"".$description."\")";

		//echo $query;
		$results = mysqli_query($link, $query) or die ('Could not run query ' . mysqli_error($link));

		if($results) { 
	
       			$url = 'index.php?code=1&name='.$partname_show; 
       			header ("location: ".$url);
       			exit; 
     		} else {
       			//echo $query. '<BR>';
       			echo  mysqli_error($link); 
      		}
  	}
	mysqli_free_result($results);

}else{
        $url = 'index.php?code=3';
        header ("location: ".$url);
        exit;

}
?>
