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
/***************************************************************************************																			*
 *																												*
 *Date modified: Mar 22nd 2006																			*
 *Modified by: Karolina Maneva-Jakimoska																*
 ***************************************************************************************/
/****************************************************************************************
 *																													*
 * Routine/Module Name: printtaxon																		*
 *																													*
 * Parameter Description: $tsn: Taxonomic Serial Number, Key value in the TaxonomicUnit *
 *	table.																									*
 *																													*
 * Description: Used in the Taxonomic Search routine to generate an html table to		*
 *	select taxonomic serial numbers for use in finding those values.						*
 *																													*
 * Author: David A. Gaitos, MorphBank Project Director											*
 * Date: Dec 13, 2005																						*
 ****************************************************************************************/
// **************************************************************************************
// * This function accepts as input a TSN number. It finds the rank and kingdom ids	*
// * associated with that taxon and discovers what type of taxon it is. It then precedes*
// * to determine the following taxon in that all have the same required parent. It	*
// * builds an arrray of those taxa.																	*
// **************************************************************************************
function printtaxon($link, $tsn) {
	$query = 'select * from Tree where tsn =' . trim($tsn);
	$results = mysqli_query($link, $query);
	$row = mysqli_fetch_array($results);
	$rank_id = $row['rank_id'];
	$kingdom_id = $row['kingdom_id'];
	// ****************************************************************************************
	// * Get the next records in the Tree that have the rank_id one higher and order*
	// * them by the Rank_id.	We must be careful here. If a person is adding a major		*
	// * Taxonomic category such Phylum, Class, Order, Family, Genus, or Species then they	*
	// * can only add that category. Only categories can be added that have the same			*
	// * Taxonomic required parent.																			*
	// ****************************************************************************************
	if ($rank_id == 30 || $rank_id == 60 || $rank_id == 100 || $rank_id == 140
	|| $rank_id == 180 || $rank_id == 220) {
		$logical_Operator = ">";
	} else {
		$logical_Operator = ">=";
	}

	$query = "select * from TaxonUnitTypes where rank_id $logical_Operator $rank_id and kingdom_id =$kingdom_id order by rank_id";
	$results1 = mysqli_query($link, $query);
	$numrows = mysqli_num_rows($results1);
	$row1 = mysqli_fetch_array($results1);
	//$numrows = mysqli_num_rows($results1);

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
		$row1 = mysqli_fetch_array($results1);
		$returnTaxonTypes[1]['rank_id'] = $row1['rank_id'];
		$returnTaxonTypes[1]['kingdom_id'] = $row1['kingdom_id'];
		$returnTaxonTypes[1]['rank_name'] = $row1['rank_name'];
		$returnTaxonTypes[1]['req_parent_rank_id'] = $row1['req_parent_rank_id'];
		$req_parent = $row1['req_parent_rank_id'];
	}
	$counter = 2;
	if ($numrows > 2) {
		$row1 = mysqli_fetch_array($results1);
		while ($numrows > $counter && $req_parent == $row1['req_parent_rank_id']) {
			$returnTaxonTypes[$counter]['rank_id'] = $row1['rank_id'];
			$returnTaxonTypes[$counter]['kingdom_id'] = $row1['kingdom_id'];
			$returnTaxonTypes[$counter]['rank_name'] = $row1['rank_name'];
			$returnTaxonTypes[$counter]['req_parent_rank_id'] = $row1['req_parent_rank_id'];
			$counter++;
			$row1 = mysqli_fetch_array($results1);
		}
	}
	echo '<tr><td><b>Rank Identification: <span style="color:red">*</span></b></td><td><select name="rank_id" size =".$SIZE." >';
	$counter = $counter - 1;
	for ($index = 0; $index <= $counter; $index++) {
		echo '<option value ="';
		echo $returnTaxonTypes[$index]['rank_id'];
		echo '"';
		if ($rank_id == 180 && $returnTaxonTypes[$index]['rank_id'] == 220)
		echo 'selected="selected"';
		echo '>';
		echo $returnTaxonTypes[$index]['rank_name'];
		echo '</option>';
	}
	echo '</select></td></tr>';
}
/****************************************************************************************
 *																													*
 * Routine/Module Name: findParentTaxon																*
 *																													*
 * Parameter Description: $tsn: Taxonomic Serial Number										*
 *																													*
 * Description: Many times when we are doing searches of the taxonomic tree we will	*
 *	often need to know the direct parent of the current taxonomic unit. We obtain	*
 *	the information by getting the current record pointed to by the parameter $tsn	*
 *	get the $parenttsn. We then obtain that record and extract the tsn, all four		*
 *	names, the rank_id and the kingdom_id and return this information in the form		*
 *	of an array back to the calling routine.														*
 *																													*
 * Author: David A. Gaitos, MorphBank Project Director											*
 * Date: Sept 23, 2005																						*
 ****************************************************************************************/
