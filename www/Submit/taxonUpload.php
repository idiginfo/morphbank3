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
 * ScientificName => 28
 * Rank => 29
 * TaxonAuthorDate => 30
 * NameType => 31
 * DateToPublish => 32
 * Usage => 33
 * NameSource => 34
 * URL => 35
 * Comment => 36
 * Group => 37
 * User => 38
 * Submitter => 39
 */
$reqFields = array(28, 29, 31, 32, 33, 37, 38, 39);

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
    //if (in_array($colIndex, $reqFields) && empty($cellValue)) die("Missing required value for cell $colIndex $rowIndex.$lb");
    
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
	$tree_data[$rowIndex] = array_filter(array_slice($array_data[$rowIndex], 0, 27, true));
	end($tree_data[$rowIndex]);
	$rank = getRankByColumn(key($tree_data[$rowIndex]));
	$taxon_data[$rowIndex] = $tree_data[$rowIndex] + array_slice($array_data[$rowIndex], 27, 17, true);
  $taxon_data[$rowIndex]['rankid'] = $rank;
  $taxon_data[$rowIndex]['datetopublish'] = gmdate('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($taxon_data[$rowIndex]['datetopublish']));
}

// Delete and recreate existing log file to start fresh
$filename = ($lb == "\n") ? "taxonUploadLog.txt" : "taxonUploadLog.html";
if (file_exists($filename)) {
  if (!unlink($filename)) die("Could not delete old log file.$lb");
} else {
  if (!$fh = fopen($filename, 'w')) die("Unable to create log file.$lb");
  fclose($fh);
}


/**
 * Loop through compiled taxon data
 */
