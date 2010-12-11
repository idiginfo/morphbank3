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
  //Class to create and manipulate the OTU's 
  //@author Karolina MJ
  //@date June 4th 2007
  //
  
  
  class Otu extends Collection {
      var $label;
      var $link;
      
      function Otu($link, $objId) {
          parent::Collection($link, $objId);
          $this->link = $link;
          if ($objId != null)
              $this->LoadOtuFromDataBase($objId);
      }
      
      function getLabel() {
          return $this->label;
      }
      function setLabel($label) {
          $this->label = $label;
      }
      
      function getTaxons() {
          return $this->getObjectsByRole("taxonconcept");
      }
      
      /*
       function addTaxon($taxon){
       $this->addObject($taxon, "taxonconcept");
       }
       */
      
      function LoadOtuFromDataBase($id) {
          $query = "select BaseObject.*,Otu.label from BaseObject,Otu where BaseObject.id=Otu.id and BaseObject.id=" . $id;
          $result = mysqli_query($this->link, $query);
          if (!$result)
              echo "problems qurying the database";
          else {
              
              $numRows = mysqli_num_rows($result);
              if ($numRows < 1)
                  echo "There is no Otu with that id in the database";
              else {
                  
                  $row = mysqli_fetch_array($result);
                  $this->setId($row['id']);
                  $this->setLabel($row['label']);
                  
                  $query = "select * from CollectionObjects where collectionId=" . $id;
                  
                  $result = mysqli_query($this->link, $query);
                  if (!$result)
                      echo "problems querying the database";
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
      }
      
      //function to add new Objects (Taxa or Specimens to the OTU)
      //firstcheck the type of object and handle properly 
      function addObjectToOtu($objectList) {
          $query = "select max(objectOrder)+1 as objectOrder from CollectionObjects where collectionId=" . $this->getId();
		  $result = mysqli_query($this->link, $query) or die(mysqli_error($this->link).' '.$query);
          
		  
		  if ($result) {
		  	$row = mysqli_fetch_array($result);
          	$order = $row['objectOrder'];
		  } else {
		  	$order = 1;
		  }
		  
		  $order = (empty($order)) ? 1 : $order;
		  
          for ($i = 0; $i < count($objectList); $i++) {
              if ($objectList[$i]['objectTypeId'] == 'Taxon') {
                  $id = $this->TaxonObject($objectList[$i]['id']);
                  $role = "taxonconcept";
                  $objTypeId = "TaxonConcept";
                  $query = "Select scientificName from Tree where tsn=" . $objectList[$i]['id'];
                  $result = mysqli_query($this->link, $query);
                  $row = mysqli_fetch_array($result);
                  $title = $row['scientificName'];
              }
              if ($objectList[$i]['objectTypeId'] == 'Specimen') {
                  $id = $objectList[$i]['id'];
                  $role = "specimen";
                  $objTypeId = "Specimen";
                  $query = "Select scientificName from Tree, Specimen where Specimen.tsnId = Tree.tsn and Specimen.id=" . $id;
                  $result = mysqli_query($this->link, $query) or die(mysqli_error($this->link).' '.$query);
                  $row = mysqli_fetch_array($result);
                  $title = $row['scientificName'];
              }
              
              $query = "CALL `CollectionObjectsInsertK`(" . $this->getId() . "," . $id . "," . $order . ", \"" . $objTypeId . "\",\"" . $title . "\",\"" . $role . "\")";
			  $procedureResult = mysqli_multi_query($this->link, $query)  or die(mysqli_error($this->link).' '.$query);
              if ($procedureResult)
                  $order++;
              else
                  return false;
          }
          TaxaKeywords($this->link, null, $this->getId());
          return true;
      }
      //end of function to add new Objects (Taxa or Specimens to the OTU)
      
      //This function selects Taxon name id for a given tsn
      //if the id does not exists creates new object and returns
      //the id
      function TaxonObject($tsn) {
          $id = -1;
          
          $query = "SELECT * FROM TaxonConcept,BaseObject WHERE TaxonConcept.id=BaseObject.id AND tsn=" . $tsn;
          $result = mysqli_query($this->link, $query);
          if (!$result) {
              echo 'Problems querying the database1';
          } else {
              
              $numRows = mysqli_num_rows($result);
              if ($numRows < 1) {
                  //the record does not exist in TaxonConcept
                  
                  //check what is the status of that tsn in the moment
                  $query = "SELECT `usage`,scientificName FROM Tree WHERE tsn=" . $tsn;
                  $result = mysqli_query($this->link, $query);
                  if (!$result)
                      echo 'Problems querying the database';
                  else {
                      
                      $row = mysqli_fetch_array($result);
                      $status = $row['usage'];
                      $name = $row['scientificName'];
                      $nameSpace = "NULL";
                      $objectTypeId = "TaxonConcept";
                      $description = "Taxon concept for tsn " . $tsn;
                      
                      $query = "CALL `TaxonConceptInsert`(" . $tsn . "," . $nameSpace . ",\"" . $status . "\"," . $this->getSubmittedBy() . ",";
                      $query .= $this->getGroupId() . ",";
                      $query .= $this->getUserId() . ",\"" . $description . "\",NOW(),\"" . $objectTypeId . "\",NULL,NULL,NULL,NULL,\"";
                      $query .= $name . "\", @oId)";
                      
                      $result = mysqli_multi_query($this->link, $query);
                      if ($result) {
                          if (mysqli_multi_query($this->link, "select @oId")) {
                              if ($idResult = mysqli_store_result($this->link)) {
                                  $row = mysqli_fetch_row($idResult);
                                  $id = $row[0];
                              } else
                                  echo "error is " . mysqli_error($this->link);
                          } else
                              echo "error is " . mysqli_error($this->link);
                      } else
                          echo "error is " . mysqli_error($this->link);
                  }
              } else {
                  
                  $row = mysqli_fetch_array($result);
                  $id = $row['id'];
              }
          }
          return $id;
      }
      //end of function TaxonObject
      
      
      /**
       * @method to add new Otu record to the data base using the OtuInsert procedure
       * @return the id of the newly created otu record in the data base
       */
      
      function saveOtu() {
          $query = "CALL `OtuInsert`(\"" . $this->getLabel() . "\",\"" . $this->getName() . "\"," . $this->getSubmittedBy() . "," . $this->getGroupId() . ",";
          $query .= $this->getUserId() . ",\"" . $this->getDescription() . "\",\"" . $this->getObjectTypeId() . "\",\"" . $this->getObjectLogo() . "\",\"";
          $query .= $this->getKeywords() . "\",\"" . $this->getSummaryHTML() . "\",\"" . $this->getThumbURL() . "\", @oId)";
          
          echo $query;
          
          $result = mysqli_multi_query($this->link, $query);
          
          if ($result) {
              if (mysqli_multi_query($this->link, "select @oId")) {
                  if ($idResult = mysqli_store_result($this->link)) {
                      $row = mysqli_fetch_row($idResult);
                      $id = $row[0];
                      $tsnList = $this->getObjects();
                      for ($i = 0; $i < count($tsnList); $i++) {
                          echo "tsn object is " . $tsnList[$i];
                          $colObj = $tsnList[$i];
                          $objectId = $colObj->getObjectId();
                          echo "object Id is " . $objectId;
                          if ($objectId > -1) {
                              //$colObj->setObjectTypeId("TaxonConcept");
                              //$colObj->setObjectTitle("Tsn on the Otu list");
                              //$colObj ->setObjectRole("tsn");
                              
                              $query = "CALL `CollectionObjectsInsertK`(" . $id . "," . $objectId . "," . $i . ",\"";
                              $query .= $colObj->getObjectTypeId() . "\",\"" . $colObj->getObjectTitle() . "\",\"";
                              $query .= $colObj->getObjectRole() . "\")";
                              
                              echo $query;
                              
                              if (mysqli_multi_query($this->link, $query))
                                  printf("New otu added successfully");
                          }
                      }
                  } else
                      echo "error is " . mysqli_error($this->link);
              } else
                  echo "error is " . mysqli_error($this->link);
          } else
              echo "error is " . mysqli_error($this->link);
      }
      
      //function to delete created otu if action canceled
      function deleteOtuFromDB() {
          $query = "Delete from CollectionObjects where collectionId=" . $this->getId();
          $result = mysqli_query($this->link, $query);
          
          $this->deleteObjectFromTable("Otu", $this->getId());
          $query = "Delete from Taxa where boId=" . $this->getId();
          $result = mysqli_query($this->link, $query);
          $this->deleteObjectFromTable("BaseObject", $this->getId());
      }
      //end of function deleteOtuFromDB
  }
  //end of class Otu
?>
