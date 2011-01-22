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

/**
 * Need to get POST array to unset some values
 * Period in email causes problems on redirect
 * Do not want to send password info via get on redirect
 */
$postArray = $_POST;
unset($postArray['pin']);
unset($postArray['confirm_pin']);
unset($postArray['email']);

$errorPriority = 5;

$action = isset($_POST['spamcode']) ? 'new' : 'add';
$userId = $objInfo->getUserId();
$groupId = $objInfo->getUserGroupId();
$indexUrl = "/Admin/User/$action";
$editUrl = "/Admin/User/edit";
$queryString = getParamString($postArray);

if ($action == 'add' && $groupId != $config->adminGroup) {
	header ("location: $indexUrl&code=6&$queryString");
	exit;
}

// If New user, check spam code
if ($action == 'new') {
	$codeArray = getSpamCode($_POST['spamid']);
	if (strtolower($_POST['spamcode']) != strtolower($codeArray['code'])) {
		header("location: $indexUrl&code=8&$queryString");
	}	
}

// If new user, resume file required
if ($action == 'new' && empty($_FILES['userresume'])) {
	header ("location: $indexUrl&code=16&$queryString");
	exit;
}

// Get post variables
$first_name   = $_POST['first_name'];
$last_name    = $_POST['last_name'];
$middle_init  = $_POST['middle_init'];
$suffix       = $_POST['suffix'];
$uin          = $_POST['uin'];
$pin          = $_POST['pin'];
$email        = $_POST['email'];
$affiliation  = $_POST['affiliation'];
$street1      = $_POST['street1'];
$street2      = $_POST['street2'];
$city         = $_POST['city'];
$country      = $_POST['country'];
$state        = $_POST['state'];
$zipcode      = $_POST['zipcode'];
$logoURL      = $_POST['link'];
$name         = trim($first_name) . " " . trim($last_name);
$address      = trim($street1) . " " . trim($street2) . " " . trim($city) . " " . trim($state) . " " . trim($country) . " " . trim($zipcode);
$status       = $_POST['accountstatus'] == 1 ? 1 : 0;

$db = connect();

if (empty($userId) || empty($groupId)) {
	$groupId = $config->adminGroup;
	$sql = "SELECT min(userId) as userId FROM `BaseObject` WHERE groupId = $groupId";
	$userId = $db->getOne($sql);
	isMdb2Error($userId, "Get default Admin", $errorPriority);
}

// Insert BaseObject for User
$params = array($db->quote("User"), $userId, $groupId, $userId, "NOW()", $db->quote("User added"), $db->quote(NULL));
$result = $db->executeStoredProc('CreateObject', $params);
if(isMdb2Error($result, 'Create Object procedure', $errorPriority)) {
	header("location: $indexUrl&code=9&$queryString");
	exit;
}
$id = $result->fetchOne();
clear_multi_query($result);

// If user created, handle file upload if existing
if (isset($_FILES['userlogo']) && ($_FILES['userlogo']['name'] > "")) {
	$new_image = $_FILES['userlogo']['name'];

	$simple_name = substr($new_image, 0, strpos($new_image, "."));
	$simple_name .= $_POST['id'];
	$image_new = $simple_name . substr($new_image, strpos($new_image, "."), strlen($new_image) - 1);

	$tmpFile = $_FILES['userlogo']['tmp_name'];

	if (!move_uploaded_file($tmpFile, $config->userLogoPath . $image_new)) {
		header("location: $indexUrl&code=7&$queryString");
	}
	exec("chmod 755 " . $config->userLogoPath . $image_new);

	$userLogo = $config->appServerBaseUrl . '/images/userLogos/' . trim($image_new);
}

// Insert BaseObject for Groups
$params = array($db->quote("Groups"), $userId, $groupId, $userId, "NOW()", $db->quote("Group added"), $db->quote(NULL));
$result = $db->executeStoredProc('CreateObject', $params);
if(isMdb2Error($result, 'Create Object procedure', $errorPriority)) {
	header("location: $editUrl/$id&code=11");
	exit;
}
$group_id = $result->fetchOne();
clear_multi_query($result);

// prepare user update
$userUpdater = new Updater($db, $id, $userId , $groupId, 'User');
$userUpdater->addField('last_Name', $last_name, null);
$userUpdater->addField('first_Name', $first_name,null);
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
if (is_string($numRows)) { // Error returned
	header("location: $editUrl/$id&code=10");
	exit;
}

