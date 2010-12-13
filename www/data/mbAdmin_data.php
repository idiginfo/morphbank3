<?php
//include_once('../config.inc');

global $config;

$adminMenuWidth = 80;
$adminMenuInitHorPosition = 425; // Based of mainNavOutContainer from mb2_main.css
$adminMenu = array(
				array(
					'name' => 'User',
					'href' => 'User/',
					'description' => '<p>Performs basic database maintenance functions on user table</p>',
					'horPosition' => $adminMenuInitHorPosition
				),
				array( 
					'name' => 'Group',
					'href' => 'Group/',
					'description' => '<p>Performs basic database maintenance functions on group table</p>',
					'horPosition' => $adminMenuInitHorPosition + $adminMenuWidth
				),
				array(
					'name' => 'News',
					'href' => 'News/',
					'description' => '<p>Update News items</p>',
					'horPosition' => $adminMenuInitHorPosition + ($adminMenuWidth*3)
				),
				array(
					'name' => 'Mirror Server',
				    'href' => 'MirrorServer/',
                    'description' => '<p>Creates and updates mirror sites</p>',
					'horPosition' => $adminMenuInitHorPosition + ($adminMenuWidth*4)
				),
				array(
					'name' => 'Taxon Name Search',
                    'href' => '../Admin/TaxonSearch/index.php?searchonly=1/',
                    'description' => '<p>Search for a TSN.  Provides a drill down tree search capability to locate a specific 
                    				TSN number associated with a Kingdom, phylum, class, order, family, genus, species or any 
                    				one of the inter-categories. </p>',
					'horPosition' => $adminMenuInitHorPosition + ($adminMenuWidth*5)
				)
			);