function FindParentTaxon($link, $tsn) {
	$query = "select * from Tree where tsn=" . $tsn . ";";
	//echo $query;
	$results = mysqli_query($link, $query);
	$row = mysqli_fetch_array($results);
	$parenttsn = $row['parent_tsn'];
	$query = "select * from Tree where tsn =" . $parenttsn . ";";
	//echo $query;
	$results = mysqli_query($link, $query);
	if ($row) {
		$row = mysqli_fetch_array($results);
		$returnparent['tsn'] = $row['tsn'];
		$returnparent['name'] = trim($row['scientificName']);
		$returnparent['rank_id'] = $row['rank_id'];
		$returnparent['kingdom_id'] = $row['kingdom_id'];
	} else
	echo "No results returned";
	return $returnparent;
}

/****************************************************************************************
 *																													*
 * Routine/Module Name: hasChildren																	*
 *																													*
 * Parameter Description: $tsn: Taxonomic Serial Number										*
 *																													*
 * Description: A routine to see if a Taxonomic Serial Number is the parent of any	*
 *	other node. Used in searching Taxonomic trees to see if we display an option		*
 *	to go lower in the tree.																				*
 *																													*
 * Author: David A. Gaitos, MorphBank Project Director											*
 * Date: Jan 23, 2006																						*
 ****************************************************************************************/
function hasChildren($link, $tsn) {
	$query = "select * from Tree where parent_tsn = " . $tsn;
	$results = mysqli_query($link, $query);
	$numrows = mysqli_num_rows($results);
	if ($numrows > 0)
	return 1;
	else
	return 0;
}
/****************************************************************************************
 *																													*
 * Routine/Module Name: IsNotAdmin																		*
 *																													*
 * Parameter Description: $id: The user id															*
 *																													*
 * Description: Checks to see if a users is a MorphBank Administrator. Should have	*
 *	a Privilege TSN of 0.																				*
 *																													*
 * Author: David A. Gaitos, MorphBank Project Director											*
 * Date: Dec 13, 2005																						*
 ****************************************************************************************/

function IsNotAdmin($link, $id) {
	$results = mysqli_query($link, "select * from User where id=" . $id);
	echo mysqli_error();
	if (!results) exit;
	$row = mysqli_fetch_array($results);
	if ($row['privilegetsn'] == 0 || $row['altprivilegetsn'] == 0) {
		return 0;
	}
	return 1;
}
/********************************************************************************************
 * Function TSNinGroup is a routine where we pass in two TSN numbers. The first is the		*
 * the Group TSN and the second is that assigned as the Privilege TSN of the user.			*
 * The idea is to find if the user privilege tsn is with the lineage of the Group TSN or	*
 * equal to it. Also, if a use has a privilege TSN of 0 they are authorized the entire	*
 * Taxon structure.																								*
 ********************************************************************************************/