// prepare Groups update
$userUpdater = new Updater($db, $group_id, $userId , $groupId, 'Groups');
$userUpdater->addField('groupName', "$uin's group", null);
$userUpdater->addField('tsn', 0,null);
$userUpdater->addField('groupManagerId', $id, null);
$userUpdater->addField('status', 1, null);
$userUpdater->addField('dateCreated', $db->mdbNow(), null);
$numRows = $userUpdater->executeUpdate();
if (is_string($numRows)) { // Error returned
	header("location: $editUrl/$id&code=12");
	exit;
}

// Insert in UserGroup table
$data = array($id, $group_id, $userId, $db->mdbNow(), $db->mdbToday(), 'coordinator');
$sql = "insert into UserGroup (user, groups, userId, dateCreated, dateToPublish, userGroupRole) values (?,?,?,?,?,?)";
$stmt = $db->prepare($sql);
$affRows = $stmt->execute($data);
if(isMdb2Error($affRows, 'Create user group', $errorPriority)) {
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
if ($_POST['subscription'] == 1 && $config->mailList) {
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
	
	if (!preg_match('/Successfully subscribed/', $data)) {
		$text = "A FAILURE OCCURED ADDING NEW USER TO MAILING LIST:\n\n";
		$text .= "Name: $first_name $last_name \n";
		$text .= "Email: $email\n";
		
		$html = "A FAILURE OCCURED ADDING NEW USER TO MAILING LIST:<br /><br />";
		$html .= "<b>Name:</b> $first_name $last_name<br />";
		$html .= "<b>Email:</b> $email<br />";
		
        require('Mail.php');
        require('Mail\mime.php');
    
        $message = new Mail_mime();
        $message->setTXTBody($text);
        $message->setHTMLBody($html);
        $body = $message->get();
        $extraheaders = array("From" => $config->email, "Subject" => $config->appName . " - Add To Mail List Failure");
        $headers = $message->headers($extraheaders);
    
        $mail = Mail::factory("mail");
        $result = $mail->send($config->email, $headers, $body);
        if (PEAR::isError($result)) {
          errorLog("Error sending email: Failure to add user to mailist.", $result->getDebugInfo());
        }
	}
}


if ($action == 'new') {
	$fileTmp  = $_FILES['userresume']['tmp_name'];
	$fileType = $_FILES['userresume']['type'];
	$fileName = $_FILES['userresume']['name'];
	$fileExt  = strrchr($fileName, '.');
	$file     = $uin.$fileExt;
	$filePath = $config->cvFolder.$file;

	if (!move_uploaded_file($fileTmp, $filePath)) {
		header("location: $indexUrl&code=7&$queryString");
	}

	$text = "A REQUEST FOR NEW USER ACCOUNT ON MORPHBANK FROM:\n\n";
	$text .= "Name: $first_name $last_name \n";
	$text .= "Email: $email\n";
	$text .= "Resume\CV: ".$config->appServerBaseUrl."Admin/User/getCV.php?cv=".$file."\n";
	$text .= "Mail List Subscribe: ".(isset($_POST['subscription']) ? 'Yes' : 'No')."\n\n";
	
	$html = "A REQUEST FOR NEW USER ACCOUNT ON MORPHBANK FROM:<br /><br />";
	$html .= "<b>Name:</b> $first_name $last_name<br />";
	$html .= "<b>Email:</b> $email<br />";
	$html .= "<b>Resume\CV</b>: ".$config->appServerBaseUrl."Admin/User/getCV.php?cv=".$file."<br />";
	$html .= "<b>Mail List Subscribe</b>: ".(isset($_POST['subscription']) ? 'Yes' : 'No')."<br /><br />";
	
    require('Mail.php');
    require('Mail\mime.php');

    $message = new Mail_mime();
    $message->setTXTBody($text);
    $message->setHTMLBody($html);
    $body = $message->get();
    $extraheaders = array("From" => $config->email, "Subject" => $config->appName . " - New User Account");
    $headers = $message->headers($extraheaders);

    $mail = Mail::factory("mail");
    $result = $mail->send($config->email, $headers, $body);
    if (PEAR::isError($result)) {
      errorLog("Error sending email: New user account.", $result->getDebugInfo());
    }
	
	header ("location: $indexUrl&code=14");
	exit;
}

header ("location: $indexUrl&code=15");
exit;
