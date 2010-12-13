<?php
/**
 * File name: mainNews.php
 * @package Morphbank2
 * @subpackage Admin News
 */

include_once('imageFunctions.php');

function mainNews() {
	global $objInfo;
	global $config;

	echoJavaScript();
	$link = Adminlogin();

	echo '<form method="post" action ="index.php" name="News" enctype="multipart/form-data">';

	/******************************************************************
	 *  Store the current row if it was sent by a subsequent call to   *
	 *  this function.  This tells us where in the set of usernames    *
	 *  the current record to be displayed.  If no row number was sent *
	 *  either by get or post, we set it to 0.                         *
	 *******************************************************************/

	if (isset($_GET['row'])) {
		$rowNum = $_GET['row'];
	} elseif ($rowNum == null) {
		if ($_POST['row'] != null) {
			$rowNum = $_POST['row'];
		} else {
			$rowNum = 0;
		}
	}


	/******************************************************************
	 * Before we build the web page we must determine if there was     *
	 * a previous call to the web page and an update, or add           *
	 * was performed.  If an update or add was requested, we must take *
	 * those actions.                                                  *
	 ******************************************************************/

	if (isset($_GET['id']) && isset($_GET['imageid'])) {
		$id = $_GET['id'];
	}

	/*********************************************************************
	 *  If the update button has been selected on the previous call to    *
	 *  this screen then it is assumed that modifications to the database *
	 *  record have been made and the record should be updated. Note,     *
	 *  the "id" of the record cannot be changed because it is the        *
	 *  primary key.  Also,  when forming the character string that       *
	 *  will be the sql update line, we must include a special condition  *
	 *  that recognizes that the first element in the list of columns     *
	 *  to be updated does not contain a comma.                           *
	 *                                                                    *
	 *********************************************************************/

	if ($_POST['id'] != null) {
		if (isset($_FILES['new_image']) && ($_FILES['new_image']['name'] > "")) {
			$new_image = $_FILES['new_image']['name'];
			//if($image=="")
			$image = $new_image;
			//$imagename = substr($new_image,0,strpos($new_image,"."));
			$imagesize = $_FILES['new_image']['size'];
			$imagetype = $_FILES['new_image']['type'];
			$tmpFile = $_FILES['new_image']['tmp_name'];
			move_uploaded_file($tmpFile, $config->newsImagePath . $new_image);
			exec("chmod 755 " . $config->newsImagePath . $new_image);
		} else {
			$image = $_POST['image'];
		}

		$id = $_POST['id'];
		$query = "Update News set ";
		$queryh = "INSERT INTO History (id,userId,groupId,dateModified,modifiedFrom,modifiedTo,tableName) VALUES(";
		$queryh .= $id . "," . $objInfo->getUserId() . "," . $objInfo->getUserGroupId() . ",NOW(),'";
		$modifiedFrom = "";
		$modifiedTo = "";
		$flag = false;
		/*********************************************************************
		 * The Contributor of the news as well as the initial date and        *
		 * not modifiable.  Once a News record has been added, these          *
		 * baseObject fields cannot be changed.                               *
		 ********************************************************************/
		$db = connect(); // prepare for quoting strings

		// update title
		$flag = $flag && updateFields('title', $_POST['title_old'], $_POST['title']);

		// update body
		$temp = mysqli_query($link, "Select body from News where id=" . $id);
		$temp_array = mysqli_fetch_array($temp);
		$flag = $flag && updateFields('body', $temp_array, $_POST['body']);

		// update image file name
		$flag = $flag && updateFields('image', $_POST['image_old'], $image);

		// update imagetext
		$flag = $flag && updateFields('imagetext', $_POST['imagetext_old'], $_POST['imagetext']);

		if (strrpos($query, ",") == (strlen($query) - 1)){
			$query = substr($query, 0, strlen($query) - 1);
		}

		$query .= " where id = " . $_POST['id'];
		//echo $query;
		if ($flag) {
			$results = mysqli_query($link, $query);
			if (!results) {
				echo mysqli_error($link) . "<br/>";
			} else {
				$queryh .= $modifiedFrom . "','" . $modifiedTo . "','News')";
				//echo $queryh;
				$result = mysqli_query($link, $queryh);
				if (!$result)
				mysqli_error($link);
				else
				echo '<span style="color:#17256B"><b>News information updated successfully</b></span>';
			}
		} else {

			echo '<span style="color:#17256B"><b>No information needs to be updated at this time</b></span>';
		}
	}

	/*******************************************************************
	 * Select all News records.                                         *
	 *******************************************************************/
	$results = mysqli_query($link, 'select * from News');
	if ($results)
	$numRows = mysqli_num_rows($results);
	else
	echo "No results returned";
	/*********************************************************************
	 * for a new or last items, we want to set the current row to be the *
	 * last one in the database ordered by last_name.                    *
	 ********************************************************************/
	for ($index = 0; $index < $numRows; $index++) {
		$row = mysqli_fetch_array($results);
		$NewsData['id'][$index] = $row['id'];
		$NewsData['title'][$index] = $row['title'];
		$NewsData['image'][$index] = $row['image'];
		$NewsData['imageText'][$index] = $row['imageText'];
		$NewsData['body'][$index] = $row['body'];
		$BOresults = mysqli_query($link, 'SELECT * FROM BaseObject where id=' . $row['id']);
		$BOrow = mysqli_fetch_array($BOresults);
		$NewsData['dateCreated'][$index] = $BOrow['dateCreated'];
		$NewsData['contributor'][$index] = $BOrow['userId'];
		$Userresults = mysqli_query($link, 'SELECT * FROM User where id=' . $BOrow['userId']);
		$Userrow = mysqli_fetch_array($Userresults);
		$NewsData['name'][$index] = $Userrow['name'];
	}

	if ($_GET['new'] == '1' || $_GET['last'] == 1)
	$rowNum = $numRows - 1;
	if ($NewsData['image'][$rowNum] != null && $NewsData['image'][$rowNum] != "") {
		$imageloc = '/images/newsImages/' . $NewsData['image'][$rowNum];
		//echo "image location is ".$imageloc;
		$news_text = trim($NewsData['body'][$rowNum]);
		$size = getSafeImageSize($imageloc);
	} else
	$size = 0;
	$id = $NewsData['id'][$rowNum];

	echo '<input type="hidden" name="image" value="' . $NewsData['image'][$rowNum] . '" />
              <input type="hidden" name="image_old" value="' . $NewsData['image'][$rowNum] . '"/>';

	echo '<table border="0">';
	echo '<tr>
  <td><b>Contributor:</b></td>
  <td>' . $NewsData['name'][$rowNum] . '</td>
   <td><b>Date Added:</b>' . $NewsData['dateCreated'][$rowNum] . '</td></tr>
        <tr>
  <td><b>Title:</b></td>
  <td><input type="text" name="title" size="45" value="' . $NewsData['title'][$rowNum] . '" />
        <input type="hidden" name="title_old" value="' . htmlspecialchars($NewsData['title'][$rowNum]) . '"></td>
  <td rowspan="8" colspan="1">';

	if ($size != 0) {
		echo '<img src="' . $imageloc . '" name="image" width="150" ></td></tr>';
	} else {

		echo '<img src="/style/webImages/defaultNews.png" height="145" width="210" alt="IMAGE NOT AVAILABLE"/>';
	}

	echo '<tr>
        <td><b>News:</b></td>
        <td><textarea name="body" rows="9" cols="44" maxlength="512" wrap>' . $NewsData['body'][$rowNum] . '</textarea>
            <input type="hidden" name="body_old" value="' . htmlspecialchars($NewsData['body'][$rowNum]) . '" />
        </td></tr>';
	//$body_old=trim($NewsData['body'][$rowNum]);
	echo '<tr>
  <td><b>Image Text</b></td>
  <td><input type="text" name="imagetext" size="40" value="' . $NewsData['imageText'][$rowNum] . '" />
            <input type="hidden" name="imagetext_old" value="' . htmlspecialchars($NewsData['imageText'][$rowNum]) . '" /></td></tr>
        <tr><td><b>Update image: </b></td><td><input type="file" name="new_image" size="40" /></td>
  </tr>
  <tr>
  <td><input type="hidden" name="id" value="' . $NewsData['id'][$rowNum] . '" /></td>
  <td><input type="hidden" name="row" value="' . $rowNum . '" /></td>
  </tr>
  </table>';


	//*********************************************************************
	//* print out the navigation (next, prev, first, last)                *
	//*********************************************************************
	printNav($rowNum, $numRows);

	//*********************************************************************
	//* print out the command buttons                                     *
	//*********************************************************************
	printButtons($id);
	echo '</form>';
}

