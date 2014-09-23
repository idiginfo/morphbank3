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
include_once('updater.class.php');
include_once('objectFunctions.php');
include_once('updateObjectKeywords.php');
include_once('urlFunctions.inc.php');
include_once('spam.php');
include_once('Classes/UUID.php');

/**
 * Need to get POST array to unset some values
 * Period in email causes problems on redirect
 * Do not want to send password info via get on redirect
 */

$post_array = $_POST;
unset($post_array['pin']);
unset($post_array['confirm_pin']);
unset($post_array['email']);
$queryString = getParamString($post_array);

$action = isset($_POST['spamcode']) ? 'new' : 'add';
$userId = $objInfo->getUserId();
$groupId = $objInfo->getUserGroupId();
$indexUrl = "/Admin/User/$action";
$editUrl = "/Admin/User/edit";

if ($action == 'add' && $groupId != $config->adminGroup)
{
	header("location: $indexUrl&code=6&$queryString");
	exit;
}

$required = array('first_name', 'last_name', 'email', 'affiliation', 'country');
if ($action == 'new')
{
	$required = array_merge($required, array('uin', 'pin'));
}

foreach ($required as $field)
{
	$req_field = trim($_POST[$field]);
	if (empty($req_field))
	{
		header("location: $indexUrl&code=17&$queryString");
		exit;
	}
}

// Check spam code
if ($action == 'new')
{
	$codeArray = getSpamCode($_POST['spamid']);
	if (strtolower($_POST['spamcode']) != strtolower($codeArray['code']))
	{
		header("location: $indexUrl&code=8&$queryString");
		exit;
	}
}

// If new user, resume file required
if ($action == 'new' && empty($_FILES['userresume']))
{
	header("location: $indexUrl&code=16&$queryString");
	exit;
}

// Get post variables
$first_name = trim($_POST['first_name']);
$last_name = trim($_POST['last_name']);
$middle_init = trim($_POST['middle_init']);
$suffix = trim($_POST['suffix']);
$uin = trim($_POST['uin']);
$pin = trim($_POST['pin']);
$email = trim($_POST['email']);
$affiliation = trim($_POST['affiliation']);
$street1 = trim($_POST['street1']);
$street2 = trim($_POST['street2']);
$city = trim($_POST['city']);
$country = trim($_POST['country']);
$state = trim($_POST['state']);
$zipcode = trim($_POST['zipcode']);
$logoURL = trim($_POST['link']);
$name = $first_name . " " . $last_name;
$address = $street1 . " " . $street2 . " " . $city . " " . $state . " " . $country . " " . $zipcode;
$status = $_POST['accountstatus'] == 1 ? 1 : 0;

$db = connect();

// Check uin is not already used
$sql = "select count(*) from User where uin = ?";
$count = $db->getOne($sql, array('integer'), array($uin));
if (isMdb2Error($count, "select existing user", 6))
{
	header("location: $indexUrl&code=3&$queryString");
	exit;
}
if ($count > 0)
{
	header("location: $indexUrl&code=18&$queryString");
	exit;
}

// Check email is not already used
$sql = "select count(*) from User where email = ?";
$count = $db->getOne($sql, array('integer'), array($email));
if (isMdb2Error($count, "select existing email", 6))
{
	header("location: $indexUrl&code=3&$queryString");
	exit;
}
if ($count > 0)
{
	header("location: $indexUrl&code=19&$queryString");
	exit;
}

if (empty($userId) || empty($groupId))
{
	$groupId = $config->adminGroup;
	$sql = "SELECT min(userId) as userId FROM `BaseObject` WHERE groupId = $groupId";
	$userId = $db->getOne($sql);
	isMdb2Error($userId, "Get default Admin");
}

// Insert BaseObject for User
$uuid = UUID::v4();
$params = array(
	$db->quote("User"),
	$userId,
	$groupId,
	$userId,
	"NOW()",
	$db->quote("User added"),
	$db->quote(NULL),
	$db->quote($uuid)
);
$result = $db->executeStoredProc('CreateObject', $params);
if (isMdb2Error($result, 'Create Object procedure', 6))
{
	header("location: $indexUrl&code=9&$queryString");
	exit;
}
$id = $result->fetchOne();
clear_multi_query($result);

// Insert BaseObject for Groups
$uuid = UUID::v4();
$params = array(
	$db->quote("Groups"),
	$userId,
	$groupId,
	$userId,
	"NOW()",
	$db->quote("Group added"),
	$db->quote(NULL),
	$db->quote($uuid)
);
$result = $db->executeStoredProc('CreateObject', $params);
if (isMdb2Error($result, 'Create Object procedure', 6))
{
	header("location: $editUrl/$id&code=11");
	exit;
}
$group_id = $result->fetchOne();
clear_multi_query($result);

// If user created, handle file upload if existing
if (isset($_FILES['userlogo']) && !empty($_FILES['userlogo']['name']))
{
	// Allow only letters, numbers, underscores, and period in file name
	$file_name = trim(preg_replace("/[^a-zA-Z0-9_.]/", "", $_FILES['userlogo']['name']));
	@move_uploaded_file($_FILES['userlogo']['tmp_name'], $config->userLogoPath . $file_name);
	$userLogo = $config->appServerBaseUrl . '/images/userLogos/' . $file_name;
}

