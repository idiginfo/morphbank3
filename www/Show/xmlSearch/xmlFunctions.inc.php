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

//TODO check whether this module is used and how it is used.
function xmlCheckIfArray( $imageArray ) {
	if (is_array($imageArray)) {
		$arraySize = count($imageArray);
		
		for ($i = 0; $i < $arraySize; $i++)
			xmlResult($imageArray[$i]);			
	}
	
	else 
		xmlResult($imageArray);

}


function xmlResult ( $imageId ) {
echo '				
	&lt;result xmlns=&quot;http://morphbank.scs.fsu.edu&quot;&gt;<br />
		&nbsp;&nbsp;&nbsp;&nbsp;&lt;image&gt;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;name&gt;'.$imageId.'&lt;/name&gt;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;extension&gt;.tif&lt;/extension&gt;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;LSID&gt;URN:LSID:morphbank:com:imageTIFF:'.$imageId.'&lt;/LSID&gt;<br />
		&nbsp;&nbsp;&nbsp;&nbsp;&lt;/image&gt;<br />
		&nbsp;&nbsp;&nbsp;&nbsp;&lt;image&gt;<br />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;name&gt;'.$imageId.'&lt;/name&gt;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;extension&gt;.jpeg&lt;/extension&gt;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;LSID&gt;URN:LSID:morphbank:com:imageJPEG:'.$imageId.'&lt;/LSID&gt;<br />
		&nbsp;&nbsp;&nbsp;&nbsp;&lt;/image&gt;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;name&gt;'.$imageId.'&lt;/name&gt;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;extension&gt;.jpg&lt;/extension&gt;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;LSID&gt;URN:LSID:morphbank:com:imageJPG:'.$imageId.'&lt;/LSID&gt;<br />
		&nbsp;&nbsp;&nbsp;&nbsp;&lt;/image&gt;<br />
	&lt;/result&gt;<br /><br />';

}




?>