$taxonCache = array();
$row_value = 1;
foreach ($taxon_data as $taxon) {
  // Increment row value so we can easily find Row in Excel sheet
  $row_value++;
  
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
    
    $col = trim($col);
    $taxonBranch .= ($col == 1) ? $taxon[$col] : ' ' . $taxon[$col];
		
    // If taxon branch is cached, set as parent tsn and continue to next column
    if (array_key_exists($taxonBranch, $taxonCache)) {
      $parent_tsn = $taxonCache[$taxonBranch];
      continue;
    }
    
    
    $rank_id = getRankByColumn($col);
    $rank_name = getRankName($rank_id);
		$parent_name = trim(getScientificName($parent_tsn));
		$scientific_name = trim(createNewScientificName($taxon[$col], $parent_name, $rank_id, $parent_tsn, TRUE));
    write_log("Check scientific name: $scientific_name, rank_id: $rank_id, parent_tsn: $parent_tsn.");
    $row = getRowByScientificName($scientific_name, $rank_id, $parent_tsn);
    
    if (!is_null($row)) {
      write_log("Scientific name $scientific_name already exists.");
			$taxonCache[$taxonBranch] = $row->tsn;
			$parent_tsn = $row->tsn;
      write_log("Setting parent tsn for branch cache to $parent_tsn.");
		} else {
      // If $taxon['rank'] == $rank_id, we have reached the complete scientific name
      // and can now enter all other pertinent information concerning taxon
      write_log("Scientific name $scientific_name does not exist. Preparing data.");
      if ($taxon['rankid'] == $rank_id) {
        $kingdom_id = getKingdomId($taxon[1]);
        $letter = $taxon['scientificname'][0];
        $scientificName = $taxon['scientificname'];
        $taxon_author_id = FindAuthor($taxon['taxonauthordate'], $kingdom_id);
        $authorName = FindAuthorName($taxon_author_id);
        $comment = empty($taxon['comment']) ? NULL: $taxon['comment'];
        $nameSource = $taxon['namesource'];
      } else {
        // If rank ids do not match, prepare to insert new parent scientific name
        $kingdom_id = getKingdomId($taxon[1]);
        $letter = $scientific_name[0];
        $scientificName = $scientific_name;
        $taxon_author_id = NULL;
        $authorName = NULL;
        $comment = NULL;
        $nameSource = NULL;
      }
      
      // Begin transaction
      $db->beginTransaction();
      write_log("Starting DB transactions for $scientificName.");
      
      // Get next tsn
      $tsn = getNextTsn();
      
      // Params for Tree Insert
      $treeParams = array(
          $tsn,
          $taxon['usage'],
          'mtsn',
          $parent_tsn,
          $kingdom_id,
          $rank_id,
          $letter,
          $scientificName,
          $taxon_author_id,
          $taxon['nametype'],
          $nameSource,
          $comment,
      );
      insertTree($treeParams);
      
      // Get next BaseObject id
      $bid = getNextBaseObjectId();
      
      // Params for BaseObject Insert
      $boParams = array(
          $bid,
          $taxon['user'],
          $taxon['group'],
          $db->mdbNow(),
          $db->mdbNow(),
          $taxon['datetopublish'],
          'TaxonConcept',
          'taxon',
          $taxon['submitter'],
          $scientificName,
      );
      insertBaseObject($boParams);
      
      // insert TaxonConcept
      $tcParams = array(
          $bid,
          $tsn,
          $taxon['usage'],
      );
      insertTaxonConcept($tcParams);
      
      // Insert Taxa
      write_log("Retrieving taxonomic name branch for parent tsn $parent_tsn");
      $sciNames = getTaxonomicNamesFromBranch($parent_tsn, " ", false);
      $taxonomicNames = $sciNames . ' ' . $scientificName;
      $taxaParams = array(
          $bid,
          $tsn,
          $scientificName,
          $taxon_author_id,
          $authorName,
          $taxon['usage'],
          $parent_tsn,
          $parent_name,
          $kingdom_id,
          $taxon[1],
          $rank_id,
          $rank_name,
          $taxon['nametype'],
          $nameSource,
          $taxon['user'],
          $taxon['group'],
          $taxon['datetopublish'],
          'TaxonConcept',
          $taxonomicNames,          
      );
      insertTaxa($taxaParams);
      
      // Insert external links
      insertExternalLinks($bid, $taxon);
      
      // Insert external references
      insertExternalRefs($bid, $taxon);

      // Commit all queries
      $db->commit();
      write_log("Processing of $scientificName completed.");
      
      write_log("Setting taxon branch cache value to tsn $tsn.");
      $taxonCache[$taxonBranch] = $tsn;
      write_log("Setting parent_tsn to $tsn.");
      $parent_tsn = $tsn;
		}
    write_log($lb);
	}
}
write_log("Upload script completed.", true);


/**
 * Retrieve and return the next TSN
 * 
 * @global
 *   $db Database object
 * @global
 *   $lb Line break variable
 * @return
 *   $tsn The next TSN available in the database 
 */
function getNextTsn() {
  global $db, $lb;
  
  // Get min/max range for tsn
  $sql = "select minId, maxId from CurrentIds where type='tsn'";
  $row = $db->queryRow($sql, null, MDB2_FETCHMODE_ASSOC);
  $error = "Error: Selecting min/max tsn from CurrentIds.$lb";
  handleError($row, $error, true);
  
  // Select next Tsn
  $sql = "select max(tsn+1) as tsn from Tree where tsn >= $row[minid] and tsn < $row[maxid]";
  $tsn = $db->queryOne($sql);
  $error = "Error: Selecting next tsn.$lb";
  handleError($tsn, $error, true);
  
  return empty($tsn) ? $row['minid'] : $tsn;
}

/**
 * Retrieve and return the next BaseObject Id
 * 
 * @global
 *   $db Database object
 * @global
 *   $lb Line break variable
 * @return
 *   $bid The next available BaseObject Id available in the database 
 */
function getNextBaseObjectId() {
  global $db, $lb;
  
  // Get min/max range for tsn
  $sql = "select minId, maxId from CurrentIds where type='object'";
  $row = $db->queryRow($sql, null, MDB2_FETCHMODE_ASSOC);
  $error = "Error: Selecting min/max BaseObject id from CurrentIds.$lb";
  handleError($row, $error, true);

  // Select next id
  $sql = "select max(id)+1 as bid from BaseObject where id >= $row[minid] and id < $row[maxid]";
  $bid = $db->queryOne($sql);
  $error = "Error: Selecting next tsn.$lb";
  handleError($bid, $error, true);
  
  if (empty($bid)) write_log ("Error: Next BaseObject id value returned empty.", true);
  
  return $bid;
}

