<?php
/**
 * create and process the taxon search form
 * Includes display of the search form and the search results
 */
function mainTaxon() {
	global $objInfo, $config;

	$link = Adminlogin();

	if (isset($_GET['returnURL'])){
		$returnURL = $_GET['returnURL'];
	} else {
		$returnURL = $_SERVER['HTTP_REFERER'];
	}
	$pop = $_GET['pop'] == 'yes' ? "yes" : "";

	echo '<div class="mainGenericContainer" id="taxonSelect" style="width:90%; min-width: 850px;">';

	//retrieving SESSION variables
	$groupId = $objInfo->getUserGroupId();
	$userRole = $objInfo->getUserGroupRole();
	
	// Set vars
	$searchonly  = $_GET['searchonly'];
	$annotation  = $_GET['annotation'];
	$synonyms    = $_GET['synonyms'];
	$TSNtype     = $_GET['TSNtype'];
	$tsn         = $_GET['tsn'];
	$view        = $_GET['view'];
	$browse      = $_GET['browse'];
	$search_name = $_GET['search_name'];
	$found       = false;
	
	// Variable array to pass through functions instead of using global
	$varArray = array(
		'searchonly' => $searchonly, 
		'annotation' => $annotation, 
		'synonyms'   => $synonyms, 
		'TSNtype'    => $TSNtype, 
		'view'       => $view, 
	    'browse'     => $browse, 
	    'returnURLL' => $returnURL, 
	    'pop'        => $pop
	);
	
	// Form submitted
	if ((!empty($search_name) && intval($search_name) > 0)) {
		$tsnTemp    = (int)$search_name;
		$result     = SingleTreeRecord($tsnTemp);
		$tsn        = $result['tsn'];
		$found      = true;
	} elseif (!empty($search_name)) {
		$search_result = getSimilarNames($search_name);
		$numRows = $search_result->numRows();
		if ($numRows > 0) {
			if ($numRows > 1) {
				echo "<span style='color:#17256B'><b> There is more than one result matching your search criteria.";
				echo "</b></span><br/><br/>";
				$old_kingdom;
				while($result = $search_result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
					$kingdom = $result['kingdom_id'];
					if ($old_kingdom != $kingdom) {
						$kingdom_name = $result['kingdom_name'];
						echo "<span style='color:navy'><b>Kingdom $kingdom_name</b></span><br/>";
					}
					$author = TaxonAuthor($result['taxon_author_id']);
					if (!empty($author)) $author = "--" . $author;
					$reason = $result['unaccept_reason'];
					echo '&nbsp;<a href=index.php?tsn=' . $result['tsn'] . '&pop=' . $pop . '&synonyms=' . $synonyms . '&searchonly='
					. $searchonly . '&TSNtype=' . $TSNtype . '&annotation=' . $annotation
					. '&view=' . $view . '&browse=' . $browse . '&returnURL=' . urlencode($returnURL) . '>' 
					. trim($result['scientificname']) . '</a>' . $author . '--TSN[' . $result['tsn'] . ']' . $reason . '<br/>';
					$old_kingdom = $kingdom;
					$browse = 1;
					$found = true;
				}
				echo '<br/><br/>';
			} else {
				$result     = $search_result->fetchRow(MDB2_FETCHMODE_ASSOC);
				$tsn        = $result['tsn'];
				$found      = true;
			}
		}
	} elseif (!empty($tsn)) {
		$result     = SingleTreeRecord($tsn);
		$tsn        = $result['tsn'];
		$found      = true;
	}

	if (!$found && empty($tsn) && !empty($search_name) ) {
		echo '<span style="color:red"><b>No taxa found for search term "'.$search_name.'"</b></span><br/><br/>';
	}

	// name search form
	echo '<form method="get" id="taxonsearch" >'
		.'<input type="hidden" name="searchonly" value="' . $searchonly . '">'
		.'<input type="hidden" name="TSNtype" value="' . $TSNtype . '">'
		.'<input type="hidden" name="synonyms" value="' . $synonyms . '">'
		.'<input type="hidden" name="browse" value="' . $browse . '">'
		.'<input type="hidden" name="view" value="' . $view . '">'
		.'<input type="hidden" name="pop" value="' . $pop . '">'
		.'<table><tr>'
		.'<td>Taxon Name Search</td>'
		.'<td><input type="text" name="search_name" size="60" /></td>'
		.'<td>'
		.'<a href=\'javascript: document.getElementById("taxonsearch").submit();\' class="button smallButton"'
		.' title="Click to start your search">'
		.'<div>Search</div></a>'
		.'</td>'
		.'<td>'
		.'<a href="/Admin/TaxonSearch/index.php?searchonly='.$searchonly.'&pop='.$pop.'" class="button smallButton" title="Click to reset page">'
		.'<div>Reset</div></a>'
		.'</td>
		.</tr></table><br/>';
	echo "</form>\n";
	echo '<form method="get" id="mainform" >';
	echo '<input type="hidden" name="pop" value="' . $pop . '">';
	
	if (empty($tsn) && empty($search_name)) $tsn = 0;
	if (!empty($tsn) || empty($search_name)) {
		echo '<input type="radio" name="syno" value="1"  onclick=\'document.getElementById("synonym_yes").submit()\'';
		if ($synonyms != '0') echo 'checked="checked"';
		echo ' />Show all taxon names';
		echo '<input type="radio" name="syno" value="0"  onclick=\'document.getElementById("synonym_no").submit()\' ';
		if($synonyms == '0') echo 'checked="checked"';
		echo ' />Show valid/accepted taxon names<br /><br />';
		
		$tableline = getBranchAsLinks($tsn, $varArray);
		echo !empty($tableline) ? "<span style='font-size:12px;'>$tableline</span><br/><br/>" : "";
		
		if (!empty($result)) {
			//start of the selected taxon table
			echo '<h1><b>Search Result</b></h1><br /><br />';
			echo '<table width="100%" border="1"><tr>'
			.'<th><b>Scientific Name</b></th>'
			.'<th><b>TSN</th></b>'
			.'<th><b>Taxon Author</b></th>'
			.'<th><b>Common Name</b></th>'
			.'<th><b>Name Source</b></th>'
			.'<th><b>Usage</b></th>'
			.'<th><b>Reason (ITIS)</b></th>'
			.'<th><b>Taxon Rank</b></th>';
			if ($searchonly != '1'){
				echo '<th><b>Select Taxon Name</b></th>';
			}
			if (isLoggedIn()) {
				echo '<th><b>Annotate Taxon Name</b></th>';
				echo "<th>Add Taxon Below</th>";
			}
			echo "</tr>\n";
			buildTable($result, $varArray);
			echo '</table><br/>';
		}
		
		//start of the taxon table
		if (!empty($result)) {
			echo '<h1><b>Children of <span style="color:blue">' . $result['scientificname'] . '</span></b></h1><br /><br />';	
		}
		echo '<table width="100%" border="1"><tr>'
		.'<th><b>Scientific Name</b></th>'
		.'<th><b>TSN</th></b>'
		.'<th><b>Taxon Author</b></th>'
		.'<th><b>Common Name</b></th>'
		.'<th><b>Name Source</b></th>'
		.'<th><b>Usage</b></th>'
		.'<th><b>Reason (ITIS)</b></th>'
		.'<th><b>Taxon Rank</b></th>';
		if ($searchonly != '1'){
			echo '<th><b>Select Taxon Name</b></th>';
		}
		if (isLoggedIn()) {
			echo '<th><b>Annotate Taxon Name</b></th>';
			echo "<th>Add Taxon Below</th>";
		}
		echo "</tr>\n";


		//******************************************************************************************************
		//*  Find all of the children of the current node that list that TSN as their parent. We do not        *
		//*  care if they are of the same rank, just that they have the same parent.                           *
		//*  Also pick up their venacular (common) name to display with the list.                              *
		//******************************************************************************************************
		$db = connect();
		$query = "SELECT * FROM Tree WHERE parent_tsn = " . $tsn;
		if ($synonyms == '0') $query .= " and (`usage`='valid' or `usage`='accepted')";
		$query .= " ORDER BY scientificName";
		$results = $db->query($query);
		$numrows = $results->numRows();

		//If the taxon does not have any more children
		if ($numrows < 1) {
			echo '<table width="100%" border="1"><tr><th><b>No more records with the specified criteria<b/>'
			.'</th></tr></table></div>';
			finishHtml();
			exit(1);
		}

		while ($row = $results->fetchRow(MDB2_FETCHMODE_ASSOC)) {
			buildTable($row, $varArray);
		}
		echo '</table></form><br/>';
	}

	//create empty forms for the radio buttons
	$url_action = "index.php?&tsn=" . $tsn . "&searchonly=" . $searchonly . "&view="
					. $view . "&TSNtype=" . $TSNtype . "&pop=$pop&synonyms=";
	$url_1 = $url_action . "1";
	$url_0 = $url_action . "0";

	echo '<form id="synonym_yes" method="get" action="' . $url_1 . '">'
	.'<input type="hidden" name="tsn" value="' . $tsn . '">'
	.'<input type="hidden" name="searchonly" value="' . $searchonly . '">'
	.'<input type="hidden" name="TSNtype" value="' . $TSNtype . '">'
	.'<input type="hidden" name="synonyms" value="1">'
	.'<input type="hidden" name="view" value="' . $view . '">'
	.'<input type="hidden" name="browse" value="' . $browse . '">'
	.'<input type ="hidden" name="pop" value="' . $pop . '">';
	echo "</form>\n";

	echo '<form id="synonym_no" method="get" action="' . $url_0 . '">'
	.'<input type="hidden" name="tsn" value="' . $tsn . '">'
	.'<input type="hidden" name="searchonly" value="' . $searchonly . '">'
	.'<input type="hidden" name="TSNtype" value="' . $TSNtype . '">'
	.'<input type="hidden" name="synonyms" value="0">'
	.'<input type="hidden" name="view" value="' . $view . '">'
	.'<input type="hidden" name="browse" value="' . $browse . '">'
	.'<input type ="hidden" name="pop" value="' . $pop . '">';
	echo "</form>\n";	
	echo '</div>';
}
//end of mainTaxon

