<?php

//***********************************************************************************
//truncates show for lat/long coordinates to 5 values after the decimal place
//k. seltmann july 2008
//************************************************************************************

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