/**
 * Insert new Tree record
 * 
 * @global
 *   $db Database object
 * @global
 *   $lb Line break
 * @global
 *   $row_value Current row value of Excel sheet
 * @global
 *   $scientificName Scientific name being processed
 * @global
 *   $tsn Current tsn id
 * @global
 *   $parent_tsn Current parent tsn id
 * @param
 *   $treeParams Array of values used during Tree insert
 * @return
 */
function insertTree($treeParams) {
  global $db, $lb, $row_value, $scientificName, $tsn, $parent_tsn;

  $values = "Row: $row_value, $scientificName, tsn: $tsn, parent_tsn: $parent_tsn.";
  
  $sql = "insert into Tree set tsn = ?, `usage` = ?, unaccept_reason = ?, parent_tsn = ?, kingdom_id = ?, rank_id = ?, " . 
         "letter = ?, scientificName = ?, taxon_author_id = ?, nameType = ?, nameSource = ?, comments = ?"; 
  $stmt = $db->prepare($sql);
  $error = "Error: Preparing Tree insert for: " . $values;
  handleError($stmt, $error, true);
  
  $error = "Error: Executing Tree insert for: " . $values;
  $affRows = $stmt->execute($treeParams);
  handleError($affRows, $error, true);

  $stmt->free();
  
  write_log("Inserted Tree: " . $values);
  
  return;
}

/**
 * Insert new BaseObject record
 * 
 * @global
 *   $db Database object
 * @global
 *   $lb Line break
 * @global
 *   $treeParams Array of values used in Tree insert
 * @global
 *   $row_value Current row value of Excel sheet
 * @global
 *   $scientificName Scientific name being processed
 * @global
 *   $bid Current BaseObject id
 * @global
 *   $tsn Current tsn id
 * @global
 *   $parent_tsn Current parent tsn id
 * @param
 *   $boParams Array of values used for BaseObject insert
 * @return
 */
function insertBaseObject($boParams) {
  global $db, $treeParams, $lb, $row_value, $scientificName, $bid, $tsn, $parent_tsn;
  
  $values = "Row: $row_value, $scientificName, bid: $bid, tsn: $tsn, parent_tsn: $parent_tsn.";
  
  $sql = "insert into BaseObject set id = ?, userId = ?, groupId = ?, dateCreated = ?, dateLastModified = ?, dateToPublish = ?, " . 
         "objectTypeId = ?, description = ?, submittedBy = ?, name = ?";
  $stmt = $db->prepare($sql);
  $error = "Error: Preparing BaseObject insert for: " . $values;
  handleError($stmt, $error, true);

  $error = "Error: Executing BaseObject insert for: " . $values;
  $affRows = $stmt->execute($boParams);
  handleError($affRows, $error, true);

  $stmt->free();
  
  write_log("Inserted BaseObject: " . $values);
  
  return;
}

/**
 * Insert new TaxonConcept
 * 
 * @global
 *   $db Database object
 * @global
 *   $lb Line break
 * @global
 *   $treeParams Array of values used in Tree insert
 * @global
 *   $row_value Current row value of Excel sheet
 * @global
 *   $scientificName Scientific name being processed
 * @global
 *   $bid Current BaseObject id
 * @global
 *   $tsn Current tsn id
 * @global
 *   $parent_tsn Current parent tsn id
 * @param
 *   $tcParams Array of values used in TaxonConcept insert
 * @return
 */
