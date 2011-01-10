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

global $config;

$optionsMenuWidth = 80;   
$optionsMenuInitHorPosition = 425; // Based of mainNavOutContainer from mb2_main.css

$optionsMenu = array(			array( 	'name' => 'Browse',
							'href' => $config->domain. 'Browse/',
							'description' => '<p>Browse images, specimens, views, localities, collections, annotations etc.</p>',
							'horPosition' => $optionsMenuInitHorPosition),
					array(	'name' => 'Search',
							'href' => $config->domain.'Search/',
							'description' => '<p>Allows users to search MorphBank</p>',
							'horPosition' => $optionsInitHorPosition + $optionsMenuWidth),
					array(	'name' => 'Submit',
							'href' => $config->domain.'Submit/',
							'description' => '<p>Allows Access to Upload module: Add Location/Specimens/Views/Images</p>',
							'horPosition' => $optionsMenuInitHorPosition + ($optionsMenuWidth*2)),
					array(	'name' => 'Edit',
							'href' => $config->domain.'Edit/',
							'description' => '<p>Allows Access to Edit module: Edit user Localities/Specimens/Views/Images as well as other reference objects</p>',
							'horPosition' => $optionsMenuInitHorPosition + ($optionsMenuWidth*3)),
					array(	'name' => 'Collection',
							'href' => $config->domain.'myCollection/manageCollections.php',
							'description' => '<p>Manages user collections</p>',
							'horPosition' => $optionsMenuInitHorPosition + ($optionsMenuWidth*4)),
					array(	'name' => 'Annotation',
							'href' => $config->domain.'Annotation/annotationManager.php',
							'description' => '<p>Allows Access to Upload module: Add Location/Specimens/Views/Images</p>',
							'horPosition' => $optionsMenuInitHorPosition + ($optionsMenuWidth*4)),
				);

?>
