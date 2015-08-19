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

class TreeView {

	// The root TSN which is to be visible from the tree
	// The standard is set as 0 to allow all kingdoms to be shown
	var $rootTsn = 0;
	
	// The path to the root directory of the website.
	// This should be the same value as that held in config.inc
	var $pathRoot = "";
	
	// Name of the table holding the taxonomic information
	var $tableName = "Tree";
	
	// Name of the string which holds the tree
	var $treeString = "";
	
	// http string for the second link. (to show the list of the images)
	// =========== add by Wilfredo =================
	var $extraLinkHref = ""; // This var has to be setup before create the tree using the function setExtraLinkHref
	var $extraIcon = "camera-min.gif";

	var $extraLinkHref1 = ""; // This var has to be setup before create the tree using the function setExtraLinkHref1
	var $extraIcon1 = "itisLogo-trans.png";

        // =========== add by Karolina =================
        var $extraLinkHref2 = ""; // This var has to be setup before create the tree using the function setExtraLinkHref1
        var $extraIcon2 = "annotate-trans.png";



	// "privates"
	var $lft = NULL;
	var $rgt = NULL;
        var $link = NULL;

	##################################################################################
	
	// Sets the root visible node
	function setRootVisible ($r)
	{
		$this->rootTsn = $r;
	}
	
	// Sets the taxonDatabase information
	// The $data variable should be an array passed to the class with the following
	// indicies
	// 0 - Host name of the database server
	// 1 - Username used to connect to the server
	// 2 - Password used to connect to the server
	// 3 - Database name on the server to use
	// 4 - Port used to connect to the database (Note this is the ONLY optional one)
	function setTaxonDatabase($data)
	{
		$this->taxonDatabaseHost = $data[0];
		$this->databaseUsername = $data[1];
		$this->databasePassword = $data[2];
		$this->databaseName = $data[3];
		if (isset($data[4]))
		{
			$this->databasePort = $data[4];
		}
	}
	
	// Sets the pathRoot variable, allowing this to be set automatically from the value
	// held in config.inc (assuming the calling script sets the value)
	function setPathRoot($path)
	{
		$this->pathRoot = $path;
	}
	
	function setExtraLinkHref($link)
	{
		$this->extraLinkHref = $link;
	}
	
	function setExtraLinkHref1($link)
	{
		$this->extraLinkHref1 = $link;
	}

	function setExtraLinkHref2($link)
        {
	       $this->extraLinkHref2 = $link;
	}