//function SingleTreeRecord
function SingleTreeRecord($tsn)
{
	$db = connect();
	$query = "SELECT t.*, k.kingdom_name FROM Tree t left join Kingdoms k on t.kingdom_id = k.kingdom_id where t.tsn=" . $tsn;
	$row = $db->getRow($query,null,null,null,MDB2_FETCHMODE_ASSOC);
	isMdb2Error($row, $query);
	return $row;
}
//end of function SingleTreeRecord

/**
 *
 * @param $search_name
 */
function getSimilarNames($search_name) {
	$array = explode(" ", trim($search_name));
	$query = "select t.*, tr.usage, tr.unaccept_reason, k.kingdom_name as kingdom_name 
				from Taxa t 
				left join Kingdoms k on k.kingdom_id = t.kingdom_id
				left join Tree tr on tr.tsn = t.tsn  
				where t.scientificName LIKE '%";
	foreach ($array as $term){
		$query .= $term . "%";
	}
	$query .= "' order by kingdom_id, scientificName";
	$db = connect();
	$result = $db->query($query);
	isMdb2Error($result, $query);
	return $result;
}
//end of function ParseName


//function that returns the rank
function FindRank($rank_id, $kingdom_id)
{
	$db = connect();
	$taxonTypeQuery = "select rank_name from TaxonUnitTypes where rank_id = $rank_id and kingdom_id = $kingdom_id";
	$rank_name = $db->getOne($taxonTypeQuery);
	isMdb2Error($rank_name, $taxonTypeQuery);
	$taxonType = trim($rank_name);
	return $taxonType;
}
//end of function FindRank

