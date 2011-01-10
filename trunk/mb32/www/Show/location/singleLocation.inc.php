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
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

$locationArray = getLocationData($id);
$baseObjectArray = getBaseObjectData($id);

$className = checkForExtLinks($id) ? "topBlueBorder" : "blueBorder";
$popUrl = (isset($_GET['pop'])) ? "/Show/?pop=Yes&amp;id=" : "/?id=";

	if (isset($_GET['pop'])) 
		echo '<div class="popContainer" style="width:770px">';
	else
		echo '<div class="mainGenericContainer" style="width:770px">';
	
	echo'
		<h2>Locality Record: ['.$id.']</h2>
			<table class="'.$className.'" width="100%"  border="0" cellspacing="0" cellpadding="2">
			  <tr>
				<td class="firstColumn"  width="40%" valign="top">
					<div class="popCellPadding">';
						showBaseObjectData($baseObjectArray);
						echo'
					</div>
				</td>
				<td width="60%" valign="top">
					<table align="left" border="0">
						<tr>
							<th>Locality :</th><td>'. $locationArray['locality'] .'</td>
							
						</tr>
						<tr>
							<th>Continent:</th><td>'.$locationArray['continentOcean'].'</td>
						</tr>
						<tr>
							<th>Country:</th><td>'.$locationArray['country'].'</td>
						</tr>
						
						<tr>
							<th>Latitude:</th><td>'. truncateValue($locationArray['latitude']). '</td>
						</tr>
						<tr>
							<th>Longitude:</th><td>' .truncateValue($locationArray['longitude']) . '</td>
						</tr>
						<tr>
							<th>Precision:</th><td>'.$locationArray['coordinatePrecision'].'</td>
						</tr>';
						
						if ($locationArray['minimumElevation'] == '' || $locationArray['maximumElevation'] == '' ) {
							echo '<tr>
									<th>Elevation (m):</th><td> '.$locationArray['minimumElevation'].'  '.$locationArray['maximumElevation'].' </td>
								  </tr>';
						}
						else {
							echo '<tr>
										<th>Elevation (m):</th><td> '.$locationArray['minimumElevation'].' &nbsp;-&nbsp; '.$locationArray['maximumElevation'].' </td>
								  </tr>';
						}
						
						if ($locationArray['minimumDepth'] == '' || $locationArray['maximumDepth'] == '' ) {
							echo '<tr>
									<th>Depth (m):</th><td> '.$locationArray['minimumDepth'].'  '.$locationArray['maximumDepth'].'</td>
								  </tr>';
						}
						else {
							echo '<tr>
									<th>Depth (m):</th><td> '.$locationArray['minimumDepth'].' &nbsp;-&nbsp; '.$locationArray['maximumDepth'].'</td>
								  </tr>';
						
						}
						echo '
											
					</table>
				
				
				</td>
			  </tr>
				
			</table>';
			
			if (checkForExtLinks($id)) {
				echo'
			
				<table class="bottomBlueBorder" width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td>
							<table width="100%" border="0" cellspacing="0" cellpadding="2">
								<tr>
									<td>';
									showExternalLinks($id);
									echo'								
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>';
			}
			
		echo'				
	  </div>';
	  
	
	  
function getLocationData($id) {
	$link = adminLogin();
	
	$sql = 'SELECT * FROM Locality  WHERE id = '.$id;
		  
	$result = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if ($result) {
		$total = mysqli_num_rows($result);
		
		if ($total = 1) {
			$array = mysqli_fetch_array($result);		
			return $array;
		}
		else {
			$error = 'More than one Locality... Error';
			return $error;
		}
	
	}
	else {
		$error = 'Query Error';
		return $error;
	
	}
}
?>