/****************************************************************************************
 *																													*
 * Routine/Module Name: TSNinGroup																		*
 *																													*
 * Parameter Description: $grouptsn: The Taxonomic Serial Number associated with a group*
 *								$usertsn: The Taxonomic Serial Number assigned to a user.	*
 *																													*
 * Description: This routine grew out of a need to make sure that a user who is being	*
 *	being considered for membership into a group is authorized. The basic idea		*
 *	is that a user must have at least the same Taxonomic Serial Number as the Group	*
 *	or be one of the parent links going up the tree, or be an administrator TSN=0.	*
 *	The search up the tree continues until either a match of the $usertsn or the		*
 *	Kingdom level is found with no match. Return=1 means they are authorized, return *
 *	=0 means they are not.																				*
 *																													*
 * Author: David A. Gaitos, MorphBank Project Director											*
 * Date: Dec 13, 2005																						*
 ****************************************************************************************/
function TSNinGroup($link, $grouptsn, $usertsn)
{
	return true; // cut through the red tape GR June 09
	//		if ($usertsn == 0)
	//			return 1;
	//		if ($usertsn == $grouptsn)
	//			return 1;
	//
	//		$query = 'select * from Tree where tsn=' . $grouptsn;
	//		$results = mysqli_query($link, $query);
	//		$numrows = mysqli_num_rows($results);
	//		if ($numrows < 1)
	//			return 0;
	//		$row = mysqli_fetch_array($results);
	//		while ($row['rank_id'] != 10) {
	//			$tsn = $row['parent_tsn'];
	//			$query = 'select * from Tree where tsn=' . $row['parent_tsn'];
	//			$results = mysqli_query($link, $query);
	//			if ($results)
	//				$numrows = mysqli_num_rows($results);
	//			else
	//				return 0;
	//			if ($numrows < 1)
	//				return 0;
	//			$row = mysqli_fetch_array($results);
	//			if ($usertsn == $row['tsn'])
	//				return 1;
	//			else
	//				continue;
	//		}
	//		return 0;
}

/****************************************************************************************
 *																													*
 * Routine/Module Name: UserinGroup																	*
 *																													*
 * Parameter Description: $groupid: The primary key value of the Groups table to be	*
 *	searched.																								*
 *								$userid: The primary key value of the `user` table.			*
 *																													*
 * Description: This routine checks to see if the user identified by the $userid		*
 *	parameter is a member of the group identified by the $groupid parameter.			*
 *	This is done by doing a query on the UserGroup table to see if there is a valid	*
 *	returned value. Return =1 means the person exists in the group, Return=0 means	*
 *	a record was not found.																			*
 *																													*
 * Author: David A. Gaitos, MorphBank Project Director											*
 * Date: Sept 23, 2005																						*
 ****************************************************************************************/
function UserinGroup($groupid, $userid)
{
	$db = connect();
	$sql = "select userGroupRole from UserGroup where user = ? and groups = ?";
	$role = $db->getOne($sql, null, array($userid, $groupid));
	return $role;
}

function SeekLastName($link, $char)
{
	$results = mysqli_query($link, 'select * from User order by last_Name, first_Name');
	$numrows = mysqli_num_rows($results);
	for ($index = 0; $index < $numrows; $index++) {
		$row = mysqli_fetch_array($results);
		$Last_Name = $row['last_Name'];
		if (substr($Last_Name, 0, 1) >= substr($char, 0, 1))
		return $index;
	}
	return $numrows - 1;
}

/****************************************************************************************
 *																													*
 * Routine/Module Name: selectUsers																	*
 *																													*
 * Parameter Description: $groupID: Primary key of the group									*
 *								$Listofusers: An array of User table id's of people who were *
 *									selected in this routine to belong to this group.			*
 *																													*
 * Description: Pass in the Group id and show all Morphbank users. Build a table of	*
 *		scrolling names to select/deslect to belong in the group and pass back to the	*
 *		calling routine.																					*
 *																													*
 * Author: David A. Gaitos, MorphBank Project Director											*
 * Date: Sept 23, 2005																						*
 * Modified: Dec 1, 2005: Need for this routine was removed given the requirement	*
 *		limit membership in groups to only those people with valid privilege TSNs.		*
 ****************************************************************************************/