/**
 * Get the Author as an HTML-safe string
 * @param $link
 * @param $taxonAuthorId
 */
function TaxonAuthor($taxonAuthorId) {
	if (intval($taxonAuthorId)<=0) return '';
	$db = connect();
	$query_author = "SELECT taxon_author FROM TaxonAuthors WHERE taxon_author_id=" . $taxonAuthorId;
	$author = $db->getOne($query_author);
	isMdb2Error($author, $query_author);
	return htmlentities($author, ENT_QUOTES, "UTF-8");
}

function PrintAddTSN($tsn, $label = "Add new taxon", $class="button mediumButton right") {
	global $config;
	$pop = $_GET['pop'] == 'yes' ? "yes" : "";
	$rankId = getRankId($tsn);
	if ($rankId >= 100) {
		$URL = $config->domain . "Admin/TaxonSearch/addTSN.php?tsn=$tsn&pop=$pop";
		echo '<a href="'.$URL . '" class="'.$class.'" title="Click to add new taxon">';
		echo '<div>'.$label.'</div></a>';

	}
}

function getSynonym($tsn) {
	$db = connect();
	$query_name = "select t.scientificName as names from Tree t " 
					. "left join synonym_links s on s.tsn_accepted = t.tsn "
					. "where s.tsn=" . $tsn;
	$synonym = $db->getOne($query_name);
	isMdb2Error($synonym, $query_name);
	return $synonym;
}

