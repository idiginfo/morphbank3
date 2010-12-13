<?php

// Note: To use this module you have to be already connected to the DB

$sciNameQuery = "SELECT scientificName FROM Tree WHERE tsn=?";
function getScientificName($tsn){
	global $sciNameQuery;
	$db=connect();
	$sciname = $db->getOne($sciNameQuery,null,array($tsn),null);
	isMdb2Error($sciname, $sciNameQuery." parent_tsn $tsn");
	return $sciname;
}

$rankIdQuery = "select rank_id from Tree where tsn=?";
function getRankId($tsn){
	global $rankIdQuery;
	$db=connect();
	$rank_id = $db->getOne($rankIdQuery,null,array($tsn),null);
	isMdb2Error($rank_id, $rankIdQuery." tsn: $tsn");
	return $rank_id;
}

/**
 * Returns tsn name and id
 * @param int $tsn
 * @return array $tsnName
 */
function getTsnName ($tsn) {
	if (!$tsn || intval($tsn)<1) return;
	$db = connect();
	$sql = "SELECT scientificName, tsn FROM Tree WHERE tsn = ?";
	$tsnRecord = $db->getRow($sql, null, array($tsn), null, MDB2_FETCHMODE_ASSOC);
	isMdb2Error($tsnRecord);
	$tsnName['name'] = $tsnRecord['scientificname'];
	$tsnName['tsn'] = $tsn;
	return $tsnName;
}

/**
 * Return parent tsn
 * @param $tsn
 */
function getParentTsn($tsn) {
	$db = connect();
	$sql = "select parent_tsn from Tree where tsn = ?";
	$parentTsn = $db->getOne($sql, null, array($tsn));
	isMdb2Error($name, "Error selecting parent tsn for taxon");
	return $parentTsn;
}

/**
 * Create taxon name for annotation
 * @param $scientificName
 * @param $rank_id
 * @param $parentName
 * @param $parentTsn
 * @param $prefix
 */
function annotateAddPrefix($taxonName, $rank_id, $parentName, $parentTsn, $prefix) {
	$childName = str_replace($parentName, "", $taxonName);
	if ($rank_id <= 180) { // genus or above
		$scientificName = "$prefix $taxonName";
	} elseif ($rank_id > 180 && $rank_id <= 220) { // up to species
		$scientificName = "$parentName $prefix $childName";
	} else { // below species
		// look for the rank 220 parent. if there isn't one, don't allow.
		$parentRank = getRankId($parentTsn);
		if ($parentRank < 220) { // no species
			return false;
		}
		$scientificName = "$parentName $prefix $childName";
	}
	return $scientificName;
}



function printTsnNameNoLinks($tsnName) {
	global $config;
	$name = $tsnName['name'];
	//echo $links;
	return $name;
}

/*
 ** Function printTsnNameLinks is in charge to create the html code (string) to show two links:
 **  - Taxonomic name (unit_name1 unit_name2 unit_name3 and unit name4) link pointing to Browse/ByName/ module
 **  - Hierarchy tree (icon) link pointing to Browse/ByTaxon/ module
 */
function printTsnNameLinks($tsnName) {
	global $config;

	$browseByTaxonHref= $config->domain . 'Browse/ByTaxon/';
	$browseByNameHref= $config->domain . 'Browse/ByImage/';

	if ($tsnName['name'] == "") return "";

	$hrefWithPop ='javascript:loadInOpener(\''.$browseByNameHref.'?tsn='.$tsnName['tsn'].'\')';
	$hrefWithoutPop = $browseByNameHref.'?tsn='.$tsnName['tsn'];

	$links =  '<a href="'.(isset($_GET['pop'])?$hrefWithPop:$hrefWithoutPop).'" title="Images of this Taxon">'.$tsnName['name'].'</a>';

	$hrefWithPop ='javascript:loadInOpener(\''.$browseByTaxonHref.'?tsn='.$tsnName['tsn'].'\')';
	$hrefWithoutPop = $browseByTaxonHref.'?tsn='.$tsnName['tsn'];

	$links .= ' <a href="'.(isset($_GET['pop'])?$hrefWithPop:$hrefWithoutPop).'"><img border="0" src="/style/webImages/hierarchryIcon.png"
				title="See hierarchy tree of ['.$tsnName['tsn'].']" alt="hierarchy" /></a><br />';

	//echo $links;
	return $links;
}

function getTaxonomicNamesFromBranch ($tsn, $separatorString=" ", $quote=true){
	$branchNames = getTaxonBranchNames($tsn);

	$separator = "";
	foreach ($branchNames as $name){
		$taxNames .= $separator . $name;
		$separator = $separatorString;
	}
	if ($quote){
		$db = connect();
		$taxNames = $db->quote($taxNames,'text',true);
	}
	return $taxNames;
}

function getTaxonBranchNames($tsn){
	$sql = "select scientificName from TaxonBranchNode where child = $tsn order by rankId ";
	$db = connect();
	$branchNames = array();
	$branch = $db->query($sql);
	isMdb2Error($branch,$sql);
	while ($node = $branch->fetchRow()) {
		$branchNames[] = $node[0];
	}

	return $branchNames;
}


function getTaxonomicNames($tsn){
	if (empty($tsn)) return null;
	$db = connect();
	$query = "SELECT taxonomicNames FROM Taxa t where tsn=$tsn";
	$taxnames = $db->queryOne($query);
	isMdb2Error($taxnames, $query);
	return $taxnames;
}

