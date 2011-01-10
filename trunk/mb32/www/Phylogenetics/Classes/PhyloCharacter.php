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

  //
  // @author Karolina MJ
  // @date June 5th 2007
  // 
  
  class PhyloCharacter extends Collection {
      var $id;
      var $label;
      var $characterNumber;
      var $discrete;
      var $ordered;
      var $publicationId;
      var $pubComment;
      var $link;
      
      
      function PhyloCharacter($link, $objId) {
          parent::Collection($link, $objId);
          $this->link = $link;
          $this->id = $objId;
          if ($objId != null)
              $this->LoadCharacterFromDB($objId);
      }
      
      function getId() {
          return $this->id;
      }
      
      function setId($id) {
          $this->id = $id;
      }
      
      function getCharacterNumber() {
          return $this->characterNumber;
      }
      
      function setCharacterNumber($characterNumber) {
          $this->characterNumber = $characterNumber;
      }
      /*
       function getDevelopmentalStage() {
       return $this->developmentalStage;
       }
       
       function setDevelopmentalStage($developmentalStage) {
       $this -> developmentalStage = $developmentalStage;
       }
       */
      function getDiscrete() {
          return $this->discrete;
      }
      
      function setDiscrete($discrete) {
          $this->discrete = $discrete;
      }
      /*
       function getForm() {
       return $this->form;
       }
       
       function setForm($form) {
       $this -> form = $form;
       }
       */
      function getLabel() {
          return $this->label;
      }
      
      function setLabel($label) {
          $this->label = $label;
      }
      
      function getOrdered() {
          return $this->ordered;
      }
      
      function setOrdered($ordered) {
          $this->ordered = $ordered;
      }
      
      function getPubComment() {
          return $this->pubComment;
      }
      
      function setPubComment($pubComment) {
          $this->pubComment = $pubComment;
      }
      
      
      function getPublicationId() {
          return $this->publicationId;
      }
      
      function getPublication() {
          if ($this->getPublicationId() != null) {
              $query = "Select * from Publication where id=" . $this->getPublicationId();
              $result = mysqli_query($this->link, $query);
              $row = mysqli_fetch_array($result);
              return $row;
          } else
              return null;
      }
      
      function setPublicationId($publicationId) {
          $this->publicationId = $publicationId;
      }
      
      function LoadCharacterFromDB($id) {
          $query = "select BaseObject.*,MbCharacter.* from MbCharacter,BaseObject where BaseObject.id=" . $id;
          $query .= " and MbCharacter.id=" . $id;
          $result = mysqli_query($this->link, $query);
          if (!$result)
              echo "Problems querying the database";
          else {
              
              if (mysqli_num_rows($result) < 1)
                  echo "There is no character with that id in the database";
              else {
                  
                  $row = mysqli_fetch_array($result);
                  $this->setLabel($row['label']);
                  $this->setCharacterNumber($row['characterNumber']);
                  $this->setDiscrete($row['discrete']);
                  $this->setOrdered($row['ordered']);
                  $this->setPublicationId($row['publicationId']);
                  $this->setPubComment($row['pubComment']);
              }
              $query = "select * from CollectionObjects where collectionId=" . $id . " order by objectOrder";
              $result = mysqli_query($this->link, $query);
              if (!$result)
                  echo "Problems querying the database";
              else {
                  
                  $numRows = mysqli_num_rows($result);
                  if ($numRows > 0) {
                      for ($i = 0; $i < $numRows; $i++) {
                          $row = mysqli_fetch_array($result);
                          $colObj = new CollectionObject($this->link, $this, $id);
                          $colObj->setObjectTitle($row['objectTitle']);
                          $colObj->setObjectRole($row['objectRole']);
                          $colObj->setObjectTypeId($row['objectTypeId']);
                          $colObj->setCollectionId($row['collectionId']);
                          $colObj->setObjectId($row['objectId']);
                          $colObj->setObjectOrder($row['objectOrder']);
                          $collectionObjects[] = $colObj;
                      }
                      $this->setObjects(null);
                      $this->setObjects($collectionObjects);
                      $obj = $this->getObjects();
                  }
              }
          }
      }
      
      //function to get all the states and images from the character as objects
      function getObjectsFromCharacter() {
          //$charObjectList[] = $this->getObjectId(); 
          //the list contains state id's (make all the code to work with id's in collection objects)
          $stateList = $this->getCharacterStates();
          for ($i = 0; $i < count($stateList); $i++) {
              //function changed to work with objects instead with id's
              $newState = new CharacterState($this->link, $stateList[$i]->getObjectId());
              $stateObjectList = array_values($newState->getImages());
              $charObjectList[] = $stateList[$i];
              $charObjectList = array_merge($charObjectList, $stateObjectList);
          }
          return $charObjectList;
      }
      
      //returns an array of object values
      function getIdsFromCharacter() {
          $this->LoadCharacterFromDB($this->getId());
          //the list contains state id's (make all the code to work with id's in collection objects)
          $stateList = $this->getCharacterStates();
          for ($i = 0; $i < count($stateList); $i++) {
              //function changed to work with objects instead with id's
              $newState = new CharacterState($this->link, $stateList[$i]->getObjectId());
              $stateImages = $newState->getImages();
              if ($stateImages != null) {
                  $stateObjectList = array_values($stateImages);
                  $charObjectList[] = $stateList[$i];
                  $charObjectList = array_merge($charObjectList, $stateObjectList);
              } else
                  $charObjectList[] = $stateList[$i];
          }
          for ($i = 0; $i < count($charObjectList); $i++) {
              $obj = $charObjectList[$i];
              $charIdList[$i]['objectId'] = $obj->getObjectId();
              $charIdList[$i]['collectionId'] = $obj->getCollectionId();
              $charIdList[$i]['objectTypeId'] = $obj->getObjectTypeId();
              $charIdList[$i]['objectOrder'] = $obj->getObjectOrder();
              $charIdList[$i]['objectRole'] = $obj->getObjectRole();
              $charIdList[$i]['objectTitle'] = $obj->getObjectTitle();
          }
          return $charIdList;
      }
      
      
      //function to update the character
      //and save the order of the states, images ,annotations etc...
      function updateCharacter($newList) {
          $this->LoadCharacterFromDB($this->getId());
          if ($this->validateCharacter($newList)) {
              $this->OrganizeList($newList);
              $this->ReorderList($newList);
              //exit();
              return true;
          } else
              return false;
      }
      
      
      //function to see if the character is valid
      //the same number of elements and every element in one list belongs to
      //the other list
      function validateCharacter($newList) {
          $dbList = $this->getIdsFromCharacter();
          if (count($dbList) != count($newList)) {
              return false;
          } else {
              
              for ($j = 0; $j < count($newList); $j++) {
                  for ($i = 0; $i < count($dbList); $i++) {
                      if ($newList[$j] != $dbList[$i]['objectId']) {
                          continue;
                      } else
                          break;
                  }
                  if ($i == count($dbList))
                      return false;
                  else
                      continue;
              }
              return true;
          }
      }
      
      //function to reorganize the list and delete the states that where
      //in some other collection before the update
      function OrganizeList($newList) {
          $indexList = $this->getIndexForState($newList);
          for ($j = 0; $j < count($indexList) - 1; $j++) {
              $counter = 0;
              //run the last state separatelly from the for loop
              for ($i = $indexList[$j] + 1; $i < $indexList[$j + 1]; $i++) {
                  if ($this->imageInState($newList[$indexList[$j]], $newList[$i])) {
                      $counter++;
                      continue;
                  } else {
                      
                      $this->putImageInState($newList[$indexList[$j]], $newList[$i], $i);
                      $otherState = $this->imageInOtherState($newList[$indexList[$j]], $newList[$i]);
                      if ($otherState == null)
                          continue;
                      else {
                          
                          $this->deleteObjectFromCollection($otherState, $newList[$i]);
                          $this->ReorderCollection($newList[$indexList[$j]]);
                      }
                      $counter++;
                  }
              }
          }
          //the last state with the images
          for ($i = $indexList[count($indexList) - 1] + 1; $i < count($newList); $i++) {
              $lastSt = count($indexList) - 1;
              if ($this->imageInState($newList[$indexList[$lastSt]], $newList[$i])) {
                  $counter++;
                  continue;
              } else {
                  
                  $this->putImageInState($newList[$indexList[$lastSt]], $newList[$i], $i);
                  $otherState = $this->imageInOtherState($newList[$indexList[$lastSt]], $newList[$i]);
                  if ($otherState == null)
                      continue;
                  else {
                      
                      $this->deleteObjectFromCollection($otherState, $newList[$i]);
                      $this->ReorderCollection($newList[$indexList[$lastSt]]);
                  }
                  $counter++;
              }
          }
      }
      
      function ReorderList($newList) {
          global $link;
          $indexList = $this->getIndexForState($newList);
          //update the order of the states
          for ($i = 0; $i < count($indexList); $i++) {
              $query = "update CollectionObjects set objectOrder=" . $i . " where collectionId=" . $this->getId();
              $query .= " and objectId=" . $newList[$indexList[$i]];
              mysqli_query($link, $query);
          }
          for ($j = 0; $j < (count($indexList) - 1); $j++) {
              $counter = 0;
              for ($i = $indexList[$j] + 1; $i < $indexList[$j + 1]; $i++) {
                  $query = "update CollectionObjects set objectOrder=" . $counter . " where collectionId=" . $newList[$indexList[$j]];
                  $query .= " and objectId=" . $newList[$i];
                  mysqli_query($link, $query);
                  $counter++;
              }
          }
          //run the reordering separately for the last state
          $lastSt = count($indexList) - 1;
          $counter = 0;
          for ($i = $indexList[$lastSt] + 1; $i < count($newList); $i++) {
              $query = "Update CollectionObjects set objectOrder=" . $counter . " where collectionId=" . $newList[$indexList[$lastSt]];
              $query .= " and objectId=" . $newList[$i];
              mysqli_query($link, $query);
              $counter++;
          }
          $updatedCharacter = new PhyloCharacter($link, $this->getId());
          $this->setObjects($updatedCharacter->getObjects());
      }
      
      function putImageInState($state, $image, $index) {
          global $link;
          $objType = $this->getObjectTypeId($image);
          $title = $this->getObjectTitleFrom($image);
          if ($title == null) {
              $idArray = array("id" => $image, "objectTypeId" => "Image");
              $title = getObjectTitle($idArray, null);
          }
          $role = "image";
          $query = "CALL `CollectionObjectsInsertK`(" . $state . "," . $image . "," . $index . ", \"" . $objType . "\",\"" . $title . "\",\"" . $role . "\")";
          mysqli_multi_query($this->link, $query);
      }
      
      //function to add Image to character
      function addImageToCharacter($imageList) {
          global $link;
          $query = "SELECT objectId from CollectionObjects where collectionId=" . $this->getId() . " and objectTitle='Undesignated state'";
          $result = mysqli_query($link, $query);
          $row = mysqli_fetch_array($result);
          $id = $row['objectId'];
          $query = "SELECT count(*) as position from CollectionObjects where collectionId=" . $row['objectId'] . " and objectTypeId='Image'";
          $result = mysqli_query($link, $query);
          $row = mysqli_fetch_array($result);
          $order = $row['position'];
          for ($i = 0; $i < count($imageList); $i++) {
              $this->putImageInState($id, $imageList[$i]['id'], $order);
              $order++;
          }
      }
      
      
      function imageInOtherState($newState, $image) {
          global $link;
          $query = "select collectionId from CollectionObjects where objectId=" . $image . " and collectionId!=" . $newState;
          $results = mysqli_query($link, $query);
          $numRows = mysqli_num_rows($results);
          if ($numRows < 1)
              return null;
          else {
              
              for ($i = 0; $i < $numRows; $i++) {
                  $rows = mysqli_fetch_array($results);
                  //check which of the states belong to this character and take care only of that image
                  $query = "select objectId from CollectionObjects where objectId=" . $rows['collectionId'] . " and collectionId=" . $this->getId();
                  $result = mysqli_query($link, $query);
                  if (mysqli_num_rows($result) < 1)
                      continue;
                  else {
                      
                      $row = mysqli_fetch_array($result);
                      return $row['objectId'];
                  }
              }
          }
          return null;
      }
      
      function imageInState($state, $image) {
          global $link;
          $query = "select collectionId from CollectionObjects where collectionId=" . $state . " and objectId=" . $image;
          $result = mysqli_query($link, $query);
          if (!$result) {
              echo "problems querying the database";
              return false;
          } else {
              
              $numRow = mysqli_num_rows($result);
              if ($numRow < 1)
                  return false;
              else {
                  
                  return true;
              }
          }
      }
      
      //function that returns the state id based on the imageid and phylochar id
      function getStateForImage($image) {
          $stateList = $this->getCharacterStates();
          for ($i = 0; $i < count($stateList); $i++) {
              if ($this->imageInState($stateList[$i]->getObjectId(), $image))
                  return $stateList[$i]->getObjectId();
              else
                  continue;
          }
          return null;
      }
      
      //function to return index of the state bars from user interface (checked works correctly)
      function getIndexForState($newList) {
          global $link;
          $stateIndex = array();
          for ($i = 0; $i < count($newList); $i++) {
              $query = "select objectTypeId from BaseObject where id=" . $newList[$i];
              $result = mysqli_query($link, $query);
              if (!$result)
                  echo "Problems querying the database";
              else {
                  
                  $row = mysqli_fetch_array($result);
                  if ($row['objectTypeId'] == "CharacterState") {
                      $stateIndex[] = $i;
                  } else
                      continue;
              }
          }
          return $stateIndex;
      }
      
      function ReorderState($stateId) {
          global $link;
          $query = "Select objectId from CollectionObjects where collectionId=" . $stateId;
          $result = mysqli_query($link, $query);
          $rows = mysqli_num_rows($result);
          if ($rows > 0) {
              for ($i = 0; $i < $rows; $i++) {
                  $row = mysqli_fetch_array($result);
                  $id = $row['objectId'];
                  $query = "Update CollectionObjects set objectOrder=" . $i . " where collectionId=" . $stateId . " and objectId=" . $id;
                  mysqli_query($link, $query);
              }
          }
      }
      
      
      //make CharacterState object from a set of images
      function makeState($images, $charStateValue, $title) {
          global $link;
          $stateImages = array();
          $collectionId = null;
          
          $query = "SELECT objectId from CollectionObjects where collectionId=" . $this->getId();
          $query .= " and objectRole='state' and objectTitle='Undesignated state'";
          $result = mysqli_query($link, $query);
          $row = mysqli_fetch_array($result);
          $nullState = $row['objectId'];
          
          for ($i = 0; $i < count($images); $i++) {
              //echo "image id is ".$images[$i];
              $newObj = new CollectionObject($link, $collectionId, $images[$i]);
              $newObj->setObjectOrder($i);
              $newObj->setObjectTypeId($this->getObjectTypeId($images[$i]));
              $newObj->setObjectTitle($this->getObjectTitleFrom($images[$i]));
              $newObj->setObjectRole("image");
              $stateImages[] = $newObj;
              $this->deleteObjectFromCollection($nullState, $images[$i]);
          }
          
          $newState = new CharacterState($link, $collectionId);
          $newState->setUserId($this->getUserId());
          $newState->setGroupId($this->getGroupId());
          $newState->setSubmittedBy($this->getSubmittedBy());
          $newState->setDateToPublish($this->getDateToPublish());
          $newState->setName($title);
          $newState->setCharStateValue($charStateValue);
          $newState->setObjects($stateImages);
          $id = $newState->saveCharacterState();
          //call collection object procedure to add state to the character
          $query = "SELECT max(objectOrder) as objectOrder From CollectionObjects where collectionId=" . $this->getId();
          $query .= " and objectRole='state'";
          //echo "\n".$query."\n";
          $result = mysqli_query($link, $query);
          $row = mysqli_fetch_array($result);
          $order = $row['objectOrder'] + 1;
          $objectType = "CharacterState";
          $role = "state";
          $query = "CALL `CollectionObjectsInsertK`(" . $this->getId() . "," . $id . "," . $order . ",\"";
          $query .= $objectType . "\",\"" . $title . "\",\"" . $role . "\")";
          mysqli_multi_query($this->link, $query);
          
          $this->ReorderCollection($nullState);
      }
      
      //make set of characterStates by providing the list of dividers and set of values
      //needs to be changed to fit new collection object constructor
      function makeStates($dividers, $values) {
          $bar1 = 0;
          $bar2 = 0;
          $stateImages[] = array();
          $charStates[] = array();
          
          for ($i = 0; $i < count($dividers); $i++) {
              $bar2 = $dividers[$i];
              $newState = new CharacterState();
              for ($j = $bar1; $j < $bar2; $j++) {
                  $newObj = new CollectionObject($newState, getImages()->get($j));
                  $newObj->setObjectRole("image");
                  $newObj->setObjectOrder($j);
                  $stateImages[] = $newObj;
              }
              $bar1 = $bar2;
              $newState->setObjects($stateImages);
              $newState->setCharStateValue($values[$i]);
              $charStates[] = $newState;
          }
          return $charStates;
      }
      
      //function to find the title of the object
      function getObjectTitleFrom($imageId) {
          global $link;
          $title = null;
          $states = $this->getCharacterStates();
          //echo "there are ".count($states)." number of states";
          for ($i = 0; $i < count($states); $i++) {
              $state = $states[$i];
              $stateId = $state->getObjectId();
              $query = "select objectTitle from CollectionObjects where collectionId=" . $stateId . " and objectId=" . $imageId;
              $result = mysqli_query($link, $query);
              if (mysqli_num_rows($result) > 0) {
                  $row = mysqli_fetch_array($result);
                  //echo "title is ".$row['objectTitle'];
                  $title = $row['objectTitle'];
              } else
                  continue;
          }
          
          return $title;
      }
      
      //function to find the type of the object
      function getObjectTypeId($imageId) {
          global $link;
          $type = null;
          $query = "select objectTypeId from BaseObject where id=" . $imageId;
          //echo "\n".$query."\n";
          $result = mysqli_query($link, $query);
          if (!$result)
              echo "problems querying the data base";
          else {
              
              $row = mysqli_fetch_array($result);
              //echo "type is ".$row['objectTypeId'];
              $type = $row['objectTypeId'];
          }
          return $type;
      }
      
      
      //make the state part of the character, this does not save the 
      //character just makes it a collection object in the list of
      //collection objects associated with the character 
      
      function addState($state, $name) {
          // remove common images from character collection
          
          $characterObjects = array_values($this->getObjects());
          $stateObjects = array_values($state->getObjects());
          
          for ($i = 0; $i < count($stateObjects); $i++) {
              $stateObj = $stateObjects[$i];
              if ($stateObj->getObjectTypeId() == "Image") {
                  for ($j = 0; $j < count($characterObjects); $j++) {
                      $charObj = $characterObjects[$j];
                      if ($charObj->getObjectTypeId() == "Image" && $stateObj->getObjectId() == $charObj->getObjectId()) {
                          $characterObjects->remove($j);
                          $j--;
                      }
                  }
              }
          }
          // insert this at the end of the states, before the images
          $newState = new CollectionObject($this, $state, $this->link);
          $newState->setObjectRole("state");
          $newState->setObjectTitle($name);
          if (count($characterObjects) == 0)
              $characterObjects->addObject($newState->getObject());
          else {
              
              $i = 0;
              
              while ($i < count($characterObjects)) {
                  $charObj = $characterObjects[$i];
                  if ($charObj->getObjectTypeId() == "CharacterState")
                      $i++;
                  else
                      break;
              }
              $newState->setObjectOrder($i);
              $characterObjects[] = $newState;
          }
      }
      
      function getAllImages() {
          // get the images (and annotations) from "this" plus from the states
          // images and annotations all have role of an "image"
          global $link;
          
          $allImages[] = array();
          $stateImages[] = array();
          $allImages = array_values($this->getImages());
          if ($this->getCharacterStates() == null)
              return $allImages;
          $allStates = array_values($this->getCharacterStates());
          for ($i = 0; $i < count($allStates); $i++) {
              $query = "select objectId as id from CollectionObject where collectionId=" . $allStates[$i] . " and objectRole='image'";
              //echo $query;
              $result = mysqli_query($link, $query);
              if (!$result)
                  echo "Problems querying the database";
              else {
                  
                  $numrows = mysqli_num_rows($result);
                  if ($numrows < 1)
                      continue;
                  else {
                      
                      for ($index = 0; $index < $numrows; $index++) {
                          $row = mysqli_fetch_array($result);
                          $currentId = $row[$index];
                          $stateImages[] = $currentId;
                      }
                      $allImages = array_merge($allImages, $stateImages);
                  }
              }
          }
          return $allImages;
      }
      
      //function that returns a thumb associated with that character
      static function getThumb($characterId) {
          global $link;
          $query = "select objectId as id from CollectionObjects where collectionId=" . $characterId . " and objectRole='state'";
          $result = mysqli_query($link, $query);
          $row = mysqli_fetch_array($result);
          $stateId = $row['id'];
          $query = "select objectId from CollectionObjects where collectionId=" . $stateId . " and objectTypeId='Image' and objectOrder=0";
          $result2 = mysqli_query($link, $query);
          $row2 = mysqli_fetch_array($result2);
          return $row2['objectId'];
      }
      
      static function getImageIds($characterId) {
          global $link;
          $query = "select objectId as id from CollectionObjects where collectionId=" . $characterId . " and objectRole='state'";
          $result = mysqli_query($link, $query);
          $numrows = mysqli_num_rows($result);
          for ($index = 0; $index < $numrows; $index++) {
              $row = mysqli_fetch_array($result);
              $stateId = $row['id'];
              //this query might needs to be changed to ask for annotations to, so put role instead of type
              $query = "select count(*) as num from CollectionObjects where collectionId=" . $stateId . " and objectTypeId='Image'";
              // echo $query;
              $result2 = mysqli_query($link, $query);
              $row2 = mysqli_fetch_array($result2);
              $counter = $counter + $row2['num'];
          }
          return $counter;
      }
      
      function getImages() {
          // get images and annotations from "this"
          return $this->getObjectsByRole("image");
      }
      
      
      //make sure it returns the stateId not the state as an object
      function getCharacterStates() {
          //get characterStates from "this"
          return $this->getObjectsByRole("state");
      }
      
      
      function savePhyloCharacter() {
          $pulicationId = (!empty($this->publicationId)) ? $this->publicationId : "NULL";
          $this->keywords = str_replace('"', '\"', $this->keywords);
          $query = "CALL `PhyloCharacterInsert`(\"" . $this->label . "\",\"" . $this->characterNumber . "\",";
          $query .= $this->discrete . "," . $this->ordered . "," . $pulicationId . ",\"";
          $query .= $this->pubComment . "\",\"" . $this->name . "\",\"";
          $query .= $this->objectTypeId . "\",\"" . $this->description . "\"," . $this->submittedBy . "," . $this->groupId . "," . $this->userId . ",\"";
          $query .= $this->dateToPublish . "\",\"" . $this->objectLogo . "\",\"";
          $query .= $this->keywords . "\",\"" . $this->summaryHTML . "\",\"" . $this->thumbURL . "\", @oId)";
          
          //echo $query;
          $result = mysqli_multi_query($this->link, $query) or die(mysqli_error($this->link));
          
          if ($result) {
              if (mysqli_multi_query($this->link, "select @oId")) {
                  if ($idResult = mysqli_store_result($this->link)) {
                      $row = mysqli_fetch_row($idResult);
                      $id = $row[0];
                      for ($i = 0; $i < count($this->collectionObjects); $i++) {
                          $colObj = $this->collectionObjects[$i];
                          $query = "CALL `CollectionObjectsInsertK`(" . $id . "," . $colObj->getObjectId() . "," . $i . ",\"";
                          $query .= $colObj->getObjectTypeId() . "\",\"" . $colObj->getObjectTitle() . "\",\"";
                          $query .= $colObj->getObjectRole() . "\")";
                          
                          mysqli_multi_query($this->link, $query);
                          //printf("New phyloCharacter added successfully");
                      }
                  } else
                      echo "error 3 is " . mysqli_error($link);
              } else
                  echo "error 2 is " . mysqli_error($link);
          } else
              echo "error 1 is " . mysqli_error($this->link);
      }
      //end of savePhyloCharacter
      
      
      //function to delete the phylogenetic character from the Database
      function deleteCharacterCollectionFromDB() {
          $query = "Select objectId,objectTypeId from CollectionObjects where collectionId=" . $this->getId();
          $result = mysqli_query($this->link, $query);
          $rowNum = mysqli_num_rows($result);
          for ($i = 0; $i < $rowNum; $i++) {
              $row = mysqli_fetch_array($result);
              //deletes all rows in CollectionObjects table
              $this->deleteObjectFromCollection($row['objectId'], null);
              //change this so it will delete all the objects
              $this->deleteObjectFromTable("CharacterState", $row['objectId']);
              //name the objectType which is PhyloCharState
          }
          
          $this->deleteObjectFromCollection($this->getId(), null);
          $this->deleteObjectFromTable("MbCharacter", $this->getId());
          
          for ($i = 0; $i < $rowNum; $i++) {
              $this->deleteObjectFromTable("BaseObject", $row['objectId']);
          }
          $this->deleteObjectFromTable("BaseObject", $this->getId());
          $this->deleteObjectFromTable("Keywords", $this->getId());
		  $this->deleteObjectFromTable("Collection", $this->getId());
      }
      //end of function deleteCharacterCollectionFromDB
      
      //function to delete the phylogenetic character from the Database
      //but not the collection (applies to character created from an existing collection)
      function deleteCharacterFromDB() {
          $query = "Select objectId,objectTitle from CollectionObjects where collectionId=" . $this->getId();
          $result = mysqli_query($this->link, $query);
          $rowNum = mysqli_num_rows($result);
          for ($i = 0; $i < $rowNum; $i++) {
              $row = mysqli_fetch_array($result);
              $query = "Update CollectionObjects set collectionId=" . $this->getId() . " where collectionId=" . $row['objectId'];
              mysqli_query($this->link, $query);
              $query = "Update BaseObject set objectTypeId='Collection' where id=" . $this->getId();
              mysqli_query($this->link, $query);
              
              //add code to update the Keywords table 
              
              $this->deleteObjectFromTable("CharacterState", $row['objectId']);
              $this->deleteObjectFromTable("BaseObject", $row['objectId']);
          }
      }
      //end of function deleteCharacterFromDB
  }
  //end of PhyloCharacter
?>
