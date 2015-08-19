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

//
// @author Wilfredo Blanco
// @date July 17th 2007
// 


include_once('Phylogenetics/Classes/BaseObject.php');
include_once('Phylogenetics/Classes/Collection.php');
include_once('Phylogenetics/Classes/CollectionObject.php');
include_once('Phylogenetics/Classes/Matrix.php');

// Class in charge to display the Character/State Matrix interface
// This class use the output (structure of objects) from the Matrix class in
// Matrix.php module from Karolina. Check method setCharStageMatrix.

class charStageMatrixInterface {
	public $title;					// Title of the matrix
	private $domainName;			// It could be taken from the global variables, but ... 
	private $actionUrl;				// use domainName to complete the current url
	
	private $selectedOneCell;		// boolean value =>if one cell of the matrix is selected, 
									// if (selectedCol && selectedRow) then selectedOneCell is True
	private $selectedCol;			// Name of the selected column 
	private $selectedRow;			// Name of the selected row
	
	private $charStageM;			// Matrix Id
	
	private $linkClickingOnCell;	// var to store the action url whe click on a cell
	private $linkClickingOnRow;		// var to store the action url whe click on a Row
	private $linkClickingOnColumn;	// var to store the action url whe click on a Column
	
	private $numMatrixRows;			// Matrix rows
	private $numMatrixCols;			// Matrix columns
	
	private $httpGetLine;			// Associative array to store the GET variable to built url when form is sumited and arrows
	private $cphttpGetLine;			// Copy of above array
		
	private $dRowToShow = 10; 		// Default rows to show	
	private $dColToShow = 6;		// Default columns to show
	
	function setTitle( $newTitle = "") {
		$this->title = $newTitle; 
	}
	
	function getDomainName() {
		return $this->domainName;
	}
	
	function setDomainName ( $myDomain = "http://morphban4.scs.fsu.edu") {
		$this->domainName = $myDomain;
		$this->actionUrl = $this->domainName.'MyMatrices/index.php?';
	}
	
	function setLinkClickingOnCell ( $linkClickingOnCell) {
		if ( $linkClickingOnCell)
			$this->linkClickingOnCell = $linkClickingOnCell;
		else
			$this->linkClickingOnCell = $this->actionUrl;
	}
	
	function setLinkClickingOnRow ( $linkClickingOnRow) {
		if ($linkClickingOnRow)
			$this->linkClickingOnRow = $linkClickingOnRow;
		else
			$this->linkClickingOnRow = $this->actionUrl;
	}
	
	function setLinkClickingOnColumn ( $linkClickingOnColumn ) {
		if ( $linkClickingOnColumn)
			$this->linkClickingOnColumn = $linkClickingOnColumn;
		else
			$this->linkClickingOnColumn = $this->actionUrl;
	}
	
	// Class constructor
	function __construct ( $myDomain = "http://morphban4.scs.fsu.edu") {
		$this->setDomainName ($myDomain);
		$this->linkClickingOnColumn = $this->domainName.'MyCollection/?pop=Yes&amp;id=';
		$this->linkClickingOnRow = $this->domainName.'MyCollection/?pop=Yes&amp;id=';
		$this->linkClickingOnCell = $this->domainName.'MyCollection/?pop=Yes&amp;id=';
		
		$this->retrieveDataFromGET();  // Get value of Matrix Id from GET html line (use setCharStageMatrix method in other case)
		$this->setCharStageMatrix();
		$this->cphttpGetLine = $this->httpGetLine;
	}
	
	function __destruct() {
       $this->charStageM = null;
    }

	function setCharStageMatrix( $matId = null) {
		global $link;
		if ($matId) {  // comming as a parameter $matId
			$this->charStageM = new Matrix($link, $matId);
			$this->numMatrixRows = $this->charStageM->getNumRows();
			$this->numMatrixCols = $this->charStageM->getNumChars();
			$this->httpGetLine['matId'] = $matId;
			$this->resetColRows();
			return;
		}
		if ($this->httpGetLine['matId']) { // matId value from GET
			$this->charStageM = new Matrix($link, $this->httpGetLine['matId']);
			$this->numMatrixRows = $this->charStageM->getNumRows();
			$this->numMatrixCols = $this->charStageM->getNumChars();
			$this->resetColRows();
			return; 
		}
		// Other case (not parameter and not GET)
		$this->charStageM = null;		
	}
	