function getVernacularNames($tsn){
	$db = connect();
	$names = null;
	if (empty($tsn)) return null;
	$sql = "select vernacular_name from Vernacular where tsn=$tsn";
	$results = $db->query($sql);
	isMdb2Error($results,$sql);
	while ($name = $results->fetchOne()){
		$names .= $name . ' ';
	}
	return $names;
}

function getTsnByType($id, $objectType){
	$db = connect();
	$tsn = 0;
	if ($objectType=='View'){
		$sql = "select viewTsn from View where id=$id";
	} else if ($objectType=='Specimen') {
		$sql = "select tsnid from Specimen where id=$id";
	} else if ($objectType=='Image') {
		$sql = "select tsnid from Specimen s join Image i on s.id=i.specimenId where i.id=$id";
	} else if ($objectType=='TaxonConcept') {
		$sql = "select tsn from TaxonConcept where id=$id";
	} else if ($objectType=='Collection') {
	} else if ($objectType=='DeterminationAnnotation') {
		$sql = "select tsnid from DeterminationAnnotation where id=$id";
	} else if ($objectType=='Annotation') {
		// get the tsn of the annotated object
		$idSql = "select objectId,objectTypeId from Annotation where id=$id";
		list($objectId,$objectTypeId) = $db -> queryRow($idSql);
		if ($objectId!=null &&$objectId!=0){
			return getTsnByType($objectId,$objectTypeId);
		}
	}
	if (!empty($sql)){
		$tsn = $db -> queryOne($sql);
	}
	return $tsn;
}

function getTaxonomicNamesByType($id, $objectType){
	return getTaxonomicNames(getTsnByType($id, $objectType));
}

function getVernacularNamesByType($id, $objectType){
	return getVernacularNames(getTsnByType($id, $objectType));
}

function getTaxonBranchArray($tsn) {
	if (empty($tsn)) return null;
	$requestTsn = $tsn;
	$db = connect();
	$query = "SELECT tsn, rankid, scientificName, rank  FROM TaxonBranchNode t where child=$tsn order by rankid";
	$rows = $db->query($query);
	isMdb2Error($rows,$query);
	$taxonBranch = array();
	while (list($tsn, $rankId, $scientificName, $rank) = $rows->fetchRow()){
		$taxonBranch[]=array('tsn'=>$tsn, 'rank_id'=>$rankId, 'name'=>$scientificName, 'rank'=>$rank);
	}
	if (count($taxonBranch)>0) return $taxonBranch;
	return getTaxonBranchFromParent($requestTsn);

}

function getTaxonBranchFromParent($tsn){
	$db = connect();
	$taxonBranch = array();

	$sql = "select t.parent_tsn, t.rank_id, t.scientificName, n.rank_name from Tree t join TaxonUnitTypes n"
	." on t.rank_id=n.rank_id and t.kingdom_id=n.kingdom_id where t.tsn=?";
	$stmt = $db->prepare($sql);
	isMDB2Error($stmt);
	while ($tsn != 0){
		$result = $stmt->execute(array($tsn));
		isMDB2Error($result);
		list($parentTsn,$rankId,$scientificName,$rank) = $result->fetchRow();
		$taxonBranch[]=array('tsn'=>$tsn, 'rank_id'=>$rankId, 'name'=>$scientificName,
			'rank'=>$rank);
		$tsn = $parentTsn;
	}
	return $taxonBranch;
}


// Function to get the tsn name of any rank_id for a
// derminated tsn. Return NULL when it is not defined.
function getTsnNameByRankId ( $tsn, $rank_id) {
	$query = "SELECT scientificName FROM TaxonBranchNode t where child=$tsn  and rank_id=$rank_id";
	$name = $db->getOne($query);
	isMdb2Error($name);
	return $name;
}

/**
 * Return the rank name given rank id
 * @param $rankId
 */
function getRankName($rankId) {
	$db = connect();
	$sql = "Select rank_name from TaxonUnitTypes where rank_id = ?";
	$rankName = $db->getOne($sql, null, array($rankId));
	isMdb2Error($rankName, $sql);
	return $rankName;
}

/*
 ** Update imagesCount field from Tree table for a certain tsn.
 ** The new value of imagesCount=imagesCount+numImgesToSum.
 ** The function is execute this operation for the current tsn and
 ** all its parents.
 **
 ** Important: In order to execute this function you have to have Update privileges
 **
 ** If you want to update imagesCount for the entire Tree table, please check module countImagesPerTaxon.php
 ** at /data/adminProcesses
 */
function updateImagesCountOnTree ($tsn=NULL, $numImagesToSum=0) {
	$db = connect();
	$parentsPath = getTaxonBranchArray($tsn);
	if ($parentsPath == null) return false;
	$numParents = count($parentsPath);
	for ( $i = 0; $i < $numParents; $i++) {
		$sql = 'UPDATE Tree SET imagesCount = imagesCount+('.$numImagesToSum.') WHERE tsn='.$parentsPath[$i]['tsn'];
		$result =$db->exec( $sql);
		isMdb2Error($tsn,$sql);
	}
	return true;
}

function checkAnnotationType($id) {
	global $link;
	$sql = 'SELECT objectId, typeAnnotation FROM Annotation WHERE id='.$id;
	$result = mysqli_query($link, $sql);
	if ($result) {
		$array = mysqli_fetch_array($result);
		return $array;
	}
	return FALSE;
}