    function createTree($tsn = 0, $majorCategories=0, $noSynonyms=0, $images=0) {

		if (!$this->connectDatabase()) return "";
		
		$taxonRankIdToDisplay[0] = array ('name' => 'Life', 'display' => TRUE);
		$taxonRankIdToDisplay[10] = array ('name' => 'Kingdom', 'display' => TRUE);
		$taxonRankIdToDisplay[20] = array ('name' => 'Subkingdom', 'display' => FALSE);
		$taxonRankIdToDisplay[30] = array ('name' => 'Phylum', 'display' => TRUE);
		$taxonRankIdToDisplay[40] = array ('name' => 'Subphylum', 'display' => FALSE);
		$taxonRankIdToDisplay[50] = array ('name' => 'Superclass', 'display' => FALSE);
		$taxonRankIdToDisplay[60] = array ('name' => 'Class', 'display' => TRUE);
		$taxonRankIdToDisplay[70] = array ('name' => 'Subclass', 'display' => FALSE);
		$taxonRankIdToDisplay[80] = array ('name' => 'Infraclass', 'display' => FALSE);
		$taxonRankIdToDisplay[90] = array ('name' => 'Superorder', 'display' => FALSE);
		$taxonRankIdToDisplay[100] = array ('name' => 'Order', 'display' => TRUE);
		$taxonRankIdToDisplay[110] = array ('name' => 'Suborder', 'display' => FALSE);
		$taxonRankIdToDisplay[120] = array ('name' => 'Infraorder', 'display' => FALSE);
		$taxonRankIdToDisplay[130] = array ('name' => 'Superfamily', 'display' => FALSE);
		$taxonRankIdToDisplay[140] = array ('name' => 'Family', 'display' => TRUE);
		$taxonRankIdToDisplay[150] = array ('name' => 'Subfamily', 'display' => FALSE);
		$taxonRankIdToDisplay[160] = array ('name' => 'Tribe', 'display' => FALSE);
		$taxonRankIdToDisplay[170] = array ('name' => 'Subtribe', 'display' => FALSE);
		$taxonRankIdToDisplay[180] = array ('name' => 'Genus', 'display' => TRUE);
		$taxonRankIdToDisplay[190] = array ('name' => 'Subgenus', 'display' => FALSE);
		$taxonRankIdToDisplay[220] = array ('name' => 'Species', 'display' => TRUE);
		$taxonRankIdToDisplay[230] = array ('name' => 'Subspecies', 'display' => FALSE);
		$taxonRankIdToDisplay[240] = array ('name' => 'Variety', 'display' => TRUE);
		$taxonRankIdToDisplay[245] = array ('name' => 'Form', 'display' => TRUE);
		// === not very well clear so far, then I'll show all of them
		$taxonRankIdToDisplay[250] = array ('name' => 'Subvariety', 'display' => TRUE);
		$taxonRankIdToDisplay[255] = array ('name' => 'Stirp', 'display' => TRUE);
		$taxonRankIdToDisplay[260] = array ('name' => 'Form', 'display' => TRUE);
		$taxonRankIdToDisplay[265] = array ('name' => 'Aberration', 'display' => TRUE);
		$taxonRankIdToDisplay[270] = array ('name' => 'Subform', 'display' => TRUE);
		$taxonRankIdToDisplay[300] = array ('name' => 'Unspecified', 'display' => TRUE);
		
		// Get the parents
		$parentTsn = $this->getArrayParents($tsn);
		if ($parentTsn == 0) return "";
		
		$numParents = count($parentTsn);
		$numlevel = 0;
		for ( $i = 0; $i < $numParents; $i++) {
			$level = "";
			if ($taxonRankIdToDisplay[ $parentTsn[$i]['rank_id']]['display'] || !$majorCategories) {
				$numlevel += 1;
				// concat the dots depending of the levels (i)
				for ( $j = 0; $j < $numlevel; $j++) $level .= ".";
				
				$rankName = $this->getRankNameFromId ( $parentTsn[$i]['kingdom_id'], $parentTsn[$i]['rank_id']);
				if($images=='0'){
				  if($noSynonyms=='0'){
				     if (($parentTsn[$i]['usage']=='invalid') || ($parentTsn[$i]['usage']=='not accepted'))
					$this->treeString .= $level."|".$rankName." -<font color=\"#8a0404\"><b><i>".$parentTsn[$i]['name']."</i> [".$parentTsn[$i]['usage']."]</b></font>|".$parentTsn[$i]['tsn']."||||1|";
				     else
				       $this->treeString .= $level."|".$rankName." -<b><i>".$parentTsn[$i]['name']."</i></b>|".$parentTsn[$i]['tsn']."||||1|";}
				  else{
				    if (($parentTsn[$i]['usage']=='invalid') || ($parentTsn[$i]['usage']=='not accepted')){
				      $numlevel = $numlevel-1;
				      continue;}
				    else
				         $this->treeString .= $level."|".$rankName." -<b><i>".$parentTsn[$i]['name']."</i></b>|".$parentTsn[$i]['tsn']."||||1|";
				  }
								
				//$numImages = $this->getNumImagesPerTaxon($parentTsn[$i]['tsn']);
				$numImages = $parentTsn[$i]['imagesCount'];
				if ($numImages>0)
					$this->treeString .= $numImages."|".$this->extraIcon."|".$this->extraLinkHref."?tsn=".$parentTsn[$i]['tsn']."|";
				else
				  $this->treeString .= "0|";

                                //------------added by Karolina-------------
				//annotation icon and link
				if($parentTsn[$i]['tsn']!=0)
				  $this->treeString .= $this->extraIcon2."|".$this->extraLinkHref2."?tsn=".$parentTsn[$i]['tsn']."|";
				//------------------------------------------

				if ($parentTsn[$i]['tsn']>0 && $parentTsn[$i]['tsn']<999000000) // Itis icon and link
				  $this->treeString .= $this->extraIcon1."|".$this->extraLinkHref1.$parentTsn[$i]['tsn']."|\r\n"; 
				else
				  $this->treeString .= "|\r\n";
				
				}
				else{
				  $numImages = $parentTsn[$i]['imagesCount'];
				  if($numImages<1){
				    $numlevel = $numlevel-1;
				    continue;}
				  else{
				    if($noSynonyms=='0'){
				      if (($parentTsn[$i]['usage']=='invalid') || ($parentTsn[$i]['usage']=='not accepted'))
					$this->treeString .= $level."|".$rankName." -<font color=\"#8a0404\"><b><i>".$parentTsn[$i]['name']."</i> [".$parentTsn[$i]['usage']."]</b></font>|".$parentTsn[$i]['tsn']."||||1|";
				      else
					$this->treeString .= $level."|".$rankName." -<b><i>".$parentTsn[$i]['name']."</i></b>|".$parentTsn[$i]['tsn']."||||1|";}
				    else{
				      if (($parentTsn[$i]['usage']=='invalid') || ($parentTsn[$i]['usage']=='not accepted')){
					$numlevel = $numlevel-1;
					continue;}
				    else
				      $this->treeString .= $level."|".$rankName." -<b><i>".$parentTsn[$i]['name']."</i></b>|".$parentTsn[$i]['tsn']."||||1|";
				    }
				    
				    $this->treeString .= $numImages."|".$this->extraIcon."|".$this->extraLinkHref."?tsn=".$parentTsn[$i]['tsn']."|";
				    //------added by Karolina-----
				    $this->treeString .= $this->extraIcon2."|".$this->extraLinkHref2."?tsn=".$parentTsn[$i]['tsn']."|";
				    //---------------------------

				    if ($parentTsn[$i]['tsn']>0 && $parentTsn[$i]['tsn']<999000000) // Itis icon and link
				      $this->treeString .= $this->extraIcon1."|".$this->extraLinkHref1.$parentTsn[$i]['tsn']."|\r\n"; 
				    else
				      $this->treeString .= "|\r\n";
				  }
				}
			}
		}
		       
		// Get the children
		if (!$majorCategories){ 
			$arrayChildren = $this->getArrayNamesChildren($tsn);
		}
		else { //temporal solution 
			for ($rank=$parentTsn[$i-1]['rank_id']+5; $rank<305; $rank=$rank+5)
			  if (isset($taxonRankIdToDisplay[$rank]['display']) && $taxonRankIdToDisplay[$rank]['display']) break; 

			$arrayChildren = $this->getArrayNamesChildrenRankId($tsn, $rank);
		}
		
		if ($arrayChildren)
			$this->printArrayTSNToString($arrayChildren, $numlevel+1, true, $noSynonyms, $images);
	      			
		return $this->treeString;
    }
	
