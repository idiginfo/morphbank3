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

$uploadMenuWidth = 80;   
$uploadMenuInitHorPosition = 425; // Based of mainNavOutContainer from mb2_main.css
$uploadMenu = array(	array(	'name' => 'Locality',
							'href' => 'Location/',
							'description' => '<p>Allows users to add location information of their collections</p>',
							'horPosition' => $uploadMenuInitHorPosition),
					array(	'name' => 'Specimen',
							'href' => 'Specimen/',
							'description' => '<p>Allows users to add specimens</p>',
							'horPosition' => $uploadMenuInitHorPosition + $uploadMenuwidth),
					array(	'name' => 'View',
							'href' => 'View/',
							'description' => '<p>Allows users to add views that best describe the image</p>',
							'horPosition' => $uploadMenuInitHorPosition + ($uploadMenuWidth*2)),
					array( 	'name' => 'Image',
							'href' => 'Image/',
							'description' => '<p>Allows users to add images of a particular specimen</p>',
						'horPosition' => $uploadInitHorPosition + ($uploadMenuWidth*3)),
			                array(  'name' => 'Publication',
						        'href' => '/Admin/Publication/addPublication.php',
				                        'description' => '<p>Allows users to add publications</p>',
				                        'horPosition' => $uploadInitHorPosition + ($uploadMenuWidth*4)),
			                array(  'name' => 'Taxon Name',
						        'href' => '/Admin/TaxonSearch/index.php?searchonly=1/',
				                        'description' => '<p>Allows users to search and add taxon names </p>',
				                        'horPosition' => $uploadInitHorPosition + ($uploadMenuWidth*4))

				);

?>
