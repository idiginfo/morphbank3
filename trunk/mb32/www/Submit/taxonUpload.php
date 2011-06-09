<?php
/**
 * $file can be xls, xlsx, csv, ods
 */
if (substr(php_sapi_name(), 0, 3) == 'cli') {
  require_once(dirname(dirname(dirname(__FILE__))) . '/configuration/app.server.php');
  if (empty($argv[1])) die("Error: File path required in passed variables.\n");
  $file = $argv[1];
  $lb = "\n";
} else {
  $file = "";
  $lb = "<br />";
  die("Web form upload has not been created yet.$lb");
}

if (!file_exists($file)) die("$file does not exist.$lb");

require_once('Classes/PHPExcel.php');
include_once('updater.class.php');
include_once('tsnFunctions.php');
include_once('updateObjectKeywords.php');
include_once('extLinksRefs.php');
include_once('urlFunctions.inc.php');

$db = connect();

$pathinfo = pathinfo($file);
$ext = $pathinfo['extension'];

// Set file type
switch ($ext) {
  case 'xlsx':
      $inputFileType = 'Excel2007';
    break;
  case 'xls':
      $inputFileType = 'Excel5';
    break;
  case 'csv':
      $inputFileType = 'CSV';
    break;
  case 'ods';
      $inputFileType = 'OOCalc';
    break;
}

$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load($file);
$rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();

/**
 * Required fields
 * 
 * ScientificName => 30
 * Usage => 33
 * Group => 36
 * User => 37
 * Submitter => 38
 */
$reqFields = array(30, 33, 36, 37, 38);

// Loop through sheet, collect data, filter, find rank
$array_data = array();
foreach ($rowIterator as $row) {
	$cellIterator = $row->getCellIterator();
	$cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
	$rowIndex = $row->getRowIndex();
	foreach ($cellIterator as $cell) {
		$cellColumn = $cell->getColumn();
		$colIndex   = $cell->columnIndexFromString($cellColumn);
		$cellValue  = $cell->getCalculatedValue();
    
    // if columns missing, report error
    if (in_array($colIndex, $reqFields) && empty($cellValue)) die("Missing required value for cell $cellColumn $rowIndex.$lb");
    
    // If this is row one, save the values to reference later
    if ($rowIndex == 1) {
      if ($colIndex > 27) $header_row[$colIndex] = strtolower($cellValue);
      continue;
    } else {
      if ($colIndex > 27) {
        $array_data[$rowIndex][$header_row[$colIndex]] = trim($cellValue);
      } else {
        $array_data[$rowIndex][$colIndex] = trim($cellValue);
      }
    }
	}
  if ($rowIndex == 1) continue;
	$tree_data[$rowIndex] = array_filter(array_slice($array_data[$rowIndex], 0, 28, true));
	end($tree_data[$rowIndex]);
	$rank = getRankByColumn(key($tree_data[$rowIndex]));
	$taxon_data[$rowIndex] = $tree_data[$rowIndex] + array_slice($array_data[$rowIndex], 27, 10, true);
  $taxon_data[$rowIndex]['rankid'] = $rank;
}


/**
 * Loop through compiled taxon data
 */