	// Prints out the array of tsn and taxon names to the string for reading
	// by the script which produces the tree file in the browser.
	function printArrayTSNToString($array, $level, $expanded, $noSynonyms, $images)
	{
		$arrayLength = count($array);
				
		for ($i = 0; $i < $arrayLength; $i ++)
		{
			for ($j = 0; $j < $level; $j++)
			{
				$this->treeString .=".";
			}
			//$numImages = $this->getNumImagesPerTaxon($array[$i]['tsn']);
			$numImages = $array[$i]['imagesCount'];
			$rankName = $this->getRankNameFromId( $array[$i]['kingdom_id'], $array[$i]['rank_id']);
			if($images=='0'){
			  if($noSynonyms=='0'){
			    if (($array[$i]['usage'] == 'not accepted') || ($array[$i]['usage'] == 'invalid'))
			      $this->treeString .= "|".$rankName." -<font color=\"#8a0404\"><b><i>".$array[$i]['name']."</i> [".$array[$i]['usage']."]</b></font>|".$array[$i]['tsn'];
			    else
			      $this->treeString .= "|".$rankName." -<b><i>".$array[$i]['name']."</i></b>|".$array[$i]['tsn'];}
			else{
			  if(($array[$i]['usage'] == 'not accepted') || ($array[$i]['usage'] == 'invalid')){
			    //$new_length = strlen($this->treeString)-$level;
			    //$new_string = substr($this->treeString,0,$new_length);
			    $this->treeString = substr($this->treeString,0,strlen($this->treeString)-$level);
			    continue;}
			  else
			    $this->treeString .= "|".$rankName." -<b><i>".$array[$i]['name']."</i></b>|".$array[$i]['tsn'];

			}
			$this->treeString .= "||||".($expanded?"1":"0")."|";
			if ($numImages>0)
				$this->treeString .= $numImages."|".$this->extraIcon."|".$this->extraLinkHref."?tsn=".$array[$i]['tsn']."|";
			else
				$this->treeString .= "0|||";

			//----added by Karolina-------
			$this->treeString .= $this->extraIcon2."|".$this->extraLinkHref2."?tsn=".$array[$i]['tsn']."|";
			//----------------------------

			if ($array[$i]['tsn']>0 && $array[$i]['tsn']<999000000)
				$this->treeString .= $this->extraIcon1."|".$this->extraLinkHref1.$array[$i]['tsn']."|\r\n"; 
			else
				$this->treeString .= "|\r\n";
			}
			else{
			  if($numImages>0){
			    if($noSynonyms=='0'){
			    if (($array[$i]['usage'] == 'not accepted') || ($array[$i]['usage'] == 'invalid'))
			      $this->treeString .= "|".$rankName." -<font color=\"#8a0404\"><b><i>".$array[$i]['name']."</i> [".$array[$i]['usage']."]</b></font>|".$array[$i]['tsn'];
			    else
			      $this->treeString .= "|".$rankName." -<b><i>".$array[$i]['name']."</i></b>|".$array[$i]['tsn'];}
			else{
			  if(($array[$i]['usage'] == 'not accepted') || ($array[$i]['usage'] == 'invalid')){
			    //$new_length = strlen($this->treeString)-$level;
			    //$new_string = substr($this->treeString,0,$new_length);
			    $this->treeString = substr($this->treeString,0,strlen($this->treeString)-$level);
			    continue;}
			  else
			    $this->treeString .= "|".$rankName." -<b><i>".$array[$i]['name']."</i></b>|".$array[$i]['tsn'];

			}
			$this->treeString .= "||||".($expanded?"1":"0")."|";
			$this->treeString .= $numImages."|".$this->extraIcon."|".$this->extraLinkHref."?tsn=".$array[$i]['tsn']."|";

			//----added by Karolina
			$this->treeString .= $this->extraIcon2."|".$this->extraLinkHref2."?tsn=".$array[$i]['tsn']."|";
			//----------------------

			if ($array[$i]['tsn']>0 && $array[$i]['tsn']<999000000)
				$this->treeString .= $this->extraIcon1."|".$this->extraLinkHref1.$array[$i]['tsn']."|\r\n"; 
			else
				$this->treeString .= "|\r\n";
			  }
			  else{
			    $this->treeString = substr($this->treeString,0,strlen($this->treeString)-$level);
			    continue;
			  }
			}
			/*
			if ($expanded){
				$this->treeString .= "||||1|images|";
				//$this->treeString .=	$this->extraLinkHref."?tsn=".$array[$i]['tsn']."\r\n";
			}
			else {
				$this->treeString .= "||||0|images|\r\n";
			}
			*/
		}
	}
		
