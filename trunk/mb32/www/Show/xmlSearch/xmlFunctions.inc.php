<?php

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