function getTaxonConceptTSN($id) {
	$db = connect();
	$sql = 'SELECT tsn FROM TaxonConcept WHERE id='.$id;
	$tsn = $db->getOne($sql);
	if($message =	isMdb2Error($tsn,$sql,false)) return false;
	return $tsn;
}

function getBranchAsLinks($tsn, $varArray) {
	extract($varArray);
	if (empty($tsn)) return null;
	$branchList = "";
	$parents_array = getTaxonBranchArray($tsn);

	foreach ($parents_array as $parent){
		$branchList .= !empty($branchList) ? ' >> ' : '';
		$branchList .= '<a href="index.php?tsn=' . $parent['tsn'] . '&pop='. $pop . '&searchonly=' . $searchonly;
		$branchList .= '&view=' . $view . '&browse=' . $browse . '&returnURL=' . $returnURL; 
		$branchList .= '&synonyms=' . $synonyms . '&TSNtype=' . $TSNtype . '&annotation=' . $annotation . '">';
		$branchList .= $parent['name']. '</a> (' . $parent['tsn'] . ')';
	}
	return $branchList;
}
/**
 * Insert $child into tree below $parent
 * update lft and rgt of Tree to accomodate $child
 *
 * @param $child tsn of child node
 * @param $parent tsn of parent node
 */
function insertIntoTree($child, $parent) {

	$db = connect();
	$sql = "SELECT rgt FROM Tree WHERE tsn=$parent";
	$newLeft = $db->getOne($sql);
	isMdb2Error($newLeft, $sql);
	$newRight = $newLeft + 1;

	// update rgt on the branch of the new node
	$updateRight = "UPDATE Tree SET rgt=rgt+2 WHERE rgt>=$newLeft and lft<$newLeft";
	$rgtCount = $db->exec($updateRight);
	isMdb2Error($rgtCount, $updateRight);

	// update lft and rgt to the right of the branch
	$updateLeft = "UPDATE Tree SET lft=lft+2, rgt=rgt+2 WHERE rgt>=$newLeft and lft>=$newLeft";
	$lftCount = $db->exec($updateLeft);
	isMdb2Error($lftCount, $updateLeft);

	$updateLR = "UPDATE Tree SET lft=$newLeft, rgt=$newRight where tsn=$child";
	$lrCount = $db->exec($updateLR);
	isMdb2Error($lrCount, $updateLR);

	return "$lftCount,$rgtCount,$lrCount";
}

function deleteFromTree($tsn){
	//TODO build deleteFromTree!
	// decrement lefts and rights by 2 as appropriate
	// set parent_tsn to null for node $tsn
}

/**
 * Code below imported from /Admin/TaxonSearch/editFunctions.php
 * editFunctions.php deleted
 */
function showTaxonForm($array, $tsn, $parent_tsn, $rank_id, $parentRank, $baseObjectId = null){
	global $objInfo;
	$db = connect();
	
	$main_tsn = (empty($tsn)) ? $parent_tsn : $tsn;
	$parentName = getScientificName($parent_tsn);
	$scientificName = $array['scientificname'];
	$kingdom_id = $array['kingdom_id'];
	
	if (empty($tsn)) { // Insert
		$frmId = "frmAddTaxon";
		$frmAction = "commitTSN.php";
		$frmButton = "Submit";
		$reference = $array['reference'];
		$reference_id = $array['reference_id'];
		$contributor = $array['Contributor'];
		$taxon_author = $array['taxon_author'];
	} else { // update
		$childName = $scientificName;
		$frmId = "frmEditTaxon";
		$frmAction = "modifyTSN.php";
		$frmButton = "Update";
		$isUpdate = true;
		$reference = !empty($array['title']) ? $array['title'] : $array['publicationtitle'];
        if (strlen($reference) > 55) $reference = substr($reference, 0, 55) . '...';
        $reference_id = $array['publicationid'];
        $contributor = $array['bouserid'];
        $groupId = $array['bogroupid'];
        $taxon_author = $array['taxon_author_name'];
        $ref = "/Admin/TaxonSearch/editTSN.php?id=$tsn";
	}
	
	list($newName) = getSimpleName($parentName, $childName);
	if (empty($newName)) $newName = $childName;
	$bigTitle = 'Enter Taxon Author(s), year in the specified format:<br />' . '- Only last name of the authors separated by comma<br />' . '- Use first name initial only if confusion possible<br />' . '- Last author in the list separated by "and"<br />' . '- List the year on the end separated by comma from the names<br />' . '- Follow nomenclature standards if the name is combination<br />' . '  or the name was moved from one parent to another<br />' . '- Use parenthesis when necessary<br />';
	$postItContent = htmlentities($bigTitle);
	
	include_once 'taxonForm.php';
}

/**
 * Retrieve parent rank id for editing taxon
 * @param int $tsn
 */
function getParentRank($tsn) {
	$db = connect();
	$sql = "select rank_id from Tree where tsn = ?";
	$rank_id = $db->getOne($sql, null, array($tsn));
	isMdb2Error($count, 'Select parent rank id: ' . $sql);
	return $rank_id;
}

/**
 * Determine simple name and indicator after removing the parent name
 * @param $parentName
 * @param $childName
 * @param $rankId
 * @return array(simplename, indicator);
 */
