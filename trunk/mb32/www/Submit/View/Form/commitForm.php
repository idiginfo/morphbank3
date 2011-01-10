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

<?php 
include_once('head.inc.php');



checkIfLogged();
groups();
$link = Adminlogin();


if(checkAuthorization('Form', $objInfo->getUserId(), $objInfo->getUserGroupId(), 'add')){
	
	$formname_show = $_POST['Form'];
	$form =  	  	mysqli_real_escape_string($link, trim($_POST['Form']));
	$description =  	mysqli_real_escape_string($link, trim($_POST['description']));


  	$checksql = ' SELECT * FROM Form WHERE  name = \'' .$form.'\';' ;
    	//echo $checksql;

  	$exists = mysqli_fetch_array(runQuery($checksql));

  	if($exists){

		$url = 'index.php?code=0&name='.$exists['name'];
	        header ("location: ".$url);
	        exit;
  	}else{


                $query =  "CALL FormInsert(\"".$form."\",\"".$description."\")";

		//echo $query;
		$results = mysqli_query($link, $query) or die ('Could not run query ' . mysqli_error($link));

		if($results) { 

       			$url = 'index.php?code=1&name='.$formname_show; 
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