function selectUsers($groupID, $listofusers) {
	$link = Adminlogin();
	$results = mysqli_query($link, "select * from User where accountstatus = true order by last_Name;");
	$numrows = mysqli_num_rows($results);
	if ($numrows == 0) exit;
	echo '<tr title="Press CTL key to select/deselect users">
		<td><b>MorphBank User</b></td>
		<td><select name=userid[] MULTIPLE SIZE=8>';
	for ($index = 0; $index < $numrows; $index++) {
		$row = mysqli_fetch_array($results);
		if (UserinGroup($groupID, $row['id']) == 1) {
			$Selected = " selected ";
			$listofusers[$index] = $row['id'];
		} else
		$Selected = " ";
		echo "<option value=" . $row['id'] . $Selected . ">" . $row['name'] . "</option>";
	}
	echo '</selected></td></tr>';
}


function GetLowestTaxonname($link, $tsn) {
	$results = mysqli_query($link, 'select * from Tree where tsn=' . $tsn);
	if (!$results)
	return "Life_on_Earch";
	$row = mysqli_fetch_array($results);
	if ($row['unit_name4'] != "")
	return $row['unit_name4'];
	elseif ($row['unit_name3'] != "")
	return $row['unit_name3'];
	elseif ($row['unit_name2'] != "")
	return $row['unit_name2'];
	else
	return $row['unit_name1'];
}


/****************************************************************************************
 *																													*
 * Routine/Module Name: IDincurrlist																	*
 *																													*
 * Parameter Description: $id: User table primary key value.									*
 *								$currlist: Array of User primary keys.							*
 * Description: Searches a an arrary $currlist to see if the $id passed is in that list.*
 *	This routine is used to determine if users were added or deleted from a group		*
 *	membership.																								*
 *																													*
 * Author: David A. Gaitos, MorphBank Project Director											*
 * Date: Dec 13, 2005																						*
 ****************************************************************************************/
function IDincurrlist($id, $currlist) {
	$index = sizeof($currlist);
	for ($counter = 0; $counter < $index; $counter++) {
		if ($id == $currlist[$counter])
		return 1;
	}
	return 0;
}

/****************************************************************************************
 * Pass in the $id of the user and the $group number and check if this person is the	*
 * group coordinator. Simply, the $id will match the groupManagerId field.				*
 *****************************************************************************************/
function groupCoordinator($id, $group) {
	$db = connect();
	$sql = "select groupManagerId from Groups where id = ?";
	$groupManagerId = $db->getOne($sql, null, array($group));
	return $id == $groupManagerId ? 1 : 0;
}
// ******************************************************************************************
// * Pass into this routine the currlist of users that have been selected in the			*
// * update of TaxonGroup screen. Basically we have selected those individuals we wish	*
// * to belong to a group and have this routine delete those individuals that do not		*
// * exist in the $currlist group but are in the database and add those that are in the	*
// * list but not in the database.																			*
// ******************************************************************************************

