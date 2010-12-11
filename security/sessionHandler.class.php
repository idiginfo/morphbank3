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
/*
 File name: sessionHandler.class .php
 @author Neelima Jammigumpula <jammigum@scs.fsu.edu>
 @package Morphbank2
 @subpackage security

 This class is defined to handle session variables at one place. A class instance is used as a session variable from which the class variables can be accessed. Advantage of using a class is that variables can be added or removed from the class (in other words more structured for the future too) without the need for changing the existing code.

 Functions:
 -setUserInfo: Sets these user variables: tsn, privilegeTSN, primaryTSN and secondaryTSN.
 -setUserGroupInfo: Sets the group variables based on the group the user selects to work with.
 -getSessionVariables: Gets the required session variables required by the modules. Not implemented as of yet.
 -destroySession: This is called in logout process and cleans up after sessions.

 Notes:
 - If objects are to be stored as session variables, you should include class definitions for those objects in all scripts that initialize sessions, whether the scripts use the class or not.
 - Important thing to note is that the class instance must be serialized before assigning it to session object and unserialized when it is assigned back for manipulation.
 - Right now, they rely on cookies. In future, extend this to check whether user disabled cookies or not. If cookies are disabled, the program might communicate using GET with ID passed in the URL. This depends on time constraints.
 */
require_once('MyManagerSessionHandler.class.php');

class sessionHandler extends MyManagerSessionHandler {

	public $logged = false;

	public $domainName;
	public $name = 'Guest';
	public $username = '';
	public $userId;
	public $privilegeTSN;
	public $primaryTSN;
	public $secondaryTSN;

	public $groupName;
	public $groupId;
	public $userGroup;
	public $userGroupId;
	public $userGroupRole = 'world';
	public $groupTSN;
	public $groupTSNName;
	public $userTSNName;
	public $groupIds = array();

	public $preferredServer = 'http://morphbank.net';
	public $serverId = 1 ;
	public $serverLogo = 'mbLogoHeader1.png';
	public $serverName = 'Morphbank';
	public $ifMirror = false;
	public $isMirror;
	public $link;
	public $pop = true;

	function setDomainName($domainName) {
		$this->domainName = $domainName;
	}

	function setLogged($logged){
		$this->logged = $logged;
	}

	function setLink($link) {
		$this->link = $link;
	}

	function setUserInfo($name, $username, $userId, $privilegeTSN, $primaryTSN, $secondaryTSN, $userTSNName){
		$this->name = $name;
		$this->username = $username;
		$this->userId = $userId;
		$this->privilegeTSN =$privilegeTSN;
		$this->primaryTSN =$primaryTSN;
		$this->secondaryTSN =$secondaryTSN;
		$this->userTSNName =$userTSNName;
	}

	function setUserGroupInfo($userGroupId, $userGroup, $userGroupRole, $groupTSN, $groupTSNName){
		$this->userGroupId = $userGroupId;
		$this->groupId = $userGroupId;
		$this->userGroup = $userGroup;
		$this->groupName =  $userGroup;
		$this->userGroupRole = $userGroupRole;
		$this->groupTSN = $groupTSN;
		$this->groupTSNName = $groupTSNName;
	}

	function setGroupIdArray($array){
		$this->groupIds = $array;
	}

	function setServerInfo($serverId, $preferredServer, $logo, $serverName){
		$this->serverId= $serverId;
		$this->preferredServer = $preferredServer;
		$this->serverLogo= $logo;
		$this->serverName= $serverName;
	}

	function setMirror($mirror){
		$this->ifMirror = $mirror;
	}

	function setMirrorStatus($mirror){
		$this->isMirror = $mirror;
	}

