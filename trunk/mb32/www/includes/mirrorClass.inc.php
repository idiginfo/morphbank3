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

include_once('HTML/Progress2.php');

class mirrorProgressBar extends HTML_Progress2
{
  /* Extends HTML_Progress2 so that a progress bar display class
     is available for mirrorClass.php to output progress bar,
     thumbnail flickers.
  */

  function mirrorProgressBar()
  {
    /* Constructor for mirrorProgressBar, sets defaults for labels
       that are referenced in Listener object. Any modification
       to label attributes should be reflected in the notify()
       function.
    */
    parent::HTML_Progress2();
    $cell_attributes = array("active-color" => "#000066", "width" => "18");
    $this->setCellAttributes($cell_attributes);
    $this->setCellCount(20);
    $this->addLabel(HTML_PROGRESS2_LABEL_TEXT, 'txt1', 'Mirroring... ');
    $this->addLabel(HTML_PROGRESS2_LABEL_STEP, 'stp1');
    $this->setLabelAttributes('stp1', array(
					    'valign' => 'bottom',
					    'color'  => 'blue',
					    'align'  => 'right'
					    ));
    $this->addLabel(HTML_PROGRESS2_LABEL_TEXT, 'txt2', 'Filename');
    $this->setLabelAttributes('txt2', array(
					    'align' => 'left',
					    'valign' => 'bottom'
					    ));
  }

  function getImageUpdateScript()
  {
    /* Extension function that will output all necessary Javascript
       for Progress Bar and Thumbnail Image progress. 
    */

    echo $this->getScript(false);
    echo "<script type=\"text/javascript\">\n";
    echo "  function updateImage(pImageURL){\n";
    echo "    var image = new Image();\n";
    echo "    image.src = pImageURL;\n";
    echo "    document.getElementById('updateImage').src = image.src;\n";
    echo "}\n";
    echo "</script>\n";
  }

  function imageDisplay()
  {
    /* Place holder container for Progress Bar and Image Thumbnail progress.
       The Javascript acts on this specific Elements and any change needs to be
       reflected in the Javascript as well. 
    */
    echo "<table>";
    echo "<tr>";
    echo "<td>";
    echo "<div id='image'><img id='updateImage' name='updateImage' src=''></div>";
    echo "</td>";
    echo "<td>";
    $this->display();
    echo "</td>";
    echo "</tr>";
    echo "</table>";
  }

  function notify(&$notification)
  {
    /* Listener function tailored to the mirrorClass. Notification
       names are case sensitive. 
     */
    $notifyName = $notification->getNotificationName();
 
    if (strcasecmp($notifyName, 'onTick') == 0) {
      $notifyInfo = $notification->getNotificationInfo();
      $this->setLabelAttributes('txt2', array('value' => $notifyInfo[1]));
      echo "<script type=\"text/javascript\">updateImage(\"$notifyInfo[2]\");</script>";
      $this->moveNext();
    }    
    if (strcasecmp($notifyName, 'onFinish') == 0) {
      $notifyInfo = $notification->getNotificationInfo();
      $this->setLabelAttributes('txt1', array('value' => 'Finished'));
    }    
  }
}
?>

<?php
include_once('PEAR.php');
include_once('HTTP.php');
include_once('Net/FTP.php');
include_once('Log.php');
include_once('imageFunctions.php');

class mirror
{
  private $serverName;
  private $basePath;
  private $username;
  private $port;
  private $passwd;
  private $index;
  private $imageIdList = array();
  private $fileList = array();
  private $allImageTypes = array("tiff", "thumbs", "jpg", "jpeg");
  private $imageExtensions = array("tiff" => ".tif", "thumbs" => ".jpg", "jpg" => ".jpg", "jpeg" => ".jpeg");

  public function __construct($serverName, $basePath, $port, $username, $passwd)
  {
    /* General Constructor */
    $this->serverName = $serverName;
    $this->basePath = $basePath;
    $this->port = $port;
    $this->username = $username;
    $this->passwd = $passwd;
  }

  public function __destruct(){}