function getSimpleName($parentName, $childName){
	$prefix = strpos($childName.' ', $parentName);
	if($prefix===false || $prefix > 0){ // parent is not a prefix
		return array(null);
	}
	$diff = trim(substr($childName, strlen($parentName)));
	return array($diff);
}

/**
 * Update taxa name when changing
 * @param $tsn
 * @param $sciName
 * @param $parentName
 */
function setScientificName($tsn, $sciName, $parentName){
	$db = connect();
	
	// Update Tree table
	$data = array($sciName, $tsn);
	$sql = "update Tree set scientificName = ? where tsn = ?";
	$stmt = $db->prepare($sql);
	$affRows = $stmt->execute($data);
	isMdb2Error($affRows, "Update Tree scientifc name");
	
	// Update Taxa table
	$data = array('scientificName' => $sciName, 'taxonomicNames' => $sciName, 'parent_name' => $parentName);
	$sql = "update Taxa set scientificName = ?, taxonomicNames = ?, parent_name = ? where tsn = ?";
	$stmt = $db->prepare($sql);
	$affRows = $stmt->execute($data);
	isMdb2Error($affRows, "Update Taxa scientific name, taxanomic names, parent name");
	
	return $affRows;
}

/**
 * Create new scientific name
 * @param $newName
 * @param $parentName
 * @param $rank
 * @param $parentTSN
 */
function createNewScientificName($newName, $parentName, $rank_id, $parentTSN){
	if ($rank_id <= 180) { // rank is genus or above
		$scientificName = trim($newName);
	} elseif ($rank_id > 180 && $rank_id <= 220) { // rank is subgenus to species
		$scientificName = trim($parentName);
		if ($rank_id == 190) {// user parenthesis or not
			$preg = '#\([^\)]+\)#';
			if (preg_match($preg, $newName)) {
				$scientificName .= " " . trim($newName);
			} else {
				$scientificName .= " (" . trim($newName) . ")";
			}
		} else {
			$scientificName .= " " . trim($newName);
		}
	} else { // rank is below genus and above species
		// If parent rank is above species, do not allow unless it is Genus
		$parentRank = getRankId($parentTSN);
		if ($parentRank < 220 && $parentRank != 180) {
			return false;
		}
		$scientificName = trim($parentName) . " " . trim($newName);
	}
	return $scientificName;
}

/**
 * Function to check if TSN has children
 * @param $tsn
 * @return bool
 */
function HaveChildren($tsn) {
	$db = connect();
	$sql = "SELECT count(*) from Tree WHERE parent_tsn = ?";;
	$count = $db->getOne($sql, null, array($tsn));
	if (isMdb2Error($count, "Check TSN children", 2)) {
		return false;
	}
	return $count == 0 ? false : true;
}

/**
 * Recursive function to check taxon children for edit permissions
 * @param $tsn
 * @param $userId
 * @param $groupId
 */
function CheckChildren($tsn, $userId, $groupId) {
	$db = connect();
	$query = "select t.tsn, b.id as boid from Tree t "
			. "left join TaxonConcept tc on tc.tsn = t.tsn "
			. "left join BaseObject b on b.id = tc.id "
			. "where t.parent_tsn = ?";
	$results = $db->getAll($query, null, array($tsn), null, MDB2_FETCHMODE_ASSOC);
	isMdb2Error($results, "Database error checking taxon children", 2);
	if ($results) {
		foreach ($results as $row) {
			if (!checkAuthorization($row['boid'], $userId, $groupId, 'edit')) {
				return false;
			}
			CheckChildren($row['tsn'], $userId, $groupId);
		}
	}
	return true;
}

/**
 * update children scientificName
 * @param unknown_type $tsn
 * @param unknown_type $newName
 * @param unknown_type $oldName
 */
function updateChildrenName ($tsn, $newName, $oldName) {
	$db = connect();
	$query = "select * from Tree where parent_tsn= " . $tsn;
	$results = $db->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
	isMdb2Error($results, "Update taxon children select");

	if (empty($results)) return true;
	foreach ($results as $row) {
		$childTsn    = $row['tsn'];
		$oldChildName = $row['scientificname'];
		$newChildName = str_replace($oldName, $newName, $oldChildName);
		//echo "$oldName, $newName, $oldChildName<br />";
		//echo $newChildName;
		//exit;
		setScientificName($childTsn, $newChildName, $newName);
		updateChildrenName($childTsn, $newChildName, $oldChildName);
	}
	return true;
}

//function that prints the top line with all the parent names
function printTaxonNames($tsn) {
	if (empty($tsn)) return null;
	$db = connect();
	$query = "SELECT * FROM Tree WHERE tsn=" . $tsn;
	$row = $db->getRow($query,null,null,null,MDB2_FETCHMODE_ASSOC);
	isMdb2Error($row,$query);
	$TaxonName = $row['scientificname'];
	$Currentrank_id = $row['rank_id'];
	$Parenttsn = $row['parent_tsn'];
	$kingdom_id = $row['kingdom_id'];
	$query = "SELECT rank_name FROM TaxonUnitTypes WHERE rank_id=$Currentrank_id AND kingdom_id=$kingdom_id";
	$rank = $db->getOne($query);
	isMdb2Error($rank,"printtaxonnames: $query");
	$taxonomicNames = getTaxonomicNamesFromBranch ($tsn, " / ", false);
	$taxonomicNames .= " [ $rank ]";


	$tag = "<b><span style='color:#17256B'>As a child of: ---Life / $taxonomicNames</span></b>";
	echo $tag . "<br/><hr/>";
}

