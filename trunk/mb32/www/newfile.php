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

while (($data = fgetcsv($handle, 1000, ",")) !== false) {
	$num = count($data);
	$rows++;
	echo "$num fields in line $rows: <br /><br />";

	$parent = 0;
	$comments = '\''. $data[27]. '\'';
	$nameSource = '\''. $data[32]. '\'';
	if ($userId == '') $userId = $data[35];
	if ($groupId == '') $groupId = $data[34];
	$taxonAuthorId =  'NULL';
	$usage = '\''. $data[31]. '\'';
	if($usage != 'public') $dateToPublish = '\''. date('Y-m-d', (mktime(0, 0, 0, date("m") +6, date("d") - 1, date("Y")))). '\'';
	$keywords =  '';
	$name = $data[28];

	for ($c=0; $c < $num; $c++) {
		switch ($c){
			case 0: 	$rank = 10; break; //Kingdom
			case 1:		$rank = 20; break; //Subkingdom
			case 2: 	$rank = 30; break; //Phylum
			case 3: 	$rank = 40; break; //Subphylum
			case 4: 	$rank = 50; break; //Superclass
			case 5: 	$rank = 60; break; //Class
			case 6: 	$rank = 70; break; //Subclass
			case 7: 	$rank = 80; break; //Infraclass
			case 8: 	$rank = 90; break; //Superorder
			case 9: 	$rank = 100; break; // Order
			case 10: 	$rank = 110; break; //Suborder
			case 11: 	$rank = 120; break; //Infraorder
			case 12: 	$rank = 130; break; // Superfamily
			case 13: 	$rank = 140; break; //Family
			case 14: 	$rank = 150; break; //Subfamily
			case 15: 	$rank = 160; break; //Tribe
			case 16: 	$rank = 170; break; //Subtribe
			case 17: 	$rank = 180; break; //Genus
			case 18: 	$rank = 190; break; //Subgenus
			case 19: 	$rank = 200; break; //Section
			case 20: 	$rank = 210; break; //Subsection
			case 21: 	$rank = 220; break; //Species
			case 22: 	$rank = 230; break; //Subspecies
			case 23: 	$rank = 240; break; //Variety
			case 24: 	$rank = 250; break; //Subvariety
			case 25: 	$rank = 260; break; //Form
			case 26: 	$rank = 270; break; //Subform
		} //end of switch case

		if($c < 27 && $data[$c] != ''){   //Columns till Subform
			$AuthorSql = 'SELECT taxon_author_id FROM TaxonAuthors WHERE taxon_author = \'' .str_replace("'", "''", $data[29]). '\' AND kingdom_id = ' .$kingdomId. ';';
			$authorId = mysqli_fetch_array(mysqli_query($link, $AuthorSql));
			if ($c == 18) {
				$sql = 'SELECT tsn, parent_tsn, kingdom_id, rank_id, nameSource, comments, taxon_author_id, `usage` FROM Tree WHERE scientificName = \'' .$data[17]. ' ' .$data[$c] . '\' ORDER BY tsn DESC;';
			} elseif ($c == 21) {
				if ($data[18] != '') {
					$sql = 'SELECT tsn, parent_tsn, kingdom_id, rank_id, nameSource, comments, taxon_author_id, `usage`  FROM Tree WHERE scientificName = \'' .$data[17]. ' ' .$data[18] . ' ' .$data[$c]. '\' ORDER BY tsn DESC;';
				} else {
					$sql = 'SELECT tsn, parent_tsn, kingdom_id, rank_id, nameSource, comments, taxon_author_id, `usage`  FROM Tree WHERE scientificName = \'' .$data[28] . '\' AND taxon_author_id =\''.$authorId['taxon_author_id'].'\'  ORDER BY tsn DESC;';
				}
			} elseif ($c == 22) {
				if ($data[18] != '') {
					$sql = 'SELECT tsn, parent_tsn, kingdom_id, rank_id, nameSource, comments, taxon_author_id, `usage`  FROM Tree WHERE scientificName = \'' .$data[17]. ' ' .$data[18] . ' ' .$data[21]. ' ' .$data[$c]. '\' ORDER BY tsn DESC;';
				} else {
					$sql = 'SELECT tsn, parent_tsn, kingdom_id, rank_id, nameSource, comments, taxon_author_id,`usage`  FROM Tree WHERE scientificName = \'' .$data[17]. ' '  .$data[21]. ' ' .$data[$c] . '\'  AND taxon_author_id =\''.$authorId['taxon_author_id'].'\'  ORDER BY tsn DESC;';
				}
			} else {
				$sql = 'SELECT tsn, parent_tsn, kingdom_id, rank_id, nameSource, comments, taxon_author_id, `usage`  FROM Tree WHERE scientificName = \'' .$data[$c] . '\' ORDER BY tsn DESC;';
			}
			echo 'SQL: ' .$sql. "\n";

			if($res = mysqli_query($link, $sql)){
				$numRows = mysqli_num_rows($res);
				$row = mysqli_fetch_array($res);
				$tsn = $row['tsn'];
				//echo 'TSN: ' .$tsn. ' parent_tsn: ' .$parent. ' row[parent]: ' .$row['parent_tsn'] . 'Num of Rows: ' .$numRows. "\n";

				if($row['kingdom_id'] != '') $kingdomId = $row['kingdom_id'];
				//echo 'Kingdom Id: ' .$row['kingdom_id'] . '         ' .$kingdomId;

				if($parent != $row['parent_tsn'] || $row['usage'] == 'invalid' || $row['usage'] == 'not accepted') {
					if ($rank <= 180) {
						$scientificName = '' .$data[$c]. '';
						$letter = '\'' .substr($data[$c], 0, 1). '\'';
					} else {
						if ($data[$c] != '') {
							if ($rank == 190) {
								if($data[18] != '') $name = $data[17]. ' ('. $data[18]. ')';
								else $name = $data[17];
							}
							//does not include for subspecies
							if ($rank == 220) {
								if($data[18] != '') 	$name = $data[17]. ' ('. $data[18]. ') ' .$data[21];
								else	$name = trim($data[17]). ' ' .trim($data[21]);
							}

							if($rank == 230){
								if($data[18] != '') 	$name = $data[17]. ' ('. $data[18]. ') ' .$data[21]. ' ' .$data[22];
								else 	$name = $data[17]. ' ' .$data[21]. ' ' .$data[22];
							}

							if($data[29] != ''){
								$existAuthorSql = 'SELECT taxon_author_id FROM TaxonAuthors WHERE taxon_author = \'' .str_replace("'", "''", $data[29]). '\' AND kingdom_id = ' .$kingdomId. ';';
								$authorExists = mysqli_fetch_array(mysqli_query($link, $existAuthorSql));

								echo 'existAuthorSql: ' .$existAuthorSql. "\n";
							}

							$scientificName = ".$data[28]."; $letter = '\''. substr($data[28], 0, 1). '\'';
							$namecmp = str_replace(' ', '', $name); $scientificNamecmp = str_replace(' ', '', $data[28]);

							if($namecmp == $scientificNamecmp) {
								if(!$authorExists['taxon_author_id']) {
									$taxonAuthor = mysqli_fetch_array(mysqli_query($link, 'SELECT MAX(taxon_author_id) + 1 AS authorId FROM TaxonAuthors;'));
									$authorSql = 'INSERT INTO TaxonAuthors VALUES(' .$taxonAuthor['authorId']. ', \'' .trim($data[29]). '\', NOW(), ' .$kingdomId. ');';
									$author = mysqli_query($link, $authorSql);
									echo 'Author Sql: ' .$authorSql. "\n";
									if($author) {
										$taxonAuthorId = $taxonAuthor['authorId'];
									} else {
										$taxonAuthorId = $authorExists['taxon_author_id'];	
									}
								}
							}
						} // end of if($data[$c] != '')
						$scientificName = "$name";
					} // end of else part of if ($rank <=180)
				}else{
					$query = 'UPDATE Tree SET ';

					if($data[32] != '' && !eregi($data[32], $row['nameSource'])){
						if(is_null($row['nameSource']))
							$query .= ' nameSource =  \'' .$data[32]. '\'';
						else
							$query .= ' nameSource = concat(nameSource, \'  \',   \'' .$data[32]. '\')';
					}

					if($data[27] != '' && !eregi($data[27], $row['comments'])){
						if(strlen($query) > 16)
							$query .= ',  comments = concat(comments, \'   \',  \'' . str_replace("'","''", $data[27]). '\')';
						else
							$query .= ' comments = concat(comments, \'   \',  \'' . str_replace("'","''", $data[27]). '\')';
					}
					$query .= ' WHERE tsn = ' .$tsn;
				} //end of if($parent != $row['parent_tsn']

				//echo $row['scientificName'] .$row['usage'];

				if($query != '' && eregi('UPDATE', $query) > 0){
					if(strlen($query) > 38)
						$results = mysqli_query($link, $query) or die ($query . "\n " .mysqli_error($link));
					$parent = $row['tsn'];
				}else{
					/*
					 //Call Tree Insert procedure to insert new taxa
					 $params = array();
					 $params[] = $db->quote($nameSpace);
					 $params[] = $db->quote($usage);
					 $params[] = $objInfo->getUserId();
					 $params[] = $objInfo->getUserGroupId();
					 $params[] = $contributor;
					 $params[] = "NOW()";
					 $params[] = $db->quote($unnacept_reason);
					 $params[] = $db->quote($parent_tsn, 'integer');// for parent
					 $params[] = $db->quote($kingdom_id, 'integer');
					 $params[] = $db->quote($rank_id, 'integer');
					 $params[] = $db->quote($letter);
					 $params[] = $db->quote($scientificName);
					 $params[] = $db->quote($taxon_author_id, 'integer');
					 $params[] = $db->quote($referenceId, 'integer');
					 $params[] = $db->quote($pages);
					 $params[] = $db->quote($nameType);
					 $params[] = $db->quote($nameSource);
					 $params[] = $db->quote($comments);

					 $result = $db->executeStoredProc('TreeInsert', $params);
					 if(isMdb2Error($result, 'TreeInsert Stored Procedure', 5)) {
					 header("location: $indexUrl&tsn=$main_tsn&code=4");
					 exit;
					 }
					 $tsn = $result->fetchOne();
					 clear_multi_query($result);
					 */

					$query = 'CALL `TreeInsert`("", ' .$usage. ', ' .$userId. ', ' .$groupId. ', ' .$userId. ', NULL, ' .$dateToPublish. ', \'TaxonConcept\', NULL, NULL, NULL, NULL, "' .$scientificName. '", "' .$unit_ind1. '", "' .$unit_name1. '", "' .$unit_ind2. '", "' .$unit_name2. '", "' .$unit_ind3. '", "' .$unit_name3. '", "' .$unit_ind4. '", "' .$unit_name4. '", "", ' .$parent. ', ' .$kingdomId. ', ' .$rank. ', ' .$letter. ', "' .$scientificName. '", ' .$taxonAuthorId. ', NULL, "", "' .$data[30]. '", ' .$nameSource. ', ' .$comments. ', @tsn)';
					$results = mysqli_query($link, $query) or die ($query . "\n " .mysqli_error($link));
					if (mysqli_multi_query($link, "select @tsn")){
						if ($res = mysqli_store_result($link)){
							while ($row = mysqli_fetch_row($res)) $tsn = $row[0];
						}
					}
					$parent = $tsn;

				}

				echo 'Query: ' .$query ."\n Parent: " .$parent. 'row[tsn]: ' .$row['tsn']. '     tsn: ' .$tsn. "\n". 'Author Sql: ' .$authorSql. "\n";
				$query = ''; $name = '';
			}else //end of if ($res ....
			echo (mysqli_error($link));
		} //end of if($c <...
	} //end of for loop

} //end of while loop
