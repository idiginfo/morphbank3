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
// @author Karolina MJ
// @date June 5th 2007
// based on java file writen by riccardi 
//

class CollectionObject {

        var $object;
        var $objectId;
        var $objectOrder;
        var $objectTypeId;
        var $objectTitle;
        var $objectRole; 
        var $collection;
        var $collectionId;
        var $link;

       //this variable added just to accomodate matrix display of the 
       //character and the otu and does not have corresponding field
       //in CollectionObject table in the database
       var $shortTitle;
       
       //possible need for empty constructor
       //but since overload is not possible in php find some other
       //way to deal with different types of constructors
       //it takes three parameters: link, collection, objectId
   
       function CollectionObject($link, $coll, $obj){
	 //echo "\n in collectionObject objectId is ".$obj."\n";
	 if($obj!=null)
	   $this->objectId = $obj;
	 if($coll!=null)
	   $this->collection = $coll;
	 $this->link = $link;
       }
       
       function getObjectId(){
	 return $this->objectId;
       }

       function setObjectId($objectId){  
	 $this->objectId = $objectId;
       }
     
       function getObject(){
	 return $this->object;
       }

       function setObject($object){
	 $this->object = $object;
       }

       function getObjectOrder(){
	 return $this->objectOrder;
       }

       function setObjectOrder($index){
	 $this ->objectOrder = $index;
       }
       
       function getObjectRole(){
	 return $this->objectRole;
       }
       
       function setObjectRole($objectRole){
	 $this->objectRole = $objectRole;
       }

       function getCollection() {
	 return $this->collection;
       }

       function setCollection($collection) {
	 $this->collection = $collection;
       }

       function getObjectTitle() {
	 return $this->objectTitle;
       }

       function setObjectTitle($objectTitle) {
	 $this->objectTitle = $objectTitle;
       }

       function getObjectTypeId() {
	 return $this->objectTypeId;
       }

       function setObjectTypeId($objectTypeId) {
	 $this -> objectTypeId = $objectTypeId;
       }

       function getCollectionId() {
	 return $this->collectionId;
       }

       function setCollectionId($collectionId) {
	 $this -> collectionId = $collectionId;
       }

       function getShortTitle(){
	 return $this->shortTitle;
       }

       function setShortTitle($shortTitle){
	 $this -> shortTitle = $shortTitle;
       }
  
       function ObjectType($objId) {
	 $objectTypeId = null;

	 $query = "SELECT objectTypeId from BaseObject where id=".$objId;
	 // echo $query;
	 $result = mysqli_query($this->link, $query);
	 if(!$result)
	   echo 'Problems querying the database';
	 else{
	   $row = mysqli_fetch_array($result);
	   $objectTypeId = $row['objectTypeId'];
	 }
	 return $objectTypeId;
       }


  function ObjectTitle($objId,$colId) {
    $objectTitle = null;

    $query = "SELECT objectTitle from CollectionObjects where objectId=".$objId. " and collectionId=".$colId;
    //echo $query;
    $result = mysqli_query($this->link, $query);
    if(!$result)
      echo 'Problems querying the database';
    else{
      $row = mysqli_fetch_array($result);
      $objectTitle = $row['objectTitle'];
    }
    return $objectTitle;
  }

  
     }
?>