//This function checks for the Taxon_author_name
//If the name exists returns the id if not creates the new
//entry in TaxonAuthors table and returns the id

function FindAuthor($taxon_author, $kingdom_id) {
	$db = connect();
	if (empty($taxon_author) || empty($kingdom_id)){
		return null;
	}
	$query = "SELECT taxon_author_id FROM TaxonAuthors WHERE taxon_author = "
	. $db->quote($taxon_author) . " AND kingdom_id=$kingdom_id";
	$authorId = $db->queryOne($query);
	isMdb2Error($row,$query);

	if (!$authorId) {// author not in database for this kingdom
		// get new id and insert author
		$query2 = "SELECT MAX(taxon_author_id) AS id FROM TaxonAuthors";
		$maxAuthorId = $db->queryOne($query2);
		isMdb2Error($maxAuthorId,$query);
		$authorId = (int)$maxAuthorId + 1;
		$query_update = "INSERT INTO TaxonAuthors (taxon_author_id, taxon_author, update_date, kingdom_id)"
		." VALUES ($authorId,".$db->quote($taxon_author).",NOW(),$kingdom_id)";
		$rowCount = $db->exec($query_update);
		isMdb2Error($rowCount,$query);
	}
	return $authorId;
}

//This function checks for the Taxon_author_name
//end returns his name
function FindAuthorName($taxon_author_id) {
	if (empty($taxon_author_id)) return null;
	$db = connect();
	$query = "SELECT taxon_author from TaxonAuthors where taxon_author_id=$taxon_author_id";
	$taxon_author = $db->getOne($query);
	isMdb2Error($maxAuthorId,$query);
	return $taxon_author;
}


//This function returns the Publication name
function FindRefName($ref_id) {
	$db = connect();
	$query = "SELECT author,year,title FROM Publication WHERE id=" . $ref_id;
	$row = $db->getRow($query, null,null,null,MDB2_FETCHMODE_ASSOC);
	isMdb2Error($maxAuthorId,$query);
	$name = $row['author'] . "." . $row['year'] . "." . $row['title'];
	return $name;
}

//This function returns the Vernacular name and language if one exists
function getTSNVernacular($tsn)
{
	if (empty($tsn)) return;
	$db = connect();
	$query = "select * from Vernacular where tsn = ? order by vern_id";
	$results = $db->getAll($query, null, array($tsn), null, MDB2_FETCHMODE_ASSOC);
	return $results;
}


function displayVernacular($tsnId = null) {
	
	$vernacular = getTSNVernacular($tsnId);
	$vernNum = count($vernacular);
	
	if ($vernNum > 0) {
		$html = '
			<br /><br />
			<a href="" class="toggleTable" style="display:none"><img src="/style/webImages/plusIcon.png" alt="" title="And a new link" /> (Add External Links)</a>
			<table id="vernacularTbl" class="edit" title="'.($vernNum+2).'" cellpadding="3" cellspacing="0" border="0" width="100%" style="display:inline">
				<tr>
					<td width="25%">&nbsp;</td>
					<td>
						<a href="" class="addRow" name="add" >
						<img src="/style/webImages/plusAdd.gif" alt="Add Row"  title="Click to Add a Row" /></a>
						&nbsp;&nbsp;
						<a href="" class="removeRow" name="remove">
						<img src="/style/webImages/minusRemove.gif" alt="Remove Row"  title="Click to Remove a Row" /></a>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><b>Vernacular:</b></td>
					<td><b>Name:</b></td>
					<td><b>Language</b></td>
				</tr>';
				$i=1;
				foreach ($vernacular as $vern) { // vernacular_name, language, vern_id
					$html .= '<tr>';
					$html .= '<td><input type="hidden" name="vernId[]" value="' . $vern['vern_id'] . '"></td>';
					$html .= '<td><input type="text" name="vernacularName[]" id="name_' . $i . '" value="' . $vern['vernacular_name'] . '" /></td>';
					$html .= '<td><input type="text" class="autocomplete vernacular" name="language[]" id="langauge_' . $i . '" value="' . $vern['language'] . '" /></td>';
					$html .= '</tr>';
				}
			$html .= '</table><br />';
	} else {
		$html = '
		<br /><br />
		<table id="vernacularTbl" class="edit" title="3" cellpadding="0" cellspacing="3" border="0" width="100%">
			<tr> 
				<td width="25%">&nbsp;</td>
				<td>
					<a href="" class="addRow" name="add" >
					<img src="/style/webImages/plusAdd.gif" alt="Add Row"  title="Click to Add a Row" /></a>
					&nbsp;&nbsp;
					<a href="" class="removeRow" name="remove">
					<img src="/style/webImages/minusRemove.gif" alt="Remove Row"  title="Click to Remove a Row" /></a>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><b>Vernacular:</b></td>
				<td><b>Name:</b></td>
				<td><b>Language:</b></label></td>
			</tr>
			<tr>
				<td>&nbsp;</tad>
				<td><input type="text" name="vernacularNameAdd[]" id="vernacular_name_1" /></td>
				<td><input type="text" class="autocomplete vernacular" name="languageAdd[]" id="language_1" /></td>
			</tr>
		</table>
		<br />';
	}
	return $html;
}