	function getArrayParents($tsn) {
		// Get left and right values
		$sql = "SELECT lft, rgt FROM ".$this->tableName." WHERE tsn=".$tsn." AND lft>=0 AND rgt>=0";
		$result = mysqli_fetch_array( mysqli_query($this->link,$sql));
		if (!$result) return 0;
		
		$lft = $result['lft'];
		$this->lft = $lft;
		$rgt = $result['rgt'];
		$this->rgt = $rgt;
		// Get path of the parents
		//$sql = "SELECT tree.tsn as tsn, lft, rgt";
		$sql = "SELECT tsn, `usage`, unit_name1, unit_name2, unit_name3, unit_name4, scientificName, lft, rgt, kingdom_id, rank_id, imagesCount";
		$sql .= " FROM ".$this->tableName;
		$sql .= " WHERE lft<=".$lft." AND rgt>=".$rgt." AND `usage`!='not public' ORDER BY lft ASC";		
		$result = mysqli_query($this->link,$sql);
		$i = 0;
		while ( $array = mysqli_fetch_array($result)) {
		  /*$parent_tsn_array[$i]['name'] = $array['unit_name1'];
			if ($array['unit_name2'] != "")
			{
				$parent_tsn_array[$i]['name'] .= " ".$array['unit_name2'];
				if ($array['unit_name3'] != "")
				{
					$parent_tsn_array[$i]['name'] .= " ".$array['unit_name3'];
					if ($array['unit_name4'] != "")
						$parent_tsn_array[$i]['name'] .= " ".$array['unit_name4'];
				}
			}*/
			$parent_tsn_array[$i]['name'] = $array['scientificName'];// why the concatenation before
			$parent_tsn_array[$i]['tsn'] = $array['tsn'];
			$parent_tsn_array[$i]['usage'] = $array['usage'];
			$parent_tsn_array[$i]['kingdom_id'] = $array['kingdom_id'];
			$parent_tsn_array[$i]['rank_id'] = $array['rank_id'];
			$parent_tsn_array[$i]['imagesCount'] = $array['imagesCount'];
			$i++;
		}
		return $parent_tsn_array;
	}
	
