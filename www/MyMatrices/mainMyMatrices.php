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