function deleteNews($id) {
	$db = connect();
	if (empty($id)) return;
	
	$sql = "select image from News where id = ?";
	$image = $db->getOne($sql, null, array($id));
	isMdb2Error($image, 'Selecting image from News');
	
	if ($image) {
		exec("rm " . $imagedelete['image']);
	}
	
	$sql = "delete from News where id = ? limit 1";
	// Once you have a valid MDB2 object named $mdb2...
$table_name   = 'user';
$table_fields = array('name', 'country');
$table_values = array('Bob', 'USA');
$types = array('text', 'text');

$mdb2->loadModule('Extended');
$sth = $mdb2->extended->autoPrepare($table_name, null,
                        MDB2_AUTOQUERY_DELETE, 'id = 1', $types);
	
	
	$query = 'delete from News where id=' . $deletednews;
	//echo $query;
	$delresults = mysqli_query($link, $query);
	if ($delresults) {
		$BOdelete = mysqli_query($link, 'delete from BaseObject where id=' . $deletednews);
		if (!$BOdelete)
		mysqli_error($link);
	} else
	mysqli_error($link);
}


function printButtons($id)
{
	global  $config;

	echo '<table align="right">
                 <tr>
                    <td>
                       <a href="javascript: document.forms[0].submit();" class="button smallButton" title="Update News">
                       <div>Update</div></a>
                     </td>
               <td>
                        <a href="' . $config->domain . 'Admin/News/addNews.php" class="button smallButton" title="Click to go to add news form">
                           <div>Add news</div>
                        </a>
                     </td>  
               <td>
                        <a href="' . $config->domain . 'Admin/News/?delete=' . $id . '" class="button largeButton" title="Click to delete the news">
                          <div>Delete news</div></a>
                     </td>
               <td>
                        <a href="' . $config->domain . 'Admin/" class="button smallButton" title="Click to return to Admin"><div>Return</div></a>
                     </td>
                </tr>
       </table>';
}

/**
 * Add to sql variables $query, $modifiedFrom and $modifiedTo
 * to create updates for this field
 * @param $field
 * @param $oldVal
 * @param $newVal
 * @return boolean true if variables were changed
 */
function updateFields($field, $oldVal, $newVal){
	global $db, $query, $modifiedFrom, $modifiedTo;
	$oldVal = trim($oldVal);
	$newVal = trim($newVal);
	$query .= $field ."=" . $db->quote($newVal,'text') . ",";
	$modifiedFrom .= $field . ": " . $db->quote($oldVal,'text',false);
	$modifiedTo .= $field . ": " . $db->quote($newVal,'text',false);
	return true;
}

function echoJavaScript()
{
	echo '<script type ="text/javascript">
  function openImage(){
       var location = "selectimage.php";
          NEWS = window.open(location,"NEWS", "location = 1, directories=0,dependent=1,menubar=0,top=20,left=20,width=800,height=800,scrollbars=1,resizable=1");
           if (window.focus){                        
                 NEWS.focus();
            }
        }


  function updateimage(value){
                document.forms[0].image.value=value;                
        }

</script>';
}
?>
