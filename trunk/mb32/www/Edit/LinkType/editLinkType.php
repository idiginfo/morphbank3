<?php
/**
 File name: editLinkType.php
 @author Neelima Jammigumpula <jammigum@scs.fsu.edu>
 @package Morphbank2
 @subpackage Submit
 @subpackage Edit
 @subpackage LinkType
 Included Files: editjavascripts.php
 This has a simple function javascript($numRows) that creates the javascript function based on
 the number of rows returned from the query which is passed to it.

 This script has only one function editLinkType that displays the GUI and form data from the database.
 **/

function editLinkType()
{
	//These are declared global so that they can be used anywhere in the function. These are defined in config.php
	

	if ($_GET['offset'])
	$offset = $_GET['offset'];
	else
	$offset = 0;

	// Get total number of records from the table for navigation icons.
	$totalNumRows = mysqli_num_rows(runQuery('SELECT name AS LinkType, description AS Description,
		linkTypeId AS id FROM ExternalLinkType ORDER BY name;'));

	// Get 20 records at a time from a given offset.
	$result = runQuery('SELECT name AS LinkType, description AS Description,
	 linkTypeId AS id FROM ExternalLinkType ORDER BY name LIMIT ' . $offset . ', 20;');

	$numRows = mysqli_num_rows($result);

	echo '<form name = "editLinkType" method = "post" action = "modifyLinkType.php" onsubmit = "return checkall()" >
	<h1><b>Edit External Link Types</b></h1>
		 <br /><br />';

	//*****************************************************************************
	// This section goes through each column and prints it out to the screen with *
	// that value as taken from the database.			 *
	//*****************************************************************************

	/* Hidden inputs

	rows: Holds the number of rows or records value which is used in modifyAaa scripts for update queries.
	idXX: Keeps the id of each record which will be used in modifyAaa script.
	A javascript function changed() manipulates this form input as required.
	*/

	if ($result) {
		echo '<input type="hidden" name="rows" value="' . $numRows . '" />
		 <input type="hidden" name="flag" value="false" />
		 <table width="600"> <tr><td><b>Link Type <span class="req">*</span></b></td>
		<td> <b> Description <span class="req">*</span></b></td></tr>';

		for ($i = 0; $i < $numRows; $i++) {
			mysqli_data_seek($result, $i) or die(mysqli_error());
			$row = mysqli_fetch_array($result, MYSQL_ASSOC);

			echo '<tr>';

			foreach ($row as $column => $val) {
				$META = mysqli_fetch_field($result);
				$SIZE = $META->max_length;

				if ($column == "id") {
					echo '<td width = "10%"><input type="hidden" name="id' . $i . '" value="' . $row['id'] . '" /> </td>';
				} else
				echo '<td><input type="text" size ="30" name="' . $column . $i . '" value="' . $val . '" onchange = "changed()" /> </td>';
			}
			echo '</tr>';
		}
	} else {
		print "No records Found";
	}
	// cleanup
	freeResult($result);
	echo '</table><br/><br/><strong><span class="req">* -Required</span>
	</strong>
	<table width="500"><tr><td align = "right"> 
		<a href = "javascript: checkall()" class="button smallButton"><div>Update</div> </a>
		<a href = "javascript: top.location = \'../\'" class="button smallButton"><div>Return</div> </a>
	</td></tr></table>';

	if ($totalNumRows > 20)
	Navigation($totalNumRows, $offset);
	echo '</form>';


	include_once('editjavascript.php');
	javascript($numRows);
}

function Navigation($numRows, $offset)
{
	
	echo '<hr /><table width="500"><tr>
			 <td width="100">&nbsp;</td>
			 <td><a href="index.php?offset=0" >
			 <img src="/style/webImages/goFirst2.png" 
			 alt = "First 20 Records" title = "First 20 Records" border="0">
			 </a></td>';
	if ($offset >= 20) {
		echo "<td><a href=\"index.php?offset=<?= $offset-20; \">
		<img src=\"/style/webImages/backward-gnome.png\" border=\"0\"></a></td>";
	}
	echo "<td>&nbsp;</td>";
	if ($offset < ($numRows - 20)) {
		echo "<td><a href=\"index.php?offset=$offset+20\">
		<img src=\"/style/webImages/forward-gnome.png\"	border=\"0\"></a></td>";
	}
	echo "<td>&nbsp;&nbsp;</td><td><a href=\"index.php?offset=$numRows-20\">
		<img src=\"/style/webImages/goLast2.png\" border=\"o\"></a></td>
		</tr></table><hr />";
}
