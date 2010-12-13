<?php
include_once('charStageMatrixInterface.class.php');

// simply echos the following contents to the web browser.
// This helps keep the main scripts simpler to read.
function mainMyMatrices() {
	global $config;
	global $characterState;
	global $matrix;
	
	echo '<div class="mainGenericContainer" style="width:auto">'; // width depent of the matrix table
		
	// New charStageMatrixInterface class
	// The constructor already check the GET variables and update the corresponding attributes
	$myCharStageMatrixInterface = new charStageMatrixInterface( $config->domain); 
	
	$myCharStageMatrixInterface->setTitle('My matrix title'); // I'm not sure if I can get the title from the matrix obj
	
	// displayFromGet method is ready to get the necesary parameter from the url line ($_GET)
	$myCharStageMatrixInterface->displayFromGet();
	
	// displayFromPara method is waiting for the parameters 
	// Parameters $matId, 
	//			$sCol (start column), 
	//			$sRow (start Row), 
	//			$nCol (amount of columns to show), 
	//			$nRow amounto of rows to show)
	// $myCharStageMatrixInterface->displayFromPara( 143004, 1, 1, 5, 5);   
	echo '</div>';
}

?>