/**
 * Insert vernacular
 * @param integer $tsn
 * @param array $array post array
 * @return bool
 */
function insertVernacular($tsn, $array) {
	if (empty($array['vernacularNameAdd'][0])) return true;
	$count = count($array['vernacularNameAdd']);
	$db = connect();
	
	$sql = "select max(vern_id) as vern_id from Vernacular;";
	$vern_id = $db->queryOne($sql);
	if ($message = isMdb2Error($vern_id, "select max vern_id", 5)) {
		return false;
	}
	
	$sql = "insert into Vernacular (tsn, vernacular_name, language, approved_ind, update_date, vern_id) VALUES ($tsn, ?, ?, 'N', NOW(), ?)";
	$types = array('text', 'text', 'integer');
	$sth = $db->prepare($sql, $types);
	if(isMdb2Error($result, 'Vernacular insert')) {
		return false;
	}
	for ($i=0; $i< $count; $i++) {
		$vernacular_name = trim($array['vernacularNameAdd'][$i]);
		$language = trim($array['languageAdd'][$i]);
		$vern_id++;
		if (empty($array['languageAdd'][$i]) || empty($array['vernacularNameAdd'][$i])) return false;
		$result = $sth->execute(array($vernacular_name, $language, $vern_id));
		if(isMdb2Error($result, 'Vernacular insert')) {
			return false;
		}
	}
	return true;
}

/**
 * Update existing vernacular
 * @param integer $tsn
 * @param array $array post array
 * @return bool
 */
function updateVernacular($tsn, $array){
	if (empty($array['vernId'][0])) return true;
	$count = count($array['vernId']);
	$db = connect();
	
	$sql = "update Vernacular set vernacular_name = ?, language = ?, update_date = NOW() where tsn = $tsn and vern_id = ?";
	$types = array('text', 'text', 'integer');
	$sth = $db->prepare($sql, $types);
	if(isMdb2Error($sth, 'Prepare vernacular update')) {
		return false;
	}
	
	for ($i = 0; $i < $count; $i++) {
		$vern_id = trim($array['vernId'][$i]);
		$vernacular_name = trim($array['vernacularName'][$i]);
		$language = trim($array['language'][$i]);
		if (empty($array['language'][$i]) || empty($array['vernacularName'][$i])) return false;
		$result = $sth->execute(array($vernacular_name, $language, $vern_id));
		if(isMdb2Error($result, 'Vernacular update')) {
			return false;
		}
	}
	return true;
}

//This function returns the Vernacular name and language if one exists
function FindVernacular($tsn)
{
	$db = connect();
	$Vernacular[0] = "";
	$Vernacular[1] == "";
	$Vernacular[2];

	$query = "SELECT vernacular_name, language, vern_id FROM Vernacular WHERE tsn="
	. $tsn . " order by vern_id";
	//echo $query;
	$result = $db->query($query);
	if ($result) {
		while ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC)){
			$Vernacular[0][$index] = $row['vernacular_name'];
			$Vernacular[1][$index] = $row['language'];
			$Vernacular[2][$index] = $row['vern_id'];
			//echo "vern name is ".$Vernacular[0][$index];
			//echo "vern language is ".$Vernacular[1][$index];
		}
	} else {

		$query = "SELECT max(vern_id)+1 as id from Vernacular";
		$id = $db->getOne($query);
		$Vernacular[2][0] = $id;
		$Vernacular[1][0] = "";
		$Vernacular[0][0] = "";
	}
	//echo "vernacular size is ".count($Vernacular);
	return $Vernacular;
}

//function that selects the vernacular languages from the data base

function VernLanguages()
{
	$db = connect();
	$query = "SELECT DISTINCT language FROM Vernacular";
	$result = $db->query($query);
	if (!$result) return "";

	while ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
		$vern = $row['language'];
		$vernacular[$index] = $vern;
	}
	usort($vernacular, "strnatcmp");
	$vern = "";
	for ($i = 0; $i < sizeof($vernacular); $i++) {
		$vern .= $vernacular[$i] . ",";
	}
	$vern = substr($vern, strpos($vern, ',') + 1);
	$vern = substr($vern, 0, strrpos($vern, ','));
	return $vern;
}

/**
 * Checks if the tsn is already userd in the system
 * @param $tsn
 * @return bool
 */
function CheckTsn($tsn) {
	$db = connect();
	$query = "select count(*) as count from Specimen where tsnId = $tsn";
	$count = $db->getOne($query);
	if ($count > 0) {
		return false;
	}

	$query = "select count(*) as count from View where viewTSN = $tsn";
	$count = $db->getOne($query);
	if ($count > 0) return false;

	$query = "select count(*) as count from DeterminationAnnotation where tsnId = $tsn";
	$count = $db->getOne($query);
	if ($count > 0) return false;

	$query = "select count(*) as count from Annotation a left join TaxonConcept tc on tc.id = a.objectId where tc.tsn = $tsn";
	$count = $db->getOne($query);
	if ($count > 0) return false;

	return true;
} //end of function CheckTSN

function printVar($var){
	echo '<pre>'; var_dump($var); echo '</pre>';
}

