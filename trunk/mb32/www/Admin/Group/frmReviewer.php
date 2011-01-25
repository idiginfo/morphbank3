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
?>

<form id="frmReviewer" method="post" action="commitReviewer.php">
<input type="hidden" name="id" value="<?php echo $id ?>" />
<input type="hidden" name="returnUrl" value="<?php echo $returnUrl ?>" />"
<h3>Information based on the group's name and privileges</h3>
<table width="400px">
	<tr>
		<td><b>Account name</b></td>
		<td><input type="text" size="30" name="reviewer" value="Reviewer"
			readonly="readonly" /> <input type="hidden" name="last_Name"
			value="Reviewer" /></td>
	</tr>
	<tr>
		<td><b>Account created for group</b></td>
		<td><input type="text" size="30" name="group"
			value="<?php echo $grpName ?>" readonly="readonly" /> <input
			type="hidden" name="first_Name" value="<?php echo $grpName ?>" /></td>
	</tr>
</table>
<br />
<br />
<h3>Enter user name and password for reviewer</h3>
<p>Please keep notes on the user name and password so you can pass the 
information on to the reviewer.</p>
<div class="frmError"></div>
<table width="400px">
	<tr>
		<td><b>User name <span class="req">*</span></b></td>
		<td><input type="text" size="30" name="uin" /></td>
	</tr>
	<tr>
		<td><b>Password <span class="req">*</span></b></td>
		<td><input type="text" size="30" id="pin" name="pin" /></td>
	</tr>
	<tr>
		<td><b>Re-enter password <span class="req">*</span></b></td>
		<td><input type="text" size="30" name="pin_re" /></td>
	</tr>
</table>
<br />
<span class="req">* - Required field</span><br />
<table align="right">
	<tr>
		<td><input type="submit" class="button smallButton" title="Click to save the changes" value="Submit" /></td>
		<td><a href="<?php echo $returnUrl ?>"
			class="button smallButton"
			title="Click to return to the previous page">
		<div>Return</div>
		</a></td>
	</tr>
</table>
</form>
