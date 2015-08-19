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

include_once('Phylogenetics/phylo.inc.php');

class mbObjectClass {

	public $link;
	public $myObjOptions;
	public $color;
	public $userId;
	public $groupId;
	public $opener;
	public $offset;
	public $numPerPage;
	public $callingPage;
	public $newOffset;
	public $total;
	public $domainName;
	public $thumbnailWidth;
	public $imgDirectory;
	public $specialCharsArray;
	public $objInfoRef;
	public $img;
	public $imgObject;

	function __construct($link, $domainName = "http://morphbank.net/", $total = 0) {
		$this->link = adminLogin();
		$this->domainName = $domainName;
		$this->total = $total;
		$this->setupVars();
	}

	function setupVars() {
		global $objInfo;
		$myObjOptions = array (	'Info'=> TRUE,
								'Edit'=> TRUE,
								'Annotate'=> TRUE,
								'Select'=> FALSE,
								'Delete'=> FALSE,
								'Copy' => FALSE);
		// Loop
		//===========================
		$this->color[0] = "#ffffff";
		$this->color[1] = "#e5e5f5";
		$this->thumbnailWidth = 93;
		$this->imgDirectory = $this->domainName.'style/webImages/';

		$this->userId = $objInfo->getUserId() ? $objInfo->getUserId() : 0;
		$this->groupId = $objInfo->getUserGroupId() ? $objInfo->getUserGroupId() : 0;
		$this->opener = isset($_GET['pop']) ? "&amp;opener=true":"&amp;opener=false";
			
		// Set the value of offset to that passed in the URL, else 0.
		$this->offset = (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$this->numPerPage = (isset($_GET['numPerPage'])) ?  $_GET['numPerPage'] : 20;
		$this->objInfoRef =& $objInfo;

		if ($_GET['goTo'] != "") {
			//$newOffset =(int)(($_GET['goTo']-1)/$_GET['numPerPage']);
			$this->newOffset =(int)($_GET['goTo'])?$_GET['goTo']-1:$_GET['offset']/$_GET['numPerPage'];
			if ($this->newOffset*$_GET['numPerPage']<$this->total) $_GET['offset'] = $this->newOffset*$_GET['numPerPage'];
		}
		$this->numRows = ($this->total-$_GET['offset'])>=$_GET['numPerPage']?$_GET['numPerPage']:$this->total-$_GET['offset'];

		if(isset($_GET['offset'])) {
			$this->offset = $_GET['offset'];
		} else {
			$this->offset = 0;
		}
		$this->specialCharsArray = array('&oacute;' => '&#243;');
	}

	function getNumRows() {
		return $this->numRows;
	}

	function getTotal() {
		return $this->total;
	}

	function getNumPerPage() {
		return $this->numPerPage;
	}

	function getOffset() {
		return $this->offset;
	}

	function getAnnotationCount($id) {
		$sql = 'SELECT count(*) AS count FROM Annotation INNER JOIN BaseObject ON Annotation.id = BaseObject.id WHERE Annotation.objectId='.$id.' AND (BaseObject.dateToPublish < NOW() OR BaseObject.userId='.$this->userId.' OR BaseObject.groupId='.$this->groupId.')';
		$results = mysqli_query($this->link, $sql);
		if ($results) {
			$array = mysqli_fetch_assoc($results);
			return $array['count'];
		}
	}

	function cleanArrayOfSpecialCharacters($array) {
		if (!$array) return $array;
		foreach($array as $k => $v) {
			$array[$k] = htmlentities($v, ENT_QUOTES, "UTF-8");
			//$array[$k] = htmlentities($v);
		}
		return $array;
	}

	function echoCheckBox($i, $value) {
		if ($this->objInfoRef->getLogged())
		echo '<input id="box-'.($i+1).'" type="checkbox" name="object'.($i+1)
		.'" value="'.$value.'" onclick="swapColor(\''.($i+1).'\')"/>&nbsp;';
	}

	static function setupGetVars($thumbsperpage) {
		if (!isset($_GET['offset']))
		$_GET['offset'] = 0;

		if ($_GET['resetOffset'] =='on') {
			$_GET['offset'] = 0;
			$_GET['goTo'] = "";
			$_GET['resetOffset'] = 'off';
		}

		if (!isset($_GET['goTo']) || !is_numeric($_GET['goTo'])) $_GET['goTo'] = "";
		if (is_numeric($_GET['goTo'])) $_GET['goTo'] = round((int)$_GET['goTo']);
		
		if (!isset($_GET['numPerPage'])) {
			$_GET['numPerPage'] = $thumbsperpage;
		}
	}
}

?>