// **************************************************************************************
// * This function accepts as input a TSN number. It finds the rank and kingdom ids	*
// * associated with that taxon and discovers what type of taxon it is. It then precedes*
// * to determine the following taxon in that all have the same required parent. It	*
// * builds an arrray of those taxa.																	*
// **************************************************************************************
function printRankSelector($tsn, $rankId = null) {
	$db = connect();
	$query = 'select * from Tree where tsn =' . trim($tsn);
	$row = $db->getRow($query,null,null,null, MDB2_FETCHMODE_ASSOC);
	isMdb2Error($row,$query);
	$rank_id = $row['rank_id'];
	$kingdom_id = $row['kingdom_id'];
	$selectedRank = $rankId;
	// ****************************************************************************************
	// * Get the next records in the Tree that have the rank_id one higher and order*
	// * them by the Rank_id.	We must be careful here. If a person is adding a major		*
	// * Taxonomic category such Phylum, Class, Order, Family, Genus, or Species then they	*
	// * can only add that category. Only categories can be added that have the same			*
	// * Taxonomic required parent.																			*
	// ****************************************************************************************
	if ($rank_id == 30 || $rank_id == 60 || $rank_id == 100 || $rank_id == 140
		|| $rank_id == 180 || $rank_id == 190 || $rank_id == 220) {
		$logical_Operator = ">";
	} else {
		$logical_Operator = ">=";
	}

	$query = "select * from TaxonUnitTypes where rank_id $logical_Operator $rank_id and kingdom_id =$kingdom_id order by rank_id";
	$results1 = $db->query($query);
	isMdb2Error($results1,$query);
	$numrows = $results1->numRows();
	//echo "rank $rank_id kingdom $kingdom_id\n<br/>";
	//echo "numRows: $numrows<br/>";
	$row1 = $results1->fetchRow(MDB2_FETCHMODE_ASSOC);
	//printVar($row1);
	//echo "rank ".$row1['rank_id']." kingdom". $row1['kingdom_id']."\n<br/>";
	
	/******************************************************************************************
		* Depending upon the number of Taxonoomic Unit types returned, we must treat the case	*
		* where only one row is returned versus if more then one row is returned.					*
		* This is because of the way the Taxonomic Unit Type table data is structured.			*
		******************************************************************************************/
	$returnTaxonTypes[0]['rank_id'] = $row1['rank_id'];
	$returnTaxonTypes[0]['kingdom_id'] = $row1['kingdom_id'];
	$returnTaxonTypes[0]['rank_name'] = $row1['rank_name'];
	$returnTaxonTypes[0]['req_parent_rank_id'] = $row1['req_parent_rank_id'];
	if ($numrows > 1) {
		$row1 = $results1->fetchRow( MDB2_FETCHMODE_ASSOC);
		$returnTaxonTypes[1]['rank_id'] = $row1['rank_id'];
		$returnTaxonTypes[1]['kingdom_id'] = $row1['kingdom_id'];
		$returnTaxonTypes[1]['rank_name'] = $row1['rank_name'];
		$returnTaxonTypes[1]['req_parent_rank_id'] = $row1['req_parent_rank_id'];
		$req_parent = $row1['req_parent_rank_id'];
	}
	$counter = 2;
	if ($numrows > 2) {
		$row1 = $results1->fetchRow(MDB2_FETCHMODE_ASSOC);
		while ($numrows > $counter && $req_parent == $row1['req_parent_rank_id']) {
			$returnTaxonTypes[$counter]['rank_id'] = $row1['rank_id'];
			$returnTaxonTypes[$counter]['kingdom_id'] = $row1['kingdom_id'];
			$returnTaxonTypes[$counter]['rank_name'] = $row1['rank_name'];
			$returnTaxonTypes[$counter]['req_parent_rank_id'] = $row1['req_parent_rank_id'];
			$counter++;
			$row1 = $results1->fetchRow(MDB2_FETCHMODE_ASSOC);
		}
	}
	echo '<tr><td><b>Rank Identification: <span style="color:red">*</span></b></td><td><select name="rank_id" id="rank_id" size =".$SIZE." >';
	$counter = $counter - 1;
	if (is_null($selectedRank)) {
		echo '<option value="" selected="selected">--Select--</option>';
	}
	for ($index = 0; $index <= $counter; $index++) {
		$rank = $returnTaxonTypes[$index]['rank_id'];
		$selected = $rank == $selectedRank ? 'selected="selected"' : '';
		echo '<option '.$selected.' value="'.$rank.'">';
		echo $returnTaxonTypes[$index]['rank_name'];
		echo "</option>\n";
	}
	echo '</select></td></tr>';
}

