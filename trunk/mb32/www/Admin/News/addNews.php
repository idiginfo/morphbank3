<?php
//  Author: David A. Gaitros
// Date:  9/16/2005
// Description:  PhP script to display the News add screen. The form will automatically call
//               the addNewscript.php upon pressing of the submit button.                                                                    //
//Modified : 1/26/2006: Path names and static file names.
//Modified : 11/07/2006 by Karolina Maneva-Jakimoska to make the image for News be uploaded as
//             the news is created

include_once('head.inc.php');
include_once('Admin/admin.functions.php');



checkIfLogged();

// Check authorization

if (!isAdministrator()) {
	header("location: /Admin/User/edit");
}

$title = 'Add News';
initHtml($title, null, null);
echoHead(false, $title);
?>
<div class="mainGenericContainer" style="width:700px">
<h1>Add News</h1>
<?php getMsg($_GET['code']); ?>
<form id="frmNews" action="commitNews.php" method="post"	enctype="multipart/form-data">
<table border="0">
	<tr>
		<td><b>Title:<span style="color: red">*</span></b></td>
		<td><input type="text" name="title" size="54" /></td>
	</tr>
	<tr>
		<td><b>News Script:<span style="color: red">*</span></b></td>
		<td><textarea rows="8" cols="50" name="body"></textarea></td>
	</tr>
	<tr>
		<td><b>Image to be uploaded for this news:</b></td>
		<td><input type="file" name="imageFile" size="54" /></td>
	</tr>
	<tr>
		<td><b>Image Text:</b></td>
		<td><input type="text" size="54" name="imageText" title="Text to be displayed with the image" /></td>
	</tr>
</table>
<br />
<span style='font-size: 12; color: red'><b>* - Required</b></span>
<table align="right">
	<tr>
		<td><input type="submit" class="button smallButton" value="Submit" /></td>
		<td><a href="/Admin/News/" class="button smallButton" title="Return to News"><div>Return</div></a></td>
	</tr>
</table>
</form>
</div>
<?php
finishHtml();

function getMsg($code) {
	if (empty($code)) return;
	switch($code) {
		case 1:
			echo "<h3>You have successfully added the News item with id <a href=\"/?id=$id\">$id</a></h3>\n";
		break;
		default:
		break;
	}
	return;
}
?>