	// Initialization and setup the amounts of columns and rows to be display 
	private function resetColRows() {
		// be sure there is a value
		$this->httpGetLine['sRow']=isset($this->httpGetLine['sRow'])?$this->httpGetLine['sRow']:1; 
		$this->httpGetLine['sCol']=isset($this->httpGetLine['sCol'])?$this->httpGetLine['sCol']:1;
		
		if (!isset($this->httpGetLine['nRow']))
			$this->httpGetLine['nRow']=$this->numMatrixRows>=$this->dRowToShow?$this->dRowToShow:$this->numMatrixRows;
		if (!isset($this->httpGetLine['nCol']))
			$this->httpGetLine['nCol']=$this->numMatrixCols>=$this->dColToShow?$this->dColToShow:$this->numMatrixCols;
		$this->cphttpGetLine = $this->httpGetLine;
		
		// If the form data is wrong
		if ($this->httpGetLine['sRow']<0 && (($this->httpGetLine['sRow']+$this->httpGetLine['nRow'])>$this->numMatrixRows)) {
			$this->httpGetLine['sRow'] = 1;
			$this->httpGetLine['nRow']=$this->numMatrixRows>=$this->dRowToShow?$this->dRowToShow:$this->numMatrixRows;
		}
		if ($this->httpGetLine['sCol']<0 && (($this->httpGetLine['sCol']+$this->httpGetLine['nCol'])>$this->numMatrixCols)) {
			$this->httpGetLine['sCol'] = 1;
			$this->httpGetLine['nCol'] = $this->numMatrixCols>=$this->dColToShow?$this->dColToShow:$this->numMatrixCols;
		}
	}
	
	// Retrieve variables from GET
	private function retrieveDataFromGET () {
		$this->selectedCol = null;
		$this->selectedRow = null;
		$this->selectedOneCell = false;
		
		if (isset($_GET['matId'])) {
			$this->httpGetLine['matId'] = $_GET['matId'];
		} else
			$this->httpGetLine['matId']= null;
		
		if (isset($_GET['col'])) {
			$this->selectedCol = $_GET['col'];
		}
		if (isset($_GET['row'])) {
			$this->selectedRow = $_GET['row'];
		}
		if ( $this->selectedRow && $this->selectedCol) { // one cell selected
			$this->selectedOneCell = true;
		}
		if (isset($_GET['sCol'])) {
			$this->httpGetLine['sCol'] = $_GET['sCol'];
		}
		if (isset($_GET['sRow'])) {
			$this->httpGetLine['sRow'] = $_GET['sRow'];
		}
		if (isset($_GET['nCol'])) {
			$this->httpGetLine['nCol'] = $_GET['nCol'];
		}
		if (isset($_GET['nRow'])) {
			$this->httpGetLine['nRow'] = $_GET['nRow'];
		}
	}
	
	// Everything is taken from the url line (Get)
	function displayFromGet( )  {
		$this->display();
	}
	
	// Using parameters
	function displayFromPara ( $matId, $sCol, $sRow, $nCol, $nRow )  {
		$this->setCharStageMatrix( $matId);
		$this->httpGetLine['sCol'] = $sCol;
		$this->httpGetLine['sRow'] = $sRow;
		$this->httpGetLine['nCol'] = $nCol;
		$this->httpGetLine['nRow'] = $nRow;
		$this->resetColRows();
		$this->display();
	}
		
