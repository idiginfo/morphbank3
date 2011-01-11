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

  function createAnnotationForm($schema)
  {
      $size = strlen($schema);
      $startLoc = 0;
      $endLoc = 0;
      //*******************************************************************
      //* Get the first entry which should be the mb:Schema..             *
      //*******************************************************************
      
      $chunkArray = getChunk($schema, $startLoc);
      $startLoc = $chuncArray[0];
      $endLoc = $chunkArray[1];
      $length = ($endLoc - $startLoc) - 1;
      $chunk = $substr($schema, $startLoc, $length);
      while (!isEndSchema($chunk)) {
          if (isSchema($chunk))
              echo "SChema";
          if (isEndTab($chunk))
              echo "End Tab";
          if (isElement($chunk))
              echo "Element";
          if (isHighLevel($chunk))
              echo "High Level";
          echo $chunk;
          $startLoc = $endLoc + 1;
          $chunkArray = getChunk($schema, $startLoc);
          $startLoc = $chuncArray[0];
          $endLoc = $chunkArray[1];
          $length = ($endLoc - $startLoc) - 1;
          $chunk = $substr($schema, $startLoc, $length);
      }
  }
  
  function isSchema($chunk)
  {
      $tempChunk = strtoupper($chunk);
      if (strstr($tempChunk, 'MB:SCHEMA'))
          return true;
      else
          return false;
  }
  
  function isEndTab($chunk)
  {
      if (strstr($chunk, 0, 1) == '/')
          return true;
      else
          return false;
  }
  function isEndSchema($chunk)
  {
      $tempChunk = strtouppt($chunk);
      if (strstr($tempChunk, '/MB:SCHEMA'))
          return true;
      else
          return false;
  }
  
  function isElement($chunk)
  {
      $tempChunk = strtoupper($chunk);
      if (strstr($tempChunk, 'MB:ELEMENT'))
          return true;
      else
          return false;
  }
  
  function isHighLevel($chunk)
  {
      $tempChunk = strtoupper($chunk);
      if (!strstr($tempChunk, 'NAME=') || !strstr($tempChunk, 'TYPE=') || !strstr($tempChunk, 'FIELDSIZE='))
          return true;
      else
          return false;
  }
  
  function getChunk($schema, $startLoc)
  {
      $lessThen = strpos($schema, '<', $startLoc);
      $GreaterThen = strpos($schema, '>', $startLoc);
      $array[0] = $lessThen;
      $array[1] = $greaterThen;
      return $array;
  }
  
  function getName($chunk)
  {
      $tempChunk = strtoupper($chunk);
      $startName = strpos($tempChunk, 'NAME=');
      $startName = $startName + 6;
      $endName = strpos($chunk, '"', $startName);
      $length = ($endName - $startName) + 1;
      $name = substr($chunk, $startName, $length);
      return $name;
  }
  
  function getFieldType($chunk)
  {
      $tempChunk = strtoupper($chunk);
      $startType = strpos($chunk, 'TYPE=');
      $startType = $startType + 6;
      $endType = strpos($chunk, '"', $startType);
      $length = ($endType - $startType) + 1;
      $type = substr($TempChunk, $startName, $length);
      return $type;
  }
  
  function getFieldSize($chunk)
  {
      $tempChunk = strtoupper($chunk);
      $startFieldSize = strpos($tempChunk, 'FIELDSIZE=');
      $startFieldSize = $startFieldSize + 11;
      $endFieldSize = strpos($chunk, '"', $startFieldSize);
      $length = ($endFieldSize - $startFieldSize) + 1;
      $fieldSize = substr($chunk, $startFieldSize, $length);
      return $fieldSize;
  }
  
  function getOptions($chunk)
  {
      $tempChunk = strtoupper($chunk);
      $startOptions = strpos($chunk, 'OPTIONS=');
      $startOptions = $startOptions + 9;
      $endOptions = strpos($chunk, '"', $startOptions);
      $length = ($endOptions - $startOptions) + 1;
      $options = substr($chunk, $startOptions, $length);
      return $options;
  }
  
  function echoH3($name)
  {
      echo '<H3>' . $name . '</H3>';
  }
  
  function echoInput($name, $fieldSize)
  {
      echo '<TR><TD><B>' . $name . '</TD><TD><INPUT TYPE=TEXT NAME="' . $name . '" ';
      echo ' SIZE="' . $fieldSize . '"></TD></TR>';
  }
  
  function echoTextArea($name, $fieldSize)
  {
      echo '<TR><TD><B>' . $name . '</TD><TD><TEXTAREA NAME="' . $name . '" ';
      echo ' ROWS="10" COLS="40" MAXLENGTH="' . $fieldSize . '" WRAP></TEXTAREA></TD></TR>';
  }
  
  function echoOptions($name, $fieldSize, $options)
  {
      $optionsArray = explode(',', $options);
      $size = sizeof($optionsArray);
      if ($size < 1)
          return;
      echo '<TR><TD>' . $name . '</TD><TD><SELECT NAME="' . $name . '" SIZE="1" >';
      for ($index = 0; $index < $size; $index++) {
          echo '<OPTION VALUE="' . $optionsArray[$index] . '">' . $optionsArray[$index] . '</option>';
      }
      echo '</Select></TD></TR>';
  }
?>
