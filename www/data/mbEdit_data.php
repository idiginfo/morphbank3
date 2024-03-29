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

$uploadMenuWidth = 80;   
$uploadMenuInitHorPosition = 425; // Based of mainNavOutContainer from mb2_main.css
$uploadMenu = array(	 array( 	'name' => 'Locality',
							'href' => 'Locality/',
							'description' => '<p>Allows users to edit Locality.</p>',
							'horPosition' => $uploadInitHorPosition ),
					array( 	'name' => 'Specimen',
							'href' => 'Specimen/',
							'description' => '<p>Allows users to edit Specimens</p>',
							'horPosition' => $uploadInitHorPosition + $uploadMenuWidth),
					array( 	'name' => 'View',
							'href' => 'View/',
							'description' => '<p>Allows users to edit Views</p>',
							'horPosition' => $uploadInitHorPosition + ($uploadMenuWidth*2)),
					array( 	'name' => 'Image',
							'href' => 'Image/',
							'description' => '<p>Allows users to edit Images</p>',
							'horPosition' => $uploadInitHorPosition + ($uploadMenuWidth*3)),
					array( 	'name' => 'Annotation',
							'href' => '../Annotation/annotationManager.php',
							'description' => '<p>Allows users to edit Annotations</p>',
							'horPosition' => $uploadInitHorPosition + ($uploadMenuWidth*4)),
					array( 	'name' => 'Collection',
							'href' => '../myCollection/manageCollections.php',
							'description' => '<p>Allows users to edit Collections</p>',
                  					'horPosition' => $uploadInitHorPosition + ($uploadMenuWidth*5)),
			                array(  'name' => 'Publication',
				                        'href' => '../Admin/Publication/editPublication.php',
				                        'description' => '<p>Allows users to edit Publications</p>',
						        'horPosition' => $uploadInitHorPosition + ($uploadMenuWidth*6)),
            			        array(  'name' => 'Taxon Name',
				                        'href' => '../TaxonSearch/editTSN.php',
				                        'description' => '<p>Allows users to edit TSN names</p>',
				                        'horPosition' => $uploadInitHorPosition + ($uploadMenuWidth*7))


		);

$supportMenu = array(	 array( 	'name' => 'Imaging Technique',
							'href' => 'ImagingTechnique/',
							'description' => '<p>Allows users to edit Imaging Technique</p>',
							'horPosition' => $uploadInitHorPosition + ($uploadMenuWidth*4)),
					array( 	'name' => 'Imaging Preparation Technique',
							'href' => 'ImagingPreparationTechnique/',
							'description' => '<p>Allows users to edit Imaging Preparation Technique</p>',
							'horPosition' => $uploadInitHorPosition + ($uploadMenuWidth*5)),
					array( 	'name' => 'Specimen Part',
							'href' => 'Part/',
							'description' => '<p>Allows users to edit Specimen Part</p>',
							'horPosition' => $uploadInitHorPosition + ($uploadMenuWidth*6)),
					array( 	'name' => 'Sex',
							'href' => 'Sex/',
							'description' => '<p>Allows users to edit Sex</p>',
							'horPosition' => $uploadInitHorPosition + ($uploadMenuWidth*7)),
					array( 	'name' => 'Form',
							'href' => 'Form/',
							'description' => '<p>Allows users to edit Form.</p>',
							'horPosition' => $uploadInitHorPosition + ($uploadMenuWidth*8)),
					array( 	'name' => 'Developmental Stage',
							'href' => 'Stage/',
							'description' => '<p>Allows users to edit Developmental Stage.</p>',
							'horPosition' => $uploadInitHorPosition + ($uploadMenuWidth*9)),
					array( 	'name' => 'View Angle',
							'href' => 'ViewAngle/',
							'description' => '<p>Allows users to edit View Angle.</p>',
							'horPosition' => $uploadInitHorPosition + ($uploadMenuWidth*10)),
					array( 	'name' => 'Link Type',
							'href' => 'LinkType/',
							'description' => '<p>Allows users to edit View Angle.</p>',
							'horPosition' => $uploadInitHorPosition + ($uploadMenuWidth*11))
				);

?>