//function that prints navigation
function printNavTSN($rowNum, $numRows, $top)
{
	global  $config;

	$rownumber = $rowNum + 1;
	if ($top == 1) {
		echo '<form name="navigation" method="post" action="editTSN.php"><table width="auto">';

	} else {
		echo '<br/><table width="80%">';
	}
	echo '<tr><td width="15%px">&nbsp;</td>';
	if ($top == 1) {
		echo '<td width="10%px">';
	} else {
		echo '<td width="15%px">';
	}
	echo '<a href="'.$config->domain.'Admin/TaxonSearch/editTSN.php?row=0" title=\"First Record\">'
	.'<img src="/style/webImages/goFirst.png" border="0" alt="goFirst" /></a></td>';
	if ($rowNum > 0) {
		if ($top == 1) {
			echo '<td width="10%px">';
		} else {
			echo '<td width="15%px">';
		}
		echo '<a href="'.$config->domain.'Admin/TaxonSearch/editTSN.php?row='.$rowNum-1
		.'" title="Previous Record"> <img src="/style/webImages/backward-gnome.png" border="0"
				alt="Previous" /></a></td>';
	}
	if ($top == 1) {
		echo '<td width="10%px">';
	} else {
		echo '<td width="20%px">';
	}
	echo $rownumber . '</td>';

	if ($rowNum < $numRows - 1) {
		if ($top == 1) {
			echo '<td width="10%px">';
		} else {
			echo '<td width="15%px">';
		}
		echo '<a href="'.$config->domain.'Admin/TaxonSearch/editTSN.php?row='
		.$rowNum + 1 .'" title="Click for Next Record">'
		.'<img src="/style/webImages/forward-gnome.png" border="0" alt="Next" /></a></td>';

	}
	echo '<td width="10%px"><a href="'.$config->domain.'Admin/TaxonSearch/editTSN.php?last=1&'
	.'title="Last Record"> <img src="/style/webImages/goLast.png" border="0"
		alt="goLast" /></a></td>';
	echo '<td width="10%px">of<'.$numRows.'</td>';

	if ($top == 1) {
		echo '<td width="10%px"><b>Taxon #: </b></td>'
		.'<td width="10%px"><input type="text" size="6" maxlength="10"	name="taxon"'
		.'title="Enter the taxon number of the record you want to edit" /></td>'
		.'<td width="10%px"><a href="javascript: document.navigation.submit();"'
		.' title="Serach tsn" class="button smallButton"><div>Go</div></a></td>';
	}
	echo '</tr></table>';

}//end of function printNav

//function to get records for user
function getTaxonsForUser($userId) {
	$db = connect();
	global $objInfo, $config;
	$query = "SELECT t.*, tc.status, b.id, b.userId FROM Tree t, TaxonConcept tc, BaseObject b WHERE ";
	$query .= "(b.userId=$userId OR b.submittedBy=$userId) AND b.id=tc.id AND ";
	$query .= " tc.tsn=t.tsn AND t.tsn>=999000000 AND ";
	$query .= "((t.nameType='Regular scientific name' AND (tc.status='public' OR tc.status='not public'))";
	$query .= " OR (tc.status!='public' AND t.nameType='Manuscript name'))";
	//echo $query;
	$result = $db->query($query);
	isMdb2Error($result,$query);
	$numRows = $result->numRows();
	if ($numRows < 1) {
		echo '<h1>No Taxon Names for User: ' . $objInfo->getName()
		. '</h1><br/><br/><a href = "' . $config->domain
		. 'MyManager/?tab=taxaTab" class="button smallButton right" title="Click to return to the previous page" ><div>Return</div></a>';
		echo '</td></tr></table>';
	} else
	return $result;
}

//function to determine the row for provided tsn
function FindRowForTaxon($taxon, $userId) {
	$result = getTaxonsForUser($userId);
	if ($result) {
		while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC)){
			if (trim($taxon) == $row['tsn']) return $i;
		}
	}
	return - 1;
}

/**
 * Get Rank by column
 * Used during taxon upload using Excel
 * @param $value
 */
function getRankByColumn ($col) {
	switch ($col) {
		case 1: 	$rank = 10;  break; //Kingdom
		case 2:		$rank = 20;  break; //Subkingdom   
		case 3: 	$rank = 30;  break; //Phylum
		case 4: 	$rank = 40;  break; //Subphylum
		case 5: 	$rank = 50;  break; //Superclass
		case 6: 	$rank = 60;  break; //Class
		case 7: 	$rank = 70;  break; //Subclass
		case 8: 	$rank = 80;  break; //Infraclass
		case 9: 	$rank = 90;  break; //Superorder
		case 10: 	$rank = 100; break; //Order
		case 11: 	$rank = 110; break; //Suborder
		case 12: 	$rank = 120; break; //Infraorder
		case 13: 	$rank = 130; break; //Superfamily
		case 14: 	$rank = 140; break; //Family
		case 15: 	$rank = 150; break; //Subfamily
		case 16: 	$rank = 160; break; //Tribe
		case 17: 	$rank = 170; break; //Subtribe
		case 18: 	$rank = 180; break; //Genus
		case 19: 	$rank = 190; break; //Subgenus
		case 20: 	$rank = 200; break; //Section
		case 21: 	$rank = 210; break; //Subsection
		case 22: 	$rank = 220; break; //Species
		case 23: 	$rank = 230; break; //Subspecies
		case 24: 	$rank = 240; break; //Variety
		case 25: 	$rank = 250; break; //Subvariety
		case 26: 	$rank = 260; break; //Form
		case 27: 	$rank = 270; break; //Subform
		case 28: 	$rank = 280; break; //Cultivar
		default:    $rank = '';  break;
	}
	return $rank;
}

function getRowByScientificName($scientific_name, $rank_id, $parent_tsn = 0) {
	$db = connect();
	//$params = array($scientificName, $rank_id, $parent_tsn);
	$sql = "select * from Taxa where match(scientificName) against('$scientific_name') and rank_id = $rank_id and parent_tsn = $parent_tsn";
	//$row = $db->getRow($sql, null, $params, null, MDB2_FETCHMODE_ASSOC);
	$row = $db->queryRow($sql, null, MDB2_FETCHMODE_ASSOC);
	isMdb2Error($row, "Error selecting scientificName information");
	return $row;
}