function updateUserGroup($currlist, $currroles, $roleids, $groupId) {
	global $objInfo;
	
	$db = connect();
	$sql = "select * from UserGroup where groups = ?";
	$rows = $db->getAll($sql, null, array($groupId), null, MDB2_FETCHMODE_ASSOC);
	isMdb2Error($rows, "Select information from UserGroup");
	$numrows = count($rows);
	$numlist = 0;

	foreach ($rows as $row) {
		$UserGroupid[$numlist++] = $row['user'];
		if (IDincurrlist($row['user'], $currlist) == 0 && !GroupCoordinator($row['user'], $groupId)) {
			$sql = "delete from UserGroup where user = '".$row['user']."' and groups = $groupId and userGroupRole <> 'coordinator'";
			echo "$sql<br />";
			$result = $db->exec($sql);
			isMdb2Error($result, "Error deleting user from group");
			
			$sql = "insert into History (id, userId, groupId, dateModified, modifiedFrom, modifiedTo, tableName) " 
					. "values (".$row['user'].",".$objInfo->getUserId().",".$objInfo->getUserGroupId().",NOW(),"
					. "'user: ".$row['user']."',' removed from group: ".$groupId."','UserGroup')";
			echo "$sql<br />";
			$result = $db->exec($sql);
			isMdb2Error($result, "Error inserting history for delete");
		}
		exit;
	}

	// ******************************************************************************************
	// * If the User Id isn't in the current list of users selected by the Group/User screen,	*
	// * then we can assume that this is a new user that has been added to the group and we	*
	// * create an insert SQL statement that places this person in the UserGroup table			*
	// * associated with the the $groupId.																		*
	// ******************************************************************************************
	$numlist = sizeof($currlist);
	$roleindex = 0;
	for ($index = 0; $index < $numlist; $index++) {
		if (IDincurrlist($currlist[$index], $UserGroupid) == 0) {
			$sql = 'Insert into UserGroup (user, groups, userId, dateLastModified, dateCreated, dateToPublish ';
			$sql .= ', userGroupRole) values ( ';
			$sql .= $currlist[$index] . ',';
			$sql .= $groupId . ',';
			$sql .= $objInfo->getUserId() . ',NOW(),NOW(),NOW(),"guest")';
			echo "$sql<br />";
			$result = $db->exec($sql);
			isMdb2Error($result, "Error inserting into UserGroup");			
		}
		// ******************************************************************************************
		// * This is where we modify the roles of the people who are in the group. Since we		*
		// * do not know at this time which ones were added we just update them all. Note here	*
		// * that the minimum privilege of anyone in a group is "guest" so if for some reason		*
		// * a role was not selected we default to "guest".													*
		// ******************************************************************************************
		$curr_role = UserinGroup($groupId, $currlist[$index]);
		if ($curr_role != "") {
			while ($currlist[$index] != $roleids[$roleindex]) {
				$roleindex++;
			}
			if ($currroles[$roleindex] == "None" || $currroles[$roleindex] == "none") {
				$currroles[$roleindex] = "guest";
			}
			$sql = 'update UserGroup set userGroupRole = ';
			$sql .= '"' . $currroles[$roleindex] . '"';
			$sql .= ' where user=' . $currlist[$index];
			$sql .= ' and groups = ' . $groupId;
			echo "$sql<br />";
			$result = $db->exec($sql);
			isMdb2Error($result, "Error updating UserGroup");	
			if ($currroles[$roleindex] != $curr_role) {
				$sql = "insert into History (id,userId,groupId,dateModified,modifiedFrom,modifiedTo,tableName) values (";
				$sql .= $currlist[$index] . "," . $objInfo->getUserId() . "," . $objInfo->getUserGroupId() . ",NOW(),";
				$sql .= "'userRole: " . $curr_role . "',' userRole: " . $currroles[$roleindex] . "','UserGroup')";
				echo "$sql<br />";
				$result = $db->exec($sql);
				isMdb2Error($result, "Error inserting History for updating UserGroup");
			}
		}
	}
}

function printNav($rowNum, $numRows) {
	
	$rownumber = $rowNum + 1;
	?>
<table width="500">
	<tr>
		<td width="100">&nbsp;</td>
		<td><a href="index.php?row=0" title="First Record"><img
			src="/style/webImages/goFirst.png" border="0"
			alt="goFirst" /></a></td>
			<?php
			if ($rowNum > 0) {
				?>
		<td><a href="index.php?row=<?php
			echo $rowNum - 1;
?>"
			title="Previous Record"><img
			src="/style/webImages/backward-gnome.png" border="0"
			alt="Previous" /></a></td>
			<?php
			}
			echo '<td>' . $rownumber . '</td>';
			if ($rowNum < $numRows - 1) {
				?>
		<td><a href="index.php?row=<?php
			echo $rowNum + 1;
?>"
			title="Next Record"><img
			src="/style/webImages/forward-gnome.png" border="0"
			alt="Next" /></a></td>
			<?php
			}
			?>
		<td><a href="index.php?last=1" title="Last Record"><img
			src="/style/webImages/goLast.png" border="0"
			alt="goLast" /></a></td>
		<td width="100">of <?php
		echo $numRows
		?></td>
	</tr>
</table>
		<?php
}

