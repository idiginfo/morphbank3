#-------------------------------------------------------------------------------
# Copyright (c) 2010 Greg Riccardi, Fredrik Ronquist.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Public License v2.0
# which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# 
# Contributors:
#   Fredrik Ronquist - conceptual modeling and interaction design
#   Austin Mast - conceptual modeling and interaction design
#   Greg Riccardi - initial API and implementation
#   Wilfredo Blanco - initial API and implementation
#   Robert Bruhn - initial API and implementation
#   Christopher Cprek - initial API and implementation
#   David Gaitros - initial API and implementation
#   Neelima Jammigumpula - initial API and implementation
#   Karolina Maneva-Jakimoska - initial API and implementation
#   Katja Seltmann - initial API and implementation
#   Stephen Winner - initial API and implementation
#-------------------------------------------------------------------------------
<?php
require_once('Classes/PHPExcel.php');
require_once('tsnFunctions.php');

// Set Excel file
$file = 'robertTestXLS.xls';

// Instaniate PHPExcel
$objReader = new PHPExcel_Reader_Excel5();
$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load($file);
$rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();

/**
 * Required fields
 * ScientificName => 30
 * Usage          => 33
 * Group Id       => 36
 * User Id        => 37
 * Submitter Id   => 38
 */
$reqFields = array(30, 33, 36, 37, 38);

// Loop through sheet, collect data, filter, find rank
$array_data = array();
foreach ($rowIterator as $row) {
	$cellIterator = $row->getCellIterator();
	$cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
	$rowIndex = $row->getRowIndex ();
	if (1 == $rowIndex) continue;
	foreach ($cellIterator as $cell) {
		$cellColumn = $cell->getColumn();
		$colIndex   = $cell->columnIndexFromString($cellColumn);
		$cellValue  = $cell->getCalculatedValue();
		if (in_array($colIndex, $reqFields) && empty($cellValue)) {
			die("Missing required value for cell " . $cellColumn . $rowIndex);
		}
		$array_data[$rowIndex][$colIndex] = trim($cellValue);
	}
	$tree_data[$rowIndex] = array_filter(array_slice($array_data[$rowIndex], 0, 28, true));
	end($tree_data[$rowIndex]);
	$tree_data[$rowIndex]['rank'] = getRankByColumn(key($tree_data[$rowIndex]));
	$taxon_data[$rowIndex] = $tree_data[$rowIndex] + array_slice($array_data[$rowIndex], 28, 10, true);
}

/**
 * Loop through taxon data
 * Build and cache the taxa tree for each row to avoid hitting database if taxon already checked
 */
$taxonCache = array();
$parent_tsn = 0;
foreach ($taxon_data as $taxon) {
	$taxonString = '';
	echo "<br /><br />new row<br />";
	for ($i = 1; $i <= 18; $i++) {
		if (empty($taxon[$i])) continue;
		$taxonString .= ($i == 1) ? $taxon[$i] : ' ' . $taxon[$i];
		//$taxonString = md5($taxonString);
		if (isset($taxonCache[$taxonString])) continue; // Already checked
		$rank_id = getRankByColumn($i);
		$parent_name = getScientificName($parent_tsn);
		$scientific_name = createNewScientificName($taxon[$i], $parent_name, $rank_id, $parent_tsn);
		$row = getRowByScientificName($scientific_name, $rank_id, $parent_tsn);
		if (!is_null($row)) {
			$taxonCache[$taxonString] = true;
			$parent_tsn = $row['tsn'];
			echo "$scientific_name : $taxonString cached<br />";
		} else {
			echo "insert $scientific_name<br />";
			$taxonCache[$taxonString];
		}
	}

}
echo '<pre>';
print_r($taxonCache);
echo '</pre>';
exit;

/**
 Cell values

 [1] => Kingdom
 [2] => Subkingdom
 [3] => Phylum
 [4] => Subphylum
 [5] => Superclass
 [6] => Class
 [7] => Subclass
 [8] => Infraclass
 [9] => Superorder
 [10] => Order
 [11] => Suborder
 [12] => InfraOrder
 [13] => Superfamily
 [14] => Family
 [15] => Subfamily
 [16] => Tribe
 [17] => Subtribe
 [18] => Genus
 [19] => Subgenus
 [20] => Section
 [21] => Subsection
 [22] => Species
 [23] => Subspecies
 [24] => Variety
 [25] => Subvariety
 [26] => Form
 [27] => Subform
 [28] => Cultivar
 [29] => comments
 [30] => scientificName
 [31] => taxon_author
 [32] => nameType
 [33] => usage
 [34] => nameSource
 [35] => url
 [36] => Group Id
 [37] => User Id
 [38] => Submitter Id
 */