	function display( ) {
		
		
		$this->echoJSFuntions();
		// Title and Id --- maybe the title could be taken from Karolina structure
		echo '<div> 
				<h1>Id:'.$this->httpGetLine['matId'].'<br/>'.$this->title.'</h1></div><br /><br />'; // no getObjectTitle()... so I used my title
		
		// Display the form 
		$this->displayForm();
		
		// Display the top nav Bar 
		$this->displayTopNavBar($width); // 6 columns and 1 row
		
		// Table with 4 rows. The matrix is displayed at the first Row (rowspan=4),second column (colspan = 4)
		echo '<table class="outSideTable">
				<tr>
					<td class="arrowCell">
						<a href="'.$this->getHttpGETLine_goToFirstRow().'" title="go to first row">
						<img src="/style/webImages/goUpFirst.png" border="0" alt="UpFirst" /></a>
					</td>
					<td rowspan=4 colspan=4>';
						// Display the Matrix 
						$this->displayMatrix();
		echo '      </td>
					<td class="arrowCellTop" align="right">
						<a href="'.$this->getHttpGETLine_goToFirstRow().'" title="go to first row">
						<img src="/style/webImages/goUpFirst.png" border="0" alt="UpFirst" /></a>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<a href="'.$this->getHttpGETLine_goToPreviousRow().'" title="previous group of rows">
						<img src="/style/webImages/up-gnome.png" border="0" alt="Up" /></a>
					</td>
					<td valign="top" align="right">
						<a href="'.$this->getHttpGETLine_goToPreviousRow().'" title="previous group of rows">
						<img src="/style/webImages/up-gnome.png" border="0" alt="Up" /></a>
					</td>
				</tr>
				<tr>
					<td valign="bottom">
						<a href="'.$this->getHttpGETLine_goToNextRow().'" title="next group of rows">
						<img src="/style/webImages/down-gnome.png" border="0" alt="Down" /></a>
					</td>
					<td valign="bottom" align="right">
						<a href="'.$this->getHttpGETLine_goToNextRow().'" title="next group of rows">
						<img src="/style/webImages/down-gnome.png" border="0" alt="Down" /></a>
					</td>
				</tr>
				<tr>
					<td class="arrowCellBott" valign="bottom">
						<a href="'.$this->getHttpGETLine_goToLastRow().'" title="last group of rows">
						<img src="/style/webImages/goDownLast.png" border="0" alt="DownLast" /></a>
					</td>
					<td class="arrowCell" valign="bottom" align="right">
						<a href="'.$this->getHttpGETLine_goToLastRow().'" title="last group of rows">
						<img src="/style/webImages/goDownLast.png" border="0" alt="DownLast" /></a>
					</td>
				</tr>
		</table>';
		
		// Display the bottom nav Bar
		$this->displayBottomNavBar($width);
	}
	
