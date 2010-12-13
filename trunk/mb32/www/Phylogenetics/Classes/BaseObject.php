<?php

//
// @author Karolina MJ
// @date June2nd 2007
//


abstract class BaseObject{

      var $id;
      var $userId;     
      var $submittedBy;
      var $groupId;
      var $dateCreated;
      var $dateLastModified;
      var $dateToPublish;
      var $objectTypeId;
      var $description;
      var $objectLogo;
      var $keywords;
      var $summaryHTML;
      var $thumbURL;
      var $name;
      var $link;


  //constructor 
      function BaseObject($link,$objId){
	$this-> link = $link;
	if($objId!=null)
	  $this-> LoadBaseObjectFromDB($objId);
      }

      function getId() {
        return $this->id;
      }

      function setId($id) {
        $this->id=$id;
      }

      function getDateCreated() {
	return $this->dateCreated;
      }

     function setDateCreated($dateCreated) {
	 $this->dateCreated = $dateCreated;
     }

     function getDateLastModified() {
	 return $this->dateLastModified;
     }

     function setDateLastModified($dateLastModified) {
	 return $this->dateLastModified = $dateLastModified;
       }

     function getDateToPublish() {
	 return $this->dateToPublish;
     }

     function setDateToPublish($dateToPublish) {
	 $this->dateToPublish = $dateToPublish;
     }
  
     function getDescription() {
	 return $this->description;
     }

     function setDescription($description) {
	 $this->description = $description;
     }
       
     function getGroupId() {
       return $this ->groupId;
     }

     function setGroupId($groupId) {
	 $this->groupId = $groupId;
       }

     function getObjectTypeId() {
	 return $this->objectTypeId;
     }

     function setObjectTypeId($objectTypeId) {
	 $this->objectTypeId = $objectTypeId;
     }

     function getUserId() {
	 return $this -> userId;
     }

     function setUserId($userId){
       $this -> userid = $userId;
     }

     function setUser($user) {
       $this ->user = $user;
     }

     function getSubmittedBy() {
       return $this -> submittedBy;
     }

     function setSubmittedBy($submittedBy) {
       $this -> submittedBy = $submittedBy;
     }

     function getKeywords() {
       return $this -> keywords;
     }

     function setKeywords($keywords) {
       $this->keywords = $keywords;
     }

     function getObjectLogo() {
       return $this -> objectLogo;
     }

     function setObjectLogo($objectLogo) {
       $this->objectLogo = $objectLogo;
     }

     function getSummaryHTML() {
       return $this -> summaryHTML;
     }

     function setSummaryHTML($summaryHTML) {
       $this ->summaryHTML = $summaryHTML;
     }

     function getThumbURL() {
       return $this -> thumbURL;
     }

     function setThumbURL($thumbURL) {
       $this -> thumbURL = $thumbURL;
     }
    
     function getName() {
       return $this -> name;
     }

     function setName($name) {
       $this -> name = $name;
     }

     function LoadBaseObjectFromDB($objId){
       $query ="Select * from BaseObject where id=".$objId;
       $result = mysqli_query($this->link,$query);
       if(!$result){
	 echo "Problems querying the database from BaseObject";
       }
       else{
	 $rowNum = mysqli_num_rows($result);
	 if($rowNum<1){
	   echo "There is no BaseObject with that id";
	   $this->setId(null);
	 }
	 else{
	   $row = mysqli_fetch_array($result);
	   $this->setId($row['id']);
	   $this->setUserId($row['userId']);
	   $this->setGroupId($row['groupId']);
	   $this->setDateCreated($row['dateCreated']);
	   $this->setDateLastModified($row['dateLastModified']);
	   $this->setDateToPublish($row['dateToPublish']);
	   $this->setObjectTypeId($row['objectTypeId']);
	   $this->setDescription($row['description']);
	   $this->setSubmittedBy($row['submittedBy']);
	   $this->setObjectLogo($row['objectLogo']);
	   $this->setKeywords($row['keywords']);
	   $this->setSummaryHTML($row['summaryHTML']);
	   $this->setThumbURL($row['thumbURL']);
	   $this->setName($row['name']);
	 }
       }
     }

}//end of class BaseObject
?>