function insertTaxonConcept($tcParams) {
  global $db, $lb, $treeParams, $row_value, $scientificName, $bid, $tsn, $parent_tsn;
  
  $values = "Row: $row_value, $scientificName, bid: $bid, tsn: $tsn, parent_tsn: $parent_tsn.";
  
  $sql = "insert into TaxonConcept set id = ?, tsn = ?, status = ?";
  $stmt = $db->prepare($sql);
  $error = "Error: Preparing TaxonConcept insert for: " . $values;
  handleError($stmt, $error, true);

  $error = "Error: Executing TaxonConcept insert for: " . $values;
  $affRows = $stmt->execute($tcParams);
  handleError($affRows, $error, true);

  $stmt->free();
  
  write_log("Inserted TaxonConcept: " . $values);
  
  return;
}

/**
 * Insert Taxa record
 * 
 * @global
 *   $db Database object
 * @global
 *   $lb Line break
 * @global
 *   $row_value Current row value of Excel sheet
 * @global
 *   $scientificName Scientific name being processed
 * @global
 *   $bid Current BaseObject id
 * @global
 *   $tsn Current tsn id
 * @global
 *   $parent_tsn Current parent tsn id
 * @param
 *   $taxaParams Array of values used in Taxa insert
 * @return
 */
function insertTaxa($taxaParams) {
  global $db, $lb, $row_value, $scientificName, $bid, $tsn, $parent_tsn;

  $values = "Row: $row_value, $scientificName, bid: $bid, tsn: $tsn, parent_tsn: $parent_tsn.";
  
  $sql = "insert into Taxa set boId = ?, tsn = ?, scientificName = ?, taxon_author_id = ?, taxon_author_name = ?, status = ?, " . 
         "parent_tsn = ?, parent_name = ?, kingdom_id = ?, kingdom_name = ?, rank_id = ?, rank_name = ?, nameType = ?, " . 
         "nameSource = ?, userId = ?, groupId = ?, dateToPublish = ?, objectTypeId = ?, taxonomicNames = ?";
  $stmt = $db->prepare($sql);
  $error = "Error: Preparing Taxa insert for: " . $values;
  handleError($stmt, $error, true);
  
  $error = "Error: Executing Taxa insert for: " . $values;
  $affRows = $stmt->execute($taxaParams);
  handleError($affRows, $error, true);
  
  $stmt->free();
  
  write_log("Inserted Taxa: " . $values);
  
  return;
}

/**
 * Insert External Links
 * 
 * @global
 *   $db Database object
 * @global
 *   $lb Line break
 * @global
 *   $taxaParams Array of values used in Taxa insert
 * @global
 *   $row_value Current row value of Excel sheet
 * @global
 *   $scientificName Scientific name being processed
 * @global
 *   $tsn Current tsn id
 * @global
 *   $parent_tsn Current parent tsn id
 * @param
 *   $bid Current BaseObject id
 * @param
 *   $taxon Array values for current Excel row
 * @return
 */
function insertExternalLinks($bid, $taxon) {
  global $db, $lb, $taxaParams, $row_value, $scientificName, $tsn, $parent_tsn;
  
  $values = "Row: $row_value, $scientificName, bid: $bid, tsn: $tsn, parent_tsn: $parent_tsn.";
    
  if (empty($bid)) {
    $db->rollback();
    $error = "Error: Missing BaseObject Id needed for External Links: " . $values;
    write_log($error, true);
  }
  
  if (is_null(checkExternalLinks($taxon))) return;
  
  if (!checkExternalLinks($taxon)) {
    $error = "Error: Missing required values (type, label, url) for External Links: " . $values;
    write_log($error, true);
  }
  
  $type = trim($taxon['externallinktype']);
  $label = trim($taxon['externallinklabel']);
  $url = trim($taxon['externallinkurl']);
  $description = trim($taxon['externallinkdescription']);
  
  $params = array($type);
  $type_id = $db->getOne("select linkTypeId from ExternalLinkType where name = ?", null, $params);
  $error = "Error: Row $row_value. Selecting linkTypeId for $type.";
  handleError($type_id, $error, true);
  
  $values = "Row: $row_value";
  
  $params = array($bid, $type_id, $label, $url, $description, NULL);
  $sql = "insert into ExternalLinkObject set mbId = ?, extLinkTypeId = ?, Label = ?, urlData = ?, description = ?, externalId = ?";
  $stmt = $db->prepare($sql);
  $error = "Error: Preparing External Links insert for " . $values;
  handleError($stmt, $error, true);

  $error = "Error: Execute External Links insert for " . $values;
  $affRows = $stmt->execute($params);
  handleError($affRows, $error, true);

  $stmt->free();
  
  write_log("Inserted External Link: " . $values);
  
  return;
}

