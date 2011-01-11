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

<div class="mainGenericContainer" style="width:700px">
<h1><?php echo $frmTitle ?></h1>
<?php getMsg($_GET['code']); ?>
<form id="frmNews" action="<?php echo $frmAction ?>" method="post"	enctype="multipart/form-data">
<table border="0">
	<tr>
		<td><b>Title:<span style="color: red">*</span></b></td>
		<td><input type="text" name="title" value="<?php echo $title ?> size="54" /></td>
	</tr>
	<tr>
		<td><b>News Script:<span style="color: red">*</span></b></td>
		<td><textarea rows="8" cols="50" name="body"><?php echo $text ?></textarea></td>
	</tr>
	<tr>
		<td><b>Image to be uploaded for this news:</b></td>
		<td><input type="file" name="imageFile" size="54" /></td>
	</tr>
	<tr>
		<td><b>Image Text:</b></td>
		<td><input type="text" size="54" name="imageText" value="<?php echo $imgText ?> title="Text to be displayed with the image" /></td>
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
