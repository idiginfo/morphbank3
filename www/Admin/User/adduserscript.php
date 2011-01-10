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
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

include_once('head.inc.php');
include_once('../admin.functions.php');


$title = 'Add User';
initHtml($title, null, null);
echoHead(false, $title);
checkIfLogged();

if ($_PUT['returntouser'] != null)
exit;

$first_name = $HTTP_POST_VARS['first_name'];
$last_name = $HTTP_POST_VARS['last_name'];
$middle_init = $HTTP_POST_VARS['middle_init'];
$suffix = $HTTP_POST_VARS['suffix'];
$uin = $HTTP_POST_VARS['uin'];
$pin = $HTTP_POST_VARS['pin'];
$email = $HTTP_POST_VARS['email'];
$affiliation = $HTTP_POST_VARS['affiliation'];
$privilegetsn = $HTTP_POST_VARS['privilegetsn'];
$primarytsn = $HTTP_POST_VARS['primarytsn'];
$secondarytsn = $HTTP_POST_VARS['secondarytsn'];
$street1 = $HTTP_POST_VARS['street1'];
$street2 = $HTTP_POST_VARS['street2'];
$city = $HTTP_POST_VARS['city'];
$country = $HTTP_POST_VARS['country'];
$state = $HTTP_POST_VARS['state'];
$zipcode = $HTTP_POST_VARS['zipcode'];
$name = trim($first_name) . " " . trim($last_name);
$address = trim($street1) . " " . trim($street2) . " " . trim($city) . " " . trim($state) . " " . trim($country) . " " . trim($zipcode);


echo '<div class="main">';
echo '<div class="mainGenericContainer" style="width:700px">';
$UserId = $objInfo->getUserId();
Adminlogin();

/***********************************************************************************************
 *  1. Get the current id from the id table.                                               *
 *  2. Add one to the id field.                                                                *
 *  3. Update the id field in the id table.                                                  *
 *  4. Insert the new user record using the new id .                                         *
 *  5. Add a new record to the "baseobject" table referenceing the new user.                   *
 **********************************************************************************************/
while (true) {
	$curr_id = getNewId();
	$user_Id = $curr_id;
	$query = "Select uin from User where uin ='" . $uin . "' and first_Name='" . $first_name . "' and last_Name='" . $last_name . "' and affiliation='" . $affiliation . "' and country='" . $country . "' and privilegeTSN='" . $privilegetsn . "';";
	$results = mysql_query($query);
	$num_results = mysql_num_rows($results);
	if ($num_results != 0) {
		echo '<br></br>';
		echo '<b>User already exists in the Data Base</b>';
		break;
	}

	$query = "Select uin from User where uin ='" . $uin . "';";
	$results = mysql_query($query);
	$num_results = mysql_num_rows($results);
	if ($num_results != 0) {
		echo '<br></br>';
		echo '<b>Username already exists. Choose another username</b>';
		break;
	}

	$query = "insert into User (id, last_Name, first_Name, middle_init, suffix, uin,pin,email," . "accountstatus, affiliation,privilegetsn,primarytsn,secondarytsn, street1, street2, city,country," . " state, zipcode,name,address) values" . "(\"" . $curr_id . "\",\"" . $last_name . "\",\"" . $first_name . "\",\"" . $middle_init . "\",\"" . $suffix . "\",\"" . $uin . "\",password(\"" . $pin . "\"),\"" . $email . "\",true,\"" . $affiliation . "\",\"" . $privilegetsn . "\",\"" . $primarytsn . "\",\"" . $secondarytsn . "\",\"" . $street1 . "\",\"" . $street2 . "\",\"" . $city . "\",\"" . $country . "\",\"" . $state . "\",\"" . $zipcode . "\",\"" . $name . "\",\"" . $address . "\") ";
	$results = mysql_query($query);
	if ($results) {
		$status = AddBaseObject($curr_id, $objInfo->getUserid(), $objInfo->getUserGroupId(), "User", date('Y m d'), "Added new user to database");
		if (!$status) {
			echo '<br></br>';
			echo '<b>Base Object for user not created</b>';
			break;
		}
	} else {

		echo '<br></br>';
		echo '<b>Bad results returned, possibly SQL command did not execute or username already exists</b>';
		break;
	}


	//create a group with the same name as the username just entered
	$curr_id = getNewId();
	$uig = "'s group";
	$uin = $uin . $uig;
	$query_group = "INSERT INTO Groups (id,groupName,tsn,groupManagerId) VALUES (";
	$query_group .= "\"" . $curr_id . "\",\"" . $uin . "\",\"" . $privilegetsn . "\",\"" . $user_Id . "\")";

	$results_group = mysql_query($query_group);
	if ($results_group) {
		$query = "insert into UserGroup values (";
		$query .= $user_Id . ",";
		$query .= $curr_id . ",";
		$query .= $UserId . ",";
		$query .= 'NOW(), NOW(), NOW(),';
		$query .= "'coordinator')";
		$results = mysql_query($query);
		if (!$results) {
			echo '<b>Record into UserGroup not added</b>';
			break;
		} else {

			$status_g = AddBaseObject($curr_id, $objInfo->getUserid(), $objInfo->getUserGroupId(), "Group", date('Y m d'), "Added new group to database");
			if (!$status_g) {
				echo '<b>Base Object for group not created</b>';
				break;
			}
			echo '<b>Record added successfuly</b>';
			break;
		}
	} else {

		echo '<br></br>';
		echo '<b>Bad results returned, possibly SQL command did not execute or groupname already exists</b>';
		break;
	}
}
?>

<table width="600px">
	<tr>
		<td><a href="javascript:history.go(-1)"> <img
			src="/style/webImages/buttons/addUser.png" border="0"
			title="Click to add another User" alt="Add another user" /></a></td>
		<td><a href="javascript:history.go(-2)"> <img
			src="/style/webImages/buttons/returnToUser.png" border="0"
			title="Click to Return to User Menu" alt="Return to user menu" /></a></td>
	</tr>
</table>

<?php
echo "</div>";
include('footer.inc.php');
echo "</div>";
finishHtml();
?>
