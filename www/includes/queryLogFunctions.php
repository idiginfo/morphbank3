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

/**
*	Writes query out to a log file log/userQuery.log
*
*	logQuery function takes 4 arguments.  First is an SQL string query.  Second is an array of
*	search termes used to create the query.  Third is a string with either the success or failure
*	of a query and the error message if applicable.  And fouth (optional) is the number or search
*	options the user searched by.
*/


function logQuery($sql, $array, $querySuccess, $numOptions = '') {	
	global $objInfo;
	
	$filename = 'log/userQuery.log';
	$num = count($array);
	
	// code from php.net	
	$fileOutput = date('M d, Y G:i:s');
	
	if ($objInfo->getUserId() != NULL) {
		$fileOutput .= "\nFrom User: ".$objInfo->getName();
	
	}
	
	$fileOutput .= "\n"."Query Success: ".$querySuccess;

	if (!$array[0]) {
		for ($i = 1; $i <= $numOptions; $i++) {
			if ($array[$i]['entity'] != '')
				$fileOutput .= "\nTable: ".$array[$i]['entity']."   Operator: ".$array[$i]['operator']."   Field: ".$array[$i]['field']."   Search Text: ".$array[$i]['searchText'];
		}		
	}
	
	else {
		$fileOutput .= "\nSearch Terms: ";
		for ($i=0; $i < $num; $i++) {
			
			$fileOutput .= $array[$i]." ";	
		}
	}	
	
	$fileOutput .= "\n\n".$sql."\n\n\n\n";
	
	// Let's make sure the file exists and is writable first.
	if (is_writable($filename)) {
	
	   // In our example we're opening $filename in append mode.
	   // The file pointer is at the bottom of the file hence
	   // that's where $somecontent will go when we fwrite() it.
	   if (!$handle = fopen($filename, 'a')) {
			 echo "Cannot open file ($filename)";
			 exit;
	   }
	
	   // Write $somecontent to our opened file.
	   if (fwrite($handle, $fileOutput) === FALSE) {
		   echo "Cannot write to file ($filename)";
		   exit;
	   }
	  
	   fclose($handle);
	
	} else {
	   echo "The file $filename is not writable";
	}
} // end logQuery

?>