	function checkLogin($uin, $pin, $link){
		$sql = "SELECT id, name, privilegeTSN, primaryTSN, secondaryTSN, preferredGroup FROM User "
		." WHERE uin='$uin' AND pin = PASSWORD('$pin') AND status = 1";
		$row = mysqli_fetch_array(mysqli_query($link , $sql));
		if ($row) {
			$this->setLogged('true');

			$userTsnName = mysqli_fetch_array(mysqli_query($link , 'SELECT scientificName FROM Tree WHERE tsn = ' . $row['privilegeTSN'] . ';'));
			$this->setUserInfo($row['name'], $uin, $row['id'], $row['privilegeTSN'], $row['primaryTSN'], $row['secondaryTSN'], $userTsnName['scientificName']);

			//set group info based on preferredGroup of user and skip the whole process of group selection.
			if ($row['preferredGroup'] != '') {
				$groupSql = 'SELECT Groups.id, groupName, Groups.tsn, userGroupRole FROM User LEFT JOIN UserGroup ON User.id = UserGroup.user LEFT JOIN Groups ON UserGroup.groups = Groups.id WHERE User.uin = \'' . $uin . '\' AND Groups.id = \'' . $row['preferredGroup'] . '\';';
			} else {
				$preferredGroup = mysqli_fetch_array(mysqli_query($link , 'SELECT groups FROM `UserGroup` WHERE user = ' . $row['id'] . ' LIMIT 1'));
				$groupSql = 'SELECT Groups.id, groupName, Groups.tsn, userGroupRole FROM User LEFT JOIN UserGroup ON User.id = UserGroup.user LEFT JOIN Groups ON UserGroup.groups = Groups.id WHERE User.uin = \'' . $uin . '\' AND Groups.id = \'' . $preferredGroup['groups'] . '\';';
			}

			$groupRow = mysqli_fetch_array(mysqli_query($link , $groupSql));

			if ($groupRow) {
				$nameSql = 'SELECT unit_name1 FROM Tree WHERE tsn = ' . $groupRow['tsn'] . ';';
				$name = mysqli_fetch_array(mysqli_query($link , $nameSql));
			}

			if ($groupRow && $name) {
				$this->setUserGroupInfo($groupRow['id'], $groupRow['groupName'], $groupRow['userGroupRole'], $groupRow['tsn'], $name['unit_name1']);
			}

			//Get groupIds of the user
			$groupsRes = mysqli_query($link , 'SELECT groups FROM UserGroup WHERE user = ' . $row['id'] . ';');
			$groupArray = array();

			while ($array = mysqli_fetch_array($groupsRes)) {
				array_push($groupArray, $array['groups']);
			}

			$this->setGroupIdArray($groupArray);

			$serverSql = 'SELECT serverId, imageURL, logo, mirrorGroup FROM User, ServerInfo WHERE id = ' . $row['id'] . ' AND User.preferredServer = ServerInfo.serverId;';
			$serverRow = mysqli_fetch_array(mysqli_query($link , $serverSql));
			$serverNameSql = 'SELECT groupName FROM Groups WHERE id = ' . $serverRow['mirrorGroup'];
			$serverName = mysqli_fetch_array(mysqli_query($link , $serverNameSql));
			if ($serverRow) {
				$this->setServerInfo($serverRow['serverId'], $serverRow['imageURL'], $serverRow['logo'], $serverName['groupName']);

				//check for mirrorGroup coordinator
				$mirrorSql = 'SELECT mirrorGroup FROM ServerInfo, Groups WHERE Groups.id=ServerInfo.mirrorGroup ';
				$mirrorSql .= ' AND Groups.groupManagerId =' . $row['id'];

				$result = mysqli_query($link , $mirrorSql);
				$mirrorRow = mysqli_fetch_array($result);

				if ($mirrorRow)
				$this->setMirror('true');
				else
				$this->setMirror('false');
			}
			return true;
		}
		$this->setLogged('false');
		$this->destroySession();
		return false;
	}

	function check($objectId, $function = 'view') {
		return checkAuthorization($objectId, $this->userId, $this->groupId, $function);
	}


	function getUrl($id, $action, $tsn = "", $subAction = "") {
		if ($action == "edit") {
			$url = $this->domainName.'Edit/index.php?pop=yes&id='.$id.$tsn;
			$this->pop = true;
		} elseif ($action == "annotate") {
			$url = $this->domainName.'Annotation/index.php?pop=yes&id='.$id;
			$this->pop = true;
		} elseif ($action == "view") {
			$url = $this->domainName.'Show/index.php?pop=yes&id='.$id.$tsn;
			$this->pop = true;
		} elseif ($action == "submit") {
			if ($subAction == "image"){
				$url = $this->domainName.'Submit/Image/index.php?id='.$id;
			}elseif ($subAction == "view"){
				$url = $this->domainName.'Submit/View/index.php?id='.$id;
			}elseif ($subAction == "locality"){
				$url = $this->domainName.'Submit/Location/index.php?id='.$id;
			}elseif ($subAction == "specimen"){
				$url = $this->domainName.'Submit/Specimen/index.php?id='.$id;
			}else{
				$url = $this->domainName.'Submit/index.php?id='.$id;
			}
			$this->pop = false;
		} else {
			$url = $this->domainName.'Edit/index.php?pop=yes&id='.$id.$tsn;
			$this->pop = true;
		}
		return $url;
	}

	function getPop() {
		if ($this->pop)	return "true";
		return "false";
	}

	function getLogged(){
		return $this->logged;
	}

	function getName(){
		return $this->name;
	}

	function getUserName(){
		return $this->username;
	}

	function getUserId(){
		return $this->userId;
	}

	function getUserTSN(){
		return $this->privilegeTSN;
	}

	function getUserPrimaryTSN(){
		return $this->primaryTSN;
	}

	function getUserTSNInfo(){
		$array = array($this->privilegeTSN, $this->primaryTSN, $this->secondaryTSN, $this->userTSNName);
		return $array;
	}

	function getUserInfo(){
		$array = array($this->username, $this->userId, $this->privilegeTSN, $this->primaryTSN, $this->secondaryTSN, $this->userTSNName);
		return $array;
	}

	function getUserGroupInfo(){
		$array = array($this->userGroup, $this->userGroupId, $this->userGroupRole, $this->groupTSN, $this->groupTSNName);
		return $array;
	}

	function getUserGroup(){
		return $this->userGroup;
	}

	function getUserGroupId(){
		return $this->userGroupId;
	}

	function getGroupTSN(){
		return $this->groupTSN;
	}

	function getUserTSNName(){
		return $this->userTSNName;
	}

	function getGroupTSNName(){
		return $this->groupTSNName;
	}

	function getUserGroupRole(){
		return $this->userGroupRole;
	}

	function getGroupIdArray(){
		$array = $this->groupIds;
		return $array;
	}

	function getPreferredServer(){
		return $this->preferredServer;
	}

	function getPreferredServerId(){
		return $this->serverId;
	}

	function getServerLogo(){
		return $this->serverLogo;
	}

	function getServerInfo(){
		$array = array( $this->preferredServer, $this->serverId, $this->serverLogo, $this->serverName);
		return $array;
	}

	function setPreferredServer($preferredServer){
		$this->preferredServer = $preferredServer;
	}

	function isMirror(){
		return $this->isMirror;
	}

	function ifMirror(){
		return $this->ifMirror;
	}

	//This function is called when user logs out
	function destroySession(){
		unset($_SESSION['userInfo']);
		session_destroy();
	}
}
?>