	// Some function to validate the form entries
	private function echoJSFuntions() {
		echo '
			<script language="JavaScript" type="text/javascript">
			// Declaring required variables
			var digits = "0123456789";
		
			function isInteger(s) {
				var i;
    			for (i = 0; i < s.length; i++) {
	    			// Check that current character is number.
        			var c = s.charAt(i);
        		if (((c < "0") || (c > "9"))) return false;
    		}
    		// All characters are numbers.
    		return true;
			}
			
		function ValidateForm(){
			var sRow=document.displayInfo.sRow
			var sCol=document.displayInfo.sCol
			var nRow=document.displayInfo.nRow
			var nCol=document.displayInfo.nCol
	
			if ((sRow.value==null)||(sRow.value=="")
					||(!isInteger(sRow.value))||(Number(sRow.value)<1)){
				alert("Please Enter a correct value (not null, integer > 0) for First row")
				sRow.focus()
				return false
			}
			if ((sCol.value==null)||(sCol.value=="")
				||(!isInteger(sCol.value))||(Number(sCol.value)<1)){
				alert("Please Enter a correct value (not null, integer > 0) for First column")
				sCol.focus()
				return false
			}
			if ((nRow.value==null)||(nRow.value=="")
				||(!isInteger(nRow.value))||(Number(nRow.value)<1)){
				alert("Please Enter a correct value (not null, integer > 0) for Num. rows")
				nRow.focus()
				return false
			}
			if ((nCol.value==null)||(nCol.value=="")
					||(!isInteger(nCol.value))||(Number(nCol.value)<1)){
				alert("Please Enter a correct value (not null, integer > 0) for Num. columns")
				nCol.focus()
				return false
			}
			
			return true
 		}
 		// it is not used
 		function alertSize() {
			var myWidth = 0, myHeight = 0;
			if( typeof( window.innerWidth ) == "number" ) {
				//Non-IE
			  	myWidth = window.innerWidth;
			    myHeight = window.innerHeight;
			} else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
			    //IE 6+ in "standards compliant mode"
			    myWidth = document.documentElement.clientWidth;
			    myHeight = document.documentElement.clientHeight;
			} else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
			    //IE 4 compatible
			    myWidth = document.body.clientWidth;
			    myHeight = document.body.clientHeight;
			}
			window.alert( "Width = " + myWidth );
			window.alert( "Height = " + myHeight );
		}
		</script>';
	}
	
	private function displayForm() {
		echo '<div>
				<form name="displayInfo" method="get" onSubmit="return ValidateForm()">
					<input type="hidden" name="row" value="'.$this->selectedRow.'">
					<input type="hidden" name="col" value="'.$this->selectedCol.'">
					<input type="hidden" name="matId" value="'.$this->httpGetLine['matId'].'">
				<table border=0>
					<tr>
						<td width="80px" align="left">OTU:</td>
						<td width="60px" align="right">'.($this->numMatrixRows-1).'</td><td width = "30px"></td>
						<td width="120px" align="left">First OTU:</td>
						<td width="60px" align="right"><input align="right" type="text" size="5" name="sRow" value="'.$this->httpGetLine['sRow'].'"></td><td width = "30px"></td>
						<td width="150px" align="left">First Character:</td>
						<td width="60px" align="right"><input align="right" type="text" size="5" name="sCol" value="'.$this->httpGetLine['sCol'].'"></td>
						<td width = "30px"></td><td></td>
					</tr>
					<tr>
						<td width="80px" align="left">Characters:</td>
						<td width="60px" align="right">'.($this->numMatrixCols-1).'</td><td width = "30px"></td>
						<td width="120px" align="left">No. of OTUs:</td>
						<td width="60px" align="right"><input align="right" type="text" size="5" name="nRow" value="'.$this->httpGetLine['nRow'].'"></td><td width = "30px"></td>
						<td width="150px" align="left">No. of Characters:</td>
						<td width="60px" align="right"><input align="right" type="text" size="5" name="nCol" value="'.$this->httpGetLine['nCol'].'"></td>	
						<td width = "30px"></td><td><input type="submit" value="Submit"></td>				
					</tr>
				</table>
				</form>
			</div>
			<br>';
	}
	
	private function displayTopNavBar () { // 6 columns and 1 row
    	
		echo '<table class="outSideTable" border=0>
    		<tr>
    			<td align="left" width="30px">
    			    <a href="'.$this->getHttpGETLine_diagUpLeft().'row='.$hr.'&col='.$hc.'" title="diagonal move (Up+Left)">
    			    <img src="/style/webImages/UpperLeft.png" border="0" width="12px" alt="goToDiagonal" /></a>			
    			</td>
    			<td align="left" width="30px">
    				<a href="'.$this->getHttpGETLine_goToFirstCol().'" title="go to first column">
    				<img src="/style/webImages/goFirst2.png" border="0" alt="goToFirst" /></a>
    			</td>
    			<td align="left">
    				<a href="'.$this->getHttpGETLine_goToPreviousCol().'" title="previous column">
    				<img src="/style/webImages/backward-gnome.png" border="0" alt="back" /></a>
    			</td>
    			<td align="right">
    				<a href="'.$this->getHttpGETLine_goToNextCol().'" title="next column">
    				<img src="/style/webImages/forward-gnome.png" border="0" alt="foward" /></a>
    			</td>
    			<td align="right" width="30px">
    				<a href="'.$this->getHttpGETLine_goToLastCol().'" title="go to last column">
    				<img src="/style/webImages/goLast2.png"  border="0" alt="goToLast" /></a>
    			</td>
    			<td align="right" width="30px">
    				<a href="'.$this->getHttpGETLine_diagUpRight().'" title="diagonal move (Up+Right)">
    				<img src="/style/webImages/UpperRight.png" width="12px" border="0" alt="goToDiagonal" /></a>
    			</td>
    		</tr>
    	</table>';
	}
	
	private function displayBottomNavBar() { 
    	
		//$colWidth = round(($width-78)/2);
    	echo '<table class="outSideTable"
    		<tr>
    			<td align="left" width="30px">
    			    <a href="'.$this->getHttpGETLine_diagDownLeft().'" title="diagonal move (Down+Left)">
    			    <img src="/style/webImages/LowerLeft.png" width="12px" border="0" alt="goToDiagonal" /></a>			
    			</td>
    			<td align="left" width="30px">
    				<a href="'.$this->getHttpGETLine_goToFirstCol().'" title="go to first column"><img src="/style/webImages/goFirst2.png" border="0" alt="goToFirst" /></a>
    			</td>
    			<td align="left">
    				<a href="'.$this->getHttpGETLine_goToPreviousCol().'" title="previous column"><img src="/style/webImages/backward-gnome.png" border="0" alt="back" /></a>
    			</td>
    			<td align="right">
    				<a href="'.$this->getHttpGETLine_goToNextCol().'" title="next column"><img src="/style/webImages/forward-gnome.png" border="0" alt="foward" /></a>
    			</td>
    			<td align="right" width="30px">
    				<a href="'.$this->getHttpGETLine_goToLastCol().'" title="go to last column"><img src="/style/webImages/goLast2.png"  border="0" alt="goToLast" /></a>
    			</td>
    			<td align="right" width="30px">
    				<a href="'.$this->getHttpGETLine_diagDownRight().'" title="diagonal move (Down+Right)">
    				<img src="/style/webImages/LowerRight.png" width="12px" border="0" alt="goToDiagonal" /></a>
    			</td>
    		</tr>
    	</table>';
	}
	
	function displayMatrix() {
		 
		if ($this->charStageM == null) {
			echo '<div><h2>Matrix is null</h2></div>';
			return;			
		}
			
		$matrixObjects = $this->charStageM->getMatrixObjects( 	$this->httpGetLine['sCol']-1, //1 from the form is zero index
																$this->httpGetLine['sRow']-1, 
																$this->httpGetLine['nRow'],
																$this->httpGetLine['nCol']);
																
		//$matrixObjects = $this->charStageM->getMatrixObjects( $this->httpGetLine['sRow'], 
		//														$this->httpGetLine['sCol'], 
		//														$this->httpGetLine['nRow'],
		//														$this->httpGetLine['nCol']);
		$headers = $matrixObjects[0];
		$myMatrixContWidth = $width-80;
		echo '<div class="mainMyMatrixContainer" style="height:'.$height.'px;width=auto">';
		echo '<table class="manageMatrixTable" border="0" cellspacing="0" cellpadding="0">';
		// firs row (header)
		echo '<tr>
		       <th class="firstCell" align="left" ></th>'; //  first cell of the table
		for ($c=0; $c<count($headers); $c++) {
			$v = $headers[$c]->getObjectTitle();
			//$v = $headers[$c]->getShortTitle();
			$id = $headers[$c]->getObjectId();
			echo ' <th onMouseOver=this.style.backgroundColor="#bbbbff";this.style.cursor="hand"  onMouseOut=this.style.backgroundColor="#17256b"> 
						<a href="javascript:openPopup(\''.$this->linkClickingOnColumn.$id.'\')" title="Open character = '.$v.'">'. $v.'</a>
				   </th>';	
		}
		echo '</tr>';
		
   		// The rest of the rows
		$rowsNum = count($matrixObjects);
		for ($r=1; $r<$rowsNum; $r++)
			{
				$row = $matrixObjects[$r];
				$hr = $row[0]->getObjectTitle();
				$hrId = $row[0]->getObjectId();
				echo '<tr valign="bottom">';
				echo '   <td class="firstColumn" onMouseOver=this.style.backgroundColor="#FFEDCA";this.style.cursor="hand"  onMouseOut=this.style.backgroundColor="#08fff0"> 
							<a href="javascript:openPopup(\''.$this->linkClickingOnRow.$hrId.'\')" title="Open OTU = '.$hr.'">'. $hr.'</a> </td>'; // first column
				for ($c=1; $c<count($row); $c++) {
					$hc = $headers[$c-1]->getObjectTitle();
					if (isset($row[$c][value])) {
						if ($this->selectedOneCell && ($this->selectedRow == $hr) && ($this->selectedCol == $hc)) 
							$td = '<td class = "selected" >';
 						elseif (!($this->selectedOneCell) && ($this->selectedRow == $hr)) 
 							$td = '<td class = "selected" >';
 						elseif (!($this->selectedOneCell) && ($this->selectedCol == $hc))
 							$td = '<td class = "selected" >';
 						else 
 							$td='<td onMouseOver=this.style.backgroundColor="#FFEDCA";this.style.cursor="hand"  onMouseOut=this.style.backgroundColor="#FFFFFF">';			
						echo $td.'<a href="'.$this->createNavHttpGETLine(true).'row='.$hr.'&col='.$hc.'" 
								title="Select the ('.$hr.','.$hc.') cell">'.$row[$c][value].'</a>';
					}
					else
						echo '<td class="emptyCell"> --- ';
				
					echo '</td>';
				}
				echo '</tr>';
			}	
	
		echo '</table>
			</div>';	
    }
	
	function createNavHttpGETLine ($current = false) {
		$str = "";
		if ($current)
			$ar = $this->httpGetLine;
		else
			$ar = $this->cphttpGetLine;
		foreach ($ar as $key => $value) 
    		$str = $str.$key.'='.$value.'&';
    	
    	return $this->actionUrl.$str;
	}
	
	function createHttpSelectGETLine () {
		$str = "";
		if (isset($this->selectedRow)) {
			$str = $str.'row='.$this->selectedRow.'&';
		}
		if (isset($this->selectedCol)) {
			 $str = $str.'col='.$this->selectedCol.'&';
		}
		return $str;
	}
    
    private function getHttpGETLine_diagUpLeft() {
	    $this->cphttpGetLine = $this->httpGetLine;
	    $numRow = $this->cphttpGetLine['nRow'];
		$numCol = $this->cphttpGetLine['nCol'];
	    if ($numRow > 0) {
	    	if ($this->cphttpGetLine['sRow']>1) 
	    		--$this->cphttpGetLine['sRow'];
	  	} else 
    		$this->resetColRows();
    		
		if ($numCol > 0) {
	    	--$this->cphttpGetLine['sCol'];
	    	if ($this->cphttpGetLine['sCol']<=0) 
	    		$this->cphttpGetLine['sCol']=1;
	  	} else 
    		$this->resetColRows();
    	
        return $this->createNavHttpGETLine().$this->createHttpSelectGETLine();
	}
	
	private function getHttpGETLine_diagUpRight() {
		$this->cphttpGetLine = $this->httpGetLine;
	    $numRow = $this->cphttpGetLine['nRow'];
		$numCol = $this->cphttpGetLine['nCol'];
	    if ($numRow > 0) {
	    	if ($this->cphttpGetLine['sRow']>1) 
	    		--$this->cphttpGetLine['sRow'];
	  	} else 
    		$this->resetColRows();
    	if ($numCol > 0) {
		    if (($this->cphttpGetLine['sCol']+$numCol)<$this->numMatrixCols) {
	    		++$this->cphttpGetLine['sCol'];
    		}
    	} else 
    		$this->resetColRows();
    	return $this->createNavHttpGETLine().$this->createHttpSelectGETLine();
	}
	
	private function getHttpGETLine_diagDownLeft() {
		$this->cphttpGetLine = $this->httpGetLine;
		$numRow = $this->cphttpGetLine['nRow']; // current number of Rows to show
		$numCol = $this->cphttpGetLine['nCol'];
		if ($numRow > 0) {
		    if (($this->cphttpGetLine['sRow']+$numRow)<$this->numMatrixRows) {
	    		++$this->cphttpGetLine['sRow'];
    		}
    	} else 
    		$this->resetColRows();
		if ($numCol > 0) {
	    	--$this->cphttpGetLine['sCol'];
	    	if ($this->cphttpGetLine['sCol']<=0) 
	    		$this->cphttpGetLine['sCol']=1;
	  	} else 
    		$this->resetColRows();
    	
        return $this->createNavHttpGETLine().$this->createHttpSelectGETLine();
	}
	
	private function getHttpGETLine_diagDownRight() {
		$this->cphttpGetLine = $this->httpGetLine;
		$numRow = $this->cphttpGetLine['nRow']; // current number of Rows to show
		$numCol = $this->cphttpGetLine['nCol'];
		if ($numRow > 0) {
		    if (($this->cphttpGetLine['sRow']+$numRow)<$this->numMatrixRows) {
	    		++$this->cphttpGetLine['sRow'];
    		}
    	} else 
    		$this->resetColRows();
    	if ($numCol > 0) {
		    if (($this->cphttpGetLine['sCol']+$numCol)<$this->numMatrixCols) {
	    		++$this->cphttpGetLine['sCol'];
    		}
    	} else 
    		$this->resetColRows();
    	return $this->createNavHttpGETLine().$this->createHttpSelectGETLine();
	}
	
	private function getHttpGETLine_goToFirstCol() {
		$this->cphttpGetLine = $this->httpGetLine;
		$numCol = $this->cphttpGetLine['nCol'];
	    if ($numCol > 0)
	    	$this->cphttpGetLine['sCol'] = 1;
    	else 
    		$this->resetColRows();
    		
    	return $this->createNavHttpGETLine().$this->createHttpSelectGETLine();
	}
	
	private function getHttpGETLine_goToFirstRow() {
		$this->cphttpGetLine = $this->httpGetLine;
		$numRow = $this->cphttpGetLine['nRow'];
	    if ($numRow > 0)
	    	$this->cphttpGetLine['sRow'] = 1;
    	else 
    		$this->resetColRows();
    		
    	return $this->createNavHttpGETLine().$this->createHttpSelectGETLine();
	}
	
	private function getHttpGETLine_goToPreviousCol() {
		$this->cphttpGetLine = $this->httpGetLine;
		$numCol = $this->cphttpGetLine['nCol'];
	    if ($numCol > 0) {
	    	--$this->cphttpGetLine['sCol'];
	    	if ($this->cphttpGetLine['sCol']<=0) 
	    		$this->cphttpGetLine['sCol']=1;
	  	} else 
    		$this->resetColRows();
    	
    	return $this->createNavHttpGETLine().$this->createHttpSelectGETLine();
	}
	
	private function getHttpGETLine_goToPreviousRow() {
		$this->cphttpGetLine = $this->httpGetLine;
		$numRow = $this->cphttpGetLine['nRow'];
	    if ($numRow > 0) {
	    	if ($this->cphttpGetLine['sRow']>1) 
	    		--$this->cphttpGetLine['sRow'];
	  	} else 
    		$this->resetColRows();
    	
    	return $this->createNavHttpGETLine().$this->createHttpSelectGETLine();
	}
	
	private function getHttpGETLine_goToNextCol() {
		$this->cphttpGetLine = $this->httpGetLine;
		$numCol = $this->cphttpGetLine['nCol']; // current number of Cols to show
		if ($numCol > 0) {
		    if (($this->cphttpGetLine['sCol']+$numCol)<$this->numMatrixCols) {
	    		++$this->cphttpGetLine['sCol'];
    		}
    	} else 
    		$this->resetColRows();
    	return $this->createNavHttpGETLine().$this->createHttpSelectGETLine();
	}
	
	private function getHttpGETLine_goToNextRow() {
		$this->cphttpGetLine = $this->httpGetLine;
		$numRow = $this->cphttpGetLine['nRow']; // current number of Rows to show
		if ($numRow > 0) {
		    if (($this->cphttpGetLine['sRow']+$numRow)<$this->numMatrixRows) {
	    		++$this->cphttpGetLine['sRow'];
    		}
    	} else 
    		$this->resetColRows();
    	return $this->createNavHttpGETLine().$this->createHttpSelectGETLine();
	}
	
	private function getHttpGETLine_goToLastCol() {
		$this->cphttpGetLine = $this->httpGetLine;
		$numCol = $this->cphttpGetLine['nCol']; // current number of Col to show
		if ($numCol > 0) 
		    $this->cphttpGetLine['sCol'] = $this->numMatrixCols-$numCol;
	   	else 
    		$this->resetColRows();
    	return $this->createNavHttpGETLine().$this->createHttpSelectGETLine();
	}
	
	private function getHttpGETLine_goToLastRow() {
		$this->cphttpGetLine = $this->httpGetLine;
		$numRow = $this->cphttpGetLine['nRow']; // current number of Col to show
		if ($numRow > 0) 
		    $this->cphttpGetLine['sRow'] = $this->numMatrixRows-$numRow;
	   	else 
    		$this->resetColRows();
    	return $this->createNavHttpGETLine().$this->createHttpSelectGETLine();
	}	 	
} // end class
?>
