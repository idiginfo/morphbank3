<?php

class charStageMatrix {
	private $sql;
	public $title;
	private $domainName;
	private $actionUrl;
	private $selectedCol;
	private $selectedRow;
	private $selectedOneCell;
	
	private $charStageM;
		
	// function to use if we want to generate the two dimensional array base on a SQL query
	function setMatrixFromSql() { 
		$sql = "";
	}
	
	function setTitle( $newTitle = "") {
		$this->title = $newTitle; 
	}
	
	function getDomainName() {
		return $this->domainName;
	}
	
	function setDomainName ( $myDomain = "http://morphbank4.scs.fsu.edu") {
		$this->domainName = $myDomain;
		$this->actionUrl = $this->domainName.'MyMatrices/index.php?pop=NO';
	}
	
	function __construct ( $matrix) {
		$this->setCharStageMatrix( $matrix);
		$this->retrieveDataFromGET();
	}
	
	function __destruct() {
       $this->charStageM = null;
    }

	function setCharStageMatrix( $matrix) {
		$this->charStageM = $matrix;
	}
	
	private function retrieveDataFromGET () {
		$this->selectedCol = null;
		$this->selectedRow = null;
		$this->selectedOneCell = false;
		if (isset($_GET['col'])) {
			$this->selectedCol = $_GET['col'];
			//print $this->selectedCol.'<br>';
		}
		if (isset($_GET['row'])) {
			$this->selectedRow = $_GET['row'];
			//print $this->selectedRow.'<br>';
		}
		if ( $this->selectedRow && $this->selectedCol) {
			$this->selectedOneCell = true;
			//print $this->selectedOneCell;
		}
	}
	
	function display() {
		$rows = array_keys($this->charStageM);
		$rowsNum = count($rows);
		$cols = array(); 
		for ($i=0; $i<$rowsNum; $i++)
		{
			$rowcols = array_keys($this->charStageM[$rows[$i]]);
			if (!empty($rowcols))
				$cols = array_merge($cols, $rowcols);
		}
		$colsAux = array_unique($cols);
		$colsNum = count($colsAux);
		
		// be sure the keys are continue 1,2, ... n
		$cols = array();
		reset($colsAux);
		for ($c=0; $c<$colsNum; $c++) {
			list($key,$value) = each($colsAux);
			$cols[$c] = $value; 
		}
		
		// Ready to display
		echo '<div style="float:left;"> <h1>'.$this->title.'</h1></div><br /><br /><br />
				<div class="mainMyMatrixContainer">';
		echo '<table class="manageMatrixTable" width="100%" border="0" cellspacing="0" cellpadding="0">';
		// firs row (header)
		echo '<tr>
		       <th class="firstCell"></th>'; //first cell of the table
		for ($c=0; $c<$colsNum; $c++) {
			echo ' <th> <a href="'.$this->actionUrl.'&col='.$cols[$c].'" title="Select the '.$cols[$c].' column">'. $cols[$c].'</a></th>';	
		}
		echo '</tr>';
		for ($r=0; $r<$rowsNum; $r++)
		{
			echo '<tr valign="bottom">';
			echo '   <td class="firstColumn"> <a href="'.$this->actionUrl.'&row='.$rows[$r].'" title="Select the '.$rows[$r].' row">'. $rows[$r].'</a> </td>'; // first column
			for ($c=0; $c<$colsNum; $c++) {
				
				if (isset($this->charStageM[$rows[$r]][$cols[$c]])) {
					if ($this->selectedOneCell && ($this->selectedRow == $rows[$r]) && ($this->selectedCol == $cols[$c])) 
						$td = '<td class = "selected" ';
					elseif (!($this->selectedOneCell) && ($this->selectedRow == $rows[$r])) 
						$td = '<td class = "selected" ';
					elseif (!($this->selectedOneCell) && ($this->selectedCol == $cols[$c]))
						$td = '<td class = "selected" ';
					else 
						$td='<td ';			
					echo $td.'<a href="'.$this->actionUrl.'&row='.$rows[$r].'&col='.$cols[$c].'" 
									title="Select the ('.$rows[$r].','.$cols[$c].') cell">'.$this->charStageM[$rows[$r]][$cols[$c]].'</a>';
				}
				else
					echo '<td class="emptyCell"> --- ';
				
				echo '</td>';
			}
			echo '<tr>';
			
		}
		echo '</table>
				</div>';	
	}
	
	// It is not used, so far 
	function echoJSFuntions() {
		echo '
			<script language="JavaScript" type="text/javascript">
			<!--
								
			//-->
			</script>';
	}
	 	
} // end class

?>