  public function set($serverName, $basePath, $port, $username, $passwd)
  {
    /* Override constructor to set private vars
     */
    $this->serverName = $serverName;
    $this->basePath = $basePath;
    $this->port = $port;
    $this->username = $username;
    $this->passwd = $passwd;
  }

  public function addFile($filepath, $thumbnail, $filetype, $database_id)
  {
    /* Adds an arbitrary file to a list of files queued for mirroring
       array_push() uses more overhead, so simply use PHP for appending a single element. 
    */
    $this->fileList[] = array(filepath => $filepath, thumbnail => $thumbnail, filetype => $filetype, database_id => $database_id);
  }

  public function removeFile($filepath)
  {
    /* Removes a file from the list of file queued for mirroring 
     */ 
    foreach ($this->fileList as $key => $row) 
      {
	if(in_array($filepath, $row))
	  {
	    unset($this->fileList[$key]);
	  }
      }
  }
  
  public function addFileList($newFileList)
  {
    /* Adds an array of files to a list of files queued for mirroring 
     */
    $this->fileList[] = array_merge($this->fileList, $newFileList);
  }

  public function fileListSize()
  {
    /* Return size of array.
     */
    return count($this->fileList);
  }

  public function printFileList(){
    /* Function intended for debugging */
    foreach ($this->fileList as $file)
      {
	echo $file['filepath'] . "<br>";
	echo $file['thumbnail'] . "<br>";
	echo $file['filetype'] . "<br>";
	echo $file['database_id'] . "<p>";
      }
  }

  public function addImage($imageId)
  {
    /* Adds an imageId to a list of imageIds queued for mirroring
       array_push() uses more overhead, so simply use PHP for appending a single element. 
    */
    $filetype = "image";
    foreach ($this->allImageTypes as $imageType)
      {
	/* Create full paths for local image(s) and thumbnail 
	 */
	$localImagePath = $imageType . "/" . getImagePathOld($imageId) . $this->imageExtensions[$imageType];
	$thumbnailPath = "thumbs/" . getImagePathOld($imageId) . ".jpg";
	$this->addFile($localImagePath, $thumbnailPath, $filetype, $imageId);
      }
  }

  public function removeImage($imageId)
  {
    /* Removes an imageId from the list of files queued for mirroring 
     */ 
    foreach ($this->allImageTypes as $imageType)
      {
	/* Create full paths for local image(s) and thumbnail 
	 */
	$localImagePath = $imageType . "/" . getImagePathOld($imageId) . $this->imageExtensions[$imageType];
	$this->removeFile($localImagePath);
      }
  }

  public function checkExists($url)
  {
    /* Checks if given URL is accessible (i.e. response code 200) 
       If not accessible, returns a PEAR exception with message   
       containing response code 
     */
    $response = HTTP::head($url);
    if (PEAR::isError($response)){
      die($response->getMessage());
    }
    if ($response['response_code'] == 200) {
      return TRUE;
    }
    else {
      $message = "HTTP response code " . $response['response_code'] . " for " . $url;
      $exception = PEAR::raiseError($message);
      return $exception;
    }
  }

  public function testMirror()
  {
    /* Tests mirror connectivity 
     */
    /* Create PEAR FTP Object 
     */
    $ftp = new Net_FTP($this->serverName, $this->port);
    if (PEAR::isError($error = @$ftp)){
      die($error->getMessage());
    }
    /* Create FTP Connection 
     */
    if (PEAR::isError($error = @$ftp->connect())){
      die($error->getMessage());
    }
    /* Login FTP Connection 
     */
    if (PEAR::isError($error = @$ftp->login($this->username, $this->passwd))){
      die($error->getMessage());
    }
    /* Change to baseDirectory 
     */
    if (PEAR::isError($error = @$ftp->cd($this->basePath))){
      die($error->getMessage());
    }
    if (PEAR::isError($ftp->disconnect())){
      die($ftp->getMessage());
    }
  }