function buildTable($row, $varArray) {
	extract($varArray);
	
	$db = connect();
	
	$color = false;
	$tsn = $row['tsn'];
	$nameSource = $row['namesource'];
	if (empty($nameSource)) $nameSource = '&nbsp;';

	$taxonType = FindRank($row['rank_id'], $row['kingdom_id']);
	$vernacular = getVernacularNames($tsn);
	$name = $row['scientificname'];

	//find the determination author name and year
	$author_id = $row['taxon_author_id'];
	$author = TaxonAuthor($author_id);
	if (empty($author))	$author = '&nbsp';
	
	$usage = $row['usage'];
	$unaccept_reason = $row['unaccept_reason'];
	if (empty($unaccept_reason)) $unaccept_reason = '&nbsp;';

	if (($usage == 'not accepted') || ($usage == 'invalid')) {
		$color = true;
		$synonym_of = getSynonym($tsn);
		$unaccept_reason = "$unaccept_reason of $synonym_of";
	}

	if ($color) {
		echo '<tr class="synonym"><td>' . $name . '</td>';
	} else {
		echo '<tr>';
		$targetUrl = "index.php?&tsn=$tsn&searchonly=" . $searchonly . "&TSNtype=" . $TSNtype . 
					"&annotation=" . $annotation . "&view=" . $view . "&browse=" . $browse . 
					"&synonyms=" . $synonyms . "&pop=" . $pop;
		echo '<td><a href="'.$targetUrl
					. '" title="Click the name to display the level" class="taxonSearch">'
					. $name . '</a></td>';
	}
	echo "<td>$tsn</td><td>$author</td><td>$vernacular</td><td>$nameSource</td><td>$usage</td>"
		."<td>$unaccept_reason</td><td>$taxonType</td></span>";

	//if name and tsn needs to be selected from the table
	if ($searchonly != '1') {
		$tsnName = addslashes(getScientificName($tsn));
		if ($TSNtype == 2) {
			echo '<td title="Select Primary" align="center">'
			.'<a href="javascript: opener.updateprimaryTSN(' . $tsn . ', \'' . $tsnName
			. '\'); window.close();" class="button smallButton" title="Click to select this taxon">'
			.'<div>Select <span>&#8730</span></div></a></td>';
		} elseif ($TSNtype == 3) {
			echo '<td title="select Secondary" align="center">'
			.'<a href="javascript: opener.updatesecondaryTSN(' . $tsn . ',\''. $tsnName
			. '\'); window.close();" class="button smallButton" title="Click to select this taxon">'
			.'<div>Select <span>&#8730</span></div></a></td>';
		} elseif ($TSNtype == 4) {
			echo '<td title="select Alternative Privilege" align="center">'
			.'<a href="javascript: opener.updatealtprivTSN(' . $tsn . ',\'' . $tsnName
			. '\'); window.close();" class="button smallButton" title="Click to select this taxon">'
			.'<div>Select <span>&#8730</span></div></a></td>';
		} elseif ($TSNtype == 1) {
			echo '<td title="Select Privilege" align="center">
                   <a href="javascript: opener.updateprivilegeTSN(' . $tsn . ',\'' . $tsnName 
			. '\'); window.close();" class="button smallButton" title="Click to select this taxon">'
			.'<div>Select <span>&#8730</span></div></a></td>';
		} elseif ($TSNtype == 5) {
			echo '<td align="center"><a href="javascript: opener.updateSynonymTSN('
			. $tsn . ',\'' . $tsnName
			. '\'); window.close();" class="button smallButton" title="Click to select this taxon"><div>Select <span>&#8730</span></div></a>
                   </td>';
		} elseif ($TSNtype == 6) {
			echo '<td align="center"><a href="javascript: opener.updateParentTSN('
			. $tsn . ',\'' . $tsnName . '\'); window.close();" class="button smallButton" title="Click to select this taxon">'
			.'<div>Select <span>&#8730</span></div></a></td>';
		} else {
			echo '<td align="center"><a href="javascript: opener.updateTSN(' . $tsn . ',\'' . $tsnName
			. '\'); window.close();" class="button smallButton" title="Click to select this taxon">'
			.'<div>Select <span>&#8730</span></div></a></td>';
		}
	}
	if (isLoggedIn()){
		echo '<td align="center"><a href="javascript: openPopup(\'/Admin/TaxonSearch/annotateTSN.php?&tsn=' . $tsn 
			. '&view=' . $view . '&browse='	. $browse . '&synonyms=' . $synonyms . '&pop=yes\');" class="button smallButton">'
			. '<div>Annotate</div></a></td>';
		echo '<td align="center">';
		PrintAddTSN($tsn,"Add","button smallButton");
		echo "</td>";
	}
	echo "</tr>\n";
}