/****************************************************************************************
 *																													*
 * Routine/Module Name: printaccountstatus															*
 *																													*
 * Parameter Description: $val: Indicates if an option for selection or not selection	*
 *	was turned on by the User account screen.													*
 *																													*
 * Description: Displays the account status of a user on the User Add/Update screen.	*
 *	Assumes that this routine is used in conjunction with a table and has two columns.*
 *																													*
 * Author: David A. Gaitos, MorphBank Project Director											*
 * Date: Sept 10, 2005																						*
 ****************************************************************************************/
function printaccountstatus($val) {
	if ($val == 1) {
		$aselected = ' selected="selected" ';
		$dselected = ' ';
		$valtext = 'true';
	} else {
		$aselected = ' ';
		$dselected = ' selected="selected" ';
		$valtext = 'false';
	}
	echo '<tr><td><b>Account Status: </b></td>
 <td><select name="accountstatus" title="Activate or Deactivate">
 <option value="true" ' . $aselected . '>Active Account</option>
 <option value="false" ' . $dselected . '>Inactive Account</option>
 </select>
 </td></tr>';
}

/****************************************************************************************
 *																													*
 * Routine/Module Name: printcountry																	*
 *																													*
 * Parameter Description: $val: This is the country that was the original value of		*
 *	the field.																								*
 *																													*
 * Description: In the User Account screen, there is a drop down menu to allow users	*
 *	to select a coutry for the address. This routine gets all of the countries from	*
 *	the country table and displays them in the screen as a drop-down menu for			*
 *	selection. The country indicated by the $val parameters is the default selected *
 *	field.																									*
 *																													*
 * Author: David A. Gaitos, MorphBank Project Director											*
 * Date: Sept 10, 2005																						*
 ****************************************************************************************/

function printcountry($val) {
	$link = AdminLogin();
	$results = mysqli_query($link, "select * from Country order by description;");
	$numrows = mysqli_num_rows($results);
	if ($numrows == 0) exit;
	?>
<tr>
	<td><b>Country: <span style="color: red">*</span></b></td>
	<td><select name="country" title="Select a country" size="1">
		<option value="0"
		<?php
		if ($val == "0")
		echo 'selected="selected"';
		?>>Select one country from the drop-down list</option>
		<?php
		for ($index = 0; $index < $numrows; $index++) {
			$row = mysqli_fetch_array($results);
			if ($val == $row['description']) {
				$Selected = " selected='selected' ";
			} else {
				$Selected = " ";
			}
			echo '<option value="' . $row['description'] . '" ' . $Selected . ">" . $row['description'] . "</option>";
		}
		echo '</select></td></tr>';
}

/****************************************************************************************
 *																													*
 * Routine/Module Name: printstate																		*
 *																													*
 * Parameter Description: $val; Character string. Contains the name of a state.			*
 *																													*
 * Description: Used in the User update/maintenance screen to display and select		*
 *	states for use in the address field.															*
 *																													*
 * Author: David A. Gaitos, MorphBank Project Director											*
 * Date: Sept 13, 2005																						*
 ****************************************************************************************/
