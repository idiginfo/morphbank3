<?php
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