  public function createMirror($overwrite, &$bar)
  {
    /* Creates mirrored image set from list of files 
     */
    $count = 0;
    $thumbnailPath = "";

    /* Get current execution time limit and set new time limit to unlimited to allow
       script to transfer LARGE sets of data files without timing out. 
     */
    $php_max_exec_time_limit = ini_get('max_execution_time');
    set_time_limit(0);

    $log_conf = array('mode' => 0666, 'timeFormat' => '%X %x');
    $logger = &Log::factory('file', '/data/www.morphbank.net/log/morphbank-error.log', 'MIRROR', $log_conf);

    $dispatch =& Event_Dispatcher::getInstance('ProgressMeter');

    /* Create PEAR FTP Object 
     */
    $ftp = new Net_FTP($this->serverName, $this->port);
    if (PEAR::isError($error = @$ftp)){
      $logger->log($error->getMessage());
      die($error->getMessage());
    }
    /* Create FTP Connection 
     */
    if (PEAR::isError($error = @$ftp->connect())){
      $logger->log($error->getMessage() . " - " . $this->serverName);
      die($error->getMessage());
    }
    /* Login FTP Connection 
     */
    if (PEAR::isError($error = @$ftp->login($this->username, $this->passwd))){
      $logger->log($error->getMessage());
      die($error->getMessage());
    }
    /* Change to baseDirectory 
     */
    if (PEAR::isError($error = @$ftp->cd($this->basePath))){
      $logger->log($error->getMessage());
      die($error->getMessage());
    }
    /* For each file in the list 
     */
    foreach ($this->fileList as $fileObject)
      {
	/* Create absolute paths for local image and remote destination 
	 */
	global $config;
	$remoteImagePath = $fileObject['filepath'];
	$fullLocalPath = $config->testRootPath . $fileObject['filepath'];
	
	/* Strip off file name in order to recursively build FTP directory, then implode back to a string 
	 */
	$dirs =  explode('/', $fileObject['filepath']);
	$filename = array_pop($dirs);
	$remoteDirPath = implode("/", $dirs);
	/* This is used to compensate for image specific database updates
           to the MirrorInfo table. The mirrorclass has been extended to 
           allow for arbitrary file type transfers, but this is not yet
           reflected in the database schema or the getImage functions.
	 */
	$imageId = $fileObject['database_id'];
	
	/* Recursively create directory, if it fails print error messsage and continue 
           Should gracefully fail if it rebuilding a directory that already exists 
	*/
	if (PEAR::isError($error = @$ftp->mkdir($remoteDirPath, TRUE))){
	  $logger->log($error->getMessage());
	  echo $error->getMessage();
	}
	/* Upload file to directory. If it fails, print error message and continue 
	 */
	if (PEAR::isError($error = @$ftp->put($fullLocalPath, $remoteImagePath, $overwrite, FTP_BINARY))){
	  $logger->log($error->getMessage());
	  echo $error->getMessage();
	}
	/* Update Database 
	 */
	//$serverId = mysqli_fetch_array(executeQuery('SELECT serverId FROM ServerInfo WHERE url = "' .$this->serverName. '";'));
	if (strpos($fileObject['filepath'], "thumb") !== FALSE) {
		$serverId = mysqli_fetch_array(runQuery('SELECT serverId FROM ServerInfo WHERE url = "' .$this->serverName. '";'));
		$mirrorSql = 'INSERT INTO MirrorInfo (serverId, imageId) VALUES (' .$serverId['serverId']. ', ' .$imageId. ');';
		//executeQuery($mirrorSql);
		runQuery($mirrorSql);
	}
	/* Send listener completion event
	 */
	if (strcmp($thumbnailPath, $fileObject['thumbnail']))
	{
	  $thumbnailPath = $config->domain .'/images/' . $fileObject['thumbnail'];
	}
	$dispatch->post($this, 'onTick', array($count, $filename, $thumbnailPath));
	$count++;
      }
    /* Disconnect Database
     */
    if (PEAR::isError($ftp->disconnect())){
      die($ftp->getMessage());
    }
    $dispatch->post($this, 'onFinish', array($count, $fileObject['database_id'], $thumbnailPath));
    /* Reset max execution time limit
     */
    set_time_limit($php_max_exec_time_limit);
  }
}
?>
