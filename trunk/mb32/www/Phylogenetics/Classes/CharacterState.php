<?php
  //
  //@author Karolina MJ
  //@date June 7th 2007 
  //
  
  
  class CharacterState extends Collection {
      var $charStateValue;
      var $link;
      
      function CharacterState($link, $objId) {
          parent::Collection($link, $objId);
          $this->link = $link;
          if ($objId != null)
              $this->LoadCharacterStateFromDB($objId);
          else {
              
              $this->setObjectTypeId("CharacterState");
          }
      }
      
      function getCharStateValue() {
          return $this->charStateValue;
      }
      
      function setCharStateValue($charStateValue) {
          $this->charStateValue = $charStateValue;
      }
      
      function LoadCharacterStateFromDB($id) {
          $query = "select BaseObject.*,CharacterState.charStateValue from BaseObject,CharacterState where";
          $query .= " BaseObject.id=" . $id . " and CharacterState.id=" . $id;
          $result = mysqli_query($this->link, $query);
          if (!$result)
              echo "Problems querying the database";
          else {
              
              $numRows = mysqli_num_rows($result);
              if ($numRows < 1)
                  echo "There is no characterState with that id in the data base";
              else {
                  
                  $row = mysqli_fetch_array($result);
                  $this->setId($row['id']);
                  $this->setCharStateValue($row['charStateValue']);
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
                      $this->setObjects($collectionObjects);
                  }
              }
          }
      }
      
      
      //method to return all images and annotation for the state
      function getImages() {
          // get images and annotations from this
          return $this->getObjectsByRole("image");
      }
      
      
      /**
       * @method to add new PhyloCharState record to the data base using
       *         the PhyloCharStateInsert procedure
       * @return the id of the newly created characterState record in the data base
       */
      
      function saveCharacterState() {
          $id = null;
          
          $this->keywords = str_replace('"', '\"', $this->keywords);
          
          $query = "CALL `PhyloCharacterStateInsert`(\"" . $this->charStateValue . "\",\"" . $this->name . "\"," . $this->submittedBy . ",";
          $query .= $this->groupId . "," . $this->userId . ",\"" . $this->dateToPublish . "\",\"";
          $query .= $this->description . "\",\"" . $this->objectTypeId . "\",\"" . $this->objectLogo . "\",\"" . $this->keywords . "\",\"";
          $query .= $this->summaryHTML . "\",\"";
          $query .= $this->thumbURL . "\", @oId)";
          
          $result = mysqli_multi_query($this->link, $query);
          
          if (!$result)
              echo "error 1 is " . mysqli_error($this->link);
          else {
              
              if (!mysqli_multi_query($this->link, "select @oId"))
                  echo "error 2 is " . mysqli_error($this->link);
              else {
                  
                  $idResult = mysqli_store_result($this->link);
                  $row = mysqli_fetch_row($idResult);
                  $id = $row[0];
                  
                  for ($i = 0; $i < count($this->collectionObjects); $i++) {
                      $colObjects = $this->getObjects();
                      $obj = $colObjects[$i];
                      $query = "CALL `CollectionObjectsInsertK`(" . $id . "," . $obj->getObjectId() . "," . $i . ",\"";
                      $query .= $obj->getObjectTypeId() . "\",\"" . $obj->getObjectTitle() . "\",\"";
                      $query .= $obj->getObjectRole() . "\")";
                      //echo $query;
                      
                      if (mysqli_multi_query($this->link, $query))
                          continue;
                      else
                          echo "error 4 is " . mysqli_error($this->link);
                  }
              }
          }
          return $id;
      }
      //end of save characterState
  }
  //end of class CharacterState
?>
