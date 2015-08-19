<div class="mainGenericContainer" >
<h1><?php echo $frmTitle ?></h1>
<form id="frmNews" action="/Admin/News/<?php echo $postFile ?>" method="post"	enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php echo $id ?>" />
<table border="0">
	<tr>
		<td><b>Title:<span style="color: red">*</span></b></td>
		<td><input type="text" name="title" size="54" value="<?php echo $row['title'] ?>" /></td>
	</tr>
	<tr>
		<td valign="top"><b>News Text:<span style="color: red">*</span></b></td>
		<td><textarea rows="15" cols="50" name="body"><?php echo $row['body'] ?></textarea></td>
	</tr>
  <?php if (!empty($row['image']) && $action == 'edit'): ?>
  <tr>
    <td></td>
		<td>
      <img src="<?php echo $image ?>" name="image" width="150" />
    </td>
	</tr>
  <? endif; ?>
	<tr>
		<td><b>Image:</b></td>
		<td><input type="file" name="imageFile" size="54" /></td>
	</tr>
	<tr>
		<td><b>Image Text:</b></td>
		<td><input type="text" size="54" name="imageText" value="<?php echo $row['imagetext'] ?>" title="Text to be displayed with the image" /></td>
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
