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

// This module will define the variables, structures (XML code generation) and details of how MB2 
// will communicate with others Web Sites such as:
//	- ITIS (taxonomic names)
//  - Google Map (locations)
//	- NCBI-National Center for Biotechnology Information (GenBank links) 
//	- and more

// ITIS link
// We have to add (concat) the tsn number to this string
$itisHref = "http://www.itis.gov/servlet/SingleRpt/SingleRpt?search_topic=TSN&amp;search_value=";

$ncbiNucleotideHref = "http://www.ncbi.nlm.nih.gov/entrez/viewer.fcgi?db=nucleotide&amp;val=";
?>