$taxonCache = array();
foreach ($taxon_data as $taxon) {
  $taxonBranch = NULL;
  $scientific_name = NULL;
  $parent_tsn = 0;
  
  /**
   * Loop through columns of single row
   * If scientific name does not exist, create it.
   * Cache taxon branch string in array to avoid hitting database every time
   */
	for ($col = 1; $col <= 27; $col++) {
    // If column empty, continue
		if (empty($taxon[$col])) continue;
    
		$taxonBranch .= ($col == 1) ? $taxon[$col] : ' ' . $taxon[$col];
		
    // If taxon branch is cached, set as parent tsn and continue to next column
    if (array_key_exists($taxonBranch, $taxonCache)) {
      $parent_tsn = $taxonCache[$taxonBranch];
      continue;
    }
    
    // Get rank according to column
		$rank_id = getRankByColumn($col);
		$parent_name = trim(getScientificName($parent_tsn));
		$scientific_name = trim(createNewScientificName($taxon[$col], $parent_name, $rank_id, $parent_tsn));
    echo "Check scientific name: $scientific_name.$lb";
    $row = getRowByScientificName($scientific_name, $rank_id, $parent_tsn);
    
    if (!is_null($row)) {
      echo "Scientific name $scientific_name already exists.$lb";
			$taxonCache[$taxonBranch] = $row->tsn;
			$parent_tsn = $row->tsn;
		} else {
      // If $taxon['rank'] == $rank_id, we have reached the complete scientific name
      // and can now enter all other pertinent information concerning taxon
      echo "Scientific name $scientific_name does not exist. Preparing data.$lb";
      if ($taxon['rankid'] == $rank_id) {
        $kingdom_id = getKingdomId($taxon[1]);
        $taxon_author_id = $db->quote(FindAuthor($taxon['taxonauthordate'], $kingdom_id));
        $authorName = $db->quote(FindAuthorName($taxon_author_id));
        $letter = $db->quote($taxon['scientificname'][0]);
        $scientificName = $db->quote($taxon['scientificname']);
        $comment = empty($taxon['comment']) ? $db->quote(NULL): $db->quote($taxon['comment']);
      } else {
        // If rank ids do not match, prepare to insert new scientific name
        $taxon_author_id = $db->quote(NULL);
        $authorName = $db->quote(NULL);
        $letter = $db->quote($scientific_name[0]);
        $scientificName = $db->quote($scientific_name);
        $comment = $db->quote(NULL);
      }
      
      // Begin transaction
      $db->beginTransaction();
      echo "Starting TreeInsert transaction for $scientificName.$lb";
      
      //Call Tree Insert procedure to insert new taxa
      $params = array();
      $params[] = $db->quote(NULL);
      $params[] = $db->quote($taxon['usage']);
      $params[] = $db->quote($taxon['submitter'], 'integer');
      $params[] = $db->quote($taxon['group'], 'integer');
      $params[] = $db->quote($taxon['user'], 'integer');
      $params[] = "NOW()";
      $params[] = $db->quote('mtsn');
      $params[] = $db->quote($parent_tsn, 'integer');
      $params[] = $db->quote($kingdom_id, 'integer');
      $params[] = $db->quote($rank_id, 'integer');
      $params[] = $letter;
      $params[] = $scientificName;
      $params[] = $taxon_author_id;
      $params[] = $db->quote(NULL);
      $params[] = $db->quote(NULL);
      $params[] = $db->quote($taxon['nametype']);
      $params[] = $db->quote($taxon['namesource']);
      $params[] = $comment;

      $result = $db->executeStoredProc('TreeInsert', $params);
      if (isMdb2Error($result, 'TreeInsert Stored Procedure', 6)) {
        $db->rollback();
        die("Error: TreeInsert transaction failed. Rolled back for $scientificName.$lb");
      } else {
        $db->commit();
        echo "TreeInsert transaction committed for $scientificName.$lb";
        echo $lb;
      }
      
      $tsn = $result->fetchOne();
      clear_multi_query($result);
      
      $db->beginTransaction();
      echo "Starting transaction for Taxa update.$lb";
      
      // Update Taxa
      $parentName = getScientificName($parent_tsn);
      $sciNames = getTaxonomicNamesFromBranch($parent_tsn, " ", false);
      $taxonomicNames = $sciNames . ' ' . $scientificName;
      $taxaUpdater = new Updater($db, $tsn, $taxon['user'] , $taxon['group'], 'Taxa', 'tsn', false);
      $taxaUpdater->addField('parent_name', $parentName, null);
      $taxaUpdater->addField('taxon_author_name', $authorName, null);
      $taxaUpdater->addField('taxonomicNames', $taxonomicNames, null);
      $numRowsTaxa = $taxaUpdater->executeUpdate();
      if (is_string($numRowsTaxa)) { // Error returned
        $db->rollback();
        die("Error: Transaction rolled back for Taxa update on $scientificName.$lb");
        
      } else {
        $db->commit();
        echo "Transaction committed for Taxa update on $scientificName.$lb";
      }
      $taxonCache[$taxonBranch] = $tsn;
      $parent_tsn = $tsn;
		}
    echo "$lb";
	}
}
exit;