	// Takes a TSN, and returns an array of all the DIRECT child elements of that TSN.
	// Note, this returns an array of arrays where the first element in the child array is the name
	// and the second element in the array is the TSN
	function getArrayNamesChildren($tsn)
	{
		//$this->connectDatabase();
		$sql  = "SELECT tsn, `usage`, unit_name1, unit_name2, unit_name3, unit_name4, scientificName, kingdom_id, rank_id, imagesCount FROM ";
		$sql .= '`'.$this->tableName.'` WHERE parent_tsn = '.$tsn.' ';
		$sql .= ' AND `usage`!="not public" ORDER BY unit_name1, unit_name2, unit_name3, unit_name4';
		$result = mysqli_query($this->link,$sql);
				
		$array = false;
		$i = 0;
		
		while ($resultArray = mysqli_fetch_array($result))
		{
/*$array[$i]['name'] = $resultArray['unit_name1']; //.$resultArray['unit_name2'].$resultArray['unit_name3'].$resultArray['unit_name4'];
			if ($resultArray['unit_name2'] != "")
			{
				$array[$i]['name'] .= " ".$resultArray['unit_name2'];
				if ($resultArray['unit_name3'] != "")
				{
					$array[$i]['name'] .= " ".$resultArray['unit_name3'];
					if ($resultArray['unit_name4'] != "")
					{
						$array[$i]['name'] .= " ".$resultArray['unit_name4'];
					}
				}
			}*/
			
			$array[$i]['name'] = $resultArray['scientificName'];// why the concatenation before
			$array[$i]['tsn'] = $resultArray['tsn'];
			$array[$i]['usage'] = $resultArray['usage'];
			$array[$i]['kingdom_id'] = $resultArray['kingdom_id'];
			$array[$i]['rank_id'] = $resultArray['rank_id'];
			$array[$i]['imagesCount'] = $resultArray['imagesCount'];
			$i++;
		}
		return $array;
	}
	
	function getArrayNamesChildrenRankId ( $tsn, $nextRankToShow) {
		//$this->connectDatabase();
		$sql  = "SELECT tsn, `usage`, unit_name1, unit_name2, unit_name3, unit_name4, scientificName, kingdom_id, rank_id, imagesCount FROM ";
		$sql .= '`'.$this->tableName.'` WHERE lft> '.$this->lft.' and rgt<'.$this->rgt.' and rank_id='.$nextRankToShow.' ';
		$sql .= 'AND `usage`!="not public" ORDER BY unit_name1, unit_name2, unit_name3, unit_name4';
		$result = mysqli_query($this->link,$sql);
		
		$array = false;
		$i = 0;
		
		while ($resultArray = mysqli_fetch_array($result))
		{
/*$array[$i]['name'] = $resultArray['unit_name1']; //.$resultArray['unit_name2'].$resultArray['unit_name3'].$resultArray['unit_name4'];
			if ($resultArray['unit_name2'] != "")
			{
				$array[$i]['name'] .= " ".$resultArray['unit_name2'];
				if ($resultArray['unit_name3'] != "")
				{
					$array[$i]['name'] .= " ".$resultArray['unit_name3'];
					if ($resultArray['unit_name4'] != "")
					{
						$array[$i]['name'] .= " ".$resultArray['unit_name4'];
					}
				}
			}*/
			
			$array[$i]['name'] = $resultArray['scientificName'];// why the concatenation before
			$array[$i]['tsn'] = $resultArray['tsn'];
			$array[$i]['usage'] = $resultArray['usage'];
			$array[$i]['kingdom_id'] = $resultArray['kingdom_id'];
			$array[$i]['rank_id'] = $resultArray['rank_id'];
			$array[$i]['imagesCount'] = $resultArray['imagesCount'];
			$i++;
		}
		return $array;
	}
	
	function getRankNameFromId ( $kingdomId, $rankId) { 
		if ($kingdomId == NULL) return "";
		if ($rankId == NULL) return "";
		
		$table = 'TaxonUnitTypes';
		$sql = 'SELECT rank_name FROM ';
		$sql .= '`'.$table.'` WHERE kingdom_id='.$kingdomId.' AND rank_id='.$rankId;
		$result = mysqli_query($this->link,$sql);
		if (!$result) 
			return 0;
		$oneRow = mysqli_fetch_array($result);	

		return $oneRow['rank_name'];
	}
	
	function getNumImagesPerTaxon( $tsn=NULL) {
		
		if ($tsn == NULL) return 0;
		
		//$this->connectDatabase();
		$table = $this->tableName;
		$sql  = 'SELECT imagesCount FROM ';
		$sql .= '`'.$table.'` WHERE tsn = '.$tsn;
		$result = mysqli_query($this->link,$sql);
		if (!$result) 
			return 0;
		$oneRow = mysqli_fetch_array($result);
		
		return $oneRow['imagesCount'];
	}
	
	// Connects to the database
	function connectDatabase(){

	  // Connect to MySQL server and Morphbank database.
	  $this->link = adminlogin();
	    
	    if (!$this->link)
	      return false;
	    else
	      return true;
	}
}
?>
