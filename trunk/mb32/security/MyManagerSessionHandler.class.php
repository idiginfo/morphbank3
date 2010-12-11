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

class MyManagerSessionHandler {
	private $keywords;
	private $sortBy = array(NULL, NULL, NULL);
	private $limitBy = array("contributor" => NULL, "submitter" => NULL, "current" => NULL, "any" => NULL);
	private $numPerPage;
	private $pageNumber;
	private $massAction;
	private $currentPage;
	private $currentTab;
	private $collectionToggle;
	private $taxaToggle;
	
	function __construct() {
	//setUpVars();
	}
	
	private function setUpVars() {
		$this->keywords = NULL;
		$this->sortBy;
		$this->limitBy;
		$this->numPerPage = NULL;
		$this->pageNumber = NULL;
		$this->massAction = NULL;
		$this->currentPage = NULL;
	}
	
	// setter funtions
	function setKeywords($keywords) {
		$this->keywords = $keywords;	
	}

	function setSortBy($sortBy1, $sortBy2, $sortBy3) {
		$this->sortBy[0] = $sortBy1;
		$this->sortBy[1] = $sortBy2;
		$this->sortBy[2] = $sortBy3;
	}

	function setLimitBy($limitByArray) {
		$this->limitBy = $limitByArray;
	}
	
	function setNumPerPage($numPerPage) {
		$this->numPerPage = $numPerPage;	
	}
	
	function setPageNumber($pageNumber) {
		$this->pageNumber = $pageNumber;	
	}
	
	function setMassAction($massAction) {
		$this->massAction = $massAction;	
	}
	
	function setCurrentPage($currentPage) {
		$this->currentPage = $currentPage;	
	}

	function setCurrentTab($currentTab) {
		$this->currentTab = $currentTab;	
	}
	
	function setCollectionToggle($collectionToggle) {
		$this->collectionToggle = $collectionToggle;	
	}
	
	function setTaxaToggle($taxaToggle) {
		$this->taxaToggle = $taxaToggle;	
	}
	
	// getter funtions
	function getKeywords() {
		return $this->keywords;	
	}

	function getSortBy() {
		return $this->sortBy;
	}
	
	function getLimitBy() {
		return $this->limitBy;
	}
	
	function getNumPerPage() {
		return $this->numPerPage;	
	}
	
	function getPageNumber() {
		return $this->pageNumber;	
	}
	
	function getMassAction() {
		return $this->massAction;	
	}
	
	function getCurrentPage() {
		return $this->currentPage;	
	}

	function getCurrentTab() {
		return $this->currentTab;
	}
	
	function getCollectionToggle() {
		return $this->collectionToggle;
	}
	
	function getTaxaToggle() {
		return $this->taxaToggle;
	}
} // end class definition
?>
