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

//***********************************************************************************
//truncates show for lat/long coordinates to 5 values after the decimal place
//k. seltmann july 2008
//************************************************************************************

function truncateValueTest($locationCord) {
return sprintf('%F', $locationCord);
}

function truncateValue($locationCord) {

		 $num = explode( '.', $locationCord );
		 $num_left = trim( $num[0] );
		 $num_right = trim( $num[1] );  
		
	if (strlen($num_right) < 6){
			return $locationCord;

		}else{
	 			return sprintf(' %.5f ', $locationCord);
		}
	}
?>