function printstate($val) {
	?>
		<tr>
			<td><b>State: </b></td>
			<td><select name="state" title="Select a State" size=".$SIZE.">
				<option value=" "
				<?php
				if ($val == " ")
				echo 'selected="selected"';
				?>>None</option>
				<option value="AL"
				<?php
				if ($val == "AL")
				echo 'selected="selected"';
				?>>AL Alabama</option>
				<option value="AK"
				<?php
				if ($val == "AK")
				echo 'selected="selected"';
				?>>AK Alaska</option>
				<option value="AZ"
				<?php
				if ($val == "AZ")
				echo 'selected="selected"';
				?>>AZ Arizona</option>
				<option value="AR"
				<?php
				if ($val == "AR")
				echo 'selected="selected"';
				?>>AR Arkansas</option>
				<option value="CA"
				<?php
				if ($val == "CA")
				echo 'selected="selected"';
				?>>CA California</option>
				<option value="CO"
				<?php
				if ($val == "CO")
				echo 'selected="selected"';
				?>>CO Colorado</option>
				<option value="CT"
				<?php
				if ($val == "CT")
				echo 'selected="selected"';
				?>>CT Connecticut</option>
				<option value="DE"
				<?php
				if ($val == "DT")
				echo 'selected="selected"';
				?>>DE Delaware</option>
				<option value="DC"
				<?php
				if ($val == "DC")
				echo 'selected="selected"';
				?>>DC District of Columbia</option>
				<option value="FL"
				<?php
				if ($val == "FL")
				echo 'selected="selected"';
				?>>FL Florida</option>
				<option value="GA"
				<?php
				if ($val == "GA")
				echo 'selected="selected"';
				?>>GA Georgia</option>
				<option value="HI"
				<?php
				if ($val == "HI")
				echo 'selected="selected"';
				?>>HI Hawaii</option>
				<option value="ID"
				<?php
				if ($val == "ID")
				echo 'selected="selected"';
				?>>ID Idaho</option>
				<option value="IL"
				<?php
				if ($val == "IL")
				echo 'selected="selected"';
				?>>IL Illinois</option>
				<option value="IN"
				<?php
				if ($val == "IN")
				echo 'selected="selected"';
				?>>IN Indiana</option>
				<option value="IA"
				<?php
				if ($val == "IA")
				echo 'selected="selected"';
				?>>IA Iowa</option>
				<option value="KS"
				<?php
				if ($val == "KS")
				echo 'selected="selected"';
				?>>KS Kansas</option>
				<option value="KY"
				<?php
				if ($val == "KY")
				echo 'selected="selected"';
				?>>KY Kentucky</option>
				<option value="LA"
				<?php
				if ($val == "LA")
				echo 'selected="selected"';
				?>>LA Louisiana</option>
				<option value="ME"
				<?php
				if ($val == "ME")
				echo 'selected="selected"';
				?>>ME Maine</option>
				<option value="MD"
				<?php
				if ($val == "MD")
				echo 'selected="selected"';
				?>>MD Maryland</option>
				<option value="MA"
				<?php
				if ($val == "MA")
				echo 'selected="selected"';
				?>>MA Massachusetts</option>
				<option value="MI"
				<?php
				if ($val == "MI")
				echo 'selected="selected"';
				?>>MI Michigan</option>
				<option value="MN"
				<?php
				if ($val == "MN")
				echo 'selected="selected"';
				?>>MN Minnesota</option>
				<option value="MS"
				<?php
				if ($val == "MS")
				echo 'selected="selected"';
				?>>MS Mississippi</option>
				<option value="MO"
				<?php
				if ($val == "MO")
				echo 'selected="selected"';
				?>>MO Missouri</option>
				<option value="MT"
				<?php
				if ($val == "MT")
				echo 'selected="selected"';
				?>>MT Montana</option>
				<option value="NE"
				<?php
				if ($val == "NE")
				echo 'selected="selected"';
				?>>NE Nebraska</option>
				<option value="NV"
				<?php
				if ($val == "NV")
				echo 'selected="selected"';
				?>>NV Nevada</option>
				<option value="NH"
				<?php
				if ($val == "NH")
				echo 'selected="selected"';
				?>>NH New Hampshire</option>
				<option value="NM"
				<?php
				if ($val == "NM")
				echo 'selected="selected"';
				?>>NM New Mexico</option>
				<option value="NY"
				<?php
				if ($val == "NY")
				echo 'selected="selected"';
				?>>NY New York</option>
				<option value="NC"
				<?php
				if ($val == "NC")
				echo 'selected="selected"';
				?>>NC North Carolina</option>
				<option value="ND"
				<?php
				if ($val == "ND")
				echo 'selected="selected"';
				?>>ND North Dakota</option>
				<option value="OH"
				<?php
				if ($val == "OH")
				echo 'selected="selected"';
				?>>OH Ohio</option>
				<option value="OK"
				<?php
				if ($val == "OK")
				echo 'selected="selected"';
				?>>OK Oklahoma</option>
				<option value="OR"
				<?php
				if ($val == "OR")
				echo 'selected="selected"';
				?>>OR Oregon</option>
				<option value="PA"
				<?php
				if ($val == "PA")
				echo 'selected="selected"';
				?>>PA Pennsylvania</option>
				<option value="RI"
				<?php
				if ($val == "RI")
				echo 'selected="selected"';
				?>>RI Rhode Island</option>
				<option value="SC"
				<?php
				if ($val == "SC")
				echo 'selected="selected"';
				?>>SC South Carolina</option>
				<option value="SD"
				<?php
				if ($val == "SD")
				echo 'selected="selected"';
				?>>SD South Dakota</option>
				<option value="TN"
				<?php
				if ($val == "TN")
				echo 'selected="selected"';
				?>>TN Tennessee</option>
				<option value="TX"
				<?php
				if ($val == "TX")
				echo 'selected="selected"';
				?>>TX Texas</option>
				<option value="UT"
				<?php
				if ($val == "UT")
				echo 'selected="selected"';
				?>>UT Utah</option>
				<option value="VT"
				<?php
				if ($val == "VT")
				echo 'selected="selected"';
				?>>VT Vermont</option>
				<option value="VA"
				<?php
				if ($val == "VA")
				echo 'selected="selected"';
				?>>VA Virginia</option>
				<option value="WA"
				<?php
				if ($val == "WA")
				echo 'selected="selected"';
				?>>WA Washington</option>
				<option value="WV"
				<?php
				if ($val == "WV")
				echo 'selected="selected"';
				?>>WV West Virginia</option>
				<option value="WI"
				<?php
				if ($val == "WI")
				echo 'selected="selected"';
				?>>WI Wisconsin</option>
				<option value="WY"
				<?php
				if ($val == "WY")
				echo 'selected="selected"';
				?>>WY Wyoming</option>
			</select></td>
		</tr>

		<?php
}

//function to get server name from ServerInfo table
/* Not used
function GetServerName($link, $serverId) {
	$link = connect();
	$query = "SELECT url FROM ServerInfo WHERE serverId=" . $serverId;
	$result = mysqli_query($link, $query);
	if (!$result) {
		echo '<span style="color:red">Problems querying the database</span>';
	} else {
		$row = mysqli_fetch_array($result);
		return $row['url'];
	}
}
*/

//function to get group names list for the user
function prefGroup($link, $userId, $prefGroup) {
	$link = Adminlogin();
	$query = "Select Groups.groupName,Groups.id from Groups, UserGroup where UserGroup.user =" . $userId . " and Groups.id = UserGroup.groups";
	$result = mysqli_query($link, $query);
	$num_rows = mysqli_num_rows($result);
	echo '<select name="preferredGroup" >';
	for ($i = 0; $i < $num_rows; $i++) {
		$row = mysqli_fetch_array($result);
		if ($prefGroup == $row['id']) {
			echo '<option selected="selected" value="' . $row['id'] . '">' . $row['groupName'] . '</option>';
		} else {
			echo '<option value="' . $row['id'] . '">' . $row['groupName'] . '</option>';
		}
	}
	echo '</select>';
}
?>