// prepare user update
$userUpdater = new Updater($db, $id, $userId, $groupId, 'User');
$userUpdater->addField('last_Name', $last_name, null);
$userUpdater->addField('first_Name', $first_name, null);
$userUpdater->addField('middle_init', $middle_init, null);
$userUpdater->addField('suffix', $suffix, null);
$userUpdater->addField('uin', $uin, null);
$userUpdater->addPasswordField('pin', $pin, null);
$userUpdater->addField('email', $email, null);
$userUpdater->addField('status', $status, null);
$userUpdater->addField('affiliation', $affiliation, null);
$userUpdater->addField('privilegeTSN', 0, null); // TODO Default 0. Eventually remove
$userUpdater->addField('primaryTSN', 0, null); // TODO Default 0. Eventually remove
$userUpdater->addField('secondaryTSN', 0, null); // TODO Default 0. Eventually remove
$userUpdater->addField('street1', $street1, null);
$userUpdater->addField('street2', $street2, null);
$userUpdater->addField('city', $city, null);
$userUpdater->addField('country', $country, null);
$userUpdater->addField('state', $state, null);
$userUpdater->addField('zipcode', $zipcode, null);
$userUpdater->addField('name', $name, null);
$userUpdater->addField('address', $address, null);
$userUpdater->addField("preferredGroup", $group_id, null);
$userUpdater->addField('logoURL', $logoURL, null);
$userUpdater->addField('userLogo', $userLogo, null);
$numRows = $userUpdater->executeUpdate();
if (is_string($numRows))
{ // Error returned
	header("location: $editUrl/$id&code=10");
	exit;
}

// prepare Groups update
$userUpdater = new Updater($db, $group_id, $userId, $groupId, 'Groups');
$userUpdater->addField('groupName', "$uin's group", null);
$userUpdater->addField('tsn', 0, null);
$userUpdater->addField('groupManagerId', $id, null);
$userUpdater->addField('status', 1, null);
$userUpdater->addField('dateCreated', $db->mdbNow(), null);
$numRows = $userUpdater->executeUpdate();
if (is_string($numRows))
{ // Error returned
	header("location: $editUrl/$id&code=12");
	exit;
}

// Insert in UserGroup table
$data = array($id, $group_id, $userId, $db->mdbNow(), $db->mdbToday(), 'coordinator');
$sql = "insert into UserGroup (user, groups, userId, dateCreated, dateToPublish, userGroupRole) values (?,?,?,?,?,?)";
$stmt = $db->prepare($sql);
$affRows = $stmt->execute($data);
if (isMdb2Error($affRows, 'Create user group', 6))
{
	header("location: $editUrl/$id&code=13");
	exit;
}


/**
 * Add user to mailing list if one exists and they subscribe using CURL
 * Success: preg_match finds "Successfully subscribed" in html
 * Failure: preg_match finds "Error subscribing" in html
 *
 * This operation is based on Mailman mailing list program.
 * Different options may be required if a different mailing list is used
 *
 */
if ($_POST['subscription'] == 1 && $config->mailList && $config->allowMailing && function_exists('curl_version') == "Enabled")
{
	$postData['subscribees'] = $email;  // New user email
	$postData['subscribe_or_invite'] = 0;
	$postData['send_welcome_msg_to_this_batch'] = 1;
	$postData['send_notifications_to_list_owner'] = 1;

	$base_url = $config->mailListUrl;
	$postData[$config->mailListPasswordInput] = $config->mailListPassword;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $base_url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($ch);
	curl_close($ch);

	if (!preg_match('/Successfully subscribed/', $data))
	{
		if (!preg_match('/Already a member/', $data))
		{
			$text = "A FAILURE OCCURED ADDING NEW USER TO MAILING LIST:\n\n";
			$text .= "Name: $first_name $last_name \n";
			$text .= "Email: $email\n";
			$subject = $config->appName . " - Add To Mail List Failure";
			$headers['From'] = $config->email;
			$headers['To'] = $config->email;
			$headers['Subject'] = $config->appName . " - Add To Mail List Failure";
			$params['sendmail_path'] = '/usr/sbin/sendmail';

			// Create the mail object using the Mail::factory method
			require('Mail.php');
			$mail_object = &Mail::factory('sendmail', $params);
			$result = $mail_object->send($config->email, $headers, $text);
			if (PEAR::isError($result))
			{
				errorLog("Error sending email: Failure to add user to mailist.", $result->getDebugInfo(), 5);
			}
		}
	}
}

if ($action == 'new')
{
	$fileTmp = $_FILES['userresume']['tmp_name'];
	$fileType = $_FILES['userresume']['type'];
	$fileName = $_FILES['userresume']['name'];
	$fileExt = strrchr($fileName, '.');
	$file = $uin . $fileExt;
	$filePath = $config->cvFolder . $file;
	move_uploaded_file($fileTmp, $filePath);

	$text = "A REQUEST FOR NEW USER ACCOUNT ON MORPHBANK FROM:\n\n";
	$text .= "Name: $first_name $last_name \n";
	$text .= "Email: $email\n";
	$text .= "Resume\CV: " . $config->appServerBaseUrl . "Admin/User/getCV.php?cv=" . $file . "\n";
	$text .= "Mail List Subscribe: " . (isset($_POST['subscription']) ? 'Yes' : 'No') . "\n\n";
	$subject = $config->appName . " - Add To Mail List Failure";
	$headers['From'] = $config->email;
	$headers['To'] = $config->email;
	$headers['Subject'] = $config->appName . " - New User Account";
	$params['sendmail_path'] = $config->sendMail;

	// Create the mail object using the Mail::factory method
	if ($config->allowMailing)
	{
		require('Mail.php');
		$mail_object = &Mail::factory('sendmail', $params);
		$result = $mail_object->send($config->email, $headers, $text);
		if (PEAR::isError($result))
		{
			errorLog("Error sending email: New user account.", $result->getDebugInfo(), 5);
		}
	}

	header("location: $indexUrl&code=14");
	exit;
}

header("location: $indexUrl&code=15");
exit;

