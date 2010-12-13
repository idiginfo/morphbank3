<?php
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
