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
  // @date July 17th 2007
  // 
  
  class Matrix extends Collection {
      var $id;
      var $numRows;
      var $numChars;
      var $matrixCells;
      
      function Matrix($link, $objId) {
          parent::Collection($link, $objId);
          $this->link = $link;
          $this->id = $objId;
          if ($objId != null)
              $this->LoadMatrixFromDB($objId);
      }
      
      function getId() {
          return $this->id;
      }
      
      function setId($id) {
          $this->id = $id;
      }
      
      function getNumRows() {
          return $this->numRows;
      }
      
      function setNumRows($numRows) {
          $this->numRows = $numRows;
      }
      
      function getNumChars() {
          return $this->numChars;
      }
      
      function setNumChars($numChars) {
          $this->numChars = $numChars;
      }
      
      function getMatrixCells() {
          return $this->matrixCells;
      }
      
      function setMatrixCells($matrixCells) {
          for ($i = 0; $i < count($matrixCells); $i++) {
              $this->matrixCells[$i] = $matrixCells[$i];
          }
      }
      
      function getRows() {
          return $this->getObjectsByRole("row");
      }
      
      function getColumns() {
          return $this->getObjectsByRole("column");
      }
      
      function LoadMatrixFromDB($id) {
          $query = "select BaseObject.*, Matrix.numRows, Matrix.numChars from Matrix, BaseObject where BaseObject.id=" . $id;
          $query .= " and Matrix.id=" . $id;
          $result = mysqli_query($this->link, $query);
          if (!$result)
              echo "Problems querying the database";
          else {
              
              if (mysqli_num_rows($result) < 1)
                  echo "There is no Matrix with that id in the database";
              else {
                  
                  $row = mysqli_fetch_array($result);
                  $this->setNumRows($row['numRows']);
                  $this->setNumChars($row['numChars']);
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
          //code to load the cells from the database from MatrixCellValue table
          //this will change to incorporate MatrixCell objects!!!
          
          $query = "select * from MatrixCellValue where matrixId=" . $id;
          $result = mysqli_query($this->link, $query);
          if (!$result)
              echo "Problems querying the database";
          else {
              
              $numRows = mysqli_num_rows($result);
              if ($numRows > 0) {
                  for ($i = 0; $i < $numRows; $i++) {
                      $row = mysqli_fetch_array($result);
                      $matrixCells[$i]['id'] = $row['id'];
                      $matrixCells[$i]['matrixId'] = $row['matrixId'];
                      $matrixCells[$i]['row'] = $row['row'];
                      $matrixCells[$i]['col'] = $row['col'];
                      $matrixCells[$i]['value'] = $row['value'];
                  }
                  $this->setMatrixCells($matrixCells);
              }
          }
      }
      
      //function to get the headers for the rows
      //returns an array of Otu objects 
      function getRowHeaders($startCol, $endCol) {
          $allRowHeaders = array_values($this->getRows());
          if ($endCol >= count($allRowHeaders))
              $endCol = count($allRowHeaders);
          $counter = 0;
          for ($i = $startCol; $i < $endCol; $i++) {
              $rowHeaders[$counter] = $allRowHeaders[$i];
              $counter++;
          }
          return $rowHeaders;
      }
      
      //function to get the headers for the columns
      //returns an array of PhyloChar object information
      function getColumnHeaders($startRow, $endRow) {
          $allColHeaders = array_values($this->getColumns());
          
          //add code to add the short title to the collection objects
          
          for ($i = 0; $i < count($allColHeaders); $i++) {
              $objectId = $allColHeaders[$i]->getObjectId();
              
              $query = "Select label as shortTitle, name as title from MbCharacter,BaseObject where ";
              $query .= "BaseObject.id=MbCharacter.id and MbCharacter.id=" . $objectId;
              
              //get the result and if short title set the shortTitle 
              //if not take the first 4 characters from the long one and make that a short title
              $result = mysqli_query($this->link, $query);
              $row = mysqli_fetch_array($result);
              
              if ($row['shortTitle'] != null && $row['shortTitle'] != "")
                  $allColHeaders[$i]->setShortTitle($row['shortTitle']);
              else
                  $allColHeaders[$i]->setShortTitle(substr($row['title'], 0, 4));
          }
          
          if ($endRow >= count($allColHeaders))
              $endRow = count($allColHeaders);
          $counter = 0;
          for ($i = $startRow; $i < $endRow; $i++) {
              $colHeaders[$counter] = $allColHeaders[$i];
              $counter++;
          }
          return $colHeaders;
      }
      
      //function to get the cellStructure
      //returns a two dimensional array of cellValues
      function getCellStructure($startRow, $startCol, $endRow, $endCol) {
          $matrix = $this->getMatrixCells();
          $counter = 0;
          
          for ($i = $startRow * $this->numChars; $i < $endRow * $this->numChars; $i++) {
              if (($matrix[$i]['col'] > $startCol) && ($matrix[$i]['col'] < ($endCol + 1))) {
                  $newMatrix[$counter] = $matrix[$i];
                  $counter++;
              }
          }
          return $newMatrix;
      }
      
      //function that creates data structure (array) to display part/whole matrix
      function getMatrixObjects($startX, $startY, $numOfRows, $numOfCols) {
          //create first row of the structure to hold the column headers
          $displayMatrix[0] = $this->getColumnHeaders($startX, $startX + $numOfCols);
          
          $cellStructure = $this->getCellStructure($startX, $startY, $startX + $numOfRows, $startY + $numOfCols);
          $rowHeaders = $this->getRowHeaders($startY, $startY + $numOfRows);
          
          //every folowing row starts with row header followed by cells
          for ($i = 0; $i < $numOfRows; $i++) {
              $row[0] = $rowHeaders[$i];
              
              for ($j = 0; $j < $numOfCols; $j++) {
                  $row[$j + 1] = $cellStructure[$j + ($i * $numOfCols)];
              }
              $displayMatrix[$i + 1] = $row;
          }
          return $displayMatrix;
      }
  }
  //end of Matrix
?>
