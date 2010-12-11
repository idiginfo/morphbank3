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
  //
  // @author KarolinaMJ
  // @date June 13th 2007
  //
  
  
  class Collection extends BaseObject {
      var $userId;
      var $groupId;
      //var $name;
      //array of collectionObjects
      var $collectionObjects = array();
      var $objectIds = array();
      var $link;
      
      function Collection($link, $colId) {
          parent::BaseObject($link, $colId);
          $this->link = $link;
      }
      
      //function to create the character after collection has been created
      //this function should be called when a character is made from existing collection
      //or if the character is made from a set of images first save collection is called
      //and then madeCharacterFromCollection
      
      function madeCharacterFromCollection($imageList)//besides imageList a list of values can be passed by the user interface
      //or after the character is created with null values the user is prompted 
      //to insert neccessary values
      
      {
          //update BaseObject objectTypeId to MbCharacter
          $query = "update BaseObject set objectTypeId='MbCharacter' where id=" . $this->getId();
          mysqli_query($this->link, $query);
          
          //update objectTypeId in the Keywords table
          $query = "update Keywords set objectTypeId='MbCharacter' where id=" . $this->getId();
          mysqli_query($this->link, $query);
          
          //create character record
          $query = "insert into MbCharacter(id) values(" . $this->getId() . ")";
          mysqli_query($this->link, $query);
          
          $query = "select objectId from CollectionObjects where collectionId=" . $this->getId() . " and objectRole='state' and objectOrder=0";
          $result = mysqli_query($this->link, $query);
          $numRows = mysqli_num_rows($result);
          
          if ($numRows < 1) {
              //Create state to put initial images
              $state = new CharacterState($this->link, null);
              //every character has one null state which contains the images that do not belong
              $state->setCharStateValue("nullState");
              //to any other state, the order in the character collection should remain 0
              $colArray = array();
              if ($imageList != null) {
                  for ($i = 0; $i < count($imageList); $i++) {
                      $colObj = new CollectionObject($this->link, $state, $imageList[$i]['id']);
                      $role = "image";
                      $colObj->setObjectRole('image');
                      //$colObj->setObjectRole($colObj->ObjectRole($imageList[$i]['id'])); change this when Steve starts populating objectRole 
                      $colObj->setObjectOrder($i);
                      $colObj->setObjectTitle($colObj->ObjectTitle($imageList[$i]['id'], $this->id));
                      $colObj->setObjectTypeId($colObj->ObjectType($imageList[$i]['id']));
                      $colArray[] = $colObj;
                  }
              }
              $state->setObjects($colArray);
              $state->setUserId($this->getUserId());
              $state->setGroupId($this->getGroupId());
              $state->setSubmittedBy($this->getSubmittedBy());
              $state->setName("Undesignated state");
              $state->setDateToPublish($this->getDateToPublish());
              $newStateId = $state->saveCharacterState();
              
              //made the state to point to the character
              $query = "CALL `CollectionObjectsInsertK`(" . $this->getId() . "," . $newStateId . ",0,'CharacterState','Undesignated state','state')";
              mysqli_multi_query($this->link, $query);
              
              //remove the collectionObject records where the collection id is the character for all the images now belonging to the
              //null state of the character
              for ($i = 0; $i < count($imageList); $i++) {
                  $this->deleteObjectFromCollection($this->getId(), $imageList[$i]['id']);
              }
          }
      }
      
      //function to create OTU from existing collection or from a set of selected images
      //otu list is a list of tsn's
      function madeOtuFromCollection($otuList) {
          //update BaseObject objectTypeId to Otu
          $query = "update BaseObject set objectTypeId='Otu' where id=" . $this->getId();
          mysqli_query($this->link, $query);
          
          //update objectTypeId in the Keywords table
          $query = "update Keywords set objectTypeId='Otu' where id=" . $this->getId();
          mysqli_query($this->link, $query);
          
          //create Otu record
          $query = "insert into Otu(id) values(" . $this->getId() . ")";
          mysqli_query($this->link, $query);
          
          //for every tsn in the list check if there is a baseobject
          //if yes grab the id if now create one and get the id
          $colArray = array();
          if ($otuList != null) {
              for ($i = 0; $i < count($otuList); $i++) {
                  //check the objectType ID to see if it is a Specimen or Taxa
                  
                  if ($otuList[$i]['objectTypeId'] != "Taxon") {
                      $query = "SELECT objectTypeId, name from BaseObject WHERE id=" . $otuList[$i]['id'];
                      $result = mysqli_query($this->link, $query);
                      if (mysqli_num_rows($result) > 0) {
                          $row = mysqli_fetch_array($result);
                          $objectTypeId = $row['objectTypeId'];
                          $id = $otuList[$i]['id'];
                          $name = $row['name'];
                          
                          //the following code snipet will not be needed when we start populating
                          //the name field in BaseObject
                          if (($name == null || $name == "") && $objectTypeId == "Specimen") {
                              $query = "Select Tree.scientificName from Specimen, Tree where Specimen.tsnId=Tree.tsn and Specimen.id=" . $id;
                              $row = mysqli_fetch_array(mysqli_query($this->link, $query));
                              $name = $row['scientificName'];
                          }
                      }
                  } else {
                      
                      //extract the usage and the scientific name for the taxon
                      $query = "select `usage`, scientificName from Tree where tsn=" . $otuList[$i]['id'];
                      $result = mysqli_query($this->link, $query);
                      $row = mysqli_fetch_array($result);
                      $status = $row['usage'];
                      $scientificName = $row['scientificName'];
                      
                      $query = "Select id from TaxonConcept where TaxonConcept.tsn=" . $otuList[$i]['id'];
                      $result = mysqli_query($this->link, $query);
                      if (mysqli_num_rows($result) > 0) {
                          $row = mysqli_fetch_array($result);
                          $id = $row['id'];
                      } else {
                          
                          
                          $query = "CALL `TaxonConceptInsert`(" . $otuList[$i]['id'] . ",NULL,'" . $status . "'," . $this->getSubmittedBy() . ",";
                          $query .= $this->getGroupId();
                          $query .= "," . $this->getUserId() . ",NULL, NOW(),'TaxonConcept',NULL,NULL,NULL,NULL,'" . $scientificName . "', @oId)";
                          
                          $result = mysqli_multi_query($this->link, $query);
                          if ($result)
                              if (mysqli_multi_query($this->link, "select @oId"))
                                  if ($idResult = mysqli_store_result($this->link)) {
                                      $row = mysqli_fetch_row($idResult);
                                      $id = $row[0];
                                  }
                      }
                      $objectTypeId = 'TaxonConcept';
                      $name = $scientificName;
                  }
                  //this code is not working properly needs to be changed
                  //this need to be changed it will not always be TaxonConcept
                  
                  $colObj = new CollectionObject($this->link, $this->getId(), $id);
                  if ($objectTypeId != "" && $objectTypeId != null) {
                      $colObj->setObjectRole(strtolower($objectTypeId));
                      $colObj->setObjectTypeId($objectTypeId);
                      $colObj->setObjectTitle($name);
                  }
                  /*else{
                   $colObj->setObjectRole('taxonconcept');
                   $colObj->setObjectTitle($scientificName);
                   $colObj->setObjectTypeId('TaxonConcept');
                   }*/
                  $colObj->setObjectOrder($i);
                  $colArray[] = $colObj;
                  
                  $query = "CALL `CollectionObjectsInsertK`(" . $this->getId() . "," . $id . "," . $i . ",'" . $colObj->getObjectTypeId() . "','";
                  $query .= $colObj->getObjectTitle() . "','" . $colObj->getObjectRole() . "')";
                  
                  mysqli_multi_query($this->link, $query);
              }
              $this->setObjects($colArray);
          }
      }
      //end of function madeOtufromCollection 
      
      //function to return ordered list of the Id's with the type
      //apparently not used anywhere so it will not break
      
      function getFullObjectList() {
          $objectList = null;
          $counter = 0;
          for ($i = 0; $i < count($collectionObjects); $i++) {
              $colObj = $collectionObjects[$i];
              if (!CheckIfCollection($colObj->getObjectId())) {
                  $objectList[$counter][0] = $colObj->getObjectTypeId();
                  $objList[$counter][1] = $colObj->getObjectId();
                  $counter++;
              } else {
                  
                  $subcollection = new Collection($this->link, $colObj->getObjectId());
                  $subColObjList = $subcollection->getFullObjectList();
                  $objectList = array_merge($objectList, $subColObjList);
                  $counter = $counter + count($subColObjList);
              }
          }
          return $objectList;
      }
      
      function CheckIfCollection($objId) {
          $query = "select id from Collection where id=" . $objId;
          $result = mysqli_query($this->link, $query);
          if (!$result) {
              echo "problems querying the database";
              return false;
          } else {
              
              if (mysqli_num_rows($result) > 0)
                  return true;
              else
                  return false;
          }
      }
      
      function addObject($obj) {
          $collObj = new CollectionObject($this, $obj);
          $collectionObjects[] = $collObj;
      }
      
      function addObjectOnPosition($obj, $index) {
          $tempArray1[] = array();
          $tempArray2[] = array();
          
          $collObj = new CollectionObject($this, $obj);
          if ($index > count($collectionObjects))
              $collectionObjects[] = collObj;
          else {
              
              for ($i = 0; $i < $index; $i++)
                  $tempArray1[$i] = $collectionObjects[$i];
              for ($i = index; $i < count($collectionObjects); $i++)
                  $tempArray2[$i] = $collectionObjects[$i];
              $tempArray1[] = $collObj;
              $collectionObjects = array_merge($tempArray1, $tempArray2);
          }
          updateObjectOrder();
      }
      
      function addObjectWithRole($obj, $role) {
          $collObj = new CollectionObject($this, $obj);
          $collObj->setObjectRole($role);
          $colObj->setObjectOrder(count($collectionObjects));
          $collectionObjects[] = $obj;
      }
      
      function moveObject($index, $newIndex) {
          $colObj = $collectionObjects[$index];
          addObject($colObj->getObject(), $newIndex);
          if ($newIndex < $index)
              $index++;
          removeObject($index);
      }
      
      function moveObjectInNew($index, $newIndex, $newCollection) {
          $colObj = $collectionObjects[$index];
          $obj = $colObj->getObject();
          $newCollection->addObject($obj, $newIndex);
          removeObject($index);
      }
      
      function moveObjectInNewWithRole($index, $newIndex, $newCollection, $newRole) {
          moveObject($index, $newIndex, $newCollection);
          $colObjects = $newCollection->getObjects();
          $colObjects[$newIndex]->setObjectRole($newRole);
      }
      
      function updateObjectOrder() {
          $collectionObjects = $this->getObjects();
          for ($i = 0; $i < count($collectionObjects); $i++) {
              $collectionObjects[$i]->setObjectOrder($i);
          }
      }
      
      function removeObjectByPlace($index) {
          $tempArray1 = array();
          $tempArray2 = array();
          $collectionObjects = $this->getObjects();
          for ($i = 0; $i < $index; $i++)
              $tempArray1[] = $collectionObjects[$i];
          for ($i = $index + 1; $i < count($collectionObjects); $i++)
              $tempArray2[] = $collectionObjects[$i];
          $collectionObjects = array_merge($tempArray1, $tempArray2);
          $this->updateObjectOrder();
      }
      
      function deleteObjectFromCollection($collection, $object) {
          global $link;
          
          if ($object != null) {
              $query = "Select objectTypeId, objectTitle from CollectionObjects where objectId=" . $object;
              $row = mysqli_fetch_array(mysqli_query($link, $query));
              
              if ($row['objectTypeId'] != "CharacterState") {
                  $query = "Delete from CollectionObjects where collectionId=" . $collection;
                  $query .= " and objectId=" . $object;
                  //echo $query."\n";
                  mysqli_query($this->link, $query);
              } else {
                  
                  if ($row['objectTitle'] != "Undesignated state") {
                      //assign all the objects to the undesignated state
                      $query = "Select objectId from CollectionObjects where collectionId=";
                      $query .= $this->getId() . " and objectTitle='Undesignated state'";
                      //echo $query;
                      $row = mysqli_fetch_array(mysqli_query($this->link, $query));
                      
                      $query = "Update CollectionObjects set collectionId=" . $row['objectId'] . " where collectionId=" . $object;
                      //echo $query;
                      mysqli_query($this->link, $query);
                      
                      $query = "Delete from CollectionObjects where collectionId=" . $this->getId() . " and objectId=" . $object;
                      //echo $query;
                      mysqli_query($this->link, $query);
                      
                      $this->deleteObjectFromTable("CharacterState", $object);
                      //change this to invoke delete baseobject inside delete object from Table
                      //if the object is BaseObject
                      $this->deleteObjectFromTable("BaseObject", $object);
                      $this->ReorderCollection($this->getId());
                  }
              }
          } else {
              
              $query = "Delete from CollectionObjects where collectionId=" . $collection;
              mysqli_query($this->link, $query);
          }
      }
      
      function ReorderCollection($collectionId) {
          global $link;
          $query = "select objectId from CollectionObjects where collectionId=" . $collectionId . " order by objectId";
          //echo $query;
          $result = mysqli_query($link, $query);
          $rows = mysqli_num_rows($result);
          //echo "rows in the result ".$rows;
          if ($rows > 0) {
              for ($i = 0; $i < $rows; $i++) {
                  $row = mysqli_fetch_array($result);
                  $id = $row['objectId'];
                  $query = "Update CollectionObjects set objectOrder=" . $i . " where collectionId=" . $collectionId . " and objectId=" . $id;
                  //echo $query;
                  mysqli_query($link, $query);
              }
          }
      }
      
      
      
      function deleteObjectFromTable($table, $id) {
          $query = "DELETE FROM " . $table . " WHERE id=" . $id;
          //echo $query;
          $result = mysqli_query($this->link, $query);
          if (!$result)
              echo "Problems deleting the record " . mysqli_error($this->link);
      }
      
      function removeObject($objectId) {
          $i = 0;
          for ($i = 0; $i < count($this->getObjects()); $i++) {
              $colObjects = $this->getObjects();
              if ($colObjects[$i]->getObjectId() == $objectId) {
                  $this->removeObjectByPlace($i);
                  break;
              } else
                  continue;
          }
      }
      
      //returns list of object Id's initialy
      //changed to return list of objects
      function getObjectsByRole($role) {
          $listByRole = null;
          $colObjects = array_values($this->collectionObjects);
          for ($i = 0; $i < count($this->collectionObjects); $i++) {
              $colObj = $colObjects[$i];
              if ($colObjects[$i]->getObjectRole() == $role) {
                  $listByRole[$i] = $colObjects[$i];
              }
          }
          return $listByRole;
      }
      
      //returns list of object Id's
      function getObjectsByType($objectTypeId) {
          $listByType = null;
          
          for ($i = 0; $i < count($this->collectionObjects); $i++) {
              if ($this->collectionObjects[$i]->getObjectTypeId() == $objectTypeId)
                  $listByType[$i] = $this->collectionObjects[$i]->getObject();
          }
          return $listByType;
      }
      /*
       function getName(){
       return $this->name;
       }
       
       function setName($name) {
       $this->name = $name;
       }
       */
      function getObjects() {
          return $this->collectionObjects;
      }
      
      function setObjects($objects) {
          //reset collectionObjects if previously set
          $this->collectionObjects = null;
          for ($i = 0; $i < count($objects); $i++) {
              $this->collectionObjects[$i] = $objects[$i];
          }
      }
      
      function getObject($index) {
          return $this->collectionObjects[$index]->getObject();
      }
      
      function getUserId() {
          return $this->userId;
      }
      
      function setUserId($userId) {
          $this->userId = $userId;
      }
      
      function getGroupId() {
          return $this->groupId;
      }
      
      function setGroupId($groupId) {
          $this->groupId = $groupId;
      }
      
      function saveCollection() {
          $query = "CALL `BaseObjectInsert`(\"" . $this->userId . "\"," . $this->groupId . "," . $this->dateToPublish . "," . $this->objectTypeId . ",\"";
          $query .= $this->description . "\",\"" . $this->submittedBy . "\",\"";
          $query .= $this->objectLogo . "\",\"" . $this->keywords . "\",\"";
          $query .= $this->summaryHTML . "\",\"" . $this->thumbURL . "\", \"" . $this->name . "\",@oId)";
          
          //echo $query;
          $result = mysqli_multi_query($this->link, $query);
          
          if ($result) {
              if (mysqli_multi_query($this->link, "select @oId")) {
                  if ($idResult = mysqli_store_result($this->link)) {
                      $row = mysqli_fetch_row($idResult);
                      $id = $row[0];
                      for ($i = 0; $i < count($collectionObjects); $i++) {
                          $colObj = $collectionObjects[$i];
                          $query = "CALL `CollectionObjectsInsertK`(" . $id . "," . $collObj->getId() . "," . $i . ",\"";
                          $query .= $collObj->getObjectTypeId() . "\",\"" . $collObj->getObjectTitle() . "\",\"";
                          $query .= $collObj->getObjectRole() . "\")";
                          
                          //echo $query;
                          if (mysqli_multi_query($this->link, $query))
                              printf("New phyloCharacter added successfully");
                      }
                  } else
                      echo "error 3 is " . mysqli_error($this->link);
              } else
                  echo "error 2 is " . mysqli_error($this->link);
          } else
              echo "error 1 is " . mysqli_error($this->link);
      }
  }
  //end of class Collection.php
?>
