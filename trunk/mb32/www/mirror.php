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

function getAccessNumList($database, $accessNum){
  
  $link = mysql_pconnect('localhost:3066', 'webuser', 'namaste');
  
  if($database){
    mysql_select_db($database);
    //echo 'database is' .$database . "\n";
  }else{
    mysql_select_db('morphbank2test');
    //echo 'database is morphbank2test';
  }
  
  if($accessNum !=''){
    
    $accessNumRes = mysql_query("SELECT accessNum FROM Image, baseObject WHERE baseObject.id = Image.id and objectTypeId = 'Image' and accessNum > $accessNum and baseObject.dateToPublish < NOW() ORDER BY accessNum ASC;") or die(mysql_error());
    
  }else{
    
    $accessNumRes = mysql_query("SELECT accessNum FROM Image, baseObject WHERE baseObject.id = Image.id and objectTypeId = 'Image' and baseObject.dateToPublish < NOW();") or die(mysql_error());
    
  }
  
  $accessNumList = array();
  
  while ($row = mysql_fetch_array($accessNumRes)){
    
    array_push($accessNumList, $row['accessNum']);
  }
  
  return $accessNumList;
} //end of function accessNumList

function copyToMirror($serverName, $serverPath, $username, $pwd, $database, $accessNum){
  
  require_once 'Net/FTP.php';
  
  $accessNumList = getAccessNumList($database, $accessNum);
  
  $ftp = new Net_FTP($serverName, 21);
  $ftp->connect();
  $ftp->login($username, $pwd);
  
  for($i= 0; $i< count($accessNumList); $i++){
    
    //Get directory.	
    $directory = getDirectory($accessNumList[$i]);
    
    //Set image path.	
    
    $tiff = '/data/images/tiff/' .$directory. '.tif';
    $thumbs = '/data/images/thumbs/' .$directory. '.jpg';
    $jpg = '/data/images/jpg/' .$directory. '.jpg';
    $jpeg = '/data/images/jpeg/' .$directory. '.jpeg';
    
    $servertiff = $serverPath. 'tiff/' .$directory. '.tif';
    $serverthumbs = $serverPath. 'thumbs/' .$directory. '.jpg';
    $serverjpg = $serverPath. 'jpg/' .$directory. '.jpg';
    $serverjpeg = $serverPath. 'jpeg/' .$directory. '.jpeg';
    
    //Server copy
    
    /*
		$tifferror = $ftp->put($tiff, $servertiff, 'false', FTP_BINARY);
                if(PEAR::isError($tifferror)){
		echo 'tifferror: msg='.$tifferror->message;

    */
    
    $tiffError = $ftp->put($tiff, $servertiff, 'false', FTP_BINARY);
    $thumbsError = $ftp->put($thumbs, $serverthumbs, 'false', FTP_BINARY);
    $jpgError = $ftp->put($jpg, $serverjpg, 'false', FTP_BINARY);
    $jpegError = $ftp->put($jpeg, $serverjpeg, 'false', FTP_BINARY);
    
    if(ereg('could not be uploaded', $tiffError->message)){
      checkDirectory($servertiff, $ftp, $tiff);
    }
    if(ereg('could not be uploaded', $thumbsError->message))
      checkDirectory($serverthumbs, $ftp, $thumbs);
    if(ereg('could not be uploaded', $jpgError->message))
      checkDirectory($serverjpg, $ftp, $jpg);
    if(ereg('could not be uploaded', $jpegError->message))
      checkDirectory($serverjpeg, $ftp, $jpeg);
    
    exit;
  }
  $ftp->disconnect();
}

function checkDirectory($remoteDir, $ftp, $localdir){
  
  $dirs = explode('/', $remoteDir);
  
  for($i =0; $i < count($dirs); $i++){
    
    echo $dirs[$i];
    $array = $ftp->ls($dirs[$i], NET_FTP_DIRS_ONLY);
    
    print_r($array);
    //$ftp->mkdir($dir);
    //$ftp->cd();
    
  }
  
}

function imgTags($serverName, $accessNum){
  
}

function getDirectory($accessNum){
  // First ensure image ID is 12 characters and if not, pad left with "0"
  $id_pad = str_pad($accessNum, 12, "0", STR_PAD_LEFT);
  
  $directory = substr($id_pad,0,1);
  
  for ($j = 1; $j < 10; $j ++) {
    $directory .= '/'.substr($id_pad,$j,1);
  }
  
  $directory .= '/'.$accessNum; 
  
  return $directory;
}

copyToMirror('morphbank.scs.fsu.edu', '/home/ftpdev/', 'ftpdev', 'd3vFtp!', 'MB27', 38513);

/*

        $tiff = array();
        $thumbs = array();
        $jpg = array();
        $jpeg = array();

        $serverTiff = array();
        $serverThumbs = array();
        $serverJpg = array();
        $serverJpeg = array();



                array_push($tiff, '/data/images/tiff/' .$directory. '.tif');
                array_push($thumbs, '/data/images/thumbs/' .$directory. '.jpg');
                array_push($jpg, '/data/images/jpg/' .$directory. '.jpg');
                array_push($jpeg = '/data/images/jpeg/' .$directory. '.jpeg');

                array_push($servertiff, '/data/images/tiff/' .$directory. '.tif');
                array_push($serverthumbs, '/data/images/thumbs/' .$directory. '.jpg');
                array_push($serverjpg, '/data/images/jpg/' .$directory. '.jpg');
                array_push($serverjpeg, '/data/images/jpeg/' .$directory. '.jpeg');
*/
?>
