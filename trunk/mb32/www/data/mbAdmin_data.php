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
