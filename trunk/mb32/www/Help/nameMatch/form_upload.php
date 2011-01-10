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

/*
 * File name: name_match_action.php
 * script that returns the wildcard matches from mb Tree based on scientific name
 * @author Katja Seltmann moon@begoniasociety.org
 *
 *
 *  \'%'.$querry.'%\'
 */


function echoNameMatch($names) {
	$db = connect();
	if (!empty($_REQUEST['kingdom_id'])){
		$where_extra = " kingdom_id = " . $_REQUEST['kingdom_id'] . " and ";
	}
	
	$matches = '<h3>Matched Terms</h3>&nbsp&nbsp<a href="download.php?action=matched">Download Matched Terms CSV</a>';
	$matches .= '<table width="100%" cellpadding="5" cellspacing="0" border="1">'
				. '<tr><th style="text-align:left">Term</th>'
				. '<th style="text-align:left">scientific name</th>'
				. '<th style="text-align:left">kingdom</th>'
				. '<th style="text-align:left">parent</th>'
				. '<th style="text-align:left">rank</th>'
				. '<th style="text-align:left">tsn</th>'
				. '<th style="text-align:left">taxon author</th>'
				. '<th style="text-align:left">name source</th>'
				. '<th style="text-align:left">accept</th>'
				. '<th style="text-align:left">image count</th></tr>';
	
	$no_matches = '<h3>Unmatched Terms</h3>&nbsp&nbsp<a href="download.php?action=unmatched">Download Unmatched Terms CSV</a>';
	$no_matches .= '<table width="30%" cellpadding="5" cellspacing="0" border="1">';
	
	unset($_SESSION['cvs_matched_terms']);
	unset($_SESSION['cvs_unmatched_terms']);
	
	$csv_matches[] = array(
		"term",
		"scientific name",
		"kingdom",
		"parent",
		"rank",
		"tsn",
		"taxon author",
		"name source",
		"accept",
		"image count",
		"parents"
	);
	
	$csv_no_matches[] = array(
		'term'
	);
	
	echo $matches;
	$data = array_filter(explode("\n", $names)); 
	foreach ($data as $term){
		$term = trim($term);
		if (empty($term)) continue;
		$sql= "SELECT tsn FROM Taxa  WHERE tsn is not null and $where_extra (match(scientificname) AGAINST (? in boolean mode)) order by kingdom_id, rank_id, scientificName";
		$results = $db->getAll($sql, null, array($term), null, MDB2_FETCHMODE_ASSOC);
		isMdb2Error($results, $query);
		$numRows = count($results);
		if ($numRows == 0) {
			$csv_no_matches[] = array($term);
			$no_matches .= '<tr><td>' . $term . '</td></tr>';
			continue;
		}
		echo '<tr><td rowspan="'.($numRows*2).'">'.htmlentities($term, ENT_QUOTES, "UTF-8").'</td>';
		$firstRow = true;
		$i=0;
		foreach ($results as $tsn) {
			$matches = '';
			$sql = 'SELECT k.kingdom_name, tu.rank_name, t.tsn, t.parent_tsn, t.scientificName, t.nameSource, t.unaccept_reason, t.imagesCount, ta.taxon_author '
					. ' FROM Tree t'
					. ' LEFT JOIN Kingdoms k ON t.kingdom_id = k.kingdom_id'
					. ' LEFT JOIN TaxonAuthors ta ON t.taxon_author_id = ta.taxon_author_id'
					. ' LEFT JOIN TaxonUnitTypes tu ON t.rank_id = tu.rank_id'
					. ' AND t.kingdom_id = tu.kingdom_id'
					. ' WHERE tsn='.$tsn['tsn'];
			$row = $db->getRow($sql, null, null, null, MDB2_FETCHMODE_ASSOC);
			isMdb2Error($row, $sql);
			$parentName = getScientificName($row['parent_tsn']);
			$taxonomicNames = getTaxonomicNames($tsn['tsn']);
			$csv_matches[] = array(
				$term, 
				$row['scientificname'],
				$row['kingdom_name'],
				$parentName,
				$row['rank_name'],
				$tsn['tsn'],
				$row['taxon_author'],
				$row['namesource'],
				$row['unaccept_reason'],
				$row['imagescount'],
				$taxonomicNames
			);
			if (!$firstRow) $matches .= '<tr>';
			$matches .= '<td align="left" rowspan="2">' . ' '.$row['scientificname'] . '</td>';
			$matches .= '<td align="left">' . $row['kingdom_name'] . '</td>';
			$matches .= '<td align="left">' . htmlentities($parentName, ENT_QUOTES, "UTF-8") . '</td>';
			$matches .= '<td align="left">' . $row['rank_name'] . '</td>';
			$matches .= '<td align="left">' . $tsn['tsn'] . '</td>';
			$matches .= '<td align="left">' . htmlentities($row['taxon_author'], ENT_QUOTES, "UTF-8") . '</td>';
			$matches .= '<td align="left">' . htmlentities($row['nameSource'], ENT_QUOTES, "UTF-8") . '</td>';
			$matches .= '<td align="left">' . htmlentities($row['unaccept_reason'], ENT_QUOTES, "UTF-8") . '</td>';
			$matches .= '<td align="left">' . $row['imagescount'] . '</td>';
			$matches .= '</tr>';
			$matches .= '<tr><td colspan="8"><b>Parents:</b> ' . htmlentities($taxonomicNames, ENT_QUOTES, "UTF-8");
			$matches .= '</td></tr>';
			echo $matches;
			$firstRow = false;
			$i++;
		}
	}
	echo '</table>';
	if (count($csv_no_matches) > 1) {
		echo '<br /><br />';
		echo $no_matches . '</table>';
		$_SESSION['cvs_unmatched_terms'] = $csv_no_matches;
	}
	$_SESSION['cvs_matched_terms'] = $csv_matches;
}
