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
/**
 File name: editAngle.php
 @author Neelima Jammigumpula <jammigum@scs.fsu.edu>
 @package Morphbank2
 @subpackage Submit
 @subpackage Edit
 @subpackage ViewAngle

 Included Files: editChanged.php
 This has a simple function editChanged($numRows) that creates the javascript function based on
 the number of rows returned from the query which is passed to it.
 This script has only one function editAngle that displays the GUI and form data from the database.
 **/

function editAngle() {
	//These are declared global so that they can be used anywhere in the function. These are defined in config.php
	

	if ($_GET['offset']) {
		$offset = $_GET['offset'];
	} else {
		$offset = 0;
	}
	// Gets no. of records from the table.
	$totalNumRows = mysqli_num_rows(runQuery("SELECT name as 'Angle', description as 'Description' FROM ViewAngle ORDER BY name;"));

	$result = runQuery('SELECT name AS Angle, description AS Description FROM ViewAngle ORDER BY name LIMIT ' . $offset . ', 20;');
	$numRows = mysqli_num_rows($result);

	echo '<form  name = "editAngle" method = "post" action = "modifyAngle.php" onsubmit = "return checkall()" >
    <h1><b>Edit View Angle</b></h1>
          <br /><br />';

	//*****************************************************************************
	// This section goes through each column and prints it out to the screen with *
	// that value as taken from the database.                                     *
	//*****************************************************************************

	/* Hidden inputs
	 rows: Holds the number of rows or records value which is used in modifyAaa scripts for update queries.
	 idXX: Keeps the id of each record which will be used in modifyAaa script.
	 changedXX: This is used to keep tract of the changed records.
	 A javascript function changed(change) manipulates this form input as required.
	 */

	if ($result) {
		echo '<input type="hidden" name="rows" value="' . $numRows . '" />
      <input type="hidden" name="flag" value = "false"/>
          <table width="600"> <tr><td><b>View Angle <span class="req">*</span></b></td>
        <td> <b> Description <span class="req">*</span></b></td></tr>';

		for ($i = 0; $i < $numRows; $i++) {
			mysqli_data_seek($result, $i) or die(mysqli_error($link));
			$row = mysqli_fetch_array($result, MYSQL_ASSOC);
			echo '<tr>';
			foreach ($row as $column => $val) {
				$META = mysqli_fetch_field($result);
				$SIZE = $META->max_length;

				if ($column == "Angle") {
					echo '<td><input type="hidden" name="id' . $i . '" value="' . $val . '" />
										<input type="text" size ="40" name="' . $column . $i . '" value="' . $val . '" onchange = "changed()" /></td>';
				} else {
					echo '<td><input type="text" size ="40" name="' . $column . $i . '" value="' . $val . '" onchange = "changed()"/> </td>';
				}
			}
			echo '</tr>';
		}
	} else {
		print "No records Found";
	}
	// cleanup
	freeResult($result);
	echo '</table><br /><br/><strong><span class="req">* -Required</span></strong>';
	echo '<table width="500"><tr>';
	echo '<td align="right"><a href="javascript: checkall()" class="button smallButton">';
	echo '<div>Update</div></a>';
	echo '<a href="javascript: top.location = \'../\'" class="button smallButton">';
	echo '<div>Return</div></a></td></tr></table>';
	if ($totalNumRows > 20) {
		Navigation($totalNumRows, $offset);
	}
	echo '</form>';
	include_once('editjavascript.php');
	javascript($numRows);
}

function Navigation($numRows, $offset)
{
	
	echo '<hr/><table width="500"><tr><td width="100">&nbsp;</td>';
	echo '<td><a href="index.php?offset=0"> <img src="/style/webImages/goFirst2.png"';
	echo 'alt="First 20 Records" title="First 20 Records" border="0"></a></td>';
	if ($offset >= 20) {
		echo '<td><a href="index.php?offset=<?= $offset-20; ?>">';
		echo '<img src="/style/webImages/backward-gnome.png" border="0"></a></td>';
	}
	echo '<td>&nbsp;</td>';
	if ($offset < ($numRows - 20)) {
		echo '<td><a href="index.php?offset=<?= $offset+20;?>">';
		echo '<img src="/style/webImages/forward-gnome.png" border="0"></a></td>';
	}
	echo '<td>&nbsp;&nbsp;</td>';
}
?>