/**
 * Insert External References
 * 
 * @global
 *   $db Database object
 * @global
 *   $lb Line break variable
 * @global
 *   $taxaParams Array of values used for Taxa insert
 * @global
 *   $row_value Current Excel row
 * @global
 *   $scientificName Current Scientific name
 * @global
 *   $tsn Current tsn id
 * @global
 *   $parent_tsn Current parent tsn id
 * @param
 *   $bid Current BaseObject id
 * @param
 *   $taxon Array representing values in Excel row
 * @return type 
 */
function insertExternalRefs($bid, $taxon) {
  global $db, $lb, $taxaParams, $row_value, $scientificName, $tsn, $parent_tsn;
   
  $values = "Row: $row_value, $scientificName, bid: $bid, tsn: $tsn, parent_tsn: $parent_tsn.";
    
  if (empty($bid)) {
    $db->rollback();
    $error = "Error: Missing BaseObject Id needed for External References: " . $values;
    write_log($error, true);
  }
  
  $type_id = 4;
  $description = trim($taxon['externaleefdescription']);
  $external_id = trim($taxon['externalrefuniqueid']);
  
  if ((!empty($external_id) && empty($description)) || (empty($external_id) && !empty($description))) {
    $error = "Error: Missing required values for External References: " . $values;
    write_log($error, true);
  }
  
  $params = array($bid, $type_id, $description, $external_id);
  $sql = "insert into ExternalLinkObject set mbId = ?, extLinkTypeId = ?, description = ?, externalId = ?";
  $stmt = $db->prepare($sql);
  $error = "Error: Preparing External References insert for " . $values;
  handleError($stmt, $error, true);

  $error = "Error: Execute External References insert for " . $values;
  $affRows = $stmt->execute($params);
  handleError($affRows, $error, true);

  $stmt->free();
  
  write_log("Inserted External References: " . $values);
  
  return;
  
}

/**
 * Assess whether external links are empty or valid
 * 
 * @param
 *   $taxon Array representing Excel row
 * @return
 *   Return null or boolean value depending on comparisons
 */
function checkExternalLinks($taxon) {
  $type = trim($taxon['externallinktype']);
  $label = trim($taxon['externallinklabel']);
  $url = trim($taxon['externallinkurl']);
  
  if (empty($type) && empty($label) && empty($url)) return null;
  if (!empty($type) && (empty($label) || empty($url))) return false;
  if (!empty($label) && (empty($type) || empty($url))) return false;
  if (!empty($url) && (empty($type) || empty($label))) return false;
  
  return true;
}

/**
 * Function to write data to log as script is being
 * parsed and entered into the database
 * 
 * @global
 *   $filename File name for the log
 * @global
 *   $lb Line break variable
 * @param
 *  $string String containing error inforamtion
 * @param
 *  $die Boolean value to tell script to quit
 * @return 
 */
function write_log($string, $die = false) {
  global $filename, $lb;
  
  if (!$fh = fopen($filename, 'a')) {
    die("Unable to open log file.");
  }
  fwrite($fh, $string.$lb);
  fclose($fh);
  
  echo $string.$lb;
  
  if ($die) die();
  
  return;
}

/**
 * Handles errors when the occur
 * 
 * @global
 *   $db Database object
 * @param
 *   $stmt Database statement
 * @param
 *   $error Error string
 * @param
 *   $die Boolean value to determine if script should stop
 * @return
 */
function handleError($stmt, $error, $die = false) {
  global $db;
  
  if (!PEAR::isError($stmt)) return;
  $db->rollback();
  $error .= $stmt->getUserInfo();
  write_log($error, $die);
  
  return;
}
